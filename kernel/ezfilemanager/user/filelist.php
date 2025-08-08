<?php
// 
// $Id: filelist.php 9424 2002-04-15 11:49:48Z jhe $
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
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$Language = $ini->variable( "eZFileManagerMain", "Language" );
$ImageDir = $ini->variable( "eZFileManagerMain", "ImageDir" );
$FileCat = $ini->variable( "eZFileManagerMain", "FileCat" );
$SyncDir = $ini->variable( "eZFileManagerMain", "SyncDir" );
$SectionID = $ini->variable( "eZFileManagerMain", "DefaultSection" );
$Limit = $ini->variable( "eZFileManagerMain", "Limit" );
$ShowUpFolder = $ini->variable( "eZFileManagerMain", "ShowUpFolder" ) == "enabled";

$t = new eZTemplate( "kernel/ezfilemanager/user/" . $ini->variable( "eZFileManagerMain", "TemplateDir" ),
                     "kernel/ezfilemanager/user/intl/", $Language, "filelist.php" );

$t->set_file( "file_list_page_tpl", "filelist.tpl" );

$t->setAllStrings();

$t->set_block( "file_list_page_tpl", "current_folder_tpl", "current_folder" );

// path
$t->set_block( "file_list_page_tpl", "path_item_tpl", "path_item" );
$t->set_block( "file_list_page_tpl", "write_menu_tpl", "write_menu" );
$t->set_block( "file_list_page_tpl", "delete_menu_tpl", "delete_menu" );
$t->set_block( "file_list_page_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_page_tpl", "next_tpl", "next" );
$t->set_block( "file_list_page_tpl", "prev_tpl", "prev" );

$t->set_block( "file_list_tpl", "file_tpl", "file" );

$t->set_block( "file_tpl", "read_tpl", "read" );

$t->set_block( "read_tpl", "description_edit_tpl", "description_edit" );
$t->set_block( "read_tpl", "description_tpl", "description" );

$t->set_block( "file_tpl", "write_tpl", "write" );
$t->set_block( "file_tpl", "no_write_tpl", "no_write" );

$t->set_block( "file_list_page_tpl", "parent_folder_tpl", "parent_folder" );
$t->set_block( "file_list_page_tpl", "folder_list_tpl", "folder_list" );
$t->set_block( "folder_list_tpl", "folder_tpl", "folder" );

$t->set_block( "folder_tpl", "folder_write_tpl", "folder_write" );
$t->set_block( "folder_tpl", "folder_read_tpl", "folder_read" );

$t->set_var( "read", "" );
$t->set_var( "description_edit", "" );
$t->set_var( "description", "" );
$t->set_var( "sync_dir", $SyncDir );
/*
echo "<pre>";
print_r ($t);
echo "</pre>";
exit();
*/
$user =& eZUser::currentUser();

if ( isSet ( $FileUpload ) )
{

	$category = new eZVirtualFolder( $FileCat );
	syncDir( $SyncFileDir, $category );
	eZHTTPTool::header( "Location: /filemanager/list/$FileCat" );
    exit();
}

$folder = new eZVirtualFolder( $FolderID );

if ( $FolderID == 0 )
    $GlobalSectionID = $ini->variable( "eZFileManagerMain", "DefaultSection" );
else
    $GlobalSectionID = eZVirtualFolder::sectionIDstatic ( $FolderID );
// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$error = true;

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
$t->set_var( "folder", "" );


if ( $folder->id() != 0 )
{
    $t->set_var( "current_folder_description", $folder->description() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "folder_name", $folder->name() );
    $t->set_var( "folder_description", $folder->description() );
    $t->parse( "current_folder", "current_folder_tpl" );

    $parent = $folder->parent();
    if ( is_object( $parent ) )
        $parent = $parent->id();
    $t->set_var( "parent_folder_id", $parent );
    $t->set_var( "td_class_parent", "bglight" );
    if ( $ShowUpFolder )
        $t->parse( "parent_folder", "parent_folder_tpl" );
    else
        $t->set_var( "parent_folder", "" );
}
else
{
    $t->set_var( "parent_folder", "" );
}

// path
$pathArray =& $folder->path();

