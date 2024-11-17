<?php
// 
// $Id: productreport.php 6230 2001-07-20 11:28:54Z jakobn $
//
// Created on: <11-Jan-2001 14:47:56 bf>
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
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZStatsMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );

include_once( "ezstats/classes/ezpageview.php" );
include_once( "ezstats/classes/ezpageviewquery.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezorder.php" );

$t = new eZTemplate( "ezstats/admin/" . $ini->read_var( "eZStatsMain", "AdminTemplateDir" ),
                     "ezstats/admin/intl", $Language, "productreport.php" );

$t->setAllStrings();

$t->set_file( array(
    "product_report_tpl" => "productreport.tpl"
    ) );

$t->set_block( "product_report_tpl", "most_viewed_product_tpl", "most_viewed_product" );
$t->set_block( "product_report_tpl", "most_added_to_cart_products_tpl", "most_added_to_cart_products" );
$t->set_block( "product_report_tpl", "most_added_to_wishlist_products_tpl", "most_added_to_wishlist_products" );
$t->set_block( "product_report_tpl", "most_bought_products_tpl", "most_bought_products" );

$t->set_block( "product_report_tpl", "month_tpl", "month" );
$t->set_block( "month_tpl", "month_previous_tpl", "month_previous" );
$t->set_block( "month_tpl", "month_previous_inactive_tpl", "month_previous_inactive" );
$t->set_block( "month_tpl", "month_next_tpl", "month_next" );
$t->set_block( "month_tpl", "month_next_inactive_tpl", "month_next_inactive" );

if ( !is_numeric( $Year ) || !is_numeric( $Month ) )
{
    $cur_date = new eZDate();
    $Year = $cur_date->year();
    $Month = $cur_date->month();
}

$query = new eZPageViewQuery();

$tmpProduct = new eZProduct();

// most viewed products
$productReport =& $query->topProductRequests( );

$productArray = array();
foreach ( $productReport as $product )
{
    if ( preg_match( "#^/trade/productview/(.*?)/#", $product["URI"], $regArray ) )
    {
        $idx = $regArray[1];
        
        $count = $productArray[$idx]["Count"];
        
        $productArray[$idx]["Count"] = $count + $product["Count"];
        $productArray[$idx]["ID"] = $regArray[1];
    }
}

if ( !empty( $productArray ) )
{
    $i = 0;
    foreach ( $productArray as $productItem )
    {
        $t->set_var( "bg_color", $i % 2 == 0 ? "bglight" : "bgdark" );
        $t->set_var( "product_name", $tmpProduct->productName( $productItem["ID"] ) );
        $t->set_var( "view_count", $productItem["Count"] );

        $t->parse( "most_viewed_product", "most_viewed_product_tpl", true );
        ++$i;
    }
}
else
{
    $t->set_var( "most_viewed_product", "" );
}

// mostly added to cart

$productReport =& $query->topProductAddToCart( );

$productArray = array();
foreach ( $productReport as $product )
{
    if ( preg_match( "#^/trade/cart/add/(.*?)/#", $product["URI"], $regArray ) )
    {
        $idx = $regArray[1];
        
        $count = $productArray[$idx]["Count"];
        
        $productArray[$idx]["Count"] = $count + $product["Count"];
        $productArray[$idx]["ID"] = $regArray[1];
    }
}

if ( !empty( $productArray ) )
{
    $i = 0;
    foreach ( $productArray as $productItem )
    {
        $t->set_var( "bg_color", $i % 2 == 0 ? "bglight" : "bgdark" );
        $t->set_var( "product_name", $tmpProduct->productName( $productItem["ID"]  ) );
        $t->set_var( "add_count", $productItem["Count"] );

        $t->parse( "most_added_to_cart_products", "most_added_to_cart_products_tpl", true );
        ++$i;
    }
}
else
{
    $t->set_var( "most_added_to_cart_products", "" );
}

//  // mostly added to wishlist

$productReport =& $query->topProductAddToWishlist( );

$productArray = array();
foreach ( $productReport as $product )
{

    if ( preg_match( "#^/trade/wishlist/add/(.*?)#", $product["URI"], $regArray ) )
    {
        $idx = $regArray[1];
        
        $count = $productArray[$idx]["Count"];
        
        $productArray[$idx]["Count"] = $count + $product["Count"];
        $productArray[$idx]["ID"] = $regArray[1];
    }
}

if ( !empty( $productArray ) )
{
    $i = 0;
    foreach ( $productArray as $productItem )
    {
        $t->set_var( "bg_color", $i % 2 == 0 ? "bglight" : "bgdark" );
        $t->set_var( "product_name", $tmpProduct->productName( $productItem["ID"]  ) );
        $t->set_var( "add_count", $productItem["Count"] );

        $t->parse( "most_added_to_wishlist_products", "most_added_to_wishlist_products_tpl", true );
        ++$i;
    }
} 
else
{
    $t->set_var( "most_added_to_wishlist_products", "" );
}
 
// Most bought product

$order = new eZOrder();
$productReport =& $order->mostPopularProduct();

if ( !empty( $productReport ) )
{
    $i = 0;
    foreach ( $productReport as $productItem )
    {
        $t->set_var( "bg_color", $i % 2 == 0 ? "bglight" : "bgdark" );
        $t->set_var( "product_name", $tmpProduct->productName( $productItem["ProductID"]  ) );

        $t->set_var( "buy_count", $productItem["Count"] );
        $t->set_var( "total_buy_count", $productItem["RealCount"] );

        $t->parse( "most_bought_products", "most_bought_products_tpl", true );
        ++$i;
    }
}
else
{
    $t->set_var( "most_bought_products", "" );
}

    
$next_month = new eZDate( $Year, $Month, 1, 0, 1, 0 );
$prev_month = new eZDate( $Year, $Month, 1, 0, -1, 0 );

$t->set_var( "next_month", $next_month->month() );
$t->set_var( "previous_month", $prev_month->month() );
$t->set_var( "next_year", $next_month->year() );
$t->set_var( "previous_year", $prev_month->year() );

$t->set_var( "month_next_inactive", "" );
$t->set_var( "month_next", "" );
$t->set_var( "month_previous", "" );
$t->set_var( "month_previous_inactive", "" );

$cur_date = new eZDate();

if ( $cur_date->isGreater( $next_month ) )
    $t->parse( "month_next_inactive", "month_next_inactive_tpl" );
else
    $t->parse( "month_next", "month_next_tpl" );

$t->parse( "month_previous", "month_previous_tpl" );

$t->parse( "month", "month_tpl" );

$t->set_var( "this_month", $Month );
$t->set_var( "this_year", $Year );


$t->pparse( "output", "product_report_tpl" );
 

?>
