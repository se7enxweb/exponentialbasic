<?php
//
// $Id: datasupplier.php 6484 2001-08-17 13:36:01Z jhe $
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
if( eZPermission::checkPermission( $user, "eZSiteManager", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "game":
    {
        switch ( $url_array[3] )
        {
            case "list":
            {
                if ( $url_array[4] == "parent" )
                    $Offset = $url_array[5];
                include( "kernel/ezquiz/admin/gamelist.php" );
            }
            break;
            
            case "edit":
            {
                if ( isset( $url_array[4] ) && is_numeric( $url_array[4] ) )
                {
                    $GameID = $url_array[4];
                    $Action = "edit";
                }
                else
                {
                    $GameID = false;
                }

                include ( "kernel/ezquiz/admin/gameedit.php" );
            }
            break;
            case "new":
            case "delete":
            case "update":
            case "insert":
            {
                if ( isset( $url_array[4] ) && is_numeric( $url_array[4] ) )
                {
                    $GameID = $url_array[4];
                }
                else
                {
                    $GameID = false;
                    $Action = "New";
                }

                include ( "kernel/ezquiz/admin/gameedit.php" );
            }
            break;

            case "questionedit":
            {
                if ( is_numeric( $url_array[4] ) )
                    $QuestionID = $url_array[4];
                include ( "kernel/ezquiz/admin/questionedit.php" );
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