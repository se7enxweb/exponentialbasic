<?php
//
// $Id: ezwishlistitem.php 7465 2001-09-26 07:09:33Z ce $
//
// Definition of eZWishItem class
//
// Created on: <21-Oct-2000 18:08:30 bf>
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
//! eZWishlistItem handles a shopping wishlist
/*!
  Example code:
  \code
  $product = new eZProduct( 3 );

  $wishlistItem = new eZWishlistItem();
  $wishlistItem->setProduct( $product );
  $wishlistItem->setWishlist( $wishlist );

  // Store to the database
  $wishlistItem->store();

  \endcode
  \sa eZWishlist
*/

// include_once( "classes/ezdb.php" );

// include_once( "eztrade/classes/ezwishlistoptionvalue.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezcartitem.php" );
// include_once( "eztrade/classes/ezcart.php" );


class eZWishListItem
{
    /*!
      Constructs a new eZWishlist object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="", $fetch=true )
    {
        $this->IsBought = 0;

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
      Stores a wishlist to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_WishListItem" );
            $nextID = $db->nextID( "eZTrade_WishListItem", "ID" );

            $res = $db->query( "INSERT INTO eZTrade_WishListItem
                                  ( ID, ProductID, WishListID, Count, IsBought )
                                  VALUES
                                  ( '$nextID',
		                            '$this->ProductID',
		                            '$this->WishListID',
 		                            '$this->Count',
		                            '$this->IsBought' )
                                  " );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_WishListItem SET
		                         ProductID='$this->ProductID',
		                         WishListID='$this->WishListID',
		                         Count='$this->Count',
		                         IsBought='$this->IsBought'
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
            $db->array_query( $wishlist_array, "SELECT * FROM eZTrade_WishListItem WHERE ID='$id'" );
            if ( count( $wishlist_array ) > 1 )
            {
                die( "Error: Wishlist's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $wishlist_array ) == 1 )
            {
                $this->ID = $wishlist_array[0][$db->fieldName( "ID" )];
                $this->ProductID = $wishlist_array[0][$db->fieldName( "ProductID" )];
                $this->WishListID = $wishlist_array[0][$db->fieldName( "WishListID" )];
                $this->Count = $wishlist_array[0][$db->fieldName( "Count" )];
                $this->IsBought = $wishlist_array[0][$db->fieldName( "IsBought" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZWishlistItem object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZTrade_WishListOptionValue WHERE WishListItemID='$this->ID'" );

        $res[] = $db->query( "DELETE FROM eZTrade_WishListItem WHERE ID='$this->ID'" );

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
      Returns the product to the wishlist item as an eZProduct object.

    */
    function &product()
    {
       $ret = false;

       $prod = new eZProduct( );
       if ( $prod->get( $this->ProductID ) )
       {
           $ret = $prod;
       }

       return $ret;
    }

    /*!
      Returns the wishlist.
    */
    function &wishlist()
    {
       $ret = false;

       $wishlist = new eZWishlist( );
       if ( $wishlist->get( $this->WishListID ) )
       {
           $ret = $wishlist;
       }

       return $ret;
    }

    /*!
      Returns the product count.
    */
    function count( )
    {
       return $this->Count;
    }

    /*!
      Sets the product.
    */
    function setProduct( $product )
    {
       if ( is_a( $product, "eZProduct" ) )
       {
           $this->ProductID = $product->id();
       }
    }

    /*!
      Sets the wishlist.
    */
    function setWishList( $wishlist )
    {
       if ( is_a( $wishlist, "eZWishlist" ) )
       {
           $this->WishListID = $wishlist->id();
       }
    }

    /*!
      Sets the product to be bought. This is only an indication so that the user, and
      other people looking at the wishlist, sees if the item is already bought for this
      user.
    */
    function setIsBought( $value )
    {
       if ( $value == true )
       {
           $this->IsBought = 1;
       }
       else
       {
           $this->IsBought = 0;
       }
    }

    /*!
      Returns true if the item is already bought. False if not.
    */
    function isBought()
    {
       $ret = false;

       if ( $this->IsBought == 1 )
       {
           $ret = true;
       }

       return $ret;
    }

    /*!
      Returns all the option values as an array of eZWishlistOptionValue objects.

      An empty array is returned if none exists.
    */
    function &optionValues( )
    {
       $return_array = array();
       $db =& eZDB::globalDatabase();

       $db->array_query( $res_array, "SELECT ID FROM eZTrade_WishListOptionValue
                                     WHERE
                                     WishListItemID='$this->ID'
                                   " );

       foreach ( $res_array as $item )
       {
           $return_array[] = new eZWishlistOptionValue( $item[$db->fieldName( "ID" )] );
       }
       return $return_array;
    }

    /*!
      Will move the current eZWishListItem to the cart.
    */
    function moveToCart()
    {
       // fetch the cart or create one
       $cart = new eZCart();
       $session = new eZSession();

       // if no session exist create one.
       if ( !$session->fetch() )
       {
           $session->store();
       }

       $user =& eZUser::currentUser();

       $cart = $cart->getBySession( $session );

       if ( !$cart )
       {
           $cart = new eZCart();
           $cart->setSession( $session );

           $cart->store();
       }

       $product = $this->product();

       $cartItem = new eZCartItem();

       $cartItem->setProduct( $product );
       $cartItem->setCart( $cart );

       // set the wishlist item
       $cartItem->setWishListItem( $this );

       $cartItem->store();

       $optionValues = $this->optionValues();


       if ( count( $optionValues ) > 0 )
       {
           foreach ( $optionValues as $value )
           {
               $cartOption = new eZCartOptionValue();
               $cartOption->setCartItem( $cartItem );

               $cartOption->setOption( $value->option() );
               $cartOption->setOptionValue( $value->optionValue() );

               $cartOption->store();
           }
       }

    }

    /*!
      Sets the number of products.
    */
    function setCount( $count )
    {
       if ( isset( $this->State_ ) && $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Count = $count;
    }


    /*!
      Returns the price of the wishlist item.

      Options and count is calculated.
    */
    function price()
    {
        $optionValues =& $this->optionValues();
        $product =& $this->product();

        $optionPrice = 0.0;
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();

            // the pricegroup is set in the datasupplier

            $PriceGroup = $GLOBALS["PriceGroup"];

            // get the value price if exists
            $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup, $option->id(), $value->id() );

            $found_price = false;

            if ( $price )
            {
                $found_price = true;
            }

            // if not fetch the standard price
            if ( !$found_price )
            {
                $price = $value->price();
            }

            $optionPrice += $price;
        }

        $price = ( $product->price() + $optionPrice )  * $this->count();

        return $price;
    }

    var $ID;
    var $ProductID;
    var $WishListID;
    var $Count;
    var $IsBought;
}
?>