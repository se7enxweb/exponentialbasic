<?php
//
// $Id: messageedit.php 9550 2002-05-21 09:19:02Z jhe $
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

include_once( "classes/ezlocale.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZForumMain", "Language" );

if ( isSet( $EditButton ) )
{
    $Action = "edit";
}

if ( !empty( $CancelButton ) )
{
    $Action = "cancel";
}

if ( !empty( $PreviewButton ) )
{
    $Action = "preview";
}

if ( $Action == "preview" )
{
    $NewMessageTopic = trim( $NewMessageTopic );
    $NewMessageBody = $NewMessageBody;

    if ( empty( $NewMessageTopic ) || empty( $NewMessageBody ) )
    {
        $Error = true;
        $Action = $StartAction;
    }
}

// Select which main page we are going to view.

switch ( $Action )
{
    case "reply":
    case "new":
    case "edit":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messageedit.tpl"  );
        $t->set_block( "page", "errors_tpl", "errors_item" );
        $t->set_var( "errors_item", "" );
    }
    break;

    case "delete":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messagedelete.tpl"  );
    }
    break;

    case "preview":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messagepreview.tpl"  );
    }
    break;

    case "completed":
    {
        $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                             "ezforum/user/intl", $Language, "message.php" );

        $t->set_file( "page", "messageposted.tpl"  );
        $t->setAllStrings();
    }
    break;
}


// Any errors?
$Error = false;
$Errors = false;

$Locale = new eZLocale( $Language );

// Do some action!

