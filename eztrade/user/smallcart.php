<?php
//
// $Id: smallcart.php 7248 2001-09-15 12:37:19Z pkej $
//
// Created on: <12-Dec-2000 15:21:10 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezshippingtype.php" );
include_once( "eztrade/classes/ezcartitem.php" );
include_once( "eztrade/classes/ezcartoptionvalue.php" );
include_once( "ezsession/classes/ezsession.php" );

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


$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "smallcart.php" );


$t->setAllStrings( true );

$t->set_file( "cart_page_tpl", "smallcart.tpl" );

$t->set_block( "cart_page_tpl", "cart_checkout_tpl", "cart_checkout" );
$t->set_block( "cart_page_tpl", "empty_cart_tpl", "empty_cart" );


$t->set_block( "cart_page_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );


// fetch the cart items
$items =& $cart->items( );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();


$i = 0;
$sum = 0.0;
$totalVAT = 0.0;
$can_checkout = true;
$t->set_var( "cart_item", "" );


foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
    $t->set_var( "cart_item_id", $item->id() );

    $product = $item->product();
    if ( $product )
    {
        $price = $product->price() * $item->count();

        $currency->setValue( $price );

        $priceobj = new eZCurrency();

        if ( ( isset( $RequireUserLogin ) && !$RequireUserLogin or is_a( $user, "eZUser" ) ) and
             $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
        {
            $t->set_var( "product_price", $item->localePrice( true, true, $PricesIncludeVAT ) );
        }
        else
        {
           $t->set_var( "product_price", $item->localePrice( true, true, $PricesIncludeVAT ) );
        }

        // product price
        $currency->setValue( $price );

        $sum += $price;


        $t->set_var( "product_id", $product->id() );
        $t->set_var( "product_name", $product->name() );

        $t->set_var( "cart_item_count", $item->count() );

        $optionValues =& $item->optionValues();
        $Quantity = $product->totalQuantity();

        $min_quantity = false;
        if ( !$product->hasPrice() )
        {
            foreach ( $optionValues as $optionValue )
            {
                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                if ( !(is_bool( $value_quantity ) and !$value_quantity) )
                {
                    if ( is_bool( $min_quantity ) )
                        $min_quantity = $value_quantity;
                    else
                        $min_quantity = min( $min_quantity , $value_quantity );
                }
            }
        }
        if ( !(is_bool( $min_quantity ) and !$min_quantity) and
             $RequireQuantity and $min_quantity == 0 )
            $can_checkout = false;

        if ( $product->discontinued() )
            $can_checkout = false;

        $t->parse( "cart_item", "cart_item_tpl", true );
    }

    $i++;
}


// shipping cost and VAT
$cart->cartTotals( $tax, $total );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$currency->setValue( $total["shipinctax"] );
$t->set_var( "shipping_sum", $locale->format( $currency ) );

$currency->setValue( $total["inctax"] );
$t->set_var( "cart_sum", $locale->format( $currency ) );

$currency->setValue( $total["tax"] );
$t->set_var( "cart_vat_sum", $locale->format( $currency ) );



if ( count( $items ) > 0 and $can_checkout )
{
    $t->parse( "cart_checkout", "cart_checkout_tpl" );
}
else
{
    $t->set_var( "cart_checkout", "" );
}

if ( count( $items ) > 0 )
{
    $t->parse( "cart_item_list", "cart_item_list_tpl" );
    $t->set_var( "empty_cart", "" );
}
else
{
    $t->parse( "empty_cart", "empty_cart_tpl" );
    $t->set_var( "cart_item_list", "" );
}

$t->set_var( "sitedesign", $GlobalSiteDesign );

$t->pparse( "output", "cart_page_tpl" );

?>