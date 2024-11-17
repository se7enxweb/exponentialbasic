<?php
//
// $Id: ezquizgame.php 9344 2002-03-06 08:56:33Z jhe $
//
// ezquizgame class
//
// Created on: <22-May-2001 13:45:37 ce>
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
//! ezquizgame documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
include_once( "ezquiz/classes/ezquizquestion.php" );

class eZQuizGame
{

    /*!
      Constructs a new eZQuizGame object.

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
      Stores a eZQuizGame object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name =& $db->escapeString( $this->Name );
        $description =& $db->escapeString( $this->Description );
        $startDate =& $this->StartDate->timeStamp();
        $stopDate =& $this->StopDate->timeStamp();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZQuiz_Game" );
			$this->ID = $db->nextID( "eZQuiz_Game", "ID" );
            $res[] = $db->query( "INSERT INTO eZQuiz_Game
                                  (ID, Name, Description, StartDate, StopDate)
                                  VALUES
                                  ('$this->ID','$name','$description','$startDate','$stopDate')" );
            $db->unlock();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZQuiz_Game SET
                                     Name='$name',
                                     Description='$description',
                                     StartDate='$startDate',
                                     StopDate='$stopDate'
                                     WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZQuizGame object from the database.
    */
    function delete( $catID = -1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        $questions =& $this->questions();
        if ( is_array( $questions ) )
        {
            foreach ( $questions as $question )
            {
                $question->delete();
            }
        }

        $res[] = $db->query( "DELETE FROM eZQuiz_Game WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $quizArray, "SELECT * FROM eZQuiz_Game WHERE ID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );

            if ( count( $quizArray ) == 1 )
            {
                $this->fill( $quizArray[0] );
                $ret = true;
            }
            elseif ( count( $quizArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$quizArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $quizArray[$db->fieldName( "ID" )];
        $this->Name =& $quizArray[$db->fieldName( "Name" )];
        $this->Description =& $quizArray[$db->fieldName( "Description" )];
        $this->StartDate = new eZDate();
        $this->StartDate->setTimeStamp( $quizArray[$db->fieldName( "StartDate" )] );
        $this->StopDate = new eZDate();
        $this->StopDate->setTimeStamp( $quizArray[$db->fieldName( "StopDate" )] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizGame objects.
    */
    static public function &getAll( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        if ( $limit == false )
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZQuiz_Game
                                           ORDER BY StartDate DESC" );

        }
        else
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZQuiz_Game
                                           ORDER BY StartDate DESC",
                                           array( "Offset" => $offset, "Limit" => $limit ) );
        }

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    static public function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count FROM eZQuiz_Game" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID to the game. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the game.
    */
    function &name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the description of the game.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the start date of the game.
    */
    function &startDate()
    {
        return $this->StartDate;
    }

    /*!
      Returns the stop date of the game.
    */
    function &stopDate()
    {
        return $this->StopDate;
    }

    /*!
      Sets the login.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the description.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the start date for the game.
    */
    function setStartDate( &$date )
    {
        if ( is_a( $date, "eZDate" ) )
            $this->StartDate = $date;
    }

    /*!
      Sets the start date for the game.
    */
    function setStopDate( &$date )
    {
        if ( is_a( $date, "eZDate" ) )
            $this->StopDate = $date;
    }

    /*!
      Returns true if the game is closed!
    */
    function isClosed()
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );

        $ret = false;
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StopDate < '$now' AND ID = '$this->ID'" );

        $ret = $quizArray[0][$db->fieldName( "ID" )];

        if ( $ret == $this->ID )
        {
            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns true if the game is in the future!
    */
    function isFutureGame()
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );

        $ret = false;
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StartDate > '$now' AND ID = '$this->ID'" );

        $ret = $quizArray[0][$db->fieldName( "ID" )];

        if ( $ret == $this->ID )
        {
            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns every questions to this quiz game
      The questions is returned as an array of eZQuizQuestion objects.
    */
    function &questions()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Question WHERE GameID='$this->ID'" );

        for ( $i = 0; $i < count( $questionArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizQuestion( $questionArray[$i][$db->fieldName( "ID" )], true );
        }
        return $returnArray;
    }

    /*!
      Returns a specific question based on the placement (number)
      The question is returned as eZQuizQuestion objects.
    */
    function &question( $placement )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $question, "SELECT ID FROM eZQuiz_Question WHERE GameID='$this->ID' AND Placement='$placement'" );

        $return = new eZQuizQuestion( $question[$db->fieldName( "ID" )], true );

        return $return;
    }



