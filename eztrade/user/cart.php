<?php
// 
// $Id: cart.php 9407 2002-04-10 11:49:02Z br $
//
// Created on: <27-Sep-2000 11:57:49 bf>
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

// common includes
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

// specific includes
include_once( "classes/ezcurrency.php" );
include_once( "eztrade/classes/ezcart.php" );

// Load settings
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowNamedQuantity = $ini->read_var( "eZTradeMain", "ShowNamedQuantity" ) == "true";
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true" ? true : false;
$RequireQuantity = $ini->read_var( "eZTradeMain", "RequireQuantity" ) == "true";
$ShowOptionQuantity = $ini->read_var( "eZTradeMain", "ShowOptionQuantity" ) == "true";
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$ShowExTaxColumn = $ini->read_var( "eZTradeMain", "ShowExTaxColumn" ) == "enabled" ? true : false;
$ShowIncTaxColumn = $ini->read_var( "eZTradeMain", "ShowIncTaxColumn" ) == "enabled" ? true : false;
$ShowExTaxTotal = $ini->read_var( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
$ColSpanSizeTotals = $ini->read_var( "eZTradeMain", "ColSpanSizeTotals" );

if ( isset( $ShopMore ) ) 
{
    eZHTTPTool::header( "Location: /trade/productlist/1" );
    exit();
}

// These are the common objects regardless of Action
$session =& eZSession::globalSession();


// Set some variables to defaults.
$ShowCart = false;
$ShowSavingsColumn = false;

if ( isset( $DeleteSelected ) )
{
    if ( count( $CartSelectArray ) > 0 )
    foreach ( $CartSelectArray as $cartID )
    {
        $cartItem = new eZCartItem( $cartID );
        $optionValues =& $cartItem->optionValues();

        foreach( $optionValues as $optionValue )
        {
            $optionValue->delete();
        }

        $cartItem->delete();
    }
    
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

if ( ( $Action == "Refresh" ) || isSet( $DoCheckOut ) )
{
    $i = 0;
    $delete = false;
    if ( count( $CartIDArray ) > 0 )
    foreach ( $CartIDArray as $cartID )
    {
        if ( $CartCountArray[$i] < 1 )
        {
            $cartItem = new eZCartItem( $cartID );
            $optionValues =& $cartItem->optionValues();
            
            foreach( $optionValues as $optionValue )
            {
                $optionValue->delete();
            }
            
            $cartItem->delete();
        }
        else
        {
            $cartItem = new eZCartItem( $cartID );
            $product =& $cartItem->product();

            $optionValues =& $cartItem->optionValues();
            $j = 0;

            // First we track the maximum number of items available in stock.
            $maxInStock = false;
            
            foreach( $optionValues as $optionValue )
            {
                $value = $optionValue->optionValue();
                
                $totalQuantity = $value->totalQuantity();
                
                if ( $totalQuantity != false and
                    $totalQuantity < $CartCountArray[$i] )
                {
                    if ( $maxInStock == false or $totalQuantity < $maxInStock )
                    {
                        $maxInStock = $totalQuantity;
                    }
                }
            }
            
            $totalQuantity = $product->totalQuantity();
            
            if ( $totalQuantity != false and
                $totalQuantity < $CartCountArray[$i] )
            {
                if ( $maxInStock == false or $totalQuantity < $maxInStock )
                {
                    $maxInStock = $totalQuantity;
                }
            }

            // Next step is to actually set the info.
            foreach( $optionValues as $optionValue )
            {
                $value = $optionValue->optionValue();

                if ( ( $CartCountArray[$i] > $maxInStock ) and ( $value->totalQuantity() != false ) and ( $maxInStock != false ) )
                {
                    $optionValue->setCount( $maxInStock );
                }
                else
                {
                    $optionValue->setCount( $CartCountArray[$i] );
                }

                $optionValue->store();
            }

            if ( ( $CartCountArray[$i] > $maxInStock ) and ( $product->totalQuantity() != false ) and ( $maxInStock != false ) )
            {
                $cartItem->setCount( $maxInStock );
            }
            else
            {
                $cartItem->setCount( $CartCountArray[$i] );
            }

            $cartItem->store();
        }
        $i++;
    }
}

// checkout
if ( isSet( $DoCheckOut ) )
{
    eZHTTPTool::header( "Location: /trade/customerlogin/" );
    exit();
}

$cart = new eZCart();
$cart = $cart->getBySession( $session );

$user =& eZUser::currentUser();

if ( !$cart )
{
    $cart = new eZCart();
    $cart->setSession( $session );
    
    $cart->store();
}

$items = $cart->items( );


if ( $Action == "AddToBasket" )
{
    $product = new eZProduct();
    if ( !$product->get( $ProductID ) )
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }

    // check if a product like this is already in the basket.
    // if so-> add the count value.
    $Quantity = $product->totalQuantity();
    if ( $product->hasQuantity( $RequireQuantity ) )
    {
        $productAddedToBasket = false;
        {
            // fetch the cart items
            $items = $cart->items( );

            foreach ( $items as $item )
            {
                $productItem =  $item->product();
                // the same product
                if ( ( $ProductID == $productItem->id() ) && ( $productAddedToBasket == false ) )
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
                            $productAddedToBasket = true;
                        }
                    }
                    else
                    { // product without options
                        if ( isset( $OptionValueArray ) && count( $OptionValueArray ) == 0 )
                        {
                            $item->setCount( $item->count() + 1 );
                            $item->store();
                            $productAddedToBasket = true;
                        }
                    }
                }
            }
        }

        if ( $productAddedToBasket == false )
        {
            $can_add = true;
            if ( !$product->hasQuantity() )
                $can_add = false;

            if ( isset( $OptionValueArray ) && count( $OptionValueArray ) > 0 )
            {
                foreach ( $OptionValueArray as $value )
                {
                    $optionValue = new eZOptionValue( $value );
                    if ( !$optionValue->hasQuantity( $RequireQuantity ) )
                    {
                        $can_add = false;
                    }
                }
            }
            if ( $can_add )
            {
                $cartItem = new eZCartItem();

                $cartItem->setProduct( $product );
                $cartItem->setCart( $cart );

                $voucherInformationID = $session->variable( "VoucherInformationID" );
                $session->setVariable( "VoucherInformationID", 0 );
                $cartItem->setVoucherInformation( $voucherInformationID );

                $cartItem->store();

                if ( isset( $OptionValueArray ) && count( $OptionValueArray ) > 0 )
                {
                    $i = 0;
                    foreach ( $OptionValueArray as $value )
                    {
                        $option = new eZOption( $OptionIDArray[$i] );
                        $optionValue = new eZOptionValue( $value );
                        if ( $optionValue->hasQuantity( $RequireQuantity ) )
                        {
                            $cartOption = new eZCartOptionValue();
                            $cartOption->setCartItem( $cartItem );
                            $cartOption->setOption( $option );
                            $cartOption->setRemoteID( $optionValue->remoteID() );
                            $cartOption->setOptionValue( $optionValue );

                            $cartOption->store();
                        }
                        $i++;
                    }
                }
            }
        }
    }
    eZHTTPTool::header( "Location: /trade/cart/" );
    exit();
}

