<?php
//
// $Id: forgot.php 9821 2003-05-19 07:30:55Z br $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

//require( "ezuser/user/usercheck.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezforgot.php" );
include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$headersInfo = getallheaders();

$languageIni = new INIFIle( "ezuser/user/intl/" . $Language . "/forgot.php.ini", false );

// Get the user.
if ( isset( $Login ) && $Login )
{
    $getUser = new eZUser();
    $user = $getUser->getUser( $Login );
}

if ( isSet( $ChangeButton ) && ( $user == false ) )
{
    eZHTTPTool::header( "Location: /user/unsuccessfull/" );
    exit();
}

// Store the user with a unique hash and mail the hash variable to the user.
if ( $user )
{
    if ( eZMail::validate( $user->email() ) == false )
    {
        eZHTTPTool::header( "Location: /user/missingemail/" );
        exit();
    }

    $subjectText = ( $languageIni->read_var( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
    $bodyText = $languageIni->read_var( "strings", "body_text" );

    $bodyFooter = $languageIni->read_var( "strings", "body_footer" );                      //SF
    $reminderMailFromAddress = $ini->read_var( "eZUserMain", "ReminderMailFromAddress" );  //SF

    $forgot = new eZForgot();
    $forgot->setUserID( $user->id() );
    $userID = $user->id();
    $forgot->store();

    $mailpassword = new eZMail();
    $mailpassword->setTo( $user->email() );
    $mailpassword->setSubject( $subjectText );
    $mailpassword->setFrom( $reminderMailFromAddress  );                                               //SF

    $body = ( $bodyText . "\n");
    $body .= ( "http://" . $headersInfo["Host"] . $ini->WWWDir . $ini->Index . "/user/forgot/change/" . htmlspecialchars( $forgot->Hash() ) );
    $body .= ( $bodyFooter );                                                                      //SF

    $mailpassword->setBody( $body );
    $mailpassword->send();


    eZHTTPTool::header( "Location: /user/successfull/" );
    exit();
}


if ( $Action == "change" )
{
    $change = new eZForgot();

    if ( $change->check( $Hash ) )
    {
        $change->get( $change->check( $Hash ) );
        $subjectNewPassword = $languageIni->read_var( "strings", "subject_text_password" );

        $reminderMailFromAddress = $ini->read_var( "eZUserMain", "ReminderMailFromAddress" );  //SF
        $bodyFooter = $languageIni->read_var( "strings", "body_footer" );                      //SF

        $bodyNewPassword = $languageIni->read_var( "strings", "body_text_password" );
        $passwordText = $languageIni->read_var( "strings", "password" );
        $userID = $change->userID();
        $user = new eZUser( $userID );
        $password = substr( md5( microtime() ), 0, 7 );
        $user->setPassword( $password );
        $user->store();
        $mail = new eZMail();
        $mail->setTo( $user->email() );
        $mail->setSubject( $subjectNewPassword . " " . $headersInfo["Host"] );

        $mail->setFrom( $reminderMailFromAddress );                                            //SF

        $body = ( $bodyNewPassword . "\nhttp://" . $headersInfo["Host"] . $ini->WWWDir . $ini->Index . "/user/login/.\n" ); //SF
        $body .= ( $passwordText . ": "  .  $password );

        $body .= ( $bodyFooter );                                                              //SF

        $mail->setBody( $body );
        $mail->send();

        // Cleanup
        $change->get( $change->check( $Hash ) );
        $change->delete();
    }
    eZHTTPTool::header( "Location: /user/generated/" );
    exit();
}

// Template
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ), "ezuser/user/intl", $Language, "forgot.php" );
$t->setAllStrings();

$t->set_file( array( "login" => "forgot.tpl" ) );

$t->pparse( "output", "login" );

?>