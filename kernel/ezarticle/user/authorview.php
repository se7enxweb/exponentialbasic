<?php
// 
// $Id: authorview.php 6484 2001-08-17 13:36:01Z jhe $
//
// Created on: <16-Feb-2001 15:36:13 amos>
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
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezlist.php" );

// include_once( "ezarticle/classes/ezarticle.php" );
// include_once( "ezuser/classes/ezauthor.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZArticleMain", "Language" );
$Limit = $ini->variable( "eZArticleMain", "AuthorArticleLimit" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->variable( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl/", $Language, "authorview.php" );

$t->setAllStrings();

$t->set_file( "author_view_tpl", "authorview.tpl" );

$t->set_block( "author_view_tpl", "article_item_tpl", "article_item" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;
if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isset( $SortOrder ) )
    $SortOrder = "published";

$article_count =& eZArticle::authorArticleCount( $AuthorID );

$t->set_var( "article_count", $article_count );
$t->set_var( "article_start", $Offset + 1 );
$t->set_var( "article_end", min( $Offset + $Limit, $article_count ) );

$articles =& eZArticle::authorArticleList( $AuthorID, $Offset, $Limit, $SortOrder );

$t->set_var( "author_id", $AuthorID );
$author = new eZAuthor( $AuthorID );
$t->set_var( "author_name", $author->name() );
$t->set_var( "author_mail", $author->email() );

$t->set_var( "sort", $SortOrder );

$t->set_var( "article_item", "" );

$db =& eZDB::globalDatabase();
$i = 0;
$dateTime = new eZDateTime();
foreach( $articles as $article )
{
    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
    $t->set_var( "article_id", $article[$db->fieldName("ID")] );
    $t->set_var( "article_name", htmlspecialchars( $article[$db->fieldName("Name")] ) );
    $t->set_var( "category_id", $article[$db->fieldName("CategoryID")] );
    $t->set_var( "article_category", $article[$db->fieldName("CategoryName")] );
    $t->set_var( "author_name", $article[$db->fieldName("AuthorName")] );
    $dateTime->setTimeStamp( $article[$db->fieldName("Published")] );
    $t->set_var( "article_published", $locale->format( $dateTime ) );
    $t->parse( "article_item", "article_item_tpl", true );
    $i++;
}

eZList::drawNavigator( $t, $article_count, $Limit, $Offset, "author_view_tpl" );

$t->pparse( "output", "author_view_tpl" );

?>