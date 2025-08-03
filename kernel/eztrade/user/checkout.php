<?php
//
// $Id: checkout.php 9904 2004-07-09 11:44:47Z br $
//
// Created on: <28-Sep-2000 15:52:08 bf>
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

//$_GET = array_map('stripslashes',  $_GET);
//$_POST = array_map('stripslashes',  $_POST);

// // include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$shippingname = eZHTTPTool::getVar( "shippname" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$OrderSenderEmail = $ini->read_var( "eZTradeMain", "OrderSenderEmail" );
$OrderReceiverEmail = $ini->read_var( "eZTradeMain", "OrderReceiverEmail" );
$ForceSSL = $ini->read_var( "eZTradeMain", "ForceSSL" );
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$ShowExTaxColumn = $ini->read_var( "eZTradeMain", "ShowExTaxColumn" ) == "enabled" ? true : false;
$ShowIncTaxColumn = $ini->read_var( "eZTradeMain", "ShowIncTaxColumn" ) == "enabled" ? true : false;
$ShowExTaxTotal = $ini->read_var( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
$ShowTaxBasis = $ini->read_var( "eZTradeMain", "ShowTaxBasis" ) == "enabled" ? true : false;
$ColSpanSizeTotals = $ini->read_var( "eZTradeMain", "ColSpanSizeTotals" );
$StateTaxBilling = $ini->read_var( "eZTradeMain", "StateTaxBilling" ) == "enabled" ? true : false;
$StateTaxShipping = $ini->read_var( "eZTradeMain", "StateTaxShipping" ) == "enabled" ? true : false;
$CountryVATDiscrimination = $ini->read_var( "eZTradeMain", "CountryVATDiscrimination" ) == "enabled" ? true : false;

$OrderDisclaimer = $ini->read_var( "eZTradeMain", "OrderDisclaimer" ) == "enabled" ? true : false;
$OrderDisclaimerText = $ini->read_var( "eZTradeMain", "OrderDisclaimerText" );
$ShippingDisclaimerText = $ini->read_var( "eZTradeMain", "ShippingDisclaimerText" );

//Depricated ? $checkups = $ini->read_var( "eZTradeMain", "UPSOFF" );
$upscheck = $ini->read_var( "eZTradeMain", "UPSXMLShipping" )=="enabled"?1:0;
$uspscheck = $ini->read_var( "eZTradeMain", "USPSXMLShipping" )=="enabled"?1:0;

if(($upscheck==0)&&($uspscheck==0))
$checkups=0;
else
$checkups=1;

// Set some variables to defaults.
$ShowCart = false;
$ShowSavingsColumn = false;

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezoption.php" );
// include_once( "eztrade/classes/ezoptionvalue.php" );
// include_once( "eztrade/classes/ezcart.php" );
// include_once( "eztrade/classes/ezcartitem.php" );
// include_once( "eztrade/classes/ezcartoptionvalue.php" );
// include_once( "eztrade/classes/ezpreorder.php" );
// include_once( "eztrade/classes/ezorder.php" );
// include_once( "eztrade/classes/ezwishlist.php" );
// include_once( "eztrade/classes/ezvoucher.php" );

// shipping
// include_once( "eztrade/classes/ezshippingtype.php" );
// include_once( "eztrade/classes/ezshippinggroup.php" );
// include_once( "eztrade/classes/ezcheckout.php" );

// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );

// include_once( "ezsession/classes/ezsession.php" );

// include_once( "ezmail/classes/ezmail.php" );

//$currentTypeID = array();

if(eZHTTPTool::getVar( "ShippingTypeID" )){
    $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID", true );
    $test = $currentTypeID[0];
}else{
    $currentTypeID[0] = "03";
    $test = $currentTypeID[0];
}

$cart = new eZCart();

// there are way to manny calls to get the current user in
// this particular file, i've commented out the duplicates as needed.
// $user = eZUser::currentUser();

$session =& eZSession::globalSession();

$user = eZUser::currentUser();

if ( !is_a( $user, "eZUser" ) )
    eZHTTPTool::header( "Location: /user/login/" );

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

// get the cart or create it
$cart = $cart->getBySession( $session, "Cart" );
if ( !$cart || !$cart->items() )
{
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language , "checkout.php" );

$t->setAllStrings();

$t->set_file( "checkout_page_tpl", "checkout.tpl" );

$t->set_block( "checkout_page_tpl", "empty_cart_tpl", "empty_cart" );

$t->set_block( "checkout_page_tpl", "full_cart_tpl", "full_cart" );
$t->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "header_savings_item_tpl", "header_savings_item" );
$t->set_block( "cart_item_list_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );
$t->set_block( "cart_item_list_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );

$t->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );
$t->set_block( "cart_item_tpl", "cart_savings_item_tpl", "cart_savings_item" );
$t->set_block( "cart_item_tpl", "cart_inc_tax_item_tpl", "cart_inc_tax_item" );
$t->set_block( "cart_item_tpl", "cart_ex_tax_item_tpl", "cart_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_basis_tpl", "cart_item_basis" );
$t->set_block( "cart_item_basis_tpl", "basis_savings_item_tpl", "basis_savings_item" );
$t->set_block( "cart_item_basis_tpl", "basis_inc_tax_item_tpl", "basis_inc_tax_item" );
$t->set_block( "cart_item_basis_tpl", "basis_ex_tax_item_tpl", "basis_ex_tax_item" );

$t->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );
$t->set_block( "cart_item_option_tpl", "option_savings_item_tpl", "option_savings_item" );
$t->set_block( "cart_item_option_tpl", "option_inc_tax_item_tpl", "option_inc_tax_item" );
$t->set_block( "cart_item_option_tpl", "option_ex_tax_item_tpl", "option_ex_tax_item" );

