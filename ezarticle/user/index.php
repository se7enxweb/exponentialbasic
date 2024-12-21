<?php
// 
// $Id: index.php 7506 2001-09-27 10:26:56Z bf $
//
// Created on: <27-Apr-2001 10:15:40 amos>
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
// include_once( "classes/ezlist.php" );

// include_once( "ezarticle/classes/ezarticlecategory.php" );
// include_once( "ezarticle/classes/ezarticle.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "index.php" );

$t->setAllStrings();

$t->set_file( "index_tpl", "index.tpl" );

$t->set_block( "index_tpl", "index_item_tpl", "index_item" );
$t->set_block( "index_item_tpl", "article_item_tpl", "article_item" );
$t->set_block( "article_item_tpl", "comma_item_tpl", "comma_item" );
$t->set_block( "index_tpl", "letter_item_tpl", "letter_item" );

$t->set_var( "index_item", "" );

$letterArray =& eZArticle::keywordFirstLetters();

if (  !in_array( $CurrentIndex, $letterArray ) )
{
    $CurrentIndex = $letterArray[0];
}

$t->set_var( "current_letter", $CurrentIndex );


foreach ( $letterArray as $letter )
{
    $t->set_var( "letter", $letter );
    $t->parse( "letter_item", "letter_item_tpl", true );
}


$indexes =& eZArticle::manualKeywordIndex( $CurrentIndex );
foreach( $indexes as $indexKeyword )
{
    $t->set_var( "article_item", "" );
    $t->set_var( "index_name", $indexKeyword );
    $articles =& eZArticle::searchByShortContent( "", array( $indexKeyword ) );
    $i = 0;
    foreach( $articles as $article )
    {
        $t->set_var( "comma_item", "" );
        if ( $i > 0 )
            $t->parse( "comma_item", "comma_item_tpl" );
        $t->set_var( "article_name", $article->name() );
        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_page", 1 );
        $cats = $article->categories( false );
        $t->set_var( "article_category", $cats[0] );
        $t->parse( "article_item", "article_item_tpl", true );
        ++$i;
    }
    $t->parse( "index_item", "index_item_tpl", true);
}

if ( $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");
    $output = $t->parse( "output", "index_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "index_tpl" );
}

?>
