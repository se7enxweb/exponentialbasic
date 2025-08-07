<?php
// 
// $Id: imagelist.php 9501 2002-05-02 17:09:58Z br $
//
// Created on: <10-Dec-2000 16:16:20 bf>
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

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& eZINI::instance( 'site.ini' );
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$Language = $ini->variable( "eZImageCatalogueMain", "Language" );

$ImageDir = $ini->variable( "eZImageCatalogueMain", "ImageDir" );
$PicCat = $ini->variable( "eZImageCatalogueMain", "PicCat" );
$SyncDir = $ini->variable( "eZImageCatalogueMain", "SyncDir" );
$SectionID = $ini->variable( "eZImageCatalogueMain", "DefaultSection" );

$t = new eZTemplate( "kernel/ezimagecatalogue/admin/" . $ini->variable( "eZImageCatalogueMain", "AdminTemplateDir" ),
                     "kernel/ezimagecatalogue/admin/intl/", $Language, "imagelist.php" );

$t->set_file( "image_list_page_tpl", "imagelist.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();

// Set detail or normal mode
if ( isset ( $DetailView ) )
{
    $session =& eZSession::globalSession();
    $session->setVariable( "ImageViewMode", "Detail" );
}
if ( isset ( $NormalView ) )
{
    $session =& eZSession::globalSession();
    $session->setVariable( "ImageViewMode", "Normal" );
}


function syncDir( $root, $category )
	{
    	global $user;
	    $dir = eZPBFile::dir( $root, false );
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
        	            $sub = new eZImageCategory(  );
            	        $sub->setParent( $category );
                	    $sub->setName( $entry );
					    $sub->setSectionID( $SectionID );
                    	$sub->store();
	                    eZObjectPermission::removePermissions( $sub->id(), "imagecatalogue_category", "r" );
    	                eZObjectPermission::removePermissions( $sub->id(), "imagecatalogue_category", "w" );
	                    eZObjectPermission::removePermissions( $sub->id(), "imagecatalogue_category", "u" );
	                    $group = new eZUserGroup( -1 );
			            eZObjectPermission::setPermission( -1, $sub->id(), "imagecatalogue_category", "r" );
	                    eZObjectPermission::setPermission( 1, $sub->id(), "imagecatalogue_category", "w" );
    	                eZObjectPermission::setPermission( 1, $sub->id(), "imagecatalogue_category", "u" );
        	        }
            	    //print( "Syncing dir " . $root . $entry . "<br>" );
                	syncDir( $root . $entry . "/", $sub );
            	}
            	else if ( filetype( $root . $entry ) == "file" )
            	{
                	$images = $category->images( "time", 0, -1, false, $user );
	                $imageExists = false;
	                foreach ( $images as $image )
    	            {
        	            if ( $entry == $image->name() )
            	        {
                	        $imageExists = true;
                    	}
      	}
	                if ( $imageExists == false )
    	  {
        	 $image = new eZImage();
             $image->setName( $entry );
             $image->setDescription( $Description );
             $image->setUser( $user );
             $author = new eZAuthor( 1 );
             $image->setPhotographer( $author );
             //print( "adding image: " . $root . $entry . "<br>" );
             $file = new eZImageFile();
             $file->getFile( $root . $entry );
             $image->store();
             $image->setImage( $file, $image->id() );
             $image->setCategoryDefinition( $category );
             eZImageCategory::addImage( $image, $category->id() );
			$image->setOriginalFileName( $entry );
            $image->store();
            eZObjectPermission::removePermissions( $image->id(), "imagecatalogue_image", "r" );
            eZObjectPermission::removePermissions( $image->id(), "imagecatalogue_image", "w" );
            eZObjectPermission::setPermission( -1, $image->id(), "imagecatalogue_image", "r" );
            eZObjectPermission::setPermission( 1, $image->id(), "imagecatalogue_image", "w" );
         }
      }
   }
 }
}

if ( isSet ( $ImageUpload ) )
{
	$category = new eZImageCategory( $PicCat );
	syncDir( $SyncDir, $category );
	eZHTTPTool::header( "Location: /imagecatalogue/image/list/$PicCat/" );
    exit();
}


$checkMode =& eZSession::globalSession();

if ( $checkMode->variable( "ImageViewMode" ) == "Detail" )
{
    $DetailView = true;
}
else if ( $checkMode->variable( "ImageViewMode" ) == "Normal" )
{
    $NormalView = true;
}

$t->set_block( "image_list_page_tpl", "current_category_tpl", "current_category" );