$t->set_block( "full_cart_tpl", "subtotal_ex_tax_item_tpl", "subtotal_ex_tax_item" );
$t->set_block( "full_cart_tpl", "subtotal_inc_tax_item_tpl", "subtotal_inc_tax_item" );

$t->set_block( "full_cart_tpl", "shipping_ex_tax_item_tpl", "shipping_ex_tax_item" );
$t->set_block( "full_cart_tpl", "shipping_inc_tax_item_tpl", "shipping_inc_tax_item" );

$t->set_block( "full_cart_tpl", "vouchers_tpl", "vouchers_tpl" );
$t->set_block( "vouchers_tpl", "voucher_item_tpl", "voucher_item" );
$t->set_block( "checkout_page_tpl", "remove_voucher_tpl", "remove_voucher" );

$t->set_block( "full_cart_tpl", "total_ex_tax_item_tpl", "total_ex_tax_item" );
$t->set_block( "full_cart_tpl", "total_inc_tax_item_tpl", "total_inc_tax_item" );
$t->set_block( "full_cart_tpl", "tax_total_tpl", "tax_total" );


$t->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
$t->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );

$t->set_block( "full_cart_tpl", "shipping_type_tpl", "shipping_type" );



$t->set_block( "checkout_page_tpl", "shipping_select_tpl", "shipping_select" );

$t->set_block( "checkout_page_tpl", "shipping_address_tpl", "shipping_address" );
$t->set_block( "checkout_page_tpl", "billing_address_tpl", "billing_address" );
$t->set_block( "billing_address_tpl", "billing_option_tpl", "billing_option" );
$t->set_block( "checkout_page_tpl", "wish_user_tpl", "wish_user" );

$t->set_block( "checkout_page_tpl", "sendorder_item_tpl", "sendorder_item" );

$t->set_block( "checkout_page_tpl", "show_payment_tpl", "show_payment" );
$t->set_block( "show_payment_tpl", "payment_method_tpl", "payment_method" );


$t->set_var( "show_payment", "" );

$t->set_var( "show_payment", "" );
$t->set_var( "price_ex_vat", "" );
$t->set_var( "price_inc_vat", "" );
$t->set_var( "cart_item", "" );
$t->set_var(  "wish_user", "" );
$t->set_var( "pay_with_voucher", "false" );


$t->set_block( "order_disclaimer_tpl", "order_disclaimer" );
$t->set_block( "shipping_disclaimer_tpl", "shipping_disclaimer" );

/*
  $t->set_var(  "order_disclaimer_text", "$OrderDisclaimerText" );
  $t->set_var(  "shipping_disclaimer_text", "$ShippingDisclaimerText" );
*/

$t->set_var(  "order_disclaimer_text", "By submitting this order, I hereby agree to FullThrottle.com's <a href=\"/policy/return\">Return Policy</a> and <a href=\"/policy/shipping\">Shipping Policy</a>" );

$t->set_var(  "shipping_disclaimer_text", "Please call us before ordering to confirm availability if you need the items delivered by a specific date!" );

$t->parse( "order_disclaimer", "order_disclaimer_tpl" );
$t->parse( "shipping_disclaimer", "shipping_disclaimer_tpl" );


if ( isset ( $RemoveVoucher ) )

{
    if ( count ( $RemoveVoucherArray ) > 0 )
    {
        $newArray = array();
        $payWithVoucher = $session->arrayValue( "PayWithVoucher" );

        while( list($key,$voucherID) = each( $payWithVoucher ) )
        {
            if ( !in_array ( $voucherID, $RemoveVoucherArray ) )
                 $newArray[$voucherID] = $price;
        }

        $session->setVariable( "PayWithVoucher", "" );
    }
}

//wha?
$test = "test";

