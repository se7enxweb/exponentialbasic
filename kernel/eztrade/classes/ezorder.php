<?php
//
// $Id: ezorder.php 9830 2003-06-03 06:18:07Z jhe $
//
// Definition of eZOrder class
//
// Created on: <28-Sep-2000 16:40:01 bf>
//
// This source file is part of Exponential Basic, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZTrade
//! eZOrder handles orders.
/*!

  \sa eZOrderItem eZOrderOptionValue eZDateTime
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );

// include_once( "eztrade/classes/ezpreorder.php" );
// include_once( "eztrade/classes/ezorderstatustype.php" );
// include_once( "eztrade/classes/ezorderstatus.php" );
// include_once( "eztrade/classes/ezorderitem.php" );
// include_once( "eztrade/classes/ezshippingtype.php" );
// include_once( "eztrade/classes/ezvoucherused.php" );

// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezaddress/classes/ezaddress.php" );


class eZOrder
{
    /*!
      Constructs a new eZOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->IsExported = 0;
        $this->PersonID = 0;
        $this->CompanyID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
	    $ini =& eZINI::instance( 'site.ini' );
	    $this->checkups = $ini->variable( "eZTradeMain", "UPSOFF" );
    }

    /*!
      Stores a order to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->TextPaymentMethod = $db->escapeString( $this->PaymentMethod );
        $comment = $db->escapeString( $this->Comment );

        if ( !isset( $this->ID ) )
        {
            $timeStamp =& (new eZDateTime())->timeStamp( true );
            $db->lock( "eZTrade_Order" );
            $nextID = $db->nextID( "eZTrade_Order", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_Order
                                  (ID,
	     	                       UserID,
		                           ShippingAddressID,
		                           BillingAddressID,
		                           PaymentMethod,
		                           IsExported,
                                   ShippingVAT,
                                   ShippingTypeID,
		                           Date,
		                           IsVATInc,
		                           ShippingCharge,
                                   PersonID,
                                   CompanyID,
                                   Comment )
                                  VALUES
                                  ('$nextID',
		                           '$this->UserID',
		                           '$this->ShippingAddressID',
		                           '$this->BillingAddressID',
		                           '$this->PaymentMethod',
		                           '$this->IsExported',
                                   '$this->ShippingVAT',
                                   '$this->ShippingTypeID',
		                           '$timeStamp',
                                   '$this->IsVATInc',
		                           '$this->ShippingCharge',
                                   '$this->PersonID',
                                   '$this->CompanyID',
                                   '$comment' )" );
            $db->unlock();
			$this->ID = $nextID;

            // store the status
            $statusType = new eZOrderStatusType( );
            $statusType = $statusType->getByName( "intl-order received" );

            $status = new eZOrderStatus();
            $status->setType( $statusType );

            $status->setOrderID( $this->ID );

            $user =& eZUser::currentUser();
//              print( $user->id() );

            $status->setAdmin( $user );
            $status->store();
        }
        else
        {
            $db->query( "UPDATE eZTrade_Order SET
		                         UserID='$this->UserID',
		                         ShippingAddressID='$this->ShippingAddressID',
		                         BillingAddressID='$this->BillingAddressID',
		                         PaymentMethod='$this->PaymentMethod',
		                         IsExported='$this->IsExported',
                                 ShippingVAT='$this->ShippingVAT',
                                 ShippingTypeID='$this->ShippingTypeID',
		                         Date=Date,
		                         ShippingCharge='$this->ShippingCharge',
                                 PersonID='$this->PersonID',
                                 CompanyID='$this->CompanyID',
                                 Comment='$comment'
                                 WHERE ID='$this->ID'
                                 " );

        }

        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Deletes a eZOrder object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $items = $this->items();
        $db->begin();

        if ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }
        $ret[] = $db->query( "DELETE FROM eZTrade_OrderStatus WHERE OrderID='$this->ID'" );
        $ret[] = $db->query( "DELETE FROM eZUser_UserShippingLink WHERE AddressID='$this->ShippingAddressID'" );
        $ret[] = $db->query( "DELETE FROM eZTrade_Order WHERE ID='$this->ID'" );

        eZDB::finish( $ret, $db );

        return true;
    }


    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_Order WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][$db->fieldName( "ID" )];
                $this->UserID =& $cart_array[0][$db->fieldName( "UserID" )];
                $this->ShippingAddressID =& $cart_array[0][$db->fieldName( "ShippingAddressID" )];
                $this->BillingAddressID =& $cart_array[0][$db->fieldName( "BillingAddressID" )];
                $this->ShippingCharge =& $cart_array[0][$db->fieldName( "ShippingCharge" )];
                $this->ShippingVAT =& $cart_array[0][$db->fieldName( "ShippingVAT" )];
                $this->ShippingTypeID =& $cart_array[0][$db->fieldName( "ShippingTypeID" )];
                $this->PaymentMethod =& $cart_array[0][$db->fieldName( "PaymentMethod" )];
                $this->Date =& $cart_array[0][$db->fieldName( "Date" )];
                $this->IsVATInc =& $cart_array[0][$db->fieldName( "IsVATInc" )];
                $this->IsExported =& $cart_array[0][$db->fieldName( "IsExported" )];
                $this->PersonID =& $cart_array[0][$db->fieldName( "PersonID" )];
                $this->CompanyID =& $cart_array[0][$db->fieldName( "CompanyID" )];
                $this->Comment =& $cart_array[0][$db->fieldName( "Comment" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Fetches all active orders.

      Note: Default limit is 40.
    */
    function &getAll( $offset = 0, $limit = 40, $OrderBy = "Date" )
    {
        switch ( strtolower( $OrderBy ) )
        {
            case "no":
            {
                $OrderBy = "ID DESC";
                break;
            }
            case "created":
            {
                $OrderBy = "Date DESC";
                break;
            }
            case "modified":
            {
                $OrderBy = "Altered DESC";
                break;
            }
            case "status":
            {
                $OrderBy = "StatusID";
                break;
            }
            default:
            {
                $OrderBy = "Date DESC";
                break;
            }
        }
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $db->array_query( $order_array,
                          "SELECT * FROM ( SELECT eZTrade_Order.ID as ID,
                           eZTrade_Order.Date as Date,
                           max( eZTrade_OrderStatus.Altered ) as Altered,
                           max( eZTrade_OrderStatus.StatusID ) as StatusID
                           FROM eZTrade_Order, eZTrade_PreOrder, eZTrade_OrderStatus
                           WHERE eZTrade_Order.ID = eZTrade_OrderStatus.OrderID AND
                           eZTrade_Order.ID = eZTrade_PreOrder.OrderID
                           GROUP BY eZTrade_Order.ID, eZTrade_Order.Date ) AS tmp_table ORDER BY $OrderBy",
                           array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Fetch all the orders for the currenct user.

      Note: Default limit is 40.
    */
    function &getByUser( $offset = 0, $limit = 40, $OrderBy = "Date", $user = false )
    {
        switch ( strtolower( $OrderBy ) )
        {
            case "no":
            {
                $OrderBy = "ID";
                break;
            }
            case "created":
            {
                $OrderBy = "Date";
                break;
            }
            case "modified":
            {
                $OrderBy = "Altered";
                break;
            }
            case "status":
            {
                $OrderBy = "StatusID";
                break;
            }
            default:
            {
                $OrderBy = "Date";
                break;
            }
        }
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $userID = $user->id();

        $db->array_query( $order_array,
                          "SELECT eZTrade_Order.ID as ID,
                           eZTrade_Order.Date as Date,
                           max( eZTrade_OrderStatus.Altered ) as Altered,
                           max( eZTrade_OrderStatus.StatusID ) as StatusID
                           FROM eZTrade_Order, eZTrade_PreOrder, eZTrade_OrderStatus
                           WHERE eZTrade_Order.ID = eZTrade_OrderStatus.OrderID AND UserID='$userID' AND
                           eZTrade_Order.ID = eZTrade_PreOrder.OrderID
                           GROUP BY eZTrade_Order.ID, eZTrade_Order.Date
                           ORDER BY $OrderBy",
                           array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

        /*!
      Fetch all the orders for the currenct user.

      Note: Default limit is 40.
    */
    function &getCountByUser( $user = false, $OrderBy = false )
    {
        switch ( strtolower( $OrderBy ) )
        {
            case "no":
            {
                $OrderBy = "ID";
                break;
            }
            case "created":
            {
                $OrderBy = "Date";
                break;
            }
            case "modified":
            {
                $OrderBy = "Altered";
                break;
            }
            case "status":
            {
                $OrderBy = "StatusID";
                break;
            }
            default:
            {
                $OrderBy = "Date";
                break;
            }
        }
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $userID = $user->id();

        $db->query_single( $res,
                           "SELECT COUNT(eZTrade_Order.ID) as Count
                            FROM eZTrade_Order, eZTrade_OrderStatus, eZTrade_PreOrder
                            WHERE eZTrade_Order.ID = eZTrade_OrderStatus.OrderID AND
                            eZTrade_Order.ID = eZTrade_PreOrder.OrderID AND UserID='$userID' " );

        return $res[$db->fieldName( "Count" )];
    }

    static public function &getByContact( $contact, $is_person = true, $offset = 0, $limit = 40 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        if ( $is_person )
            $condition = "PersonID";
        else
            $condition = "CompanyID";

        $db->array_query( $order_array,
                          "SELECT eZTrade_Order.ID FROM eZTrade_Order, eZTrade_PreOrder WHERE $condition='$contact' AND " .
                          "eZTrade_Order.ID = eZTrade_PreOrder.OrderID",
                          array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns every order one customer has made.
    */
    function &getByCustomer( $user )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $userID = $user->id();
        $db->array_query( $order_array,
                          "SELECT eZTrade_Order.ID FROM eZTrade_Order, eZTrade_PreOrder WHERE
                           UserID='$userID' AND eZTrade_Order.ID = eZTrade_PreOrder.OrderID" );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Fetches new orders, orders which is not exported.
    */
    function &getNew()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $db->array_query( $order_array,
                          "SELECT eZTrade_Order.ID FROM eZTrade_Order, eZTrade_PreOrder
                           WHERE IsExported='0' AND eZTrade_Order.ID = eZTrade_PreOrder.OrderID" );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Does a search in the order database.

      Note: Default limit is 20.
    */
    function &search( $queryText, $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $order_array = array();

        $db->array_query( $order_array, "SELECT eZTrade_Order.ID
                                         FROM eZTrade_Order, eZTrade_PreOrder
                                         WHERE eZTrade_Order.ID LIKE '%$queryText%' AND
                                         eZTrade_Order.ID = eZTrade_PreOrder.OrderID",
                                         array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $order_array ); $i++ )
        {
            $return_array[$i] = new eZOrder( $order_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the total count of a query.
    */
    function &getSearchCount( $queryText )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $order_array, "SELECT count(eZTrade_Order.ID) as Count
                                                     FROM eZTrade_Order, eZTrade_PreOrder
                                                     WHERE eZTrade_Order.ID LIKE '%$queryText%' AND
                                                     eZTrade_Order.ID = eZTrade_PreOrder.OrderID" );

        $ret = 0;
        if ( count( $order_array ) == 1 )
        {
            $ret = $order_array[0][$db->fieldName( "Count" )];
        }

        return $ret;
    }

    /*!
      Returns the total count of orders.
    */
    function &getTotalCount()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $order_array, "SELECT count(eZTrade_Order.ID) as Count
                                                     FROM eZTrade_Order, eZTrade_PreOrder
                                                     WHERE eZTrade_Order.ID = eZTrade_PreOrder.OrderID" );

        $ret = 0;
        if ( count( $order_array ) == 1 )
        {
            $ret = $order_array[0][$db->fieldName( "Count" )];
        }

        return $ret;
    }

    /*!
      Returns the order date as a eZDateTime object.
    */
    function &date()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Date );
        return $dateTime;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the user as a eZUser object.

      false (0) is returned if unsuccessful.
    */
    function &user()
    {
       $ret = false;

       $user = new eZUser( );
       if ( $user->get( $this->UserID ) )
           $ret = $user;

       return $ret;
    }

    /*!
      Returns the shipping charge.
    */
    function shippingCharge()
    {
       return $this->ShippingCharge;
    }

    /*!
      Returns the shipping vat.
    */
    function shippingVAT()
    {
       return $this->ShippingVAT;
    }

    /*!
      Returns the shipping address.
    */
    function &shippingAddress()
    {
       $shippingAddress = new eZAddress( $this->ShippingAddressID );

       return $shippingAddress;
    }

    /*!
      Returns true if the order was included with VAT.
    */
    function isVATInc()
    {
        if ( $this->IsVATInc == 1 )
            return true;
        else
            return false;
    }


    /*!
      Returns the user to ship the goods to.

      Returns false if unsuccessful.
    */
    function &shippingUser()
    {
       // check the owner of the address

        $db =& eZDB::globalDatabase();

        $address_array = array();

        $retUser = false;

        if ( $this->PersonID == 0 && $this->CompanyID == 0 )
        {
            $db->array_query( $address_array,
            "SELECT * FROM eZUser_UserShippingLink WHERE AddressID='$this->ShippingAddressID'" );

            if ( count( $address_array ) == 1 )
            {
                $retUser = new eZUser( $address_array[0][$db->fieldName( "UserID" )] );
            }
            else
            {
                print( "Error: eZOrder::shippingUser() " . count( $address_array ) . " users found, should be 1." );
            }
            return $retUser;
        }
        return false;
    }

    /*!
      Returns the billing address.
    */
    function &billingAddress()
    {
       $billingAddress = new eZAddress( $this->BillingAddressID );

       return $billingAddress;
    }

    /*!
      Returns the user comment.
    */
    function &comment()
    {
       return $this->Comment;
    }

    /*!
      Returns the shipping type as a eZShippingType object.

      Will return false if an error occured.
    */
    function &shippingType()
    {
       $ret = false;

       if( $this->checkups == 1 )
       {
           $ret = $this->ShippingTypeID;
       }
       else
       {
            if ( $this->ShippingTypeID > 0 )
            {
                $ret = new eZShippingType( $this->ShippingTypeID );
            }
       }

       return $ret;
    }

    /*!
      Returns the payment method text.
    */
    function paymentMethod()
    {
       return $this->PaymentMethod;
    }

    
	/*      Returns the authorization code.
    
    function authCode()
    {
       return $this->AuthCode;
    }
*/
    /*!
      Returns true if the order is exported. This is for use with integration
      with other systems.
    */
    function isExported( $value )
    {
       if ( $this->IsExported == 1 )
           return true;
       else
           return false;
    }

    /*!
      Returns the person we are shopping for
    */
    function personID()
    {
        return $this->PersonID;
    }

    /*!
      Returns the company we are shopping for
    */
    function companyID()
    {
        return $this->CompanyID;
    }

    /*!
      Sets the payment method.
    */
    function setPaymentMethod( $value )
    {
        if ( is_array ( $value ) )
        {
            $i = 0;
            foreach ( $value as $item )
            {
                if ( $i == 0 )
                    $method = $item;
                else
                    $method .= "," . $item;
            }
            $value = $method;
        }
        $this->PaymentMethod = $value;
    }

/*    function setAuthCode( $value )
    {
        $this->AuthCode = $value;
    }  */

    /*!
      Sets the user.
    */
    function setUser( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Sets the user comment.
    */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

///////////////////////////////////////////////////////////////////////////
// possible "User Info Swap"

    /*!
      Sets the shipping address.
    */
    function setShippingAddress( $shippingAddress, $user )
    {
       if ( is_a( $shippingAddress, "eZAddress" ) )
       {
           $shippingAddress =& $shippingAddress->copy();
           $this->ShippingAddressID = $shippingAddress->id();
           $userID = $user->id();

           $db =& eZDB::globalDatabase();
           $db->begin();

	   //           $userID = $user->id();

           $db->lock( "eZUser_UserShippingLink" );
           $nextID = $db->nextID( "eZUser_UserShippingLink", "ID" );

           $ret[] = $db->query( "INSERT INTO eZUser_UserShippingLink
                                  (ID,
	     	                       UserID,
		                           AddressID )
                                  VALUES
                                  ('$nextID',
		                           '$userID',
		                           '$this->ShippingAddressID') " );
           $db->unlock();
           eZDB::finish( $ret, $db );
       }
    }

    /*!
      Sets the billing address.
    */
    function setBillingAddress( $billingAddress, $user )
    {
       if ( is_a( $billingAddress, "eZAddress" ) )
       {
           $billingAddress =& $billingAddress->copy();
           $this->BillingAddressID = $billingAddress->id();
       }
    }

    /*!
      Sets the shipping charge. Inc. VAT.
    */
    function setShippingCharge( $value )
    {
       $this->ShippingCharge = $value;

       setType( $this->ShippingCharge, "double" );
    }

    /*!
      Sets the shipping VAT. The VAT component of the shipping charge.
    */
    function setShippingVAT( $value )
    {
       $this->ShippingVAT = $value;

       setType( $this->ShippingVAT, "double" );
    }

    /*!
      Sets true if an order is included with VAT, false if not.
    */
    function setIsVATInc( $value )
    {
        if ( $value )
            $this->IsVATInc = 1;
        else
            $this->IsVATInc = 0;
    }


    /*!
      Sets the status of the order.
    */
    function setStatus( $type )
    {
       if ( is_a( $type, "eZOrderStatusType" ) )
       {
           $this->OrderStatus_ = $type->id();
       }
    }

    /*!
      Sets the shipping type ID of the order.
    */
    function setShippingTypeID( $type )
    {
       $this->ShippingTypeID = $type;
    }

    /*!
      Sets the order to be exported or not. This is used for integration with
      other systems. So you know if you have fetched this order or not.
    */
    function setIsExported( $value )
    {
       if ( $value == true )
           $this->IsExported = 1;
       else
           $this->IsExported = 0;
    }

    /*!
      Sets the person we are shopping for
    */
    function setPersonID( $userID )
    {
        if ( is_a( $userID, "eZUser" ) )
            $id = $userID->ID();
        else
            $id = $userID;
        if ( is_numeric( $id ) )
        {
            $this->PersonID = $id;
        }
    }

    /*!
      Sets the company we are shopping for
    */
    function setCompanyID( $id )
    {
        if ( is_numeric( $id ) )
        {
            $this->CompanyID = $id;
        }
    }

    /*!
      Returns the initial status as a eZOrderStatus object.
    */
    function &initialStatus( )
    {
        $db =& eZDB::globalDatabase();
        $statusType = new eZOrderStatusType();

        $statusType->getByName( "intl-order received" );

        $db->array_query( $status_array, "SELECT ID FROM eZTrade_OrderStatus
                                                    WHERE OrderID='$this->ID'
                                                    ORDER BY Altered DESC" );
        $ret = false;
        if ( count( $status_array ) )
        {
            $ret = new eZOrderStatus( $status_array[0][$db->fieldName( "ID" )]);
        }
        return $ret;
    }

    /*!
      Returns the last status change  as a eZOrderStatus object.
    */
    function &lastStatus( )
    {
        $db =& eZDB::globalDatabase();
        $statusType = new eZOrderStatusType();

        $statusType->getByName( "intl-order received" );

        $db->array_query( $status_array, "SELECT ID FROM eZTrade_OrderStatus
                                                    WHERE OrderID='$this->ID'
                                                    ORDER BY Altered DESC" );
        $ret = false;
        if ( count( $status_array ) )
        {
            $ret = new eZOrderStatus( $status_array[0][$db->fieldName( "ID" )] );
        }
        return $ret;
    }

    /*!
      Returns the status history as an array of eZOrderStatus object.
    */
    function &statusHistory()
    {
        $db =& eZDB::globalDatabase();

        $statusType = new eZOrderStatusType();

        $statusType->getByName( "intl-order received" );

        $db->array_query( $status_array, "SELECT ID FROM eZTrade_OrderStatus
                                                    WHERE OrderID='$this->ID'
                                                    ORDER BY Altered" );
        $ret = array();
        foreach ( $status_array as $status )
        {
           $ret[] = new eZOrderStatus( $status[$db->fieldName( "ID" )] );
        }
        return $ret;

    }

    /*!
      Returns all the order items.
    */
    function &items()
    {
        $ret = array();

        $db =& eZDB::globalDatabase();

        $db->array_query( $order_array, "SELECT * FROM
                                       eZTrade_OrderItem
                                       WHERE OrderID='$this->ID'" );

        if ( count( $order_array ) > 0 )
        {
           $return_array = array();
           foreach ( $order_array as $item )
           {
               $return_array[] = new eZOrderItem( $item[$db->fieldName( "ID" )] );
           }
           $ret = $return_array;
        }

        return $ret;
    }

    /*!
      Returns the voucher for this order.
    */
    function &usedVouchers()
    {
        $ret = array();

        $db =& eZDB::globalDatabase();

        $db->array_query( $order_array, "SELECT ID FROM
                                       eZTrade_VoucherUsed
                                       WHERE OrderID='$this->ID'" );

        if ( count( $order_array ) > 0 )
        {
           $return_array = array();
           foreach ( $order_array as $item )
           {
               $return_array[] = new eZVoucherUsed( $item[$db->fieldName( "ID" )] );
           }
           $ret = $return_array;
        }

        return $ret;
    }


    /*!
      Returns the total price on an order. Without the shipping charge.
    */
    function totalPrice( $user = false )
    {
        $retPrice = 0;
        $db =& eZDB::globalDatabase();

        $db->array_query( $order_item_array, "SELECT Price, Count FROM
                                              eZTrade_OrderItem
                                              WHERE OrderID='$this->ID'" );

        foreach ( $order_item_array as $item )
        {
            $price = $item[$db->fieldName("Price")];
//            $price = $price * $item["Count"];

            $retPrice += $price;
        }
        return $retPrice;
    }

    /*!
      Returns the total price with VAT on an order. Without the shipping charge.
    */
    function totalPriceIncVAT()
    {
       return $this->totalPrice() + $this->totalVAT();
    }

    /*!
      Returns the total VAT on an order. Without the shipping charge.
    */
    function totalVAT()
    {
       $retPrice = 0;
       $db =& eZDB::globalDatabase();

       $db->array_query( $order_item_array, "SELECT VAT, Count FROM
                                                    eZTrade_OrderItem
                                                    WHERE OrderID='$this->ID'" );

       foreach ( $order_item_array as $item )
       {
           $price = $item[$db->fieldName( "VAT" )];

//           $price = $price * $item["Count"];

           $retPrice += $price;
       }

       return $retPrice;
    }

    /*!
      Returns true if the user is the owner of this order.
    */
    function isOwner( &$user )
    {
       $db =& eZDB::globalDatabase();

       $userID = $user->id();
       $ret = false;
       $db->query_single( $res, "SELECT ID FROM eZTrade_Order
                                 WHERE ID='$this->ID' AND UserID='$userID'" );
       if ( is_numeric( $res[$db->fieldName( "ID" )] ) )
           $ret = true;

       return $ret;
    }


    /*!
      Returns the most request bought products.
    */
    function &mostPopularProduct()
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $db->array_query( $product_array,
                          "SELECT ProductID, Count(ProductID) AS Count, Sum( Count ) AS RealCount
                           FROM eZTrade_OrderItem GROUP BY ProductID
                           ORDER BY RealCount DESC" );

        foreach ( $product_array as $item )
        {
            $ret[] = array( "ProductID" => $item[$db->fieldName( "ProductID" )],
                            "Count" => $item[$db->fieldName( "Count" )],
                            "RealCount" => $item[$db->fieldName( "RealCount" )] );
        }

        return $ret;
    }

    /*!

    */
    function &expiringOrders( $startdate, $time = 86400 )
    {
        if ( is_a( $startdate, "eZDate" ) || is_a( $startdate, "eZDateTime" ) )
        {
            $startdate = $startdate->timeStamp();
        }
        $enddate = $startdate + $time;

        $db =& eZDB::globalDatabase();
        $db->array_query( $orders, "SELECT ID FROM eZTrade_OrderItem
                                    WHERE ExpiryDate >= '" . $startdate . "'
                                    AND ExpiryDate < '" . $enddate . "'" );
        $return_array = array();

        foreach ( $orders as $order )
        {
            $return_array[] = $order[$db->fieldName( "ID" )];
        }
        return $return_array;
    }

    static public function soldProducts( $product )
    {
        if ( is_a( $product, "eZProduct" ) )
            $product = $product->ID();

        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT SUM( Count ) AS C FROM eZTrade_OrderItem WHERE ProductID='$product'" );
        return $res[0][$db->fieldName( "C" )];
    }

    /*!
        This function calculates the totals of the order contents.


     */
    function orderTotals( &$tax, &$total )
    {
        $items = $this->items( );

        $tax = array();
        $total = array();

        $totalIncTax = 0;
        $totalExTax = 0;

        foreach ( $items as $item )
        {
            $product =& $item->product();
            $vatPercentage = (int)$product->vatPercentage();

            $tax["$vatPercentage"] = array();
            $tax["$vatPercentage"]["basis"] = false;
            $tax["$vatPercentage"]["tax"] = false;
            $tax["$vatPercentage"]["percentage"] = false;
    
            $exTax = $item->correctPrice( true, true, false );
            $incTax = $item->correctPrice( true, true, true );

            $totalExTax += $exTax;
            $totalIncTax += $incTax;

            $tax["$vatPercentage"]["basis"] += $exTax;
            $tax["$vatPercentage"]["tax"] += $incTax - $exTax;
            $tax["$vatPercentage"]["percentage"] = $vatPercentage;
        }

        $total["subinctax"] = $totalIncTax;
        $total["subextax"] = $totalExTax;
        $total["subtax"] = $totalIncTax - $totalExTax;

        $shippingCost = $this->ShippingCharge;
        $shippingVAT = $this->ShippingVAT;

        if ( $shippingVAT && $shippingCost )
        {
            $shippingVATPercentage = round( $shippingVAT / ( ( $shippingCost - $shippingVAT ) / 100 ), 2 );
        }
        else
            $shippingVATPercentage = 0;

        $tax["$shippingVATPercentage"] = array();
        $tax["$shippingVATPercentage"]["basis"] = false;
        $tax["$shippingVATPercentage"]["tax"] = false;
        $tax["$shippingVATPercentage"]["percentage"] = false;

        $tax["$shippingVATPercentage"]["basis"] += $shippingCost - $shippingVAT;
        $tax["$shippingVATPercentage"]["tax"] += $shippingVAT;
        $tax["$shippingVATPercentage"]["percentage"] = $shippingVATPercentage;

//RS        $total["shipinctax"] = $shippingCost;
//RS        $total["shipextax"] = $shippingCost - $shippingVAT;
        $total["shiptax"] = $shippingVAT;

        $total["shipinctax"] = $shippingCost + $shippingVAT;
        $total["shipextax"] = $shippingCost;

        $total["inctax"] = $total["subinctax"] + $total["shipinctax"];
        $total["extax"] = $total["subextax"] + $total["shipextax"];
        $total["tax"] = $total["subtax"] + $total["shiptax"];
    }


    /*!
        This function calculates the totals of the order contents.


     */
    function voucherTotal( &$tax, &$total, $voucherItem=false )
    {
        $tax = "";
        $total = "";

        if ( is_a ( $voucherItem, "eZVoucherUsed" ) )
            $voucher =& $voucherItem->voucher();
        else
            $voucher =& $voucherItem;

        $product =& $voucher->product();
        $vatPercentage = $product->vatPercentage();
        $exTax = $voucherItem->correctPrice( false );
        $incTax = $voucherItem->correctPrice( true );

        $totalExTax += $exTax;
        $totalIncTax += $incTax;

        $tax["$vatPercentage"]["basis"] += $exTax;
        $tax["$vatPercentage"]["tax"] += $incTax - $exTax;
        $tax["$vatPercentage"]["percentage"] = $vatPercentage;

        $total["subinctax"] = $totalIncTax;
        $total["subextax"] = $totalExTax;
        $total["subtax"] = $totalIncTax - $totalExTax;
        $total["inctax"] = $total["subinctax"];
        $total["extax"] = $total["subextax"];
        $total["tax"] = $total["subtax"];
    }

    /*!
      \static
      Returns all the users which has created an order.

      The users are returned as an array of eZUser objects.
    */
    static public function &customers( $searchText = false )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $user_array = array();
		
		if ( $searchText )
			$searchQuery = "AND (eZUser_User.FirstName LIKE '%$searchText%' OR
                                            eZUser_User.LastName LIKE '%$searchText%' OR
                                            eZUser_User.Login LIKE '%$searchText%' OR
                                            eZUser_User.Email LIKE '%$searchText%')";
		else
			$searchQuery = "";

        $db->array_query( $user_array, "SELECT eZTrade_Order.UserID, eZUser_User.FirstName FROM eZTrade_Order, eZTrade_PreOrder, eZUser_User
                                        WHERE eZTrade_Order.UserID=eZUser_User.ID AND eZTrade_Order.ID = eZTrade_PreOrder.OrderID
                                        $searchQuery GROUP BY eZTrade_Order.UserID, eZUser_User.FirstName ORDER BY eZUser_User.FirstName " );

        for ( $i = 0; $i < count( $user_array ); $i++ )
        {
            $return_array[$i] = new eZUser( $user_array[$i][$db->fieldName( "UserID" )] );
        }

        return $return_array;
    }

    var $ID;
    var $UserID;
    var $ShippingAddressID;
    var $BillingAddressID;
    /// price inc. VAT
    var $ShippingCharge;
    /// the VAT component of ShippingCharge
    var $ShippingVAT;
    var $PaymentMethod;
	//var $AuthCode;
    var $Date;
    var $PersonID;
    var $CompanyID;
    var $IsVATInc;
    var $Comment;
    
    var $ShippingTypeID;
    var $OrderStatus_;
    var $IsExported;
    var $TextPaymentMethod;
    var $checkups;

}

?>