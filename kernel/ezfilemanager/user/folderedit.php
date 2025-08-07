<?php
// 
// $Id: folderedit.php 9793 2003-03-25 08:26:23Z br $
//
// Created on: <08-Jan-2001 11:13:29 ce>
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
// include_once( "classes/ezhttptool.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// include_once( "ezsitemanager/classes/ezsection.php" );

// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

$user =& eZUser::currentUser();

if ( isset( $NewFolder ) )
{
    $Description = false;
    $Name = false;
    $readGroupArrayID = array();
    $writeGroupArrayID = array();
    $uploadGroupArrayID = array();
    $sectionID = false;
}
// check if user has rights to edit the current folder.
if ( isset( $FolderID ) && $FolderID != 0 &&
     !eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'w' ) &&
     !eZVirtualFolder::isOwner( $user, $FolderID ) ) 
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

if ( isset( $Cancel ) )
{
    $folder = new eZVirtualFolder( $FolderID );

    $parent = $folder->parent();

    if ( !isset( $parentID ) )
        $parentID = "0";
    
    if ( $parent )
        $parentID = $parent->id();

    eZHTTPTool::header( "Location: /filemanager/list/" . $parentID );
    exit();
}

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZFileManagerMain", "Language" );


$t = new eZTemplate( "kernel/ezfilemanager/user/" . $ini->variable( "eZFileManagerMain", "TemplateDir" ),
                     "kernel/ezfilemanager/user/intl/", $Language, "folderedit.php" );

$t->set_file( "folder_edit_tpl", "folderedit.tpl" );

$t->setAllStrings();

$t->set_block( "folder_edit_tpl", "value_tpl", "value" );
$t->set_block( "folder_edit_tpl", "errors_tpl", "errors" );
$t->set_block( "folder_edit_tpl", "write_group_item_tpl", "write_group_item" );
$t->set_block( "folder_edit_tpl", "read_group_item_tpl", "read_group_item" );
$t->set_block( "folder_edit_tpl", "upload_group_item_tpl", "upload_group_item" );
$t->set_block( "folder_edit_tpl", "section_item_tpl", "section_item" );

$t->set_var( "errors", "" );
$t->set_var( "name_value", $Name );
$t->set_var( "description_value", $Description );

$error = false;
$permissionCheck = true;
$nameCheck = true;
$descriptionCheck = false;

$t->set_block( "errors_tpl", "error_write_permission", "error_write" );
$t->set_var( "error_write", "" );

$t->set_block( "errors_tpl", "error_upload_permission", "error_upload" );
$t->set_var( "error_upload", "" );

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_parent_check_tpl", "error_parent_check" );
$t->set_var( "error_parent_check", "&nbsp;" );

$t->set_block( "errors_tpl", "error_read_everybody_permission_tpl", "error_read_everybody_permission" );
$t->set_var( "error_read_everybody_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_write_everybody_permission_tpl", "error_write_everybody_permission" );
$t->set_var( "error_write_everybody_permission", "&nbsp;" );


