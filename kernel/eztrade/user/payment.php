<?php
//
// $Id: payment.php 9407 2002-04-10 11:49:02Z br $
//
// Created on: <02-Feb-2001 16:31:53 bf>
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

unset( $PaymentSuccess );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "eztrade/classes/ezcart.php" );
// include_once( "eztrade/classes/ezcheckout.php" );

$ini =& eZINI::instance( 'site.ini' );
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$session =& eZSession::globalSession();

// fetch the cart
$cart = new eZCart();
$cart = $cart->getBySession( $session );
$RequireUserLogin = true;

$Language =& $ini->variable( "eZTradeMain", "Language" );
$locale = new eZLocale( $Language );

function deleteCache($ProductID, $CategoryID, $CategoryArray, $Hotdeal )
{
    if ( is_a( $ProductID, "eZProduct" ) )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $Hotdeal = $ProductID->isHotDeal();
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "kernel/eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "kernel/eztrade/cache/", array( "productlist",
                                                          array_merge( array( $CategoryID ), $CategoryArray ) ),
                                 "cache", "," );

    foreach ( $files as $file )
    {
        $file->delete();
    }
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "kernel/eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }
    $files =& eZCacheFile::files( "kernel/ezarticle/cache/",
                                  array( "articlefrontpage",
                                         NULL,
                                         NULL),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

}

if ( !$cart )
{
    $orderCompletedID = $session->variable( "OrderCompletedID" ) ;

    if( $orderCompletedID > 0 )
    {
        $user =& eZUser::currentUser();
        $order = new eZOrder( $orderCompletedID );
        $orderUser = $order->user();
        if ( $user->id() == $orderUser->id() )
        {
            eZHTTPTool::header( "Location: http://" . $HTTP_HOST . $indexFile . "/trade/ordersendt/$orderCompletedID/" );
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: /trade/cart/" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /trade/cart/" );
        exit();
    }
}

// get the cart items.
$items = $cart->items();

// this is the value to charge the customer with
$ChargeTotal = $session->variable( "TotalCost" ) ;

// this is the total vat.
$ChargeVATTotal = $session->variable( "TotalVAT" ) ;

// The comment from the user.
$Comment = $session->variable( "Comment" ) ;

$PreOrderID = $session->variable( "PreOrderID" );

$currency = new eZCurrency();
$checkout = new eZCheckout();
$instance =& $checkout->instance();

$paymentMethod = $session->arrayValue( "PaymentMethod" );
$paymentMethod = $paymentMethod[0];

// var_dump( $instance->paymentFile( $paymentMethod ) );


if ( $paymentMethod == true and $paymentMethod != "voucher_done" )
{
    include( $instance->paymentFile( $paymentMethod ) );
}
else
{
    $PaymentSuccess = true;
}