// path
$t->set_block( "image_list_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "image_list_page_tpl", "image_list_tpl", "image_list" );

$t->set_block( "image_list_page_tpl", "normal_view_button", "normal_button" );
$t->set_block( "image_list_page_tpl", "detail_view_button", "detail_button" );

$t->set_block( "image_list_page_tpl", "write_menu_tpl", "write_menu" );

$t->set_block( "write_menu_tpl", "next_tpl", "next" );
$t->set_block( "write_menu_tpl", "previous_tpl", "prev" );

$t->set_block( "write_menu_tpl", "default_new_tpl", "default_new" );
$t->set_block( "write_menu_tpl", "default_delete_tpl", "default_delete" );

$t->set_block( "default_delete_tpl", "delete_categories_button_tpl", "delete_categories_button" );
$t->set_block( "default_delete_tpl", "delete_images_button_tpl", "delete_images_button" );

$t->set_block( "image_list_tpl", "image_tpl", "image" );
$t->set_block( "image_list_tpl", "detail_view_tpl", "detail_view" );

$t->set_block( "image_tpl", "read_tpl", "read" );
$t->set_block( "image_tpl", "read_span_tpl", "read_span" );
$t->set_block( "image_tpl", "write_tpl", "write" );

$t->set_block( "detail_view_tpl", "detail_read_tpl", "detail_read" );
$t->set_block( "detail_view_tpl", "detail_write_tpl", "detail_write" );
$t->set_block( "detail_read_tpl", "image_variation_tpl", "variation" );

$t->set_block( "image_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$t->set_block( "category_tpl", "category_write_tpl", "category_write" );
$t->set_block( "category_tpl", "category_read_tpl", "category_read" );

$t->set_var( "read", "" );
$t->set_var( "variation", "" );
$t->set_var( "write_menu", "" );

$t->set_var( "next", "" );
$t->set_var( "prev", "" );

$t->set_var( "delete_images_button" , "" );
$t->set_var( "delete_categories_button" , "" );
$t->set_var( "default_new" , "" );
$t->set_var( "default_delete" , "" );
$t->set_var( "main_category_id", $CategoryID );

$t->set_var( "sync_dir", $SyncDir );

$category = new eZImageCategory( $CategoryID );

 
// Check if user have permission to the current category

$error = true;

if ( eZObjectPermission::hasPermission( $category->id(), "imagecatalogue_category", "r", $user )
     || eZImageCategory::isOwner( $user, $CategoryID ) )
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
    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "imagecatalogue_category", "r", $user ) ||
         eZImageCategory::isOwner( $user, $categoryItem->id()) )
    {
        $t->parse( "category_read", "category_read_tpl" );
    }

    // Check if user have write permission
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $categoryItem->id(), "imagecatalogue_category", "w", $user ) ) ||
         ( eZImageCategory::isOwner( $user, $categoryItem->id() ) ) )
    {
        $t->parse( "category_write", "category_write_tpl" );
        $t->parse( "delete_categories_button", "delete_categories_button_tpl" );
        $t->parse( "default_delete", "default_delete_tpl" );
        $t->parse( "write_menu", "write_menu_tpl" );
    }
    $t->parse( "category", "category_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0  &&  !isset( $SearchText ))
{
    $t->parse( "category_list", "category_list_tpl" );
}
else
{
    $t->set_var( "category_list", "" );
}

$limit = $ini->variable( "eZImageCatalogueMain", "ListImagesPerPage" );

// Print out all the images
if ( isset( $SearchText )  )
{
    $imageList =& eZImage::search( $SearchText );
    $count =& eZImage::searchCount( $SearchText );

  $URL = "search/?SearchText=$SearchText";

  // die("$SearchText - $Offset - $limit -- $count ---");

  $page_link = "search/?SearchText=$SearchText&Offset={item_index}";
  $page_link_next = "search/?SearchText=$SearchText&Offset={item_next_index}";
  $page_link_prev = "search/?SearchText=$SearchText&Offset={item_previous_index}";

  // $imageList =& eZImage::search( $SearchText );
  // $count =& eZImage::searchCount( $SearchText );
}
else
{
    $imageList =& $category->images( "time", $Offset, $limit );
    $count =& $category->imageCount(  );

  $URL = "image/list/$CategoryID/$Offset";

  $page_link = "image/list/$CategoryID/parent/{item_index}";
  $page_link_next = "image/list/$CategoryID/parent/{item_next_index}";
  $page_link_prev = "image/list/$CategoryID/parent/{item_previous_index}";
}


$i = 0;
$j = 0;
$counter = 0;


foreach ( $imageList as $image )
{
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );

    $t->set_var( "main_category_id", $CategoryID . "/" . $Offset );
    
    $t->set_var( "end_tr", "" );        
    $t->set_var( "begin_tr", "" );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "original_image_name", $image->originalFileName() );
    $t->set_var( "image_name", $image->name() );
    $t->set_var( "image_caption", $image->name() );
    $t->set_var( "image_url", $image->name() );

    $width =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewWidth" );
    $height =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewHight" );
    
    $variation =& $image->requestImageVariation( $width, $height );
    
    $t->set_var( "image_description",$image->description() ); 
    $t->set_var( "image_alt", $image->name() );
    $t->set_var( "image_src", "/" . $variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );

    if ( $image->fileExists( true ) )
    {
        $imagePath =& $image->filePath( true );
        $size = eZPBFile::filesize( $imagePath );
    }
    else
    {
        $size = 0;
    }

    $size = eZPBFile::siFileSize( $size );

    $t->set_var( "image_size", $size["size-string"] );
    $t->set_var( "image_unit", $size["unit"] );
    $t->set_var( "image_caption", $image->caption() );

    $t->set_var( "read", "" );
    $t->set_var( "write", "" );

    $t->set_var( "read_span", "" );
    $imagesPerRow = $ini->variable( "eZImageCatalogueMain", "ListImagesPerRow" );
    if ( count( $imageList ) == $counter + 1 )
    {
        $colspan = ( $imagesPerRow-1 ) - ($imagesPerRow % 4);
        if ( $colspan > 0 )
        {
            $t->set_var( "col_span", $colspan );
            $t->parse( "read_span", "read_span_tpl" );
        }
    }

    // Check if user have read permission
    $t->set_var( "detail_read", "" );
    $can_read = false;
    $can_write = false;

    $variationList = $image->variations();

        for ( $i = 0; $i < count( $variationList ); $i++ )
        {
            if ( $variationList[$i]->height() == $image->height() &&
                 $variationList[$i]->width() == $image->width() )
            {
                $value = array_slice( $variationList, $i, 1 );
                $variationList = array_merge( $value, array_slice( $variationList, 0, $i - 1 ), array_slice( $variationList, $i + 1 ) );
                break;
            }
        }
        
        $t->set_var( "variation", "" );

        
        $can_read = true;
        if ( ( $j % $imagesPerRow ) == 0 )
        {
            $t->set_var( "begin_tr", "<tr>" );
        }
        else if ( ( $j % $imagesPerRow ) == ( $imagesPerRow - 1 ) )
        {
            $t->set_var( "end_tr", "</tr>" );
        }

        if ( isset ( $DetailView ) )
        {
            $t->parse( "detail_read", "detail_read_tpl" );
        }
        else
        {
            $t->parse( "read", "read_tpl" );
        }
        $j++;
    

    // Check if user have write permission
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $image->id(), "imagecatalogue_image", "w", $user ) ) ||
         ( eZImage::isOwner( $user, $image->id() ) ) )
    {
        $can_write = true;
        if ( isset ( $DetailView ) )
        {
            $deleteImage = true;
            $t->parse( "detail_write", "detail_write_tpl" );

            $t->parse( "delete_images_button", "delete_images_button_tpl" );
            $t->parse( "default_delete", "default_delete_tpl" );
            $t->parse( "write_menu", "write_menu_tpl" );
        }
        else
        {
            $t->parse( "write", "write_tpl" );
        }
    }
    else
    {
        $t->set_var( "detail_write", "" );
    }

    // Set the detail or normail view
    if ( isset ( $DetailView ) )
    {
        $t->set_var( "image", "" );

        if ( $can_read )
            $t->parse( "detail_view", "detail_view_tpl", true );
    }
    else
    {
        $t->set_var( "detail_view", "" );
    
        if ( $can_read )
            $t->parse( "image", "image_tpl", true );
    }


    $counter++;
}

