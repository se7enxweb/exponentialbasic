<?php
// 
// $Id: messagesimplelist.php,v 1.15.2.2 2002/05/08 11:51:36 vl Exp $
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
$ReviewLimit = $ini->read_var( "eZTradeMain", "ReviewLimit" );

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "messagereviewlist.php" );


$t->set_file( "messagelist", "messagereviewlist.tpl"  );

$t->set_block( "messagelist", "message_list_tpl", "message_list" );
$t->set_block( "messagelist", "view_list_tpl", "view_list" );
$t->set_block( "message_list_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "private_message_tpl", "private_message" );

$t->setAllStrings();

$forum = new eZForum( $ForumID );

$locale = new eZLocale( $Language );

if ( !isset($Offset) || !$Offset )
    $Offset = 0;

$messageList =& $forum->messageTree( $Offset, $SimpleUserList );
$messageCount =& $forum->messageCount();
$t->set_var( "total_threads", $forum->messageCount( false, false ) );
//$t->set_var( "total_messages", $forum->messageCount( false, true ) );
if ( $forum->messageCount( false, false ) > 0 )
	$t->parse( "view_list", "view_list_tpl" );
else
	$t->set_var( "view_list", "" );
	
$t->set_var( "message_list", "" );
	
if ( !$messageList )
{
    $errorIni = new INIFile( "ezforum/user/intl/" . $Language . "/" . "messagereviewlist.php" . ".ini" , false );
    $noitem = $errorIni->read_var( "strings", "noitem" );
	$noitem="<p>".$noitem."</p>";
    $t->set_var( "message_list", $noitem );
}
else
{
    $level = 0;
    $i = 0;
    foreach ( $messageList as $message )
    {
        $level = $message->depth();
		
		if ($level == 0)
		{
			if ( $i <= $ReviewLimit )
				{
				if ( ( $i % 2 ) == 0 )
    	        	$t->set_var( "td_class", "bglight" );
        		else
        		    $t->set_var( "td_class", "bgdark" );
                
//        	if ( $level > 0 )
//            	$t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
//        	else
            	$t->set_var( "spacer", "" );        
           	 
				$t->set_var( "topic", htmlspecialchars( $message->topic() ) );
//        	$t->set_var( "body", eZTextTool::nl2br( $message->body() ) );
//			$repArray = array("<br>","<br/>","</br>");
      			$messageBody = str_replace( array("<br>","<br/>","</br>"), "&nbsp;", $message->body() );
				$messageBody = strip_tags($messageBody);
				$t->set_var( "body", $messageBody );
//			$t->set_var( "body", strip_tags( $message->body() ) );
        		$time =& $message->postingTime();
        		$t->set_var( "postingtime", $locale->format( $time ) );
        		$t->set_var( "message_id", $message->id() );
            	$muser =& $message->user();
				if ( $muser->id() == 0 )
					$MessageAuthor = $anonymous;
				else
					$MessageAuthor = $muser->firstName() . " " . $muser->lastName();
				if ( $muser->firstName()== "" && $muser->lastName()=="" )
					$MessageAuthor = $anonymous;
				$t->set_var( "user", $MessageAuthor );
				$user =& eZUser::currentUser();
				$t->set_var( "private_message", "" );
				if ( ( $MessageAuthor != $anonymous) and ($user) )
				{
					$t->set_var( "username", $muser->login() );
					$t->set_var( "PM_topic", urlencode (": ".$message->topic() ) );
					$t->parse( "private_message", "private_message_tpl" );
				}
				$t->parse( "message_item", "message_item_tpl", true );
			}
        	$i++;
		}
    }
    $t->parse( "message_list", "message_list_tpl", true );
}


eZList::drawNavigator( $t, $messageCount, $SimpleUserList, $Offset, "messagelist" );

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );

if ( !isset( $newmessage ) )
{
	$newmessage = "";
}

$t->set_var( "newmessage", $newmessage );

$url = explode( "parent", $_SERVER['REQUEST_URI'] );
$t->set_var( "url", $url[0] );
$t->set_var( "forum_id", $forum->id() );
$t->set_var( "forum_name", $forum->name() );

$t->pparse( "output", "messagelist" );

?>