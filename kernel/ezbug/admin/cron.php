<?php
// 
// $Id: cron.php 9606 2002-06-04 11:43:47Z jhe $
//
// Created on: <08-Aug-2001 14:28:11 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

// include_once( "ezmail/classes/ezmailaccount.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezbug/classes/ezbug.php" );
// include_once( "ezbug/classes/ezbuglog.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

$ini = new eZINI( "site.ini", false );

$Language = $ini->variable( "eZBugMain", "Language" );
$MailUser = $ini->variable( "eZBugMain", "MailAccount" );
$MailPassword = $ini->variable( "eZBugMain", "MailPassword" );
$MailServer = $ini->variable( "eZBugMain", "MailServer" );
$MailServerPort = $ini->variable( "eZBugMain", "MailServerPort" );
$MailReplyTo = $ini->variable( "eZBugMain", "MailReplyToAddress" );

$mail_array = eZMailAccount::getNewMail( $MailUser, $MailPassword, $MailServer, $MailServerPort );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->variable( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "confirmationmail.php" );

$t->setAllStrings();

$t->set_file( "confirmation_mail_tpl", "confirmationmail.tpl" );

$t->set_block( "confirmation_mail_tpl", "subject_tpl", "subject" );
$t->set_block( "confirmation_mail_tpl", "mail_body_tpl", "mail_body" );
$t->set_block( "confirmation_mail_tpl", "reply_body_tpl", "reply_body" );

foreach ( $mail_array as $mail )
{
    $makebug = true;
    if ( eZBug::bugExists( $list[1] ) )
    {
        $bug = new eZBug( $list[1] );
        $log = new eZBugLog();
        $body = "";
        foreach ( split( "\n", $mail->body() ) as $line )
        {
            if ( !preg_match( "/^[ ]*>/", $line ) )
            {
                $body .= $line . "\n";
            }
        }
        $log->setDescription( $body );
        $log->setBug( $bug );
        $log->store();
        $makebug = false;
        $t->set_var( "prefix", $ini->variable( "eZMailMain", "ReplyPrefix" ) );
        $mailSubject = $mail->subject();
        $mailBody = $t->parse( "dummy", "reply_body_tpl" );
    }
    
    if ( $makebug )
    {
        $bug = new eZBug();
        $bug->setUserEmail( $mail->replyTo() );
        $bug->setName( $mail->subject() );
        $bug->setDescription( $mail->body() );
        $bug->setIsHandled( false );
        $bug->store();
        $t->set_var( "prefix", "" );
        $t->set_var( "bug_id", $bug->ID() );
        $mailSubject = $t->parse( "dummy", "subject_tpl" );
        $mailBody = $t->parse( "dummy", "mail_body_tpl" );
    }
    
    $confmail = new eZMail();
    $confmail->setTo( $mail->replyTo() );
    $confmail->setFrom( $MailReplyTo );
    $confmail->setReplyTo( $MailReplyTo );
    $confmail->setSubject( $mailSubject );
    $confmail->setBody( $mailBody );
    $confmail->send();

    $mail->delete();
}

?>