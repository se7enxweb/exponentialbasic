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

$user = eZUser::currentUser();

$accountNumber          = eZHTTPTool::getVar( 'AccountNumber' );
$action                 = eZHTTPTool::getVar( 'Action' );
$actionValue            = eZHTTPTool::getVar( 'ActionValue' );
$allowSimultaneousLogins = eZHTTPTool::getVar( 'AllowSimultaneousLogins' );
$back                   = eZHTTPTool::getVar( 'Back' );
$cancel                 = eZHTTPTool::getVar( 'Cancel' );
$deleteAuthor           = eZHTTPTool::getVar( 'DeleteAuthor' );
$deleteGroups           = eZHTTPTool::getVar( 'DeleteGroups' );
$deleteIDArray          = eZHTTPTool::getVar( 'DeleteIDArray' ) ?? [];
$description            = eZHTTPTool::getVar( 'Description' );
$eMail                  = eZHTTPTool::getVar( 'EMail' );
$email                  = eZHTTPTool::getVar( 'Email' );
$firstName              = eZHTTPTool::getVar( 'FirstName' );
$groupArray             = eZHTTPTool::getVar( 'GroupArray' ) ?? [];
$groupArrayID           = eZHTTPTool::getVar( 'GroupArrayID' ) ?? [];
$groupID                = eZHTTPTool::getVar( 'GroupID' );
$groupURL               = eZHTTPTool::getVar( 'GroupURL' );
$idArray                = eZHTTPTool::getVar( 'IDArray' ) ?? [];
$id                     = eZHTTPTool::getVar( 'Id' );
$index                  = eZHTTPTool::getVar( 'Index' );
$infoSubscription       = eZHTTPTool::getVar( 'InfoSubscription' );
$isRoot                 = eZHTTPTool::getVar( 'IsRoot' );
$language               = eZHTTPTool::getVar( 'Language' );
$lastName               = eZHTTPTool::getVar( 'LastName' );
$login                  = eZHTTPTool::getVar( 'Login' );
$mainGroup              = eZHTTPTool::getVar( 'MainGroup' );
$max                    = eZHTTPTool::getVar( 'Max' );
$maxLogins              = eZHTTPTool::getVar( 'MaxLogins' );
$moduleTabBar           = eZHTTPTool::getVar( 'ModuleTabBar' );
$name                   = eZHTTPTool::getVar( 'Name' );
$newAuthor              = eZHTTPTool::getVar( 'NewAuthor' );
$newPassword            = eZHTTPTool::getVar( 'NewPassword' );
$oldPassword            = eZHTTPTool::getVar( 'OldPassword' );
$orderBy                = eZHTTPTool::getVar( 'OrderBy' );
$password               = eZHTTPTool::getVar( 'Password' );
$permissionArray        = eZHTTPTool::getVar( 'PermissionArray' ) ?? [];
$permissionID           = eZHTTPTool::getVar( 'PermissionID' );
$phone                  = eZHTTPTool::getVar( 'Phone' );
$refererURL             = eZHTTPTool::getVar( 'RefererURL' );
$search                 = eZHTTPTool::getVar( 'Search' );
$searchText             = eZHTTPTool::getVar( 'SearchText' );
$sessionArrayID         = eZHTTPTool::getVar( 'SessionArrayID' ) ?? [];
$sessionID              = eZHTTPTool::getVar( 'SessionID' );
$sessionTimeout         = eZHTTPTool::getVar( 'SessionTimeout' );
$signature              = eZHTTPTool::getVar( 'Signature' );
$simultaneousLogins     = eZHTTPTool::getVar( 'SimultaneousLogins' );
$singleModule           = eZHTTPTool::getVar( 'SingleModule' );
$siteDesign             = eZHTTPTool::getVar( 'SiteDesign' ) ?? $siteDesign;
$store                  = eZHTTPTool::getVar( 'Store' );
$totalTypes             = eZHTTPTool::getVar( 'TotalTypes' );
$userArrayID            = eZHTTPTool::getVar( 'UserArrayID' ) ?? [];
$userID                 = eZHTTPTool::getVar( 'UserID' );
$username               = eZHTTPTool::getVar( 'Username' );
$verifyPassword         = eZHTTPTool::getVar( 'VerifyPassword' );

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
                $action = "Delete";
                $sessionID = $url_array[4];
            }
            include( "kernel/ezuser/admin/sessioninfo.php" );
        }
        break;

        // hack: Two methods to access the userlist. Through direct GroupID here, or by indexes (under)
        case "ingroup" :
        {
            $groupID = $url_array[3];
            $index = 0;
            include( "kernel/ezuser/admin/userlist.php" );
        }
        break;
        // end hack

        case "userlist" :
        {
            $index = $url_array[3];
            $orderBy = $url_array[4];
            if ( !isset( $groupID ) )
                $groupID = $url_array[5];
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
                $action = "new";
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "insert" )
            {
                $action = "insert";
                include( "kernel/ezuser/admin/useredit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $action = "edit";
                $userID = $url_array[4];
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $action = "update";
                $userID = $url_array[4];
                include( "kernel/ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $action = "delete";
                $userID = $url_array[4];
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
                $action = "insert";
                include( "kernel/ezuser/admin/groupedit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $action = "edit";
                $groupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $action = "update";
                $groupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $action = "delete";
                $groupID = $url_array[4];
                include( "kernel/ezuser/admin/groupedit.php" );
            }
        }
        break;

        case "login" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $ini = eZINI::instance( 'site.ini' );
            $demoSite = $ini->variable( "site", "DemoSite" );

            if ( $demoSite == "enabled" ) {
                print("<div align='center'>This is a demosite only. You are not allowed to change the admin password!</div>\n");
            }else{
                $action = $url_array[3];
                include( "kernel/ezuser/admin/passwordchange.php" );
            }

        }
        break;

        case "settings" :
        {
            $action = $url_array[3];
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
                $action = $url_array[3];
            else
                $action = "";
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $action = $url_array[3];
            include( "kernel/ezuser/admin/passwordchange.php" );
        }
        break;

        case "settings" :
        {
            $action = $url_array[3];
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