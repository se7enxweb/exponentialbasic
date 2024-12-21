<?php
// 
// $Id: ezvoucherused.php 7870 2001-10-16 09:21:05Z ce $
//
// eZVoucherUsed class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <06-Sep-2001 09:46:53 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! ezquizgame
//! ezquizgame documentation.
/*!

  Example code:
  \code
  $used = new eZVoucherUsed(); // Create a new eZVoucherUsed object.
  $voucher = new eZVoucher( 4 ); // Get a voucher with id 4
  $used->setVoucher( $voucher ); // Sets the voucher object.
  $used->setPrice( 100 ); // Set the price.
  $used->store(); // Stores the object to the database.
  \endcode

*/

// include_once( "classes/ezdate.php" );
// include_once( "eztrade/classes/ezorder.php" );
// include_once( "eztrade/classes/ezvoucher.php" );
	      
class eZVoucherUsed
{

    /*!
      Constructs a new eZVoucherUsed object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZVoucherUsed object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
       
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_VoucherUsed" );
            $nextID = $db->nextID( "eZTrade_VoucherUsed", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_VoucherUsed
                      ( ID, Used, Price, VoucherID, OrderID, UserID )
                      VALUES
                      ( '$nextID',
                        '$timeStamp',
                        '$this->Price',
                        '$this->VoucherID',
                        '$this->OrderID',
                        '$this->UserID'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_VoucherUsed SET
                                     Used=Used,
                                     Price='$this->Price',
                                     VoucherID='$this->VoucherID',
                                     OrderID='$this->OrderID',
                                     UserID='$this->UserID'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZVoucherUsed object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $res = $db->query( "DELETE FROM eZTrade_VoucherUsed WHERE ID='$this->ID'" );
    
        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $voucherArray, "SELECT * FROM eZTrade_VoucherUsed WHERE ID='$id'",
                              0, 1 );
            if( count( $voucherArray ) == 1 )
            {
                $this->fill( $voucherArray[0] );
                $ret = true;
            }
            elseif( count( $voucherArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( $voucherArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $voucherArray[$db->fieldName( "ID" )];
        $this->Used = $voucherArray[$db->fieldName( "Used" )];
        $this->Price = $voucherArray[$db->fieldName( "Price" )];
        $this->VoucherID = $voucherArray[$db->fieldName( "VoucherID" )];
        $this->OrderID = $voucherArray[$db->fieldName( "OrderID" )];
        $this->UserID = $voucherArray[$db->fieldName( "UserID" )];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVoucherUsed objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $voucherArray = array();

        if ( $limit == false )
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_VoucherUsed
                                           " );
        }
        else
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_VoucherUsed
                                           ", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($voucherArray); $i++ )
        {
            $returnArray[$i] = new eZVoucherUsed( $voucherArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZTrade_VoucherUsed" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the time when this voucher was used.
    */
    function &used()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Used );

        return $dateTime;
    }

    /*!
      Sets the voucher price for this object.
    */
    function setPrice( $value )
    {
       $this->Price = $value;
       setType( $this->Price, "double" );
    }

    /*!
      Sets the user for this object.
    */
    function setUser( &$value )
    {
        if ( get_class ( $value ) )
            $this->UserID = $value->id();
        else if ( is_numeric ( $value ) )
            $this->UserID = $value;
    }

    /*!
      Sets the voucher for this object.
    */
    function setVoucher( &$value )
    {
        if ( get_class ( $value ) )
        {
            $this->VoucherID = $value->id();
        }
        else if ( is_numeric ( $value ) )
        {
            $this->VoucherID = $value;
        }
    }

    /*!
      Sets the order for this object.
    */
    function setOrder( &$value )
    {
        if ( get_class ( $value ) )
        {
            $this->OrderID = $value->id();
        }
        else if ( is_numeric ( $value ) )
        {
            $this->OrderID = $value;
        }
    }

    /*!
      Returns the voucher of this object.
    */
    function &voucher( $asObject=true )
    {
        if ( $asObject )
        {
            $ret = new eZVoucher( $this->VoucherID );
        }
        else
        {
            $ret = $this->VoucherID;
        }
        return $ret;
    }

    /*!
      Returns the order of this object.
    */
    function &order( $asObject=true )
    {
        if ( $asObject )
        {
            $ret = new eZOrder( $this->OrderID );
        }
        else
        {
            $ret = $this->OrderID;
        }
        return $ret;
    }
    
    /*!
      Returns the user of this object.
    */
    function &user( $asObject=true )
    {
        if ( $asObject )
        {
            $ret = new eZUser( $this->UserID );
        }
        else
        {
            $ret = $this->UserID;
        }
        return $ret;
    }

    /*!
      Returns the price of the voucher.
    */
    function &price( )
    {
        return $this->Price;
    }

    /*!
      Returns the correct price of the voucher based on the logged in user, and the
      VAT status and use.
    */
    function &correctPrice( $calcVAT )
    {
        $voucher =& $this->voucher();
        $product =& $voucher->product();
        
        $price = $this->Price;
        
        $vatType =& $product->vatType();
        
        if ( $calcVAT == true )
        {
            if ( $product->excludedVAT() )
            {
                $vatType =& $product->vatType();
                $vat = 0;
       
                if ( $vatType )
                {
                    $vat =& $vatType->value();
                }
                
                $price = ( $price * $vat / 100 ) + $price;
            }
        }
        else
        {
            if ( $product->includesVAT() )
            {
                $vatType =& $product->vatType();
                $vat = 0;
                
                if ( $vatType )
                {
                    $vat =& $vatType->value();
                }
                
                $price = $price - ( $price / ( 100 + $vat ) ) * $vat;
                
            }
        }
        return $price;
    }    


    /*!
      Get the used voucher for a user.
    */
    function getByUser( &$user )
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $userID = $user->id();

        $db->array_query( $res, "SELECT ID FROM eZTrade_VoucherUsed WHERE UserID='$userID' ORDER By Used DESC " );

        foreach( $res as $result )
        {
            $ret[] = new eZVoucherUsed( $result[$db->fieldName( "ID" )] );
        }

        return $ret;
    }



    var $ID;
    var $Used;
    var $Price;
    var $UserID;
    var $OrderID;
}

?>