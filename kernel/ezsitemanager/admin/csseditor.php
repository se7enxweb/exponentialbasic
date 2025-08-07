<?php
//
// $Id: siteconfig.php,v 1.4.2.2 2002/03/04 12:56:24 ce Exp $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezfile.php" );


if ( isset( $Store ) )
{
    if ( file_exists( "design/standard/style.css" ) )
        $fp = eZPBFile::fopen( "design/standard/style.css", "w+");
    else
        $fp = eZPBFile::fopen( "design/ecommerce/style.css", "w+");

    $Contents =& str_replace ("\r", "", $Contents );
    fwrite ( $fp, $Contents );
    fclose( $fp );
}


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->variable( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "csseditor.php" );
$t->setAllStrings();

$t->set_file( "site_config_tpl", "csseditor.tpl" );

if ( file_exists( "design/standard/style.css" ) )
    $lines = eZPBFile::file( "design/standard/style.css" );
else
    $lines = eZPBFile::file( filename: "design/ecommerce/style.css" );

$contents = "";
foreach ( $lines as $line )
{
    $contents .= stripslashes($line);
}

$t->set_var( "file_contents", htmlspecialchars( $contents ) );
$t->set_var( "file_name", isset( $fileName ) ? $fileName : '' );

$t->pparse( "output", "site_config_tpl" );

?>