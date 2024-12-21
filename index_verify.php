<?php
// 
// $Id: index_verify.php 8914 2002-01-08 09:59:34Z kaid $
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

// Find out, where our files are.
if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_FILENAME, $regs ) )
    $siteDir = $regs[1];
elseif ( ereg( "(.*/)([^\/]+\.php)/?", $PHP_SELF, $regs ) )
    $siteDir = $DOCUMENT_ROOT . $regs[1];
else
	$siteDir = "./";

// Detect os platform
if ( substr( php_uname(), 0, 7) == "Windows" )
    $separator = ";";
else
    $separator = ":";

// Build include path
$includePath = ini_get( "include_path" );
if ( trim( $includePath ) != "" )
    $includePath .= $separator . $siteDir;
else
    $includePath = $siteDir;
ini_set( "include_path", $includePath );

ob_end_clean();

// script to check if the site is alive
// this script will return 42 if the server is alive
// it will return 13 if not

// include_once( "classes/ezdb.php" );

$db =& eZDB::globalDatabase();
$db->query_single( $session_array, "SELECT COUNT( ID ) AS Count FROM eZSession_Session" );
$db->query_single( $user_array, "SELECT COUNT( ID ) AS Count FROM eZUser_User" );
$db->query_single( $stats_array, "SELECT COUNT( ID ) AS Count FROM eZStats_PageView" );

// test results & informational notice
if ( $user_array["Count"] > 0 ) {
    print( "Success: <br /> Code: 42 <br /><br /> Congratulations! Your eZ publish 2 Installation has succesfully connected to the database and these tables: <br /> eZUser_User, eZSession_Session and eZStats_PageView. You can now use the <a href='/'>installation</a>" );
} else {
    print( "Error: <br /> Code: 13 <br /><br /> Your eZ publish 2 Installation was not able to successfully connect to the database or the tables: <br /> eZUser_User, eZSession_Session and eZStats_PageView were missing or contained no data. <br /> Please check your database settings, configuration and <a href='/index_verify.php'>try again</a>." );
}

exit();
?>
