<?php
// 
// $Id: browse.php 6969 2001-09-05 11:54:47Z jhe $
//
// Created on: <29-May-2001 14:58:11 ce>
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFileManagerMain", "Language" );

$ImageDir = $ini->read_var( "eZFileManagerMain", "ImageDir" );

$t = new eZTemplate( "kernel/ezfilemanager/admin/" . $ini->read_var( "eZFileManagerMain", "AdminTemplateDir" ),
                     "kernel/ezfilemanager/admin/intl/", $Language, "browse.php" );

$t->set_file( "browse_page_tpl", "browse.tpl" );

$t->setAllStrings();

$t->set_block( "browse_page_tpl", "current_folder_tpl", "current_folder" );

// path
$t->set_block( "browse_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "browse_page_tpl", "file_list_tpl", "file_list" );

$t->set_block( "file_list_tpl", "file_tpl", "file" );

$t->set_block( "file_tpl", "read_tpl", "read" );

$t->set_block( "browse_page_tpl", "folder_list_tpl", "folder_list" );
$t->set_block( "folder_list_tpl", "folder_tpl", "folder" );

$t->set_block( "folder_tpl", "folder_read_tpl", "folder_read" );

$t->set_var( "read", "" );

$user =& eZUser::currentUser();

$folder = new eZVirtualFolder( $FolderID );

$error = true;

$session =& eZSession::globalSession();

$returnUrl = $session->variable( "FileListReturnTo" );
$t->set_var( "name", $session->variable( "NameInBrowse" ) );

$t->set_var( "action_url", $returnUrl );

// Check for read permission in the current folder.
if ( eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "r", $user ) ||
     eZVirtualFolder::isOwner( $user, $folder->id() ) )
{
    $error = false;
} 

if ( $FolderID == 0 )
{
    $error = false;
}

$t->set_var( "write_menu", "" );
$t->set_var( "delete_menu", "" );
$t->set_var( "current_folder", "" );
$t->set_var( "current_folder_description", "" );

if ( $folder->id() != 0 )
{
    $t->set_var( "current_folder_description", $folder->description() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "folder_name", $folder->name() );
    
    $t->parse( "current_folder", "current_folder_tpl" );
}

// path
$pathArray =& $folder->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "folder_id", $path[0] );

    $t->set_var( "folder_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

// Print out the folders.

$folderList =& $folder->getByParent( $folder );

$i = 0;
$deleteFolders = false;
foreach ( $folderList as $folderItem )
{
    $t->set_var( "folder_name", $folderItem->name() );
    $t->set_var( "folder_id", $folderItem->id() );

    $t->set_var( "folder_read", "" );
    $t->set_var( "folder_write", "" );

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    if ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "r", $user ) ||
         eZVirtualFolder::isOwner( $user, $folderItem->id()) )
     
    {
        $t->parse( "folder_read", "folder_read_tpl" );
        $i++;
    }

    $t->parse( "folder", "folder_tpl", true );
}


if ( count( $folderList ) > 0 )
{
    $t->parse( "folder_list", "folder_list_tpl" );
}
else
{
    $t->set_var( "folder_list", "" );
}

// Print out the files.

$count = $folder->countFiles();
$fileList =& $folder->files( "name", 0, $count );

$deleteFiles = false;
foreach ( $fileList as $file )
{
    $t->set_var( "file_id", $file->id() );
//    $t->set_var( "original_file_name", $file->originalFileName() );
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
         eZVirtualFile::isOwner( $user ,$file->id() ) )
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

if ( count( $fileList ) > 0 )
{
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
}

$t->set_var( "image_dir", $ImageDir );
$t->set_var( "main_folder_id", $FolderID );

if ( $error == false )
{
    $t->pparse( "output", "browse_page_tpl" );
}
else
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

?>