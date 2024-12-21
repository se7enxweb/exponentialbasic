<?php
//
// $Id: singlelist.php 9724 2002-09-03 09:10:28Z fh $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
// include_once( "ezbulkmail/classes/ezbulkmailforgot.php" );
// include_once( "ezuser/classes/ezuser.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZBulkMailMain", "Language" );
$languageIni = new INIFIle( "ezbulkmail/user/intl/" . $Language . "/subscriptionlogin.php.ini", false );
$category = eZBulkMailCategory::singleList();

if( isset( $Hash ) && is_object( $category ) )
{
    $change = new eZBulkMailForgot();
    if( $change->check( $Hash ) != false )
    {
        $change->get( $change->check( $Hash ) );
        if( eZBulkMailSubscriptionAddress::addressExists( $change->mail() ) && isset( $UnSubscribe ) )
        {
            $subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $change->mail() );
            if( $subscriptionaddress != false )
            {
                $subscriptionaddress->delete();
                $change->delete();
                $unsubscribed="";
                include( "ezbulkmail/user/usermessages.php" );
            }
        }
        else if( isset( $Subscribe ) )
        {
            $subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $change->mail() );
            $subscriptionaddress->store();
            $subscriptionaddress->unsubscribe( true );
            $subscriptionaddress->subscribe( $category );

            // Cleanup
            $change->delete();
 
            $subscribed="";
            include( "ezbulkmail/user/usermessages.php" );

        }
    }
    else
    {
        $hasherror = "";
        include( "ezbulkmail/user/usermessages.php" );
    }
}
else if( isset( $Hash ) && !is_object( $category ) )
{
    echo "Your administrator has specified an email list that is not available.";
}

if( isset( $SubscribeButton ) )
{
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        if( $subscriptionaddress->setEMail( $Email ) && !$subscriptionaddress->addressExists( $Email ) )
        {
            $headersInfo = getallheaders();
            $subjectText = ( $languageIni->read_var( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
            $bodyText = $languageIni->read_var( "strings", "body_text" );

            $forgot = eZBulkMailForgot::getByEmail( $Email );
            $forgot->store();

            $mailconfirmation = new eZMail();
            $mailconfirmation->setTo( $Email );
            $mailconfirmation->setSubject( $subjectText );
            $mailconfirmation->setFrom( $ini->read_var( "eZBulkMailMain", "BulkmailSenderAddress" ) );
            $mailconfirmation->setFromName( $ini->read_var( "eZBulkMailMain", "BulkMailSenderName" ) );
            $body = ( $bodyText . "\n");
            $body .= ( "http://" . $headersInfo["Host"] . "/bulkmail/singlelistsubscribe/" . $forgot->Hash() );

            $mailconfirmation->setBodyText( $body );
            $mailconfirmation->send();

            eZHTTPTool::header( "Location: /bulkmail/successfull/" );
            exit();
        }
        else
        {
            // Not a valid email address
            $subscriptionerror = "";
            include( "ezbulkmail/user/usermessages.php" );
        }
}

if( isset( $UnSubscribeButton ) )
{
    $subscriptionaddress = new eZBulkMailSubscriptionAddress();
    if( $subscriptionaddress->addressExists( $Email ) )
    {
        $headersInfo = getallheaders();
        $subjectText = ( $languageIni->read_var( "strings", "subject_text_unsubscribe" ) . " " . $headersInfo["Host"] );
        $bodyText = $languageIni->read_var( "strings", "body_text_unsubscribe" );

        $forgot = new eZBulkMailForgot();
        $forgot->get( $Email );
        $forgot->setMail( $Email );
        $forgot->store();

        $mailconfirmation = new eZMail();
        $mailconfirmation->setTo( $Email );
        $mailconfirmation->setSubject( $subjectText );
        $mailconfirmation->setFrom( $ini->read_var( "eZBulkMailMain", "BulkmailSenderAddress" ) );
        $mailconfirmation->setFromName( $ini->read_var( "eZBulkMailMain", "BulkMailSenderName" ) );

        $body = ( $bodyText . "\n");
        $body .= ( "http://" . $headersInfo["Host"] . "/bulkmail/singlelistunsubscribe/" . $forgot->Hash() );

        $mailconfirmation->setBodyText( $body );
        $mailconfirmation->send();

        $unsubscribemail = "";
        include( "ezbulkmail/user/usermessages.php" );
    }
    else
    {
        $subscriptionerror = "";
        include( "ezbulkmail/user/usermessages.php" );

    }
}

?>
