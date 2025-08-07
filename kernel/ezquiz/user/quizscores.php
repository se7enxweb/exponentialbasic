<?php
// 
// $Id: quizscores.php 6226 2001-07-20 11:24:09Z jakobn $
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
// include_once( "ezquiz/classes/ezquizquestion.php" );
// include_once( "ezquiz/classes/ezquizgame.php" );
// include_once( "ezquiz/classes/ezquizanswer.php" );
// include_once( "ezquiz/classes/ezquizscore.php" );

$ini =& eZINI::instance( 'site.ini' );

$Limit = $ini->variable( "eZQuizMain", "ScoreLimit" );
$Language = $ini->variable( "eZQuizMain", "Language" );
$ScoreCurrent = $ini->variable( "eZQuizMain", "ScoreCurrent" );

$t = new eZTemplate( "kernel/ezquiz/user/" . $ini->variable( "eZQuizMain", "TemplateDir" ),
                     "kernel/ezquiz/user/intl/", $Language, "quiz.php" );

$intl = new INIFIle( "kernel/ezquiz/user/intl/". $Language . "/quiz.php.ini" );

$t->setAllStrings();

$t->set_file( array(
    "score_page_tpl" => "scores.tpl"
    ) );

$t->set_block( "score_page_tpl", "error_item_tpl", "error_item" );
$t->set_block( "score_page_tpl", "no_scores_item_tpl", "no_scores_item" );
$t->set_block( "score_page_tpl", "not_closed_item_tpl", "not_closed_item" );
$t->set_block( "score_page_tpl", "future_item_tpl", "future_item" );
$t->set_block( "score_page_tpl", "score_list_item_tpl", "score_list_item" );
$t->set_block( "score_list_item_tpl", "score_item_tpl", "score_item" );

$t->set_var( "error_item", "" );
$t->set_var( "no_scores_item", "" );
$t->set_var( "not_closed_item", "" );
$t->set_var( "future_item", "" );
$t->set_var( "score_list_item", "" );
$t->set_var( "score_item", "" );

$game = new eZQuizGame( $GameID );
$t->set_var( "game_id", $GameID );
$t->set_var( "game_name", $game->name() );
$t->set_var( "questions", $game->numberOfQuestions() );
$t->set_var( "players", $game->numberOfPlayers() );

$score = new eZQuizScore();
$scores = $score->getAllByGame( $game, $Offset, $Limit );

$count = count( $scores ); 
$scoreCount = $score->countAllByGame( $game );

$last = 0;
$lastColor = "bgdark";
$position = $Offset + 1;
$locale = new eZLocale( $Language );

if( $game->isClosed() )
{
    $printScores = true;
}
else
{
    $printScores = false;
    $GenerateStaticPage = false;
    
    if( $game->isFutureGame() )
    {
        $start = $game->startDate();
        $t->set_var( "game_start", $locale->format( $start, false ) );
        $t->parse( "future_item", "future_item_tpl" );
    }
    elseif( $ScoreCurrent == "disabled" )
    {
        $stop = $game->stopDate();
        $t->set_var( "game_stop", $locale->format( $stop, false ) );
        $t->parse( "not_closed_item", "not_closed_item_tpl" );
    }
    else
    {
        $printScores = true;
    }
}

if( $printScores == true )
{
    if( $count > 0 )
    {
        foreach( $scores as $score )
        {

            $currentScore = $score->totalScore();

            if( $currentScore == $last && $position != ( $Offset + 1 ) )
            {
                $t->set_var( "score_position", "&nbsp;" );

                if( $lastColor == "bglight" || $i = 0 )
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
                if( $lastColor == "bglight" )
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


            $t->set_var( "score", $currentScore );
            
            $user = $score->user();
            
            $t->set_var( "player", $user->login() );
            $t->set_var( "player_id", $user->id() );

            $t->parse( "score_item", "score_item_tpl", true );

            $last = $currentScore;
            $position++;
            $i++;
        }

        $t->parse( "score_list_item", "score_list_item_tpl" );
    }
    else
    {
        $t->parse( "no_scores_item", "no_scores_item_tpl" );
    }
}

eZList::drawNavigator( $t, $scoreCount, $Limit, $Offset, "score_page_tpl" );

if( $error )
{
    switch( $error )
    {
        default:
        {
            $t->set_var( "error_message", $intl->variable( "strings", "error_undefined" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;
    }
}

$t->pparse( "output", "score_page_tpl" );

?>