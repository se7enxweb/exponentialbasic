<?php
//
// $Id: datasupplier.php 6225 2001-07-20 11:22:30Z jakobn $
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
if( eZPermission::checkPermission( $user, "eZPoll", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "pollist" :
    {
        if( isset( $DeletePolls ) )
            $Action = "Delete";

        if ( isset( $AddPoll ) )
        {
            include( "ezpoll/admin/polledit.php" );
        }
        else
        {        
            include( "ezpoll/admin/pollist.php" );
        }
    }
    break;

    case "polledit" :
        if ( ( $url_array[3] == "new" ) )
        {
            $Action = "New";
            include( "ezpoll/admin/polledit.php" );
        }
        else if ( ( $url_array[3] == "insert" ) )
        {
            $Action = "Insert";
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "edit" ) )
        {
            $Action = "Edit";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "update" ) )
        {
            $Action = "Update";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        break;
}

?>