<?php
//
// $Id: datasupplier.php 6739 2001-08-29 10:37:23Z jhe $
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezbug/classes/ezbugmodule.php" );
// include_once( "ezbug/classes/ezbug.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZBugMain", "DefaultSection" );

function hasPermission( $bugID )
{
    $user =& eZUser::currentUser();
    $bug = new eZBug( $bugID );
    $module = $bug->module();
    if ( is_a( $module, "eZBugModule" ) && eZObjectPermission::hasPermission( $module->id(), "bug_module", "w" ) )
    {
        return true;
    }
    else
    {
        return false;
    }
}

switch ( $url_array[2] )
{
    case "edit" :
    {
        if ( $url_array[3] == "edit" && hasPermission( $url_array[4] ) )
        {
            $Action = "Edit";
            $BugID = $url_array[4];
            include( "kernel/ezbug/admin/bugedit.php" );
        }
        else if ( $url_array[3] == "fileedit" && hasPermission( $BugID ) )
        {
            switch ( $url_array[4] )
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
        else if ( $url_array[3] == "imageedit" && hasPermission( $BugID ) )
        {
            switch ( $url_array[4] )
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
        else if ( hasPermission( $BugID ) )
        {
            $Action = "Update";
            include( "kernel/ezbug/admin/bugedit.php" );
        }
        else // someone is trying to push the envelope
        {
            eZHTTPTool::header( "Location: /error/403");
            exit();
        }
    }
    break;

    case "archive" :
    {
        $ModuleID = $url_array[3];

        include( "kernel/ezbug/user/buglist.php" );
    }
    break;

    case "search" :
    {
        if ( $url_array[3] == "parent" )
        {
            $Offset = $url_array[5];
            $SearchText = urldecode( $url_array[4] );
        }

        include( "kernel/ezbug/user/search.php" );
    }
    break;

    case "view" :
    case "bugview" :
    {
        $BugID = $url_array[3];

        include( "kernel/ezbug/user/bugview.php" );
    }
    break;


    case "report" :
    {
        switch ( $url_array[3] )
        {
            case "create" :
            {
                $Action = "";
                include( "kernel/ezbug/user/bugreport.php" );
            }
            break;
            case "new" :
            {
                $Action = "New";
                $BugID = "";
                include( "kernel/ezbug/user/bugreport.php" );
            }
            break;

            case "edit" :
            {
                $BugID = $url_array[4];
                $Action = "Edit";
                if ( $session->variable( "CurrentBugEdit" ) == $BugID && $BugID != 0 )
                {
                    $session->setVariable( "CurrentBugEdit", 0 );
                    include( "kernel/ezbug/user/bugreport.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403");
                    exit();
                }
            }
            break;

            case "update" :
            {
                $BugID = $url_array[4];
                $Action = "Update";
                include( "kernel/ezbug/user/bugreport.php" );
            }
            break;

            case "fileedit" :
            {
                if ( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/user/fileedit.php" );
                }
                else if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "kernel/ezbug/user/fileedit.php" );
                }
                else if ( $url_array[4] == "delete" )
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
                if ( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "kernel/ezbug/user/imageedit.php" );
                }
                else if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "kernel/ezbug/user/imageedit.php" );
                }
                else if ( $url_array[4] == "delete" )
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

            default :
            {
                print( "Error: Bug file not found" );
            }
            break;
        }
    }
    break;

    case "unhandled" :
    {
        include( "kernel/ezbug/user/unhandledbugs.php" );
    }
    break;

    case "reportsuccess" :
    {
        include( "kernel/ezbug/user/reportsuccess.php" );
    }
    break;

    default :
    {
        print( "Error: Bug file not found" );
    }
    break;

}

?>