    /*!
      Returns the number of questions to this quiz game
    */
    function &numberOfQuestions()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZQuiz_Question WHERE GameID='$this->ID'" );
        $ret = $result[$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns the number of players for this quiz game
    */
    function &numberOfPlayers()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZQuiz_Score WHERE GameID='$this->ID' AND FinishedGame=1" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns all the open games
    */
    function &openGames( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );
        $returnArray = array();
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StartDate <= '$now' AND StopDate >= '$now'
                                       ORDER BY StartDate DESC",
                                       array( "Offset" => $offset, "Limit" => $limit ) );

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the number of open games
    */
    function &numberOfOpenGames()
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT count(ID) as Count FROM eZQuiz_Game
                                       WHERE StartDate <= '$now' AND StopDate >= '$now'" );

        $ret = $quizArray[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns the games opening
    */
    function &opensNext( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );

        $returnArray = array();
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StartDate > '$now'
                                       ORDER BY StartDate DESC",
                          array( "Offset" => $offset, "Limit" => $limit ) );
        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the closed games
    */
    function &closedGames( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );
        $returnArray = array();
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StopDate < '$now'
                                       ORDER BY StartDate DESC",
                          array( "Offset" => $offset, "Limit" => $limit ) );

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the number of closed games
    */
    function &numberOfClosedGames()
    {
        $db =& eZDB::globalDatabase();
        $now = eZDateTime::timeStamp( true );
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT count(ID) as Count FROM eZQuiz_Game
                                       WHERE StopDate < '$now'" );
        $ret = $quizArray[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns all the games started within a period.
    */
    function &startedInPeriod( &$inStartDate, &$inStopDate )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        $stopDate = $inStopDate->timeStamp();
        $startDate = $inStartDate->timeStamp();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StartDate >= '$startDate' AND StartDate <= '$stopDate'
                                       ORDER BY StartDate" );
        $ret = $result[$db->fieldName( "Count" )];

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i] );
        }

        return $returnArray;
    }

    /*!
      Returns all the games ended within a period.
    */
    function &endedInPeriod( &$inStartDate, &$inStopDate )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        $startDate = $inStartDate->timeStamp();
        $stopDate = $inStopDate->timeStamp();

        $db->array_query( $quizArray, "SELECT * FROM eZQuiz_Game
                                       WHERE StopDate >= '$startDate' AND StopDate <= '$stopDate'
                                       ORDER BY StartDate" );
        $ret = $result[$db->fieldName( "Count" )];

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i] );
        }

        return $returnArray;
    }

     /*!
      Returns all the games which embraces this period.
    */
    function &embracingPeriod( &$inStartDate, &$inStopDate )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        $startDate = $inStartDate->timeStamp();
        $stopDate = $inStopDate->timeStamp();

        $db->array_query( $quizArray, "SELECT ID FROM eZQuiz_Game
                                       WHERE StartDate <= '$startDate' AND StopDate >= '$stopDate'
                                       ORDER BY StartDate" );
        $ret = $result[$db->fieldName( "Count" )];

        for ( $i = 0; $i < count( $quizArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizGame( $quizArray[$i] );
        }

        return $returnArray;
    }




    var $ID;
    var $Name;
    var $Description;
    var $StartDate;
    var $StopDate;
}

?>