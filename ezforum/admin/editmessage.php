<?php
//
// $Id: editmessage.php 7759 2001-10-10 13:18:29Z jhe $
//
// Author: Lars Wilhelmsen <lw@ez.no>
//  
// Created on: <25-Jul-2000 15:13:15 lw>
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

/*!
*/

// include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

// include_once( "classes/template.inc" );
//// include_once( "$DOC_ROOT/classes/ezdb.php" );
// include_once( "$DOC_ROOT/classes/ezforummessage.php" );
// include_once( "../classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "edit" => "$DOC_ROOT/admin/templates/editmessage.tpl",
                    "navigation" => "$DOC_ROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOC_ROOT/templates/navigation-bottom.tpl" ) );

$msg = new eZforumMessage;
$msg->get( $message_id );

$t->set_var( "message_id", $message_id );
$t->set_var( "docroot", $DOC_ROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation", true);

// rest
$author = new eZUser();

$t->set_var( "user", $author->get( $msg->userId() ) );
$t->set_var( "topic", $msg->topic() );
$t->set_var( "body", $msg->body() );

if ( $msg->emailNotice() == "Y" )
{
    $t->set_var( "email-notice", "checked" );
}
else
{
    $t->set_var( "email-notice", "" );
}

$t->set_var( "link1-url", "admin/category.php" );
$t->set_var( "link1-caption", "Gå til topp" );
$t->set_var( "link2-url", "search.php" );
$t->set_var( "link2-caption", "Søk" );

$t->set_var( "back-url", "admin/message.php" );
$t->parse( "navigation-bar-bottom", "navigation-bottom", true );
$t->pparse( "output", "edit" );

?>
