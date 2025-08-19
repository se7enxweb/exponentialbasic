<?php
//
// $Id: datasupplier.php 9465 2002-04-24 07:38:20Z jhe $
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if ( eZPermission::checkPermission( $user, "eZSiteManager", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "template":
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                $filePath = $url_array[4];
                include( "kernel/ezsitemanager/admin/templatelist.php" );
            }
            break;

            case "edit" :
            {
                $filePath = $url_array[4];
                include( "kernel/ezsitemanager/admin/templateedit.php" );
            }
            break;
        }

    }
    break;

    case "menu":
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                $ParentID = $url_array[4];
                include( "kernel/ezsitemanager/admin/menulist.php" );        
            }
            break;

            case "edit" :
            {
                $MenuID = $url_array[4];
                include( "kernel/ezsitemanager/admin/menuedit.php" );
            }
            break;
        }
    }
    break;

    
    case "siteconfig":
    {
        include( "kernel/ezsitemanager/admin/siteconfig.php" );        
    }
    break;

    case "csseditor":
    {
        include( "kernel/ezsitemanager/admin/csseditor.php" );
    }
    break;
    
    case "file":
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                include( "kernel/ezsitemanager/admin/filelist.php" );
            }
            break;

            case "edit" :
            {
                $fileName = $url_array[4];
                include( "kernel/ezsitemanager/admin/fileedit.php" );
            }
            break;
        }
    }
    break;
    
    case "sqladmin":
    {
        include( "kernel/ezsitemanager/admin/sqlquery.php" );
    }
    break;

    case "cache":
    {

        switch ( $url_array[3] )
        {
            case "variation":
            {
	          include( "kernel/ezsitemanager/admin/imagevariationadmin.php" );
	        }
      	    break;

            case "template":
            {
            include( "kernel/ezsitemanager/admin/cacheadmin.php" );
            }
            break;

            default :
            {
            include( "kernel/ezsitemanager/admin/cacheadmin.php" );
            }
            break;
        }
    }
    break;

    case "imagecache":
    {
        include( "kernel/ezsitemanager/admin/imagevariationcacheadmin.php" );
    }
    break;
    
    case "section":
    {

        switch ( $url_array[3] )
        {
            case "list":
            {
                if ( $url_array[4] == "parent" )
                    $Offset = $url_array[5];
                include( "kernel/ezsitemanager/admin/sectionlist.php" );
            }
            break;


            case "edit":
            case "new":
            case "delete":
            case "update":
            case "insert":
            {
                if ( isset( $url_array[5] ) && $url_array[5] == "up" )
                {
                    $RowID = $url_array[6];
                    $Action = "up";
                }
                if ( isset( $url_array[6] ) && $url_array[5] == "down" )
                {
                    $RowID = $url_array[6];
                    $Action = "down";
                }
                
                if ( isset( $url_array[4] ) && is_numeric( $url_array[4] ) )
                    $SectionID = $url_array[4];
                include ( "kernel/ezsitemanager/admin/sectionedit.php" );
            }
            break;
        }
        break;
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