$t->set_var( "path_item", "" );
$lastPath = array();
foreach ( $pathArray as $path )
{
    $t->set_var( "folder_id", $path[0] );
    $t->set_var( "folder_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
    $lastPath = $path;
}

if ( count( $lastPath ) == 0 )
{
    $t->set_var( "top_folder_name", '' );
}
else
{
    $t->set_var( "top_folder_name", $lastPath[1] );
}

// Print out the folders.
$folderList =& $folder->getByParent( $folder );

$i = ( $folder->id() && $ShowUpFolder ) ? 1 : 0;
$deleteFolders = false;

foreach ( $folderList as $folderItem )
{
    $t->set_var( "folder_description", $folderItem->description() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->set_var( "folder_id", $folderItem->id() );

    $t->set_var( "folder_read", "" );
    $t->set_var( "folder_write", "" );

    $t->set_var( "td_class", ( $i % 2 ) ? "bgdark" : "bglight" );

    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "w", $user ) &&
           eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "w", $user ) ) ||
         ( eZVirtualFolder::isOwner( $user, $folderItem->id() ) ) )
    {
        $t->parse( "folder_write", "folder_write_tpl" );
        $deleteFolders = true;
    }

    if ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "r", $user ) ||
         eZVirtualFolder::isOwner( $user, $folderItem->id() ) )
    {
        $t->parse( "folder_read", "folder_read_tpl" );
        $t->parse( "folder", "folder_tpl", true );
        $i++;
    }
}


if ( count( $folderList ) > 0 )
{
    $t->parse( "folder_list", "folder_list_tpl" );
}
else
{
    $t->set_var( "folder_list", "No folders to display. Click new folder to create one." );
}

// Print out the files.

$fileList =& $folder->files( "name", $Offset, $Limit );

  //for debugging
/*
if ( count($fileList) > 0 )
{
	echo "<pre>";
	print_r($fileList);
	echo "<pre>";
	exit();
}
*/

$deleteFiles = false;
foreach ( $fileList as $file )
{
    $filename = $file->name();
    if ( $ini->variable( "eZFileManagerMain", "DownloadOriginalFilename" ) == "true" )
        $originalfilename = $file->originalFileName();
    else
        $originalfilename = $filename;
    
    $t->set_var( "file_id", $file->id() );
    $t->set_var( "original_file_name_without_spaces", str_replace( " ", "%20", $originalfilename ) );
    $t->set_var( "original_file_name", $filename );
    $t->set_var( "file_name", $filename );
    $t->set_var( "file_url", $filename );
    $t->set_var( "file_description", $file->description() );

    $fileOwner =& $file->user();
    if ( $fileOwner )
        $t->set_var( "file_owner", $fileOwner->firstName() . " " . $fileOwner->lastName() );
    
    $filePath = $file->filePath( true );

    $size = $file->siFileSize();
    $t->set_var( "file_size", $size["size-string"] );
    $t->set_var( "file_unit", $size["unit"] );

    $t->set_var( "file_read", "" );
    $t->set_var( "file_write", "" );
    $t->set_var( "td_class", ( $i % 2 ) ? "bgdark" : "bglight" );

    if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) ||
         eZVirtualFile::isOwner( $user ,$file->id() ) )
    {
	
				 if ( $user &&
         	( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "w", $user ) ||
           eZVirtualFile::isOwner( $user, $file->id() ) ) )
			{   
			$t->parse( "description_edit", "description_edit_tpl" );
    	    $t->set_var( "description", "" );
			}
		else
			{
			$t->parse( "description", "description_tpl" );
    	    $t->set_var( "description_edit", "" );
			}
	
        $t->parse( "read", "read_tpl" );
        $i++;
    }
    else
    {
        $t->set_var( "read", "" );
    }
    
    if ( $user &&
         ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "w", $user ) ||
           eZVirtualFile::isOwner( $user, $file->id() ) ) )
    {
        $t->parse( "write", "write_tpl" );
        $t->set_var( "no_write", "" );
        $deleteFiles = true;
    }
    else
    {
        $t->set_var( "write", "" );
        $t->parse( "no_write", "no_write_tpl" );
    }

    $t->parse( "file", "file_tpl", true );
}

$fileNumber = $folder->countFiles();

