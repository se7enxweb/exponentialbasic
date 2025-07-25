<?php
// 
// $Id: articlelinks.php 6344 2001-08-01 16:22:23Z kaid $
//
// Created on: <03-Jan-2001 10:47:00 bf>
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

// graham : php - error log fix project : 20005-05-27 (undefined variable patch)
// this just shuts of the hundreds of thousands of error log entries (performance)
$DefaultLinkText = "";

$ArticlePageCaching =& $ini->read_var( "eZArticleMain", "PageCaching");
$PageCaching = "disabled";

$PureStatic = "false";
//$PureStatic ="true";

// unset( $CacheFile );
unset( $GenerateStaticPage );

if ( $PageCaching == "enabled" )
{
    // include_once( "classes/ezcachefile.php" );
    $CacheFile = new eZCacheFile( "kernel/ezarticle/cache/",
                                  array( "articlelinklist", $CategoryID, $url_array[3], $GlobalSiteDesign ),
                                  "cache", "," );
    if ( $CacheFile->exists() )
    {
        include( $CacheFile->filename( true ) );
        $PureStatic = "true";
    }
    else
    {
        $GenerateStaticPage = "true";
    }
}

if ( $PureStatic != "true" )
{
    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "classes/ezlocale.php" );

    // include_once( "ezarticle/classes/ezarticlecategory.php" );
    // include_once( "ezarticle/classes/ezarticle.php" );
    // include_once( "ezarticle/classes/ezarticlerenderer.php" );

    $ini =& INIFile::globalINI();
    $Language = $ini->read_var( "eZArticleMain", "Language" );
    $ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );

    $t = new eZTemplate( "kernel/ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                         "kernel/ezarticle/user/intl/", $Language, "articlelinks.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "article_list_page_tpl" => "articlelinks.tpl"
        ) );


    $t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
    $t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );

    $t->set_var( "image_dir", $ImageDir );
    $t->set_var( "sitedesign", $GlobalSiteDesign );
		
    $category = new eZArticleCategory( $CategoryID );

    $category_name_no_whitespace = $category->name();
    $category_name_no_whitespace = str_replace(" ", "&nbsp;", $category_name_no_whitespace);

    $t->set_var( "current_category_name", $category_name_no_whitespace );
    $t->set_var( "current_category_description", $category->description() );

    $articleList =& $category->articles( $category->sortMode(), false, true );

    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "article_list", "" );
    foreach ( $articleList as $article )
    {
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );
        $catDef =& $article->categoryDefinition();
        
        $t->set_var( "article_category_id", $catDef->id() );

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
            $t->set_var( "article_link_text", isset($DefaultLinkText)?$DefaultLinkText:'More' );
        }

		if ( ( $url_array[2] == "articlestatic" ) && ( $url_array[3] == $article->id() ) )
		{
		    $t->set_var( "mark", "menumark" );
		}
		else 
		{ 
		    $t->set_var( "mark", "" );
		}

        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }

    if ( count( $articleList ) > 0 )    
        $t->parse( "article_list", "article_list_tpl" );
    else
        $t->set_var( "article_list", "" );

    if ( isset( $GenerateStaticPage ) and $GenerateStaticPage == "true" )
    {
        $output = $t->parse( "output", "article_list_page_tpl" );
        // print the output the first time while printing the cache file.
        print( $output );
        $CacheFile->store( $output );
    }
    else
    {
        $t->pparse( "output", "article_list_page_tpl" );
    }
}

?>