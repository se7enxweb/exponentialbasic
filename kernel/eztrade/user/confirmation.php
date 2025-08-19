<?php
//
// $Id: confirmation.php 9407 2002-04-10 11:49:02Z br $
//
// <Bj�rn Reiten> <br@ez.no>
// Created on: <20-Mar-2002 14:11:34 br>
//
// This source file is part of Exponential Basic, publishing software.
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


// include_once( "classes/INIFile.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "eztrade/classes/ezorderconfirmation.php" );

$session =& eZSession::globalSession();
$orderID = $session->variable( "OrderID" );

$ini =& eZINI::instance( 'site.ini' );
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$orderConfirmation = $session->variable( "OrderConfirmation" ); 


if ( is_Numeric( $orderID ) && $orderConfirmation == $orderID )
{
    $confirmation = new eZOrderConfirmation( $orderID );
    
    $result = $confirmation->confirmOrder( $session );

    // redirect to the confirmation site if the order is sent
    $user =& eZUser::currentUser();
    if ( $user && $result )
    {
        if ( $result == true )
        {
            $session->setVariable( "OrderConfirmation", "" );
            eZHTTPTool::header( "Location: https://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/ordersendt/$orderID/" ); 
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: http://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/checkout/" );
            exit();
        }
    }
    else
    {	//ends up here!
        eZHTTPTool::header( "Location: https://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/checkout/" );
        exit();
    }
}
else if ( is_Numeric( $orderID ) )
{
    eZHTTPTool::header( "Location: https://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/ordersendt/$orderID/" );
    exit();
}
else
{
    eZHTTPTool::header( "Location: http://" . $HTTP_HOST . $wwwDir . $indexFile . "/trade/checkout/" );
    exit();
}

?>
