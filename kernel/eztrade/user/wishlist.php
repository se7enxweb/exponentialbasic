<?php
//
// $Id: wishlist.php 8850 2002-01-02 21:42:12Z kaid $
//
// Created on: <21-Oct-2000 18:09:45 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );

// include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezoption.php" );
// include_once( "eztrade/classes/ezoptionvalue.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezwishlist.php" );
// include_once( "eztrade/classes/ezwishlistitem.php" );
// include_once( "eztrade/classes/ezwishlistoptionvalue.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );


$wishlist = new eZWishlist();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}


// your own wishlist
$user =& eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /trade/customerlogin/?RedirectURL=/trade/wishlist/" );
    exit();
}

$wishlist = $wishlist->getByUser( $user );


if ( !$wishlist )
{
    $wishlist = new eZWishlist();
    $wishlist->setUser( $user );

    $wishlist->store();
}

if ( isset( $Action ) && $Action == "AddToBasket" )
{
    $product = new eZProduct( $ProductID );

    // check if a product like this is already in the basket.
    // if so-> add the count value.
    $productAddedToWishlist = false;
    {
        // fetch the cart items
        $items =& $wishlist->items( );

        foreach ( $items as $item )
        {
            $productItem =& $item->product();
            // the same product
            if ( ( $ProductID == $productItem->id() ) && ( $productAddedToWishlist == false ) )
            {
                $optionValues =& $item->optionValues();

                if ( count( $optionValues ) > 0 )
                { // product with options
                    $hasTheSameOptions = true;
                    
                    foreach ( $optionValues as $optionValue )
                    {
                        $option =& $optionValue->option();
                        $value =& $optionValue->optionValue();                        

                        $optionValueFound = false;
                        
                        if ( count( $OptionValueArray ) > 0 )
                        {
                            $i=0;
                            foreach ( $OptionValueArray as $valueItem )
                            {
                                if ( ( $OptionIDArray[$i] == $option->id() )
                                     && ( $valueItem == $value->id() ) )
                                {
                                    $optionValueFound = true;
                                }
                                $i++;
                            }
                        }
                        
                        if ( $optionValueFound == false )
                        {
                            $hasTheSameOptions = false;
                        }
                    }

                    if ( $hasTheSameOptions == true )
                    {
                        $item->setCount( $item->count() + 1 );
                        $item->store();
                        $productAddedToWishlist = true;
                    }
                }
                else
                { // product without options
                    if ( count( $OptionValueArray ) == 0 )
                    {
                        $item->setCount( $item->count() + 1 );
                        $item->store();
                        $productAddedToWishlist = true;
                    }
                }
            }
        }
    }
    
    if ( $productAddedToWishlist == false )
    {
        $wishlistItem = new eZWishlistItem();

        $wishlistItem->setProduct( $product );
        $wishlistItem->setWishlist( $wishlist );

        $wishlistItem->store();

        if ( count( $OptionValueArray ) > 0 )
        {
            $i = 0;
            foreach ( $OptionValueArray as $value )
            {

                $option = new eZOption( $OptionIDArray[$i] );
                $optionValue = new eZOptionValue( $value );

                $wishlistOption = new eZWishlistOptionValue();
                $wishlistOption->setWishlistItem( $wishlistItem );
                $wishlistOption->setOption( $option );
                $wishlistOption->setOptionValue( $optionValue );

                $wishlistOption->store();

                $i++;
            }
        }
    }

    eZHTTPTool::header( "Location: /trade/wishlist/" );

    exit();
}


if ( isset( $Action ) && $Action == "RemoveFromWishlist" )
{
    $wishListItem = new eZWishListItem( );
    if ( $wishListItem->get( $WishListItemID ) )
    {
        $wishListItem->delete();
    }

    eZHTTPTool::header( "Location: /trade/wishlist/" );

    exit();
}


