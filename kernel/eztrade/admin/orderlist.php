<?php
// 
// $Id: orderlist.php 8562 2001-11-21 16:10:04Z br $
//
// Created on: <30-Sep-2000 13:03:13 bf>
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

// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezorder.php" );
// include_once( "eztrade/classes/ezpreorder.php" );

// include_once( "eztrade/classes/ezorderstatustype.php" );

//echo $HideFollowup;
//print_r($HTTP_POST_VARS);
//exit();



if ( isset( $Hide ) )
{
    $session =& eZSession::globalSession();
    $session->setVariable( "OrderList", "Hide" );
}

if ( isset( $Show ) )
{
    $session =& eZSession::globalSession();
    $session->setVariable( "OrderList", "Show" );
}

$checkMode =& eZSession::globalSession();
if ( $checkMode->variable( "OrderList" ) == "Hide" )
    $HideFollowup = "on";
else
	$HideFollowup = "";

if( isset( $Delete ) && count( $OrderArrayID ) > 0 )
{
    foreach( $OrderArrayID as $orderid )
    {
        $order = new eZOrder( $orderid );
        $order->delete();
        $preOrder = new eZPreOrder();
        $preOrder->getByOrderID( $orderid );
        $preOrder->setOrderID( 0 );
        $preOrder->store();
    }
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "orderlist.php" );

$languageINI = new INIFile( "kernel/eztrade/admin/intl/" . $Language . "/orderlist.php.ini", false );

$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "AdminShowIncTaxColumn" ) == "enabled" ? true : false;


$t->setAllStrings();

$t->set_file( "order_list_tpl", "orderlist.tpl" );

$t->set_block( "order_list_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_item_list_tpl", "order_item_tpl", "order_item" );

// next prvious
$t->set_block( "order_list_tpl", "previous_tpl", "previous" );
$t->set_block( "order_list_tpl", "next_tpl", "next" );

$t->set_var( "site_style", $SiteDesign );

if ( isset( $URLQueryText ) )
{
    $QueryText = urldecode( $URLQueryText );
}
else
{
    $QueryText = "";
}

$t->set_var( "query_string", $QueryText );

if ( $HideFollowup = "on" )
	$t->set_var( "followup_checked", "checked" );
else
	$t->set_var( "followup_checked", "" );

$t->set_var( "previous", "" );
$t->set_var( "next", "" );

if ( !isset( $OrderBy ) )
    $OrderBy = "Date";

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = 15;

$t->set_var( "current_offset", $Offset );

$order = new eZOrder();

// perform search
if ( isset( $QueryText ) && $QueryText != "" )
{
    $orderArray = $order->search( $QueryText, $Offset, $Limit );
    $total_count = $order->getSearchCount( $QueryText );
}
else
{
    $orderArray = $order->getAll( $Offset, $Limit, $OrderBy );
    $total_count = $order->getTotalCount( );
}

if ( !$orderArray )
    $t->set_var( "order_item", "" );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
$i = 0;

foreach ( $orderArray as $order )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "order_id", $order->id() );

/*
	echo "<pre>";
	print_r($status);
    $dateTime = $status->altered();	
	
	echo $locale->format( $dateTime )."<br>";
    $dateTime = $status->altered();	

//	echo $locale->format( $dateTime )."<br>";
	print_r( $order->lastStatus() );
	echo "</pre>";
	exit();  */
    $status = $order->initialStatus();		
    $dateTime = $status->altered();
    $t->set_var( "order_date", $locale->format( $dateTime ) );
    $dateTime = $status->rawAltered();
    
    $status = $order->lastStatus();
    $lastDateTime = $status->altered();	
    $t->set_var( "altered_date", $locale->format( $lastDateTime ) );
    $lastDateTime = $status->rawAltered();	

//	if ($dateTime == $lastDateTime)
//		$lastDateTime = time();

	$statusAge = ( round( (( time()-$lastDateTime )/86400),0 ) );

	$t->set_var( "age_color", "smallgreen" );  //age since last status change - green
	
	if ( $statusAge > 7 )  //turns amber after one week
		$t->set_var( "age_color", "smallamber" );
		
	if ( $statusAge > 42 )  //turns red after six weeks
		$t->set_var( "age_color", "smallred" );
	
	$t->set_var( "status_age", $statusAge );
	
    
    $statusType = $status->type();
    $statusTypeName = !is_null( $statusType->name() ) ? $statusType->name() : false;
    $statusName = preg_replace( "#intl-#", "", $statusTypeName );
//    $statusName =  $languageINI->read_var( "strings", $statusName );
    $t->set_var( "order_status", $statusName );
	
	$user = $order->user();
	
	if ( $user )
	{
    	if ( $order->personID() == 0 && $order->companyID() == 0 )
    	{
        $t->set_var( "customer_email", $user->email() );
        $t->set_var( "customer_first_name", $user->firstName() );
        $t->set_var( "customer_last_name", $user->lastName() );
    	}
    	else
    	{
        	if ( $order->personID() > 0 )
        	{
            $customer = new eZPerson( $order->personID() );
            $t->set_var( "customer_first_name", $customer->firstName() );
            $t->set_var( "customer_last_name", $customer->lastName() );
        	}
        	else
        	{
            $customer = new eZCompany( $order->companyID() );
            $t->set_var( "customer_first_name", $customer->name() );
            $t->set_var( "customer_last_name", "" );
        	}
        $emailList = $customer->emailAddress();
        $t->set_var( "customer_email", $emailList[0] );
    	}
	}

    $order->orderTotals( $tax, $total );
    
    if ( $PricesIncludeVAT == true )
        $currency->setValue( $total["inctax"] );
    else
        $currency->setValue( $total["extax"] );
    
    // if ( $order->isVATInc() == true )
    //     $currency->setValue( $order->totalPriceIncVAT() + $order->shippingCharge());
    // else
    //     $currency->setValue( $order->totalPrice() + $order->shippingCharge() );

    $t->set_var( "order_price", $locale->format( $currency ) );
    
    $t->parse( "order_item", "order_item_tpl", true );
    $i++;
}

eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "order_list_tpl" );


$t->set_var( "url_query_string", isset( $QueryText ) ? urlencode( $QueryText ) : '' );
$t->parse( "order_item_list", "order_item_list_tpl" );
$t->pparse( "output", "order_list_tpl" );

?>