<?php
// 
// $Id: search.php 7268 2001-09-16 18:37:39Z bf $
//
// Created on: <28-Oct-2000 15:56:58 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "classes/ezlist.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZArticleMain", "Language" );
$Limit = $ini->variable( "eZArticleMain", "AdminListLimit" );


if( isset( $Delete ) && count( $ArticleArrayID ) > 0 )
{
    foreach( $ArticleArrayID as $articleID )
    {
        if( eZObjectPermission::hasPermission( $articleID, "article_article", 'w' ) )
        {
            $article = new eZArticle( $articleID );
            $article->delete();
        }
    }
}

$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->variable( "eZArticleMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "article_list_page_tpl" => "search.tpl"
    ) );

if ( !isset ( $Offset ) )
    $Offset = 0;
if ( !isset ( $StartMonth ) or $StartMonth == '' )
    $StartMonth = 0;
if ( !isset ( $StartDay ) or $StartDay == ''  )
    $StartDay = 0;
if ( !isset ( $StartYear ) or $StartYear == ''  )
    $StartYear = 0;

if ( !isset ( $StopMonth ) or $StopMonth == '' )
    $StopMonth = 0;
if ( !isset ( $StopDay ) or $StopDay == ''  )
    $StopDay = 0;
if ( !isset ( $StopYear ) or $StopYear == ''  )
    $StopYear = 0;

// article
$t->set_block( "article_list_page_tpl", "article_list_tpl", "article_list" );
$t->set_block( "article_list_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "article_is_published_tpl", "article_is_published" );
$t->set_block( "article_item_tpl", "article_not_published_tpl", "article_not_published" );
$t->set_block( "article_list_page_tpl", "article_delete_tpl", "article_delete" );

// Init url variables - for eZList...
$t->set_var( "url_start_stamp", urlencode( "+" ) );
$t->set_var( "url_stop_stamp", urlencode( "+" ) );
$t->set_var( "url_category_array", urlencode( "+" ) );
$t->set_var( "url_contentswriter_id", urlencode( "+" ) );
$t->set_var( "url_photographer_id", urlencode( "+" ) );

if ( checkdate ( $StartMonth, $StartDay, $StartYear ) )
{
    $startDate = new eZDateTime( $StartYear,  $StartMonth, $StartDay, $StartHour, $StartMinute, 0 );
    $StartStamp = $startDate->timeStamp();
}
if ( checkdate ( $StopMonth, $StopDay, $StopYear ) )
{
    $stopDate = new eZDateTime( $StopYear, $StopMonth, $StopDay, $StopHour, $StopMinute, 0 );
    $StopStamp = $stopDate->timeStamp();
}


// BUILDING THE SEARCH
// If url parameters are present when loading page, they are decoded in the datasupplier
$paramsArray = array();
if ( $SearchText )
{
    if ( isset( $StartStamp ) )
    {
        $paramsArray["FromDate"] = $StartStamp;
        $t->set_var( "url_start_stamp", urlencode( $StartStamp ) );
    }
        
    if ( isset( $StopStamp ) )
    {
        $paramsArray["ToDate"] = $StopStamp;
        $t->set_var( "url_stop_stamp", urlencode( $StopStamp ) );
    }

    if( isset( $ContentsWriterID ) && $ContentsWriterID != 0 )
    {
        $paramsArray["AuthorID"] = $ContentsWriterID;
        $t->set_var( "url_contentswriter_id", urlencode( $ContentsWriterID ) );
    }

    if( isset( $PhotographerID ) && $PhotographerID != 0 )
    {
        $paramsArray["PhotographerID"] = $PhotographerID;
        $t->set_var( "url_photographer_id", urlencode( $PhotographerID ) );
    }

    if( isset( $CategoryArray ) && is_array( $CategoryArray ) && count( $CategoryArray ) > 0 && !in_array( 0, $CategoryArray ) )
    {
        $paramsArray["Categories"] = $CategoryArray;

        // fix output string for URL
        $t->set_var( "url_category_array", urlencode( implode( "-", $CategoryArray ) ) );
    }

 //   $paramsArray["SearchExcludedArticles"] = "true";
    
    
    $article = new eZArticle();
    $totalCount = false;
    $articleList = $article->search( $SearchText, "time", true, $Offset, $Limit, $paramsArray, $totalCount );

    $t->set_var( "search_text", $SearchText );
    $t->set_var( "url_text", urlencode ( $SearchText ) );
}

if ( isset( $articleList ) && count ( $articleList ) > 0 )
{
    $locale = new eZLocale( $Language );
    $i=0;
    $t->set_var( "article_list", "" );
    foreach ( $articleList as $article )
    {
        $t->set_var( "article_name", $article->name() );

        $t->set_var( "article_id", $article->id() );

        if ( $article->isPublished() == true )
        {
            $t->parse( "article_is_published", "article_is_published_tpl" );
            $t->set_var( "article_not_published", "" );        
        }
        else
        {
            $t->set_var( "article_is_published", "" );
            $t->parse( "article_not_published", "article_not_published_tpl" );
        }

        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->parse( "article_item", "article_item_tpl", true );
        $i++;
    }
}

//echo "<pre>";
//print_r ( $t );

//echo "totalCount:".$totalCount."<br>";
//echo "Limit:".$Limit."<br>";
//echo "Offset:".$Offset."<br>";
//echo "</pre>";
//exit();

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "article_list_page_tpl" );

if ( isset( $articleList ) && count( $articleList ) > 0 )
{
    $t->parse( "article_list", "article_list_tpl" );
    $t->parse( "article_delete", "article_delete_tpl" );
}
else
{
    $t->set_var( "article_list", "" );
    $t->set_var( "article_delete", "" );
}

$t->set_var( "article_start", $Offset + 1 );
$t->set_var( "article_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "article_total", $totalCount );

$t->pparse( "output", "article_list_page_tpl" );

?>