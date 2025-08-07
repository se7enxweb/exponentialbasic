<?php
//
// $Id: datasupplier.php 8099 2001-10-30 17:35:04Z fh $
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZBulkMail", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "categorylist":
    {
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if( $Offset == "" )
            $Offset = 0;
        include( "kernel/ezbulkmail/admin/categorylist.php" );
    }
    break;

    case "categoryedit" :
    {
        $CategoryID = $url_array[3];
        if( !is_numeric( $CategoryID ) )
            $CategoryID = 0;
        include( "kernel/ezbulkmail/admin/categoryedit.php" );
    }
    break;

    case "templatelist" :
    {
        include( "kernel/ezbulkmail/admin/templatelist.php" );
    }
    break;

    case "templateedit" :
    {
        $TemplateID = $url_array[3];
        if( !is_numeric( $TemplateID ) )
            $TemplateID = 0;
        include( "kernel/ezbulkmail/admin/templateedit.php" );
    }
    break;

    case "mailedit" :
    {
        $MailID = $url_array[3];
        if( !is_numeric( $MailID ) )
            $MailID = 0;
        include( "kernel/ezbulkmail/admin/mailedit.php" );
    }
    break;

    case "drafts" :
    {
        include( "kernel/ezbulkmail/admin/maillist.php" );
    }
    break;

    case "send" :
        $SendButton = true;
    case "preview" :
        $EditButton = true;
    case "view" :
    {
        $MailID = $url_array[3];
        if( !is_numeric( $MailID ) )
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }
        include( "kernel/ezbulkmail/admin/mailview.php" );
    }
    break;


    case "masssubscribe":
    {
        if ( $ini->variable( "eZBulkMailMain", "UseEZUser" ) == "enabled" )
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }
        include( "kernel/ezbulkmail/admin/masssubscribe.php" );
    }
    break;

    case "userlist":
    {
        $CategoryID = $url_array[3];
        if( !is_numeric( $CategoryID ) )
            $CategoryID = 0;
        include( "kernel/ezbulkmail/admin/userlist.php" );
    }
    break;

    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>