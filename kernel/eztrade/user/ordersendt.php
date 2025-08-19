<?php
// 
// $Id: ordersendt.php 9407 2002-04-10 11:49:02Z br $
//
// Created on: <06-Oct-2000 14:04:17 bf>
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

// include_once( "classes/eztemplate.php" ); 
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" ); 
// include_once( "classes/ezhttptool.php" );

// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );

// include_once( "eztrade/classes/ezorder.php" ); 
// include_once( "eztrade/classes/ezproduct.php" ); 
// include_once( "eztrade/classes/ezcheckout.php" );


$Language = $ini->variable( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->variable( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" );
$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$ShowExTaxColumn = $ini->variable( "eZTradeMain", "ShowExTaxColumn" ) == "enabled" ? true : false;
$ShowIncTaxColumn = $ini->variable( "eZTradeMain", "ShowIncTaxColumn" ) == "enabled" ? true : false;
$ShowExTaxTotal = $ini->variable( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
$ColSpanSizeTotals = $ini->variable( "eZTradeMain", "ColSpanSizeTotals" );
$checkups = $ini->variable( "eZTradeMain", "UPSOFF" );
$ShowTaxBasis = $ini->variable( "eZTradeMain", "ShowTaxBasis" ) == "enabled" ? true : false;

$locale = new eZLocale( $Language );
$currency = new eZCurrency();
    

// Set some variables to defaults.
$ShowCart = false;
$ShowSavingsColumn = false;

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "ordersendt.php" );

$translation = new eZINI( "kernel/eztrade/user/intl/" . $Language . "/ordersendt.php.ini", false );

$t->setAllStrings();

$t->set_file( "order_sendt_tpl", "ordersendt.tpl" );

$t->set_block( "order_sendt_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "order_sendt_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "order_sendt_tpl", "order_item_list_tpl", "order_item_list" );
$t->set_block( "order_sendt_tpl", "printable_reciept_tpl", "printable_reciept" );


$t->set_block( "order_sendt_tpl", "full_cart_tpl", "full_cart" );
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
$t->set_block( "full_cart_tpl", "tax_total_tpl", "tax_total" );
$t->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );


$order = new eZOrder( $OrderID );
unset( $user );

// get the customer
$user = $order->user();

$currentUser =& eZUser::currentUser();

// check if the user is logged i
if ( !( $currentUser && $user ) ) 
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}


// check if the user owns the order
if ( $currentUser->id() != $user->id() )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

