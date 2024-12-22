<?php
// 
// $Id: voucherinformation.php 8119 2001-10-31 11:19:09Z ce $
//
// Created on: <06-Aug-2001 13:02:18 ce>
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
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$locale = new eZLocale( $Language );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezvoucherinformation.php" );
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductpricerange.php" );
// include_once( "ezsession/classes/ezsession.php" );

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "voucherinformation.php" );

$t->setAllStrings();

$t->set_file( "voucher_tpl", "voucherinformation.tpl" );

$t->set_block( "voucher_tpl", "email_tpl", "email" );
$t->set_block( "voucher_tpl", "smail_tpl", "smail" );

setType( $PriceRange, "integer" );

$t->set_var( "email_var", "" );
$t->set_var( "to_name", "" );
$t->set_var( "from_name", "" );
$t->set_var( "smail_var", "" );
$t->set_var( "email_text", "" );
$t->set_var( "smail_text", "" );
$t->set_var( "next", "" );
$t->set_var( "name_value", "" );
$t->set_var( "street1_value", "" );
$t->set_var( "street2_value", "" );
$t->set_var( "zip_value", "" );
$t->set_var( "place_value", "" );
$t->set_var( "country_name", "" );
$t->set_var( "smail", "" );
$t->set_var( "email", "" );
$t->set_var( "from_email", "" );

$user =& eZUser::currentUser();

$product = new eZProduct( $ProductID );

if ( $product && isset( $OK ) )
{
    $voucherInfo = new eZVoucherInformation();
            
    if ( $Mail == 1 )
    {
        $online = new eZOnline();
        $online->setUrl( $Email );
        $online->store();
        $voucherInfo->setEmail( $online );
    }
    else if ( $Mail == 2 )
    {
        $toAddress = new eZAddress();
        $toAddress->setName( $ToName );
        $toAddress->setStreet1( $ToStreet1 );
        $toAddress->setStreet2( $ToStreet2 );
        $toAddress->setZip( $ToZip );
        $toAddress->setPlace( $ToPlace );
        $toAddress->store();
        $voucherInfo->setToAddress( $toAddress );

        $fromAddress = new eZAddress();
        $fromAddress->setName( $FromName );
        $fromAddress->setStreet1( $FromStreet1 );
        $fromAddress->setStreet2( $FromStreet2 );
        $fromAddress->setZip( $FromZip );
        $fromAddress->setPlace( $FromPlace );
        $fromAddress->sfromre();
        $voucherInfo->setFromAddress( $fromAddress );
    }

    $online = new eZOnline();
    $online->setUrl( $FromEmail );
    $online->store();
    $voucherInfo->setFromEmail( $online );
    $voucherInfo->setMailMethod( $Mail );
    $voucherInfo->setFromName( $FromName );
    $voucherInfo->setFromName( $FromName );
    $voucherInfo->setToName( $ToName );
    $voucherInfo->setProduct( $product );
    
    $voucherInfo->setDescription( $Description );

    if ( $Price == 0 )
    {
        $priceRange =& $product->priceRange();
        $voucherInfo->setPrice( $priceRange->min() );
    }
    else
        $voucherInfo->setPrice( $Price );


    $voucherInfo->store();

    $voucherInformationID = $voucherInfo->id();

    $session->setVariable( "VoucherInformationID", $voucherInformationID );

    if ( isSet ( $OK ) && $voucherInformationID )
    {
        eZHTTPTool::header( "Location: /trade/cart/add/$ProductID/" );
        exit();
    }
}
else if ( $product )
{
    $range = $product->priceRange();
    $error = false;
    if ( ( $range->min() != 0 ) && ( $range->min() > $PriceRange ) )
    {
        $error = true;
    }
    if ( ( $range->max() != 0 ) && ( $range->max() < $PriceRange ) )
    {
        $error = true;
    }

    if ( $error )
    {
        eZHTTPTool::header( "Location: /trade/productview/$ProductID/" );
        exit();
    }

    if ( $MailMethod == 1 )
    {
        $t->set_var( "smail", "" );
        $t->parse( "email", "email_tpl" );
    }
    else if ( $MailMethod == 2 )
    {
        $t->set_var( "email", "" );
        $t->parse( "smail", "smail_tpl" );
    }

    $t->set_var( "mail_method", $MailMethod );
    $t->set_var( "product_name", $product->name() );
    $t->set_var( "product_id", $product->id() );

    $currency = new eZCurrency();
    
    if ( $PriceRange == 0 )
    {
        $priceRange =& $product->priceRange();
        $currency->setValue( $priceRange->min() );
        $t->set_var( "product_price", $locale->format( $currency ) );
        $t->set_var( "price_range", $priceRange->min() );
    }
    else
    {
        $currency->setValue( $PriceRange );
        $t->set_var( "price_range", $PriceRange );
        $t->set_var( "product_price", $locale->format( $currency ) );
    }
}


$t->pparse( "output", "voucher_tpl" );

?>