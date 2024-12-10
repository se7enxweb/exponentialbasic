<?php
//
// $Id: datasupplier.php 6484 2001-08-17 13:36:01Z jhe $
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

include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );

$user =& eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZNews", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $ShowUnPublished = "no";
        
        include( "eznewsfeed/admin/newsarchive.php" );
    }
    break;

    case "unpublished":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        if ( $url_array[4] == "delete" )
        {
            $Action = "Delete";
            $NewsDeleteIDArray = array( $url_array[5] );
        }

        $ShowUnPublished = "only";
        include( "eznewsfeed/admin/unpublished.php" );
    }
    break;
    
    case "sourcesite":
    {
        if ( $url_array[3]  == "edit" )
        {
            $Action = "Edit";
        }
        if ( $url_array[3]  == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3]  == "update" )
        {
            $Action = "Update";
        }
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }
        if ( $url_array[3]  == "delete" )
        {
            $Action = "Delete";
        }

        $SourceSiteID = $url_array[4];
        include( "eznewsfeed/admin/sourcesiteedit.php" );
    }
    break;
    
    case "news":
    {
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }
        else if ( $url_array[3]  == "edit" )
        {
            $arg = $url_array[4];
            
            setType( $arg, "integer" );
            if ( $arg != 0 )
            {
                $Action = "Edit";
                $NewsID = $arg;
            }
        }
        else if ( $url_array[3]  == "delete" )
        {
            $arg = $url_array[4];
            
            setType( $arg, "integer" );
            if ( $arg != 0 )
            {
                $Action = "Delete";
                $NewsID = $arg;
            }
        }

        
        include( "eznewsfeed/admin/newsedit.php" );
    }
    break;
    
    case "categoryedit":
    case "category":
    {
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3]  == "edit" )
        {
            $CategoryID = $url_array[4];
            $Action = "Edit";
        }        

        if ( $url_array[3]  == "delete" )
        {
            $CategoryID = $url_array[4];
            $Action = "Delete";
        }        
        
        include( "eznewsfeed/admin/categoryedit.php" );
    }
    break;

    case "importnews":
    {
        $Action = "ImportNews";

        if ( $url_array[3]  == "fetch" )
        {
            $Action = "Fetch";
            $SourceSiteID = $url_array[4]; 
        }
        
        include( "eznewsfeed/admin/importnews.php" );
    }
    break;

    case "search":
    {
        include( "eznewsfeed/admin/newssearch.php" );
    }
    break;
    
}

?>