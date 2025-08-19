<?php
// 
// $Id: orderedit.php 9167 2002-02-07 08:39:29Z jhe $
//
// Created on: <30-Sep-2000 13:03:13 bf>
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

if ( isset( $Cancel ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/orderlist/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";

$languageINI = new eZINI( "kernel/eztrade/admin/intl/" . $Language . "/orderedit.php.ini", false );

$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" );
// Unclear: $PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" && $total["shiptax"] ? true : false;

$ShowExTaxColumn = $ini->variable( "eZTradeMain", "AdminShowExTaxColumn" ) == "enabled" ? true : false;
//$ShowIncTaxColumn = $ini->variable( "eZTradeMain", "AdminShowIncTaxColumn" ) == "enabled" && $total["shiptax"]  ? true : false;
// Unclear:$ShowIncTaxColumn = $total["shiptax"] ? true : false;
$ShowExTaxTotal = $ini->variable( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
$ColSpanSizeTotals = $ini->variable( "eZTradeMain", "ColSpanSizeTotals" );
$wwwDir = $ini->variable( "site", "UserSiteURL" );
$adminEmail = $ini->variable( "eZTradeMain", "mailToAdmin" );
$upscheck = $ini->variable( "eZTradeMain", "UPSXMLShipping" )=="enabled"?1:0;
$uspscheck = $ini->variable( "eZTradeMain", "USPSXMLShipping" )=="enabled"?1:0;
if(($upscheck==0)&&($uspscheck==0))
$checkups=0;
else
$checkups=1;

$tester =  0;
if ( $Action == "newstatus" )
{
    $status = new eZOrderStatus();
    
    // store the status
    $statusType = new eZOrderStatusType( $StatusID );

    $status = new eZOrderStatus();
    $status->setType( $statusType );
    $status->setComment( $StatusComment );
    $status->setOrderID( $OrderID );

    $user =& eZUser::currentUser();

    $status->setAdmin( $user );

    $status->store();            

	//send customer email notice of status change
	if ( $MailNotice == "on" )
	{
		$mailTemplate = new eZTemplate( "eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "orderedit.php" );
					 
        $mailTemplate->setAllStrings();

        $subjectLine = $mailTemplate->Ini->variable( "strings", "subject" );
        $subjectLine = $subjectLine . " #" . $OrderID;
		
	    $statusName = preg_replace( "#intl-#", "", $statusType->Name() );
		$statusName = $mailTemplate->Ini->variable( "strings", "reason" ).": ".$statusName;
		$StatusComment = $mailTemplate->Ini->variable( "strings", "comment" ).": ".$StatusComment;
				
		$MailBody = "$MailBody\n";
		$MailBody .= "========================================\n";
		$MailBody .= "$statusName\n";
		$MailBody .= "$StatusComment";
		
        // send a notice mail
        $noticeMail = new eZMail();

        $noticeMail->setFrom( $adminEmail );
//        $noticeMail->setCc( $adminEmail );
		
        $noticeMail->setTo( $CustomerEmail );

        $noticeMail->setSubject( $subjectLine );
        $noticeMail->setBodyText( $MailBody );

        $noticeMail->send();
	}		
        

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/orderedit/$OrderID" );
    exit();
}


if ( $Action == "delete" )
{
    $order = new eZOrder( $OrderID );
    $order->delete();
    
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/orderlist/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "orderedit.php" );

$t->setAllStrings();

$t->set_file( "order_edit_tpl", "orderedit.tpl" );

$t->set_block( "order_edit_tpl", "visa_tpl", "visa" );
$t->set_block( "order_edit_tpl", "mastercard_tpl", "mastercard" );
$t->set_block( "order_edit_tpl", "cod_tpl", "cod" );
$t->set_block( "order_edit_tpl", "invoice_tpl", "invoice" );
$t->set_block( "order_edit_tpl", "email_followup_tpl", "email_followup" );

$t->set_block( "order_edit_tpl", "order_status_option_tpl", "order_status_option" );
$t->set_block( "order_edit_tpl", "order_status_history_tpl", "order_status_history" );

$t->set_block( "order_edit_tpl", "full_cart_tpl", "full_cart" );
$t->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "header_savings_item_tpl", "header_savings_item" );
$t->set_block( "cart_item_list_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );
$t->set_block( "cart_item_list_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );

$t->set_block( "full_cart_tpl", "voucher_item_list_tpl", "voucher_item_list" );
$t->set_block( "voucher_item_list_tpl", "voucher_used_header_inc_tax_item_tpl", "voucher_used_header_inc_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_used_header_ex_tax_item_tpl", "voucher_used_header_ex_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_left_header_inc_tax_item_tpl", "voucher_left_header_inc_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_left_header_ex_tax_item_tpl", "voucher_left_header_ex_tax_item" );
$t->set_block( "voucher_item_list_tpl", "voucher_item_tpl", "voucher_item" );
$t->set_block( "voucher_item_tpl", "voucher_used_inc_tax_item_tpl", "voucher_used_inc_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_used_ex_tax_item_tpl", "voucher_used_ex_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_left_inc_tax_item_tpl", "voucher_left_inc_tax_item" );
$t->set_block( "voucher_item_tpl", "voucher_left_ex_tax_item_tpl", "voucher_left_ex_tax_item" );

$t->set_block( "full_cart_tpl", "total_ex_tax_item_tpl", "total_ex_tax_item" );
$t->set_block( "full_cart_tpl", "total_inc_tax_item_tpl", "total_inc_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_ex_tax_item_tpl", "subtotal_ex_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_inc_tax_item_tpl", "subtotal_inc_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_ex_tax_item_tpl", "shipping_ex_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_inc_tax_item_tpl", "shipping_inc_tax_item" );

$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_savings_item_tpl", "cart_savings_item" );
$t->set_block( "cart_item_tpl", "cart_inc_tax_item_tpl", "cart_inc_tax_item" );
$t->set_block( "cart_item_tpl", "cart_ex_tax_item_tpl", "cart_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "option_savings_item_tpl", "option_savings_item" );
$t->set_block( "cart_item_option_tpl", "option_inc_tax_item_tpl", "option_inc_tax_item" );
$t->set_block( "cart_item_option_tpl", "option_ex_tax_item_tpl", "option_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_basis_tpl", "cart_item_basis" );
$t->set_block( "cart_item_basis_tpl", "basis_savings_item_tpl", "basis_savings_item" );
$t->set_block( "cart_item_basis_tpl", "basis_inc_tax_item_tpl", "basis_inc_tax_item" );
$t->set_block( "cart_item_basis_tpl", "basis_ex_tax_item_tpl", "basis_ex_tax_item" );

$t->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
$t->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );

$order = new eZOrder( $OrderID );

// get the customer

$user = $order->user();

if ( $user )
{

    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {
        $t->set_var( "customer_email", $user->email() );
        $t->set_var( "customer_id", $user->id() );    
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

    // print preorder id
    $preOrderId = new eZPreOrder();
    $preOrderId->getByOrderId( $OrderID );
    if ( $preOrderId->id() )
    {
        $t->set_var( "preorder_id", $preOrderId->id() );
    }
    else
    {
        $t->set_var( "preorder_id", "" );
    }

    // print out the addresses
    $shippingAddress =& $order->shippingAddress();

    $t->set_var( "shipping_street1", $shippingAddress->street1() );
    $t->set_var( "shipping_street2", $shippingAddress->street2() );
    $t->set_var( "shipping_zip", $shippingAddress->zip() );
    $t->set_var( "shipping_place", $shippingAddress->place() );
    $t->set_var( "shipping_phone", $shippingAddress->phone() );

    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {    
        $shippingUser = $order->shippingUser();

        if ( $shippingUser )
        {
            $t->set_var( "shipping_first_name", $shippingUser->firstName() );
            $t->set_var( "shipping_last_name", $shippingUser->lastName() );   
        }
    }
    else
    {

        if ( $order->personID() > 0 )
        {
            $customer = new eZPerson( $order->personID() );
            $t->set_var( "shipping_first_name", $customer->firstName() );
            $t->set_var( "shipping_last_name", $customer->lastName() );
        }
        else
        {
            $customer = new eZCompany( $order->companyID() );
            $t->set_var( "shipping_first_name", $customer->name() );
            $t->set_var( "shipping_last_name", "" );
        }
    }
    $country = $shippingAddress->country();
    if ( is_object( $country ) )
    {
        $t->set_var( "shipping_country", $country->name() );
    }
    else
    {
        $t->set_var( "shipping_country", "" );
    }
	
	    $region = $shippingAddress->region();

    if ( is_object( $region ) )
    {
        $t->set_var( "shipping_region", $region->name() );
    }
    else
    {
        $t->set_var( "shipping_region", "" );
    }
	
    $billingAddress =& $order->billingAddress();

    $t->set_var( "billing_street1", $billingAddress->street1() );
    $t->set_var( "billing_street2", $billingAddress->street2() );
    $t->set_var( "billing_zip", $billingAddress->zip() );
    $t->set_var( "billing_place", $billingAddress->place() );
    $t->set_var( "billing_phone", $billingAddress->phone() );
    
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );

    $country = $billingAddress->country();
    if ( is_object( $country ) )
    {
        $t->set_var( "billing_country", $country->name() );
    }
    else
    {
        $t->set_var( "billing_country", "" );
    }
    
    $region = $billingAddress->region();

    if ( is_object( $region ) )
    {
        $t->set_var( "billing_region", $region->name() );
            // $t->set_var( "billing_region", $region->Abbreviation() );
    }
    else
    {
        $t->set_var( "billing_region", "" );
    }
}

// fetch the order items
if ( $Action == "edit" )
{
    // fetch the order items
    $items = $order->items( $OrderType );
}
else
{
    $items =  $order->items();
}

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
$totalVAT = 0.0;

function turnColumnsOnOff( $rowName, $t = false, $ShowSavingsColumn = false, $ShowExTaxColumn = false, $ShowIncTaxColumn = false )
{
    if ( $ShowSavingsColumn == true )
    {
        $t->parse( $rowName . "_savings_item", $rowName . "_savings_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_savings_item", "" );
    }

    if ( $ShowExTaxColumn == true )
    {
        $t->parse( $rowName . "_ex_tax_item", $rowName . "_ex_tax_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_ex_tax_item", "" );
    }

      $t->parse( $rowName . "_inc_tax_item", $rowName . "_inc_tax_item_tpl" );
    /*
    if ($total["shiptax"] or $ShowIncTaxColumn == true )
    {
        $t->parse( $rowName . "_inc_tax_item", $rowName . "_inc_tax_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_inc_tax_item", "" );
    }
    */
}

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$numberOfItems = 0;
$j = 0;

foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
    $j++;  
    $t->set_var( "cart_item_id", $item->id() );
    $product =& $item->product();
    $vatPercentage = $product->vatPercentage();
    $productHasVAT = $product->priceIncVAT();
    
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_number", $product->productNumber() );
    $t->set_var( "product_price", $item->localePrice( false, true, $PricesIncludeVAT ) );
    $t->set_var( "product_count", $item->count() );
    $t->set_var( "product_total_ex_tax", $item->localePrice( true, true, false ) );
    $t->set_var( "product_total_inc_tax", $item->localePrice( true, true, true ) );

    $numberOfItems++;

    $numberOfOptions = 0;
    
    $optionValues =& $item->optionValues();

    $t->set_var( "cart_item_option", "" );
    $t->set_var( "cart_item_basis", "" );

    foreach ( $optionValues as $optionValue )
    {
        turnColumnsOnOff( "option", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
    
        $t->set_var( "option_id", "" );
        $t->set_var( "option_name", $optionValue->valueName() );
        $t->set_var( "option_value", $optionValue->optionName() );
        $t->set_var( "option_price", "" );
        $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        
        $numberOfOptions++;
    }
    turnColumnsOnOff( "cart", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
    turnColumnsOnOff( "basis", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
    
    if ( $numberOfOptions ==  0 )
    {
        $t->set_var( "cart_item_option", "" );
        $t->set_var( "cart_item_basis", "" );
    }
    else
    {
        if ( false )
        {
            $t->set_var( "basis_price", $item->localePrice( false, false, $PricesIncludeVAT ) );
            $t->parse( "cart_item_basis", "cart_item_basis_tpl", true );
        }
        else
        {
            $t->set_var( "cart_item_basis", "" );
        }
   }

    $t->parse( "cart_item", "cart_item_tpl", true );
}

if ( $numberOfItems > 0 )
{
    $ShowCart = true;
}

$t->setAllStrings();

turnColumnsOnOff( "header", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );

if ( $ShowCart == true )
{
    
    $order->orderTotals( $tax, $total );
if($total["shiptax"]){
  $ShowIncTaxColumn = true;
}

    $t->set_var( "empty_cart", "" );

    $currency->setValue( $total["subinctax"] );
    $t->set_var( "subtotal_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["subextax"] );
    $t->set_var( "subtotal_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["inctax"] );
    $t->set_var( "total_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["extax"] );
    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["shipinctax"] );
    $t->set_var( "shipping_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["shipextax"] );
    $t->set_var( "shipping_ex_tax", $locale->format( $currency ) );
    
    if ( $ShowSavingsColumn == false )
    {
        $ColSpanSizeTotals--;
    }
    
    $SubTotalsColumns = $ColSpanSizeTotals;
    
    if ( $ShowExTaxColumn == true )
    {
        if ( $ShowExTaxTotal == true or $ShowIncTaxColumn == false )
        {
            $t->parse( "total_ex_tax_item", "total_ex_tax_item_tpl" );
            $t->parse( "subtotal_ex_tax_item", "subtotal_ex_tax_item_tpl" );
            $t->parse( "shipping_ex_tax_item", "shipping_ex_tax_item_tpl" );
        }
        else
        {
            $t->set_var( "total_ex_tax_item", "" );
            $t->set_var( "subtotal_ex_tax_item", "" );
            $t->set_var( "shipping_ex_tax_item", "" );
        }
    }
    else
    {
        $ColSpanSizeTotals--;
        $t->set_var( "total_ex_tax_item", "" );
        $t->set_var( "subtotal_ex_tax_item", "" );
        $t->set_var( "shipping_ex_tax_item", "" );
    }

    if ( $ShowIncTaxColumn == true )
    {
        $t->parse( "total_inc_tax_item", "total_inc_tax_item_tpl" );
        $t->parse( "subtotal_inc_tax_item", "subtotal_inc_tax_item_tpl" );
        $t->parse( "shipping_inc_tax_item", "shipping_inc_tax_item_tpl" );
    }
    else
    {
        $ColSpanSizeTotals--;
        $t->set_var( "total_inc_tax_item", "" );
        $t->set_var( "subtotal_inc_tax_item", "" );
        $t->set_var( "shipping_inc_tax_item", "" );
    }
    
    if ( $ShowIncTaxColumn and $ShowExTaxColumn and $ShowExTaxTotal )
    {
        $t->set_var( "subtotals_span_size", $SubTotalsColumns - 1 );
    }
    else
    {
        $t->set_var( "subtotals_span_size", $ColSpanSizeTotals  );        
    }
    
    $t->set_var( "totals_span_size", $ColSpanSizeTotals );
    $t->parse( "cart_item_list", "cart_item_list_tpl" );
    $t->parse( "full_cart", "full_cart_tpl" );

    $currency->setValue( $total["tax"] );
    $t->set_var( "tax", $locale->format( $currency ) );

    $j = 0;

    $salestax= 0;
    if($total["shiptax"]){
    foreach ( $tax as $taxGroup )
    {
        $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;  
	if($taxGroup["basis"] == $total["subextax"]){
        $currency->setValue( $taxGroup["basis"] );    
        $t->set_var( "sub_tax_basis", $locale->format( $currency ) );

        $currency->setValue( $taxGroup["tax"] );    
        $t->set_var( "sub_tax", $locale->format( $currency ) );

        $t->set_var( "sub_tax_percentage", $taxGroup["percentage"] );
        $t->parse( "tax_item", "tax_item_tpl", true );
	  $salestax = $taxGroup["percentage"];
	}else{
	  $currency->setValue( $total["shipextax"] );    
	  $t->set_var( "sub_tax_basis", $locale->format( $currency ) );
	  
	  $currency->setValue( $total["shiptax"] );    
	  $t->set_var( "sub_tax", $locale->format( $currency ) );
	  
	  $t->set_var( "sub_tax_percentage", $salestax);
	  $t->parse( "tax_item", "tax_item_tpl", true );
	  
	}
    }
    

    $t->parse( "tax_specification", "tax_specification_tpl" );
    }else{
    $t->set_var("tax_specification", "");
    }
}

$usedVouchers =& $order->usedVouchers();
$t->set_var("user-comment-str", "Comment:");
$t->set_var("user-comment", $order->comment());
$t->set_var( "voucher_item_list", "" );

if ( count ( $usedVouchers ) > 0 )
{
    turnColumnsOnOff( "voucher_used_header", $t , $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
    turnColumnsOnOff( "voucher_left_header", $t , $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
    $j = 0;
    foreach ( $usedVouchers as $voucherUsed )
    {
        $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;  

        $voucher =& $voucherUsed->voucher();
        $t->set_var( "voucher_number", $voucher->keyNumber() );

        eZOrder::voucherTotal( $tax, $total, $voucherUsed );
        $currency->setValue( $total["extax"] );
        $t->set_var( "voucher_used_ex_tax", $locale->format( $currency ) );
        $currency->setValue( $total["inctax"] );
        $t->set_var( "voucher_used_inc_tax", $locale->format( $currency ) );

        eZOrder::voucherTotal( $tax, $total, $voucher );
        $currency->setValue( $total["extax"] );
        $t->set_var( "voucher_left_ex_tax", $locale->format( $currency ) );
        $currency->setValue( $total["inctax"] );
        $t->set_var( "voucher_left_inc_tax", $locale->format( $currency ) );

        turnColumnsOnOff( "voucher_used", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
        turnColumnsOnOff( "voucher_left", $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn );
        $t->parse( "voucher_item", "voucher_item_tpl", true );
        
    }
    $t->parse( "voucher_item_list", "voucher_item_list_tpl" );
}

$statusType = new eZOrderStatusType();
$statusTypeArray = $statusType->getAll();
foreach ( $statusTypeArray as $status )
{
    $statusName = preg_replace( "#intl-#", "", $status->name() );
//    $statusName =  $languageINI->variable( "strings", $statusName );
    
    $t->set_var( "option_name", $statusName );
    $t->set_var( "option_id", $status->id() );
    $t->parse( "order_status_option", "order_status_option_tpl", true );
}


$historyArray = $order->statusHistory();
$i=0;
foreach ( $historyArray as $status )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $admin =  $status->admin();
    
    $statusType = $status->type();

    if ( is_object( $statusType ) && $statusType->name() != "" )
    {    
        $statusName = preg_replace( "#intl-#", "", $statusType->name() );
        //    $statusName =  $languageINI->variable( "strings", $statusName );
    }
    else
    {
        $statusName = preg_replace( "#intl-#", "", "Pending" );
        // $statusName =  $languageINI->variable( "strings", $statusName );
    }
    
    
    $t->set_var( "status_date", $locale->format( $status->altered() ) );
    $t->set_var( "status_name", $statusName );
    $t->set_var( "status_comment", $status->comment() );
    $t->set_var( "admin_login", $admin->login() );
    $t->parse( "order_status_history", "order_status_history_tpl", true );
    $i++;
}

$checkout = new eZCheckout();
$instance =& $checkout->instance();
$paymentMethod = $instance->paymentName( $order->paymentMethod() );

$t->set_var( "payment_method", $paymentMethod );

$shippingType = $order->shippingType();

$t->set_var( "shipping_method", "" );

if ( ( is_object( $shippingType ) ) && ( $checkups != 1 ) )
{    
    $t->set_var( "shipping_method", $shippingType->name() );
}
else
{
    $t->set_var( "shipping_method", $shippingType );
}

if (($shippingType )&&($checkups==1))
{    
$getnames =array ( 

	'01' => 'UPS Next Day Air',

       	'02' => 'UPS 2nd Day Air',

	'03' => 'UPS Ground',

	'07' => 'UPS Worldwide Express',

	'08' => 'UPS Worldwide Expedited',

	'11' => 'UPS Standard',

	'12' => 'UPS 3 Day Select',

	'13' => 'UPS Next Day Air Saver',

	'14' => 'UPS Next Day Air Early A.M.',

	'54' => 'UPS Worldwide Express Plus',

	'59' => 'UPS 2nd Day Air A.M.',

	'64' => '',

	'65' => 'UPS Express Saver',

   );
if (!$getnames["$shippingType"])
{
$service_name=$shippingType;
}
else
{
$service_name=$getnames["$shippingType"];
}



    $t->set_var( "shipping_method", $service_name);
}





$t->set_var( "order_id", $order->id() );
$t->set_var( "www_site", $wwwDir );

$status = $order->lastStatus();
$lastDateTime = $status->altered();	
$t->set_var( "altered_date", $locale->format( $lastDateTime ) );
$lastDateTime = $status->rawAltered();	
$statusAge = ( round( (( time()-$lastDateTime )/86400),0 ) );
if ($statusAge > 42)
    $t->parse( "email_followup", "email_followup_tpl" );
else
	$t->set_var( "email_followup", "" );

$t->set_var( "admin_email", $adminEmail );
$t->pparse( "output", "order_edit_tpl" );


//print_r($tax);
//print "<br><br>";

//print_r($total);
//print_r($ok);
//print_r($test);
//print $order->comment();
//print_r($instance);
?>
