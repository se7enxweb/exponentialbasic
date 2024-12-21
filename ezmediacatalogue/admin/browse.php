<?php
// 
// $Id: browse.php 6279 2001-07-25 12:32:20Z ce $
//
// Christoffer A. Elo
// Created on: <25-Jul-2001 13:11:54 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// include_once( "ezmediacatalogue/classes/ezmedia.php" );
// include_once( "ezmediacatalogue/classes/ezmediacategory.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZMediaCatalogueMain", "Language" );

$MediaDir = $ini->read_var( "eZMediaCatalogueMain", "ImageDir" );

$t = new eZTemplate( "ezmediacatalogue/admin/" . $ini->read_var( "eZMediaCatalogueMain", "AdminTemplateDir" ),
                     "ezmediacatalogue/admin/intl/", $Language, "browse.php" );

$t->set_file( "media_list_page_tpl", "browse.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();


$session =& eZSession::globalSession();

$returnUrl = $session->variable( "MediaListReturnTo" );

$t->set_var( "action_url", $returnUrl );

$t->set_block( "media_list_page_tpl", "current_category_tpl", "current_category" );

// path
$t->set_block( "media_list_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "media_list_page_tpl", "media_list_tpl", "media_list" );

$t->set_block( "media_list_tpl", "detail_view_tpl", "detail_view" );

$t->set_block( "detail_view_tpl", "detail_read_tpl", "detail_read" );
$t->set_block( "detail_view_tpl", "detail_write_tpl", "detail_write" );

$t->set_block( "detail_read_tpl", "single_media_tpl", "single_media" );
$t->set_block( "detail_read_tpl", "multi_media_tpl", "multi_media" );

$t->set_block( "media_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_block( "category_tpl", "category_read_tpl", "category_read" );

$t->set_var( "read", "" );
$t->set_var( "write_menu", "" );

$t->set_var( "delete_media_button" , "" );
$t->set_var( "delete_categories_button" , "" );
$t->set_var( "default_new" , "" );
$t->set_var( "default_delete" , "" );

if ( !is_numeric( $CategoryID ) )
    $CategoryID = 0;
    
$category = new eZMediaCategory( $CategoryID );

// Check if user have permission to the current category

$error = true;

if ( eZObjectPermission::hasPermission( $category->id(), "mediacatalogue_category", "r", $user )
     || eZMediaCategory::isOwner( $user, $CategoryID ) )
{
    $error = false;
}

if ( $CategoryID == 0 )
{
    $t->set_var( "current_category_description", "" );
    $error = false;
}

$t->set_var( "current_category", "" );

if ( $category->id() != 0 )
{
    $t->set_var( "current_category_description", $category->description() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
    
    $t->parse( "current_category", "current_category_tpl" );
}

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}


// Print out all the categories
$categoryList =& $category->getByParent( $category );

$i=0;
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_name", $categoryItem->name() );
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_description", $categoryItem->description() );

    $t->set_var( "category_read", "" );
    $t->set_var( "category_write", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );

    // Check if user have read permission
    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "mediacatalogue_category", "r", $user ) ||
         eZMediaCategory::isOwner( $user, $categoryItem->id()) )
    {
        $t->parse( "category_read", "category_read_tpl" );
    }

    $t->parse( "category", "category_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )
{
    $t->parse( "category_list", "category_list_tpl" );
}
else
{
    $t->set_var( "category_list", "" );
}

// Print out all the media
$mediaList =& $category->media();

$selectMedia = $session->variable( "SelectMedia" );
if( !$selectMedia )
    $selectMedia = "single";

$t->set_var( "name", $session->variable( "NameInBrowse" ) );

$i = 0;
$counter = 0;
foreach ( $mediaList as $media )
{
    $t->set_var( "end_tr", "" );        
    $t->set_var( "begin_tr", "" );

    $t->set_var( "media_id", $media->id() );
    $t->set_var( "original_media_name", $media->originalFileName() );
    $t->set_var( "media_name", $media->name() );
    $t->set_var( "media_caption", $media->name() );
    $t->set_var( "media_url", $media->name() );

    if ( $selectMedia == "single" )
    {
        $t->set_var( "media_id", $media->id() );
        $t->set_var( "multi_media", "" );
        $t->parse( "single_media", "single_media_tpl" );
    }
    elseif ( $selectMedia == "multi" )
    {
        $t->set_var( "media_id", $media->id() );
        $t->set_var( "single_media", "" );
        $t->parse( "multi_media", "multi_media_tpl" );
    }

    $t->set_var( "media_description",$media->description() ); 
    $t->set_var( "media_alt", $media->name() );
    $t->set_var( "media_file_name", $media->originalFileName() );

    $t->set_var( "read", "" );
    $t->set_var( "write", "" );

    // Check if user have read permission
    $t->set_var( "detail_read", "" );
    $can_read = false;
    $can_write = false;
    if ( eZObjectPermission::hasPermission( $media->id(), "mediacatalogue_media", "r", $user ) ||
         eZMedia::isOwner( $user, $media->id() ) )
    {
        $t->parse( "detail_read", "detail_read_tpl" );
        $i++;
    }


    $t->parse( "detail_view", "detail_view_tpl", true );

    $counter++;
}

$t->set_var( "detail_button", "" );
$t->set_var( "normal_button", "" );

if ( count( $mediaList ) > 0 )
{
    $t->parse( "media_list", "media_list_tpl" );
}
else
{
    $t->set_var( "normal_button", "" );
    $t->set_var( "detail_button", "" );
    $t->set_var( "media_list", "" );
}

$t->set_var( "media_dir", $MediaDir );

$t->set_var( "main_category_id", $CategoryID );

if ( $error == false )
{
    $t->pparse( "output", "media_list_page_tpl" );
}
else
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}


?>

