<?php
// 
// $Id: seti.php,v 0.1 2004/01/10 
//
// Created on: <10-Jan-2004 Bob Sims>
//
// This source file integrates Stone Edge Order Manager
// with Exponential Basic v2.2 publishing software.
//
// Copyright (C) 2004 Bob Sims.  All rights reserved.

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

$ini =& eZINI::instance( 'site.ini' );
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$UserName = $ini->variable( "eZTradeMain", "SetiUser" );
$Password = $ini->variable( "eZTradeMain", "SetiPassword" );
$Code = $ini->variable( "eZTradeMain", "Code" );
$AdminSite = $ini->variable( "site", "AdminSiteURL" );

if( ($_SERVER['HTTP_PORT'] != 443) || ($_SERVER['HTTPS'] != 'on') ) 
{ 
// page is not secure, redirect
eZHTTPTool::header( "Location: https://" . $AdminSite . "/trade/seti/" );
} 


?>