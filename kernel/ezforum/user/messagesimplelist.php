<?php
// 
// $Id: messagesimplelist.php 9858 2003-06-17 14:17:04Z vl $
//
// Created on: <11-Sep-2000 22:10:06 bf>
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
// include_once( "classes/eztexttool.php" );


// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezlist.php" );
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezforum/classes/ezforum.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );
$SimpleUserList = $ini->read_var( "eZForumMain", "SimpleUserList" );
$anonymous = $ini->read_var( "eZForumMain", "AnonymousPoster" );

if ( $ProductID )
	{ $ListTemplate = "reviewsimplelist.php"; }
else
	{ $ListTemplate = "messagesimplelist.php"; }

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "messagesimplelist.php" );

$t->set_file( "messagelist", "messagesimplelist.tpl"  );

$t->set_block( "messagelist", "message_list_tpl", "message_list" );
$t->set_block( "message_list_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "private_message_tpl", "private_message" );

$t->setAllStrings();

$forum = new eZForum( $ForumID );

$locale = new eZLocale( $Language );

if ( !isset($Offset) )
    $Offset = 0;

$messageList =& $forum->messageTree( $Offset, $SimpleUserList );
$messageCount =& $forum->messageCount();
$t->set_var( "total_posts", $messageCount );

if ( !$messageList )
{
    $errorIni = new INIFile( "kernel/ezforum/user/intl/" . $Language . "/messagesimplelist.php.ini", false );
    $noitem =& $errorIni->read_var( "strings", "noitem" );

    $t->set_var( "message_list", $noitem );
}
else
{
    $level = 0;
    $i = 0;
    foreach ( $messageList as $message )
    {
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $level = $message->depth();
        
        if ( $level > 0 )
            $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
        else
            $t->set_var( "spacer", "" );        
            
        $t->set_var( "topic", htmlspecialchars( $message->topic() ) );
        $t->set_var( "body", eZTextTool::nl2br( $message->body() ) );
        $time =& $message->postingTime();
        $t->set_var( "postingtime", $locale->format( $time ) );
        $t->set_var( "message_id", $message->id() );
        
        $muser =& $message->user();
/*
		echo "<pre>";
		print_r ($muser);
		echo "</pre>";
*/		
        if ( $muser->id() == 0 )
            $t->set_var( "user", $message->userName() );
        else
            $t->set_var( "user", $muser->firstName() . " " . $muser->lastName() );
      
		if ( $muser->id() == 0 )
		{
    		$MessageAuthor = $anonymous;
		}
		else
		{
    		$MessageAuthor = $muser->firstName() . " " . $muser->lastName();
		}

		if ( $muser->firstName()== "" && $muser->lastName()=="" )
			$MessageAuthor = $anonymous;
				
        $t->set_var( "user", $MessageAuthor );
		

$user =& eZUser::currentUser();
$t->set_var( "private_message", "" );


if ( ( $MessageAuthor != $anonymous) and ($user) )
{
//	$user = new eZUser( $muser->id() );
		
//	$toUser = eZUser::getUser( $user );
	$t->set_var( "username", $muser->login() );
	$t->set_var( "PM_topic", urlencode (": ".$message->topic() ) );
//	$t->set_var( "topic", $message->topic() );
	$t->parse( "private_message", "private_message_tpl" );
}
 
//        $t->set_var( "username", $muser->id() );
//		$t->set_var( "PM_topic", urlencode (": ".$message->topic() ) );  
        $t->parse( "message_item", "message_item_tpl", true );
        $i++;
    }
    $t->parse( "message_list", "message_list_tpl", true );
}
eZList::drawNavigator( $t, $messageCount, $SimpleUserList, $Offset, "messagelist" );

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );

$t->set_var( "newmessage", $newmessage );

$url = explode( "parent", $_SERVER['REQUEST_URI'] );
$t->set_var( "url", $url[0] );
$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->pparse( "output", "messagelist" );

?>