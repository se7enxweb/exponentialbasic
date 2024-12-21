<?php
// 
// $Id: quizopen.php 6226 2001-07-20 11:24:09Z jakobn $
//
// Created on: <28-May-2001 11:24:41 pkej>
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
// include_once( "classes/ezlist.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztemplate.php" );

// include_once( "ezquiz/classes/ezquizgame.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZQuizMain", "Language" );
$ListLimit = $ini->read_var( "eZQuizMain", "ListLimit" );

$Limit = $ListLimit;

$t = new eZTemplate( "kernel/ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                     "kernel/ezquiz/user/intl/", $Language, "quiz.php" );

$t->setAllStrings();

$t->set_file( array(
    "quiz_list_page_tpl" => "quizlist.tpl"
    ) );

$t->set_block( "quiz_list_page_tpl", "game_list_item_tpl", "game_list_item" );
$t->set_block( "game_list_item_tpl", "game_item_tpl", "game_item" );
$t->set_block( "quiz_list_page_tpl", "no_game_list_item_tpl", "no_game_list_item" );

$game = new eZQuizGame();
$games = $game->openGames( $Offset, $Limit );
$count = count( $games );
$locale = new eZLocale( $Language );

$t->set_var( "game_start", "" );
$t->set_var( "game_stop", "" );
$t->set_var( "game_list_item", "" );
$t->set_var( "no_game_list_item", "" );

if( $count >= 1 )
{
    $i = 0;
    foreach( $games as $game )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "game_id", $game->id() );
        $t->set_var( "game_name", $game->name() );
        $t->set_var( "game_description", $game->description() );
        $t->set_var( "game_questions", $game->numberOfQuestions() );
        $t->set_var( "game_players", $game->numberOfPlayers() );
        $start = $game->startDate();
        $stop = $game->stopDate();

        if( $start->day() != 0  )
        {
            $t->set_var( "game_start", $locale->format( $start, true ) );
        }

        if( $stop->day() != 0  )
        {
            $t->set_var( "game_stop", $locale->format( $stop, true ) );
        }

        $t->parse( "game_item", "game_item_tpl", true );
        $i++;
    }
    
    $t->parse( "game_list_item", "game_list_item_tpl" );
}
else
{
    $t->parse( "no_game_list_item", "no_game_list_item_tpl" );
}

eZList::drawNavigator( $t, $gameCount, $Limit, $Offset, "quiz_list_page_tpl" );

$t->pparse( "output", "quiz_list_page_tpl" );

?>
