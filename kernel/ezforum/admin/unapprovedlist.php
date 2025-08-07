<?php
//
// $Id: unapprovedlist.php 7759 2001-10-10 13:18:29Z jhe $
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
$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZForumMain", "Language" );
$UnapprovdLimit = $ini->variable( "eZForumMain", "UnApprovdLimit" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezlist.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforum.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( "kernel/ezforum/admin/" . $ini->variable( "eZForumMain", "AdminTemplateDir" ),
                     "kernel/ezforum/admin/" . "/intl", $Language, "unapprovedlist.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "unapprovedlist.tpl" ) );

$t->set_block( "message_page", "message_item_tpl", "message_item" );


$locale = new eZLocale( $Language );

$message = new eZForumMessage();

$messages = $message->getAllUnApproved( $Offset, $UnapprovdLimit );
$messageCount = $message->unApprovedCount();

$languageIni = new eZINI( "kernel/ezforum/admin/" . "intl/" . $Language . "/unapprovedlist.php.ini", false );
$true =  $languageIni->variable( "strings", "true" );
$false =  $languageIni->variable( "strings", "false" );

if ( !$messages )
{
    $noitem = $languageIni->variable( "strings", "noitem" );
    $t->set_var( "message_item", $noitem );
}
else
{
    $i = 0;
    foreach ( $messages as $msg )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $forum = new eZForum( $msg->forumID() );
        $t->set_var( "forum_name", $forum->name() );
        $t->set_var( "forum_id", $forum->id() );

        $categoryList =& $forum->categories();
        $category =& $categoryList[0];

        $t->set_var( "category_name", $category->name() );
        $t->set_var( "category_id", $category->id() );
        $t->set_var( "message_topic", $msg->topic() );
        $t->set_var( "message_body", $msg->body() );
        $t->set_var( "reject_message", $languageIni->variable( "strings", "reject_message" ) );
        $t->set_var( "message_postingtime", $locale->format( $msg->postingTime() ) );
        $t->set_var( "message_id", $msg->id() );

        $author = $msg->user();
    
        $t->set_var( "message_user", $author->firstName() . " " . $author->lastName() );
        $t->set_var( "i", $i );
        
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $messageCount, $UnapprovdLimit, $Offset, "message_page" );

$t->pparse( "output", "message_page" );

?>