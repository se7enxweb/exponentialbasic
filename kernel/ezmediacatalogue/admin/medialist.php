<?php
// 
// $Id: medialist.php 8182 2001-11-01 17:17:57Z ce $
//
// Created on: <24-Jul-2001 11:36:48 ce>
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
// include_once( "classes/ezfile.php" );
// include_once( "classes/ezlist.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// // include_once( "ezmediacatalogue/classes/ezmedia.php" );
// include_once( "ezmediacatalogue/classes/ezmediacategory.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZMediaCatalogueMain", "Language" );

$ImageDir = $ini->read_var( "eZMediaCatalogueMain", "ImageDir" );

$t = new eZTemplate( "kernel/ezmediacatalogue/admin/" . $ini->read_var( "eZMediaCatalogueMain", "TemplateDir" ),
                     "kernel/ezmediacatalogue/admin/intl/", $Language, "medialist.php" );

$t->set_file( "media_list_page_tpl", "medialist.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();

$t->set_block( "media_list_page_tpl", "current_category_tpl", "current_category" );

// path
$t->set_block( "media_list_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "media_list_page_tpl", "media_list_tpl", "media_list" );

$t->set_block( "media_list_page_tpl", "write_menu_tpl", "write_menu" );

$t->set_block( "write_menu_tpl", "default_new_tpl", "default_new" );
$t->set_block( "write_menu_tpl", "default_delete_tpl", "default_delete" );

$t->set_block( "default_delete_tpl", "delete_categories_button_tpl", "delete_categories_button" );
$t->set_block( "default_delete_tpl", "delete_media_button_tpl", "delete_media_button" );

$t->set_block( "media_list_tpl", "media_tpl", "media" );

$t->set_block( "media_tpl", "read_tpl", "read" );
$t->set_block( "media_tpl", "write_tpl", "write" );

$t->set_block( "media_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_block( "category_tpl", "category_write_tpl", "category_write" );
$t->set_block( "category_tpl", "category_read_tpl", "category_read" );

$t->set_var( "read", "" );
$t->set_var( "write_menu", "" );

$t->set_var( "delete_media_button" , "" );
$t->set_var( "delete_categories_button" , "" );
$t->set_var( "default_new" , "" );
$t->set_var( "default_delete" , "" );

$t->set_var( "site_style", $siteDesign );

$category = new eZMediaCategory( $CategoryID );

// Check if user have permission to the current category

$error = true;

if( !isset( $Offset ) )
    $Offset = false;

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


// ###################################################
function syncDir( $root, $category )
{
  global $user;
  $dir = eZFile::dir( $root, false );
  while ( $entry = $dir->read() )
    {
      if ( $entry != "." && $entry != ".." )
	{
	  if ( filetype( $root . $entry ) == "dir" )
	    {
	      // check if category exists if not create it:
	      $subCategoryArray =& $category->getByParent( $category );
	      $sub = false;
	      foreach ( $subCategoryArray as $subCategory )
		{
		  if ( $subCategory->name() == $entry )
		    {
		      $sub = $subCategory;
		    }
		  //print( $subCategory->name() . "\n<br>" );
		}
	      if ( $sub == false )
		{
		  $sub = new eZMediaCategory(  );
		  $sub->setParent( $category );
		  $sub->setName( $entry );
		  $sub->setSectionID( $SectionID );
		  $sub->store();
		  eZObjectPermission::removePermissions( $sub->id(), "mediacatalogue_category", "r" );
		  eZObjectPermission::removePermissions( $sub->id(), "mediacatalogue_category", "w" );
		  eZObjectPermission::removePermissions( $sub->id(), "mediacatalogue_category", "u" );
		  $group = new eZUserGroup( -1 );
		  eZObjectPermission::setPermission( -1, $sub->id(), "mediacatalogue_category", "r" );
		  eZObjectPermission::setPermission( 1, $sub->id(), "mediacatalogue_category", "w" );
		  eZObjectPermission::setPermission( 1, $sub->id(), "mediacatalogue_category", "u" );
		}
	      //print( "Syncing dir " . $root . $entry . "<br>" );
	      syncDir( $root . $entry . "/", $sub );
	    }
	  else if ( filetype( $root . $entry ) == "file" )
	    {
	      $medias = $category->medias( "time", 0, -1, false, $user );
	      $mediaExists = false;
	      foreach ( $medias as $media )
		{
		  if ( $entry == $media->name() )
		    {
		      $mediaExists = true;
		    }
		}
	      if ( $mediaExists == false )
		{
		  $media = new eZMedia();
		  $media->setName( $entry );
		  $media->setDescription( $Description );
		  $media->setUser( $user );
		  $author = new eZAuthor( 1 );
		  $media->setPhotographer( $author );
		  //print( "adding media: " . $root . $entry . "<br>" );
		  $file = new eZMediaFile();
		  $file->getFile( $root . $entry );
		  $media->store();
		  $media->setMedia( $file, $media->id() );
		  $media->setCategoryDefinition( $category );
		  eZMediaCategory::addMedia( $media, $category->id() );
		  $media->setOriginalFileName( $entry );
		  $media->store();
		  eZObjectPermission::removePermissions( $media->id(), "mediacatalogue_media", "r" );
		  eZObjectPermission::removePermissions( $media->id(), "mediacatalogue_media", "w" );
		  eZObjectPermission::setPermission( -1, $media->id(), "mediacatalogue_media", "r" );
		  eZObjectPermission::setPermission( 1, $media->id(), "mediacatalogue_media", "w" );
		}
	    }
	}
    }
}


if ( isSet ( $MediaUpload ) )
{
  $category = new eZMediaCategory( $PicCat );
  syncDir( $SyncDir, $category );
  eZHTTPTool::header( "Location: /mediacatalogue/media/list/$PicCat/" );
  exit();
}

$t->set_var( "sync_dir", $SyncDir );

// ###################################################

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

    // Check if user have write permission
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $categoryItem->id(), "mediacatalogue_category", "w", $user ) ) ||
         ( eZMediaCategory::isOwner( $user, $categoryItem->id() ) ) )
    {
        $t->parse( "category_write", "category_write_tpl" );
        $t->parse( "delete_categories_button", "delete_categories_button_tpl" );
        $t->parse( "default_delete", "default_delete_tpl" );

        if ( $CategoryID == 0 )
        {
            if ( eZPermission::checkPermission( $user, "eZMediaCatalogue", "WriteToRoot" ) )
            {
                $t->parse( "category_write", "category_write_tpl" );
                $t->parse( "write_menu", "write_menu_tpl" );
            }
        }
        else
        {
            $t->parse( "category_write", "category_write_tpl" );
            $t->parse( "write_menu", "write_menu_tpl" );
        }
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

$limit = $ini->read_var( "eZMediaCatalogueMain", "ListMediaPerPage" );

// Print out all the media
$mediaList =& $category->media( "time", $Offset, $limit );

$i = 0;
$j = 0;
$counter = 0;
foreach ( $mediaList as $media )
{
    $t->set_var( "media_id", $media->id() );
    $t->set_var( "original_media_name", $media->originalFileName() );
    $t->set_var( "media_name", $media->name() );
    $t->set_var( "media_caption", $media->name() );
    $t->set_var( "media_url", $media->name() );

    $t->set_var( "media_description",$media->description() ); 
    $t->set_var( "media_alt", $media->name() );
    $t->set_var( "media_src", "/" . $media->mediaPath() );
    $t->set_var( "media_file_name", $media->originalFileName() );

        if ( ( $counter % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

    if ( $media->fileExists( true ) )
    {
        $mediaPath =& $media->filePath( true );
        $size = eZFile::filesize( $mediaPath );
    }
    else
    {
        $size = 0;
    }

    $size = eZFile::siFileSize( $size );

    $t->set_var( "media_size", $size["size-string"] );
    $t->set_var( "media_unit", $size["unit"] );
    $t->set_var( "media_caption", $media->caption() );

    $t->set_var( "read", "" );
    $t->set_var( "write", "" );

    // Check if user have read permission
    $can_read = false;
    $can_write = false;
    if ( eZObjectPermission::hasPermission( $media->id(), "mediacatalogue_media", "r", $user ) ||
         eZMedia::isOwner( $user, $media->id() ) )
    {
        $can_read = true;
        $t->parse( "read", "read_tpl" );
        $j++;
    }

    // Check if user have write permission
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $media->id(), "mediacatalogue_media", "w", $user ) ) ||
         ( eZMedia::isOwner( $user, $media->id() ) ) )
    {
        $can_write = true;
        $t->parse( "write", "write_tpl" );
        $t->parse( "delete_media_button", "delete_media_button_tpl" );
    }

    if ( $can_read )
        $t->parse( "media", "media_tpl", true );

    $counter++;
}

eZList::drawNavigator( $t, $category->mediaCount(), $limit, $Offset, "media_list_page_tpl" );

if ( isset( $can_write ) && $can_write )
{
    $t->parse( "delete_media_button", "delete_media_button_tpl" );
    $t->parse( "default_delete", "default_delete_tpl" );
}


// Print out the category/media menu
if ( $category->id() != 0 )
{
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $category->id(), "mediacatalogue_category", "w", $user ) ) )
    {
        $t->parse( "default_new", "default_new_tpl" );
        $t->parse( "write_menu", "write_menu_tpl" );
    }
}
else
{
    if ( eZPermission::checkPermission( $user, "eZMediaCatalogue", "WriteToRoot" ) )
    {
        $t->parse( "default_new", "default_new_tpl" );
        $t->parse( "write_menu", "write_menu_tpl" );
    }
}

if ( count( $mediaList ) > 0 )
{
    $t->parse( "media_list", "media_list_tpl" );
}
else
{
    $t->set_var( "media_list", "" );
}

$t->set_var( "media_dir", $ImageDir );

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