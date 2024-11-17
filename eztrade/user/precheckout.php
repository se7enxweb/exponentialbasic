<?php
//
// $Id: precheckout.php 9577 2002-06-03 08:11:11Z bf $
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


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );


$ini =& INIFile::globalINI();
$ForceSSL = $ini->read_var( "eZTradeMain", "ForceSSL" );

$index = $ini->Index;


// set SSL mode and redirect if not already in SSL mode.
if ( ( $ForceSSL == "enabled" ) )
{
    $session->setVariable( "SSLMode", "enabled" );

    // force SSL if supposed to
//    if ( $SERVER_PORT != '443' )
    {
        eZHTTPTool::header("Location: https://" . $HTTP_HOST . $index . "/trade/checkout/" );
        exit();
    }
}
elseif ( $ForceSSL == "disabled" )
{
    $session->setVariable( "SSLMode", "disabled" );

    eZHTTPTool::header("Location: http://" . $HTTP_HOST . $index . "/trade/checkout/" );
    exit();
}
elseif ( $ForceSSL == "choose" )
{
    $session->setVariable( "SSLMode", "choose" );

    if( isSet ( $WithSSL ) )
    {
        eZHTTPTool::header( "Location: https://" . $HTTP_HOST . $index . "/trade/checkout" );
        exit();

    }

    if( isSet ( $WithOutSSL ) )
    {
        eZHTTPTool::header( "Location: http://" . $HTTP_HOST . $index . "/trade/checkout" );
        exit();

    }

    $Language = $ini->read_var( "eZTradeMain", "Language" );


    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                         "eztrade/user/intl/", $Language, "precheckout.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "precheckout_tpl" => "precheckout.tpl"
        ) );

    $t->set_var( "host", $HTTP_HOST );
    $t->set_var( "php_session", "" );

    $t->pparse( "output", "precheckout_tpl" );
}
?>
