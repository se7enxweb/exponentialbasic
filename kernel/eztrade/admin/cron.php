<?php
// 
// $Id: cron.php 6378 2001-08-03 14:08:19Z jhe $
//
// Created on: <03-Aug-2001 11:33:51 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

// include_once( "classes/ezdate.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezdb.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "eztrade/classes/ezorder.php" );

// include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

$expireTime = $ini->read_var( "eZTradeMain", "EmailBeforeExpire" );

$locale = new eZLocale();

if ( $expireTime > -1 )
{
    $date = new eZDate();
    $dateTimestampe = $date->timestamp( true );
    $orders = eZOrder::expiringOrders( $dateTimestampe + ( $expireTime * 86400 ) );
    foreach ( $orders as $orderID )
    {
        $orderItem = new eZOrderItem( $orderID );
        $order = $orderItem->order();
        $user = $order->user();

        $mail = new eZMail();

        // Setup the template for email
        $mailTemplate = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                                        "kernel/eztrade/admin/intl", $Language, "productexpire.php" );
        
        $mailTemplate->set_file( "product_expires_tpl", "productexpire.tpl" );
        $mailTemplate->setAllStrings();

        $product = $orderItem->product();
        $mailTemplate->set_var( "product", $product->name() );

        if ( $order->personID > 0 )
            $customer = new eZPerson( $order->personID() );
        else if ( $order->companyID > 0 )
            $customer = new eZCompany( $order->companyID() );
        else
            $customer = $user;
        $mailTemplate->set_var( "customer", $customer->name() );

        $saleDate = $order->date();
        $mailTemplate->set_var( "sale_date", $locale->format( $saleDate ) );

        $expiryObject = new eZDateTime();
        $expiryObject->setTimeStamp( $orderItem->expiryDate() );
        $mailTemplate->set_var( "expire_date", $locale->format( $expiryObject ) );
                                
        $mailBody = $mailTemplate->parse( "dummy", "product_expires_tpl" );
        $mail->setFrom( $OrderSenderEmail );
        
        $mail->setTo( $user->email() );
        $mail->setSubject( "Product expires" );
        $mail->setBody( $mailBody );
        $mail->send();
    }
}

?>