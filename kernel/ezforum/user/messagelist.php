<?php
//
// $Id: messagelist.php 9550 2002-05-21 09:19:02Z jhe $
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

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezlist.php" );
// include_once( "ezsession/classes/ezpreferences.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezforum/classes/ezforum.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );
$NewMessageLimit = $ini->read_var( "eZForumMain", "NewMessageLimit" );
// include_once( "ezsection/classes/ezsection.php" );
$t = new eZTemplate( "kernel/ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "messagelist.php" );

    $languageIni = new INIFile( "kernel/ezforum/user/intl/" . $Language . "/messagelist.php.ini", false );
$t->set_file( "messagelist", "messagelist.tpl" );

$t->set_block( "messagelist", "header_list_tpl", "header_list" );
$t->set_block( "messagelist", "read_access_tpl", "read_access" );
$t->set_block( "messagelist", "no_access_tpl", "no_access" );

$t->set_block( "read_access_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "edit_message_item_tpl", "edit_message_item" );
$t->set_block( "read_access_tpl", "previous_tpl", "previous" );

$t->set_block( "message_item_tpl", "new_icon_tpl", "new_icon" );
$t->set_block( "message_item_tpl", "old_icon_tpl", "old_icon" );
$t->set_block( "message_item_tpl", "private_message_tpl", "private_message" );


$t->set_block( "read_access_tpl", "messages_element_tpl", "messages_element" );
$t->set_block( "read_access_tpl", "show_threads_tpl", "show_threads" );
$t->set_block( "read_access_tpl", "hide_threads_tpl", "hide_threads" );

//$t->set_var( "header_list", "" );
$t->setAllStrings();

$session =& eZSession::globalSession();
$session->fetch();

$user =& eZUser::currentUser();

if ( isset( $ForumMessages ) )
{
    if ( $user )
        eZPreferences::setVariable( "eZForum_ForumMessages", $ForumMessages );
    else
        $session->setVariable( "eZForum_ForumMessages", $ForumMessages );
    $UserLimit = $ForumMessages;
}
else
{
    if ( $user )
    {
        if ( eZPreferences::variable( "eZForum_ForumMessages" ) )
            $UserLimit = eZPreferences::variable( "eZForum_ForumMessages" );
        else
            $UserLimit = $ini->read_var( "eZForumMain", "MessageUserLimit" );
    }
    else
    {
        if ( $session->variable( "eZForum_ForumMessages" ) )
            $UserLimit = $session->variable( "eZForum_ForumMessages" );
        else
        {
            $UserLimit = $ini->read_var( "eZForumMain", "MessageUserLimit" );
            $session->setVariable( "eZForum_ForumMessages", $UserLimit );
        }
    }
}
$forum = new eZForum( $ForumID );

$categories =& $forum->categories();


if ( $user )
{
    $preferences = new eZPreferences();
    if ( isset( $HideThreads ) )
        $preferences->setVariable( "eZForum_Threads", "Hide" );

    if ( isset( $ShowThreads ) )
        $preferences->setVariable( "eZForum_Threads", "Show" );

    $showThreads =& $preferences->variable( "eZForum_Threads" );
}
else
{
    $session =& eZSession::globalSession();

    if ( isset( $HideThreads ) )
        $session->setVariable( "eZForum_Threads", "Hide" );

    if ( isset( $ShowThreads ) )
        $session->setVariable( "eZForum_Threads", "Show" );

    $showThreads = $session->variable( "eZForum_Threads" );
}

if ( $showThreads == "" )
    $showThreads = "Hide";

$group =& $forum->group();
$viewer = $user;

if ( is_a( $group, "eZUserGroup" ) )
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

if ( count( $categories ) > 0 )
{
    $category = new eZForumCategory( $categories[0]->id() );

    $t->set_var( "category_id", $category->id( ) );
    $t->set_var( "category_name", $category->name( ) );

    $t->parse( "header_list", "header_list_tpl" );

    // sections
    // include_once( "ezsitemanager/classes/ezsection.php" );

    $GlobalSectionID = eZForumCategory::sectionIDStatic( $category->id() );

    // init the section
    $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
    $sectionObject->setOverrideVariables();

}

$locale = new eZLocale( $Language );

if ( !isset( $Offset ) or !$Offset )
    $Offset = 0;

$limit_array = array_unique( array( 20, 30, 40, 50, 60, 80, 100, 150, 200, $UserLimit ) );
sort( $limit_array );

foreach ( $limit_array as $element_number )
{
    $t->set_var( "messages_number", $element_number );
    if ( $element_number == $UserLimit )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );
    $t->parse( "messages_element", "messages_element_tpl", true );
}

$t->set_var( "offset", $Offset );

if ( $showThreads == "Hide" )
{
    $t->set_var( "hide_threads", "" );
    $t->parse( "show_threads", "show_threads_tpl" );
    $messageList =& $forum->messageTreeArray( $Offset, $UserLimit, false, false );
    $messageCount =& $forum->messageCount( false, false );
}
else if ( $showThreads == "Show" )
{
    $t->set_var( "show_threads", "" );
    $t->parse( "hide_threads", "hide_threads_tpl" );
    $messageList =& $forum->messageTreeArray( $Offset, $UserLimit );
    $messageCount =& $forum->messageCount( false, true );
}

if ( !$messageList )
{
    $languageIni = new INIFile( "kernel/ezforum/user/intl/" . $Language . "/messagelist.php.ini", false );
    $noitem =  $languageIni->read_var( "strings", "noitem" );

    $t->set_var( "message_item", $noitem );
}
else
{
    $db =& eZDB::globalDatabase();

    $level = 0;
    $i = 0;
    $time = new eZDateTime();
    foreach ( $messageList as $message )
    {
        unset( $author );
        $author = new eZUser();
        $t->set_var( "user", "" );
        $t->set_var( "edit_message_item", "" );

        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

		$topic = htmlspecialchars( $message[$db->fieldName( "Topic" )]);
        $t->set_var( "topic", stripslashes($topic) );

        $time->setTimeStamp( $message[$db->fieldName( "PostingTime" )] );
        $t->set_var( "postingtime", $locale->format( $time  ) );

        $t->set_var( "message_id", $message[$db->fieldName( "ID" )] );

        $messageAge = round( $message[$db->fieldName( "Age" )] / 86400 );
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

        $userID = $message[$db->fieldName( "UserID" )];

        $author->get( $userID );

        if ( $showThreads == "Show" )
        {
            $t->set_var( "count_replies", "" );
            $level = $message[$db->fieldName( "Depth" )];

            if ( $level > 0 )
                $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
            else
                $t->set_var( "spacer", "" );
        }
        else if ( $showThreads == "Hide" )
        {
            $count = eZForumMessage::threadMessageCount( $message[$db->fieldName( "ThreadID" )] ) - 1;
            $t->set_var( "spacer", "" );
            $t->set_var( "count_replies", $count );
        }

        $anonymous = $ini->read_var( "eZForumMain", "AnonymousPoster" );

        if ( $author->id() == 0 )
        {
            if ( empty( $message[$db->fieldName( "UserName" )] ) ) {
                $t->set_var( "user", $anonymous );
                $messageAuthor = $anonymous;    
            }
            else {
                $t->set_var( "user", $message[$db->fieldName( "UserName" )] );
                $messageAuthor = $message[$db->fieldName( "UserName" )];
            }

            $t->set_var( "author", $messageAuthor );
            $t->set_var( "private_message", "" );
        }
        else
        {
            $messageAuthor = $author->firstName() . " " . $author->lastName();
            $t->set_var( "user", $author->firstName() . " " . $author->lastName() );
            if ( $author->firstName()== "" && $author->lastName()=="" )
                $messageAuthor = $anonymous;

                $t->set_var( "author", $messageAuthor );
                
                $currentUser =& eZUser::currentUser();
                $t->set_var( "private_message", "" );
                if ( ( $messageAuthor != $anonymous) && ($currentUser) )
                { 
            //		$user = new eZUser();
            //		$user->get( $author->id() );
                    $t->set_var( "username", $author->login() );
                    $t->set_var( "PM_topic", urlencode(": ".$message[$db->fieldName( "Topic" )] ) );
            //		$t->set_var( "topic", $message->topic() );
                    $t->parse( "private_message", "private_message_tpl" );
                }
        }

        /*
        if ( get_class( $viewer ) == "ezuser" )
        {
            if ( $viewer->id() == $userID && eZForumMessage::countReplies( $message["ID"] ) == 0 && !$forum->IsModerated() )
            {
                $t->parse( "edit_message_item", "edit_message_item_tpl" );
            }
        }
        */
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
}

unset( $user );

eZList::drawNavigator( $t, $messageCount, $UserLimit, $Offset, "read_access_tpl" );

$t->set_var( "forum_start", $Offset + 1 );
$t->set_var( "forum_end", min( $Offset + $UserLimit, $messageCount ) );
$t->set_var( "forum_total", $messageCount );

if ( !isset( $newmessage ) )
    $newmessage = "";
$t->set_var( "newmessage", $newmessage );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

if ( $readPermission )
{
    $t->set_var( "no_access", "" );
    $t->parse( "read_access", "read_access_tpl" );
}
else
{
    $t->set_var( "read_access", "" );
    $t->parse( "no_access", "no_access_tpl" );
}

$t->pparse( "output", "messagelist" );

?>