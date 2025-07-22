<?php
//
// $Id: datasupplier.php 9501 2002-05-02 17:09:58Z br $
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
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );

function writeAtAll()
{
    $user =& eZUser::currentUser();
    if( eZObjectPermission::getObjects( "imagecatalogue_category", 'w', true ) < 1
        && !eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot" ) )
    {
        $text = "You do not have write permission to any categories";
        $info = urlencode( $text );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
        exit();
    }
    return true;
}


switch ( $url_array[2] )
{
        case "customimage" :
    {
        $ImageID = $url_array[3];
        $ImageWidth = $url_array[4];
        $ImageHeight = $url_array[5];
        include( "kernel/ezimagecatalogue/admin/customimage.php" );
    }
    break;

    case "search" :
    {
        include( "kernel/ezimagecatalogue/admin/imagelist.php" );
    }
    break;

    case "browse":
    {
        $CategoryID = $url_array[3];
        include( "kernel/ezimagecatalogue/admin/browse.php" );
    }
    break;

    case "browsesearch":
    {
        include( "kernel/ezimagecatalogue/admin/browse.php" );
    }
    break;
    
    case "unassigned":
    {
        $Offset = $url_array[3];
        $Limit = $url_array[4];
        include( "kernel/ezimagecatalogue/admin/unassigned.php" );
    }
    break;

    case "imageview" :
    {
        $ImageID = $url_array[3];
        $VariationID = $url_array[4];
        include( "kernel/ezimagecatalogue/admin/imageview.php" );
    }
    break;

	case "import" :
    {
        include( "kernel/ezimagecatalogue/admin/imagelist.php" );
    }
    break;

    case "image" :
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                $CategoryID = $url_array[4];
                if ( !is_numeric($CategoryID ) )
                    $CategoryID = 0;
                $Offset = $url_array[6];

                if ( $Offset == "" && is_Numeric( $url_array[4] ) && is_Numeric( $url_array[5] ) )
                {
                    $Offset = $url_array[5];
                }
                else if ( $Offset == "" )
                {
                    $Offset = 0;
                }
                include( "kernel/ezimagecatalogue/admin/imagelist.php" );
            }
            break;

            case "new" :
            {
                writeAtAll();
                $Action = "New";
                include( "kernel/ezimagecatalogue/admin/imageedit.php" );
            }
            break;
            
            case "Insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "kernel/ezimagecatalogue/admin/imageedit.php" );
            }
            break;

            case "edit" :
            {
                $ImageID = $url_array[4];
                $Action = "Edit";
                if( ( eZImage::isOwner( $user, $ImageID ) ||
                     eZObjectPermission::hasPermission( $ImageID, "imagecatalogue_image", 'w' ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezimagecatalogue/admin/imageedit.php" );
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
                $ImageID = $url_array[4];
                $Action = "Update";
                if( ( eZImage::isOwner( $user, $ImageID ) ||
                     eZObjectPermission::hasPermission( $ImageID, "imagecatalogue_image", 'w' ) )
                    && writeAtAll() )
                    include( "kernel/ezimagecatalogue/admin/imageedit.php" );
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
        $ImageID = $url_array[3];
        if ( !is_numeric( $ImageID ) )
            $ImageID = 0;
        if ( ( eZImage::isOwner( $user, $ImageID ) ||
              eZObjectPermission::hasPermission( $ImageID, "imagecatalogue_image", 'r' ) ) )
            include( "kernel/ezimagecatalogue/admin/filedownload.php" );
        else
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }
    }
    break;

    case "category" :
    {
        switch( $url_array[3] )
        {
           
            case "new" :
            {
                writeAtAll();
                $CurrentCategoryID = $url_array[4];
                $Action = "New";
                include( "kernel/ezimagecatalogue/admin/categoryedit.php" );
            }
            break;

            case "insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "kernel/ezimagecatalogue/admin/categoryedit.php" );
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $CategoryID = $url_array[4];
                if( ( eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", 'w' ) ||
                      eZImageCategory::isOwner( $user, $CategoryID ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezimagecatalogue/admin/categoryedit.php" );
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
                if( ( eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", 'w' ) ||
                     eZImageCategory::isOwner( $user, $CategoryID ) )
                    && writeAtAll() )
                {
                    include( "kernel/ezimagecatalogue/admin/categoryedit.php" );
                }
                else
                {
                    $info= urlencode( "You have no permission to update categories" );
                    eZHTTPTool::header( "Location: /error/403?Info=$info" );
                    exit();
                }

            }
            break;
        }
    }
    break;

    
    default:
        $info = urlencode( "This page does not exist!" );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
    
}

?>