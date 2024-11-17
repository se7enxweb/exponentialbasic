<?php
// 
// $Id: messagesearch.php 7420 2001-09-24 11:53:43Z jhe $
//
// Created on: <21-Feb-2001 18:00:00 pkej>
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

if ( $ShowSearch == true )
{
    $t->set_file( "message_path", "messagepath.tpl"  );
    $t->set_block( "message_path", "article_message_tpl", "article_message_item" );
    $t->set_block( "article_message_tpl", "article_topic_tpl", "article_topic_item" );
    $t->set_block( "message_path", "forum_message_tpl", "forum_message_item" );
    $t->set_block( "forum_message_tpl", "forum_topic_tpl", "forum_topic_item" );

    $t->set_var( "article_message_item", "" );
    $t->set_var( "article_topic_item", "" );
    $t->set_var( "forum_message_item", "" );
    $t->set_var( "forum_topic_item", "" );

    if ( !is_object( $msg ) )
    {
        $msg = new eZForumMessage( $MessageID );
        $MessageTopic = $msg->topic();
        $ForumID = $msg->forumId();
    }
    
    $forum = new eZForum( $ForumID );
    $ForumName = $forum->name();
    $categories = $forum->categories();
    
    if ( is_object( $categories[0] ) )
    {
        $ForumCategory = new eZForumCategory( $categories[0]->id() );
        $ForumCategoryID = $ForumCategory->id();
        if ( empty( $ForumCategoryName ) || $MessagePathOverride == true )
        {
            $ForumCategoryName = $ForumCategory->name();
        }
    }
    else
    {
        include_once( "ezarticle/classes/ezarticle.php" );

        $ArticleID = eZArticle::articleIDFromForum( $ForumID );

        $article = new eZArticle( $ArticleID );
        $ArticleID = $article->id();
        $ArticleName = $article->name();

        $isArticle = true;
    }

    $t->set_var( "message_topic", $MessageTopic );
    $t->set_var( "message_id", $MessageID );

    $t->set_var( "category_name", $ForumCategoryName );
    $t->set_var( "category_id", $ForumCategoryID );

    $t->set_var( "forum_id", $ForumID );
    $t->set_var( "forum_name", $ForumName );

    $t->set_var( "article_id", $ArticleID );
    $t->set_var( "article_name", $ArticleName );

    $t->set_var( "article_message_item", "" );
    $t->set_var( "article_topic_item", "" );
    $t->set_var( "forum_message_item", "" );
    $t->set_var( "forum_topic_item", "" );

    if ( $isArticle == true )
    {
        if ( $isPreview == false )
        {
            $t->parse( "article_topic_item", "article_topic_tpl" );
        }
        $t->parse( "article_message_item", "article_message_tpl" );
    }
    else
    {
        if ( $isPreview == false )
        {
            $t->parse( "forum_topic_item", "forum_topic_tpl" );
        }
        $t->parse( "forum_message_item", "forum_message_tpl" );
    }

    if ( $doPrint == true )
    {
        $t->pparse( "message_path_file", "message_path" );
    }
    else
    {
        $t->parse( "message_path_file", "message_path" );
    }
}
else
{
    $t->set_var( "message_path_file", "" );
}
?>