if ( isset( $SendOrder ) )
{

  // die ( eZHTTPTool::getVar( "ShippingTypeID", true ) );

  if( eZHTTPTool::getVar( "ShippingTypeID" ) ){
    // print(  "totaly ");
    // which shipping type?
    $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
    // $currentTypeID = $currentTypeID['0'];
    // $currentTypeID[0] = $currentTypeID['0'];

    // $ShippingTypeID = $currentTypeID['0'];
    // echo '<br /> CurrentTypeID:: ' . $ShippingTypeID;

    // echo "REQUEST: <br><br>";
    // print_r($_REQUEST);

/*
    echo "<br><br>";
    $ShippingTypeID = $_REQUEST[ShippingTypeID];
    echo 'ShippingType: ' . $ShippingTypeID['0'];
*/
 
}else{
    // default to ... shipping type
    // print(  "totaly not");
    $currentTypeID[0] = "03";
  }

/*
 echo $currentTypeID;
   phpinfo();
 die();

*/

    // set the variables as session variables and make sure that it is not read by
    // the HTTP GET variables for security.
if(empty($currentTypeID[0]))
  {
    $uuid = $user->id();
     echo "<h3>Error:Your Shipping Address may be wrong.</h3>";
     // eZHTTPTool::header( "Location:/user/userwithaddress/edit/$uuid/" );
     eZHTTPTool::header( "Location: /trade/checkout/" );
     exit;
  }

    $preOrder = new eZPreOrder();
    $preOrder->store();

    //////////////////////////////////////////////////////////////////////////////
    // User Info Swap ....

    $session->setVariable( "PreOrderID", $preOrder->id() );
    /*

     $session->setVariable( "ShippingAddressID", $_POST["ShippingAddressID"] );
     $session->setVariable( "BillingAddressID",  $_POST["BillingAddressID"] );

    $session->setVariable( "ShippingAddressID", eZHTTPTool::getVar( "ShippingAddressID", true ) );
    $session->setVariable( "BillingAddressID", eZHTTPTool::getVar( "BillingAddressID", true ) );

     $session->setVariable( "ShippingAddressID", eZHTTPTool::getVar( "ShippingAddressID" );
     $session->setVariable( "BillingAddressID", eZHTTPTool::getVar( "BillingAddressID" );

    */

     $session->setVariable( "ShippingAddressID", eZHTTPTool::getVar( "ShippingAddressID", true ) );
     $session->setVariable( "BillingAddressID", eZHTTPTool::getVar( "BillingAddressID", true ) );

    //////////////////////////////////////////////////////////////////////////////

    $session->setVariable( "TotalCost", eZHTTPTool::getVar( "TotalCost", true ) );
    $session->setVariable( "TotalVAT", eZHTTPTool::getVar( "TotalVAT", true ) );

    if ( eZHTTPTool::getVar( "PayWithVoucher", true ) == "true" )
    {
        if ( eZHTTPTool::getVar( "PaymentMethod", true ) )
            $session->setArray( "PaymentMethod", array( eZHTTPTool::getVar( "PaymentMethod", true ),  "voucher_done" ) );
        else
            $session->setArray( "PaymentMethod", array( "voucher_done" ) );
    }
    else
        $session->setArray( "PaymentMethod", array( eZHTTPTool::getVar( "PaymentMethod", true ) ) );
    
    //    $session->setVariable( "AuthCode", eZHTTPTool::getVar( "AuthCode", true ) );

    $session->setVariable( "Comment", eZHTTPTool::getVar( "Comment", true ), eZHTTPTool::getVar( "Comment", true ) );

    $session->setVariable( "ShippingCost", $cart->shippingCost( new eZShippingType( $currentTypeID ) ) );
    $session->setVariable( "ShippingVAT", $cart->shippingVAT( new eZShippingType( $currentTypeID ) ) );

    $session->setVariable( "ShippingCost",eZHTTPTool::getVar("shippcost"),true);
    $session->setVariable( "ShippingVAT", eZHTTPTool::getVar("ShippingVAT"),true);
    $session->setVariable( "ShippingTypeID", $currentTypeID[0], true);

    // create a new order
    $order = new eZOrder();
    $user =& eZUser::currentUser();

    if ( !is_a( $user, "eZUser" ) )
    {
        eZLog::writeWarning( "user/payment.php: Got paymentSuccess without user logged in" );
        eZHTTPTool::header( "Location: /trade/cart/" );
        exit();
    }


    //////////////////////////////////////////////////////////////////////////////
    // very important step .... possible breakdown for "user info swap"

    $order->setUser( $user );

    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) != "enabled" )
    {
        $billingAddressID = $shippingAddressID;
    }

    $addressList = $user->addresses();

    $shippingAddress = new eZAddress( $session->variable( "ShippingAddressID" ) );
    $billingAddress = new eZAddress( $session->variable( "BillingAddressID" ) );

    //////////////////////////////////////////////////////////////////////////////
    // assumsions here are fairly safe, but not completely (user could switch order of usage here)
    /*
    if( $addressList[1] ){
      $shippingAddress = $addressList[1];
      $billingAddress  = $addressList[0];
    }else{
      $shippingAddress = $addressList[0];
      $billingAddress  = $addressList[0];  
    }
    */

    // kracker (2005.05.20)
    /*
    print_r( $shippingAddress );
    echo "<br>";
    print_r( $billingAddress );
    die();
    */

    //
    //////////////////////////////////////////////////////////////////////////////

    $order->setShippingCharge( $session->variable( "ShippingCost" ) );
    $order->setShippingVAT( $session->variable( "ShippingVAT" ) );

    //////////////////////////////////////////////////////////////////////////////
    // very important step .... possible breakdown for "user info swap"

    $order->setShippingAddress( $shippingAddress, $user );
    $order->setBillingAddress( $billingAddress, $user );

    //
    //////////////////////////////////////////////////////////////////////////////

    $order->setPaymentMethod( $session->arrayValue( "PaymentMethod" ) );

    // $order->setAuthCode( $session->variable( "AuthCode" ) );

    // $order->setShippingTypeID( $session->variable( "ShippingTypeID" ) );

    $order->setShippingTypeID($currentTypeID[0]);

    $order->setComment( stripslashes($Comment) );

    $order->setPersonID( $cart->personID() );
    $order->setCompanyID( $cart->companyID() );

    if($_POST['ShippingVAT'] > 0 || $_POST['TotalVAT'] > 0 )
      $order->setIsVATInc( true );
    else
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

    $session->setVariable( "OrderID", $order_id );
    $session->setVariable( "OrderConfirmation", $order_id );

    foreach ( $items as $item )
    {
        $totalVAT=0.0;
        $price=0.0;
        $totalPrice=0.0;
        $product = $item->product();

        // create a new order item.
        $orderItem = new eZOrderItem();
        $orderItem->setOrder( $order );
        $orderItem->setProduct( $product );
        $orderItem->setCount( $item->count() );

        // Set the product price.
        $price = $item->correctPrice( false, true, false );
        $orderItem->setPrice( $price );

        // Set the VAT for this product.
        $totalVAT = $item->vat( false, true );
        $orderItem->setVAT( $totalVAT );

        $expiryTime = $product->expiryTime();
        if ( $expiryTime > 0 )
            $orderItem->setExpiryDate( (new eZDateTime())->timeStamp( true ) + ( $expiryTime * 86400 ) );
        else
            $orderItem->setExpiryDate( 0 );

        $orderItem->store();

        // Store the optionvalues.
        $optionValues =& $item->optionValues();
        if ( count( $optionValues ) > 0 )
        {
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
    }


    eZHTTPTool::header( "Location: /trade/payment/" );
    exit();
}

