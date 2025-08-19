<?php
// 
// $Id: search.php 9499 2002-05-02 13:36:52Z bf $
//
// Created on: <12-Oct-2000 20:33:02 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezlist.php" );
// include_once( "classes/eztemplate.php" );

// include_once( "ezforum/classes/ezforum.php" );

// include_once( "ezuser/classes/ezuser.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZForumMain", "Language" );
$NewMessageLimit = $ini->variable( "eZForumMain", "NewMessageLimit" );
$Limit = $ini->variable( "eZForumMain", "SearchUserLimit" );

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->variable( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( "search_tpl", "search.tpl" );

$t->set_block( "search_tpl", "message_tpl", "message" );
$t->set_block( "search_tpl", "empty_result_tpl", "empty_result" );
$t->set_block( "search_tpl", "search_result_tpl", "search_result" );

$t->set_block( "message_tpl", "new_icon_tpl", "new_icon" );
$t->set_block( "message_tpl", "old_icon_tpl", "old_icon" );


if ( !isset( $Offset ) )
    $Offset = 0;

$t->set_var( "url_text", "" );
$t->set_var( "search_result", "" );

$db =& eZDB::globalDatabase();

if ( isset( $SearchText ) and !isset( $QueryString ) )
    $QueryString = $SearchText;

if ( $QueryString != "" )
{
    $tmpQueryString = str_replace( "<", "&lt;", $QueryString );
    $tmpQueryString = str_replace( ">", "&gt;", $tmpQueryString );

    $t->set_var( "url_text", $tmpQueryString );
    
    $forum = new eZForum();
    
    // do a search in all forums
    $messages = $forum->search( $QueryString, $Offset, $Limit, $total_count );

    $locale = new eZLocale( $Language );

    $level = 0;
    $i = 0;

    if ( count( $messages ) > 0 )
    {
        foreach ( $messages as $message )
        {
            if ( ( $i % 2 ) == 0 )
                $t->set_var( "td_class", "bglight" );
            else
                $t->set_var( "td_class", "bgdark" );
            
            $t->set_var( "message_topic", $message->topic() );
            $t->set_var( "postingtime", $locale->format( $message->postingTime() ) );
            $t->set_var( "message_id", $message->id() );
            
            $messageAge = round( $message->age() / ( 60 * 60 * 24 ) );
            if ( $messageAge <= $NewMessageLimit )
            {
                $t->parse( "new_icon", "new_icon_tpl" );
                $t->set_var( "old_icon", "" );
            }
            else
            {
                $t->parse( "old_icon", "old_icon_tpl" );
                $t->set_var( "new_icon", "" );
            }
            
            $author = $message->user();
            
            if ( $author->id() == 0 )
            {
                $t->set_var( "user", $ini->variable( "eZForumMain", "AnonymousPoster" ) );
            }
            else
            {
                $t->set_var( "user", $author->firstName() . " " . $author->lastName() );
            }
            
            $t->parse( "message", "message_tpl", true );
            $i++;
        }
    }
        
    if ( count( $messages ) == 0 )
    {
        $t->parse( "empty_result", "empty_result_tpl" );
    }
    else
    {
        $t->parse( "search_result", "search_result_tpl", true );
        $t->set_var( "empty_result", "" );
    }
}
else
{
    $t->parse( "empty_result", "empty_result_tpl" );
}

eZList::drawNavigator( $t, $total_count, $Limit, $Offset, "search_tpl" );

$t->set_var( "forum_start", $Offset + 1 );
$t->set_var( "forum_end", min( $Offset + $Limit, $total_count ) );
$t->set_var( "forum_total", $total_count );

$t->set_var( "url_query_string", urlencode( $QueryString ) );

$t->pparse("output","search_tpl");

?>