<?php
// 
// $Id: voucher.php,v 1.4 2001/09/24 10:19:16 ce Exp $
//
// Created on: <08-Feb-2001 14:11:48 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezcctool.php" );
// include_once( "eztrade/classes/ezvoucher.php" );

// include_once( "ezuser/classes/ezuser.php" );

if ( isSet ( $Back ) )
{
    eZHTTPTool::header( "Location: /trade/checkout/" );
    exit();
}


$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );

$session->setVariable( "PayWithVocuher", "" );

$t = new eZTemplate( "kernel/eztrade/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/eztrade/user/intl/", $Language, "corporate_account.php" );

$t->set_file( "voucher_tpl", "corporate_account.tpl" );

$t->setAllStrings();


$user =& eZUser::currentUser();

$accountNumber = $user->accountNumber();

$t->set_var( "account_number", $accountNumber );


$t->set_block( "voucher_tpl", "error_tpl", "error" );
$t->set_var( "error", "" );

if ( $Action == "Verify" )
{
  $user =& eZUser::currentUser();
  $accountNumber = $user->accountNumber();

  /*
    if ( get_class ( $voucher ) == "ezvoucher" )
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
    $PaymentSuccess = "false";
  */

  // bypass the payment.
  $PaymentSuccess = "true";

}


// $ChargeTotal is the value to charge the customer with

$t->set_var( "order_id", $PreOrderID );
$t->set_var( "payment_type", $PaymentType );

$t->pparse( "output", "voucher_tpl" );
?>
