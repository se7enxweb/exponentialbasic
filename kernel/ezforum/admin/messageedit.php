<?php
//
// $Id: messageedit.php 7788 2001-10-11 12:00:35Z jhe $
//
// Created on: Created on: <18-Jul-2000 08:56:19 lw>
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
$error = new INIFIle( "kernel/ezforum/admin/intl/" . $Language . "/messageedit.php.ini", false );

// include_once( "ezforum/classes/ezforummessage.php" );
// include_once( "ezforum/classes/ezforum.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );

require( "kernel/ezuser/admin/admincheck.php" );


if ( isset( $DeleteMessages ) )
{
    $Action = "DeleteMessages";
}

if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "MessageModify" ) )
    {
        if ( $Topic != "" &&
        $Body != "" )
        {
            $msg = new eZForumMessage();
            $msg->get( $MessageID );
            $msg->setTopic( $Topic );
            $msg->setBody( $Body );

            if ( $notice )
                $msg->enableEmailNotice();
            else 
                $msg->disableEmailNotice();

            $ForumID = $msg->forumID();

            $forum = new eZForum( $ForumID );

            $msg->store();

            eZHTTPTool::header( "Location: /forum/messagelist/$ForumID/" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
   }
    
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "MessageDelete" ) )
    {
        if ( $MessageID != "" )
        {
            $msg = new eZForumMessage();
            $msg->get( $MessageID );
            $msg->delete();
            
            $ForumID = $msg->forumID();
            $forum = new eZForum( $ForumID );
            
            eZHTTPTool::header( "Location: /forum/messagelist/$ForumID" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
}

if ( $Action == "DeleteMessages" )
{
    if ( count( $MessageArrayID ) != 0 )
    {
        foreach ( $MessageArrayID as $MessageID )
        {
            $message = new eZForumMessage( $MessageID );
            $forumID = $message->forumID();
            $message->delete();

        }
        
        if ( empty( $RefererURL ) )
        {
            eZHTTPTool::header( "Location: /forum/messagelist/$forumID" );
            exit();
        }
        else
        {
            eZHTTPTool::header( "Location: /forum/search/$RefererURL" );
            exit();
        }
    }
}

$t = new eZTemplate( "kernel/ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
"kernel/ezforum/admin/" . "/intl", $Language, "messageedit.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "messageedit.tpl" ) );

$languageIni = new INIFile( "kernel/ezforum/admin/" . "intl/" . $Language . "/messageedit.php.ini", false );
$headline =  $languageIni->read_var( "strings", "head_line_insert" );

$t->set_block( "message_page", "message_edit_tpl", "message_edit" );
$locale = new eZLocale( $Language );

$t->set_var( "message_topic", "" );
$t->set_var( "message_postingtime", "" );
$t->set_var( "message_body", "" );
$t->set_var( "message_user", "" );
$t->set_var( "message_id", $MessageID );
$action_value = "update";

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZForum", "MessageModifyAdd" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }

    $action_value = "insert";
}


if ( $Action == "edit" )
{
    $languageIni = new INIFile( "kernel/ezforum/admin/" . "intl/" . $Language . "/messageedit.php.ini", false );
    $headline =  $languageIni->read_var( "strings", "head_line_edit" );

    if ( !eZPermission::checkPermission( $user, "eZForum", "MessageModify" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
    else
    {
        $msg = new eZForumMessage();
        $msg->get( $MessageID );
        $t->set_var( "message_topic", $msg->topic() );
        $t->set_var( "message_postingtime", $locale->format( $msg->postingTime() ) );
        $t->set_var( "message_body", $msg->body() );
        $author = $msg->user();

        if ( $msg->userName() )
            $anonymous = $msg->userName();
        else
            $anonymous = $ini->read_var( "eZForumMain", "AnonymousPoster" );

        if ( $author->id() == 0 )
        {
            $MessageAuthor = $anonymous;
        }
        else
        {
            $MessageAuthor = $author->firstName() . " " . $author->lastName();
        }

        $t->set_var( "message_user", $MessageAuthor );
        $action_value = "update";
        $t->set_var( "message_id", $MessageID );
        $t->set_var( "forum_id", $msg->forumID() );
    }
}

$t->set_var( "action_value", $action_value );
$t->set_var( "error_msg", $error_msg );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "headline", $headline );
$t->pparse( "output", "message_page" );

?>
