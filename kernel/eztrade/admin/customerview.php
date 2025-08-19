<?php
// 
// $Id: customerview.php 8153 2001-11-01 13:05:52Z ce $
//
// Created on: <21-Sep-2001 16:06:44 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezlist.php" );

// include_once( "eztrade/classes/ezorder.php" );
// include_once( "eztrade/classes/ezwishlist.php" );
// include_once( "eztrade/classes/ezvoucher.php" );
// include_once( "eztrade/classes/ezvoucherused.php" );
// include_once( "ezuser/classes/ezuser.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "customerview.php" );

$languageINI = new eZINI( "kernel/eztrade/admin/intl/" . $Language . "/customerview.php.ini", false );

$t->set_file( "customer_view_tpl", "customerview.tpl" );
$t->set_block( "customer_view_tpl", "customer_account_number_box_tpl", "customer_account_number_box" );

// address
$t->set_block( "customer_view_tpl", "address_list_tpl", "address_list" );
$t->set_block( "address_list_tpl", "address_item_tpl", "address_item" );

// order
$t->set_block( "customer_view_tpl", "order_list_tpl", "order_list" );
$t->set_block( "order_list_tpl", "order_item_tpl", "order_item" );

// wish list
$t->set_block( "customer_view_tpl", "wish_list_tpl", "wish_list" );
$t->set_block( "wish_list_tpl", "wishlist_image_tpl", "wishlist_image" );
$t->set_block( "wish_list_tpl", "wishlist_item_tpl", "wishlist_item" );
$t->set_block( "wishlist_item_tpl", "is_bought_tpl", "is_bought" );
$t->set_block( "wishlist_item_tpl", "is_not_bought_tpl", "is_not_bought" );
$t->set_block( "wishlist_item_tpl", "wishlist_item_option_tpl", "wishlist_item_option" );

// vouchers
$t->set_block( "customer_view_tpl", "voucher_list_tpl", "voucher_list" );
$t->set_block( "voucher_list_tpl", "used_item_tpl", "used_item" );

$t->setAllStrings();

$customer = new eZUser( $CustomerID );

$t->set_var( "customer_id", $customer->id() );
$t->set_var( "customer_first_name", $customer->firstName() );
$t->set_var( "customer_last_name", $customer->lastName() );
$t->set_var( "customer_email", $customer->email() );

if ( $customer->accountNumber() != "" )
{
  $t->set_var( "customer_account_number", $customer->accountNumber() );
  $t->parse( "customer_account_number_box", "customer_account_number_box_tpl", true );
}else {
  $t->set_var( "customer_account_number", "N/A" );
}
$order = new eZOrder();
$orders =& $order->getByCustomer( $customer );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

// address list

$addressArray =& $customer->addresses();

foreach ( $addressArray as $address )
{    
    $t->set_var( "street1", $address->street1() );
    $t->set_var( "street2", $address->street2() );
    $t->set_var( "zip", $address->zip() );
    $t->set_var( "place", $address->place() );

    $country = $address->country();
    if( !is_bool( $country ) )
    {
        $t->set_var( "country", $country->name() );
    }
    else
    {
        $t->set_var( "country", "" );
    }
    $t->parse( "address_item", "address_item_tpl", true );
}

$t->parse( "address_list", "address_list_tpl" );


// order list
$t->set_var( "order_count", count( $orders ) );
$t->set_var( "wish_count", "" );
$u=0;
foreach ( $orders as $order )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );

    $t->set_var( "order_id", $order->id() );
    $t->set_var( "order_date", $order->id() );

    $status = $order->initialStatus( );
    $dateTime = $status->altered();
    $t->set_var( "order_date", $locale->format( $dateTime ) );

    $statusType = $status->type();
    $statusName = preg_replace( "#intl-#", "", $statusType->name() );
//    $statusName =  $languageINI->variable( "strings", $statusName );
    $t->set_var( "order_status", $statusName );

    $currency->setValue( $order->totalPrice() );
    $t->set_var( "order_price", $locale->format( $currency ) );

    $i++;
    $t->parse( "order_item",  "order_item_tpl", true );    
}

$wishlistObject  =new eZWishList();
$wishlist =& $wishlistObject->getByUser( $customer );

$t->set_var( "wish_count", "0" );
$t->set_var( "wish_list", "" );
if ( $wishlist )
{
    $items =& $wishlist->items();
    $count = count ( $items );
    $i=0;
    if ( $count > 0 )
    {
        foreach ( $items as $item )
        {
            $product =& $item->product();
            if ( $product )
            {
                if ( ( $i % 2 ) == 0 )
                    $t->set_var( "td_class", "bgdark" );
                else
                    $t->set_var( "td_class", "bglight" );
                
                $image =& $product->thumbnailImage();
                
                $t->set_var( "product_id", $product->id() );
                $t->set_var( "product_name", $product->name() );
                
                $t->set_var( "wishlist_item_id", $item->id() );
                
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

                if ( $item->isBought() )
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

                $t->set_var( "product_price", $locale->format( $currency ) );

                $t->set_var( "wishlist_item_count", $item->count() );
    
                $t->set_var( "product_price", $locale->format( $currency ) );

                $t->set_var( "wishlist_item_option", "" );

                $optionValues =& $item->optionValues();

                foreach ( $optionValues as $optionValue )
                {
                    $option =& $optionValue->option();
                    $value =& $optionValue->optionValue();
                        
                    $t->set_var( "option_name", $option->name() );
                        
                    $descriptions =& $value->descriptions();
                    $t->set_var( "option_value", $descriptions[0] );
                        
                    $t->parse( "wishlist_item_option", "wishlist_item_option_tpl", true );
                }
                    
                $i++;
                $t->parse( "wishlist_item", "wishlist_item_tpl", true );
            }
        }
        $t->set_var( "wish_count", $count );
        $t->parse( "wish_list", "wish_list_tpl" );
    }
}

$t->set_var( "voucher_list", "" );
$vouchers = "0";
$voucherObject = new eZVoucherUsed();
$vouchers =& $voucherObject->getByUser( $customer );
$count = count ( $vouchers );
$t->set_var( "voucher_count", $count );
$t->set_var( "used_item", "" );
if ( $count > 0 )
{
    $i=0;
    foreach ( $vouchers as $used )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bgdark" );
        else
            $t->set_var( "td_class", "bglight" );

        $currency->setValue( $used->price() );
        $t->set_var( "used_price", $locale->format( $currency ) );
        $t->set_var( "used_used", $locale->format( $used->used() ) );

        $localUser =& $used->user();

        $t->set_var( "user_name", $localUser->firstName() . " " . $localUser->lastName() );
        $t->set_var( "user_id", $localUser->id() );

        $order =& $used->order();
        $voucher =& $used->voucher( false );
        $t->set_var( "voucher_id", $voucher );

        $t->set_var( "order_id", $order->id() );
        $t->parse( "used_item", "used_item_tpl", true );
        $i++;
    }
        $t->parse( "voucher_list", "voucher_list_tpl" );
}


if ( count( $orders ) > 0 )
{
    $t->parse( "order_list",  "order_list_tpl" );
}
else
{
    $t->set_var( "order_list", "" );
}

$t->pparse( "output", "customer_view_tpl" );

?>
