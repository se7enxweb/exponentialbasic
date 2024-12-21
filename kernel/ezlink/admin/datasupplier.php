<?php
// 
// $Id: datasupplier.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <29-Jun-2001 13:57:58 bf>
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

// $url_array = explode( "/", $REQUEST_URI );

$user =& eZUser::currentUser();
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "classes/ezhttptool.php" );

if( eZPermission::checkPermission( $user, "eZLink", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "" :
    {
        include( "kernel/ezlink/admin/linkcategorylist.php" );
    }
    break;
    case "link" :
    {
        $LID = $url_array[3];
        include( "kernel/ezlink/admin/linkcategorylist.php" );
    }
    break;

    case "typelist" :
    {
        include( "kernel/ezlink/admin/typelist.php" );
    }
    break;
 
    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $TypeID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $TypeID = $url_array[4];
            $Action = "Delete";
        }
        if ( $url_array[3] == "up" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "up";
        }
        if ( $url_array[3] == "down" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "down";
        }
 
        include( "kernel/ezlink/admin/typeedit.php" );
    }
    break;
    
    case "category" :
    {
        if ( $url_array[4] == "parent" )
        {
            $Offset = $url_array[5];
            if ( !is_numeric( $Offset ) )
                $Offset = 0;
        }
        $LinkCategoryID = $url_array[3];
        include( "kernel/ezlink/admin/linkcategorylist.php" );
    }
    break;

    case "unacceptedlist":
    {
        if ( $url_array[3] )
            $Offset = $url_array[3];
        include( "kernel/ezlink/admin/unacceptedlist.php" );
    }
    break;
    case "unacceptededit":
    {
        include( "kernel/ezlink/admin/unacceptededit.php" );
    }
    break;
    
    case "linkedit" :
    {
        switch ( $url_array[3] )
        {
            case "new" :
            {
                $Action = "new";
                include( "kernel/ezlink/admin/linkedit.php" );
            }
            break;
            
            case "insert" :
            {
                $LinkID = $url_array[4];
                if ( isSet( $Update ) )
                {
                    $Action = "AttributeList";
                }
                else
                {
                    $Action = "insert";
                }
                include( "kernel/ezlink/admin/linkedit.php" );
            }
            break;
            
            case "edit" :
            {
                $LinkID = $url_array[4];
                $Action = "edit";
                include( "kernel/ezlink/admin/linkedit.php" );
            }
            break;
            
            case "update" :
            {
                $LinkID = $url_array[4];
                if ( isSet( $Update ) )
                {
                    $Action = "AttributeList";
                }
                else
                {
                    $Action = "update";
                }
                include( "kernel/ezlink/admin/linkedit.php" );
            }
            break;
            
            case "delete" :
            {
                $LinkID = $url_array[4];
                $Action = "delete";
                include( "kernel/ezlink/admin/linkedit.php" );
            }
            break;
            
            case "attributeedit" :
            {
                $LinkID = $url_array[4];
                include( "kernel/ezlink/admin/attributeedit.php" );
            }
            break;
        }
    }
    break;

    case "categoryedit" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "kernel/ezlink/admin/categoryedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $LinkCategoryID = $url_array[4];
            $Action = "insert";
            include( "kernel/ezlink/admin/categoryedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $LinkCategoryID = $url_array[4];
            $Action = "edit";
            include( "kernel/ezlink/admin/categoryedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $LinkCategoryID = $url_array[4];
            $Action = "update";
            include( "kernel/ezlink/admin/categoryedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $LinkCategoryID = $url_array[4];
            $Action = "delete";
            include( "kernel/ezlink/admin/categoryedit.php" );
        }
    }
    break;
    case "testbench" :
        include( "kernel/eztrade/admin/testbench.php" );
        break;
    case "search" :
    {
        if ( $url_array[3] == "parent" )
        {
            $QueryString = urldecode( $url_array[4] );
            $Offset = $url_array[5];
        }
        include( "kernel/ezlink/admin/search.php" );
    }
        break;
    case "norights" :
        include( "kernel/ezlink/admin/norights.php" );        
        break;
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
        include( "kernel/ezlink/admin/gotolink.php" );
    }
    break;


    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
