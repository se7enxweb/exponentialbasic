<?php
// 
// $Id: fileupload.php 9448 2002-04-22 08:35:46Z jhe $
//
// Created on: <10-Dec-2000 15:49:57 bf>
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
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezfile.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

/**
 * Minimal wrapper restoring the old eZFile upload API (tmpName/name/copy)
 * used by eZVirtualFile::setFile(). Extends eZFile so is_a() check passes.
 */
if ( !class_exists( 'eZFileUploadWrapper', false ) )
{
    class eZFileUploadWrapper extends eZFile
    {
        private $tmpPath;
        private $originalName;
        private $uploaded;

        public function __construct( $fieldName )
        {
            $this->tmpPath      = $_FILES[$fieldName]['tmp_name'] ?? false;
            $this->originalName = $_FILES[$fieldName]['name']     ?? false;
            $this->uploaded     = $this->tmpPath && is_uploaded_file( $this->tmpPath );
        }

        public function isUploaded() { return $this->uploaded; }
        public function tmpName()    { return $this->tmpPath; }
        public function name()       { return $this->originalName; }

        public function copy( $dest )
        {
            return move_uploaded_file( $this->tmpPath, $dest );
        }
    }
}

if ( isSet( $updateFiles ) )
{
    $action = "UpdateFiles";
}

if ( isset( $newFile ) )
{
    $action = "New";
    $description = false;
    $name = false;
    $readGroupArrayID = array();
    $writeGroupArrayID = array();
    $uploadGroupArrayID = array();
    $sectionID = false;
}
if ( isset( $newFolder ) )
{
    eZHTTPTool::header( "Location: /filemanager/folder/new/$folderID" );
    exit();
}

if ( isset( $deleteFiles ) )
{
    $action = "DeleteFiles";
}

if ( isset( $delete ) )
{
    $action = "Delete";
}

if ( isset( $deleteFolders ) )
{
    $action = "DeleteFolders";
}

if ( isset( $cancel ) )
{
    eZHTTPTool::header( "Location: /filemanager/list/" . $parentID );
    exit();
}

if ( isset( $download ) )
{
    $file = new eZVirtualFile( $fileID );

    if ( $ini->variable( "eZFileManagerMain", "DownloadOriginalFilename" ) == "true" )
        $fileName = $file->originalFileName();
    else
        $fileName = $file->name();

    eZHTTPTool::header( "Location: /filemanager/download/$fileID/$fileName" );
    exit();
}

$user = eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "kernel/ezfilemanager/user/" . $ini->variable( "eZFileManagerMain", "TemplateDir" ),
                     "kernel/ezfilemanager/user/intl/", $Language, "fileupload.php" );

$t->set_file( "file_upload_tpl", "fileupload.tpl" );

$t->setAllStrings();

$t->set_block( "file_upload_tpl", "value_tpl", "value" );
$t->set_block( "file_upload_tpl", "errors_tpl", "errors" );

