<?php
//
// $Id: payment.php 9407 2002-04-10 11:49:02Z br $
//
// Created on: <02-Feb-2001 16:31:53 bf>
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

unset( $PaymentSuccess );
include_once( "ezuser/classes/ezuser.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "eztrade/classes/ezcart.php" );
include_once( "eztrade/classes/ezcheckout.php" );

$ini =& INIFile::globalINI();
$indexFile = $ini->Index;

$session =& eZSession::globalSession();

// fetch the cart
$cart = new eZCart();
$cart = $cart->getBySession( $session, "Cart" );

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

$checkout = new eZCheckout();
$instance =& $checkout->instance();

$paymentMethod = $session->arrayValue( "PaymentMethod" );
$paymentMethod = $paymentMethod[0];



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
    // set the confirmation 
    eZHTTPTool::header( "Location: http://" . $HTTP_HOST . $indexFile . "/trade/confirmation/" );
    exit();
}
?>
