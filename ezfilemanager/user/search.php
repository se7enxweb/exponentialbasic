<?php
// 
// $Id: search.php 9842 2003-06-05 10:40:58Z br $
//
// Created on: <10-May-2001 12:48:08 ce>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezfilemanager/classes/ezvirtualfile.php" );

$Language = $ini->read_var( "eZFileManagerMain", "Language" );
$Limit = $ini->read_var( "eZFileManagerMain", "SearchListLimit" );

$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( "search_list_tpl", "search.tpl" );

$t->set_block( "search_list_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_tpl", "file_tpl", "file" );
$t->set_block( "search_list_tpl", "empty_search_tpl", "empty_search" );

$t->set_block( "file_tpl", "read_tpl", "read" );

$t->set_var( "search_text", htmlspecialchars( $SearchText ) );

if ( !isSet( $Offset ) )
    $Offset = 0;

if ( $SearchText )
{
    $file = new eZVirtualFile();
    $fileList = $file->search( $SearchText, $Offset, $Limit );
    $totalCount = $file->searchCount( $SearchText, $user ? $user->id() : -1 );

    $t->set_var( "url_text", urlencode( $SearchText ) );
}

if ( count( $fileList ) > 0 )
{
    foreach ( $fileList as $file )
    {
        $t->set_var( "file_id", $file->id() );
        $t->set_var( "original_file_name", $file->name() );
        $t->set_var( "file_name", $file->name() );
        $t->set_var( "file_url", $file->name() );
        $t->set_var( "file_description", $file->description() );
        
        $filePath = $file->filePath( true );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] );
        $t->set_var( "file_unit", $size["unit"] );
        
        $t->set_var( "file_read", "" );
        $t->set_var( "file_write", "" );
        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
        if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) ||
             eZVirtualFile::isOwner( $user, $file->id() ) )
        {
            $t->parse( "read", "read_tpl" );
            $i++;
        }
        else
        {
            $t->set_var( "read", "" );
        }
    
        $t->parse( "file", "file_tpl", true );
    }
    $t->set_var( "empty_search", "" );
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
    $t->parse( "empty_search", "empty_search_tpl" );
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "search_list_tpl" );

$t->set_var( "file_start", $Offset + 1 );
$t->set_var( "file_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "file_total", $totalCount );

$t->pparse( "output", "search_list_tpl" );

?>
