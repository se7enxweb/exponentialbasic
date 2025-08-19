<?php
// 
// $Id: filelist.php 6539 2001-08-22 08:59:45Z th $
//
// Created on: <11-Jul-2001 15:37:33 bf>
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

if ( isset( $Delete ) )
{
    foreach ( $FileDeleteArray as $file )
    {
        if ( file_exists( "kernel/ezsitemanager/staticfiles/$file" ) )
        {
            eZPBFile::unlink( "kernel/ezsitemanager/staticfiles/$file" );
        }
    }

    foreach ( $ImageDeleteArray as $file )
    {
        if ( file_exists( "kernel/ezsitemanager/staticfiles/images/$file" ) )
        {
            eZPBFile::unlink( "kernel/ezsitemanager/staticfiles/images/$file" );
        }
    }
}

if ( isset( $Upload ) )
{
    // upload file, if supplied
    $file = new eZFile();
    if ( $file->getUploadedFile( "userfile" ) == true )
    {
        $file->copy( "kernel/ezsitemanager/staticfiles/" . $file->name() );
    }

    // upload image, if supplied
    $image = new eZFile();
    
    if ( $image->getUploadedFile( "userimage" ) == true )
    {
        $image->copy( "kernel/ezsitemanager/staticfiles/images/" . $image->name() );
    }
}


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->variable( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "filelist.php" );
$t->setAllStrings();

$t->set_file( "file_list_tpl", "filelist.tpl" );

$t->set_block( "file_list_tpl", "file_tpl", "file" );
$t->set_block( "file_list_tpl", "image_tpl", "image" );

$t->set_var( "file", "" );
$t->set_var( "image", "" );

$t->set_var( "site_style", $SiteDesign );

$dir = eZPBFile::dir( "kernel/ezsitemanager/staticfiles/" );
$ret = array();
$i=0;

while ( $entry = $dir->read() )
{
	if ( $entry != "." && $entry != ".." && $entry != "images" )
    {
        if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->set_var( "file_name", $entry );
        $t->parse( "file", "file_tpl", true );
	$i++;
    }
}

$dir = eZPBFile::dir( "kernel/ezsitemanager/staticfiles/images" );
$ret = array();
while ( $entry = $dir->read() )
{
    if ( $entry != "." && $entry != ".." )
    {
        if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

        $t->set_var( "file_name", $entry );
        $t->parse( "image", "image_tpl", true );
	$i++;
    }
}

$t->pparse( "output", "file_list_tpl" );

?>