// show the shipping types
$type = new eZShippingType();
$types = $type->getAll();

// print_r($types);


//qcomp AMin.
if ($cart->ShipServiceCode && (! eZHTTPTool::getVar( "ShippingTypeID" )))
{
  $currentTypeID[0] = $cart->ShipServiceCode;
}

if ($cart->AddressID)
{
  $shipaddID = $cart->AddressID;
}


if (true || eZHTTPTool::getVar( "ShippingTypeID" ) || eZHTTPTool::getVar( "ShippingAddressID" ))
{
  //$currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );
  $shipaddID = eZHTTPTool::getVar( "ShippingAddressID" );
  $cart->AddressID=$shipaddID;
  $cart->ShipServiceCode=$currentTypeID[0];
  $cart->storeshipoptions($currentTypeID[0],$shipaddID);
}

//End qcomp AMin.


$markup =0;

$currentShippingType = false;

$thisuser =& eZUser::currentUser();

$id = $thisuser->id();

$t->set_var( "shipping_type_error","");

if($checkups==1)
{

}
else
{

foreach ( $types as $type )
{
    $t->set_var( "shipping_type_id", $type->id() );
    $t->set_var( "shipping_type_name", $type->name() );
    $t->set_var( "shipping_type_cost", "" );
}

    if ( is_numeric( $currentTypeID ) )
    {
        if ( $currentTypeID == $type->id() )
        {
            $currentShippingType = $type;
            $t->set_var( "type_selected", "selected" );
        }
        else
            $t->set_var( "type_selected", "" );
    }
    else
    {
        if ( $type->isDefault() )
        {
            $currentShippingType = $type;
            $t->set_var( "type_selected", "selected" );
        }
        else
            $t->set_var( "type_selected", "" );
    }

    $t->parse( "shipping_type", "shipping_type_tpl", true );
}

$vat = true;
$address = new eZAddress();

if ($cart->AddressID){
  $taxaddressID = $cart->AddressID;
}elseif( eZHTTPTool::getVar( "ShippingAddressID" ) ){
  $taxaddressID = eZHTTPTool::getVar( "ShippingAddressID" );
}else {
  $addressList = $thisuser->addresses();

  if( isset($addressList[1]) ){
    $taxaddressObj = $addressList[1];
  }else{
    $taxaddressObj = $addressList[0];
  }

  //$taxaddressObj = $address->mainAddress($thisuser);
  // print_r($addressList);

  $taxaddressID = $taxaddressObj->ID();
}
	
