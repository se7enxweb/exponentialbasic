<?php
//
// $Id: datasupplier.php 7047 2001-09-06 11:16:40Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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


// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/eztime.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );

$ini = eZINI::instance( 'site.ini' );
$GlobalSectionID = $ini->variable( "eZFileManagerMain", "DefaultSection" );

// HTTP input variables (replaces register_globals extraction)
$cancel             = eZHTTPTool::getVar( 'Cancel' );
$delete             = eZHTTPTool::getVar( 'Delete' );
$deleteFiles        = eZHTTPTool::getVar( 'DeleteFiles' );
$deleteFolders      = eZHTTPTool::getVar( 'DeleteFolders' );
$description        = eZHTTPTool::getVar( 'Description' );
$download           = eZHTTPTool::getVar( 'Download' );
$fileUpload         = eZHTTPTool::getVar( 'FileUpload' );
$name               = eZHTTPTool::getVar( 'Name' );
$parentID           = eZHTTPTool::getVar( 'ParentID' );
$sectionID          = eZHTTPTool::getVar( 'SectionID' );
$syncFileDir        = eZHTTPTool::getVar( 'SyncFileDir' );
$updateFiles        = eZHTTPTool::getVar( 'UpdateFiles' );
// Array POST vars
$fileArrayID        = $_POST['FileArrayID']         ?? [];
$fileUpdateIDArray  = $_POST['FileUpdateIDArray']   ?? [];
$folderArrayID      = $_POST['FolderArrayID']       ?? [];
$newDescriptionArray = $_POST['NewDescriptionArray'] ?? [];
$readGroupArrayID   = $_POST['ReadGroupArrayID']    ?? [];
$uploadGroupArrayID = $_POST['UploadGroupArrayID']  ?? [];
$writeGroupArrayID  = $_POST['WriteGroupArrayID']   ?? [];

switch ( $url_array[2] )
{
    case "new" :        
    {
        $action = "New";
        $newFile = true;
        $folderID = false;
        include_once( "kernel/ezfilemanager/user/fileupload.php" );
    }
    break;

    case "insert" :
    {
        $action = "Insert";
        include_once( "kernel/ezfilemanager/user/fileupload.php" );
    }
    break;

    case "edit" :
    {
        $fileID = $url_array[3];
        $action = "Edit";
        include_once( "kernel/ezfilemanager/user/fileupload.php" );
    }
    break;

    case "update" :
    {
        $fileID = $url_array[3];
        $action = "Update";
        include_once( "kernel/ezfilemanager/user/fileupload.php" );
    }
    break;
    
    case "fileview" :
    {
        $fileID = $url_array[3];
        include( "kernel/ezfilemanager/user/fileview.php" );
    }
    break;
    
    case "download" :
    {
        $fileID = $url_array[3];
        $fileNamePassed = $url_array[4];
        include( "kernel/ezfilemanager/user/filedownload.php" );
    }
    break;
    
    case "list" :
    {
        $folderID = $url_array[3];
        if ( !isset( $folderID ) || $folderID == "" )
            $folderID = 0;
        $offset = $url_array[4];
        if ( !isset( $offset ) || $offset == "" )
            $offset = 0;
        include( "kernel/ezfilemanager/user/filelist.php" );
    }
    break;

	case "import" :
    {
        include( "kernel/ezfilemanager/user/filelist.php" );
    }
    break;

    case "folder" :
    {
        switch ( $url_array[3] )
        {
           
            case "new" :
            {
                $parentID = $url_array[4];
                $action = "New";
                $newFolder = true;
                $folderID = false;
                include_once( "kernel/ezfilemanager/user/folderedit.php" );
            }
            break;
            case "delete" :
            {
                $folderID = $url_array[4];
                $action = "Delete";
                include_once( "kernel/ezfilemanager/user/folderedit.php" );
            }
            break;
            
            case "insert" :
            {
                $action = "Insert";
                include_once( "kernel/ezfilemanager/user/folderedit.php" );
            }
            break;

            case "edit" :
            {
                $folderID = $url_array[4];
                $action = "Edit";
                include_once( "kernel/ezfilemanager/user/folderedit.php" );
            }
            break;

            case "update" :
            {
                $folderID = $url_array[4];
                $action = "Update";
                include_once( "kernel/ezfilemanager/user/folderedit.php" );
            }
            break;

        }
    }
    break;

    case "browse":
    {
        $folderID = $url_array[3];
        include( "kernel/ezfilemanager/admin/browse.php" );
    }
    break;

    case "search":
    {
        if ( $url_array[3] == "parent" )
        {
            $searchText = urldecode( $url_array[4] );
            $offset = $url_array[5];
        }
        
        include( "kernel/ezfilemanager/user/search.php" );
    }
    break;
    
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
}

?>