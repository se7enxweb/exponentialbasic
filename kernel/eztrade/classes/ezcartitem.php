<?php
//
// $Id: ezcartitem.php 9407 2002-04-10 11:49:02Z br $
//
// Definition of eZCartItem class
//
// Created on: <27-Sep-2000 15:19:05 bf>
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

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezcartoptionvalue.php" );

// include_once( "eztrade/classes/ezwishlistitem.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );
// include_once( "eztrade/classes/ezvoucherinformation.php" );

class eZCartItem
{
    /*!
      Constructs a new eZCart object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->VoucherInformationID = 0;
        $this->WishListItemID = 0;

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
            $db->lock( "eZTrade_CartItem" );
            $nextID = $db->nextID( "eZTrade_CartItem", "ID" );
            $query = "INSERT INTO eZTrade_CartItem
                      ( ID, ProductID, CartID, Count, WishListItemID, VoucherInformationID )
                      VALUES
                      ( '$nextID',
                        '$this->ProductID',
                        '$this->CartID',
                        '$this->Count',
                        '$this->WishListItemID',
                        '$this->VoucherInformationID' )
                      ";
            $res = $db->query( $query );
            $db->unlock();

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_CartItem SET
		                         ProductID='$this->ProductID',
		                         CartID='$this->CartID',
		                         Count='$this->Count',
		                         WishListItemID='$this->WishListItemID',
                                 VoucherInformationID='$this->VoucherInformationID'
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
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_CartItem WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName( "ID" )];
                $this->ProductID = $cart_array[0][$db->fieldName( "ProductID" )];
                $this->CartID = $cart_array[0][$db->fieldName( "CartID" )];
                $this->Count = $cart_array[0][$db->fieldName( "Count" )];
                $this->WishListItemID = $cart_array[0][$db->fieldName( "WishListItemID" )];
                $this->VoucherInformationID = $cart_array[0][$db->fieldName( "VoucherInformationID" )];
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

        $res[] = $db->query( "DELETE FROM eZTrade_CartOptionValue WHERE CartItemID='$this->ID'" );

        $res[] = $db->query( "DELETE FROM eZTrade_CartItem WHERE ID='$this->ID'" );

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
      Returns the product to the cart item as an eZProduct object.

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
      Returns the cart.
    */
    function cart()
    {
       $ret = false;

       $cart = new eZCart( );
       if ( $cart->get( $this->CartID ) )
       {
           $ret = $cart;
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
      Returns the correct localized price of the product.
    */
    function localePrice( $calcCount=true, $withOptions=true, $calcVAT = false, $withPriceGroups=true )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $inLanguage = $ini->variable( "eZTradeMain", "Language" );

        $locale = new eZLocale( $inLanguage );
        $currency = new eZCurrency();

        $price = $this->correctPrice( $calcCount, $withOptions, $calcVAT, $withPriceGroups );

        $currency->setValue( $price );
        return $locale->format( $currency );
    }

    /*!
      Returns the correct price of the product based on the logged in user, and the
      VAT status and use.
    */
    function correctPrice( $calcCount=true, $withOptions=true, $calcVAT = false, $withPriceGroups=true )
    {
        $optionValues =& $this->optionValues();
        $product =& $this->product();

        $optionPrice = 0.0;
        if ( $withOptions )
        {
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();

                $price = $value->correctPrice( $calcVAT, $product, $withPriceGroups );

                if ( $calcCount == true )
                    $price = $price * $optionValue->count();

                $optionPrice+=$price;
            }
        }

        if ( $calcCount == true )
        {
            $price = ( $product->correctPrice( $calcVAT, $withPriceGroups ) * $this->count() ) + $optionPrice;
        }
        else
        {
            $price = ( $product->correctPrice( $calcVAT, $withPriceGroups ) + $optionPrice );
        }

        $voucherInfo =& $this->voucherInformation();

        if ( $voucherInfo )
        {
            if ( $calcCount == true )
            {
                $price = ( $voucherInfo->correctPrice( $calcVAT, $product ) * $this->count() ) + $optionPrice;
            }
            else
            {
                $price = ( $voucherInfo->correctPrice( $calcVAT, $product ) + $optionPrice );
            }
        }

        return $price;
    }

    /*!
      Returns the correct localized price of the product.
    */
    function localeSavings( $calcCount=true, $withOptions=true, $calcVAT = false )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $inLanguage = $ini->variable( "eZTradeMain", "Language" );

        $locale = new eZLocale( $inLanguage );
        $currency = new eZCurrency();

        $price = $this->correctSavings( $calcCount, $withOptions, $calcVAT );

