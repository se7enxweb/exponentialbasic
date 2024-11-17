<?php
// 
// $Id: pollist.php 6225 2001-07-20 11:22:30Z jakobn $
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
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZPollMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZPollMain", "DocumentRoot" );
$errorIni = new INIFIle( "ezpoll/user/intl/" . $Language . "/pollist.php.ini", false );

$noItem = $errorIni->read_var( "strings", "noitem" );

include_once( $DOC_ROOT . "/classes/ezpoll.php" );


$t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                     "ezpoll/user/intl/", $Language, "pollist.php" );

$t->setAllStrings();

$t->set_file( array(
    "poll_list_page" => "pollist.tpl"
    ) );

$t->set_block( "poll_list_page", "poll_item_tpl", "poll_item" );

$poll = new eZPoll();
$pollList = $poll->getAllActive( );

$languageIni = new INIFile( "ezpoll/user/intl/" . $Language . "/pollist.php.ini", false );
$nonActive =  $languageIni->read_var( "strings", "non_active" );
$active =  $languageIni->read_var( "strings", "active" );

if ( !$pollList )
{
    $t->set_var( "poll_item", $noItem );
}

$i=0;
foreach( $pollList as $pollItem )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "poll_id", $pollItem->id() );
    $t->set_var( "poll_name", $pollItem->name() );
    $t->set_var( "poll_description", $pollItem->description() );
    if ( $pollItem->IsClosed() )
    {
        $t->set_var( "action", "result" );
        $t->set_var( "poll_is_closed", $nonActive );
    }
    else
    {
        $t->set_var( "action", "votepage" );
        $t->set_var( "poll_is_closed", $active );
    }
    $i++;
    $t->parse( "poll_item", "poll_item_tpl", true );
}

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "poll_list_page" );

?>
