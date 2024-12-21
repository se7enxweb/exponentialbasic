<?php
// 
// $Id: unassigned.php 8425 2001-11-15 09:20:48Z br $
//
// Created on: <26-Oct-2000 19:40:18 bf>
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
// include_once( "classes/ezlist.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );
$ImageDir = $ini->read_var( "eZImageCatalogueMain", "ImageDir" );

$t = new eZTemplate( "kernel/ezimagecatalogue/admin/" . $ini->read_var( "eZImageCatalogueMain", "AdminTemplateDir" ),
                     "kernel/ezimagecatalogue/admin/intl/", $Language, "unassigned.php" );

$t->set_file( "image_list_page_tpl", "unassigned.tpl" );

$t->setAllStrings();

$user =& eZUser::currentUser();

$t->set_block( "image_list_page_tpl", "image_list_tpl", "image_list" );

$t->set_block( "image_list_tpl", "value_tpl", "value" );
$t->set_block( "image_list_tpl", "detail_view_tpl", "detail_view" );

$t->set_block( "detail_view_tpl", "detail_read_tpl", "detail_read" );
$t->set_block( "detail_view_tpl", "detail_write_tpl", "detail_write" );

$t->set_var( "read", "" );
$t->set_var( "write_menu", "" );

$t->set_var( "delete_images_button" , "" );
$t->set_var( "delete_categories_button" , "" );
$t->set_var( "default_new" , "" );
$t->set_var( "default_delete" , "" );


if ( !( $Offset > 0 ) )
    $Offset = 0;
if ( !( $Limit > 0 ) )
    $Limit = $ini->read_var( "eZImageCatalogueMain", "ListImagesPerPage" );


if ( isSet( $Update ) )
{
    for ( $i = 0; $i < count( $ImageArrayID ); $i++ )
    {
        if ( ( $CategoryArrayID[$i] != "-1" ) && ( is_numeric( $CategoryArrayID[$i] ) ) )
        {
            $image = new eZImage( $ImageArrayID[$i] );
            $category = new eZImageCategory( $CategoryArrayID[$i] );
            $category->addImage( $image );
        }
    }
}


$t->set_var( "offset", $Offset );
$t->set_var( "limit", $Limit );


// Print out all the images

$imageList =& eZImage::getUnassigned( $Offset, $Limit );


if ( $imageList )
    $imageCount =& eZImage::countUnassigned();
else
    $imageCount = 0;

$i = 0;
$counter = 0;

if ( !is_null( $imageList ) && count ( $imageList ) > 0 )
{
    foreach ( $imageList as $image )
    {
        $t->set_var( "end_tr", "" );        
        $t->set_var( "begin_tr", "" );
        
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "original_image_name", $image->originalFileName() );
        $t->set_var( "image_name", $image->name() );
        $t->set_var( "image_caption", $image->name() );
        $t->set_var( "image_url", $image->name() );
        
        $width =& $ini->read_var( "eZImageCatalogueMain", "ThumbnailViewWidth" );
        
        $height =& $ini->read_var( "eZImageCatalogueMain", "ThumbnailViewHight" );
        
        $variation =& $image->requestImageVariation( $width, $height );
        
        $t->set_var( "image_description",$image->description() ); 
        $t->set_var( "image_alt", $image->name() );
        $t->set_var( "image_src", "/" .$variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height", $variation->height() );
        $t->set_var( "image_file_name", $image->originalFileName() );
        
        if ( $image->fileExists( true ) )
        {
            $imagePath =& $image->filePath( true );
            $size = eZFile::filesize( $imagePath );
        }
        else
        {
            $size = 0;
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
    
        $size = eZFile::siFileSize( $size );

        $t->set_var( "image_size", $size["size-string"] );
        $t->set_var( "image_unit", $size["unit"] );
        $t->set_var( "image_caption", $image->caption() );

        $t->set_var( "read", "" );
        $t->set_var( "write", "" );

        // Check if user have read permission
        $t->set_var( "detail_read", "" );
        $can_read = false;
        $can_write = false;
        if ( eZObjectPermission::hasPermission( $image->id(), "imagecatalogue_image", "r", $user ) ||
             eZImage::isOwner( $user, $image->id() ) )
        {
            $can_read = true;
            if ( ( $i % 4 ) == 0 )
            {
                $t->set_var( "begin_tr", "<tr>" );
            }
            else if ( ( $i % 4 ) == 3 )
            {
                $t->set_var( "end_tr", "</tr>" );
            }

            $t->parse( "detail_read", "detail_read_tpl" );
            $i++;
        }


        $t->parse( "detail_view", "detail_view_tpl", true );

        $counter++;
    }
}


eZList::drawNavigator( $t, $imageCount, $Limit, $Offset, "image_list_page_tpl" );

if ( !is_null( $imageList ) && count( $imageList ) > 0 )
{
    $t->parse( "image_list", "image_list_tpl" );
}
else
{
    $t->set_var( "normal_button", "" );
    $t->set_var( "detail_button", "" );
    $t->set_var( "image_list", "" );
}

$category = new eZImageCategory() ;
$categoryList =& $category->getTree( );

// Make a category list
foreach ( $categoryList as $categoryItem )
{
    if( eZObjectPermission::hasPermission( $categoryItem[0]->id(), "imagecatalogue_category", 'w' )
        || eZImageCategory::isOwner( eZUser::currentUser(), $categoryItem[0]->id() ) )
    {
        $t->set_var( "option_name", $categoryItem[0]->name() );
        $t->set_var( "option_value", $categoryItem[0]->id() );

        if ( $categoryItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $categoryItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
    }
}

$t->set_var( "image_dir", $ImageDir );


$t->pparse( "output", "image_list_page_tpl" );

?>