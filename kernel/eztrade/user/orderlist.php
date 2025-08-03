<?php
// 
// $Id: orderlist.php 7813 2001-10-12 12:11:39Z ce $
//
// Created on: <21-Sep-2001 17:41:07 ce>
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
// include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowOrderStatusToUser = $ini->read_var( "eZTradeMain", "ShowOrderStatusToUser" ) == "true";

// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezorder.php" );

// include_once( "eztrade/classes/ezorderstatustype.php" );

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "orderlist.php" );

$languageINI = new INIFile( "kernel/eztrade/user/intl/" . $Language . "/orderlist.php.ini", false );

$t->setAllStrings();

$t->set_file( "order_list_tpl", "orderlist.tpl" );

$t->set_block( "order_list_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_status_header_tpl", "order_status_header" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );

$t->set_block( "order_item_tpl", "order_status_tpl", "order_status" );

$t->set_var( "site_style", $SiteDesign );

$t->set_var( "query_string", isset( $QueryText ) ? $QueryText : '' );

if ( !isset( $OrderBy ) )
    $OrderBy = "Date";
if ( !isset( $Limit ) )
    $Limit = 20;

$t->set_var( "current_offset", $Offset );

$order = new eZOrder();

$user =& eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /trade/customerlogin/?RedirectURL=/trade/orderlist/" );
    exit();
}

$orderArray = $order->getByUser( $Offset, $Limit, $OrderBy, $user );
$total_count = $order->getCountByUser( $user );
if ( !$orderArray )
    $t->set_var( "order_item", "" );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
$i = 0;

$t->set_var( "full_name", $user->firstName() . " " . $user->lastName() );

if ( $ShowOrderStatusToUser )
    $t->parse( "order_status_header", "order_status_header_tpl" );
else
$t->set_var( "order_status_header" );

foreach ( $orderArray as $order )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "order_id", $order->id() );

    $status = $order->lastStatus( );
    $dateTime = $status->altered();
    $t->set_var( "order_date", $locale->format( $dateTime ) );

    if ( $ShowOrderStatusToUser )
    {
        $statusType = $status->type();

        if ( $statusType && $statusType->name() != null )
            $statusName = preg_replace( "#intl-#", "", $statusType->name() );
        else
            $statusName = "Pending";
        // $statusName =  $languageINI->read_var( "strings", $statusName );
        $t->set_var( "order_status", $statusName );

        $t->parse( "order_status", "order_status_tpl" );
    }
    else
        $t->set_var( "order_status", "" );
        
/*        
    $currency->setValue( $order->totalPrice() );
    $t->set_var( "order_price", $locale->format( $currency ) );
*/    
	
	
	$order->orderTotals( $tax, $total );
    
    if ( isset( $_GET["PricesIncludeVAT"] ) && $PricesIncludeVAT == true )
        $currency->setValue( $total["inctax"] );
    else
        $currency->setValue( $total["extax"] );    

    $t->set_var( "order_price", $locale->format( $currency ) );
    
    $t->parse( "order_item", "order_item_tpl", true );
    $i++;
}

eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "order_list_tpl" );


$t->parse( "order_item_list", "order_item_list_tpl" );
$t->pparse( "output", "order_list_tpl" );

?>