$t->set_var( "URL", $URL );
$t->set_var( "page_link", $page_link );
$t->set_var( "page_link_next", $page_link_next );
$t->set_var( "page_link_prev", $page_link_prev );

$t->set_var( "main_category_id", $CategoryID );

eZList::drawNavigator( $t, $count, $limit, $Offset, "image_list_page_tpl" );

$t->set_var( "detail_button", "" );
$t->set_var( "normal_button", "" );
$t->set_var( "pos", $Offset );

if ( isset ( $DetailView ) )
{
    $t->set_var( "is_detail_view", "true" );
    $t->parse( "normal_button", "normal_view_button" );
}
else
{
    $t->set_var( "is_detail_view", "" );
    $t->parse( "detail_button", "detail_view_button" );
}

// Print out the category/image menu
if ( $category->id() != 0 )
{
    if ( ( $user ) &&
         ( eZObjectPermission::hasPermission( $category->id(), "imagecatalogue_category", "w", $user ) ) )
    {
        $t->parse( "default_new", "default_new_tpl" );
        $t->parse( "write_menu", "write_menu_tpl" );
    }
}
else
{
    if ( eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot" ) )
    {
        $t->parse( "default_new", "default_new_tpl" );
        $t->parse( "write_menu", "write_menu_tpl" );
    }
}

if ( count( $imageList ) > 0 )
{
    $t->parse( "image_list", "image_list_tpl" );
}
else
{
    $t->set_var( "normal_button", "" );
    $t->set_var( "detail_button", "" );
    $t->set_var( "image_list", "" );
}

$t->set_var( "image_dir", $ImageDir );

$t->set_var( "main_category_id", $CategoryID );

if ( $error == false )
{
    $t->pparse( "output", "image_list_page_tpl" );
}
else
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

?>