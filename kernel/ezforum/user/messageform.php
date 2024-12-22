<?php
//
// $Id: messageform.php 9553 2002-05-22 11:24:54Z jhe $
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztexttool.php" );

$AllowHTML = $ini->read_var( "eZForumMain", "AllowHTML" );
$language = $ini->read_var( "eZForumMain", "Language" );
$author = eZUser::currentUser();

$locale = new eZLocale( $language );

if ( isset( $ShowMessageForm ) && $ShowMessageForm )
{
    if ( isset( $ShowVisibleMessageForm ) && $ShowVisibleMessageForm )
    {
        $t->set_file( "form", "messageform.tpl"  );
        $t->set_block( "form", "author_field_tpl", "author_field" );
        $t->set_block( "author_field_tpl", "author_logged_in_tpl", "author_logged_in" );
        $t->set_block( "author_field_tpl", "author_not_logged_in_tpl", "author_not_logged_in" );

        $t->set_block( "form", "message_body_info_tpl", "message_body_info_item" );
        $t->set_block( "form", "message_reply_info_tpl", "message_reply_info_item" );
        $t->set_block( "form", "message_notice_checkbox_tpl", "message_notice_checkbox" );
        $t->set_var( "message_body_info_item", "" );
        $t->set_var( "message_reply_info_item", "" );
        $t->set_var( "message_notice_checkbox", "" );

        $t->set_var( "headline", $t->Ini->read_var( "strings", $Action . "_headline" ) );
    }

    if ( isset( $ShowHiddenMessageForm ) && $ShowHiddenMessageForm )
    {
        $t->set_file( "hidden_form", "messagehiddenform.tpl" );
    }

    if (  isset( $BodyInfo ) && $BodyInfo )
    {
        $t->parse( "message_body_info_item", "message_body_info_tpl" );
    }

    if (  isset( $ShowVisibleMessageForm ) && $ShowVisibleMessageForm && is_a( eZUser::currentUser(), "eZUser" ) )
    {
        $t->parse( "message_notice_checkbox", "message_notice_checkbox_tpl" );
    }

    if ( isset( $ReplyInfo ) && $ReplyInfo )
    {
        $t->parse( "message_reply_info_item", "message_reply_info_tpl" );
    }

    if ( isset( $Error ) && $Error )
    {
        $MessageTopic = $NewMessageTopic;
        $MessageBody = $NewMessageBody;

        $t->set_block( "errors_tpl", "error_missing_body_item_tpl", "error_missing_body_item" );
        $t->set_block( "errors_tpl", "error_missing_topic_item_tpl", "error_missing_topic_item" );

        if ( empty( $NewMessageTopic ) )
        {
            $t->parse( "error_missing_topic_item", "error_missing_topic_item_tpl" );
        }
        else
        {
            $t->set_var( "error_missing_topic_item", "" );
        }

        if ( empty( $NewMessageBody ) )
        {
            $t->parse( "error_missing_body_item", "error_missing_body_item_tpl" );
        }
        else
        {
            $t->set_var( "error_missing_body_item", "" );
        }

        $t->parse( "errors_item", "errors_tpl" );
    }

    if ( isset( $ShowEmptyMessageForm ) && $ShowEmptyMessageForm === true )
    {
        if ( !is_object( $msg ) )
        {
            $msg = new eZForumMessage( $MessageID );
            $msg->setIsTemporary( true );
        }

        if ( isset( $NewMessageTopic ) )
        {
            $MessageTopic = $NewMessageTopic;
        }
        else
        {
            $MessageTopic = $msg->topic();
        }

        if ( isset( $NewMessageBody ) )
        {
            $MessageBody = $NewMessageBody;
        }
        else
        {
            $MessageBody = $msg->body();
        }

        $MessageNotice = $msg->emailNotice();
        $ForumID = $msg->forumId();

        if ( !$msg->isTemporary() && $Action != "reply" )
        {
            $MessagePostedAt = $Locale->format( $msg->postingTime() );
        }
        else
        {
            $MessagePostedAt = $NewMessagePostedAt;
        }

        if ( isset( $NewMessageNotice ) )
        {
            $MessageNotice = $NewMessageNotice;
        }
    }
    else
    {
        if ( isset( $NewMessageAuthor ) )
        {
            $MessageAuthor = $NewMessageAuthor;
        }
        else
        {
            if ( $msg->userName() != "" && $Action != "reply" )
            {
                $MessageAuthor = $msg->userName();
            }
            else if ( !is_object( $author ) )
            {
                $author = eZUser::currentUser();
            }
        }
        if ( $msg->isTemporary() )
        {
            $MessagePostedAt = $NewMessagePostedAt;
        }
        else
        {
            $MessagePostedAt = $locale->format( $msg->postingTime() );
        }
    }

    if ( is_object( $author ) && $author->id() > 0 )
    {
        $MessageAuthor = $author->firstName() . " " . $author->lastName();
    }
    else if ( $msg->userName() != "" && $Action != "reply" )
    {
        $MessageAuthor = $msg->userName();
    }
    else
    {
        $MessageAuthor = $ini->read_var( "eZForumMain", "AnonymousPoster" );
    }

    switch ( $MessageNotice )
    {
        case "on":
        case "y":
        case "checked":
        case 1:
        case true:
        {
            $MessageNoticeText = $t->Ini->read_var( "strings", "notice_yes" );
            $MessageNotice = "checked";
            $NewMessageNotice = "checked";
        }
        break;

        case "off":
        case "n":
        case "unchecked":
        case 0:
        case false:
        {
            $MessageNoticeText = $t->Ini->read_var( "strings", "notice_no" );
            $MessageNotice = "";
            $NewMessageNotice = "";
        }
        break;
    }

    $quote = "/". chr( 34 ) . "/";

    if( !is_null( $MessageTopic ) )
    $MessageTopic = preg_replace( $quote, "&#034;", $MessageTopic );

    if( !is_null( $MessageBody ) )
    $MessageBody = preg_replace( $quote, "&#034;", $MessageBody );

    // include_once( "classes/eztexttool.php" );

    $t->set_var( "message_topic", $MessageTopic );
    $t->set_var( "new_message_topic", $NewMessageTopic );
    $t->set_var( "message_body", $MessageBody );
    $t->set_var( "new_message_body", $NewMessageBody );
    $t->set_var( "message_posted_at", $MessagePostedAt );
    $t->set_var( "message_author", $MessageAuthor );
    $t->set_var( "message_id", $MessageID );
    $t->set_var( "message_notice_text", $MessageNoticeText );
    $t->set_var( "message_notice", $MessageNotice );
    $t->set_var( "new_message_notice", $NewMessageNotice );

    $t->set_var( "reply_to_id", $ReplyToID );
    $t->set_var( "preview_id", $PreviewID );
    $t->set_var( "original_id", $OriginalID );

    $t->set_var( "forum_id", $ForumID );

    $t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
    $t->set_var( "end_action", $EndAction );
    $t->set_var( "start_action", $StartAction );
    $t->set_var( "action_value", $ActionValue );

    $AllowedTags = $ini->read_var( "eZForumMain", "AllowedTags" );
    $t->set_var( "allowed_tags", htmlspecialchars( $AllowedTags ) );

    if ( $ShowVisibleMessageForm )
    {
        if ( is_object( $author ) && $author->id() > 0 )
        {
            $t->parse( "author_field", "author_logged_in_tpl" );
        }
        else
        {
            $t->parse( "author_field", "author_not_logged_in_tpl" );
        }
    }

    if ( $ShowHiddenMessageForm )
    {
        if ( $doPrint )
        {
            $t->pparse( "message_hidden_form_file", "hidden_form" );
        }
        else
        {
            $t->parse( "message_hidden_form_file", "hidden_form" );
        }
    }

    if ( $ShowVisibleMessageForm )
    {
        if ( $doPrint )
        {
            $t->pparse( "message_form_file", "form" );
        }
        else
        {
            $t->parse( "message_form_file", "form" );
        }
    }
}
else
{
    $t->parse( "message_form_file", "" );
    $t->parse( "message_hidden_form_file", "" );
}

?>