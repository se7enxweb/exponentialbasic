<?php
// 
// $Id: search.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <27-Nov-2000 11:52:01 bf>
//
// This source file is part of Exponential Basic, publishing software.
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

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZNewsFeedMain", "Language" );

if ( $SearchText == "" )
{   //show anohther template if the search is 
    $t = new eZTemplate( "kernel/eznewsfeed/user/" . $ini->variable( "eZNewsFeedMain", "TemplateDir" ),
                         "kernel/eznewsfeed/user/intl/", $Language, "search.php" );

    $t->setAllStrings();
    
    $t->set_file( array(
        "news_search_page_tpl" => "searchmenu.tpl"
        ) );

    $t->pparse( "output", "news_search_page_tpl" );
}
else
{

    $t = new eZTemplate( "kernel/eznewsfeed/user/" . $ini->variable( "eZNewsFeedMain", "TemplateDir" ),
                         "kernel/eznewsfeed/user/intl/", $Language, "search.php" );
    
    $t->setAllStrings();
    
    $t->set_file( array(
        "news_search_page_tpl" => "search.tpl"
        ) );
    
    // news
    $t->set_block( "news_search_page_tpl", "news_list_tpl", "news_list" );
    $t->set_block( "news_list_tpl", "news_item_tpl", "news_item" );
    
    $news = new eZNews();
    
    
    // news
    
    // fetch the n next news items
    $newsList = $news->search( $SearchText );
    
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
    
    $t->pparse( "output", "news_search_page_tpl" );
}

?>