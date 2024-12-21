<?php
//
// $Id: articleheaderlist.php 9788 2003-03-24 08:52:52Z br $
//
// Created on: <26-Oct-2000 21:15:58 bf>
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
// include_once( "classes/ezlocale.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezarticle/classes/ezarticlerenderer.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );

if ( !is_numeric( $CategoryID ) )
{
    eZHTTPTool::header( "Location: /error/404" );
    exit();
}

$GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );

$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "articleheaderlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "articleheaderlist.tpl"
    ) );

// path
$t->set_block( "article_list_page_tpl", "path_item_tpl", "path_item" );

// article
$t->set_block( "article_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// product
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );


// image dir
$t->set_var( "image_dir", $ImageDir );

$category = new eZArticleCategory( $CategoryID );

$t->set_var( "current_category_name", $category->name() );
$t->set_var( "current_category_description", $category->description() );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );

    $t->parse( "path_item", "path_item_tpl", true );
}

$categoryList = $category->getByParent( $category );


// categories
$i=0;
$t->set_var( "category_list", "" );
$t->set_var( "category_item", "" );

if (is_array($categoryList))
{
    foreach ( $categoryList as $categoryItem )
    {
        $t->set_var( "category_id", $categoryItem->id() );

        $t->set_var( "category_name", $categoryItem->name() );

        $parent = $categoryItem->parent();


        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "category_description", $categoryItem->description() );

        $t->parse( "category_item", "category_item_tpl", true );
        $i++;
    }
}

if ( count( $categoryList ) > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


if ( !isset( $SortMode ) )
	$SortMode = "";

if ( $CategoryID == 0 )
{
    $article = new eZArticle();
    $articleList = $article->articles( $SortMode, false );
}
else
{
    $articleList = $category->articles( $SortMode, false, true );
}

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "article_list", "" );
foreach ( $articleList as $article )
{
    if ( eZObjectPermission::hasPermission( $article->id(), "article_article", 'r' ) )
    {
        $catDef =& $article->categoryDefinition();
        $t->set_var( "category_id", $catDef->id() );

        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );
        $def = $article->categoryDefinition();
        if( $def )
            $t->set_var( "article_category_name", $def->name() );
        else
            $t->set_var( "article_category_name" ,"" );

        $published = $article->published();

        $t->set_var( "article_published", $locale->format( $published ) );

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        if ( $article->linkText() != "" )
        {
            $t->set_var( "article_link_text", $article->linkText() );
        }
        else
        {
            $t->set_var( "article_link_text", "more" );
        }

        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

if (  $i > 0 )
    $t->parse( "article_list", "article_list_tpl" );
else
    $t->set_var( "article_list", "" );

$t->set_var( "section_id", $GlobalSectionID );

$t->pparse( "output", "article_list_page_tpl" );

?>

