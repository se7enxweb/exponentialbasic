<?php
//
// $Id: topiclist.php 9552 2002-05-22 10:34:54Z jhe $
//
// Created on: <03-Sep-2001 15:35:07 bf>
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

// include_once( "ezarticle/classes/eztopic.php" );
$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "kernel/ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "kernel/ezarticle/user/intl", $Language, "topiclist.php" );

$locale = new eZLocale( $Language );

$t->set_file( "topic_page_tpl", "topiclist.tpl" );

$t->setAllStrings();

$t->set_block( "topic_page_tpl", "topic_list_tpl", "topic_list" );
$t->set_block( "topic_list_tpl", "topic_item_tpl", "topic_item" );
$t->set_block( "topic_item_tpl", "article_item_tpl", "article_item" );

$topic = new eZTopic();

if ( $topic->get( $TopicID ) )
{
    $topicArray = array( $topic );
}
else
{
    $topicArray = $topic->getAll();
}

$t->set_var( "topic_item", "" );
$i = 0;

foreach ( $topicArray as $topic )
{
    $t->set_var( "id", $topic->id() );
    $t->set_var( "topic_name", $topic->name() );
    $t->set_var( "topic_description", $topic->description() );

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $articles =& $topic->articles();

    $t->set_var( "article_item", "" );
    foreach ( $articles as $article )
    {
        $category = $article->categoryDefinition();

        $t->set_var( "category_id", $category->id() );
        $t->set_var( "category_name", $category->name() );

        $t->set_var( "article_id", $article->id() );
        $t->set_var( "article_name", $article->name() );
        $t->parse( "article_item", "article_item_tpl", true );
    }

    $t->parse( "topic_item", "topic_item_tpl", true );
    $i++;
}

$t->parse( "topic_list", "topic_list_tpl" );

$t->pparse( "output", "topic_page_tpl" );

?>
