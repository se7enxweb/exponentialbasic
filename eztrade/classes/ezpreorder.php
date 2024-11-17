<?php
// 
// $Id: ezpreorder.php 6414 2001-08-10 12:15:27Z jhe $
//
// Definition of eZPreOrder class
//
// Created on: <15-Mar-2001 18:11:55 bf>
//
// This source file is part of eZ publish, publishing software.
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
//! eZOrder handles pre-orders.
/*!
  Pre orders is a handler for unique ID's for checkouts. This is needed
  because the orders are not created until the payment is done and
  we need a unique ID for the checkout (VISA clearing etc). This is to prevent
  double charging of the clients.

  \sa eZOrderItem
*/

include_once( "classes/ezdb.php" );


class eZPreOrder
{
    /*!
      Constructs a new eZPreOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->OrderID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order to the database.
    */
    function store()
    {
        $db =& eZDB::globaldatabase();
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_PreOrder" );
            $nextID = $db->nextID( "eZTrade_PreOrder", "ID" );
            $timeStamp =& eZDateTime::timeStamp( true );
            $ret[] = $db->query( "INSERT INTO eZTrade_PreOrder
                               ( ID,
		                         OrderID,
		                         Created )
                               VALUES
		                       ( '$nextID',
                                 '$this->OrderID',
		                         '$timeStamp' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_PreOrder SET
		                         Created=Created,
		                         OrderID='$this->OrderID'
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
        $db =& eZDB::globaldatabase();
        $db->begin();
        
        $ret[] = $db->query( "DELETE FROM eZTrade_PreOrder WHERE ID='$this->ID'" );

        eZDB::finish( $ret, $db );
            
        return true;
    }
    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globaldatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_PreOrder WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Pre order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName("ID")];
                $this->OrderID = $cart_array[0][$db->fieldName("OrderID")];
                $this->Created = $cart_array[0][$db->fieldName("Created")];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Fetches the pre order by OrderID.

      False is returned if no the orderID is not found.
    */
    function getByOrderID( $orderID )
    {
        $db =& eZDB::globaldatabase();
        $ret = false;
        
        if ( $orderID != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_PreOrder WHERE OrderID='$orderID'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Pre order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName("ID")];
                $this->OrderID = $cart_array[0][$db->fieldName("OrderID")];
                $this->Created = $cart_array[0][$db->fieldName("Created")];
                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Returns the order date as a eZDateTime object.
    */
    function date()
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
      Returns the order id. If 0 this pre order has not
      resulted in an order.
    */
    function orderID()
    {
        return $this->OrderID;
    }
    
    /*!
      Sets the order id which corresponds to this pre-order
    */
    function setOrderID( $value )
    {
        $this->OrderID = $value;
    }

    
    var $ID;
    var $Created;
    var $OrderID;

}

?>