<?php
//
// $Id: messagelist.php 7788 2001-10-11 12:00:35Z jhe $
//
// Created on: Created on: <18-Jul-2000 08:56:19 lw>
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
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );
$AdminLimit = $ini->read_var( "eZForumMain", "MessageAdminLimit" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
                     "ezforum/admin/" . "/intl", $Language, "messagelist.php" );
$t->setAllStrings();

$t->set_file( "message_page", "messagelist.tpl" );

$t->set_block( "message_page", "message_item_tpl", "message_item" );

$t->set_var( "site_style", $SiteDesign );

$forum = new eZForum( $ForumID );
$t->set_var( "forum_name", $forum->name() );

$categories = $forum->categories();

if ( count( $categories ) > 0 )
{
    $CategoryID = $categories[0]->id();
    $category = new eZForumCategory( $CategoryID );

    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $CategoryID );
}

$locale = new eZLocale( $Language );

$messages = $forum->messageTree( $Offset, $AdminLimit );
$messageCount = $forum->messageCount();

$languageIni = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/messagelist.php.ini", false );

$true =  $languageIni->read_var( "strings", "true" );
$false =  $languageIni->read_var( "strings", "false" );

$AnonymousPoster = $ini->read_var( "eZForumMain", "AnonymousPoster" );

if ( !$messages )
{
    $noitem = $languageIni->read_var( "strings", "noitem" );
    $t->set_var( "message_item", $noitem );
}
else
{
    $level = 0;
    $i = 0;
    foreach ( $messages as $message )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $level = $message->depth();
        
        if ( $level > 0 )
            $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
        else
            $t->set_var( "spacer", "" );
        
        $t->set_var( "message_topic", $message->topic() );
        
        $t->set_var( "message_postingtime", $locale->format( $message->postingTime() ) );
        
        $t->set_var( "message_id", $message->id() );
        
        $author = $message->user();

        if ( $message->userName() )
            $anonymous = $message->userName();
        else
            $anonymous = $ini->read_var( "eZForumMain", "AnonymousPoster" );
        
        if ( $author->id() == 0 )
        {
            $MessageAuthor = $anonymous;
        }
        else
        {
            $MessageAuthor = $author->firstName() . " " . $author->lastName();
        }
        
        $t->set_var( "message_user", $MessageAuthor );

        if ( $message->emailNotice() == "Y" )
            $t->set_var( "emailnotice", $true );
        else
            $t->set_var( "emailnotice", $false );
        
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $messageCount, $AdminLimit, $Offset, "message_page" );

$t->set_var( "forum_start", $Offset + 1 );
$t->set_var( "forum_end", min( $Offset + $AdminLimit, $messageCount ) );
$t->set_var( "forum_total", $messageCount );

$t->set_var( "link1-url", "");
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "admin/forum.php" );
$t->set_var( "forum_id", $ForumID );

$t->pparse( "output", "message_page" );

?>