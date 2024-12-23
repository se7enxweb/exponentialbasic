<?php
//
// $Id: ezquizscore.php 8687 2001-12-06 10:19:29Z jhe $
//
// eZQuizScore class
//
// Created on: <29-May-2001 14:01:35 pkej>
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

//!! eZQuiz
//! eZQuizScore documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezquiz/classes/ezquizgame.php" );

class eZQuizScore
{

    /*!
      Constructs a new eZQuizScore object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1, $fetch = true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a eZQuizScore object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name =& $db->escapeString( $this->Name );
        $userID = $this->User->id();
        $gameID = $this->Game->id();

        if ( !isset( $this->ID ) || $this->ID == 0 )
        {
            $db->lock( "eZQuiz_Score" );
			$this->ID = $db->nextID( "eZQuiz_Score", "ID" );

            $res[] = $db->query( "INSERT INTO eZQuiz_Score
                                     (ID, GameID, UserID, TotalScore, LastQuestion, FinishedGame)
                                     VALUES
                                     ('$this->ID','$gameID','$userID','$this->TotalScore','$this->LastQuestion','$this->FinishedGame')" );
            $db->unlock();
        }
        else if ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZQuiz_Score SET
                                     UserID='$userID',
                                     GameID='$gameID',
                                     TotalScore='$this->TotalScore',
                                     LastQuestion='$this->LastQuestion',
                                     FinishedGame='$this->FinishedGame'
                                     WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZQuizScore object from the database.
    */
    function delete( $catID = -1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZQuiz_Score WHERE ID='$catID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $scoreArray, "SELECT * FROM eZQuiz_Score WHERE ID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );

            if ( count( $scoreArray ) == 1 )
            {
                $this->fill( &$scoreArray[0] );
                $ret = true;
            }
            elseif ( count( $scoreArray ) == 0 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
        Fetches the object information from the database.

        This function uses the user id and the game id to fetch the info.

      True is retuned if successful, false (0) if not.
    */
    function getUserGame( $user, $game )
    {
        if ( is_a( $user, "eZUser" ) )
            $userID = $user->id();

        if ( is_a( $game, "eZQuizGame" ) )
            $gameID = $game->id();

        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $gameID != "" && $userID != "" )
        {
            $db->array_query( $scoreArray, "SELECT * FROM eZQuiz_Score WHERE GameID='$gameID' AND UserID='$userID'",
                              array( "Offset" => 0, "Limit" => 1 ) );

            if ( count( $scoreArray ) == 1 )
            {
                $this->fill( &$scoreArray[0] );
                $ret = true;
            }
            elseif ( count( $scoreArray ) == 0 )
            {
                $this->ID = 0;
            }
       }

       return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$scoreArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $scoreArray[$db->fieldName( "ID" )];
        $this->User =& new eZUser( $scoreArray[$db->fieldName( "UserID" )] );
        $this->Game =& new eZQuizGame( $scoreArray[$db->fieldName( "GameID" )] );
        $this->TotalScore = $scoreArray[$db->fieldName( "TotalScore" )];
        $this->LastQuestion = $scoreArray[$db->fieldName( "LastQuestion" )];
        $this->FinishedGame = $scoreArray[$db->fieldName( "FinishedGame" )];
    }

    /*!
      Returns all the scores found in the database.

      The scores are returned as an array of eZQuizScore objects.
    */
    function getAll( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $scoreArray = array();

        $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score
                          DESC LIMIT $offset, $limit" );

        for ( $i = 0; $i < count( $scoreArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizScore( $scoreArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count FROM eZQuiz_Score" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID to the score. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the user of the score.
    */
    function user()
    {
        return $this->User;
    }

    /*!
      Returns the game of the score.
    */
    function game()
    {
        return $this->Game;
    }

    /*!
      Sets the user
    */
    function setUser( &$user )
    {
        if ( is_a( $user, "eZUser" ) )
            $this->User = $user;
    }

    /*!
      Sets the game
    */
    function setGame( &$game )
    {
        if ( is_a( $game, "eZQuizGame" ) )
            $this->Game = $game;
    }

    /*!
      Sets the score
    */
    function setTotalScore( $score )
    {
        $this->TotalScore = $score;
    }

    /*!
      Gets the score
    */
    function totalScore()
    {
        return $this->TotalScore;
    }

    /*!
      Sets the last question
    */
    function setLastQuestion( $question )
    {
        $this->LastQuestion = $question;
    }

    /*!
      Sets the next question to be answere
    */
    function setNextQuestion( $question )
    {
        $this->LastQuestion = $question - 1;
    }


    /*!
      Gets the last question
    */
    function lastQuestion()
    {
        return $this->LastQuestion;
    }

    /*!
      Gets the next question number
    */
    function nextQuestion()
    {
        $ret = $this->LastQuestion;
        $ret++;
        return $ret;
    }

    /*!
      Sets the game finished
    */
    function setFinishedGame( $finished = true )
    {
        if ( $finished )
        {
            $this->FinishedGame = 1;
        }
        else
        {
            $this->FinishedGame = 0;
        }
    }

    /*!
      Returns true if this is a finished game.
    */
    function isFinishedGame()
    {
        $ret = false;
        if ( $this->FinishedGame )
        {
            $ret = true;
        }

        return $ret;
    }

    /*!
        This function returns all the scores for a game.
     */
    function scores( &$game )
    {
        $db =& eZDB::globalDatabase();

        if ( is_a( $game, "eZQuizGame" ) )
            $gameID = $game->id();


        $returnArray = array();
        $scoreArray = array();

        $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score WHERE GameID='$gameID' AND FinishedGame=1 ORDER BY TotalScore DESC" );

        for ( $i = 0; $i < count( $scoreArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizScore( $scoreArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

     /*!
        This function returns one score as the high scorer of a game.
     */
    function highScore( &$game )
    {
        $db =& eZDB::globalDatabase();

        if ( is_a( $game, "eZQuizGame" ) )
            $gameID = $game->id();

        $scoreArray = array();

        $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score WHERE GameID='$gameID' AND FinishedGame=1 ORDER BY TotalScore DESC" );

        $returnObject = new eZQuizScore( $scoreArray[0][$db->fieldName( "ID" )] );
        return $returnObject;
    }

    /*!
      Returns all the scores found in the database for one game.

      The scores are returned as an array of eZQuizScore objects.
    */
    function getAllByGame( &$game, $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $scoreArray = array();

        if ( is_a( $game, "eZQuizGame" ) )
            $gameID = $game->id();

        $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score
                          WHERE GameID='$gameID' AND FinishedGame=1 ORDER BY TotalScore DESC",
                          array( "Offset" => $offset, "Limit" => $limit ) );

        for ( $i = 0; $i < count( $scoreArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizScore( $scoreArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }


    /*!
      Returns the number of players of one game.
    */
    function countAllByGame( &$game )
    {
        $db =& eZDB::globalDatabase();

        $scoreArray = array();

        if ( is_a( $game, "eZQuizGame" ) )
            $gameID = $game->id();

        $db->array_query( $scoreArray, "SELECT count(ID) as Count FROM eZQuiz_Score
                          WHERE GameID='$gameID' AND FinishedGame=1" );

        $ret = $scoreArray[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns all the scores found in the database for one user.

      The scores are returned as an array of eZQuizScore objects.
    */
    function getAllByUser( &$user, $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $scoreArray = array();

        if ( is_a( $user, "eZUser" ) )
            $userID = $user->id();

        $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score
                          WHERE UserID='$userID' AND FinishedGame=1 ORDER BY TotalScore DESC",
                          array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $scoreArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizScore( $scoreArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the count of games this user has participated in.
    */
    function countAllByUser( &$user )
    {
        $db =& eZDB::globalDatabase();

        $scoreArray = array();

        if ( is_a( $user, "eZUser" ) )
            $userID = $user->id();

        $db->array_query( $scoreArray, "SELECT count(ID) AS Count FROM eZQuiz_Score
                          WHERE UserID='$userID' AND FinishedGame=1" );

        $ret = $scoreArray[0][$db->fieldName( "Count" )];

        return $ret;
    }
    /*!
      Returns all the saved scores found in the database for one user.

      The scores are returned as an array of eZQuizScore objects.
    */
    function getAllSavedByUser( &$user, $offset = 0, $limit = 20, $latestOnly = true )
    {
        $db =& eZDB::globalDatabase();
        $now = (new eZDateTime())->timeStamp( true );
        $returnArray = array();
        $scoreArray = array();

        if ( is_a( $user, "eZUser" ) )
            $userID = $user->id();

        if ( $latestOnly == true )
        {
            $db->array_query( $scoreArray, "SELECT Score.ID FROM eZQuiz_Score AS Score, eZQuiz_Game AS Game
                                            WHERE Game.StartDate <= '$now' AND Game.StopDate >= '$now' AND
                                            Score.UserID='$userID' AND Score.FinishedGame=0" );
        }
        else
        {
            $db->array_query( $scoreArray, "SELECT ID FROM eZQuiz_Score
                        WHERE UserID='$userID' AND FinishedGame=0 ORDER BY TotalScore
                        DESC", array( "Limit" => $limit, "Offset" => $offset ) );
        }
        for ( $i = 0; $i < count( $scoreArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizScore( $scoreArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the count of saved games this user has.
    */
    function countAllSavedByUser( &$user, $latestOnly = true )
    {
        $db =& eZDB::globalDatabase();
        $now = (new eZDateTime())->timeStamp( true );
        $scoreArray = array();

        if ( is_a( $user, "eZUser" ) )
            $userID = $user->id();

        if ( $latestOnly == true )
        {
            $db->array_query( $scoreArray, "SELECT count(Score.ID) AS Count FROM eZQuiz_Score AS Score, eZQuiz_Game AS Game
                        WHERE Game.StartDate <= '$now' AND Game.StopDate >= '$now' AND Score.UserID='$userID' AND Score.FinishedGame=0" );
        }
        else
        {
            $db->array_query( $scoreArray, "SELECT count(ID) AS Count FROM eZQuiz_Score
                        WHERE UserID='$userID' AND FinishedGame=0" );
        }
        $ret = $scoreArray[0][$db->fieldName( "Count" )];

        return $ret;
    }


    var $ID;
    var $User;
    var $Game;
    var $TotalScore;
    var $LastQuestion;
    var $FinishedGame;
}

?>