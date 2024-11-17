<?php
// 
// $Id: ezcartvoucheritem.php 7368 2001-09-21 09:59:05Z ce $
//
// Definition of eZCartItem class
//
// Created on: <27-Sep-2000 15:19:05 bf>
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
//! eZCartItem handles a shopping cart
/*!
  Example code:
  \code
  $product = new eZProduct( 3 );

  $cartItem = new eZCartItem();
  $cartItem->setProduct( $product );
  $cartItem->setCart( $cart );

  // Store to the database
  $cartItem->store();

  \endcode
  \sa eZCart
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "ezaddress/classes/ezonline.php" );
include_once( "ezaddress/classes/ezaddress.php" );


class eZCartVoucherItem
{
    /*!
      Constructs a new eZCartItem object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->Count = 1;
        }
    }

    /*!
      Stores a cart to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_CartVoucherItem" );
            $nextID = $db->nextID( "eZTrade_CartVoucherItem", "ID" );            
            
            $res = $db->query( "INSERT INTO eZTrade_CartVoucherItem
                      ( ID, CartItemID, PriceRange, Description, MailMethod, MailID )
                      VALUES
                      ( '$nextID',
                        '$this->CartItemID',
                        '$this->PriceRange',
                        '$this->Description',
                        '$this->MailMethod',
                        '$this->MailID'
                      " );

            $db->unlock();
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_CartVoucherItem SET
		                         CartItemID='$this->CartItemID',
		                         PriceRange='$this->PriceRange',
		                         Description='$this->Description',
		                         MailMethod='$this->MailMethod',
                                 MailID='$this->MailID'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
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
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_CartVoucherItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName( "ID" )];
                $this->CartItemID = $cart_array[0][$db->fieldName( "CartItemID" )];
                $this->PriceRange = $cart_array[0][$db->fieldName( "PriceRange" )];
                $this->Description = $cart_array[0][$db->fieldName( "Description" )];
                $this->MailMethod = $cart_array[0][$db->fieldName( "MailMethod" )];
                $this->MailID = $cart_array[0][$db->fieldName( "MailID" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZCartItem object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
            
        $res[] = $db->query( "DELETE FROM eZTrade_CartVoucherItem WHERE ID='$this->ID'" );
        
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            

        return true;
    }
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;        
    }

    /*!
      Returns the mailmethod.
      1 = email
      2 = smail
    */
    function mailMethod( )
    {
        return $this->MailMethod;
    }

    /*!
      Sets the mailmethod of products.
    */
    function setMailMethod( $value )
    {
        $this->MailMethod = $value;
    }

    /*!
      Returns the email
    */
    function online( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZOnline( $this->MailID );
        else
            $ret = $this->MailID;

        return $ret;
    }

    /*!
      Returns the email
    */
    function address( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZAddress( $this->MailID );
        else
            $ret = $this->MailID;

        return $ret;
    }

    

    /*!
      Sets the mailmethod of products.
    */
    function setMailMethod( $value )
    {
        $this->MailMethod = $value;
    }

    /*!
      Sets the price of voucher item.
    */
    function setPriceRange( $value )
    {
        $this->PriceRange = $value;
    }

    /*!
      Returns the price.
    */
    function priceRange( )
    {
        return $this->PriceRange;
    }

    var $ID;
    var $CartItemID;
    var $PriceRange;
    var $Description;
    var $MailMethod=0;
    var $MailID=false;
    
    /// ID to a wishlist item. Indicates which wishlistitem the cart item comes from. 0 if added from product.
    var $WishListItemID;
}

?>