if ( $PaymentSuccess == true )
{
    $orderID = $session->variable( "OrderID" );

//	if ( $IOC_authorization_code )
//    	$session->setVariable( "AuthCode", $IOC_authorization_code );
//	else
//   	$session->setVariable( "AuthCode", "" );	

    // create a new order
    $order = new eZOrder();
    $user =& eZUser::currentUser();
    $order->setUser( $user );

    if ( $ini->variable( "eZTradeMain", "ShowBillingAddress" ) != "enabled" )
    {
        $billingAddressID = $shippingAddressID;
    }
    
    $shippingAddress = new eZAddress( $session->variable( "ShippingAddressID" ) );
    $billingAddress = new eZAddress( $session->variable( "BillingAddressID" ) );

    $order->setShippingCharge( $session->variable( "ShippingCost" ) );
    $order->setShippingVAT( $session->variable( "ShippingVAT" ) );

    $order->setShippingAddress( $shippingAddress, $user );
    $order->setBillingAddress( $billingAddress, $user );
    
    $order->setPaymentMethod( $session->arrayValue( "PaymentMethod" ) );

    $order->setShippingTypeID( $session->variable( "ShippingTypeID" ) );

    $order->setComment( $Comment );

    $order->setPersonID( $cart->personID() );
    $order->setCompanyID( $cart->companyID() );

    $order->setIsVATInc( false );
    

    // fetch the cart items
    $items = $cart->items();

    // exit if no items exist
    if ( count ( $items ) == 0 )
    {
       eZHTTPTool::header( "Location: /trade/cart/" );
       exit();
    }

    $order->store();


    $order_id = $order->id();
    
    foreach ( $items as $item )
    {
        $totalVAT=0.0;
        $price=0.0;
        $totalPrice=0.0;
        $product = $item->product();

            // product price

        
        if ( ( !$RequireUserLogin or get_class( $user ) == "eZUser" ) and
             $ShowPrice and $product->showPrice() == true  )
        {
            $price = $item->correctPrice( false, true, false );
        }
        
        
        $totalVAT = $item->correctPrice( false, true, true ) - $price;
        $currency->setValue( $price );
        
// create a new order item
        $orderItem = new eZOrderItem();
        $orderItem->setOrder( $order );
        $orderItem->setProduct( $product );
        $orderItem->setCount( $item->count() );
        $orderItem->setPrice( $price );
        $orderItem->setVAT( $totalVAT );

        $expiryTime = $product->expiryTime();
        if ( $expiryTime > 0 )
            $orderItem->setExpiryDate( eZDateTime::timeStamp( true ) + ( $expiryTime * 86400 ) );
        else
            $orderItem->setExpiryDate( 0 );
    
        $orderItem->store();
    
        $optionValues =& $item->optionValues();
    
        foreach ( $optionValues as $optionValue )
        {
            $option =& $optionValue->option();
            $value =& $optionValue->optionValue();
        
            $orderOptionValue = new eZOrderOptionValue();
            $orderOptionValue->setOrderItem( $orderItem );
        
            $orderOptionValue->setRemoteID( $optionValue->remoteID() );
        
            $descriptions =& $value->descriptions();
        
            $orderOptionValue->setOptionName( $option->name() );
            $orderOptionValue->setValueName( $descriptions[0] );
            // fix
        
            $orderOptionValue->store();
        }
    }
    
    $cart->cartTotals( $tax, $total );

    //
    // Send mail confirmation
    //      
    $mailTemplate = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                                    "kernel/eztrade/user/intl", $Language, "/mailorder.php" );

    $mailTemplateIni = new eZINI( "kernel/eztrade/user/intl/" . $Language . "/ordersendt.php.ini", false );
    $mailTemplate->set_file( "order_sendt_tpl", "mailorder.tpl" );
    $mailTemplate->setAllStrings();

    $mailTemplate->set_block( "order_sendt_tpl", "credit_card_information_tpl", "credit_card_information" );
    
    $mailTemplate->set_block( "order_sendt_tpl", "customer_account_number_information_tpl", "customer_account_number_information" );

    $mailTemplate->set_block( "order_sendt_tpl", "billing_address_tpl", "billing_address" );
    $mailTemplate->set_block( "order_sendt_tpl", "shipping_address_tpl", "shipping_address" );
    $mailTemplate->set_block( "order_sendt_tpl", "order_item_list_tpl", "order_item_list" );

    $mailTemplate->set_block( "order_sendt_tpl", "full_cart_tpl", "full_cart" );
    $mailTemplate->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );

    $mailTemplate->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );

    $mailTemplate->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );

    $mailTemplate->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
    $mailTemplate->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );
    

    // get the customer
    $user = $order->user();

    $currentUser =& eZUser::currentUser();

    // check if the user is logged in
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

    if ( $user )
    {
        // print out the addresses
        $billingAddress = $order->billingAddress();

        if ( $order->personID() == 0 && $order->companyID() == 0 )
        {
            $mailTemplate->set_var( "customer_first_name", $user->firstName() );
            $mailTemplate->set_var( "customer_last_name", $user->lastName() );
        }
        else
        {
            if ( $order->personID() > 0 )
            {
                $customer = new eZPerson( $order->personID() );
                $mailTemplate->set_var( "customer_first_name", $customer->firstName() );
                $mailTemplate->set_var( "customer_last_name", $customer->lastName() );
            }
            else
            {
                $customer = new eZCompany( $order->companyID() );
                $mailTemplate->set_var( "customer_first_name", $customer->name() );
                $mailTemplate->set_var( "customer_last_name", "" );
            }
        }

        $mailTemplate->set_var( "billing_street1", $billingAddress->street1() );
        $mailTemplate->set_var( "billing_street2", $billingAddress->street2() );
        $mailTemplate->set_var( "billing_zip", $billingAddress->zip() );
        $mailTemplate->set_var( "billing_place", $billingAddress->place() );

        $country = $billingAddress->country();

        if ( $country )
        {
            if ( $ini->variable( "eZUserMain", "SelectCountry" ) == "enabled" )
                $mailTemplate->set_var( "billing_country", $country->name() );
            else
                $mailTemplate->set_var( "billing_country", "" );
        }
        else
        {
            $mailTemplate->set_var( "billing_country", "" );
        }

        $region = $billingAddress->region();

        if ( ( get_class( $region ) == "eZRegion" ) )
        {
            if ( $ini->variable( "eZUserMain", "SelectRegion" ) == "enabled" )
                $mailTemplate->set_var( "billing_region", $region->name() );
            else
                $mailTemplate->set_var( "billing_region", "" );
        }

        if ( $ini->variable( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
            $mailTemplate->parse( "billing_address", "billing_address_tpl" );
        else
            $mailTemplate->set_var( "billing_address", "" );

        if ( $order->personID() == 0 && $order->companyID() == 0 )
        {
            $shippingUser = $order->shippingUser();

            if ( $shippingUser )
            {
                $mailTemplate->set_var( "shipping_first_name", $shippingUser->firstName() );
                $mailTemplate->set_var( "shipping_last_name", $shippingUser->lastName() );
            }
        }
        else
        {
            if ( $order->personID() > 0 )
            {
                $customer = new eZPerson( $order->personID() );
                $mailTemplate->set_var( "shipping_first_name", $customer->firstName() );
                $mailTemplate->set_var( "shipping_last_name", $customer->lastName() );
            }
            else
            {
                $customer = new eZCompany( $order->companyID() );
                $mailTemplate->set_var( "shipping_first_name", $customer->name() );
                $mailTemplate->set_var( "shipping_last_name", "" );
            }
        }

        $shippingAddress = $order->shippingAddress();

        $mailTemplate->set_var( "shipping_street1", $shippingAddress->street1() );
        $mailTemplate->set_var( "shipping_street2", $shippingAddress->street2() );
        $mailTemplate->set_var( "shipping_zip", $shippingAddress->zip() );
        $mailTemplate->set_var( "shipping_place", $shippingAddress->place() );

        $country = $shippingAddress->country();

        if ( $country )
        {
            if ( $ini->variable( "eZUserMain", "SelectCountry" ) == "enabled" )
                $mailTemplate->set_var( "shipping_country", $country->name() );
            else
                $mailTemplate->set_var( "shipping_country", "" );
        }
        else
        {
            $mailTemplate->set_var( "shipping_country", "" );
        }

        $region = $shippingAddress->region();

        if ( ( get_class( $region ) == "eZRegion" ) )
        {
            if ( $ini->variable( "eZUserMain", "SelectRegion" ) == "enabled" )
                $mailTemplate->set_var( "shipping_region", $region->name() );
            else
                $mailTemplate->set_var( "shipping_region", "" );
        }

        $mailTemplate->parse( "shipping_address", "shipping_address_tpl" );
    }


    // fetch the order items
    $items = $order->items( $OrderType );


    $i = 0;
    $sum = 0.0;
    $totalVAT = 0.0;

    $numberOfItems = 0;
    $i = 0;

    $search="/&nbsp;/";
    $replace=" ";

    // Add headers!

    $productsForEmail[$i]["product_id"] = trim( $mailTemplateIni->variable( "strings", "product_id" ) );
    $productsForEmail[$i]["product_name"] = trim( $mailTemplateIni->variable( "strings", "product_name" ) );
    $productsForEmail[$i]["product_number"] = trim( $mailTemplateIni->variable( "strings", "product_number" ) );
    $productsForEmail[$i]["product_price"] = trim( $mailTemplateIni->variable( "strings", "product_price" ) );
    $productsForEmail[$i]["product_count"] = trim( $mailTemplateIni->variable( "strings", "product_qty" ) );
    $productsForEmail[$i]["product_savings"] = trim( $mailTemplateIni->variable( "strings", "product_savings" ) );
    $productsForEmail[$i]["product_total_ex_tax"] = trim( $mailTemplateIni->variable( "strings", "product_total_ex_tax" ) );
    $productsForEmail[$i]["product_total_inc_tax"] = trim( $mailTemplateIni->variable( "strings", "product_total_inc_tax" ) );

    foreach ( $items as $item )
    {
        $mailTemplate->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
        $i++;
        $mailTemplate->set_var( "cart_item_id", $item->id() );
        $product =& $item->product();
        $vatPercentage = $product->vatPercentage();
        $productHasVAT = $product->priceIncVAT();

        $productID = $product->id();

        $productsForEmail[$i]["product_id"] = $productID;
        $productsForEmail[$i]["product_name"] = trim( $product->name() );
        $productsForEmail[$i]["product_number"] = trim( $product->productNumber() );
        $productsForEmail[$i]["product_price"] = preg_replace( $search, $replace, $item->localePrice( false, true, $PricesIncludeVAT ) );
        $productsForEmail[$i]["product_count"] = $item->count();
        $productsForEmail[$i]["product_total_ex_tax"] = preg_replace( $search, $replace, $item->localePrice( true, true, false ) );
        $productsForEmail[$i]["product_total_inc_tax"] = preg_replace( $search, $replace, $item->localePrice( true, true, true ) );
        $productsForEmail[$i]["product_savings"] = "";

        $numberOfItems++;

        $numberOfOptions = 0;

        $optionValues =& $item->optionValues();

        foreach ( $optionValues as $optionValue )
        {
            $productOptions[$productID][$numberOfOptions]["option_id"] = "";
            $productOptions[$productID][$numberOfOptions]["option_name"] = trim( $optionValue->valueName() );
            $productOptions[$productID][$numberOfOptions]["option_value"] = trim( $optionValue->optionName() ) . ": ";
            $productOptions[$productID][$numberOfOptions]["option_price"] = "";

            $numberOfOptions++;
        }
    }

    $separateBy = 2;

    $order->orderTotals( $tax, $total );

    $mailTemplate->set_var( "empty_cart", "" );

    if ( $ShowExTaxColumn == true )
    {
        $currency->setValue( $total["subextax"] );
        $subextax = $locale->format( $currency );
        $subextax = preg_replace( $search, $replace, $subextax );

        $currency->setValue( $total["extax"] );
        $extax =  $locale->format( $currency );
        $extax = preg_replace( $search, $replace, $extax );

        $currency->setValue( $total["shipextax"] );
        $shipextax =  $locale->format( $currency );
        $shipextax = preg_replace( $search, $replace, $shipextax );

        $len_product_total_ex_tax = strlen( $subextax ) > $len_product_total_ex_tax ? strlen( $subextax ) : $len_product_total_ex_tax;
        $len_product_total_ex_tax = strlen( $extax ) > $len_product_total_ex_tax ? strlen( $extax ) : $len_product_total_ex_tax;
        $len_product_total_ex_tax = strlen( $shipextax ) > $len_product_total_ex_tax ? strlen( $shipextax ) : $len_product_total_ex_tax;
    }

    if ( $ShowIncTaxColumn == true )
    {
        $currency->setValue( $total["subinctax"] );
        $subinctax = $locale->format( $currency );
        $subinctax = preg_replace( $search, $replace, $subinctax );

        $currency->setValue( $total["inctax"] );
        $inctax =  $locale->format( $currency );
        $inctax = preg_replace( $search, $replace, $inctax );

        $currency->setValue( $total["shipinctax"] );
        $shipinctax =  $locale->format( $currency );
        $shipinctax = preg_replace( $search, $replace, $shipinctax );

        $len_product_total_inc_tax = strlen( $subinctax ) > $len_product_total_inc_tax ? strlen( $subinctax ) : $len_product_total_inc_tax;
        $len_product_total_inc_tax = strlen( $inctax ) > $len_product_total_inc_tax ? strlen( $inctax ) : $len_product_total_inc_tax;
        $len_product_total_inc_tax = strlen( $shipinctax ) > $len_product_total_inc_tax ? strlen( $shipinctax ) : $len_product_total_inc_tax;
    }

    if ( isset( $productOptions ) && count ( $productOptions ) > 0 )
    {
        foreach( $productOptions as $line )
        {
            $len_option_name = strlen( $line["option_name"] ) > $len_option_name ? strlen( $line["option_name"] ) : $len_option_name;
            $len_option_value = strlen( $line["option_value"] ) > $len_option_value ? strlen( $line["option_value"] ) : $len_option_value;
            $len_option_price = strlen( $line["option_price"] ) > $len_option_price ? strlen( $line["option_price"] ) : $len_option_price;
        }
    }
    
    $len_option_name += $separateBy;
    $len_option_value += $separateBy;
    $len_option_price += $separateBy;

    $optionLen = $len_option_name + $len_option_value;

    $len_product_name = $optionLen;

    foreach( $productsForEmail as $line )
    {
        $len_product_name = strlen( $line["product_name"] ) > $len_product_name ? strlen( $line["product_name"] ) : $len_product_name;
        $len_product_number = strlen( $line["product_number"] ) > $len_product_number ? strlen( $line["product_number"] ) : $len_product_number;
        $len_product_price = strlen( $line["product_price"] ) > $len_product_price ? strlen( $line["product_price"] ) : $len_product_price;
        $len_product_count = strlen( $line["product_count"] ) > $len_product_count ? strlen( $line["product_count"] ) : $len_product_count;
        $len_product_total_ex_tax = strlen( $line["product_total_ex_tax"] ) > $len_product_total_ex_tax ? strlen( $line["product_total_ex_tax"] ) : $len_product_total_ex_tax;
        $len_product_total_inc_tax = strlen( $line["product_total_inc_tax"] ) > $len_product_total_inc_tax ? strlen( $line["product_total_inc_tax"] ) : $len_product_total_inc_tax;
        $len_product_savings = strlen( $line["product_savings"] ) > $len_product_savings ? strlen( $line["product_savings"] ) : $len_product_savings;
    }

    $separateBy = 2;

    $items = "";

    $count = count( $productsForEmail );

    $len_product_number += $separateBy;
    $len_product_name += $separateBy;
    $len_product_price += $separateBy;
    $len_product_count += $separateBy;
    $len_product_total_ex_tax += $separateBy;
    $len_product_total_inc_tax += $separateBy;
    $len_product_savings += $separateBy;

    $lineFillLen = $len_product_number + $len_product_name + $len_product_price + $len_product_count;
    $totalsLen = $len_product_number + $len_product_name + $len_product_price + $len_product_count;
    $len_option_indent = $len_product_number;


    $i = 0;

    $headers = "";

    $headers = $headers . str_pad( $productsForEmail[$i]["product_number"], $len_product_number, " ", STR_PAD_RIGHT );
    $headers = $headers . str_pad( $productsForEmail[$i]["product_name"], $len_product_name, " ", STR_PAD_RIGHT );
    $headers = $headers . str_pad( $productsForEmail[$i]["product_price"], $len_product_price, " ", STR_PAD_LEFT );
    $headers = $headers . str_pad( $productsForEmail[$i]["product_count"], $len_product_count, " ", STR_PAD_LEFT );

    if ( $ShowExTaxColumn == true )
    {
        $headers = $headers . str_pad( $productsForEmail[$i]["product_total_ex_tax"], $len_product_total_ex_tax , " ", STR_PAD_LEFT );
        $lineFillLen += $len_product_total_ex_tax;
    }

    if ( $ShowIncTaxColumn == true )
    {
        $headers = $headers . str_pad( $productsForEmail[$i]["product_total_inc_tax"], $len_product_total_inc_tax, " ", STR_PAD_LEFT );
        $lineFillLen += $len_product_total_inc_tax;
    }

    if ( $ShowSavingsColumn == true )
    {
        $headers = $headers . str_pad( $productsForEmail[$i]["product_savings"], $len_product_savings, " ", STR_PAD_LEFT );
        $lineFillLen += $len_product_savings;
    }

    $mailTemplate->set_var( "headers", $headers );
    $mailTemplate->set_var( "hyphen_line", str_pad( "", $lineFillLen, "-", STR_PAD_LEFT ) );
    $mailTemplate->set_var( "equal_line", str_pad( "", $lineFillLen, "=", STR_PAD_LEFT ) );
    $mailTemplate->set_var( "cart_item", "" );

    for( $i = 1; $i < $count; $i++ )
    {
        $mailTemplate->set_var( "product_id", str_pad( $productsForEmail[$i]["product_id"], $len_product_id, " ", STR_PAD_RIGHT ) );
        $mailTemplate->set_var( "product_number", str_pad( $productsForEmail[$i]["product_number"], $len_product_number, " ", STR_PAD_RIGHT ) );
        $mailTemplate->set_var( "product_name", str_pad( $productsForEmail[$i]["product_name"], $len_product_name, " ", STR_PAD_RIGHT ) );
        $mailTemplate->set_var( "product_price", str_pad( $productsForEmail[$i]["product_price"], $len_product_price, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "product_count", str_pad( $productsForEmail[$i]["product_count"], $len_product_count, " ", STR_PAD_LEFT ) );

        if ( $ShowExTaxColumn == true )
            $mailTemplate->set_var( "product_total_ex_tax", str_pad( $productsForEmail[$i]["product_total_ex_tax"], $len_product_total_ex_tax, " ", STR_PAD_LEFT ) );
        else
            $mailTemplate->set_var( "product_total_ex_tax", "" );

        if ( $ShowIncTaxColumn == true )
            $mailTemplate->set_var( "product_total_inc_tax", str_pad( $productsForEmail[$i]["product_total_inc_tax"], $len_product_total_inc_tax, " ", STR_PAD_LEFT ) );
        else
            $mailTemplate->set_var( "product_total_inc_tax", "" );

        if ( $ShowSavingsColumn == true )
            $mailTemplate->set_var( "product_savings", str_pad( $productsForEmail[$i]["product_savings"], $len_product_savings, " ", STR_PAD_LEFT ) );
        else
            $mailTemplate->set_var( "product_savings", "" );

        $productID = $productsForEmail[$i]["product_id"];

        $mailTemplate->set_var( "cart_item_option", "" );

        if( is_array( $productOptions[$productID] ) )
        {
            foreach( $productOptions[$productID] as $option )
            {
                $mailTemplate->set_var( "option_indent", str_pad( "", $len_option_indent, " ", STR_PAD_LEFT ) );
                $mailTemplate->set_var( "option_id", str_pad( $option["option_id"], $len_option_id, " ", STR_PAD_LEFT ) );
                $mailTemplate->set_var( "option_name", str_pad( $option["option_name"], $len_option_name, " ", STR_PAD_RIGHT ) );
                $mailTemplate->set_var( "option_value", str_pad( $option["option_value"], $len_option_name, " ", STR_PAD_RIGHT ) );
                $mailTemplate->set_var( "option_price", str_pad( $option["option_price"], $len_option_price, " ", STR_PAD_LEFT ) );

                $mailTemplate->parse( "cart_item_option", "cart_item_option_tpl", true );
            }
        }

        $mailTemplate->parse( "cart_item", "cart_item_tpl", true );
    }

    if ( $numberOfItems > 0 )
    {
        $ShowCart = true;
    }

    $mailTemplate->setAllStrings();

    if ( $ShowCart == true )
    {
        if ( $ShowSavingsColumn == true )
        {
            $totalsLen += $len_product_savings;
        }

        $mailTemplate->set_var( "intl-confirming-order", trim( $mailTemplateIni->variable( "strings", "confirming-order" ) ) );
        $mailTemplate->set_var( "intl-thanks_for_shopping", trim( $mailTemplateIni->variable( "strings", "thanks_for_shopping" ) ) );;
        $mailTemplate->set_var( "intl-goods_list", trim( $mailTemplateIni->variable( "strings", "goods_list" ) ) );;

        $mailTemplate->set_var( "subtotal_inc_tax", str_pad( $subinctax, $len_product_total_inc_tax, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "total_inc_tax", str_pad( $inctax, $len_product_total_inc_tax, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "shipping_inc_tax", str_pad( $shipinctax, $len_product_total_inc_tax, " ", STR_PAD_LEFT ) );

        $mailTemplate->set_var( "subtotal_ex_tax", str_pad( $subextax, $len_product_total_ex_tax, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "total_ex_tax", str_pad( $extax, $len_product_total_ex_tax, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "shipping_ex_tax", str_pad( $shipextax, $len_product_total_ex_tax, " ", STR_PAD_LEFT ) );

        $mailTemplate->set_var( "intl-subtotal", str_pad( trim( $mailTemplateIni->variable( "strings", "subtotal" ) ), $totalsLen , " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "intl-shipping", str_pad( trim( $mailTemplateIni->variable( "strings", "shipping" ) ), $totalsLen , " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "intl-total", str_pad( trim( $mailTemplateIni->variable( "strings", "total" ) ), $totalsLen , " ", STR_PAD_LEFT ) );

        $mailTemplate->parse( "cart_item_list", "cart_item_list_tpl" );
        $mailTemplate->parse( "full_cart", "full_cart_tpl" );

        $taxBasisLen = strlen( trim( $mailTemplateIni->variable( "strings", "tax_basis" ) ) );
        $taxPercentageLen = strlen( trim( $mailTemplateIni->variable( "strings", "tax_percentage" ) ) );
        $taxLen = strlen( trim( $mailTemplateIni->variable( "strings", "tax" ) ) );

        $currency->setValue( $total["tax"] );    
        $taxValue = preg_replace( $search, $replace, $locale->format( $currency ) );
        $taxLen =  strlen( $taxValue ) > $taxLen ? strlen(  $taxValue ) : $taxLen;

        foreach( $tax as $taxGroup )
        {
            $currency->setValue( $taxGroup["basis"] );
            $subTaxBasis = trim( preg_replace( $search, $replace, $locale->format( $currency ) ) );
            $taxBasisLen = strlen( $subTaxBasis ) > $taxBasisLen ? strlen(  $subTaxBasis ) : $taxBasisLen;

            $currency->setValue( $taxGroup["tax"] );    
            $subTax = trim( preg_replace( $search, $replace, $locale->format( $currency ) ) );
            $taxLen = strlen( $subTax ) > $taxLen ? strlen(  $subTax ) : $taxLen;

            $taxPercentageLen = strlen( trim( $taxGroup["sub_tax_percentage"] ) ) > $taxPercentageLen ? strlen(  trim( $taxGroup["sub_tax_percentage"] ) ) : $taxPercentageLen;
        }

        $taxPercentageLen += $separateBy;
        $taxLen += $separateBy;
        $taxBasisLen;

        foreach( $tax as $taxGroup )
        {
            $currency->setValue( $taxGroup["basis"] );
            $subTaxBasis = trim( preg_replace( $search, $replace, $locale->format( $currency ) ) );
            $mailTemplate->set_var( "sub_tax_basis", str_pad( $subTaxBasis, $taxBasisLen, " ", STR_PAD_LEFT ) );

            $mailTemplate->set_var( "sub_tax_percentage", str_pad( $taxGroup["percentage"], $taxPercentageLen, " ", STR_PAD_LEFT ) );

            $currency->setValue( $taxGroup["tax"] );    
            $subTax = trim( preg_replace( $search, $replace, $locale->format( $currency ) ) );
            $mailTemplate->set_var( "sub_tax", str_pad( $subTax, $taxLen, " ", STR_PAD_LEFT ) );

            $mailTemplate->parse( "tax_item", "tax_item_tpl", true );
        }


        $mailTemplate->set_var( "intl-tax_basis", str_pad( trim( $mailTemplateIni->variable( "strings", "tax_basis" ) ), $taxBasisLen, " ", STR_PAD_RIGHT ) );;
        $mailTemplate->set_var( "intl-tax_percentage", str_pad( trim( $mailTemplateIni->variable( "strings", "tax_percentage" ) ), $taxPercentageLen, " ", STR_PAD_LEFT ) );;
        $mailTemplate->set_var( "intl-tax", str_pad( trim( $mailTemplateIni->variable( "strings", "tax" ) ), $taxLen, " ", STR_PAD_LEFT ) );;

        $mailTemplate->set_var( "tax", str_pad( $taxValue, $taxBasisLen + $taxLen + $taxPercentageLen, " ", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "tax_hyphen_line", str_pad( "", $taxBasisLen + $taxLen + $taxPercentageLen, "-", STR_PAD_LEFT ) );
        $mailTemplate->set_var( "tax_equal_line", str_pad( "", $taxBasisLen + $taxLen + $taxPercentageLen, "=", STR_PAD_LEFT ) );

        $mailTemplate->parse( "tax_specification", "tax_specification_tpl" );
    }

        $mailTemplate->set_var( "site_url", $SiteURL );
        $mailBody = $mailTemplate->parse( "dummy", "full_cart_tpl" );
        $subjectINI = new eZINI( "kernel/eztrade/user/intl/" . $Language . "/mailorder.php.ini", false );

        $mailSubjectUser = $subjectINI->variable( "strings", "mail_subject_user" ) . " " . $ini->variable( "site", "SiteURL" );
        $mailSubject = $subjectINI->variable( "strings", "mail_subject_admin" ) . " " . $ini->variable( "site", "SiteURL" );

    $checkout = new eZCheckout();
    $instance =& $checkout->instance();
	
    $paymentMethod = $instance->paymentName( $order->paymentMethod() );

    $mailTemplate->set_var( "payment_method", $paymentMethod );

    $mailTemplate->set_var( "comment", $order->comment() );

    $shippingType = $order->shippingType();

    if ( $shippingType && is_a( $shippingType, "eZShippingType" ) )
    {    
        $mailTemplate->set_var( "shipping_type", $shippingType->name() );
    }
    else
    {
        $mailTemplate->set_var( "shipping_type", $shippingType );
    }
    $shippingCost = $order->shippingCharge();

    $shippingVAT = $order->shippingVAT();

    $currency->setValue( $shippingCost );

    $mailTemplate->set_var( "shipping_cost", $locale->format( $currency ) );

    $sum += $shippingCost;
    $currency->setValue( $sum );
    $mailTemplate->set_var( "order_sum", $locale->format( $currency ) );

    $currency->setValue( $totalVAT + $shippingVAT );
    $mailTemplate->set_var( "order_vat_sum", $locale->format( $currency ) );

    $mailTemplate->set_var( "order_id", $order->id() );
    $mailTemplate->set_var( "credit_card_information", "" );

    // Send E-mail    
    $mail = new eZMail();
    
    $mailBody = $mailTemplate->parse( "dummy", "order_sendt_tpl" );
    // $mailBody = ln2wrn($mailBody, $OrderUserSystem);
    $mailBody .= "\r\n";
	
    $mail->setFrom( $OrderSenderEmail );
    $mail->setTo( $user->email() );
    $mail->setSubject( $mailSubjectUser );
    // set and send customer email
    $mail->setBody( $mailBody );
    $mail->send();
   
    // admin email
    // check to see if the email should be encrypted for the administrator
    $mailEncrypt = $ini->variable( "eZTradeMain", "MailEncrypt" );

    // load user account number 
    $AccountNumber = $user->accountNumber();
    
    if ( $mailEncrypt == "GPG" )
    {	
	// initialize GPG class 
        $mailBody = "";
		// gpg site ini variables
        $mailKeyname = $ini->variable( "eZTradeMain", "RecipientGPGKey" );
        $wwwUser = $ini->variable( "eZTradeMain", "ApacheUser" );

        $gpgSystem = $ini->variable( "eZTradeMain", "RecipientGPGSystem" );
        $gpgUserHomeDir = $ini->variable( "eZTradeMain", "ApacheUserHome" );
        $dotGnuPGDir = $ini->variable( "eZTradeMain", "ApacheUserGPGDir" );

        // At this point you can add any information to the template as needed
        // remember to provide a variable in the template for it.
     	
		// add credit card info for the administrator
			switch( $paymentMethodID )
    	    {
 	           case "1" :
   	           {
			    $mailTemplate->set_var( "payment_method", $paymentMethod );
    	        $mailTemplate->set_var( "cc_number", $CCNumber );
    	        $mailTemplate->set_var( "cc_expiremonth", $ExpireMonth );
    	        $mailTemplate->set_var( "cc_expireyear", $ExpireYear );
                // parse the template block
                $mailTemplate->parse( "credit_card_information", "credit_card_information_tpl");

		$mailTemplate->set_var( "customer_account_number_information", "");
		$mailTemplate->set_var( "customer_account_number", "" );
               }
          	   break;
               case "2" :
               {
                $mailTemplate->set_var( "payment_method", $paymentMethod );
    	        $mailTemplate->set_var( "cc_number", $CCNumber );
    	        $mailTemplate->set_var( "cc_expiremonth", $ExpireMonth );
    	        $mailTemplate->set_var( "cc_expireyear", $ExpireYear );
                // parse the template block
                $mailTemplate->parse( "credit_card_information", "credit_card_information_tpl");

                $mailTemplate->set_var( "customer_account_number_information", "");
                $mailTemplate->set_var( "customer_account_number", "" );
               }
               break;
               case "3" :
               {
                $mailTemplate->set_var( "payment_method", $paymentMethod );
                $mailTemplate->set_var( "credit_card_information", "");
    	        $mailTemplate->set_var( "cc_number", "" );
    	        $mailTemplate->set_var( "cc_expiremonth", "" );
    	        $mailTemplate->set_var( "cc_expireyear", "" );
		
		$mailTemplate->set_var( "customer_account_number_information", "");
                $mailTemplate->set_var( "customer_account_number", "" );
               }
               break;
               case "4" :
               {
                $mailTemplate->set_var( "payment_method", $paymentMethod );
                $mailTemplate->set_var( "credit_card_information", "");
    	        $mailTemplate->set_var( "cc_number", "" );
    	        $mailTemplate->set_var( "cc_expiremonth", "" );
    	        $mailTemplate->set_var( "cc_expireyear", "" );

                $mailTemplate->set_var( "customer_account_number", $AccountNumber );
                $mailTemplate->parse( "customer_account_number_information", "customer_account_number_information_tpl");
               }
               break;
   	       case "5" :
	       {
                $mailTemplate->set_var( "payment_method", $paymentMethod );
                $mailTemplate->set_var( "credit_card_information", "");
                $mailTemplate->set_var( "cc_number", "" );
                $mailTemplate->set_var( "cc_expiremonth", "" );
                $mailTemplate->set_var( "cc_expireyear", "" );

                $mailTemplate->set_var( "customer_account_number_information", "");
                $mailTemplate->set_var( "customer_account_number", "" );
	       }
	       break;
     		}
		// parse the template
		$mailBody = $mailTemplate->parse( "dummy", "order_sendt_tpl" );
		
		// seems line this is not needed in this version of ez? why?
	    // $mailBody = ln2wrn($mailBody, $gpgSystem);

    	// encrypt mailBody
        $mytext = new ezgpg($mailBody, $mailKeyname, $wwwUser, $gpgSystem, $dotGnuPGDir, $gpgUserHomeDir);
		// debug: mail clear text
		// $mailBody = $mailBody;
		$mailBody = $mytext->body;
		// pad encrypted text block
		// $mailBody = "\n". $mailBody;
		// set email body content
        	$mail->setBody( $mailBody );
	}
        else {
            $mailTemplate->set_var( "credit_card_information", "");
	    $mailTemplate->set_var( "customer_account_number_information", "");
        }

    $mail->setSubject( $mailSubject );
    $mail->setTo( $OrderReceiverEmail );
    $mail->setFrom( $user->email() );
    // send email
    $mail->send();
	
    // Debug Variables #################################
     // $print_cmd = "<pre>\n &nbsp; \n". $mytext->body ."\n &nbsp; \n</pre>"; // $mytext->pcmd;
    // $print_cmd .= "\n";
    // system( $print_cmd );
    // Debug Variables #################################
    // $print_cmd = "<pre>\n &nbsp; \n". $mailBody ."\n &nbsp; \n</pre>"; // $mytext->pcmd;
    // print( $print_cmd );
    // exit();
		
    // get the cart or create it
    $cart = new eZCart();
    $cart = $cart->getBySession( $session );
    foreach ( $cart->items() as $item )
    {
        // set the wishlist item to bought if the cart item is
        // fetched from a wishlist
        $wishListItem = $item->wishListItem();
        if ( $wishListItem )
        {
            $wishListItem->setIsBought( true );
            $wishListItem->store();
        }
    }

    // Decrease product/option quantity
    $items =& $cart->items();
    foreach ( $items as $item )
    {
        $product =& $item->product();
        $count = $item->count();
        $quantity = $product->totalQuantity();
        $values =& $item->optionValues();
        $selected_values = array();
        foreach ( $values as $value )
            {
                $option_value =& $value->optionValue();
                $selected_values[] = $option_value->id();
            }

        $changed_quantity = false;
        if ( !(is_bool( $quantity ) and !$quantity) )
        {
            $max_value = max( $quantity - $count, 0 );
            $product->setTotalQuantity( $max_value );
            if ( $max_value == 0 and $DiscontinueQuantityless )
                $product->setDiscontinued( true );
            $product->store();
            $changed_quantity = true;
        }
        $options =& $product->options();
        $change_discontinuity = false;
        $max_max_value = 0;
        $has_value = false;
        foreach( $options as $option )
        {
            $option_values =& $option->values();
            foreach( $option_values as $option_value )
            {
                if ( in_array( $option_value->id(), $selected_values ) )
                {
                    $value_quantity = $option_value->totalQuantity();
                    if ( !(is_bool( $value_quantity ) and !$value_quantity) )
                    {
                        $max_value = max( $value_quantity - $count, 0 );
                        $max_max_value = max( $max_max_value, $max_value );
                        $option_value->setTotalQuantity( $max_value );
                        $option_value->store();
                        $changed_quantity = true;
                    }
                }
                $value_quantity = $option_value->totalQuantity();
                if ( (is_bool( $value_quantity ) and !$value_quantity) or $value_quantity > 0 )
                {
                    $has_value = true;
                }
            }
        }
        $productQuantity = $product->totalQuantity();
        if ( ( $max_max_value == 0 and !$has_value and $DiscontinueQuantityless ) and !(is_bool( $productQuantity ) and !$productQuantity ) )
        {
            $product->setDiscontinued( true );
            $product->store();
        }
        if ( $changed_quantity )
        {
            deleteCache( $product, false, false, false );
        }

        $user =& eZUser::currentUser();



        for( $i=0; $i < $count; $i++ )
        {
            // Create vouchers
            $voucherInfo =& $item->voucherInformation();
            if ( $item->voucherInformation() )
            {
                $voucher = new eZVoucher( );
                $voucher->generateKey();
                $voucher->setAvailable( true );
                $voucher->setUser( $user );
                $voucher->setPrice( $voucherInfo->price() );
                $voucher->setTotalValue( $voucherInfo->price() );
                $voucher->setProduct( $voucherInfo->product() );
                $voucher->store();
                $voucherInfo->setVoucher( $voucher );
                $voucherInfo->store();
                $voucherInfo->sendMail();
            }
        }
    }
    
    //
    if ( is_file ( "checkout/user/postpayment.php" ) )
    {
        include( "checkout/user/postpayment.php" );
    }
    
    //  $cart->clear();

    $OrderID = $order->id();

    $preOrder = new eZPreOrder( $PreOrderID );
    $preOrder->setOrderID( $OrderID );
    $preOrder->store();

    $payedWith = $session->arrayValue( "PayedWith" );

    if ( is_array ( $payedWith ) )
    {
        foreach( $payedWith as $voucherID => $price )
        {
            $voucher = new eZVoucher( $voucherID );
            $voucher->setPrice( $voucher->price() - $price );
            if ( $voucher->price() <= 0 )
                $voucher->setAvailable( false );

            $voucher->store();
            
            $voucherUsed = new eZVoucherUsed();
            $voucherUsed->setVoucher( $voucher );
            $voucherUsed->setPrice( $price );
            $voucherUsed->setOrder( $order );
            $voucherUsed->setUser( $user );
            $voucherUsed->store();
            
        }
        $session->setVariable( "PayedWith", "" );
        $session->setVariable( "PayWithVoucher", "" );
    }
    
    // $cart->delete();

    // call the payment script after the payment is successful.
    // some systems needs this, e.g. to print out the OrderID which was cleared..
    $Action = "PostPayment";
    include( $instance->paymentFile( $paymentMethod ) );

    // Turn of SSL and redirect to http://

    $session->setVariable( "SSLMode", "disabled" ); 

    // set the confirmation 
    // eZHTTPTool::header( "Location: http://$HTTP_HOST/trade/ordersendt/$OrderID/" );
    eZHTTPTool::header( "Location: https://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/confirmation/" );
    exit();
}
?>
