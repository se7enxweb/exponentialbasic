<?php
//
// $Id: messagepermissions.php 7782 2001-10-11 11:06:20Z jhe $
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

// "Return" values set to most secure experience, our job
// is then to apply these to the job at hand.

// Is it possible to read this message;
$MessageRead = false;

// If a message has replies it cannot be edited.
$MessageEdit = false;

// Only owners can delete a message; it also has to have no replies.
$MessageDelete = false;

// If a message is temporary, then no replies can be added.
$MessageReply = false;

// Is it possible to add a message to this forum?
$ForumPost = false;

// Is it possible to read this  this forum?
$ForumRead = false;


// Which checks should be performed? All by default, this is just here for easy
// partitioning with if''s and for debugging purposes. Will also check if the
// pre-requisites are there for the test!

$CheckMessageRead = true;
$CheckMessageDelete = true;
$CheckMessageEdit = true;
$CheckMessageReply = true;
$CheckForumPost = true;
$CheckForumRead = true;

// Anonymous users "have" user id 0.
if ( is_object( $user ) )
{
    $UserID = $user->id();
}
else
{
    $UserID = 0;
}

// If a forum id isn''t provided for checking, we can''t check
// the forum permissions.
if ( $CheckForumID > 0 )
{
    $checkForum = new eZForum( $CheckForumID );
}
else
{
    $CheckForumPost = false;
    $CheckForumRead = false;
    // No point in checking for reading of message if you can''t access the forum
    $CheckMessageID = false;
}

// If a message id isn''t provided for checking, we can''t check
// the message permissions.
if ( $CheckMessageID > 0 )
{
    $checkMessage = new eZForumMessage( $CheckMessageID );
    // Check if the current user is the message owner.

    if ( $checkMessage->userID() == $UserID )
    {
        $MessageRead = true;
        $MessageOwner = true;
    }
}
else
{
    $CheckMessageReply = false;
    $CheckMessageRead = false;
    $CheckMessageEdit = false;
    $CheckMessageDelete = false;
}


// You can read all forums unless they''re set to only allow
// a certain group of people.
if ( $CheckForumRead )
{
    $group =& $checkForum->group();

    if ( ( is_a( $group, "eZUserGroup" ) ) && ( $group->id() != 0 ) )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $groupList =& $user->groups();

            foreach ( $groupList as $userGroup )
            {
                if ( $userGroup->id() == $group->id() )
                {
                    $ForumRead = true;
                    break;
                }
            }
        }
    }
    else
    {
        $ForumRead = true;
    }
}

// You can post to a forum you can read if you''re a logged in user.
// If the forum is set to anonymous anyone can post.
if ( $CheckForumPost && $ForumRead )
{
    if ( $checkForum->isAnonymous() == true )
    {
        $ForumPost = true;
    }
    else
    {
        if ( $ForumRead == true && $UserID != 0 )
        {
            $ForumPost = true;
        }
    }
}

// If you can read the forum, you can read the message if:
//    * it is approved when in a moderated forum
//    * it is your own when it is a temporary message
//    * none of the above conditions are met
if ( $CheckMessageRead && $ForumRead )
{
    if ( $checkMessage->isTemporary() == true )
    {
        if ( $MessageOwner == true )
        {
            $MessageRead = true;
        }
    }
    else
    {
        if ( $checkForum->isModerated() == true )
        {
            if ( $checkMessage->isApproved() == true )
            {
                $MessageRead = true;
            }
        }
        else
        {
            $MessageRead = true;
        }
    }
}

// If you can read a message, you own it, and it hasn''t any replies
// you can edit it.
if ( $CheckMessageEdit && $MessageRead )
{
    if ( $MessageOwner == true )
    {
        if ( eZForumMessage::countReplies( $checkMessage->id() ) == 0 )
        {
            $MessageEdit = true;
        }
    }
}

// If you can read a message and post to the forum, you can reply to it,
// except temporary messages.
if ( $CheckMessageReply  && $MessageRead && $ForumPost )
{
    if ( $checkMessage->isTemporary() == false )
    {
        $MessageReply = true;
    }
}

// If you own a message and can edit it, you can delete it.
if ( $CheckMessageDelete && $MessageEdit )
{
    if ( $MessageOwner == true )
    {
        $MessageDelete = true;
    }
}



// if ( true )
// {
//     include_once( "classes/eztexttool.php" );
//     echo "UserID = " . $UserID . "<br />\n";
//     echo "MessageOwner = " . eZTextTool::boolText( $MessageOwner ) . "<br />\n";
//     echo "ForumRead = " . eZTextTool::boolText( $ForumRead ) . "<br />\n";
//     echo "ForumPost = " . eZTextTool::boolText( $ForumPost ) . "<br />\n";
//     echo "MessageRead = " . eZTextTool::boolText( $MessageRead ) . "<br />\n";
//     echo "MessageEdit = " . eZTextTool::boolText( $MessageEdit ) . "<br />\n";
//     echo "MessageReply = " . eZTextTool::boolText( $MessageReply ) . "<br />\n";
//     echo "MessageDelete = " . eZTextTool::boolText( $MessageDelete ) . "<br />\n";
// }
?>
