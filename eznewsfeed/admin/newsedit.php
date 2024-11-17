<?php
// 
// $Id: newsedit.php 9168 2002-02-07 08:39:56Z jhe $
//
// Created on: <16-Nov-2000 13:02:32 bf>
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
include_once( "classes/ezdatetime.php" );

include_once( "eznewsfeed/classes/eznews.php" );

include_once( "classes/ezdatetime.php" );

include_once( "eznewsfeed/classes/eznews.php" );
include_once( "eznewsfeed/classes/eznewscategory.php" );
include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /newsfeed/archive/" );
    exit();
}


if ( $Action == "Insert" )
{
    $category = new eZNewsCategory( $CategoryID );
    
    $news = new eZNews( );

    $news->setName( $NewsTitle );
    $news->setIntro( $NewsIntro );
    
    if ( $IsPublished == "on" )
    {
        $news->setIsPublished( 1 );
    }
    else
    {
        $news->setIsPublished( 0 );
    }

    $news->setKeywords( $NewsKeywords );
    $news->setOrigin( $NewsSource );
    $news->setURL( $NewsURL );
    
    $dateTime = new eZDateTime( $Year, $Month, $Day, $Hour, $Second, $Minute );
    $news->setOriginalPublishingDate( $dateTime );

    $news->store();

    $category->addNews( $news );

    // delete the cache
    $dir = eZFile::dir( "eznewsfeed/cache/" );
    $files = array();
    while ( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "latestnews,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
            
            if ( ereg( "headlines,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
        }
    } 
    $dir->close();
    if ( $news->isPublished() == 1 )
    {
        eZHTTPTool::header( "Location: /newsfeed/archive/$CategoryID/" );
    }
    else
    {
        eZHTTPTool::header( "Location: /newsfeed/unpublished/$CategoryID/" );        
    }

    exit();
}

if ( $Action == "Update" )
{
    $category = new eZNewsCategory( $CategoryID );
    
    $news = new eZNews( $NewsID );

    $news->setName( $NewsTitle );
    $news->setIntro( $NewsIntro );

    if ( $IsPublished == "on" )
    {
        $news->setIsPublished( 1 );
    }
    else
    {
        $news->setIsPublished( 0 );
    }
    

    $news->setKeywords( $NewsKeywords );
    $news->setOrigin( $NewsSource );
    $news->setURL( $NewsURL );
    
    $dateTime = new eZDateTime( $Year, $Month, $Day, $Hour, $Second, $Minute );
    $news->setOriginalPublishingDate( $dateTime );

    $news->store();

    $news->removeFromCategories();
    $category->addNews( $news );

    // delete the cache
    $dir = eZFile::dir( "eznewsfeed/cache/" );
    $files = array();
    while ( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "latestnews,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( ( $regArray[1] == $CategoryID ) ||
                     ( $regArray[1] == $OldCategoryID ) )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
            
            if ( ereg( "headlines,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( ( $regArray[1] == $CategoryID ) ||
                     ( $regArray[1] == $OldCategoryID ) )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
        }
    } 
    $dir->close();

    if ( $news->isPublished() == 1 )
    {
        eZHTTPTool::header( "Location: /newsfeed/archive/$CategoryID/" );
    }
    else
    {
        eZHTTPTool::header( "Location: /newsfeed/unpublished/$CategoryID/" );        
    }
    exit();
}


if ( $Action == "Delete" )
{
    $news = new eZNews( $NewsID );

    $cats = $news->categories();
    $defCat = $cats[0];

    $CategoryID = $defCat->id();

    // delete the cache
    $dir = eZFile::dir( "eznewsfeed/cache/" );
    $files = array();
    while ( $entry = $dir->read() )
    { 
        if ( $entry != "." && $entry != ".." )
        {
            if ( ereg( "latestnews,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
            
            if ( ereg( "headlines,([^,]+)\..*", $entry, $regArray  ) )
            {
                if ( $regArray[1] == $CategoryID )
                {
                    eZFile::unlink( "eznewsfeed/cache/" . $entry );
                }
            }
        }
    } 
    $dir->close();
    
    $news->delete();
    
    eZHTTPTool::header( "Location: /newsfeed/archive/$CategoryID/" );
    exit();
}


$ini = INIFile::globalINI();

$Language = $ini->read_var( "eZNewsfeedMain", "Language" );
$ImageDir = $ini->read_var( "eZNewsfeedMain", "ImageDir" );

$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsfeedMain", "AdminTemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "newsedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "news_edit_page_tpl" => "newsedit.tpl"
    ) );

$t->set_block( "news_edit_page_tpl", "value_tpl", "value" );

$t->set_var( "action_value", "Insert" );

$t->set_var( "news_title_value", "" );
$t->set_var( "news_source_value", "" );

$today = new eZDateTime();

$t->set_var( "news_year_value", $today->year() );
$t->set_var( "news_month_value", $today->month() );
$t->set_var( "news_day_value", $today->day() );
$t->set_var( "news_hour_value", $today->hour() );
$t->set_var( "news_minute_value", $today->minute() );
$t->set_var( "news_second_value", $today->second() );
$t->set_var( "news_intro_value", "" );
$t->set_var( "news_url_value", "" );
$t->set_var( "news_keywords_value", "" );
$t->set_var( "news_id", "" );

if ( $Action == "Edit" )
{
    $news = new eZNews( $NewsID );

    $published = $news->originalPublishingDate();

    $t->set_var( "news_year_value", $published->year() );
    $t->set_var( "news_month_value", $published->month() );
    $t->set_var( "news_day_value", $published->day() );
    $t->set_var( "news_hour_value", $published->hour() );
    $t->set_var( "news_minute_value", $published->minute() );
    $t->set_var( "news_second_value", $published->second() );
    
    $t->set_var( "news_title_value", $news->name() );
    $t->set_var( "news_source_value", $news->origin() );
    $t->set_var( "news_intro_value", $news->intro() );
    $t->set_var( "news_url_value", $news->url() );
    $t->set_var( "news_keywords_value", $news->keywords() );
    $t->set_var( "news_id", $news->id() );
    $t->set_var( "action_value", "Update" );


    if ( $news->isPublished() == 1 )
    {
        $t->set_var( "news_is_published", "checked" );
    }
    else
    {
        $t->set_var( "news_is_published", "" );
    }
    
    $cats = $news->categories();
    $defCat = $cats[0];
    $t->set_var( "old_category_id", $defCat->id() );
}

// category select
$category = new eZNewsCategory();
$categoryArray = $category->getAll( );

foreach ( $categoryArray as $catItem )
{
    if ( $Action == "Edit" )
    {
        if ( $defCat->id() == $catItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }    
    
    $t->set_var( "option_value", $catItem->id() );
    $t->set_var( "option_name", $catItem->name() );

    $t->parse( "value", "value_tpl", true );    
}

$t->pparse( "output", "news_edit_page_tpl" );

?>
