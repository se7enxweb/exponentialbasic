<?php
//
// $Id: quizmyscores.php 9344 2002-03-06 08:56:33Z jhe $
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

// First check if this game is open
// Check if there are other open games

// include_once( "classes/ezlocale.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/ezlist.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezquiz/classes/ezquizquestion.php" );
// include_once( "ezquiz/classes/ezquizgame.php" );
// include_once( "ezquiz/classes/ezquizanswer.php" );
// include_once( "ezquiz/classes/ezquizscore.php" );

$ini =& INIFile::globalINI();

$Limit = $ini->read_var( "eZQuizMain", "ScoreLimit" );
$Language = $ini->read_var( "eZQuizMain", "Language" );
$ScoreCurrent = $ini->read_var( "eZQuizMain", "ScoreCurrent" );

$t = new eZTemplate( "kernel/ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                     "kernel/ezquiz/user/intl/", $Language, "quiz.php" );

$intl = new INIFile( "kernel/ezquiz/user/intl/". $Language . "/quiz.php.ini" );

$t->setAllStrings();

$t->set_file( "score_page_tpl", "quizmyscores.tpl" );

$t->set_block( "score_page_tpl", "error_item_tpl", "error_item" );
$t->set_block( "score_page_tpl", "logged_in_user_item_tpl", "logged_in_user_item" );
$t->set_block( "score_page_tpl", "no_scores_item_tpl", "no_scores_item" );
$t->set_block( "no_scores_item_tpl", "game_item_tpl", "game_item" );
$t->set_block( "score_page_tpl", "score_list_item_tpl", "score_list_item" );
$t->set_block( "score_list_item_tpl", "score_item_tpl", "score_item" );

$t->set_var( "error_item", "" );
$t->set_var( "no_scores_item", "" );
$t->set_var( "logged_in_user_item", "" );
$t->set_var( "score_list_item", "" );
$t->set_var( "score_item", "" );
$t->set_var( "game_item", "" );

$printScores = false;

$user =& eZUser::currentUser();

if ( is_a( $user, "eZUser" ) )
{
    $UserID = $user->id();

    $t->set_var( "user_id", $UserID );
    $t->set_var( "user_login", $user->login() );
    $t->set_var( "user_first", $user->firstName() );
    $t->set_var( "user_last", $user->lastName() );

    $t->parse( "logged_in_user_item", "logged_in_user_item_tpl" );

    $score = new eZQuizScore();
    $scores = $score->getAllByUser( $user, $Offset, $Limit );

    $count = count( $scores );
    $scoreCount = $score->countAllByUser( $user );

    $last = 0;
    $lastColor = "bgdark";
    $position = $Offset + 1;
    $locale = new eZLocale( $Language );
    if ( $scoreCount > 0 )
    {
        $printScores = true;
    }
    else
    {
        // Is there a game?
        $t->parse( "no_scores_item", "no_scores_item_tpl" );
    }
}
else
{
    $error = "login";
}

if ( $printScores == true )
{
    foreach ( $scores as $score )
    {

        $currentScore = $score->totalScore();

        if ( $currentScore == $last && $position != ( $Offset + 1 ) )
        {
            $t->set_var( "score_position", "&nbsp;" );

            if ( $lastColor == "bglight" || $i = 0 )
            {
                $t->set_var( "td_class", "bglight" );
            }
            else
            {
                $t->set_var( "td_class", "bgdark" );
            }
        }
        else
        {
            $t->set_var( "score_position", $position );
            if ( $lastColor == "bglight" )
            {
                $t->set_var( "td_class", "bgdark" );
                $lastColor = "bgdark";
            }
            else
            {
                $t->set_var( "td_class", "bglight" );
                $lastColor = "bglight";
            }
        }


        $game = $score->game();

        $t->set_var( "game_name", $game->name() );
        $t->set_var( "game_id", $game->id() );
        $t->set_var( "game_score", $currentScore );
        $t->set_var( "game_questions", $game->numberOfQuestions() );
        $t->set_var( "game_players", $game->numberOfPlayers() );

        $t->parse( "score_item", "score_item_tpl", true );

        $last = $currentScore;
        $position++;
        $i++;
    }

    $t->parse( "score_list_item", "score_list_item_tpl" );
}

eZList::drawNavigator( $t, $scoreCount, $Limit, $Offset, "score_page_tpl" );

if ( $error )
{
    switch ( $error )
    {
        case "login":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_login" ) );
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

$t->pparse( "output", "score_page_tpl" );

?>