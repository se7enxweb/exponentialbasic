<?php
//
// $Id: templateedit.php 9330 2002-03-04 12:56:24Z ce $
//
// Created on: <24-Sep-2001 15:16:31 bf>
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

// include_once( "classes/ezfile.php" );

if ( isset( $Store ) )
{
    $fp = eZFile::fopen( $FileName, "w+");
    $Contents =& str_replace ("\r", "", $Contents );
    fwrite ( $fp, $Contents );
    fclose( $fp );
    $realPath = $FileName;
}
else
{
    $realPath = str_replace( "-", "/", $filePath );
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "templateedit.php" );
$t->setAllStrings();

$t->set_file( "site_config_tpl", "templateedit.tpl" );

$lines = eZFile::file( $realPath );
$contents = "";
foreach ( $lines as $line )
{
    $contents .= $line;
}

$t->set_var( "file_contents", htmlspecialchars( $contents ) );
$t->set_var( "file_name", $realPath );

$t->pparse( "output", "site_config_tpl" );

?>