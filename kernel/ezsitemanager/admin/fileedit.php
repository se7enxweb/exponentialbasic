<?php
//
// $Id: fileedit.php 9330 2002-03-04 12:56:24Z ce $
//
// Created on: <11-Jul-2001 17:48:44 bf>
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


if ( !eZFile::file_exists( "ezsitemanager/staticfiles/$fileName" ) )
{
     eZHTTPTool::header( "Location: /error/404/" );
     exit();
}

if ( isset( $Store ) )
{
    $fp = eZFile::fopen( "ezsitemanager/staticfiles/$fileName", "w+");
    fwrite ( $fp, $Contents );
    fclose( $fp );
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "fileedit.php" );
$t->setAllStrings();

$t->set_file( "file_edit_tpl", "fileedit.tpl" );

$lines = eZFile::file( "ezsitemanager/staticfiles/$fileName" );
$contents = "";
foreach ( $lines as $line )
{
    $contents .= $line;
}
$t->set_var( "file_contents", htmlspecialchars( $contents ) );

$t->set_var( "file_name", $fileName );


$t->pparse( "output", "file_edit_tpl" );

?>