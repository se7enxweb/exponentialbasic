<?php
//
// $Id: datasupplier.php 7663 2001-10-04 14:31:14Z bf $
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


$ini =& INIFile::globalINI();
if ( isset( $GlobalSectionIDOverride ) )
{
    $GlobalSectionID = $GlobalSectionIDOverride;
}
else
{
    $GlobalSectionID = $ini->read_var( "eZUserMain", "DefaultSection" );
}

switch ( $url_array[2] )
{

    case "login" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/login.php" );
    }
    break;

    case "loginmain" :
    {
        $Action = $url_array[3];
        include( "kernel/ezuser/user/loginmain.php" );
    }
    break;

    case "norights":
    {
        include( "kernel/ezuser/user/norights.php" );        
    }
    break;

    case "user" :
    case "userwithaddress" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }
        if ( $url_array[3] == "edit" )
        {
            if ( $url_array[5] == "MissingAddress" )
                $MissingAddress = true;
            else
                $MissingAddress = false;
            if ( $url_array[5] == "MissingCountry" )
                $MissingCountry = true;
            else
                $MissingCountry = false;

            $UserID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "update" )
        {
            $UserID = $url_array[4];
            $Action = "Update";
        }

        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        $OverrideUserWithAddress = $ini->read_var( "eZUserMain", "OverrideUserWithAddress" );

        if ( empty( $OverrideUserWithAddress ) )
        {
            include( "kernel/ezuser/user/userwithaddress.php" );
        }
        else
        {
            if ( is_file( $OverrideUserWithAddress ) )
            {
                include( $OverrideUserWithAddress );
            }
            else
            {
                include( "kernel/ezuser/user/userwithaddress.php" );
            }
        }

    }
    break;

    case "forgot" :
    {
        $Action = $url_array[3];
        $Hash = $url_array[4];
        include( "kernel/ezuser/user/forgot.php" );
    }
    break;

    case "address" :
    {
        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
        }
        
        include( "kernel/ezuser/user/addressedit.php" );
    }
    break;

    case "logout" :
    {
        $Action = $url_array[2];
        include( "kernel/ezuser/user/login.php" );
    }
    break;

    case "unsuccessfull":
    {
        $unsuccessfull = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "successfull":
    {
        $successfull = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "missingemail":
    {
        $successfull = true;
        include( "kernel/ezuser/user/missingemailmessage.php" );
    }
    break;

    case "generated":
    {
        $generated = true;
        include( "kernel/ezuser/user/forgotmessage.php" );
    }
    break;

    case "passwordchange" :
    {
        $ini =& INIFile::globalINI();
        $DemoSite = $ini->read_var( "site", "DemoSite" );

        if ( $DemoSite == "enabled" ) {
            print("<div align='center'>This is a demosite only. You are not allowed to change the admin password!</div>\n");
        }else{
            $Action = $url_array[3];
            include( "kernel/ezuser/admin/passwordchange.php" );
        }

    }
    break;

    default :
    {
        include( "kernel/ezuser/user/login.php" );
    }
    break;


}
?>