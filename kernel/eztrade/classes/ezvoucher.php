<?php
//
// $Id: ezvoucher.php 9327 2002-03-04 12:43:38Z ce $
//
// eZVoucher class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <19-Jun-2001 17:41:06 ce>
//
// This source file is part of Exponential Basic, publishing software.
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
  $voucher = new eZVoucher(); // Create a new object.
  $voucher->setPrice( 500 ); // Sets the price of the voucher.
  $voucher->generateKey(); // Genereate a uniqe key for the voucher.

  $voucher->setMailMethod( 1 ); // Set the mail method, 1 is email and 2 is smail.
  $voucher->setAvailable( true ); // Set that this voucher is available.
  $voucher->setUser( eZUser::currentUser() ); // Sets the user that bought this voucher.
  $voucher->store(); // Stores the object to the database.
  \endcode

  \sa eZVoucherUsed eZVoucherEMail eZVoucherSMail
*/

// include_once( "classes/ezdate.php" );

// include_once( "eztrade/classes/ezvoucherinformation.php" );
// include_once( "eztrade/classes/ezvoucherused.php" );

class eZVoucher
{

    /*!
      Constructs a new eZVoucher object.

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
      Stores a eZVoucher object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();


        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Voucher" );
            $nextID = $db->nextID( "eZTrade_Voucher", "ID" );
            $timeStamp =& (new eZDateTime())->timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_Voucher
                      ( ID, Created, Price, Available, KeyNumber, UserID, ProductID, TotalValue )
                      VALUES
                      ( '$nextID',
                        '$timeStamp',
                        '$this->Price',
                        '$this->Available',
                        '$this->KeyNumber',
                        '$this->UserID',
                        '$this->ProductID',
                        '$this->TotalValue'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_Voucher SET
                                     Created=Created,
                                     Price='$this->Price',
                                     Available='$this->Available',
                                     KeyNumber='$this->KeyNumber',
                                     UserID='$this->UserID',
                                     ProductID='$this->ProductID',
                                     TotalValue='$this->TotalValue'
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
      Deletes a eZVoucher object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res = $db->query( "DELETE FROM eZTrade_Voucher WHERE ID='$this->ID'" );

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
            $db->array_query( $voucherArray, "SELECT * FROM eZTrade_Voucher WHERE ID='$id'",
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
    function fill( &$voucherArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $voucherArray[$db->fieldName( "ID" )];
        $this->Created =& $voucherArray[$db->fieldName( "Created" )];
        $this->Price =& $voucherArray[$db->fieldName( "Price" )];
        $this->Available =& $voucherArray[$db->fieldName( "Available" )];
        $this->KeyNumber =& $voucherArray[$db->fieldName( "KeyNumber" )];
        $this->UserID =& $voucherArray[$db->fieldName( "UserID" )];
        $this->ProductID =& $voucherArray[$db->fieldName( "ProductID" )];
        $this->TotalValue =& $voucherArray[$db->fieldName( "TotalValue" )];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVoucher objects.
    */
    static public function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $voucherArray = array();

        if ( $limit == false )
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_Voucher
                                           " );

        }
        else
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_Voucher ORDER BY Created DESC
                                           ", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($voucherArray); $i++ )
        {
            $returnArray[$i] = new eZVoucher( $voucherArray[$i][$db->fieldName( "ID" )] );
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
                                     FROM eZTrade_Voucher" );
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
      Returns the creation time of the voucher.
    */
    function &created()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Created );

        return $dateTime;
    }

    /*!
      Sets the login.
    */
    function generateKey( $length=15 )
    {
        $this->KeyNumber = substr( md5( microtime() ), 0, $length );
    }

    /*!
      Returns the keynumber
    */
    function keyNumber( )
    {
        return $this->KeyNumber;
    }

    /*!
      Returns the user
    */
    function &user( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZUser( $this->UserID );
        else
            $ret = $this->UserID;

        return $ret;
    }

    /*!
      Returns the product
    */
    function &product( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZProduct( $this->ProductID );
        else
            $ret = $this->ProductID;

        return $ret;
    }

    /*!
      Sets if the voucher is available or not.
    */
    function setAvailable( $value )
    {
        if ( $value == true )
            $this->Available = 1;
        else
            $this->Available = 0;
    }

    /*!
      Returns true if the voucher is avaiable
    */
    function isAvailable()
    {
        if ( $this->Available == 1 )
            return true;
        else
            return false;
    }

    /*!
      Sets the voucher price.
    */
    function setPrice( $value )
    {
       $this->Price = $value;
       setType( $this->Price, "integer" );
    }

    /*!
      Sets the total voucher price.
    */
    function setTotalValue( $value )
    {
       $this->TotalValue = $value;
       setType( $this->TotalValue, "integer" );
    }

    /*!
      Sets the user of this object.
    */
    function setUser( &$user )
    {
        if ( is_a ( $user, "eZUser" ) )
            $this->UserID = $user->id();
        elseif ( is_numeric ( $user ) )
            $this->UserID = $user;
    }

    /*!
      Sets the product of this object.
    */
    function setProduct( &$product )
    {
        if ( is_a ( $product, "eZProduct" ) )
            $this->ProductID = $product->id();
        elseif ( is_numeric ( $user ) )
            $this->ProductID = $product;
    }

    /*!
      Returns the price of the voucher.
    */
    function &price( )
    {
        return $this->Price;
    }

    /*!
      Returns the total value price of the voucher.
    */
    function &totalValue( )
    {
        return $this->TotalValue;
    }

    /*!
      Returns the correct price of the voucher based on the logged in user, and the
      VAT status and use.
    */
    function &correctPrice( $calcVAT )
    {
        $product =& $this->product();

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
      Get a voucher from a key number.
    */
    function getFromKeyNumber( &$key, $available=true )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( !$key )
            return false;

        if ( $available )
            $db->query_single( $res, "SELECT ID FROM eZTrade_Voucher WHERE KeyNumber='$key' AND Available='1'" );
        else
            $db->query_single( $res, "SELECT ID FROM eZTrade_Voucher WHERE KeyNumber='$key'" );

        if ( $res[$db->fieldName( "ID" )] )
        {
            $ret = new eZVoucher( $res[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Get a voucher from a key number.
    */
    function getByUser( &$user, $available=true )
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $userID = $user->id();

        if ( $available )
            $db->array_query( $res, "SELECT ID FROM eZTrade_Voucher WHERE UserID='$userID' AND Available='1' ORDER By Created DESC" );
        else
            $db->array_query( $res, "SELECT ID FROM eZTrade_Voucher WHERE UserID='$userID' ORDER By Created DESC " );

        foreach( $res as $result )
        {
            $ret[] = new eZVoucher( $result[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Return the voucher information.
    */
    function information( )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $res = array();

        $query = "SELECT ID FROM eZTrade_VoucherInformation WHERE VoucherID='$this->ID'";
        $db->query_single( $res, $query );

        if ( $res[$db->fieldName( "ID" )] )
        {
            $ret = new eZVoucherInformation( $res[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns all the used vouchers.
    */
    function usedList( $id=false )
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        if ( !$id )
            $id = $this->ID;

        $db->array_query( $res, "SELECT ID FROM eZTrade_VoucherUsed WHERE VoucherID='$id'" );

        foreach( $res as $used )
        {
            $ret[] = new eZVoucherUsed( $used[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    var $ID;
    var $KeyNumber;
    var $Created;
    var $Available;
    var $Price;
    var $UserID;
    var $ProductID;
    var $TotalValue;
}

?>