if ( isset( $Action ) && $Action == "Refresh" )
{
    $i=0;
    if ( count( $WishlistIDArray ) > 0 )
    foreach ( $WishlistIDArray as $wishlistID )
    {
        $wishlistItem = new eZWishlistItem( $wishlistID );
        $wishlistItem->setCount( $WishlistCountArray[$i] );
        $wishlistItem->store();
        $i++;
    }

    if ( isset( $IsPublicButton ) )
    {
        $wishlist->setIsPublic( !$wishlist->isPublic() );
        $wishlist->store();
    }
    else
    {        
        // set public/private
        if ( $IsPublic != "" )
        {
            $wishlist->setIsPublic( true );
            $wishlist->store();
        }
        else
        {
            $wishlist->setIsPublic( false );
            $wishlist->store();        
        }
    }
}


if ( isset( $Action ) && $Action == "MoveToCart" )
{
    $wishListItem = new eZWishListItem( );
    if ( $wishListItem->get( $WishListItemID ) )
    {
        $product = $wishListItem->product();
        $Quantity = $product->totalQuantity();
        if ( !$product->hasPrice() )
        {
            $optionValues =& $wishListItem->optionValues();
            $Quantity = 0;
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                if ( $value_quantity > 0 )
                    $Quantity = $value_quantity;
            }
        }

        if ( $product->hasQuantity( $RequireQuantity ) )
        {
            $wishListItem->moveToCart();
            $wishListItem->delete();
        }
    }

    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "wishlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "wishlist_page_tpl" => "wishlist.tpl"
    ) );


$t->set_block( "wishlist_page_tpl", "public_wishlist_tpl", "public_wishlist" );
$t->set_block( "wishlist_page_tpl", "non_public_wishlist_tpl", "non_public_wishlist" );

$t->set_block( "wishlist_page_tpl", "empty_wishlist_tpl", "empty_wishlist" );

$t->set_block( "wishlist_page_tpl", "wishlist_image_tpl", "wishlist_image" );


$t->set_block( "wishlist_page_tpl", "wishlist_item_list_tpl", "wishlist_item_list" );
$t->set_block( "wishlist_item_list_tpl", "product_available_header_tpl", "product_available_header" );
$t->set_block( "wishlist_item_list_tpl", "wishlist_item_tpl", "wishlist_item" );
$t->set_block( "wishlist_item_tpl", "product_available_item_tpl", "product_available_item" );
$t->set_block( "wishlist_item_tpl", "move_to_cart_item_tpl", "move_to_cart_item" );
$t->set_block( "wishlist_item_tpl", "no_move_to_cart_item_tpl", "no_move_to_cart_item" );
$t->set_block( "wishlist_item_tpl", "wishlist_item_option_tpl", "wishlist_item_option" );
$t->set_block( "wishlist_item_option_tpl", "wishlist_item_option_availability_tpl", "wishlist_item_option_availability" );

$t->set_block( "wishlist_item_tpl", "is_bought_tpl", "is_bought" );
$t->set_block( "wishlist_item_tpl", "is_not_bought_tpl", "is_not_bought" );

$t->set_block( "wishlist_page_tpl", "wishlist_checkout_tpl", "wishlist_checkout" ); //SF

$t->set_var( "public_wishlist", "" );
$t->set_var( "non_public_wishlist", "" );
if ( $wishlist->isPublic() == true )
{
    $t->parse( "public_wishlist", "public_wishlist_tpl" );
}
else
{
    $t->parse( "non_public_wishlist", "non_public_wishlist_tpl" );
}

// fetch the wishlist items
$items = $wishlist->items( );



$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$t->set_var( "product_available_header", "" );
if ( $ShowQuantity )
    $t->parse( "product_available_header", "product_available_header_tpl" );

