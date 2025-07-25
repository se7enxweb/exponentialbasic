<?php
//
// $Id: message.php 9518 2002-05-08 11:51:36Z vl $
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZForumMain", "Language" );
$NewMessageLimit = $ini->read_var( "eZForumMain", "NewMessageLimit" );
$AllowHTML = $ini->read_var( "eZForumMain", "AllowHTML" );

// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztexttool.php" );
// include_once( "classes/eztemplate.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezforum/classes/ezforum.php" );

$locale = new eZLocale( $Language );

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "message.php" );
$t->setAllStrings();

$t->set_file( "message_tpl", "message.tpl" );

$t->set_block( "message_tpl", "message_body_tpl", "message_body" );
$t->set_block( "message_tpl", "message_error_tpl", "message_error" );
$t->set_block( "message_body_tpl", "header_list_tpl", "header_list" );
$t->set_block( "message_body_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_body_tpl", "edit_current_message_item_tpl", "edit_current_message_item" );
$t->set_block( "message_body_tpl", "private_message_tpl", "private_message" );
$t->set_block( "message_item_tpl", "edit_message_item_tpl", "edit_message_item" );

$t->set_block( "message_item_tpl", "new_icon_tpl", "new_icon" );
$t->set_block( "message_item_tpl", "old_icon_tpl", "old_icon" );
$t->set_block( "message_item_tpl", "item_private_message_tpl", "item_private_message" );
$t->set_var( "private_message", "" );
$t->set_var( "header_list", "" );
$t->set_var( "edit_current_message_item", "" );

$message = new eZForumMessage( $MessageID );

$forum = new eZForum( $message->forumID() );

$group =& $forum->group();
$viewer = $user;
if ( ( is_a( $group, "eZUserGroup" ) ) && ( $group->id() != 0 ) )
{
    if ( is_a( $viewer, "eZUser" ) )
    {
        $groupList =& $viewer->groups();

        foreach ( $groupList as $userGroup )
        {
            if ( $userGroup->id() == $group->id() )
            {
                $readPermission = true;
                break;
            }
        }
    }
}
else
{
    $readPermission = true;
}

$categories = $forum->categories();

if ( count( $categories ) > 0 )
{
    $category = new eZForumCategory( $categories[0]->id() );

    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );

    $t->parse( "header_list", "header_list_tpl" );

    // sections
    // include_once( "ezsitemanager/classes/ezsection.php" );

    $GlobalSectionID = eZForumCategory::sectionIDStatic( $category->id( )  );

    // init the section
    $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
    $sectionObject->setOverrideVariables();
}

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->set_var( "message_id", $message->id() );
$t->set_var( "message_topic", $message->topic() );

$t->set_var( "topic", $message->topic() );

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

if ( $author->firstName()== "" && $author->lastName()=="" )
	$MessageAuthor = $anonymous;

$t->set_var( "main-user", $MessageAuthor );


$user =& eZUser::currentUser();
if ( ( $MessageAuthor != $anonymous) and ($user) )
{
//	$user = new eZUser();
//	$user->get( $author->id() );
	$t->set_var( "username", $author->login() );
	$t->set_var( "PM_topic", urlencode (": ".$message->topic() ) );
$t->set_var( "topic", $message->topic() );
	$t->parse( "private_message", "private_message_tpl" );
}


$time = $message->postingTime();
$t->set_var( "main-postingtime", $locale->format( $time ) );
$t->set_var( "body", eZTextTool::nl2br( $message->body() ) );

$t->set_var( "reply_id", $message->id() );
$t->set_var( "forum_id", $forum->id() );

if ( is_a( $viewer, "eZUser" ) )
{
    if ( $viewer->id() == $message->userId() && eZForumMessage::countReplies( $message->id() ) == 0 )
    {
        $t->parse( "edit_current_message_item", "edit_current_message_item_tpl" );
    }
}

$topMessage = $message->threadTop( $message );

// print out the replies tree

$messages = $forum->messageThreadTree( $message->threadID() );

//  $messages = $forum->messages();

$level = 0;

$i = 0;

foreach ( $messages as $threadmessage )
{
    $t->set_var( "edit_message_item", "" );
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
        $t->set_var( "td_alt", "1" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
        $t->set_var( "td_alt", "2" );
    }

    $level = $threadmessage->depth();

    if ( $threadmessage->id() == $MessageID )
    {
        $t->set_var( "link_color", "linkselect" );
        $t->set_var( "td_class", "bgselect" );
    }
    else
    {
        $t->set_var( "link_color", "linknormal" );
    }

    if ( $level > 0 )
        $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
    else
        $t->set_var( "spacer", "" );

    $t->set_var( "reply_id", $threadmessage->id() );
    $t->set_var( "reply_topic", $threadmessage->topic() );

    if ( $AllowHTML == "enabled" )
        $t->set_var( "reply_body", eZTextTool::nl2br( $threadmessage->body( true ) ) );
    else
        $t->set_var( "reply_body", eZTextTool::nl2br( $threadmessage->body( false ) ) );

    $messageAge = round( $threadmessage->age() / 86400 );
    if ( $messageAge <= $NewMessageLimit )
    {
        $t->parse( "new_icon", "new_icon_tpl" );
        $t->set_var( "old_icon", "" );
    }
    else
    {
        $t->parse( "old_icon", "old_icon_tpl" );
        $t->set_var( "new_icon", "" );
    }

    $time = $threadmessage->postingTime();

    $t->set_var( "postingtime", $locale->format( $time ) );
    $t->set_var( "message_id", $threadmessage->id() );

    $author = $threadmessage->user();

    if ( $author->id() == 0 )
    {
        if ( $threadmessage->userName() )
            $MessageAuthor = $threadmessage->userName();
        else
            $MessageAuthor = $anonymous;
    }
    else
    {
        $MessageAuthor = $author->firstName() . " " . $author->lastName();
    }
    
	if ( $author->firstName()== "" && $author->lastName()=="" )
	$MessageAuthor = $anonymous;

    $t->set_var( "user", $MessageAuthor );

//	$currentUser =& eZUser::currentUser();
	$t->set_var( "item_private_message", "" );
	if ( ( $MessageAuthor != $anonymous) and ($user) )
	{
//	$user = new eZUser();
//	$user->get( $author->id() );
	$t->set_var( "username", $author->login() );
	$t->set_var( "PM_topic", urlencode (": ".$message->topic() ) );
	$t->parse( "item_private_message", "item_private_message_tpl" );
	}

//unset( $user );

    /*
    if ( get_class( $viewer ) == "ezuser" )
    {
        if ( ( $viewer->id() == $threadmessage->userId() ) && ( eZForumMessage::countReplies( $threadmessage->id() ) == 0 ) )
        {
            $t->parse( "edit_message_item", "edit_message_item_tpl" );
        }
    }
    */
    $t->parse( "message_item", "message_item_tpl", true );
    $i++;
}

if ( !isset( $RedirectURL ) )
    $RedirectURL = "";

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
if ( $message->id() > 0 && !$message->isTemporary() && $message->isApproved() )
{
    $t->parse( "message_body", "message_body_tpl" );
    $t->set_var( "message_error", "" );
}
else
{
    $t->set_var( "message_body", "" );
    $t->parse( "message_error", "message_error_tpl" );
}

if ( $readPermission )
    $t->pparse( "output", "message_tpl" );

?>