<?php
//
// $Id: voucher.php 9407 2002-04-10 11:49:02Z br $
//
// Created on: <08-Feb-2001 14:11:48 ce>
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
// include_once( "classes/ezcctool.php" );
// include_once( "eztrade/classes/ezvoucher.php" );

if ( isset ( $Back ) )
{
    eZHTTPTool::header( "Location: /trade/checkout/" );
    exit();
}


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$session->setVariable( "PayWithVocuher", "" );

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "voucher.php" );

$t->set_file( "voucher_tpl", "voucher.tpl" );

$t->setAllStrings();

$t->set_block( "voucher_tpl", "error_tpl", "error" );
$t->set_var( "error", "" );

if ( $Action == "Verify" )
{
    $voucher = eZVoucher::getFromKeyNumber( $KeyNumber );
    if ( is_a ( $voucher, "eZVoucher" ) )
    {
        $array[] = $voucher->id();


        $append = $session->arrayValue( "PayWithVoucher" );


        $array = array_merge( $array, $append );

        $session->setArray( "PayWithVoucher", $array );
        $session->arrayValue( "PayWithVoucher" );

        eZHTTPTool::header( "Location: /trade/checkout/" );
        exit();

    }

    $t->parse( "error", "error_tpl" );
    $PaymentSuccess = false;
}


// $ChargeTotal is the value to charge the customer with

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );

$t->pparse( "output", "voucher_tpl" );
?>