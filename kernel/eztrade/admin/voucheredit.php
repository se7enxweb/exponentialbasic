<?php
// 
// $Id: voucheredit.php 7465 2001-09-26 07:09:33Z ce $
//
// Created on: <20-Dec-2000 18:24:06 bf>
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

// include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /trade/voucherlist/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezlocale.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$move_item = true;

// include_once( "eztrade/classes/ezvoucher.php" );

if ( ( $Action == "Update" ) || ( isset ( $Update ) ) )
{
    $voucher = new eZVoucher( $VoucherID );

    setType( $Price, "integer" );
    
    $voucher->setPrice( $Price );

    if ( $Available == "on" )
        $voucher->setAvailable( true );
    else
        $voucher->setAvailable( false );

    $voucher->store();
}

if( isset( $Ok ) )
{
    eZHTTPTool::header( "Location: /trade/voucherlist/" );
    exit();
}

if ( $Action == "Delete" )
{
    $voucher = new eZVoucher( $VoucherID );

    $voucher->delete();
    
    eZHTTPTool::header( "Location: /trade/voucherlist/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "voucheredit.php" );

$t->setAllStrings();

$t->set_file( array( "voucher_edit_tpl" => "voucheredit.tpl" ) );

$t->set_block( "voucher_edit_tpl", "used_list_tpl", "used_list" );
$t->set_block( "used_list_tpl", "used_item_tpl", "used_item" );

$locale = new eZLocale( $Language );
$currency = new eZCurrency();

$t->set_var( "action_value", "Insert" );

// edit
if ( $Action == "Edit" )
{
    $voucher = new eZVoucher( $VoucherID );

    $currency->setValue( $voucher->price() ); 
    $t->set_var( "voucher_price", $voucher->price() );
    $t->set_var( "voucher_created", $locale->format( $voucher->created() ) );
    $t->set_var( "action_value", "Update" );
    $t->set_var( "voucher_id", $voucher->id() );

    $usedList =& $voucher->usedList();

    $count = count ( $usedList );

    $voucherInfo =& $voucher->information();

    if ( $voucherInfo->mailMethod() == 1 )
    {
        $mail =& $voucherInfo->online();
        $t->set_var( "sent_email", $mail->url() );
        $t->set_var( "sent_description", $voucherInfo->description() );
    }
    else if ( $voucherInfo->mailMethod() == 2 )
    {
        
    }

    if ( $voucher->isAvailable() )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );

    $i=0;
    foreach ( $usedList as $used )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $currency->setValue( $used->price() );
        $t->set_var( "used_price", $locale->format( $currency ) );
        $t->set_var( "used_used", $locale->format( $used->used() ) );

        $localUser =& $used->user();

        $t->set_var( "user_name", $localUser->firstName() . " " . $localUser->lastName() );
        $t->set_var( "user_id", $localUser->id() );

        $order =& $used->order();

        $t->set_var( "voucher_order_id", $order->id() );
        $t->parse( "used_item", "used_item_tpl", true );
        $i++;
    }

    if ( count( $usedList ) > 0 )
    {
        $t->parse( "used_list", "used_list_tpl", true );
    }
    else
    {
        $t->set_var( "used_list", "" );
    }
    
}


$t->pparse( "output", "voucher_edit_tpl" );

?>
