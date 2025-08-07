<?php
// 
// $Id: unassigned.php 7496 2001-09-27 07:59:27Z jhe $
//
// Created on: <10-Dec-2000 16:16:20 bf>
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
// include_once( "classes/ezlog.php" );

// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZFileManagerMain", "Language" );

$ImageDir = $ini->variable( "eZFileManagerMain", "ImageDir" );


$t = new eZTemplate( "kernel/ezfilemanager/admin/" . $ini->variable( "eZFileManagerMain", "AdminTemplateDir" ),
                     "kernel/ezfilemanager/admin/intl/", $Language, "unassigned.php" );

$t->set_file( "unassigned_page_tpl", "unassigned.tpl" );

$t->setAllStrings();

$t->set_block( "unassigned_page_tpl", "current_folder_tpl", "current_folder" );
$t->set_block( "unassigned_page_tpl", "value_tpl", "value" );

$t->set_block( "unassigned_page_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_tpl", "file_tpl", "file" );
$t->set_var( "read", "" );

if ( isset( $Update ) )
{
    for ( $i = 0; $i < count( $FileArrayID ); $i++ )
    {
        if ( ( $FolderArrayID[$i] != "-1" ) && ( is_numeric( $FolderArrayID[$i] ) ) )
        {
            $file = new eZVirtualFile( $FileArrayID[$i] );
            $folder = new eZVirtualFolder( $FolderArrayID[$i] );
            $folder->addFile( $file );
        }
    }
}

// Print out the files.
$fileList =& eZVirtualFile::getUnAssigned();
// $fileCount =& eZFileManager::countUnAssigned();
if ( !is_null( $fileList ) && count( $fileList ) > 0 )
{
    foreach ( $fileList as $file )
    {
        $t->set_var( "file_id", $file->id() );
        $t->set_var( "original_file_name", $file->originalFileName() );
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
        
        $t->parse( "file", "file_tpl", true );
        $i++;
    }
    $t->parse( "file_list", "file_list_tpl" );
}
else
$t->set_var( "file_list", "" );

$folder = new eZVirtualFolder() ;
$folderList =& $folder->getTree( );

// Make a folder list
foreach ( $folderList as $folderItem )
{
    if ( eZObjectPermission::hasPermission( $folderItem[0]->id(), "imagecatalogue_category", 'w' )
        || eZImageCategory::isOwner( eZUser::currentUser(), $folderItem[0]->id() ) )
    {
        $t->set_var( "option_name", $folderItem[0]->name() );
        $t->set_var( "option_value", $folderItem[0]->id() );

        if ( $folderItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $folderItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
    }
}

$t->set_var( "image_dir", $ImageDir );

$t->pparse( "output", "unassigned_page_tpl" );

?>