$vat=false;
$tester = 1;
if ( $user )
{
    // print out the addresses
    $billingAddress = $order->billingAddress();

    if ( $order->personID() == 0 && $order->companyID() == 0 )
    {
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
    }
    
    $t->set_var( "billing_street1", $billingAddress->street1() );
    $t->set_var( "billing_street2", $billingAddress->street2() );
    $t->set_var( "billing_phone", $billingAddress->phone() );
    $t->set_var( "billing_zip", $billingAddress->zip() );
    $t->set_var( "billing_place", $billingAddress->place() );
    $t->set_var( "billing_region", $billingAddress->region() );    
    $country = $billingAddress->country();

    if ( $country )
    {
        if ( $ini->variable( "eZUserMain", "SelectCountry" ) == "enabled" )
            $t->set_var( "billing_country", $country->name() );
        else
            $t->set_var( "billing_country", "" );
    }
    else
    {
        $t->set_var( "billing_country", "" );
    }
    
    $region = $billingAddress->region();

    if ( $region )
    {
        if ( $ini->variable( "eZUserMain", "SelectRegion" ) == "enabled" )
            $t->set_var( "billing_region", $region->name() );
        else
            $t->set_var( "billing_region", "" );
    }
    else
    {
            $t->set_var( "billing_region", "" );
    }
    
    if ( $ini->variable( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        $t->parse( "billing_address", "billing_address_tpl" );
    else
        $t->set_var( "billing_address", "" );

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
    
    $shippingAddress = $order->shippingAddress();

    $t->set_var( "shipping_street1", $shippingAddress->street1() );
    $t->set_var( "shipping_street2", $shippingAddress->street2() );
    $t->set_var( "shipping_phone", $shippingAddress->phone() );
    $t->set_var( "shipping_zip", $shippingAddress->zip() );
    $t->set_var( "shipping_place", $shippingAddress->place() );
    $t->set_var( "shipping_region", $shippingAddress->region() );      
    $country = $shippingAddress->country();

    if ( $country )
    {
        if ( $ini->variable( "eZUserMain", "SelectCountry" ) == "enabled" )
            $t->set_var( "shipping_country", $country->name() );
        else
            $t->set_var( "shipping_country", "" );
    }
    else
    {
        $t->set_var( "shipping_country", "" );
    }

    $region = $shippingAddress->region();

	if ( $shippingRegion )
		{
			if ( $region->hasVAT() )
				$vat = true;
		}

    if ( $region )
    {
        if ( $ini->variable( "eZUserMain", "SelectRegion" ) == "enabled" )
            $t->set_var( "shipping_region", $region->name() );
        else
            $t->set_var( "shipping_region", "" );
    }
    else
    {
            $t->set_var( "shipping_region", "" );
    }
    
    $t->parse( "shipping_address", "shipping_address_tpl" );
}

if ( !isset( $OrderType ) )
    $OrderType = false;

// fetch the order items
$items = $order->items( $OrderType );


#$locale = new eZLocale( $Language );
#$currency = new eZCurrency();

$i = 0;
$sum = 0.0;
$totalVAT = 0.0;


// foreach ( $items as $item )
// {
//     $product = $item->product();
// 
//     $image = $product->thumbnailImage();
// 
//     if ( $image )
//     {
//         $thumbnail =& $image->requestImageVariation( 35, 35 );        
// 
//         $t->set_var( "product_image_path", "/" . $thumbnail->imagePath() );
//         $t->set_var( "product_image_width", $thumbnail->width() );
//         $t->set_var( "product_image_height", $thumbnail->height() );
//         $t->set_var( "product_image_caption", $image->caption() );
//             
//         $t->parse( "order_image", "order_image_tpl" );            
//     }
//     else
//     {
//         $t->set_var( "order_image", "" );
//     }
// 
//     $priceobj = new eZCurrency();
// 
//     if ( ( !$RequireUserLogin or get_class( $user ) == "ezuser" ) and
//          $ShowPrice and $product->showPrice() == true and $product->hasPrice() )
//     {
//         $found_price = false;
// 
//         if ( $ShowPriceGroups and $PriceGroup > 0 )
//         {
//             $price = eZPriceGroup::correctPrice( $product->id(), $PriceGroup );
//             if ( $price )
//             {
//                 if ( $PricesIncludeVAT == "enabled" )
//                 {
//                     $totalVAT = $product->addVAT( $price );
//                     $price += $totalVAT;
//                 }
//                 else
//                 {
//                     $totalVAT = $product->extractVAT( $price );
//                 }
// 
//                 $found_price = true;
//                 $price = $price * $item->count();
//             }
//         }
// 
//         if ( !$found_price )
//         {
//             if ( $PricesIncludeVAT == "enabled" )
//             {
//                 $totalVAT = $product->addVAT( $item->price( true, true ) );
//                 $price = $item->price( true, true ) + $totalVAT;
//             }
//             else
//             {
//                 $totalVAT = $product->extractVAT( $item->price( true, true ) );
//                 $price = $item->price( true, true );
//             }
//         }
//         $currency->setValue( $price );
//         $t->set_var( "product_price", $locale->format( $currency ) );
//     }
//     else
//     {
//         if ( $PricesIncludeVAT == "enabled" )
//         {
//             $totalVAT = $product->addVAT( $item->price( true, true ) );
//             $price = $item->price( true, true ) + $totalVAT;
//         }
//         else
//         {
//             $totalVAT = $product->extractVAT( $item->price( true, true ) );
//             $price = $item->price( true, true );
//         }
//     }
// 
//     $currency->setValue( $price );
// 
//     $sum += $price;
// 
//     $t->set_var( "product_name", $product->name() );
//     $t->set_var( "product_price", $locale->format( $currency ) );
// 
//     $t->set_var( "order_item_count", $item->count() );
//     
//     if ( ( $i % 2 ) == 0 )
//         $t->set_var( "td_class", "bglight" );
//     else
//         $t->set_var( "td_class", "bgdark" );
// 
//     $optionValues =& $item->optionValues();
// 
//     $t->set_var( "order_item_option", "" );
//     foreach ( $optionValues as $optionValue )
//     {
//         $t->set_var( "option_name", $optionValue->optionName() );
//         $t->set_var( "option_value", $optionValue->valueName() );
//             
//         $t->parse( "order_item_option", "order_item_option_tpl", true );
//     }
//         
//     $t->parse( "order_item", "order_item_tpl", true );
//         
//     $i++;
// }
// 
// 
// $t->parse( "order_item_list", "order_item_list_tpl" );

function turnColumnsOnOff( $rowName )
{
    global $t, $ShowSavingsColumn, $ShowExTaxColumn, $ShowIncTaxColumn;
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

    if ( $ShowIncTaxColumn == true )
    {
        $t->parse( $rowName . "_inc_tax_item", $rowName . "_inc_tax_item_tpl" );
    }
    else
    {
        $t->set_var( $rowName . "_inc_tax_item", "" );
    }
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
        turnColumnsOnOff( "option" );
    
        $t->set_var( "option_id", "" );
        $t->set_var( "option_name", $optionValue->valueName() );
        $t->set_var( "option_value", $optionValue->optionName() );
        $t->set_var( "option_price", "" );
        $t->parse( "cart_item_option", "cart_item_option_tpl", true );
        
        $numberOfOptions++;
    }
    turnColumnsOnOff( "cart" );
    turnColumnsOnOff( "basis" );
    
    if ( $numberOfOptions ==  0 )
    {
        $t->set_var( "cart_item_option", "" );
        $t->set_var( "cart_item_basis", "" );
    }
    else
    {
        if( false )
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

turnColumnsOnOff( "header" );

if ( $ShowCart == true )
{
    
    $order->orderTotals( $tax, $total );

    $t->set_var( "empty_cart", "" );

	$t->set_var( "tax_total", "" );

	if ( !$ShowIncTaxColumn && $order->isVATInc() )
	{
	    $currency->setValue( $total["inctax"] );
	    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
	    $currency->setValue( $total["tax"] );
    	$t->set_var( "total_cart_tax", $locale->format( $currency ) );	

		if ( $total["tax"]>0 )
			$t->parse( "tax_total", "tax_total_tpl" );
		else
			$t->set_var( "tax_total", "" );
	}
	else
	{

	    $currency->setValue( $total["extax"] );
	    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
	    $t->set_var( "total_cart_tax", "" );		
	    $t->set_var( "tax_total", "" );
	}

    $currency->setValue( $total["subinctax"] );
    $t->set_var( "subtotal_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["subextax"] );
    $t->set_var( "subtotal_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["inctax"] );
    $t->set_var( "total_inc_tax", $locale->format( $currency ) );

//    $currency->setValue( $total["extax"] );
//    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
    
    $currency->setValue( $total["shipinctax"] );
    $t->set_var( "shipping_inc_tax", $locale->format( $currency ) );

	$currency->setValue( $total["subextax"] );
    $t->set_var( "subtotal_ex_tax", $locale->format( $currency ) );		

    $currency->setValue( $total["shipextax"] );
    $t->set_var( "shipping_ex_tax", $locale->format( $currency ) );

    $currency->setValue( $total["tax"] );
    $t->set_var( "tax", $locale->format( $currency ) );
    
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

	if ($ShowTaxBasis)
	{
        foreach( $tax as $taxGroup )
        {
            $t->set_var( "td_class", ( $j % 2 ) == 0 ? "bglight" : "bgdark" );
            $j++;  
            $currency->setValue( $taxGroup["basis"] );    
            $t->set_var( "sub_tax_basis", $locale->format( $currency ) );

            $currency->setValue( $taxGroup["tax"] );    
            $t->set_var( "sub_tax", $locale->format( $currency ) );

            $t->set_var( "sub_tax_percentage", $taxGroup["percentage"] );
            $t->parse( "tax_item", "tax_item_tpl", true );
        }
        $t->parse( "tax_specification", "tax_specification_tpl" );
	}
	else
    // replace with a tax overview site.ini in eZTrade variable = enabled/dissabled
    // $t->parse( "tax_specification", "tax_specification_tpl" );
        $t->set_var( "tax_specification", "" );
        $t->set_var( "tax_item", "" );
}

$usedVouchers =& $order->usedVouchers();

$t->set_var( "voucher_item_list", "" );

if ( count ( $usedVouchers ) > 0 )
{
    turnColumnsOnOff( "voucher_used_header");
    turnColumnsOnOff( "voucher_left_header");
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

        turnColumnsOnOff( "voucher_used" );
        turnColumnsOnOff( "voucher_left" );
        $t->parse( "voucher_item", "voucher_item_tpl", true );
        
    }
    $t->parse( "voucher_item_list", "voucher_item_list_tpl" );
}



$checkout = new eZCheckout();
$instance =& $checkout->instance();
$paymentMethod = $instance->paymentName( $order->paymentMethod() );

$t->set_var( "payment_method", $paymentMethod );

$t->set_var( "comment", $order->comment() );

$shippingType = $order->shippingType();

// print_r( $shippingType ); 

if (($shippingType )&&($checkups!=1))
{    
    $t->set_var( "shipping_type", $shippingType->name() );
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

   $t->set_var( "shipping_type", $service_name);
}

$shippingCost = $order->shippingCharge();

$shippingVAT = $order->shippingVAT();

$currency->setValue( $shippingCost );

$t->set_var( "shipping_cost", $locale->format( $currency ) );

$sum += $shippingCost;
$currency->setValue( $sum );
$t->set_var( "order_sum", $locale->format( $currency ) );

$currency->setValue( $totalVAT + $shippingVAT );
$t->set_var( "order_vat_sum", $locale->format( $currency ) );

$t->set_var( "order_id", $OrderID );


// graham@brookinsconsulting.com - 12-19-2001:11:45
// idea to turn off feature . . .

// if ( $ini->variable( "eZTradeMain", "ShowPrintableRecieptLink" ) == "enabled" )
//     printable_reciept_enabled_tpl   printable_reciept_disabled_tpl
//    else
//        $t->set_var( "billing_address", "" );


// graham@brookinsconsulting.com - 12-19-2001:11:45
// dynamic internal printable reciept link

if ( $PrintableVersion = $ini->variable( "eZTradeMain", "ShowPrintableRecieptLink" ) )
{
switch ($PrintableVersion) {
    case "enabled":
        $printable_reciept_link = "http://$HTTP_HOST/trade/ordersendt/$OrderID/?PrintableVersion=disabled";
        $printable_reciept_text = $translation->variable( "Strings", "web_reciept" );
        break;
    case "disabled":
        $printable_reciept_link = "http://$HTTP_HOST/trade/ordersendt/$OrderID/?PrintableVersion=enabled";
        $printable_reciept_text = $translation->variable( "Strings", "printable_reciept" );
        break;
    case "":
        $printable_reciept_link = "http://$HTTP_HOST/trade/ordersendt/$OrderID/?PrintableVersion=enabled";
        $printable_reciept_text = $translation->variable( "Strings", "printable_reciept" );
        break;
    }

    $t->set_var( "printable_reciept_text", $printable_reciept_text );
    $t->set_var( "printable_reciept_link", $printable_reciept_link );
    //$t->parse( "printable_reciept" , "printable_reciept_tpl", true);
    $t->parse( "printable_reciept" , "printable_reciept_tpl" );
}
else
{
    $t->set_var( "printable_reciept", "" );
}

$t->setAllStrings();

$t->pparse( "output", "order_sendt_tpl" );

?>
