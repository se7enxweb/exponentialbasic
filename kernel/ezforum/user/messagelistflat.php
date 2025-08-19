<?php
//
// $Id: messagelistflat.php 7759 2001-10-10 13:18:29Z jhe $
//
// Created on: <03-Jul-2001 13:24:26 bf>
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

$ini =& eZINI::instance( 'site.ini' );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezlist.php" );
// include_once( "ezsession/classes/ezpreferences.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezforum/classes/ezforum.php" );

$Language = $ini->variable( "eZForumMain", "Language" );
$UserLimit = $ini->variable( "eZForumMain", "MessageUserLimit" );
$NewMessageLimit = $ini->variable( "eZForumMain", "NewMessageLimit" );

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->variable( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "messagelistflat.php" );

$t->set_file( "messagelist_tpl", "messagelistflat.tpl"  );

$t->set_block( "messagelist_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "edit_message_item_tpl", "edit_message_item" );
$t->set_block( "messagelist_tpl", "previous_tpl", "previous" );

$t->set_block( "message_item_tpl", "new_icon_tpl", "new_icon" );
$t->set_block( "message_item_tpl", "old_icon_tpl", "old_icon" );

$t->setAllStrings();

$forum = new eZForum( $ForumID );

$categories =& $forum->categories();

$user =& eZUser::currentUser();


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

    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
}

$locale = new eZLocale( $Language );

if ( !$Offset )
    $Offset = 0;

$messageList =& $forum->messageTreeArray( $Offset, $UserLimit, false, false );
$messageCount =& $forum->messageCount( false, true );

if ( !$messageList )
{
    $languageIni = new eZINI( "kernel/ezforum/user/intl/" . $Language . "/messagelistflat.php.ini", false );
    $noitem =  $languageIni->variable( "strings", "noitem" );

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
        $author = new eZUser();
        $t->set_var( "user", "" );
        $t->set_var( "edit_message_item", "" );

        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "articlelist1" );
        else
            $t->set_var( "td_class", "articlelist2" );

        $t->set_var( "topic", $message[$db->fieldName( "Topic" )] );
        $t->set_var( "body", $message[$db->fieldName( "Body" )] );

        $time->setTimeStamp( $message[$db->fieldName( "PostingTime" )] );
        $t->set_var( "postingtime", $locale->format( $time ) );

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

        $t->set_var( "count_replies", "" );
        $level = $message[$db->fieldName( "Depth" )];

        if ( $level > 0 )
            $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
        else
            $t->set_var( "spacer", "" );

        if ( $author->id() == 0 )
        {
            $t->set_var( "user", $ini->variable( "eZForumMain", "AnonymousPoster" ) );
        }
        else
        {
            $t->set_var( "user", $author->firstName() . " " . $author->lastName() );
        }

        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
}
eZList::drawNavigator( $t, $messageCount, $UserLimit, $Offset, "messagelist_tpl" );

$t->set_var( "forum_start", $Offset + 1 );
$t->set_var( "forum_end", min( $Offset + $UserLimit, $messageCount ) );
$t->set_var( "forum_total", $messageCount );

$t->set_var( "newmessage", $newmessage );

$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

if ( $readPermission )
    $t->pparse( "output", "messagelist_tpl" );

?>