// Load the template

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "cart.php" );

$t->set_file( "cart_page_tpl", "cart.tpl" );

$t->set_block( "cart_page_tpl", "empty_cart_tpl", "empty_cart" );

$t->set_block( "cart_page_tpl", "full_cart_tpl", "full_cart" );
$t->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );
$t->set_block( "cart_item_list_tpl", "header_savings_item_tpl", "header_savings_item" );
$t->set_block( "cart_item_list_tpl", "header_inc_tax_item_tpl", "header_inc_tax_item" );
$t->set_block( "cart_item_list_tpl", "header_ex_tax_item_tpl", "header_ex_tax_item" );

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

$t->set_block( "cart_page_tpl", "cart_checkout_tpl", "cart_checkout" );
$t->set_block( "cart_checkout_tpl", "cart_checkout_button_tpl", "cart_checkout_button" );

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
$i = 0;

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

    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_id", $product->id() );
    $t->set_var( "product_number", $product->productNumber() );
    $t->set_var( "product_price", $item->localePrice( false, true, $PricesIncludeVAT ) );

    $currency = new eZCurrency();
    $productPrice = $item->localePrice( false, true, $PricesIncludeVAT, false );
    $currency->setValue( $productPrice );
    $t->set_var( "product_price", $item->localePrice( false, true, $PricesIncludeVAT, false ) );

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
        
        $option =& $optionValue->option();
        $value =& $optionValue->optionValue();
        $value_quantity = $value->totalQuantity();
        $descriptions = $value->descriptions();

        $t->set_var( "option_id", $option->id() );
        $t->set_var( "option_name", $option->name() );
        $t->set_var( "option_value", $descriptions[0] );
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

if ( $ShowCart == true )
{
    
    $cart->cartTotals( $tax, $total );

    $locale = new eZLocale( $Language );
    $currency = new eZCurrency();
    
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

    foreach( $tax as $taxGroup )
    {
        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
        $j++;  
        $currency->setValue( $taxGroup["basis"] );    
        $t->set_var( "sub_tax_basis", $locale->format( $currency ) );

        $currency->setValue( $taxGroup["tax"] );    
        $t->set_var( "sub_tax", $locale->format( $currency ) );

        $t->set_var( "sub_tax_percentage", $taxGroup["percentage"] );
        $t->parse( "tax_item", "tax_item_tpl", true );
    }

    $t->parse( "tax_specification", "tax_specification_tpl" );
    $t->parse( "cart_checkout_button", "cart_checkout_button_tpl" );    
    $t->parse( "cart_checkout", "cart_checkout_tpl" );    
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

$t->pparse( "output", "cart_page_tpl" );

?>