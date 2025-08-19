<?php
//
// $Id: datasupplier.php 9460 2002-04-24 07:23:43Z bf $
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



// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "classes/INIFile.php" );

$user =& eZUser::currentUser();

if ( is_a( $user, "eZUser" )  and eZPermission::checkPermission( $user, "eZUser", "ModuleEdit" ) == true)
{
    // These should only be available if the user has permissions...
    switch ( $url_array[2] )
    {
        case "" :
        {
            include( "kernel/ezuser/admin/welcome.php" );
        }
        break;

        case "welcome" :
        {
            include( "kernel/ezuser/admin/welcome.php" );
        }
        break;

        case "extsearch" :
        {
            include( "kernel/ezuser/admin/extsearch.php" );
        }
        break;

        case "authorlist" :
        {
            include( "kernel/ezuser/admin/authorlist.php" );
        }
        break;

        case "photographerlist" :
        {
            include( "kernel/ezuser/admin/photographerlist.php" );
        }
        break;

        case "sessioninfo" :
        {
            if ( $url_array[3] == "delete" )
            {
                $Action = "Delete";
                $SessionID = $url_array[4];
            }
            include( "kernel/ezuser/admin/sessioninfo.php" );
        }
        break;

        // hack: Two methods to access the userlist. Through direct GroupID here, or by indexes (under)
        case "ingroup" :
        {
            $GroupID = $url_array[3];
            $Index = 0;
            include( "kernel/ezuser/admin/userlist.php" );
        }
        break;
        // end hack

        case "userlist" :
        {
            $Index = $url_array[3];
            $OrderBy = $url_array[4];
            if ( !isset( $GroupID ) )
                $GroupID = $url_array[5];
            include( "kernel/ezuser/admin/userlist.php" );
        }
        break;

        case "grouplist" :
        {
            include( "kernel/ezuser/admin/grouplist.php" );
        }
        break;

        case "useredit" :
        {
            if ( $url_array[3] == "new" )
            {
                $Action = "new";
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "insert" )
            {
                $Action = "insert";
                include( "kernel/ezuser/admin/useredit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $Action = "edit";
                $UserID = $url_array[4];
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $Action = "update";
                $UserID = $url_array[4];
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $Action = "delete";
                $UserID = $url_array[4];
                include( "kernel/ezuser/admin/useredit.php" );
            }
        }
        break;

        case "groupedit" :
        {
            if ( $url_array[3] == "new" )
            {
                include( "kernel/ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "insert" )
            {
                $Action = "insert";
                include( "kernel/ezuser/admin/groupedit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $Action = "edit";
                $GroupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $Action = "update";
                $GroupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $Action = "delete";
                $GroupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
        }
        break;

        case "login" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $ini =& eZINI::instance( 'site.ini' );
            $DemoSite = $ini->variable( "site", "DemoSite" );

            if ( $DemoSite == "enabled" ) {
                print("<div align='center'>This is a demosite only. You are not allowed to change the admin password!</div>\n");
            }else{
                $Action = $url_array[3];
                include( "kernel/ezuser/admin/passwordchange.php" );
            }

        }
        break;

        case "settings" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/settings.php" );
        }
        break;

        default :
        {
            eZHTTPTool::header( "Location: /error/403" );
            exit();
        }
        break;
    }
}
else
{
    // These should allways be available
    switch( $url_array[2] )
    {
        case "login" :
        {
            if ( isset( $url_array[3] ) )
                $Action = $url_array[3];
            else
                $Action = "";
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/passwordchange.php" );
        }
        break;

        case "settings" :
        {
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/settings.php" );
        }
        break;

        default :
        {
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

    }
}

?>