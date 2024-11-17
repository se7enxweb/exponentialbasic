<?php
// 
// $Id: quizplay.php 9344 2002-03-06 08:56:33Z jhe $
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

include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezquiz/classes/ezquizquestion.php" );
include_once( "ezquiz/classes/ezquizgame.php" );
include_once( "ezquiz/classes/ezquizanswer.php" );
include_once( "ezquiz/classes/ezquizscore.php" );
include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZQuizMain", "Language" ); 
$t = new eZTemplate( "ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                     "ezquiz/user/intl/", $Language, "quiz.php" );

$intl = new INIFile( "ezquiz/user/intl/". $Language . "/quiz.php.ini" );

if ( isset( $SaveButton ) )
{
    // Do we have the same user uploading the data?
    // Me? Paranoid?
    if ( $UserID == $user->id() )
    {
        $question = new eZQuizQuestion( $QuestionID );
        $game = $question->game();
        $score = new eZQuizScore();
        $score->getUserGame( $user, $game );
        if ( $score->id() == 0 )
        {
            $score->setUser( $user );
            $score->setGame( $game );
        }
        $score->setNextQuestion( $Placement );
        $score->store();
        
        eZHTTPTool::header( "Location: /quiz/my/open/" );
    }
}



if ( isset( $NextButton ) )
{
    // Do we have the same user uploading the data?
    // Me? Paranoid?
    if ( $UserID == $user->id() )
    {
        $question = new eZQuizQuestion( $QuestionID );
        $alternative = new eZQuizAlternative( $AlternativeID );
        if ( $question->isAlternative( $alternative ) )
        {
            if ( $alternative->isCorrect() )
            {
                $scoreValue = $question->score();
                if ( empty( $scoreValue ) )
                {
                    $scoreValue = 1;
                }
            }
            $answer = new eZQuizAnswer();
            
            $answer->setUser( $user );
            $answer->setAlternative( $alternative );
            $game = $question->game();
            $score = new eZQuizScore();

            $score->getUserGame( $user, $game );
            if ( $answer->hasAnswered() )
            {
                $QuestionNum = $score->nextQuestion();
            }
            else
            {
                $totalScore = $score->totalScore() + $scoreValue;
                $score->setTotalScore( $totalScore );
                $score->setGame( $game );
                $score->setUser( $user );
                
                $score->setNextQuestion( $QuestionNum );
                
                if ( $QuestionNum > $game->numberOfQuestions() )
                {
                    $score->setFinishedGame( true );
                    include_once( "ezquiz/classes/ezquiztool.php" );
                }
                else
                {
                    $score->setFinishedGame( false );
                }
                $answer->store();
                $score->store();
            }
        }
        else
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_no_such_alternative" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
    }
    else
    {
        $t->set_var( "error_message", $intl->read_var( "strings", "error_differing_user_ids" ) );
        $t->parse( "error_item", "error_item_tpl" );
    }
}

$t->setAllStrings();

$t->set_file( "question_page_tpl", "question.tpl" );

$t->set_block( "question_page_tpl", "question_item_tpl", "question_item" );
$t->set_block( "question_page_tpl", "high_score_item_tpl", "high_score_item" );
$t->set_block( "question_page_tpl", "your_score_item_tpl", "your_score_item" );
$t->set_block( "question_page_tpl", "start_item_tpl", "start_item" );
$t->set_block( "question_page_tpl", "error_item_tpl", "error_item" );
$t->set_block( "question_item_tpl", "alternative_item_tpl", "alternative_item" );

$t->set_var( "start_item", "" );
$t->set_var( "question_item", "" );
$t->set_var( "high_score_item", "" );
$t->set_var( "your_score_item", "" );
$t->set_var( "error_item", "" );

$game = new eZQuizGame( $GameID );
$t->set_var( "game_id", $GameID );
$t->set_var( "user_id", $user->id() );
$t->set_var( "game_name", $game->name() );
$t->set_var( "questions", $game->numberOfQuestions() );
$t->set_var( "players", $game->numberOfPlayers() );

$score = new eZQuizScore();
$highScorer = $score->highScore( $game );

if ( $highScorer->id() > 0 )
{
    $t->set_var( "high_score", $highScorer->totalScore() );
    $highPlayer = $highScorer->user();
    $t->set_var( "scorer", $highPlayer->login() );
    $t->set_var( "scorer_id", $highPlayer->id() );
    $t->parse( "high_score_item", "high_score_item_tpl" );
}

$score->getUserGame( $user, $game );

if ( $score->isFinishedGame() )
{
    $t->set_var( "your_score", $score->totalScore() );
    $t->set_var( "your_name", $user->login() );
    $t->set_var( "your_id", $user->id() );
    $t->parse( "your_score_item", "your_score_item_tpl" );
}

// Find out which question this user should start on (has he saved earlier info).

if ( $score->nextQuestion() > 1 )
{
    $QuestionNum = $score->nextQuestion();
}

if ( $QuestionNum == 0 )
{
    $t->parse( "start_item", "start_item_tpl" );
}
elseif ( empty( $error ) )
{
    if ( $QuestionNum >= 1 )
    {
        $QuestionNum = $score->nextQuestion();
    }
    
    $questionCount = $game->numberOfQuestions();
    $currentQuestion = $game->question( $QuestionNum );

    if ( $questionCount <= 0 )
    {
        $t->set_var( "error_message", $intl->read_var( "strings", "error_no_questions" ) );
        $t->parse( "error_item", "error_item_tpl" );
    }
    else if ( $QuestionNum <= $questionCount )
    {
        $t->set_var( "question_id", $currentQuestion->id() );

        $t->set_var( "placement", $QuestionNum );
        $QuestionNum++;

        $t->set_var( "next_question_num", $QuestionNum );
        $t->set_var( "question_name", $currentQuestion->name() );

        $alternatives = $currentQuestion->alternatives();
        
        $i = 0;
        $count = count( $alternatives );
        
        if ( $count == 0 )
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_no_alternatives" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        else
        {
            foreach ( $alternatives as $alternative )
            {
                if ( ( $i % 2 ) == 0 )
                {
                    $t->set_var( "td_class", "bglight" );
                }
                else
                {
                    $t->set_var( "td_class", "bgdark" );
                }
                $t->set_var( "alternative_id", $alternative->id() );
                $t->set_var( "alternative_name", $alternative->name() );
                $t->parse( "alternative_item", "alternative_item_tpl", true );
                $i++;
            }
            $t->parse( "question_item", "question_item_tpl" );
        }
    }
    else
    {
        // finished game.
    }
}

if ( $error )
{
    switch ( $error )
    {
        case "unopened":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_unopened" ) );
            $t->parse( "error_item", "error_item_tpl" );
        }
        break;

        case "closed":
        {
            $t->set_var( "error_message", $intl->read_var( "strings", "error_closed" ) );
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

$t->pparse( "output", "question_page_tpl" );

?>