if ( $Offset > 0 )
{
    $t->set_var( "prev_offset", ( $Offset - $Limit ) > 0 ? $Offset - $Limit : 0 );
    $t->parse( "prev", "prev_tpl" );
}
else
{
    $t->set_var( "prev", "" );
}

if ( $fileNumber > $Offset + $Limit )
{
    $t->set_var( "next_offset", $Offset + $Limit );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}

if ( count( $fileList ) > 0 )
{
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
}

if ( $FolderID == 0 )
{
    if ( eZPermission::checkPermission( eZUser::currentUser(), "eZFileManager", "WriteToRoot" ) )
        $t->parse( "write_menu", "write_menu_tpl" );
//    else
//        $t->parse( "write_menu", "" );
}
else if ( $user &&
          ( eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'w' ) ||
            eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'u' ) ||
            eZVirtualFolder::isOwner( eZUser::currentUser(), $FolderID ) ) )
{
    $t->parse( "write_menu", "write_menu_tpl" );
}

if ( $deleteFolders || $deleteFiles )
{
    $t->parse( "delete_menu", "delete_menu_tpl" );
}


$t->set_var( "image_dir", $ImageDir );
$t->set_var( "main_folder_id", $FolderID );

if ( $error == false )
{
    $t->pparse( "output", "file_list_page_tpl" );
}
else
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}


function syncDir( $root, $category )
{
    global $user;
    $dir = eZPBFile::dir( $root, false );

    while ( $entry = $dir->read() )

    {
        if ( $entry != "." && $entry != ".." )
        {
            if ( filetype( $root . $entry ) == "dir" )
            {
                // check if category exists if not create it:
                $subCategoryArray =& $category->getByParent( $category );

                $sub = false;
                foreach ( $subCategoryArray as $subCategory )
                {
                    if ( $subCategory->name() == $entry )
                    {
                        $sub = $subCategory;
                    }
                    print( $subCategory->name() . "\n<br>" );
                }

                if ( $sub == false )
                {

					$sub = new eZVirtualFolder();
					$sub->setUser( $user );
					$sub->setName( $entry );
					$sub->setParent( $category );
					$sub->store();

					//sets permissions
                    eZObjectPermission::removePermissions( $sub->id(), "filemanager_folder", "r" );
                    eZObjectPermission::removePermissions( $sub->id(), "filemanager_folder", "w" );
                    eZObjectPermission::removePermissions( $sub->id(), "filemanager_folder", "u" );

                    $group = new eZUserGroup( -1 );
                    eZObjectPermission::setPermission( -1, $sub->id(), "filemanager_folder", "r" );

                    eZObjectPermission::setPermission( 1, $sub->id(), "filemanager_folder", "w" );
                    eZObjectPermission::setPermission( 1, $sub->id(), "filemanager_folder", "u" );
                }
                print( "Syncing dir " . $root . $entry . "\n<br>" );
                syncDir( $root . $entry . "/", $sub );
            }
            else if ( filetype( $root . $entry ) == "file" )
            {
                $files = $category->files( "time", 0, -1 );

                $fileExists = false;

                foreach ( $files as $file )
                {
                    if ( $entry == $file->name() )
                    {
                        $fileExists = true;
                    }
      }

                if ( $fileExists == false )
      {

						
						$uploadedFile = new eZVirtualFile();
					    $uploadedFile->setUser( $user );
						$uploadedFile->setName( $entry );
						
						$uploadedFile->setDescription( $entry );												
                        print( "adding file: " . $root . $entry . "\n<br>" );
                        $getFile = new eZFile();
                        $getFile->getFile( $root . $entry );
				
					    $uploadedFile->store();
					    $uploadedFile->setFile( $getFile );
						
					    $folder = new eZVirtualFolder( $category->id() );
					    $folder->addFile( $uploadedFile );
					    $uploadedFile->setOriginalFileName( $entry );
						$uploadedFile->store();
						
                        eZObjectPermission::removePermissions( $uploadedFile->id(), "filemanager_file", "r" );
                        eZObjectPermission::removePermissions( $uploadedFile->id(), "filemanager_file", "w" );

                        eZObjectPermission::setPermission( -1, $uploadedFile->id(), "filemanager_file", "r" );
                        eZObjectPermission::setPermission( 1, $uploadedFile->id(), "filemanager_file", "w" );
                }
            }
        }
    }
}

?>