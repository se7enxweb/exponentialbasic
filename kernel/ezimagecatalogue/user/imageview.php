<?php
// 
// $Id: imageview.php 9345 2002-03-06 10:34:39Z jhe $
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

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagevariation.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZImageCatalogueMain", "Language" );

$ShowOriginal = $ini->variable( "eZImageCatalogueMain", "ShowOriginal" );


$t = new eZTemplate( "kernel/ezimagecatalogue/user/" . $ini->variable( "eZImageCatalogueMain", "TemplateDir" ),
                     "kernel/ezimagecatalogue/user/intl/", $Language, "imageview.php" );

$t->set_file( "image_view_tpl", "imageview.tpl" );
$t->set_block( "image_view_tpl", "product_item_tpl", "product_item" );
$t->set_block( "image_view_tpl", "article_item_tpl", "article_item" );
$t->set_block( "image_view_tpl", "related_products_tpl", "related_products" );
$t->set_block( "image_view_tpl", "related_articles_tpl", "related_articles" );
$t->set_block( "image_view_tpl", "path_tpl", "path" );
$t->set_var( "related_products", "" );
$t->set_var( "related_articles", "" );

$t->setAllStrings();

$user =& eZUser::currentUser();

$image = new eZImage( $ImageID );

// sections
// include_once( "ezsitemanager/classes/ezsection.php" );
     
$parent_category = $image->categories();

// tempo fix for admin users - maybe in the future must be changed
if ( $parent_category != 0 )
{
    $GlobalSectionID = eZImageCategory::sectionIDstatic ( $parent_category[0] ); // We use always first category ;-( [0]
}

if ( !$GlobalSectionID )
    $GlobalSectionID = $ini->variable( "eZImageCatalogueMain", "DefaultSection" );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

//if ( eZObjectPermission::hasPermission( $image->id(), "imagecatalogue_image", "r", $user ) == false )
//{
//    eZHTTPTool::header( "Location: /error/403/" );
//    exit();
//}

if ( $ShowOriginal != "enabled" && !isset( $VariationID ) )
{
    $variation =& $image->requestImageVariation( $ini->variable( "eZImageCatalogueMain", "ImageViewWidth" ),
    $ini->variable( "eZImageCatalogueMain", "ImageViewHeight" ) );
}
else if ( isset( $VariationID ) )
{
    $variation = new eZImageVariation( $VariationID );
    if ( $variation->imageID() != $ImageID )
    {
        
        $variation =& $image->requestImageVariation( $ini->variable( "eZImageCatalogueMain", "ImageViewWidth" ),
        $ini->variable( "eZImageCatalogueMain", "ImageViewHeight" ) );
    }
}


$t->set_var( "path", "" );
$category = $image->categoryDefinition();
if ( $category != -1 )
{
	$pathArray =& $category->path();
	foreach ( $pathArray as $path )
		{
	    $t->set_var( "category_id", $path[0] );
    	$t->set_var( "category_name", $path[1] );
	    $t->parse( "path", "path_tpl", true );
		}
}

// added related articles, products

$info_items = 0;
$t->set_var( "article_item", "" );
    $articles = $image->articles();

    foreach( $articles as $article )
    {
        if ( ( $user ) &&
             ( eZObjectPermission::hasPermission( $article->id(), "article_article", "r", $user ) ) &&
			 ( $article->isPublished() )				 			 
			)
        {
	    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
            $t->set_var( "article_id", $article->id() );
            $t->set_var( "article_name", $article->name() );

            $t->parse( "article_item", "article_item_tpl", true );
	    $info_items++;	
        }
    }
    if( $info_items > 0 )
        $t->parse( "related_articles", "related_articles_tpl", false );

$info_items = 0;
$t->set_var( "product_item", "" );
    $products = $image->products();

    foreach( $products as $product )
    	if ( $product->showProduct() )
		{
		$t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
        $t->set_var( "product_id", $product->id() );
        $t->set_var( "product_name", $product->name() );
        $t->parse( "product_item", "product_item_tpl", true );
        $info_items++;	
	    }
   if( $info_items > 0 )
        $t->parse( "related_products", "related_products_tpl", false );

    if ( $image->fileExists( true ) )
    {
        $imagePath =& $image->filePath( true );
        $size = eZPBFile::filesize( $imagePath );
	$t->set_var( "image_path", $imagePath );
    }
    else
    {
        $size = 0;
	$t->set_var( "image_path", "" );
    }

$size = eZPBFile::siFileSize( $size );

$width =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewWidth" );
$height =& $ini->variable( "eZImageCatalogueMain", "ThumbnailViewHight" );
$thumbnail =& $image->requestImageVariation( $width, $height );
$SiteURL = $ini->variable( "site", "UserSiteURL" );

$t->set_var( "orig_width", $image->width() );
$t->set_var( "orig_height", $image->height() );
$t->set_var( "image_src", "/" . $thumbnail->imagePath() );
$t->set_var( "site_url", $SiteURL );

$t->set_var( "image_size", $size["size-string"] );
$t->set_var( "image_unit", $size["unit"] );

$t->set_var( "image_id", $image->id() );
$t->set_var( "image_uri", "/" . $variation->imagePath() );
$t->set_var( "image_width", $variation->width() );
$t->set_var( "image_height", $variation->height() );
$t->set_var( "image_caption", $image->caption() );
$t->set_var( "image_name", $image->name() );
$t->set_var( "image_description", $image->description() );
$t->set_var( "original_image_name", $image->originalFileName() );

if ( !isset( $RefererURL ) || !$RefererURL )
	$RefererURL="";

$t->set_var( "referer_url", $RefererURL );

$t->pparse( "output", "image_view_tpl" );

?>