if ( $Action == "Insert" || $Action == "Update" )
{
    if ( $permissionCheck )
    {

        if ( $ParentID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZFileManager", "WriteToRoot" ) == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
        }
        else
        {
            $parentFolder = new eZVirtualFolder( $ParentID );
            // no write to parent.
            if ( $FolderID == 0 &&
              eZObjectPermission::hasPermission( $ParentID, "filemanager_folder", 'w' ) == false &&
              eZObjectPermission::hasPermission( $ParentID, "filemanager_folder", 'u') == false )
            {
                $t->parse( "error_write", "error_write_permission" );
                $error = true;
            }
            // update and not write or owner
            if ( ! $error && $Action == "Update" && 
                eZObjectPermission::hasPermission( $FolderID, "filemanager_folder", 'w' ) == false &&
                eZVirtualFolder::isOwner( $user, $FolderID ) == false )
            {
                $t->parse( "error_upload", "error_upload_permission" );
                $error = true;
            }
        }
    }

    if ( $nameCheck )
    {
        if ( empty( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }

    if ( $descriptionCheck )
    {
        
        if ( empty( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }

    if ( $Action == "Update" )
    {
        if ( $ParentID == $FolderID )
        {
            $t->parse( "error_parent_check", "error_parent_check_tpl" );
            $error = true;
        }
    }
   
    if ( $error )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

// Insert or update a folder.
if ( ( $Action == "Insert" || $Action == "Update" ) && $error == false )
{
    if ( $Action == "Insert" )
    {
        $folder = new eZVirtualFolder();
        $folder->setUser( $user );
    }
    else
    {
        $folder = new eZVirtualFolder( $FolderID );
    }
    $folder->setName( $Name );
    if ( isset( $Description ) )
        $folder->setDescription( $Description );

    $folder->setSectionID( $SectionID );
    $parent = new eZVirtualFolder( $ParentID );
    $folder->setParent( $parent );

    $folder->store();
    $FolderID = $folder->id();

    changePermissions( $FolderID, $ReadGroupArrayID, 'r' );
    changePermissions( $FolderID, $WriteGroupArrayID, 'w' );
    changePermissions( $FolderID, $UploadGroupArrayID, 'u');

    // check if user uploaded a dir and had upload permission only and is not owner.
    // TODO: No move, insert takes permissions of parent.
    if ( $Action == "Insert" &&
         eZObjectPermission::hasPermission( $ParentID, "filemanager_folder", 'w' ) == false &&
         $parent->user( false ) != $user->id() )
    {
        changePermissions( $FolderID, eZObjectPermission::getGroups( $ParentID, "filemanager_folder", 'r', false ), 'r' );
        changePermissions( $FolderID, eZObjectPermission::getGroups( $ParentID, "filemanager_folder", 'w', false ), 'w' );
        changePermissions( $FolderID, eZObjectPermission::getGroups( $ParentID, "filemanager_folder", 'u', false ), 'u' );
        $folder->setUser( $parent->user() );
        $folder->store();
    }
    // if update and moving into a folder that has upload permission and not owner of that folder
    // update into a upload dir is NOT allowed unless you have write permission or you are the owner.
/*
    if ( $Action == "Update" &&
         eZObjectPermission::hasPermission( $ParentID, "filemanager_folder", 'w' ) == false &&
         $parent->user( false ) != $user->id() )
    {
        exit();
        // recursivly edit permissions on all file and folders...
        $folders = array();
        $folders[] = $folder; // set permission on self.
        $files = array();
        getFilesAndFolders( $folders, $files, $folder );

        // set permissions on all these files and dirs..
        foreach ( $folders as $folderItem )
        {
            eZObjectPermission::removePermissions( $folderItem->id(), "filemanager_folder", 'w' ); // no write
            eZObjectPermission::removePermissions( $folderItem->id(), "filemanager_folder", 'r' ); // all read
            eZObjectPermission::removePermissions( $folderItem->id(), "filemanager_folder", 'u' ); // all upload
            eZObjectPermission::setPermission( -1, $folderItem->id(), "filemanager_folder", 'r' );
            eZObjectPermission::setPermission( -1, $folderItem->id(), "filemanager_folder", 'u' );
            $folderItem->setUser( $parent->user() );
            $folderItem->store();
        }
        foreach ( $files as $fileItem )
        {
            eZObjectPermission::removePermissions( $fileItem->id(), "filemanager_file", 'w' ); // no write
            eZObjectPermission::removePermissions( $fileItem->id(), "filemanager_file", 'r' ); // all read
            eZObjectPermission::setPermission( -1, $fileItem->id(), "filemanager_file", 'r' );
            $fileItem->setUser( $parent->user() );
            $fileItem->store();
        }
    }
*/

    eZHTTPTool::header( "Location: /filemanager/list/" . $ParentID );
    exit();
}

if ( $Action == "Delete" && $error == false )
{
    if ( count( $FolderArrayID ) > 0 )
    {
        foreach ( $FolderArrayID as $FolderID )
        {
            $folder = new eZVirtualFolder( $FolderID );
            $folder->delete();
        }
    }
}

$t->set_var( "write_everybody", "" );
$t->set_var( "read_everybody", "" );
$t->set_var( "upload_everybody", "" );
if ( $Action == "New" || $error )
{
    if ( $Action == "New" )
    {
        $t->set_var( "action_value", "insert" );
        $t->set_var( "folder_id", "" );
    }
    else
    {
        $t->set_var( "action_value", "update" );
        $t->set_var( "folder_id", $FolderID );
    }
    $t->set_var( "write_everybody", "selected" );
    $t->set_var( "read_everybody", "selected" );
    $t->set_var( "upload_everybody", "selected" );
}

if ( $Action == "Edit" )
{
    $folder = new eZVirtualFolder( $FolderID );

    $t->set_var( "name_value", $folder->name() );
    $t->set_var( "folder_id", $folder->id() );
    $t->set_var( "description_value", $folder->description() );

    $sectionID = $folder->sectionID();
    
    $parent = $folder->parent();

    if ( $parent )
        $parentID = $parent->id();

    $readGroupArrayID =& eZObjectPermission::getGroups( $folder->id(), "filemanager_folder", "r", false );
    $writeGroupArrayID =& eZObjectPermission::getGroups( $folder->id(), "filemanager_folder", "w", false );
    $uploadGroupArrayID =& eZObjectPermission::getGroups( $folder->id(), "filemanager_folder", "u", false );
    $t->set_var( "action_value", "update" );
}

$folder = new eZVirtualFolder() ;

$folderList = $folder->getTree( );

if ( count( $folderList ) == 0 )
{
    $t->set_var( "option_level", "" );
    $t->set_var( "value", "" );
}

// Print out all the groups.
//$groups = $user->groups();
$group = new eZUserGroup();
$groups = $group->getAll();

$t->set_var( "is_write_selected1", "" );
$t->set_var( "is_read_selected1", "" );
$t->set_var( "is_upload_selected1", "" );

foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    if ( $readGroupArrayID )
    {
        if ( in_array( $group->id(), $readGroupArrayID ) )
            $t->set_var( "is_read_selected1", "selected" );
        else
            $t->set_var( "is_read_selected1", "" );

        if ( in_array( -1, $readGroupArrayID ) )
            $t->set_var( "read_everybody", "selected" );
    }
    $t->parse( "read_group_item", "read_group_item_tpl", true );
    
    if ( $writeGroupArrayID )
    {
        if ( in_array( $group->id(), $writeGroupArrayID ) )
            $t->set_var( "is_write_selected1", "selected" );
        else
            $t->set_var( "is_write_selected1", "" );

        if ( in_array( -1, $writeGroupArrayID ) )
            $t->set_var( "write_everybody", "selected" );
    }
    $t->parse( "write_group_item", "write_group_item_tpl", true );

    if ( $uploadGroupArrayID )
    {
        if ( in_array( $group->id(), $uploadGroupArrayID ) )
            $t->set_var( "is_upload_selected1", "selected" );
        else
            $t->set_var( "is_upload_selected1", "" );

        if ( in_array( -1, $uploadGroupArrayID ) )
            $t->set_var( "upload_everybody", "selected" );
    }
    $t->parse( "upload_group_item", "upload_group_item_tpl", true );
}

$sectionList =& eZSection::getAll();

if ( count( $sectionList ) > 0 )
{
    foreach ( $sectionList as $section )
    {
        $t->set_var( "section_id", $section->id() );
        $t->set_var( "section_name", $section->name() );
        
        if ( $sectionID == $section->id() )
            $t->set_var( "section_is_selected", "selected" );
        else
            $t->set_var( "section_is_selected", "" );
        
        $t->parse( "section_item", "section_item_tpl", true );
    }
}
else
    $t->set_var( "section_item", "" );


// Print out all the folders.
if( $parentID == 0 || !isset( $parentID ) )
    $t->set_var( "root_selected", "selected" );
else
    $t->set_var( "root_selected", "" );

foreach ( $folderList as $folderItem )
{
    if ( eZObjectPermission::hasPermission( $folderItem[0]->id(), "filemanager_folder", 'w' ) ||
         eZVirtualFolder::isOwner( eZUser::currentUser(), $folderItem[0]->id() ) ||
         eZObjectPermission::hasPermission( $folderItem[0]->id(), "filemanager_folder", 'u' ) )
    {
        $t->set_var( "option_name", $folderItem[0]->name() );
        $t->set_var( "option_value", $folderItem[0]->id() );

        if ( $folderItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $folderItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->set_var( "selected", "" );
    
        if ( $folder && !$FolderID )
        {
            $FolderID = $folder->id();
        }

        $selectFolderID = $folderItem[0]->id();
        if ( $parentID )
        {
            if ( $selectFolderID == $parentID )
            {
                $t->set_var( "selected", "selected" );
            }
        }

        $t->parse( "value", "value_tpl", true );
    }
}

$t->pparse( "output", "folder_edit_tpl" );

/******* FUNCTIONS ****************************/
function changePermissions( $objectID, $groups, $permission )
{
    eZObjectPermission::removePermissions( $objectID, "filemanager_folder", $permission );
    if ( count( $groups ) > 0 )
    {
        foreach ( $groups as $groupItem )
        {
            if ( $groupItem == 0 || $groupItem == -1 )
                $group = -1;
            else
                $group = $groupItem;
            
            eZObjectPermission::setPermission( $group, $objectID, "filemanager_folder", $permission );
//            print "set: " . $group . " - " . $objectID . " - " . $permission . "<br>";
        }
    }
}

// get all the files and folders of a folder recursivly.
// obsolete, kept since we might need it sometime later if we decide to extend the permissions system again.
function getFilesAndFolders( &$folderArray, &$fileArray, $fromFolder )
{
    $result = eZVirtualFolder::getByParent( $fromFolder );
    $folderArray = array_merge( $result, $folderArray );
    $files = $fromFolder->files( "time", -1, -1 );
    $fileArray = array_merge( $files, $fileArray );
    
    foreach ( $result as $child )
    {
        getFilesAndFolders( $folderArray, $fileArray, $child );
    }
}

?>