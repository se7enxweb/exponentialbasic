<?php
//
// $Id: datasupplier.php 7420 2001-09-24 11:53:43Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
?>


<?php
$url_array = explode( "/", $REQUEST_URI );

include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );
$user =& eZUser::currentUser();
if ( eZPermission::checkPermission( $user, "eZForum", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}
switch ( $url_array[2] )
{
    case "forumlist":
    {
        $CategoryID = $url_array[3];
        include( "ezforum/admin/forumlist.php" );
    }
    break;

    case "unapprovedlist":
    {
        if ( $url_array[3] == "parent" )
            $Offset = $url_array[4];
        else
            $Offset = 0;
        include( "ezforum/admin/unapprovedlist.php" );
    }
    break;
    case "unapprovededit":
    {
        include( "ezforum/admin/unapprovededit.php" );
    }
    break;

    
    case "messagelist":
    {
        $ForumID = $url_array[3];

        if ( isset( $url_array[5] ) && isset( $url_array[4] ) && $url_array[4] == "parent" )
            $Offset = $url_array[5];
        else
            $Offset = 0;

        include( "ezforum/admin/messagelist.php" );
    }
    break;

    case "search" :
    {
        if ( $url_array[3] == "parent" )
        {
            $QueryString = urldecode( $url_array[4] );
            $Offset = $url_array[5];
            if  ( !is_numeric( $Offset ) )
                $Offset = 0;
        }
        include( "ezforum/admin/search.php" );
    }
    break;


    case "message":
    {
        $MessageID = $url_array[3];
        include( "ezforum/admin/message.php" );
    }
    break;

    case "messageedit":
    {
        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "update";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
    }
    break;
    case "forumedit":
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            
            $Action = "update";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
    }
    break;

    case "categoryedit":
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            $CategoryID = false;
            $sectionID = false;
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "update";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
    }
    break;

    case "categorylist" :
    {
        include( "ezforum/admin/categorylist.php" );
    }
    break;
    case "norights":
    {
        include( "ezforum/admin/norights.php" );
    }
    break;

    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>