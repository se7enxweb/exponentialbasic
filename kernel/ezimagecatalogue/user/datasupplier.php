<?php
//
// $Id: datasupplier.php 9623 2002-06-11 08:20:30Z jhe $
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
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini =& eZINI::instance( 'site.ini' );
$GlobalSectionID = $ini->variable( "eZImageCatalogueMain", "DefaultSection" );
$UserComments = $ini->variable( "eZImageCatalogueMain", "UserComments" );

function writeAtAll()
{
    $user =& eZUser::currentUser();
    if( eZObjectPermission::getObjects( "imagecatalogue_category", 'w', true ) < 1
        && eZObjectPermission::getObjects( "imagecatalogue_category", 'u', true ) < 1
        && !eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot" ) )
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
    case "customimage" :
    {
        $ImageID = $url_array[3];
        $ImageWidth = $url_array[4];
        $ImageHeight = $url_array[5];
        include( "kernel/ezimagecatalogue/user/customimage.php" );
    }
    break;

    case "imageview" :
    {
        $ImageID = $url_array[3];
        $VariationID = $url_array[4];
        include( "kernel/ezimagecatalogue/user/imageview.php" );

        if  ( isset( $PrintableVersion ) && ( $PrintableVersion != "enabled" ) &&  ( $UserComments == "enabled" ) )
        {
            $RedirectURL = "/imagecatalogue/imageview/$ImageID/";
            $image = new eZImage ( $ImageID );
            if ( ( $image->id() >= 1 ) )    //  && $product->discuss() )
            {
                for ( $i = 0; $i < count( $url_array ); $i++ )
                {
                    if ( ( $url_array[$i] ) == "parent" )
                    {
                        $next = $i + 1;
                        $Offset = $url_array[$next];
                    }
                }
                $forum = $image->forum();
                $ForumID = $forum->id();
                include( "kernel/ezforum/user/messagesimplelist.php" );
            }
        }
    }
    break;

    case "search" :
    {
        $CategoryID = $url_array[3];

        if ( !is_numeric( $CategoryID ) )
            $CategoryID = 0;

        if( isset( $url_array[4] ) )    
        {
            $Offset = $url_array[4];
        }
        else
        {
            $Offset = 0;
        }   

        include( "kernel/ezimagecatalogue/user/imagelist.php" );
        
    }
    break;


    case "image" :
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                if ( isset( $url_array[4] ) )
                {
                    $CategoryID = $url_array[4];
                }
                else
                {
                    $CategoryID = 0;
                }
                
                if ( !is_numeric($CategoryID ) )
                {
                    $CategoryID = 0;
                }
                
                if( isset( $url_array[6] ) )    
                {
                    $Offset = $url_array[6];
                }
                else
                {
                    $Offset = 0;
                }   

                if ( $Offset == "" && is_Numeric( $url_array[4] ) && is_Numeric( $url_array[5] ) )
                {
                    $Offset = $url_array[5];
                }
                else if ( $Offset == "" )
                {
                    $Offset = 0;
                }
                include( "kernel/ezimagecatalogue/user/imagelist.php" );
            }
            break;

            case "new" :
            {
                writeAtAll();
                $Action = "New";
                include( "kernel/ezimagecatalogue/user/imageedit.php" );
            }
            break;
            
            case "Insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "kernel/ezimagecatalogue/user/imageedit.php" );
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
                    include( "kernel/ezimagecatalogue/user/imageedit.php" );
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
                    include( "kernel/ezimagecatalogue/user/imageedit.php" );
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
            include( "kernel/ezimagecatalogue/user/filedownload.php" );
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
        include( "kernel/ezimagecatalogue/user/slideshow.php" );
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
                include( "kernel/ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "kernel/ezimagecatalogue/user/categoryedit.php" );
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
                    include( "kernel/ezimagecatalogue/user/categoryedit.php" );
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
                    include( "kernel/ezimagecatalogue/user/categoryedit.php" );
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