/////////////////////////////////////////////////////////////////
// possible bug? "User Info Swap"

//if (  eZHTTPTool::getVar( "ShippingAddressID" ) )
//{

    $shippingAddress = new eZAddress( $taxaddressID );
    $shippingRegion =& $shippingAddress->region();

//    $billingAddress = new eZAddress( $BillingAddressID );
//    $billingRegion =& $billingAddress->region();
//    $country =& $address->country();


///////////////////////////////////////////////////////////
// Open Ended Comment : Re : Shipping Vats .... ?
/*
	if ( !$shippingRegion->hasVAT() || !$StateTaxShipping 
		|| ( $shippingRegion->hasVAT() && !$billingRegion->hasVAT() )
		)
        $vat = false;  
*/
	
	if ( !$shippingRegion )
           $vat = false;
	else
		{
			if ( !$shippingRegion->hasVAT() )
        $vat = false;
}
/*
else if ( $CountryVATDiscrimination == true )
{
    $address = new eZAddress();
    $mainAddress = $address->mainAddress( $user );

    $country =& $mainAddress->country();
    if ( !$country && !$country->hasVAT() )
    {
        $vat = false;
        $totalVAT = 0;
    }
    else
    {
        $vat = false;
        $totalVAT = 0;
    }
}
*/
if ( $vat == false )
{
    $ShowExTaxColumn = true;
    $PricesIncludeVAT = false;
    $ShowExTaxTotal = true;
    $ShowIncTaxColumn = false;
}
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

$items = $cart->items( );

foreach ( $items as $item )
{
    if ( $item->correctSavings( false, true, $PricesIncludeVAT ) > 0 )
    {
        $ShowSavingsColumn = true;
    }
}

