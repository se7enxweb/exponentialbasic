<?php
// 
// $Id: smallarticlelist.php 8283 2001-11-05 16:44:16Z th $
//
// Created on: <18-Oct-2000 14:41:37 bf>
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$ImageDir = $ini->read_var( "eZArticleMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZArticleMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZArticleMain", "DefaultLinkText" );
$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );

unset( $menuCachedFile );
// do the caching
if ( $PageCaching == "enabled" )
{
    $menuCachedFile = "kernel/ezarticle/cache/smallarticlelist,". $GlobalSiteDesign .".cache";

    if ( eZFile::file_exists( $menuCachedFile ) )
    {
        include( $menuCachedFile );
    }
    else
    {
        createSmallArticleList( true );
    }            
}
else
{
    createSmallArticleList();
}

function createSmallArticleList( $generateStaticPage = false )
{
    global $ini;
    global $menuCachedFile;
    global $noItem;
	global $GlobalSiteDesign;
    global $CategoryID;
    global $Offset;
    global $Limit;
    global $Language;
	global $DefaultLinkText;

    $t = new eZTemplate( "kernel/ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                         "kernel/ezarticle/user/intl/", $Language, "smallarticlelist.php" );

    $t->setAllStrings();

    $t->set_file( "article_list_page_tpl", "smallarticlelist.tpl" );

    $t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
    $t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );
    $t->set_block( "article_item_tpl", "read_more_tpl", "read_more_item" );

    $category = new eZArticleCategory( $CategoryID );

    $t->set_var( "current_category_name", $category->name() );
    $t->set_var( "current_category_description", $category->description() );

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    $articleList = $category->articles( $category->sortMode(), false, true, $Offset, $Limit );

    $locale = new eZLocale( $Language );
    $i = 0;
    $t->set_var( "article_list", "" );
    foreach ( $articleList as $article )
    {
        $nr = ( $i % 2 ) + 1;
        $t->set_var( "alt_nr", $nr );

        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );

        $renderer = new eZArticleRenderer( $article );

        $published = $article->published();
        
        $t->set_var( "article_published", $locale->format( $published ) );

        $t->set_var( "category_id", $CategoryID );

        $t->set_var( "article_intro", $renderer->renderIntro(  ) );

	    $contents =& $renderer->renderPage();

        if ( $article->linkText() != "" )
        {
            $t->set_var( "article_link_text", $article->linkText() );
        	$t->parse( "read_more_item", "read_more_tpl" );		
        }
        else if ( !( trim( $contents[1] ) == "" && count( $article->attributes( false ) ) <= 0 ))
        {
            $t->set_var( "article_link_text", $DefaultLinkText );
        	$t->parse( "read_more_item", "read_more_tpl" );
        }
		else
		{
            $t->set_var( "article_link_text", "" );
        	$t->set_var( "read_more_item", "" );
		}
		
        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }

    if ( count( $articleList ) > 0 )    
        $t->parse( "article_list", "article_list_tpl" );
    else
        $t->set_var( "article_list", "" );


    if ( $generateStaticPage )
    {
        $fp = eZFile::fopen( $menuCachedFile, "w+");

        $output = $t->parse( "output", "article_list_page_tpl" );
        // print the output the first time while printing the cache file.
    
        print( $output );
        fwrite ( $fp, $output );
        fclose( $fp );
    }
    else
    {
        $t->pparse( "output", "article_list_page_tpl" );
    }
}

?>