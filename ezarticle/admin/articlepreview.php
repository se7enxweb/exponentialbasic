<?php
// 
// $Id: articlepreview.php 8216 2001-11-01 19:12:53Z bf $
//
// Created on: <18-Oct-2000 16:34:51 bf>
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


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "articlepreview.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_preview_page_tpl" => "articlepreview.tpl"
    ) );

$t->set_block( "article_preview_page_tpl", "page_menu_separator_tpl", "page_menu_separator" );

$t->set_block( "article_preview_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );


$t->set_block( "article_preview_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "article_preview_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "article_preview_page_tpl", "prev_page_link_tpl", "prev_page_link" );

$t->set_block( "article_preview_page_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "attribute_item_tpl", "attribute_item" );

$t->set_block( "article_preview_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );


$article = new eZArticle( );

if ( !$article->get( $ArticleID ) )
{
    eZHTTPTool::header( "Location: /error/404/" );
    exit();
}


$renderer = new eZArticleRenderer( $article );

$t->set_var( "article_name", $article->name() );
$t->set_var( "author_text", $article->authorText() );

$articleContents =& $renderer->renderPage( $PageNumber -1 );
        
$t->set_var( "article_intro", $articleContents[0] );
$t->set_var( "article_body", $articleContents[1] );


$t->set_var( "link_text", $article->linkText() );
$t->set_var( "article_id", $article->id() );


$files = $article->files();

if ( count( $files ) > 0 )
{
    foreach ( $files as $file )
    {
        $t->set_var( "file_name", $file->name() );
        $t->parse( "attached_file", "attached_file_tpl", true );
    }

    $t->parse( "attached_file_list", "attached_file_list_tpl" );
}
else
{
    $t->set_var( "attached_file_list", "" );
}

$types = $article->types();

$typeCount = count( $types );

$t->set_var( "attribute_item", "" );
$t->set_var( "type_item", "" );
$t->set_var( "attribute_list", "" );

if( $typeCount > 0 )
{
    foreach( $types as $type )
    {
        $attributes = array();
        $attributes = $type->attributes();
        $attributeCount = count( $attributes );
        
        if( $attributeCount > 0 )
        {
            $t->set_var( "type_id", $type->id() );
            $t->set_var( "type_name", $type->name() );
            $t->set_var( "attribute_item", "" );
            foreach( $attributes as $attribute )
            {
                $t->set_var( "attribute_id", $attribute->id() );
                $t->set_var( "attribute_name", $attribute->name() );
                $t->set_var( "attribute_value", nl2br( $attribute->value( $article ) ) );
                $t->parse( "attribute_item", "attribute_item_tpl", true );
            }
            $t->parse( "type_item", "type_item_tpl", true );
        }
    }

    $t->parse( "attribute_list", "attribute_list_tpl" );
}

// image list

$usedImages = $renderer->usedImageList();
$images =& $article->images();
$imageNumber = 0;
$i=0;
foreach ( $images as $imageArray ) {
    $image = $imageArray["Image"];
    $placement = $imageArray["Placement"];

	$showImage = true;

	if ( is_array( $usedImages ) ) {
		if ( in_array( $placement, $usedImages ) ) {
        	$showImage = false;
    	}
    }
            
	if (  $showImage  ) {
		if ( ( $i % 2 ) == 0 ) {
        	$t->set_var( "td_class", "bglight" );
		} else {
        	$t->set_var( "td_class", "bgdark" );
        }

		if ( $image->caption() == "" )
        	$t->set_var( "image_caption", "&nbsp;" );
		else
        	$t->set_var( "image_caption", $image->caption() );

            
        $t->set_var( "image_id", $image->id() );
        $t->set_var( "article_id", $ArticleID );

        $variation =& $image->requestImageVariation( 150, 150 );

        $t->set_var( "image_url", "/" . $variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height",$variation->height() );

        $t->parse( "image", "image_tpl", true );
    	$i++;
    }
	$imageNumber++;
}

$t->parse( "image_list", "image_list_tpl", true );

if ( $i == 0 )
    $t->set_var( "image_list", "" );    


$pageCount = $article->pageCount();

if ( $pageCount > 1 )
{
    for ( $i=0; $i<$pageCount; $i++ )
    {
        $t->set_var( "article_id", $article->id() );    
        $t->set_var( "page_number", $i+1 );

        $t->parse( "page_link", "page_link_tpl", true );
    }

    $t->parse( "page_menu_separator", "page_menu_separator_tpl" );    
}
else
{
    $t->set_var( "page_link", "" );
    $t->set_var( "page_menu_separator", "" );
}

if ( $PageNumber > 1 )
{
    $t->set_var( "prev_page_number", $PageNumber - 1 );    
    $t->parse( "prev_page_link", "prev_page_link_tpl" );
}
else
{
    $t->set_var( "prev_page_link", "" );
}

if ( $PageNumber < $pageCount )
{
    $t->set_var( "next_page_number", $PageNumber + 1 );    
    $t->parse( "next_page_link", "next_page_link_tpl" );
}
else
{
    $t->set_var( "next_page_link", "" );
}



$t->pparse( "output", "article_preview_page_tpl" );


?>