switch ( $Action )
{
    case "dodelete":
    {
        $msg = new eZForumMessage( $MessageID );

        $CheckMessageID = $MessageID;
        $CheckForumID = $msg->forumID();

        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if ( $MessageDelete == false )
        {
            die('bath');// eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }

        $msg->delete();
        eZHTTPTool::header( "Location: /forum/messagelist/$CheckForumID" );

    }
    break;

    case "delete":
    {
        $ActionValue = "dodelete";
        $StartAction = "delete";
        $EndAction = "dodelete";

        $msg = new eZForumMessage( $MessageID );

        $CheckMessageID = $MessageID;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if ( !$MessageDelete )
        {
            eZHTTPTool::header( "Location: /forum/messageedit/forbidden/?Tried=$Action&TriedMessage=$CheckMessageID&TriedForum=$CheckForumID" );
        }

        $doParse = true;
        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessage = true;
        include_once( "ezforum/user/messagebody.php" );
    }
    break;

    case "completed":
    {
        $msg = new eZForumMessage( $MessageID );

        $CheckMessageID = $msg->id();
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if ( isset( $MessageEdit ) && $MessageEdit == false )
        {
            die('mondays'); //eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }

        // Just tell the geezers that their posting has been sent or queued for moderation.
        // Also inform of any e-mails sent.

        $doParse = true;
        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessage = true;
        include_once( "ezforum/user/messagebody.php" );
    }
    break;

    case "cancel":
    {
        // If PreviewID is set then we need to delete the object.
        // Since all objects are smart enough to not generate any
        // error messages if we new an empty object and then delete it
        // no ifs are neccessary.
        $msg = new eZForumMessage( $PreviewID );
        $msg->delete();

        include_once( "classes/ezhttptool.php" );
        if ( empty( $RedirectURL ) )
        {
            if ( empty( $ForumID ) )
            {
                eZHTTPTool::header( "Location: /forum/categorylist" );
            }
            else
            {
                eZHTTPTool::header( "Location: /forum/messagelist/$ForumID" );
            }
        }
        else
        {
            eZHTTPTool::header( "Location: $RedirectURL" );
        }
    }
    break;

    case "insert":
    {
        $ActionValue = "completed";
        $msg = new eZForumMessage( $MessageID );

        $CheckMessageID = $MessageID;
        $ForumID = $msg->forumID();
        $CheckForumID = $ForumID;

        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );

        if ( isset( $ForumPost ) && !$ForumPost )
        {
            eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }
        $msg->setIsTemporary( false );
        $msg->store();

        if ( $StartAction == "reply" )
        {
            include_once( "ezforum/user/messagereply.php" );
        }
        else
        {
            if ( !is_object( $forum ) )
            {
                $forum = new eZForum( $ForumID );
            }

            // send mail to forum moderator
            $moderator = $forum->moderator();

            if ( is_object( $moderator ) )
            {
                $moderators =& eZUserGroup::users( $moderator->id() );

                if ( count( $moderators ) > 0 )
                {
                    foreach ( $moderators as $moderatorItem )
                    {
                        include_once( "ezmail/classes/ezmail.php" );
                        $mail = new eZMail();

                        $locale = new eZLocale( $Language );

                        $mailTemplate = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                                                       "ezforum/user/intl", $Language, "mailreply.php" );

                        $mailTemplate->set_file( "mailreply", "mailreply.tpl" );
                        $mailTemplate->setAllStrings();
                        $mailTemplate->set_block( "mailreply", "link_tpl", "link" );
                        $headersInfo = getallheaders();

                        $author = $msg->user();

                        if ( $author->id() == 0 )
                        {
                            if ( $msg->userName() )
                                $mailTemplate->set_var( "author", $msg->userName() );
                            else
                                $mailTemplate->set_var( "author", $ini->read_var( "eZForumMain", "AnonymousPoster" ) );
                        }
                        else
                        {
                            $mailTemplate->set_var( "author", $author->firstName() . " " . $author->lastName() );
                        }
                        $mailTemplate->set_var( "posted_at", $locale->format( $msg->postingTime() ) );

                        $subject_line = $mailTemplate->Ini->read_var( "strings", "moderator_subject" );

                        $mailTemplate->set_var( "topic", $msg->topic() );
                        $mailTemplate->set_var( "body", $msg->body() );

                        $mailTemplate->set_var( "forum_name", $forum->name() );
                        $mailTemplate->set_var( "forum_link", "http://"  . $headersInfo["Host"] . "/forum/messagelist/" . $forum->id() );

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
                        $mailTemplate->set_var( "link_2", "http://" . $ini->read_var( "site", "AdminSiteURL" ) . "/forum/messageedit/edit/" . $msg->id() );
                        $mailTemplate->set_var( "intl-info_message_1", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_1" ) );
                        $mailTemplate->set_var( "intl-info_message_2", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_2" ) );
                        $mailTemplate->set_var( "intl-info_message_3", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_3" ) );
                        $mailTemplate->set_var( "intl-info_message_4", $mailTemplate->Ini->read_var( "strings", "moderator_info_message_4" ) );

                        $bodyText = $mailTemplate->parse( "dummy", "mailreply" );

                        $mail->setSubject( $subject_line );
                        $mail->setBody( $bodyText );

                        $mail->setFrom( $moderatorItem->email() );
                        $mail->setTo( $moderatorItem->email() );

                        $mail->send();
                    }
                }
            }

            if ( $forum->isModerated() )
            {
                $msg->setIsApproved ( false );
                $msg->store();
            }
        }

        if ( isset( $RedirectURL ) && $RedirectURL != "" )
        {
            eZHTTPTool::header( "Location: $RedirectURL" );
        }
        else
        {
            eZHTTPTool::header( "Location: /forum/messageedit/$ActionValue/$OriginalID?ReplyToID=$ReplyToID&ActionStart=$ActionStart" );
        }
        exit();
    }
    break;

    case "update":
    {
        $ActionValue = "completed";
        $msg = new eZForumMessage( $OriginalID );
        $tmpmsg = new eZForumMessage( $PreviewID );

        $CheckMessageID = $OriginalID;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        include_once( "classes/ezhttptool.php" );
        if ( isset( $MessageEdit ) && $MessageEdit == false )
        {
            die('pink');//eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }

        $msg->setTopic( $tmpmsg->topic() );
        $msg->setBody( $tmpmsg->body() );
        $msg->setEmailNotice( $tmpmsg->emailNotice() );

        $msg->store();
        if ( $RedirectURL != "" )
        {
            eZHTTPTool::header( "Location: $RedirectURL" );
        }
        else
        {
            eZHTTPTool::header( "Location: /forum/messageedit/$ActionValue/$OriginalID?ActionStart=$ActionStart" );
        }
        exit();
    }

    case "new":
    {
        $StartAction = "new";
        $EndAction = "insert";
        $ActionValue = "preview";
        $NewMessagePostedAt = htmlspecialchars( $ini->read_var( "eZForumMain", "FutureDate" ) );

        $ShowMessage = false;
        include_once( "ezforum/user/messagebody.php" );

        $msg = new eZForumMessage();
        $msg->setIsTemporary( true );
        $msg->setForumID( $ForumID );

        $CheckMessageID = 0;
        $CheckForumID = $msg->forumID();
        include( "ezforum/user/messagepermissions.php" );

        if ( !$ForumPost )
        {
            include_once( "classes/ezhttptool.php" );
            die('green');// eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }

        $doParse = true;
        $ShowPath = true;
        $isPreview = true;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessageForm = true;
        $ShowEmptyMessageForm = true;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        include_once( "ezforum/user/messageform.php" );
    }
    break;

    case "edit":
    {
        if ( !isSet( $StartAction ) )
        {
            $StartAction = "edit";
            $EndAction = "update";
        }

        unset( $NewMessageAuthor );
        unset( $NewMessagePostedAt );

        $NewMessagePostedAt = htmlspecialchars( $ini->read_var( "eZForumMain", "FutureDate" ) );

        $msg = new eZForumMessage( $MessageID );

        if ( $msg->id() >= 1 )
        {
            $ForumID = $msg->forumID();
        }

        $ActionValue = "preview";

        $CheckMessageID = $MessageID;
        $CheckForumID = $ForumID;


        include( "ezforum/user/messagepermissions.php" );

        if ( isset( $MessageEdit ) && !$MessageEdit && !$Error )
        {
            include_once( "classes/ezhttptool.php" );
            die('blueboy');//eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }


        $doParse = true;
        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessageForm = true;
        $ShowEmptyMessageForm = false;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        include_once( "ezforum/user/messageform.php" );

        $doPrint = true;
    }
    break;

    case "reply":
    {
        $StartAction = $Action;
        $ActionValue = "preview";
        $EndAction = "insert";

        $MessageID = $ReplyToID;
        $NewMessagePostedAt = htmlspecialchars( $ini->read_var( "eZForumMain", "FutureDate" ) );
        $ReplyTags = $ini->read_var( "eZForumMain", "ReplyTags" );
        $ReplyStartTag = $ini->read_var( "eZForumMain", "ReplyStartTag" );
        $ReplyEndTag = $ini->read_var( "eZForumMain", "ReplyEndTag" );

        $msg = new eZForumMessage( $MessageID );
        $forum = new eZForum( $msg->forumID() );
        $ForumID = $forum->id();
        $CheckMessageID = $MessageID;
        $CheckForumID = $ForumID;
        include( "ezforum/user/messagepermissions.php" );

        if ( !$MessageReply )
        {
            //#include_once( "classes/ezhttptool.php" );
            //#eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
        }

        if ( $ReplyTags == "enabled" )
        {
            $NewMessageBody = "$ReplyStartTag" . "\n" . $msg->body() . "\n" . "$ReplyEndTag";
            $MessageBody = $NewMessageBody;
        }
        else
        {
            include_once( "classes/eztexttool.php" );
            $NewMessageBody = eZTextTool::addPre( $msg->body() );
        }

        $user =& eZUser::currentUser();

        $NewMessageTopic = $msg->topic();

        $ReplyPrefix = $ini->read_var( "eZForumMain", "ReplyPrefix" );

        if ( !preg_match( "/^$ReplyPrefix/", $NewMessageTopic ) )
        {
            $NewMessageTopic = $ReplyPrefix . $NewMessageTopic;
            $MessageTopic = $NewMessageTopic;
        }

        $doParse = true;
        $ShowMessage = true;
        include_once( "ezforum/user/messagebody.php" );

        $ShowPath = true;
        $isPreview = false;
        include_once( "ezforum/user/messagepath.php" );

        $ShowMessageForm = true;
        $ShowEmptyMessageForm = false;
        $ShowVisibleMessageForm = true;
        $ShowHiddenMessageForm = true;
        $ShowReplyInfo = true;
        $ShowBodyInfo = true;
        $NewMessageAuthor = true;
        include_once( "ezforum/user/messageform.php" );

        $doPrint = true;
    }
    break;

    case "preview":
    {
        $ActionValue = $EndAction;
        if ( isset( $Error ) && $Error == false )
        {
            if ( empty( $PreviewID ) )
            {
                switch ( $StartAction )
                {
                    case "edit":
                    {
                        $msg = new eZForumMessage();
                        $tmpmsg = new eZForumMessage( $MessageID );
                        $msg = $tmpmsg->cloneObject();
                    }
                    break;

                    case "reply":
                    {
                        $msg = new eZForumMessage();
                        $tmpmsg = new eZForumMessage( $ReplyToID );
                        $ForumID = $tmpmsg->forumID();
                        $msg->setForumID( $ForumID );
                        $msg->setParent( $ReplyToID );

                        $forum = new eZForum( $ForumID );

                        if ( $forum->isModerated() )
                        {
                            $msg->setIsApproved( false );
                        }
                        else
                        {
                            $msg->setIsApproved( true );
                        }
                    }
                    break;

                    case "new":
                    {
                        $msg = new eZForumMessage();
                        $msg->setForumID( $ForumID );
                    }
                    break;

                    default:
                    {
                        $msg = new eZForumMessage( $OriginalID );
                    }
                    break;

                }

                if ( is_object( $user ) )
                {
                    $msg->setUserID( $user->id() );
                }
                else
                {
                    $msg->setUserID( 0 );
                    $msg->setUserName( $AuthorName );
                }
            }
            else
            {
                $msg = new eZForumMessage( $PreviewID );
            }

            if ( $NewMessageNotice == "on" )
            {
                $msg->enableEmailNotice();
            }
            else
            {
                $msg->disableEmailNotice();
            }

            $AllowedTags = $ini->read_var( "eZForumMain", "AllowedTags" );
            $AllowHTML = $ini->read_var( "eZForumMain", "AllowHTML" );

            if ( $AllowHTML == "enabled" )
            {
                $msg->setTopic( $NewMessageTopic );
                $msg->setBody( $NewMessageBody );
            }
            else
            {
                $msg->setTopic( strip_tags( $NewMessageTopic ) );
                $msg->setBody( strip_tags( $NewMessageBody, $AllowedTags ) );
            }

            $msg->setIsTemporary( true );

            $msg->store();
            $PreviewID = $msg->id();

            if ( $EndAction == "insert" )
            {
                $OriginalID = $PreviewID;
            }
            else
            {
                $OriginalID = $MessageID;
            }

            $MessageID = $PreviewID;

            $CheckMessageID = $msg->id();
            $CheckForumID = $msg->forumID();
            include( "ezforum/user/messagepermissions.php" );

            if ( isset( $MessageEdit ) && $MessageEdit == false )
            {
                include_once( "classes/ezhttptool.php" );
                die('purplemonster');//eZHTTPTool::header( "Location: /error/403?Info=" . errorPage( "forum_main", "/forum/categorylist/", 403 ) );
            }

            $ShowPath = true;
            $isPreview = false;
            include_once( "ezforum/user/messagepath.php" );

            $ShowMessage = true;
            include_once( "ezforum/user/messagebody.php" );

            $ShowMessageForm = true;
            $ShowEmptyMessageForm = false;
            $ShowVisibleMessageForm = false;
            $ShowHiddenMessageForm = true;
            $ShowReplyInfo = true;
            $ShowBodyInfo = true;
            include_once( "ezforum/user/messageform.php" );
        }

        $doPrint = true;
    }
    break;

    default:
    {
        include_once( "classes/ezhttptool.php" );
        die('aqua'); //eZHTTPTool::header( "Location: /error/404?Info=" . errorPage( "forum_main", "/forum/categorylist/", 404 ) );
    }
    break;
}

// print( "ActionValue = $ActionValue <br>" );
// print( "NewMessageBody = $NewMessageBody <br>" );
// print( "MessageBody = $MessageBody <br>" );
// print( "PreviewID = $PreviewID <br>" );
// print( "ReplyToID = $ReplyToID <br>" );
// print( "OriginalID = $OriginalID <br>" );
// print( "MessageID = $MessageID <br>" );
// print( "RedirectURL = $RedirectURL <br>" );
// print( "ForumID = $ForumID <br>" );


$t->set_var( "start_action", $StartAction );
$t->set_var( "end_action", $EndAction );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "message_id", $MessageID );

$t->setAllStrings();

if ( $doPrint == true )
{
    $t->pparse( "output", "page" );
}
else
{
    $t->pparse( "forum", "page" );
}

?>