foreach ( $items as $item )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
    $i++;
    $t->set_var( "cart_item_id", $item->id() );
    $product =& $item->product();

    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_number", $product->productNumber() );
    $t->set_var( "product_price", $item->localePrice( false, true, $PricesIncludeVAT, false ) );
    $t->set_var( "product_count", $item->count() );
    $t->set_var( "product_total_ex_tax", $item->localePrice( true, true, false ) );
    $t->set_var( "product_total_inc_tax", $item->localePrice( true, true, true ) );

    if( !isset( $numberOfItems ) )
        $numberOfItems = 1;
    else
        $numberOfItems++;

    $numberOfOptions = 0;

    $optionValues =& $item->optionValues();

    $t->set_var( "cart_item_option", "" );
    $t->set_var( "cart_item_basis", "" );

    if ( $product->productType() == 2 )
        $useVoucher = true;
    else
        $useVoucher = false;

    foreach ( $optionValues as $optionValue )
    {
        turnColumnsOnOff( "option" );

        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();
        $value_quantity = $value->totalQuantity();
        $descriptions = $value->descriptions();

        $t->set_var( "option_id", $option->id() );
        $t->set_var( "option_name", $option->name() );

        if ( count( $descriptions ) > 0 )
        {
            $t->set_var( "option_value", $descriptions[0] );
        }
        else
        {
            $t->set_var( "option_value", $value->option()->name() );
        }

		if ( $value->localePrice( $PricesIncludeVAT, $product ) == 0 )
			$t->set_var( "option_price", "" );
		else
        $t->set_var( "option_price", $value->localePrice( $PricesIncludeVAT, $product ) );
        $t->parse( "cart_item_option", "cart_item_option_tpl", true );

        $numberOfOptions++;
    }
    turnColumnsOnOff( "cart" );
    turnColumnsOnOff( "basis" );

    if ( $ShowSavingsColumn == true )
    {
        if ( $item->correctSavings( true, true, $PricesIncludeVAT ) > 0 )
        {
            $t->set_var( "product_savings", $item->localeSavings( true, true, $PricesIncludeVAT ) );
        }
        else
        {
            $t->set_var( "product_savings", "&nbsp;" );
        }
        $t->parse( "cart_savings_item", "cart_savings_item_tpl" );
    }
    else
    {
        $t->set_var( "cart_savings_item", "" );
    }

    if ( $numberOfOptions ==  0 )
    {
        $t->set_var( "cart_item_option", "" );
        $t->set_var( "cart_item_basis", "" );
    }
    else
    {
        if( $product->price() > 0 )
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

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

if ( $ShowCart == true )
{
    // Vouchers
   // qcomp Amin

   ///////////////////////////////////////////////
   // graham : 2005-05-24 : this gets the shipping types
   $upsresults =  $cart->cartTotals( $tax, $total, false, true, $vat );

//print_r($total); exit();


// This is the real shipping name code

if($checkups==1)
{
  if($thisuser)
  {
      $getnames =array( 
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

       $codearr = array();
       $codarr = array();

       if(empty($upsresults) ||  $upsresults[0] == '||')
       {
// Depricated Oneline?   
// echo "<h3><font color=\"#ff0000\">Error:Your Shipping address may be wrong.</font></h3>";

	      $t->set_var( "shipping_type_error","<font color=\"#ff0000\">Error:  Your Shipping address may be wrong.  Please re-check your post code.</font>");
       }
       else
       {
	        $t->set_var( "shipping_type_error","");
       }

       foreach($upsresults as $upsr)
       {
            $splups=explode("\|\|",$upsr);
            if( isset( $splups[1] ) && $splups[1] != "" )
            $codearr[] = array($splups[0],$splups[1]);
       }

       $codesort = asort($codearr);

       foreach($codearr as $cod)
       {
	      $codarr[] = $cod;
       }

       $ii=0;
       foreach($codarr as $co)
       {
           if( isset( $co[1] ) && $co[1] != "" )
           $ser_code = $co[1];
        else
           $ser_code = false;

        // $splups=explode("\|\|",$upsr);

        //for USPS
        if (isset($getnames["$ser_code"]))
        {
            $service_name= $getnames["$ser_code"];
        }
        else
        {
            $service_name=$ser_code;
        }
        
        // Shippment Type
        $concade =  $service_name ." $ " .sprintf("%01.2f", $co[0]);
            // print($concade . "<br />");
        // print($co[1] . "<br />");

        $t->set_var( "shipping_type_id",$co[1]);
        $t->set_var( "shipping_type_name",$concade);
        $t->set_var( "shipping_type_cost",sprintf("%01.2f", $co[0]));

        if ( $currentTypeID[0] == $co[1])
        {
            $test = "$currentTypeID[0], $co[1], $concade";
            $currentShippingType = $type;

            $t->set_var( "ship_get_name",$concade);
            $t->set_var( "type_selected", "selected" );
            $markup = 1;
        }
        elseif((isset($cc[0]) && ($ii==0)&&($cc[0])))
        {
        $t->set_var( "type_selected", "selected" );
        $markup = 1;
        }
        else
        {
        $t->set_var( "type_selected", "" );
        }

        $t->parse( "shipping_type", "shipping_type_tpl", true );
        
        $ii +=1;
       }
  }
  else
  {
    foreach ( $types as $type )
    {
      $t->set_var( "shipping_type_id","");
      $t->set_var( "shipping_type_name","");
      $t->set_var( "shipping_type_cost", "" );

      $t->parse( "shipping_type", "shipping_type_tpl", true );
    }
  }
}

// End qcomp Amin

    $t->set_var( "empty_cart", "" );
    $t->set_var( "voucher_item", "" );

    $vouchers = $session->arrayValue( "PayWithVoucher" );
    if ( count ( $vouchers ) > 0 )
    {
        $t->set_var( "vouchers", "" );
        $t->set_var( "voucher_item", "" );
        $i=1;
        $continue = true;

        foreach( $vouchers as $voucherID )
        {
            if ( $continue )
            {
                $voucher = new eZVoucher( $voucherID );

                $voucherID = $voucher->id();

                $voucherPrice = $voucher->price();

                $cart->cartTotals( $tax, $voucherPrice, $voucher );

                if ( $voucherPrice["inctax"] > $total["inctax"] )
                {
                    $subtractIncVAT["inctax"] = $total["inctax"];
                    $currency->setValue( $total["inctax"] );
                    $t->set_var( "voucher_price_inc_vat", $locale->format( $currency ) );

                    $subtractExTax["extax"] = $total["extax"];
                    $currency->setValue( $total["extax"] );
                    $t->set_var( "voucher_price_ex_vat", $locale->format( $currency ) );
                    $continue = false;
                }
                else
                {
                    $subtractIncVAT["inctax"] = $voucherPrice["inctax"];
                    $currency->setValue( $voucherPrice["inctax"] );
                    $t->set_var( "voucher_price_inc_vat", $locale->format( $currency ) );

                    $subtractExTax["extax"] = $voucherPrice["extax"];
                    $currency->setValue( $voucherPrice["extax"] );
                    $t->set_var( "voucher_price_ex_vat", $locale->format( $currency ) );
                }

                $voucherSession[$voucherID] = $subtractIncVAT["inctax"];
                $t->set_var( "number", $i );

                $t->set_var( "voucher_key", $voucher->keyNumber() );
                $t->set_var( "pay_with_voucher", "true" );

                $t->parse( "voucher_item", "voucher_item_tpl", true );

                $total["extax"] -= $subtractExTax["extax"];
                $total["inctax"] -= $subtractIncVAT["inctax"];

                $i++;
            }
        }
        $t->parse( "remove_voucher", "remove_voucher_tpl" );
    }
    else
        $t->set_var( "remove_voucher", "" );

    if ( isset( $voucherSession ) && is_array ( $voucherSession ) )
    {
        $t->parse( "vouchers", "vouchers_tpl" );
        $session->setArray( "PayedWith", $voucherSession );
    }

    $currency->setValue( $total["subinctax"] );
    $t->set_var( "subtotal_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["subextax"] );
    $t->set_var( "subtotal_ex_tax", $locale->format( $currency ) );

    $currency->setValue( $total["inctax"] );
    $t->set_var( "total_inc_tax", $locale->format( $currency ) );


/////////////////////////////////////////////////////////////////////////////////////
// kracker(2005-20-05): tax_percentage : first change
// orig
//	$t->set_var( "tax_percentage", round( (($total["tax"])/$total["subextax"])*100 , 2) );

// static display only fix (that's what this wrong .... no)
//      $t->set_var( "tax_percentage", round(7.00, 2) );

// the dynamic user : ez region : vat fix ....
//      $t->set_var( "tax_percentage", round(7.00, 2) );

// Description: 
// take user, 
// get it's main address ( how do you tell if its the shipping address? can't untill after this view currently )
// consider an option to add to user registration to specify shipping address preference durring userwithaddress edit
// that option used to exist! client asked for it's removal ... grrr
// this would screw upp everything, andcould provide free shipping based on first address in system

// so we can't really know where it's going attthis point or calc the proper shipping, 
// shipping tax rate, tax rate, total tax, product total w/o / w/tax ...

// major workflow / user address managment bug (pre-existing)

//
/////////////////////////////////////////////////////////////////////////////////////
 $t->set_var( "tax_percentage", round( ( $total["tax"]) / ($total["subextax"] + $total["shipextax"]) * 100 , 2 ) );

   //     $t->set_var( "tax_percentage", round(7.00, 2) );

/////////////////////////////////////////////////////////////////////////////////////
	
	$t->set_var( "tax_total", "" );
			
	if ( !$ShowIncTaxColumn )
	{
	    $currency->setValue( $total["inctax"] );
	    $t->set_var( "total_ex_tax", $locale->format( $currency ) );

	    if ( $total["tax"]>0 )
	      $t->parse( "tax_total", "tax_total_tpl" );
	    else
	      $t->set_var( "tax_total", "" );
	}
	else
	{
    $currency->setValue( $total["extax"] );
    $t->set_var( "total_ex_tax", $locale->format( $currency ) );
	    $t->set_var( "tax_total", "" );
	}

    $currency->setValue( $total["shipinctax"] );
    $t->set_var( "shipping_inc_tax", $locale->format( $currency ) );

    $currency->setValue( $total["tax"] );
    $t->set_var( "total_cart_tax", $locale->format( $currency ) );
	
	$t->set_var( "shipping_inc_hide_tax",$total["shipinctax"] );
    $currency->setValue( $total["shipextax"] );
    $t->set_var( "shipping_ex_tax", $locale->format( $currency ) );
	$t->set_var( "shipping_ex_hide_tax", $total["shipextax"] );

    if ( $ShowSavingsColumn == false )
    {
        $ColSpanSizeTotals--;
    }

    $SubTotalsColumns = $ColSpanSizeTotals;

    if ( $ShowExTaxColumn == true )
    {
        if ( $ShowExTaxTotal == true or $ShowIncTaxColumn == false )
        {
			$GLOBALS["shcost"] = $total["shipextax"];
            $t->parse( "total_ex_tax_item", "total_ex_tax_item_tpl" );
            $t->parse( "subtotal_ex_tax_item", "subtotal_ex_tax_item_tpl" );
            $t->parse( "shipping_ex_tax_item", "shipping_ex_tax_item_tpl" );
        }
        else
        {
			$GLOBALS["shcost"] = $total["shipinctax"];
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

    if ( $ShowIncTaxColumn == true  )
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

    if( $vat == true )
    {
        $currency->setValue( $total["tax"] );
        $t->set_var( "tax", $locale->format( $currency ) );

        foreach( $tax as $taxGroup )
        {
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
		 $t->set_var( "tax_specification", "" );
}
else
{
    $t->parse( "empty_cart", "empty_cart_tpl" );
    $t->parse( "cart_checkout", "cart_checkout_tpl" );
    $t->set_var( "cart_checkout_button", "" );
    $t->set_var( "cart_item_list", "" );
    $t->set_var( "full_cart", "" );
    $t->set_var( "tax_specification", "" );
    $t->set_var( "tax_item", "" );
}



$can_checkout = true;

$user =& eZUser::currentUser();

$t->set_var( "user_id", $user->id() );
// print out the addresses

if ( $cart->personID() == 0 && $cart->companyID() == 0 )
{
    $t->set_var( "customer_first_name", $user->firstName() );
    $t->set_var( "customer_last_name", $user->lastName() );

    $addressArray = $user->addresses();
}
else
{
    if ( $cart->personID() > 0 )
    {
        $customer = new eZPerson( $cart->personID() );
        $t->set_var( "customer_first_name", $customer->firstName() );
        $t->set_var( "customer_last_name", $customer->lastName() );
    }
    else
    {
        $customer = new eZCompany( $cart->companyID() );
        $t->set_var( "customer_first_name", $customer->name() );
        $t->set_var( "customer_last_name", "" );
    }

    $addressArray = $customer->addresses();
}

if ( $checkups == 1 )
{
    $t->set_var("shipchange","onchange=\"return shipaddchange(this.form)\"");
}
else
{
    $t->set_var("shipchange","");
}

$t->parse( "shipping_select", "shipping_select_tpl", true );

foreach ( $addressArray as $address )
{
    $t->set_var( "address_id", $address->id() );
    $t->set_var( "street1", $address->street1() );

	if ( $address->street2() )
        $t->set_var( "street2", $address->street2().",&nbsp;" );
	else
        $t->set_var( "street2", "" );

    $t->set_var( "zip", $address->zip() );
    $t->set_var( "place", $address->place() );

    $country = $address->country();

    if ( $country )
    {
        $country = ", " . $country->name();
    }

    if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
        $t->set_var( "country", $country );
    else
        $t->set_var( "country", "" );
   /*!
        kracker@gci.net: recoded for eZRegion on 7.30.2001-19:09
        Note: Not tested.
    */

    $region = $address->region();

    if ( $region )
    {
        $region = ", " . $region->name();
    }

    if ( $ini->read_var( "eZUserMain", "SelectRegion" ) == "enabled" )
        $t->set_var( "region", $region );
    else
        $t->set_var( "region", "" );

    unset( $mainAddress );
    $t->set_var( "is_selected", "" );
	
    if ( isset( $BillingAddressID ) && $BillingAddressID == "" )
	{
	    $mainAddress = $addressList[0];

	  //$mainAddress = $address->mainAddress( $user );
	$BillingAddressID = $mainAddress->id();
	}

/*	
    if ( get_class( $mainAddress ) == "ezaddress" )
    {
        if ( $mainAddress->id() == $address->id() )
        {
            $t->set_var( "is_selected", "selected" );
        }
    }
*/

    if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
        {
			if ( isset( $BillingAddressID ) && (int)$BillingAddressID == $address->id() )
				$t->set_var( "billing_selected", "selected" );
			else 
				$t->set_var( "billing_selected", "" );
        $t->parse( "billing_option", "billing_option_tpl", true );
		}		
    else
        $t->set_var( "billing_option", "" );

    $t->set_var("type_selected","");

	if ($shipaddID == ""){
	  if($addressList){
        if(isset($addressList[1]) &&$addressList[1]){
	      $shipaddID = $addressList[1]->ID();
	    }else{
	      $shipaddID = $addressList[0]->ID();
	    }	    
	  }
	  //$shipaddID = $mainAddress->id();
	}
	
	if($shipaddID==$address->id())
	{
		$t->set_var("lastshipid","$shipaddID");
		$t->set_var("type_selected","selected");
	}

    $t->parse( "shipping_address", "shipping_address_tpl", true );
}


if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
    $t->parse( "billing_address", "billing_address_tpl", true );
else
    $t->set_var( "billing_address", "" );


// show the checkout types

if ( $total["inctax"] )
{
    $checkout = new eZCheckout();

    $instance =& $checkout->instance();

    $paymentMethods =& $instance->paymentMethods( $useVoucher );

    foreach ( $paymentMethods as $paymentMethod )
    {
        $t->set_var( "payment_method_id", $paymentMethod["ID"] );
        $t->set_var( "payment_method_text", $paymentMethod["Text"] );

        $t->parse( "payment_method", "payment_method_tpl", true );
    }
    $t->parse( "show_payment", "show_payment_tpl" );
}
else
{
    $t->set_var( "show_payment", "" );
}
$t->set_var( "sendorder_item", "" );

// Print the total sum.
if ( isset( $totalVoucher ) )
    $total["inctax"] = $total["inctax"] - $totalVoucher["inctax"];
else
    $total["inctax"] = $total["inctax"];

$currency->setValue( $total["inctax"] );
$t->set_var( "cart_sum", $locale->format( $currency ) );
$t->set_var( "cart_colspan", 1 + $i );

if ( isset( $sum ) && $sum <= 0 )
        $payment = false;
else
$payment = true;

// the total cost of the payment

$t->set_var( "total_cost_value", round($total["inctax"],2) );

//$t->set_var( "total_vat_value", $totalVAT );
$t->set_var( "total_vat_value", round($total["tax"],2) );
$t->set_var( "shipping_cost_value", round((float)$total["shipinctax"],2) );
$t->set_var( "shipping_vat_value", round($total["shiptax"],2) );

// A check should be done in the code above for qty.
$can_checkout = true;

if ( $can_checkout )
    $t->parse( "sendorder_item", "sendorder_item_tpl" );

$t->pparse( "output", "checkout_page_tpl" );

?>