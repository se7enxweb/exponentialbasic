<?php
//
// $Id: siteconfig.php 9330 2002-03-04 12:56:24Z ce $
//
// Created on: <12-Jul-2001 10:45:55 bf>
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
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezfile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZSiteManagerMain", "Language" );
$SitePath = $ini->variable( "site", "SitePath" );

/* Nice Idea, Not Needed because of classes/ezfile's predicate path
   Specificly the sitedir.ini does this work for us (dependancy)
*/

/*
  $ini_file = "$SitePath/override/site.ini";
  $ini_php_file = "$SitePath/override/site.ini.php" ;
*/

$ini_file = "/override/site.ini";
$ini_php_file = "/override/site.ini.php" ;

if ( isset( $Store ) )
{
    if ( file_exists( "settings/override/site.ini.php" ) )
      	$fp = eZPBFile::fopen( "settings/override/site.ini.php", "w+");
    elseif ( file_exists( "settings/override/site.ini" ) )
      	$fp = eZPBFile::fopen( "settings/site.ini", "w+");
    elseif ( file_exists( "settings/site.ini.php" ) )
      	$fp = eZPBFile::fopen( "settings/site.ini.php", "w+");
    elseif ( file_exists( "settings/site.ini" ) )
      	$fp = eZPBFile::fopen( "settings/site.ini", "w+");

    $Contents =& str_replace ("\r", "", $Contents );
    fwrite ( $fp, $Contents );
    fclose( $fp );
}


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->variable( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "siteconfig.php" );
$t->setAllStrings();

$t->set_file( "site_config_tpl", "siteconfig.tpl" );

if ( file_exists( "settings/override/site.ini.php" ) )
{
    $lines = eZPBFile::file( "settings/override/site.ini.php" );
}
elseif ( file_exists( "settings/override/site.ini" ) )
{
        $lines = eZPBFile::file( "settings/override/site.ini" );
}
elseif ( file_exists( "settings/site.ini.php" ) )
{
    $lines = eZPBFile::file( "settings/site.ini.php" );
}
elseif ( file_exists( "settings/site.ini" ) )
{
    $lines = eZPBFile::file( "settings/site.ini" );
}
else
{
  print("<span style='color: red;'>Warning</span>: The Exponential Basic site.ini file could not be found : <span style='color: red;'>$ini_file</span>");

  //  print("<br />");
}

// print("<br />This is the End ...");
$contents = "";
foreach ( $lines as $line )
{
    $contents .= stripslashes($line);
}
$t->set_var( "file_contents", htmlspecialchars( $contents ) );
$t->set_var( "file_name", isset( $fileName ) ? $fileName : false );

$t->pparse( "output", "site_config_tpl" );

?>