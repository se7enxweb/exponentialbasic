<?php
// 
// $id$
//
// Created on: <26-Oct-2000 14:26:18 ce>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZPollMain", "Language" );

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezpoll/classes/ezpollchoice.php" );

$t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/user/intl/", $Language, "votepage.php" );

$t->setAllStrings();

$poll = new eZPoll( $PollID );

if ( $poll->isClosed() )
{
    eZHTTPTool::header( "Location: /poll/result/$PollID" );
    exit();
}

$t->set_file( array(
    "vote_box" => "votepage.tpl"
    ) );

$t->set_block( "vote_box", "vote_item_tpl", "vote_item" );
$t->set_block( "vote_box", "vote_buttons_tpl", "vote_buttons" );
$t->set_block( "vote_box", "no_items_tpl", "no_items" );

$choice = new eZPollChoice();

$choiceList = $choice->getAll( $PollID );

foreach( $choiceList as $choiceItem )
{
    $t->set_var( "choice_name", $choiceItem->name() );
    $t->set_var( "choice_id", $choiceItem->id() );

    $t->set_var( "no_items", "" );
    $t->parse( "vote_item", "vote_item_tpl", true );

}

if ( count ( $choiceList ) == 0 )
{
    $t->set_var( "vote_buttons", "" );
    $t->parse( "no_items", "no_items_tpl" );
    $t->set_var( "vote_item", "" );
}
else
{
    $t->parse( "vote_buttons", "vote_buttons_tpl");
}


$poll = new eZPoll();
$poll->get( $PollID );
$t->set_var( "head_line", $poll->name() );
$t->set_var( "description", $poll->description() );
$t->set_var( "poll_id", $PollID );

  
$t->pparse( "output", "vote_box" );

?>
