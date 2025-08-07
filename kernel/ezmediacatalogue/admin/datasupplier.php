<?php
//
// $Id: datasupplier.php 8182 2001-11-01 17:17:57Z ce $
//
// Created on: <24-Jul-2001 10:59:19 ce>
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
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezmediacatalogue/classes/ezmediacategory.php" );

$ini =& eZINI::instance( 'site.ini' );
$GlobalSectionID = $ini->variable( "eZMediaCatalogueMain", "DefaultSection" );

function writeAtAll()
{
    $user =& eZUser::currentUser();
    if( eZObjectPermission::getObjects( "mediacatalogue_category", 'w', true ) < 1
        && !eZPermission::checkPermission( $user, "eZMediaCatalogue", "WriteToRoot" ) )
    {
        $text = "You do not have write permission to any categories";
        $info = urlencode( $text );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
        exit();
    }
    return true;
}

$user =& eZUser::currentUser();

switch ( $url_array[2] )
{
    case "browse":
    {
        $CategoryID = $url_array[3];
        include( "kernel/ezmediacatalogue/admin/browse.php" );
    }
    break;

    case "mediaview" :
    {
        $MediaID = $url_array[3];
        $VariationID = $url_array[4];
        include( "kernel/ezmediacatalogue/admin/mediaview.php" );
    }
    break;

    case "media" :
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                $CategoryID = $url_array[4];
                if ( !is_numeric($CategoryID ) )
                    $CategoryID = 0;

                if ( $url_array[5] == "parent" )
                    $Offset = $url_array[6];

                include( "kernel/ezmediacatalogue/admin/medialist.php" );
            }
            break;

            case "new" :
            {
                writeAtAll();
                $Action = "New";
                include( "kernel/ezmediacatalogue/admin/mediaedit.php" );
            }
            break;
            
            case "Insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "kernel/ezmediacatalogue/admin/mediaedit.php" );
            }
            break;

            case "edit" :
            {
                $MediaID = $url_array[4];
                $Action = "Edit";
                if( ( eZMedia::isOwner( $user, $MediaID ) ||
                     eZObjectPermission::hasPermission( $MediaID, "mediacatalogue_media", 'w' ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezmediacatalogue/admin/mediaedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;

            case "update" :
            {
                $MediaID = $url_array[4];
                $Action = "Update";
                if( ( eZMedia::isOwner( $user, $MediaID ) ||
                     eZObjectPermission::hasPermission( $MediaID, "mediacatalogue_media", 'w' ) )
                    && writeAtAll() )
                    include( "kernel/ezmediacatalogue/admin/mediaedit.php" );
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;
            default :
            {
                eZHTTPTool::header( "Location: /error/404" );
                exit();
            }
        }
    }
    break;

    case "download" :
    {
        $MediaID = $url_array[3];
        if ( !is_numeric( $MediaID ) )
            $MediaID = 0;
        if ( ( eZMedia::isOwner( $user, $MediaID ) ||
              eZObjectPermission::hasPermission( $MediaID, "mediacatalogue_media", 'r' ) ) )
            include( "kernel/ezmediacatalogue/admin/filedownload.php" );
        else
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }
    }
    break;

    case "slideshow" :
    {
        $CategoryID = $url_array[3];
        if ( !is_numeric( $CategoryID ) )
            $CategoryID = 0;
        $Position = $url_array[4];
        if ( !is_numeric( $Position ) )
            $Position = 0;
        $RefreshTimer = $url_array[5];
        include( "kernel/ezmediacatalogue/admin/slideshow.php" );
    }
    break;

        case "typelist" :
    {
        include( "kernel/ezmediacatalogue/admin/typelist.php" );
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
 
        include( "kernel/ezmediacatalogue/admin/typeedit.php" );
    }
    break;

    
    case "category" :
    {
        switch( $url_array[3] )
        {
            case "list" :
            {
                $CategoryID = $url_array[4];
                if ( !is_numeric($CategoryID ) )
                    $CategoryID = 0;
                $Offset = $url_array[5];
                if ( $Offset == "" )
                    $Offset = 0;
                include( "kernel/ezmediacatalogue/admin/medialist.php" );
            }
            break;

            case "new" :
            {
                writeAtAll();
                $CurrentCategoryID = $url_array[4];
                $Action = "New";
                include( "kernel/ezmediacatalogue/admin/categoryedit.php" );
            }
            break;

            case "insert" :
            {
                writeAtAll();
                $Action = "Insert";
                $CategoryID = $url_array[4];
                include( "kernel/ezmediacatalogue/admin/categoryedit.php" );
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $CategoryID = $url_array[4];
                if( ( eZObjectPermission::hasPermission( $CategoryID, "mediacatalogue_category", 'w' ) ||
                      eZMediaCategory::isOwner( $user, $CategoryID ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezmediacatalogue/admin/categoryedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $CategoryID = $url_array[4];
                if( ( eZObjectPermission::hasPermission( $CategoryID, "mediacatalogue_category", 'w' ) ||
                     eZMediaCategory::isOwner( $user, $CategoryID ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezmediacatalogue/admin/categoryedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403?Info=FUCK" );
                    exit();
                }

            }
            break;


        }
    }
    break;
}

?>