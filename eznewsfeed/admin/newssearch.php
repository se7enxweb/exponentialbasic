<?php
// 
// $Id: newssearch.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <13-Dec-2000 10:57:59 bf>
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

$ini = INIFile::globalINI();

$Language = $ini->read_var( "eZNewsfeedMain", "Language" );

$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsfeedMain", "TemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "newssearch.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_search_page_tpl" => "newssearch.tpl"
    ) );

// news
$t->set_block( "news_search_page_tpl", "news_list_tpl", "news_list" );
$t->set_block( "news_list_tpl", "news_item_tpl", "news_item" );


$t->set_block( "news_search_page_tpl", "previous_tpl", "previous" );
$t->set_block( "news_search_page_tpl", "next_tpl", "next" );

$news = new eZNews();

if ( !isSet( $Limit ) )
    $Limit = 20;
if ( !isSet( $Offset ) )
    $Offset = 0;

if ( isSet( $URLQueryString ) )
{
    $SearchText = urldecode( $URLQueryString );
}

// fetch the n next news items
$newsList =& $news->search( $SearchText, true, $Offset, $Limit );
$newsListCount =& $news->searchCount( $SearchText, true );


$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "news_list", "" );


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

if ( count( $newsList ) > 0 )    
    $t->parse( "news_list", "news_list_tpl" );
else
$t->set_var( "news_list", "" );


$prevOffs = $Offset - $Limit;
$nextOffs = $Offset + $Limit;
        
if ( $prevOffs >= 0 )
{
    $t->set_var( "prev_offset", $prevOffs  );
    $t->parse( "previous", "previous_tpl" );
}
else
{
    $t->set_var( "previous", "" );
}
        
if ( $nextOffs <= $newsListCount )
{
    $t->set_var( "next_offset", $nextOffs  );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}

$t->set_var( "url_query_string", urlencode( $SearchText ) );

$t->pparse( "output", "news_search_page_tpl" );

?>