$i = 0;
$sum = 0.0;
foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

    $product =& $item->product();

    $t->set_var( "wishlist_item_id", $item->id() );

    $image =& $product->thumbnailImage();

    if ( $image )
    {
        $thumbnail =& $image->requestImageVariation( 35, 35 );

        $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
        $t->set_var( "product_image_width", $thumbnail->width() );
        $t->set_var( "product_image_height", $thumbnail->height() );
        $t->set_var( "product_image_caption", $image->caption() );
        $t->parse( "wishlist_image", "wishlist_image_tpl" );
    }
    else
    {
        $t->set_var( "wishlist_image", "&nbsp;" );
    }

    if ( $item->isBought() == true )
    {
        $t->set_var( "is_not_bought", "" );
        $t->parse( "is_bought", "is_bought_tpl" );
    }
    else
    {
        $t->set_var( "is_bought", "" );
        $t->parse( "is_not_bought", "is_not_bought_tpl" );
    }
    
    // product price
    $price = $item->price();    
    $currency->setValue( $price );
    $sum += $price;
    
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );

    $optionValues =& $item->optionValues();

    $Quantity = $product->totalQuantity();
    if ( !$product->hasPrice() )
    {
        $Quantity = 0;
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();
            $value_quantity = $value->totalQuantity();
            if ( $value_quantity > 0 )
                $Quantity = $value_quantity;
        }
    }
    $t->set_var( "product_available_item", "" );
    if ( $ShowQuantity )
    {
        $NamedQuantity = $Quantity;
        if ( $ShowNamedQuantity )
            $NamedQuantity = eZProduct::namedQuantity( $Quantity );
        $t->set_var( "product_availability", $NamedQuantity );
        $t->parse( "product_available_item", "product_available_item_tpl" );
    }
    $t->set_var( "wishlist_item_count", $item->count() );
    
    $t->set_var( "product_price", $locale->format( $currency ) );

    $t->set_var( "wishlist_item_option", "" );
    $min_quantity = $Quantity;
    foreach ( $optionValues as $optionValue )
    {
        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();
        $value_quantity = $value->totalQuantity();

        $t->set_var( "option_name", $option->name() );

        $descriptions =& $value->descriptions();
        $t->set_var( "option_value", $descriptions[0] );

        $t->set_var( "wishlist_item_option_availability", "" );
        if ( !(is_bool( $value_quantity ) and !$value_quantity) )
        {
            if ( is_bool( $min_quantity ) )
                $min_quantity =  $value_quantity;
            else
                $min_quantity = min( $min_quantity , $value_quantity );
            $named_quantity = $value_quantity;
            if ( $ShowNamedQuantity )
                $named_quantity = eZProduct::namedQuantity( $value_quantity );
            if ( $ShowOptionQuantity )
            {
                $t->set_var( "option_availability", $named_quantity );
                $t->parse( "wishlist_item_option_availability", "wishlist_item_option_availability_tpl" );
            }
        }

        $t->parse( "wishlist_item_option", "wishlist_item_option_tpl", true );
    }

    $t->set_var( "move_to_cart_item", "" );
    $t->set_var( "no_move_to_cart_item", "" );
    if ( (is_bool( $min_quantity ) and !$min_quantity) or
         !$RequireQuantity or ( $RequireQuantity and $min_quantity > 0 ) )
    {
        $t->parse( "move_to_cart_item", "move_to_cart_item_tpl" );
    }
    else
    {
        $t->parse( "no_move_to_cart_item", "no_move_to_cart_item_tpl" );
    }

    $t->parse( "wishlist_item", "wishlist_item_tpl", true );

    $i++;
}


$currency->setValue( $sum );
$t->set_var( "wishlist_sum", $locale->format( $currency ) );


if ( count( $items ) > 0 )
{
    $t->parse( "wishlist_item_list", "wishlist_item_list_tpl" );
    $t->set_var( "empty_wishlist", "" );
}
else
{
    $t->parse( "empty_wishlist", "empty_wishlist_tpl" );
    $t->set_var( "wishlist_item_list", "" );
}

if ( count( $items ) > 0 )
{
    $t->parse( "wishlist_checkout", "wishlist_checkout_tpl" );
}
else
{
    $t->set_var( "wishlist_checkout", "" );
}


$t->pparse( "output", "wishlist_page_tpl" );

?>

