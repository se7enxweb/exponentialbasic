<?php
//
// $Id: latestmessages.php 9266 2002-02-26 08:39:17Z bf $
//
// Created on: <02-Jul-2001 11:45:17 bf>
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
// include_once( "classes/eztexttool.php" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezlist.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezforum/classes/ezforum.php" );

$Language = $ini->variable( "eZForumMain", "Language" );
$Limit = $ini->variable( "eZForumMain", "ShowMessageLimit" );
$t = new eZTemplate( "kernel/ezforum/user/" . $ini->variable( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "latestmessages.php" );

$t->set_file( "latest_messages_tpl", "latestmessages.tpl"  );
$t->set_block( "latest_messages_tpl", "message_tpl", "message" );
$t->setAllStrings();

$user =& eZUser::currentUser();
$db =& eZDB::globalDatabase();
$messages =& eZForumMessage::lastMessages( $Limit );

global $GlobalSiteDesign;

$t->set_var( "message", "" );
$i = 0;
$time = new eZDateTime();
$locale = new eZLocale( $Language );
foreach ( $messages as $message )
{
    $t->set_var( "sitedesign", $GlobalSiteDesign );

    $nr = ( $i % 2 ) + 1;
    $t->set_var( "alt_nr", $nr );

    $t->set_var( "forum_id", $message[$db->fieldName( "ForumID" )] );

    $t->set_var( "message_id", $message[$db->fieldName( "ID" )] );
    $t->set_var( "message_topic", htmlspecialchars( $message[$db->fieldName( "Topic" )] ) );

    $messageUser = new eZUser( $message[$db->fieldName( "UserID" )] );
    $t->set_var( "message_user", $messageUser->firstName() . " " . $messageUser->lastName() );

    $time->setTimeStamp( $message[$db->fieldName( "PostingTime" )] );
    $t->set_var( "message_postingtime", $locale->format( $time  ) );

    $t->set_var( "message_topic", htmlspecialchars( $message[$db->fieldName( "Topic" )] ) );

    $t->parse( "message", "message_tpl", true );
    $i++;
}
$t->pparse( "output", "latest_messages_tpl" );

?>