        $currency->setValue( $price );
        return $locale->format( $currency );
    }

    /*!
      Returns the correct savings of the product based on the logged in user, and the
      VAT status and use.
    */
    function correctSavings( $calcCount=true, $withOptions=true, $calcVAT = false )
    {
        $optionValues =& $this->optionValues();
        $product =& $this->product();

        $optionPrice = 0.0;
        if ( $withOptions )
        {
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();

                $price = $value->correctSavings( $calcVAT, $product );

                if ( $calcCount == true )
                    $price = $price * $optionValue->count();

                $optionPrice+=$price;
            }
        }

        if ( $calcCount == true )
        {
            $price = ( $product->correctSavings( $calcVAT ) * $this->count() ) + $optionPrice;
        }
        else
        {
            $price = ( $product->correctSavings( $calcVAT ) + $optionPrice );
        }

        return $price;
    }

    /*!
      Returns the price of the cart item.

      Options and count is calculated if not disabled.
    */
    function price( $calcCount=true, $withOptions=true )
    {
        $optionValues =& $this->optionValues();
        $product =& $this->product();

        $optionPrice = 0.0;
        if ( $withOptions )
        {
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();

                // the pricegroup is set in the datasupplier

                $PriceGroup = $GLOBALS["PriceGroup"];

                // get the value price if exists
                $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup,
                $option->id(), $value->id() );

                $found_price = false;

                if ( $price )
                {
                    $found_price = true;
                }

                // if not fetch the standard price
                if ( !$found_price )
                {
                    if ( $calcCount == true )
                        $price = $value->price() * $optionValue->count();
                    else
                        $price = $value->price();
                }

                $optionPrice += $price;
            }
        }

        if ( $calcCount == true )
        {
            $price = ( $product->price() * $this->count() ) + $optionPrice;
        }
        else
        {
            $price = ( $product->price() + $optionPrice );
        }
        return $price;
    }

    /*!
      Return the vat for the product.
    */
    function vat( $calcCount=true, $withOptions=true )
    {
        $vat = $this->correctPrice( $calcCount, $withOptions, true ) -
             $this->correctPrice( $calcCount, $withOptions, false );
        return $vat;
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
      Sets the cart.
    */
    function setCart( $cart )
    {
       if ( is_a( $cart, "eZCart" ) )
       {
           $this->CartID = $cart->id();
       }
    }

    /*!
      Sets the number of products.
    */
    function setCount( $count )
    {
       $this->Count = $count;
    }

    /*!
      Sets the voucherinformation.
    */
    function setVoucherInformation( $value )
    {
        if ( is_a ( $value, "eZVoucherInformation" ) )
        {
            $this->VoucherInformationID = $value->id();
        }
        else if ( is_numeric ( $value ) )
        {
            $this->VoucherInformationID = $value;
        }
    }

    /*!
      Returns the mail.
    */
    function mail( $asObject=true )
    {
        if ( $asObject )
        {
            if ( $this->MailMethod == 1 )
                $ret = new eZOnline( $this->MailID );
            else if ( $this->MailID == 2 )
                $ret = new eZAddress( $this->MailID );
        }
        else
            $ret = $this->MailID;
        return $ret;
    }

    /*!
      Sets the wishlist item.
    */
    function setWishListItem( $wishlist )
    {
       if ( is_a( $wishlist, "eZWishListItem" ) )
       {
           $this->WishListItemID = $wishlist->id();
       }
    }

    /*!
      Returns the wishlist item as a eZWishListItem object. 0 if the
    */
    function &wishListItem()
    {
       $ret = false;

       if ( ( $this->WishListItemID != 0 ) && is_numeric( $this->WishListItemID ) )
       {
           $ret = new eZWishListItem( $this->WishListItemID );
       }

       return $ret;
    }

    /*!
      Returns the voucher information.
    */
    function &voucherInformation()
    {
       $ret = false;

       if ( ( $this->VoucherInformationID != 0 ) && is_numeric( $this->VoucherInformationID ) )
       {
           $ret = new eZVoucherInformation( $this->VoucherInformationID );
       }

       return $ret;
    }

    /*!
      Returns all the option values as an array of eZCartOptionValue objects.

      An empty array is returned if none exists.
    */
    function &optionValues( )
    {
       $return_array = array();
       $db =& eZDB::globalDatabase();

       $db->array_query( $res_array, "SELECT ID FROM eZTrade_CartOptionValue
                                     WHERE
                                     CartItemID='$this->ID'
                                   " );

       foreach ( $res_array as $item )
       {
           $return_array[] = new eZCartOptionValue( $item[$db->fieldName( "ID" )] );
       }
       return $return_array;
    }

    var $ID;
    var $ProductID;
    var $CartID;
    var $Count;
    var $VoucherInformationID;

    /// ID to a wishlist item. Indicates which wishlistitem the cart item comes from. 0 if added from product.
    var $WishListItemID;
}

?>