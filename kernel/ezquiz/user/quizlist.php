<?php
// 
// $Id: quizlist.php 8125 2001-10-31 12:11:58Z jhe $
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
// include_once( "ezquiz/classes/ezquizscore.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZQuizMain", "Language" );
$ListLimit = $ini->read_var( "eZQuizMain", "ListLimit" );

$intl = new INIFile( "kernel/ezquiz/user/intl/". $Language . "/quiz.php.ini" );
$Limit = $ListLimit;

$t = new eZTemplate( "kernel/ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                     "kernel/ezquiz/user/intl/", $Language, "quiz.php" );

$t->set_file( "quiz_list_page_tpl", "quizlist.tpl" );

$t->set_block( "quiz_list_page_tpl", "game_list_item_tpl", "game_list_item" );
$t->set_block( "game_list_item_tpl", "game_item_tpl", "game_item" );
$t->set_block( "quiz_list_page_tpl", "error_item_tpl", "error_item" );

$t->set_block( "game_item_tpl", "score_link_tpl", "score_link" );

$t->set_var( "game_start", "" );
$t->set_var( "game_stop", "" );
$t->set_var( "game_list_item", "" );
$t->set_var( "game_item", "" );
$t->set_var( "error_item", "" );

$game = new eZQuizGame();
$score = new eZQuizScore();

switch ( $Action )
{
    case "list":
    {
        $games = $game->getAll( $Offset, $Limit );
        $gameCount = $game->count();
        $isGame = true;
        $t->set_var( "header_of_page", "header_game_list" );
    }
    break;
    case "future":
    {
        $games = $game->opensNext( $Offset, $Limit );
        $gameCount = $game->numberOfOpenGames();
        $isGame = true;
        $t->set_var( "header_of_page", "header_future_game_list" );
    }
    break;
    case "past":
    {
        $games = $game->closedGames( $Offset, $Limit );
        $gameCount = $game->numberOfClosedGames();
        $isGame = true;
        $t->set_var( "header_of_page", "header_past_game_list" );
    }
    break;
    case "open":
    {
        $scores = $score->getAllSavedByUser( $user, $Offset, $Limit );
        $gameCount = $score->countAllSavedByUser( $user );
        $isScore = true;
        $t->set_var( "header_of_page", "header_open_game_list" );
    }
    break;
    case "closed":
    {
        $scores = $score->getAllByUser( $user, $Offset, $Limit );
        $gameCount = $score->countAllByUser( $user );
        $isScore = true;
        $t->set_var( "header_of_page", "header_closed_game_list" );
    }
    break;
}

if ( $isGame )
{
    $count = count( $games );
}
else
{
    $count = count( $scores );
}
$locale = new eZLocale( $Language );

if ( $count > 0 && $isGame )
{
    $i = 0;
    foreach ( $games as $game )
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

        if ( $start->day() != 0  )
        {
            $t->set_var( "game_start", $locale->format( $start, true ) );
        }

        if ( $stop->day() != 0  )
        {
            $t->set_var( "game_stop", $locale->format( $stop, true ) );
        }

        if ( $game->isFutureGame() )
        {
            $t->set_var( "score_link", "&nbsp;" );
        }
        else
        {
            $t->parse( "score_link", "score_link_tpl" );
        }


        $t->parse( "game_item", "game_item_tpl", true );
        $i++;
    }
    $t->parse( "game_list_item", "game_list_item_tpl" );
}
elseif ( $count > 0 && $isScore )
{
    $i = 0;
    foreach ( $scores as $score )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $game = $score->game();

        $t->set_var( "game_id", $game->id() );
        $t->set_var( "game_name", $game->name() );
        $t->set_var( "game_description", $game->description() );
        $t->set_var( "game_questions", $game->numberOfQuestions() );
        $t->set_var( "game_players", $game->numberOfPlayers() );
        $start = $game->startDate();
        $stop = $game->stopDate();
        
        if ( is_object( $start ) )
        {
            if ( $start->day() != 0  )
            {
                $t->set_var( "game_start", $locale->format( $start, true ) );
            }
        }
        
        if ( is_object( $stop ) )
        {
            if ( $stop->day() != 0  )
            {
                $t->set_var( "game_stop", $locale->format( $stop, true ) );
            }
        }

        if ( $game->isFutureGame() )
        {
            $t->set_var( "score_link", "&nbsp;" );
        }
        else
        {
            $t->parse( "score_link", "score_link_tpl" );
        }


        $t->parse( "game_item", "game_item_tpl", true );
        $i++;
    }
    $t->parse( "game_list_item", "game_list_item_tpl" );
}
else
{
    switch ( $Action )
    {
        case "list":
        {
            $error = "list_empty";
        }
        break;
        case "future":
        {
            $error = "future_empty";
        }
        break;
        case "past":
        {
            $error = "past_empty";
        }
        break;
        case "open":
        {
            $error = "open_empty";
        }
        break;
        case "closed":
        {
            $error = "closed_empty";
        }
        break;
        default:
        {
            $error = true;
        }
        break;
    }
}

if ( $error )
{
    $GenerateStaticPage = false;
    switch ( $error )
    {
        case "list_empty":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_list_empty" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
        case "future_empty":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_future_empty" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
        case "past_empty":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_past_empty" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
        case "open_empty":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_open_empty" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
        case "closed_empty":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_closed_empty" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
        default:
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_undefined" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
    }
}

$t->setAllStrings();

eZList::drawNavigator( $t, $gameCount, $Limit, $Offset, "quiz_list_page_tpl" );

$t->pparse( "output", "quiz_list_page_tpl" );

?>
