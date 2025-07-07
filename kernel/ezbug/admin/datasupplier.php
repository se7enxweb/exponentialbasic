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


// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "classes/ezhttptool.php" );

$user =& eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZBug", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "archive" :        
    {
        $ModuleID = $url_array[3];
        $Action = "";
        include( "kernel/ezbug/admin/buglist.php" );
    }
    break;

    case "search" :        
    {
        include( "kernel/ezbug/admin/search.php" );
    }
    break;
    
    case "bugpreview" :
    case "view" :        
    {
        $BugID = $url_array[3];
        
        include( "kernel/ezbug/user/bugview.php" );
    }
    break;
    
    case "unhandled" :
    {
        include( "kernel/ezbug/admin/unhandledbugs.php" );
    }
    break;

    case "priority" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                include( "kernel/ezbug/admin/prioritylist.php" );
            }
            break;
        }
    }
    break;

    case "category" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                include( "kernel/ezbug/admin/categorylist.php" );
            }
            break;
        }
    }
    break;

    case "module" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                if( isset( $AddModule ) )  // new
                {
                    $Action = "new";
                    $ParentID = $url_array[4];
                    include( "kernel/ezbug/admin/moduleedit.php" );
                }
                else 
                {
                    $ParentID = $url_array[4];
                    include( "kernel/ezbug/admin/modulelist.php" );
                }
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "kernel/ezbug/admin/moduleedit.php" );
            }
            break;

            case "edit":
            {
                $Action = "edit";
                $ModuleID = $url_array[4];
                include( "kernel/ezbug/admin/moduleedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $ModuleID = $url_array[4];
                include( "kernel/ezbug/admin/moduleedit.php" );
            }
            break;

            case "delete":
            {
                $Action = "delete";
                $ModuleID = $url_array[4];
                include( "kernel/ezbug/admin/moduleedit.php" );
            }
            break;

        }
    }
    break;

    case "status" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $ParentID = $url_array[4];
                include( "kernel/ezbug/admin/statuslist.php" );
            }
            break;
        }
    }
    break;

    
    case "edit" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }
        else if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $BugID = $url_array[4];
        }
        else if( $url_array[3] == "fileedit" )
        {
            switch( $url_array[4] )
            {
                case  "new" :
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/admin/fileedit.php" );
                }
                break;
                case  "edit" :
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "kernel/ezbug/admin/fileedit.php" );
                }
                break;
                case "delete" :
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "kernel/ezbug/admin/fileedit.php" );
                }
                break;
                default :
                {
                    include( "kernel/ezbug/admin/fileedit.php" );
                }
                break;
            }
        }
        else if( $url_array[3] == "imageedit" )
        {
            switch( $url_array[4] )
            {
                case "new":
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/admin/imageedit.php" );
                }
                break;
                case "edit" :
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "kernel/ezbug/admin/imageedit.php" );
                }
                break;
                case "delete" :
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "kernel/ezbug/admin/imageedit.php" );
                }
                break;
                default :
                {
                    include( "kernel/ezbug/admin/imageedit.php" );
                }
                break;
            }
        }
        include( "kernel/ezbug/admin/bugedit.php" );
    }
    break;


    case "report" :
    {
        switch( $url_array[3] )
        {
            case "fileedit" :
            {
                if( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/user/fileedit.php" );
                }
                else if( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "kernel/ezbug/user/fileedit.php" );
                }
                else if( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "kernel/ezbug/user/fileedit.php" );
                }
                else
                {
                    include( "kernel/ezbug/user/fileedit.php" );
                }
            }
            break;
            case "imageedit" :
            {
                if( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/user/imageedit.php" );
                }
                else if( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "kernel/ezbug/user/imageedit.php" );
                }
                else if( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "kernel/ezbug/user/imageedit.php" );
                }
                else
                {
                    include( "kernel/ezbug/user/imageedit.php" );
                }
            }
            break;

            case "edit" :
            {
                $BugID = $url_array[4];
                $Action = "Edit";
                include( "kernel/ezbug/admin/bugedit.php" );
            }
            break;

            
            default :
            {
                print( "Error: Bug file not found" );
            }
            break;
        }
    }
    break;
}

?>