$t->set_block( "file_upload_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "file_upload_tpl", "read_group_item_tpl", "read_group_item" );

$t->set_var( "errors", "&nbsp;" );

$t->set_var( "name_value", $name );
$t->set_var( "description_value", $description );

$error = false;
$nameCheck = true;
$descriptionCheck = false;
$folderPermissionCheck = true;
$readCheck = true;
$fileCheck = true;

$t->set_block( "errors_tpl", "error_write_permission", "write_permission" );
$t->set_var( "write_permission", "" );

$t->set_block( "errors_tpl", "error_upload_permission", "upload_permission" );
$t->set_var( "upload_permission", "" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "" );

$t->set_block( "errors_tpl", "error_file_upload_tpl", "error_file_upload" );
$t->set_var( "error_file_upload", "" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "" );


if ($action == "UpdateFiles")
{
    $oldFolder = 0;
    for ( $i = 0; $i < count( $fileUpdateIDArray ); $i++ )
        {
			$file = new eZVirtualFile($fileUpdateIDArray[$i]);
			if ($newDescriptionArray[$i] != $file->description() )
			    $file->setDescription( $newDescriptionArray[$i] );
	            $oldParent = $file->folder();
            if ( $oldParent )
			    $oldFolder = $oldParent->id();
			$file->store(); 
		}
		eZHTTPTool::header( "Location: /filemanager/list/$oldFolder/" );
	    exit();					
}
if ( isset( $action ) && $action == "Insert" || isset( $action ) && $action == "Update" )
{
    if ( $folderPermissionCheck )
    {
        $folder = new eZVirtualFolder( $folderID );
        // must upload to a folder
        if ( !isset( $folderID ) || $folderID == 0 )
        {
            $t->parse( "write_permission", "error_write_permission" ); 
            $error = true;
        }
        // if not write or upload to folder...
        if ( ( !eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "w", $user ) &&
               !eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "u", $user ) ) &&
             !eZVirtualFolder::isOwner( $user, $folderID ) )
        {
            $t->parse( "write_permission", "error_write_permission" ); 
            $error = true;
        }
        // if update but not owner or write.
        if ( isset( $action ) && $action == "Update" &&
            !eZObjectPermission::hasPermission( $folder->id(), "filemanager_folder", "w", $user ) &&
            !eZVirtualFolder::isOwner( $user, $folderID ) )
        {
            $t->parse( "upload_permission", "error_upload_permission" ); 
            $error = true;
        }
    }
    
    if ( $descriptionCheck )
    {
        if ( empty( $description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $fileCheck )
    {
        $file = new eZFileUploadWrapper( 'userfile' );
        if ( !$file->isUploaded() )
        {
            if ( isset( $action ) && $action == "Insert" )
            {
                $error = true;
                $t->parse( "error_file_upload", "error_file_upload_tpl" );
            }
        }
    }

    if ( $error )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}
 
if ( isset( $action ) && $action == "Insert" && !$error )
{
    $uploadedFile = new eZVirtualFile();
    $uploadedFile->setDescription( $description );
    $uploadedFile->setUser( $user );
    $uploadedFile->setFile( $file );
    
    if ( empty( $name ) )
        $name = $uploadedFile->originalFileName();

    if ( !$ini->variable( "eZFileManagerMain", "DownloadOriginalFilename" ) == "true" )
    {
        $extension = strrchr( $uploadedFile->originalFileName(), "." );
        if ( strrchr( $name, "." ) != $extension )
            $name .= $extension;
    }

    $uploadedFile->setName( $name );
    $uploadedFile->store();
    $fileID = $uploadedFile->id();
    $folder = new eZVirtualFolder( $folderID );
    
    if ( eZObjectPermission::hasPermission( $folderID, "filemanager_folder", 'w' ) ||
         eZVirtualFolder::isOwner( $user, $folderID ) ) 
    {
        changeFileManagerPermissions( $fileID, $readGroupArrayID, 'r' );
        changeFileManagerPermissions( $fileID, $writeGroupArrayID, 'w' );
    }
    else // user had upload permission only, change ownership, set special rights..
    {
//        eZObjectPermission::removePermissions( $fileID, "filemanager_file", "wr" ); // no write/read
//        eZObjectPermission::setPermission( -1, $fileID, "filemanager_file", 'r' );
//        $uploadedFile->setUser( $folder->user() );

        changeFileManagerPermissions( $fileID, $readGroupArrayID, 'r' );
        changeFileManagerPermissions( $fileID, $writeGroupArrayID, 'w' );
        
        $uploadedFile->store();
    }

    $folder->addFile( $uploadedFile );

    eZPBLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
    eZHTTPTool::header( "Location: /filemanager/list/$folderID/" );
    exit();
}

if ( isset( $action ) && $action == "Update" && $error == false )
{
    $file = new eZFileUploadWrapper( 'userfile' );

    $uploadedFile = new eZVirtualFile( $fileID );

    $uploadedFile->setName( $name );
    $uploadedFile->setDescription( $description );
    
    if ( $file->isUploaded() )
    {
        $uploadedFile->setFile( $file );
    }    

    $uploadedFile->store();
    changeFileManagerPermissions( $fileID, $readGroupArrayID, 'r' );
    changeFileManagerPermissions( $fileID, $writeGroupArrayID, 'w' );

    $folder = new eZVirtualFolder( $folderID );

    $uploadedFile->removeFolders();
    
    $folder->addFile( $uploadedFile );

    eZPBLog::writeNotice( "File added to file manager from IP: $REMOTE_ADDR" );
    eZHTTPTool::header( "Location: /filemanager/list/$folderID/" );
}

if ( isset( $action ) && $action == "DeleteFiles" )
{
    $oldFolder = 0;
    if ( count( $fileArrayID ) != 0 )
    {
        foreach ( $fileArrayID as $id )
        {
            $file = new eZVirtualFile( $id );
            $oldParent = $file->folder();

            if ( $oldParent )
                $oldFolder = $oldParent->id();

            $file->delete();
        }
    }

    eZHTTPTool::header( "Location: /filemanager/list/$oldFolder/" );
    exit();
}

if ( isset( $action ) && $action == "Delete" )
{
    $file = new eZVirtualFile( $fileID );
    $oldParent = $file->folder();
    
    if ( $oldParent )
        $oldFolder = $oldParent->id();

    $file->delete();

    eZHTTPTool::header( "Location: /filemanager/list/$oldFolder/" );
    exit();
}

if ( isset( $action ) && $action == "DeleteFolders" )
{
    $oldFolder = 0;
    if ( count( $folderArrayID ) > 0 )
    {
        foreach ( $folderArrayID as $folderID )
        {
            $folder = new eZVirtualFolder( $folderID );
            $oldParent = $folder->parent();

            if ( $oldParent )
                $oldFolder = $oldParent->id();

            $folder->delete();
        }
    }

    eZHTTPTool::header( "Location: /filemanager/list/$oldFolder/" );
    exit();
}


$t->set_var( "write_everybody", "" );
$t->set_var( "read_everybody", "" );
if ( isset( $action ) && $action == "New" || $error )
{
    $t->set_var( "action_value", "insert" );
    $t->set_var( "file_id", "" );
    if ( $folderID )
    {
        $readGroupArrayID = eZObjectPermission::getGroups( $folderID, "filemanager_folder", "r", false );
        $writeGroupArrayID = eZObjectPermission::getGroups( $folderID, "filemanager_folder", "w", false );
    }
    else
    {
        $t->set_var( "write_everybody", "selected" );
        $t->set_var( "read_everybody", "selected" );
    }
}

if ( isset( $action ) && $action == "Edit" )
{
    $file = new eZVirtualFile( $fileID );

    if ( !( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) &&
            ( eZObjectPermission::hasPermission( $file->folder( false ), "filemanager_folder", "r", $user ) ||
              eZVirtualFolder::isOwner( $user, $file->folder( false ) ) ) ) )
    {
        eZHTTPTool::header( "Location: /error/403/" );
        exit();
    }
    
    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "file_id", $file->id() );

    $folder = $file->folder();

    if ( $folder )
        $folderID = $folder->id();

    $readGroupArrayID = eZObjectPermission::getGroups( $file->id(), "filemanager_file", "r", false );
    $writeGroupArrayID = eZObjectPermission::getGroups( $file->id(), "filemanager_file", "w", false );

    $t->set_var( "action_value", "update" );
}

