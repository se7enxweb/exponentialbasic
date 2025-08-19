<?php
//
// $Id: subscriptionlogin.php 7764 2001-10-10 19:46:42Z fh $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
// include_once( "ezbulkmail/classes/ezbulkmailforgot.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/eztemplate.php" );

// check hash from mail, validate the correct email address...
$Language = $ini->variable( "eZBulkMailMain", "Language" ); 
$TemplateDir = $ini->variable( "eZBulkMailMain", "TemplateDir" ); 
$languageIni = new eZINI( "kernel/ezbulkmail/user/intl/" . $Language . "/subscriptionlogin.php.ini", false );

if( isset( $Hash ) )
{
    $change = new eZBulkMailForgot();

    if ( $change->check( $Hash ) )
    {
        $change->get( $change->check( $Hash ) );
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        $subscriptionaddress->setEMail( $change->mail() );
        $subscriptionaddress->setEncryptetPassword( $change->password() );
        $subscriptionaddress->store();

        // Cleanup
        $session->setVariable( "BulkMailAddress", $change->mail() );
        $change->delete();
        eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
        exit();
    }
}

if( isset( $Ok ) )
{
    if( $Action == "login" )
    {
        // check if password and email is correct.. if so, let the user continue..
        if( eZBulkMailSubscriptionAddress::validate( $Email, $Password ) )
        {
            $session->setVariable( "BulkMailAddress", $Email );
            eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
            exit();
        }
    }
    else if( $Action == "create" )
    {
        // TODO:check if address allready exists!!
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        if( $subscriptionaddress->setEMail( $Email ) && $Password != "" && $Password == $Password2 ) // check if passwords are alike and that we have a valid email address...
        {
            $headersInfo = getallheaders();
            // send an email to the new address asking to confirm it..
            $subjectText = ( $languageIni->variable( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
            $bodyText = $languageIni->variable( "strings", "body_text" );

            $forgot = new eZBulkMailForgot();
            $forgot->get( $Email );
            $forgot->setMail( $Email );
            $forgot->setPassword( $Password );
            $forgot->store();

            $mailTemplate = new eZTemplate( "kernel/ezbulkmail/user/" . $ini->variable( "eZBulkMailMain", "TemplateDir" ),
                                        "kernel/ezbulkmail/user/intl", $Language, "subscriptionmail.php" );
            $mailTemplate->setAllStrings();
            $mailTemplate->set_file( "subscription_mail_tpl", "subscriptionmail.tpl" );
 
            $mailpassword = new eZMail();
            $mailpassword->setTo( $Email );
            $mailpassword->setSubject( $subjectText );

            $mailTemplate->set_var( "activation_link",  "http://" . $headersInfo["Host"] . "/bulkmail/confirmsubscription/" . $forgot->Hash() );
            $mailTemplate->set_var( "host", "http://" . $headersInfo["Host"] );
            $mailpassword->setBody( $mailTemplate->parse( "dummy", "subscription_mail_tpl" ) );
            $mailpassword->setFrom( $GlobalSiteIni->variable( "eZBulkMailMain", "BulkmailSenderAddress" ) );
            $mailpassword->send();

            eZHTTPTool::header( "Location: /bulkmail/successfull/" );
            exit();
        }
        else // we have some sort of error... find out what it is, and present it to the user..
        {
            $New = "new";
            if( $subscriptionaddress->setEMail( $Email) == false )
                $error = "emailerror";
            else if( $Password == "" )
                $error = "zeropassword";
            else
                $error = "unlikepasswords";
        }

        // send confirmation mail to that address
    }
}

$t = new eZTemplate( "kernel/ezbulkmail/user/" . $ini->variable( "eZBulkMailMain", "TemplateDir" ),
                     "kernel/ezbulkmail/user/intl", $Language, "subscriptionlogin.php" );

$t->set_file( array(
    "subscription_login_tpl" => "subscriptionlogin.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteDesign );

$t->set_block( "subscription_login_tpl", "second_password_tpl", "second_password" );
$t->set_block( "subscription_login_tpl", "new_tpl", "new" );
$t->set_block( "subscription_login_tpl", "login_tpl", "login" );
$t->set_block( "subscription_login_tpl", "error_message_tpl", "error_message" );
$t->set_var( "error_message" );
$t->set_var( "new", "" );
$t->set_var( "login", "" );
$t->set_var( "second_password", "" );
$t->set_var( "action_value", "login" );

if( isset( $New ) )
{
    $t->parse( "second_password", "second_password_tpl" );
    $t->set_var( "action_value", "create" );
    $t->parse( "login", "login_tpl" );
}
else
{
    $t->parse( "new", "new_tpl" );
}

if( isset( $error ) ) // parse the errors
{
    if( $error == "emailerror" )
    {
        $t->set_var( "error_message", $languageIni->variable( "strings", "email_error" ) );
    }
    if( $error == "zeropassword")
    {
        $t->set_var( "error_message", $languageIni->variable( "strings", "zero_password_error" ) );
    }
    else
    {
        $t->set_var( $languageIni->variable( "strings", "unlike_passwords" ) );
    }
    $t->parse( "error_message", "error_message_tpl" );
}

$t->pparse( "output", "subscription_login_tpl" );

?>