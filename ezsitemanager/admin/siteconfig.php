<?php
//
// $Id: siteconfig.php 9330 2002-03-04 12:56:24Z ce $
//
// Created on: <12-Jul-2001 10:45:55 bf>
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
include_once( "classes/ezfile.php" );


if ( isset( $Store ) )
{
    if ( eZFile::file_exists( "bin/ini/override/site.ini.php" ) )
      	$fp = eZFile::fopen( "bin/ini/override/site.ini.php", "w+");
    elseif ( eZFile::file_exists( "bin/ini/override/site.ini" ) )
      	$fp = eZFile::fopen( "bin/ini/site.ini", "w+");
    elseif ( eZFile::file_exists( "bin/ini/site.ini.php" ) )
      	$fp = eZFile::fopen( "bin/ini/site.ini.php", "w+");
    elseif ( eZFile::file_exists( "bin/ini/site.ini" ) )
      	$fp = eZFile::fopen( "bin/ini/site.ini", "w+");

    $Contents =& str_replace ("\r", "", $Contents );
    fwrite ( $fp, $Contents );
    fclose( $fp );
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/" . "/intl", $Language, "siteconfig.php" );
$t->setAllStrings();

$t->set_file( "site_config_tpl", "siteconfig.tpl" );

if ( eZFile::file_exists( "bin/ini/override/site.ini.php" ) )
    $lines = eZFile::file( "bin/ini/override/site.ini.php" );
elseif ( eZFile::file_exists( "bin/ini/override/site.ini" ) )
    $lines = eZFile::file( "bin/ini/override/site.ini" );
elseif ( eZFile::file_exists( "bin/ini/site.ini.php" ) )
    $lines = eZFile::file( "bin/ini/site.ini.php" );
elseif ( eZFile::file_exists( "bin/ini/site.ini" ) )
    $lines = eZFile::file( "bin/ini/site.ini" );

$contents = "";
foreach ( $lines as $line )
{
    $contents .= $line;
}
$t->set_var( "file_contents", htmlspecialchars( $contents ) );

$t->set_var( "file_name", $fileName );


$t->pparse( "output", "site_config_tpl" );

?>