// Print out all the groups.

$group = new eZUserGroup();
$groups = $group->getAll();

foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "is_read_selected1", "" );
    $t->set_var( "is_write_selected1", "" );
    
    if ( $readGroupArrayID )
    {
        foreach ( $readGroupArrayID as $readGroup )
        {
            if ( $readGroup == $group->id() )
            {
                $t->set_var( "is_read_selected1", "selected" );
            }
            elseif ( $readGroup == -1 )
            {
                $t->set_var( "read_everybody", "selected" );                    
            }
            else
            {
                $t->set_var( "is_read_selected", "" );
            }
        }
           
    }

    if ( $writeGroupArrayID )
    {
        foreach ( $writeGroupArrayID as $writeGroup )
        {
            if ( $writeGroup == $group->id() )
            {
                $t->set_var( "is_write_selected1", "selected" );
            }
            elseif ( $writeGroup == -1 )
            {
                $t->set_var( "write_everybody", "selected" );                    
            }
            else
            {
                $t->set_var( "is_write_selected", "" );
            }
        }
    }

    $t->parse( "write_group_item", "write_group_item_tpl", true );
    $t->parse( "read_group_item", "read_group_item_tpl", true );
}


$folder = new eZVirtualFolder() ;

$folderList = $folder->getTree();

foreach ( $folderList as $folderItem )
{
    if ( eZObjectPermission::hasPermission( $folderItem[0]->id(), "filemanager_folder", 'w' ) ||
         eZVirtualFolder::isOwner( eZUser::currentUser(), $folderItem[0]->id() ) ||
         eZObjectPermission::hasPermission( $folderItem[0]->id(), "filemanager_folder", 'u' ))
    {
        $t->set_var( "option_name", $folderItem[0]->name() );
        $t->set_var( "option_value", $folderItem[0]->id() );
        
        if ( $folderItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $folderItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->set_var( "selected", "" );
    
        if ( $folder && !$folderID )
        {
            $folderID = $folder->id();
        }

        if ( $folderID )
        {
            if ( $folderItem[0]->id() == $folderID )
            {
                $t->set_var( "selected", "selected" );
            }
        }

        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "file_upload_tpl" );

/******* FUNCTIONS ****************************/

if ( !function_exists( 'changeFileManagerPermissions' ) )
{

function changeFileManagerPermissions( $objectID, $groups, $permission )
{
    eZObjectPermission::removePermissions( $objectID, "filemanager_file", $permission );
    if ( count( $groups ) > 0 )
    {
        foreach ( $groups as $groupItem )
        {
            if ( $groupItem == 0 )
                $group = -1;
            else
                $group = new eZUserGroup( $groupItem );
            
            eZObjectPermission::setPermission( $group, $objectID, "filemanager_file", $permission );
        }
    }
}

}

?>