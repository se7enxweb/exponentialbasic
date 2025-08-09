<?php
// 
// $Id: newslist.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <29-Nov-2000 11:35:19 bf>
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

// include_once( "eznewsfeed/classes/eznews.php" );
// include_once( "eznewsfeed/classes/eznewscategory.php" );
// include_once( "eznewsfeed/classes/eznewsimporter.php" );

// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztemplate.php" );

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZNewsFeedMain", "Language" );

$t = new eZTemplate( "kernel/eznewsfeed/user/" . $ini->variable( "eZNewsFeedMain", "TemplateDir" ),
                     "kernel/eznewsfeed/user/intl/", $Language, "newslist.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_archive_page_tpl" => "newslist.tpl"
    ) );

// news
$t->set_block( "news_archive_page_tpl", "news_list_tpl", "news_list" );
$t->set_block( "news_list_tpl", "news_item_tpl", "news_item" );
$t->set_block( "news_list_tpl", "short_news_item_tpl", "short_news_item" );

$category = new eZNewsCategory( $CategoryID );


// news

// fetch the first new item
$firstNewsList =& $category->newsList( "time", "no", 0, 1 );

// fetch the n next news items
$newsList =& $category->newsList( "time", "no", 1, 4 );

// fetch the news to be listed as small items at the bottom
$shortNewsList =& $category->newsList( "time", "no", 5, 10 );

$locale = new eZLocale( $Language );

$t->set_var( "news_list", "" );


// print out the first news
if ( count( $firstNewsList ) > 0 )
{
    $t->set_var( "first_news_id", $firstNewsList[0]->id() );
    $t->set_var( "first_news_name", $firstNewsList[0]->name() );
    $t->set_var( "first_news_intro", $firstNewsList[0]->intro() );
    $t->set_var( "first_news_url", $firstNewsList[0]->url() );
    $t->set_var( "first_news_origin", $firstNewsList[0]->origin() );
    
    $published = $firstNewsList[0]->originalPublishingDate();
    $t->set_var( "first_news_date", $locale->format( $published ) );
}

$i=0;
foreach ( $newsList as $news )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "starttr", "<tr>" );
        $t->set_var( "endtr", "" );        
    }
    else
    {
        $t->set_var( "starttr", "" );
        $t->set_var( "endtr", "</tr>" );
    }
    
    
    $t->set_var( "news_name", $news->name() );

    $t->set_var( "news_intro", $news->intro() );
    $t->set_var( "news_url", $news->url() );
    $t->set_var( "news_origin", $news->origin() );

    $published = $news->originalPublishingDate();

    $t->set_var( "news_date", $locale->format( $published ) );

    
    $t->set_var( "news_id", $news->id() );

    $t->parse( "news_item", "news_item_tpl", true );
    $i++;
}

foreach ( $shortNewsList as $news )
{
    $t->set_var( "news_name", $news->name() );

    $t->set_var( "news_url", $news->url() );
    $t->set_var( "news_origin", $news->origin() );

    $published = $news->originalPublishingDate();
    $date = $published->date();
    $t->set_var( "news_date", $locale->format( $date ) );
    
    $t->set_var( "news_id", $news->id() );

    $t->parse( "short_news_item", "short_news_item_tpl", true );
//  	$t->set_var( "short_news_item", "" ); // Her ogs�!
	$i++;
}

if ( count( $newsList ) > 0 )    
    $t->parse( "news_list", "news_list_tpl" );
else
    $t->set_var( "news_list", "" );

if ( $GenerateStaticPage == "true" )
{
    $fp = eZPBFile::fopen( $cachedFile, "w+");

    $output = $t->parse( "output", "news_archive_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "news_archive_page_tpl" );
}

?>