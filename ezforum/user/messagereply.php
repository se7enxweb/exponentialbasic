<?php
//
// $Id: messagereply.php 9887 2003-08-25 10:56:32Z vl $
//
// Created on: <24-Sep-2000 12:20:32 bf>
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

include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );

if ( $StartAction == "reply" )
{
    if ( !is_object( $msg ) )
    {
        $msg = new eZForumMessage( $MessageID );
    }

    $ForumID = $msg->forumId();

    if ( !is_object( $forum ) )
    {
        $forum = new eZForum( $ForumID );
    }

    // send mail to forum moderator
    $moderator = $forum->moderator();

    $mail = new eZMail();

    $messages = $forum->messageThreadTree( $msg->threadID() );

    $replyAddress = $ini->read_var( "eZForumMain", "ReplyAddress" );
    $mail->setFrom( $replyAddress );

    $locale = new eZLocale( $Language );

    $mailTemplate = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                                    "ezforum/user/intl", $Language, "mailreply.php" );

    $mailTemplate->set_file( "mailreply", "mailreply.tpl" );
    $mailTemplate->setAllStrings();
    $mailTemplate->set_block( "mailreply", "link_tpl", "link" );

    $emailNoticeArray = array();

    if ( is_object( $moderator ) )
    {
        $moderators = eZUserGroup::users( $moderator->id() );

        if ( count( $moderators ) > 0 )
        {
            foreach ( $moderators as $moderatorItem )
            {

                $author = $msg->user();
                $headersInfo = ( getallheaders() );

                if ( $author->id() == 0 )
                {
                    $mailTemplate->set_var( "author", $ini->read_var( "eZForumMain", "AnonymousPoster" ) );
                }
                else
                {
                    $mailTemplate->set_var( "author", $author->firstName() . " " . $author->lastName() );
                }
                $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );

                $subject_line = $mailTemplate->Ini->read_var( "strings", "moderator_subject" );

                if ( $forum->isModerated() )
                {
                    $mailTemplate->set_var( "link_1", "" );
                    $mailTemplate->set_var( "link", "" );
                }
                else
                {
                    $mailTemplate->set_var( "link_1", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                    $mailTemplate->parse( "link", "link_tpl" );
                }
                $mailTemplate->set_var( "topic", $msg->topic() );
                $mailTemplate->set_var( "body", $msg->body() );
                $mailTemplate->set_var( "forum_name", $forum->name() );
                $mailTemplate->set_var( "forum_link", "http://"  . $headersInfo["Host"] . "/forum/messagelist/" . $forum->id() );
                $mailTemplate->set_var( "link_2", "http://admin." . $headersInfo["Host"] . "/forum/messageedit/edit/" . $msg->id() );
                $mailTemplate->set_var( "intl-info_message_1", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_1" ) );
                $mailTemplate->set_var( "intl-info_message_2", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_2" ) );
                $mailTemplate->set_var( "intl-info_message_3", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_3" ) );
                $mailTemplate->set_var( "intl-info_message_4", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_4" ) );

                $bodyText = ( $mailTemplate->parse( "dummy", "mailreply" ) );

                $mail->setSubject( $subject_line );
                $mail->setBody( $bodyText );

                $mail->setFrom( $moderatorItem->email() );
                $mail->setTo( $moderatorItem->email() );

                $mail->send();
                $emailNoticeArray[] = $moderatorItem->id();
            }
        }
    }


    // We just want to send notifications if forums is not moderated or message is approved
    if ( $msg->isApproved() ) 
    {
        $mail = new eZMail();
        $replyAddress = $ini->read_var( "eZForumMain", "ReplyAddress" );
        $mail->setFrom( $replyAddress );
    
        foreach ( $messages as $message )
        {
            if ( $message->id() != $msg->id() )
            {
                if ( ( $message->treeID() > $msg->treeID() ) && $message->emailNotice() )
                {
                    $user =& eZUser::currentUser();
                    $headersInfo = ( getallheaders() );

                    // $user may be false (answering an anonymous forum, with user not logged in)
                    if ( $user )
                    {
                        $mailTemplate->set_var( "author", $user->firstName() . " " . $user->lastName() );
                    }
                    else
                    {
                        $mailTemplate->set_var( "author", "Anonymous" );
                    }

                    $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );

                    $subject_line = $mailTemplate->Ini->read_var( "strings", "subject_prepend" );
                    $subject_line = $subject_line . $message->topic();
                    $subject_line = $subject_line . $mailTemplate->Ini->read_var( "strings", "subject_append" );

                    $mailTemplate->set_var( "topic", $msg->topic() );
                    $mailTemplate->set_var( "body", $msg->body() );
                    $mailTemplate->set_var( "forum_name", $forum->name() );
                    $mailTemplate->set_var( "forum_link", "http://" . $headersInfo["Host"] . "/forum/message/" . $message->id() );
                    $mailTemplate->set_var( "link_1", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                    $mailTemplate->set_var( "link_2", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                    $mailTemplate->parse( "link", "link_tpl" );

                    $bodyText = $mailTemplate->parse( "dummy", "mailreply" );

                    $mail->setSubject( $subject_line );

                    $author =& $message->user();

                    $mail->setTo( $author->email() );
                    $mail->setBody( $bodyText );

                    // only send replies to a user once
                    if ( !in_array( $author->id(), $emailNoticeArray ) )
                    {
                        $mail->send();
                        $emailNoticeArray[] = $author->id();
                    }
                }
            }
        }
    }
}

if ( $StartAction == "moderatorapprove" )
{

    if ( !is_object( $msg ) )
    {
        $msg = new eZForumMessage( $MessageID );
    }

    $ForumID = $msg->forumId();

    if ( !is_object( $forum ) )
    {
        $forum = new eZForum( $ForumID );
    }

    $messages = $forum->messageThreadTree( $msg->threadID() );

    $locale = new eZLocale( $Language );

    $mailTemplate = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                                    "ezforum/user/intl", $Language, "mailreply.php" );

    $mailTemplate->set_file( "mailreply", "mailreply.tpl" );
    $mailTemplate->setAllStrings();
    $mailTemplate->set_block( "mailreply", "link_tpl", "link" );

    $emailNoticeArray = array();

    // We just want to send notifications if forums is not moderated or message is approved
    if ( $msg->isApproved() ) 
    {
        $mail = new eZMail();
        $replyAddress = $ini->read_var( "eZForumMain", "ReplyAddress" );
        $mail->setFrom( $replyAddress );
    
        foreach ( $messages as $message )
        {
            if ( $message->id() != $msg->id() )
            {
                if ( ( $message->treeID() > $msg->treeID() ) && $message->emailNotice() )
                {
                    $user =& eZUser::currentUser();
                    $headersInfo = ( getallheaders() );

                    // $user may be false (answering an anonymous forum, with user not logged in)
                    if ( $user )
                    {
                        $mailTemplate->set_var( "author", $user->firstName() . " " . $user->lastName() );
                    }
                    else
                    {
                        $mailTemplate->set_var( "author", "Anonymous" );
                    }

                    $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );

                    $subject_line = $mailTemplate->Ini->read_var( "strings", "subject_prepend" );
                    $subject_line = $subject_line . $message->topic();
                    $subject_line = $subject_line . $mailTemplate->Ini->read_var( "strings", "subject_append" );

                    $mailTemplate->set_var( "topic", $msg->topic() );
                    $mailTemplate->set_var( "body", $msg->body() );
                    $mailTemplate->set_var( "forum_name", $forum->name() );
                    $mailTemplate->set_var( "forum_link", "http://" . $headersInfo["Host"] . "/forum/message/" . $message->id() );
                    $mailTemplate->set_var( "link_1", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                    $mailTemplate->set_var( "link_2", "http://" . $headersInfo["Host"] . "/forum/message/" . $msg->id() );
                    $mailTemplate->parse( "link", "link_tpl" );

                    $bodyText = $mailTemplate->parse( "dummy", "mailreply" );

                    $mail->setSubject( $subject_line );

                    $author =& $message->user();

                    $mail->setTo( $author->email() );
                    $mail->setBody( $bodyText );

                    // only send replies to a user once
                    if ( !in_array( $author->id(), $emailNoticeArray ) )
                    {
                        $mail->send();
                        $emailNoticeArray[] = $author->id();
                    }
                }
            }
        }
    }
}


?>