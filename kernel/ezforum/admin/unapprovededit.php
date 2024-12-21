<?php
//
// $Id: unapprovededit.php 9887 2003-08-25 10:56:32Z vl $
//
// Created on: <21-Jan-2001 13:34:48 bf>
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
// include_once( "classes/ezhttptool.php" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezforum/classes/ezforum.php" );
// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezmail/classes/ezmail.php" );

$locale = new eZLocale( $Language );

require( "kernel/ezuser/admin/admincheck.php" );

$user =& eZUser::currentUser();

$message = new eZForumMessage();

for ( $i = 0; $i < count( $ActionValueArray ); $i++ )
{
    $message = new eZForumMessage( $MessageID[$i] );

    if ( $ActionValueArray[$i] == "Defer" )
    {
    }
    if ( $ActionValueArray[$i] == "Approve" )
    {
        $message->setIsApproved( 1 );
        $message->store();
        $msg = $message;
        $StartAction = "moderatorapprove";
        include( "kernel/ezforum/user/messagereply.php" );
    }
    if ( $ActionValueArray[$i] == "Discard" )
    {
        $message->delete();
    }
    if ( $ActionValueArray[$i] == "Reject" )
    {
        $mail = new eZMail();
        $mailTemplate = new eZTemplate( "kernel/ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
                                        "kernel/ezforum/admin/intl", $Language, "mailreject.php" );

        $languageIni = new INIFile( "kernel/ezforum/admin/intl/" . $Language . "/mailreject.php.ini", false );

        $mailTemplate->set_file( "mail_reject_tpl", "mailreject.tpl" );
        $mailTemplate->setAllStrings();

        $mailTemplate->set_var( "reason_for_reject", $RejectReason[$i] );
        $mailTemplate->set_var( "message_body", nl2br( $message->body() ) );
        $mailTemplate->set_var( "message_topic", $message->topic() );
        $mailTemplate->set_var( "message_postingtime", $locale->format( $message->postingTime() ) );

        $body =& $mailTemplate->parse( "dummy", "mail_reject_tpl" );

        $mail->setSubject( $languageIni->read_var( "strings", "mail_subject" ) . " " . $message->topic() );

        $messageUser =& $message->user();

        if ( is_a( $messageUser, "eZUser" ) )
        {
            $mail->setTo( $messageUser->email() );
            $forum = new eZForum( $message->forumID() );
            $forumUser =& $forum->moderator();

            $mail->setFrom( $user->email() );
            $mail->setBody( $body );
            $mail->send();
        }
        $message->delete();
    }
}

eZHTTPTool::header( "Location: /forum/unapprovedlist/" );
exit();

?>
