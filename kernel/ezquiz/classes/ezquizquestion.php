<?php
//
// $Id: ezquizquestion.php 8687 2001-12-06 10:19:29Z jhe $
//
// eZQuizQuestion class
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
//! eZQuizQuestion documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "classes/ezdate.php" );
// include_once( "ezquiz/classes/ezquizalternative.php" );
// include_once( "ezquiz/classes/ezquizgame.php" );

class eZQuizQuestion
{

    /*!
      Constructs a new eZQuizQuestion object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1, $fetch = true )
    {
        $this->Score = 0;
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
      Stores a eZQuizQuestion object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name =& $db->escapeString( $this->Name );
        $gameID = $this->Game->id();

        $db->query_single( $result, "SELECT MAX(Placement)+1 FROM eZQuiz_Question WHERE GameID='$gameID' " );

        $place = $result[0];
        if ( $result[0] == NULL )
        {
            $place = 1;
        }

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZQuiz_Question" );
			$this->ID = $db->nextID( "eZQuiz_Question", "ID" );
            $queryText = "INSERT INTO eZQuiz_Question
                                  (ID, Name, Score, GameID, Placement)
                                  VALUES
                                  ('$this->ID','$name','$this->Score','$gameID','$place')";
           // var_dump( $queryText );
            $res[] = $db->query( $queryText );
            $db->unlock();

        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZQuiz_Question SET
                                     Name='$name',
                                     Score='$this->Score',
                                     GameID='$gameID'
                                     WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZQuizQuestion object from the database.
    */
    function delete( $catID = -1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $alternatives =& $this->alternatives();
        if ( is_array( $alternatives ) )
        {
            foreach ( $alternatives as $alternative )
            {
                $alternative->delete();
            }
        }

        $res[] = $db->query( "DELETE FROM eZQuiz_Question WHERE ID='$this->ID'" );
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
            $db->array_query( $questionArray, "SELECT * FROM eZQuiz_Question WHERE ID='$id'",
                              0, 1 );
            if ( count( $questionArray ) == 1 )
            {
                $this->fill( $questionArray[0] );
                $ret = true;
            }
            elseif ( count( $questionArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$questionArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $questionArray[$db->fieldName( "ID" )];
        $this->Name =& $questionArray[$db->fieldName( "Name" )];
        $this->Description =& $questionArray[$db->fieldName( "Description" )];
        $this->Game = new eZQuizGame( $questionArray[$db->fieldName( "GameID" )] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizQuestion objects.
    */
    function getAll( $offset = 0, $limit = 20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $questionArray = array();

        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Question
                                           ORDER BY StartDate DESC",
                                           array( "Offset" => $offset, "Limit" => $limit ) );

        for ( $i = 0; $i < count( $questionArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizQuestion( $questionArray[$i][$db->fieldName( "ID" )] );
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

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZQuiz_Question" );
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
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the score of the game.
    */
    function score()
    {
        return $this->Score;
    }

    /*!
      Returns the name of the game.
    */
    function game()
    {
        return $this->Game;
    }

    /*!
      Sets the login.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the score.
    */
    function setScore( &$value )
    {
       $this->Score = $value;
    }

    /*!
      Returns the name of the game.
    */
    function setGame( &$game )
    {
        if ( is_a( $game, "eZQuizGame" ) )
            $this->Game = $game;
    }

    /*!
        Returns true if the submitted alternative is part of the questions alternatives
     */
    function isAlternative( &$alternative )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        if ( is_a( $alternative, "eZQuizAlternative" ) )
        {
            $alternativeID = $alternative->id();
            $questionID = $this->ID;

            $db->query_single( $result, "SELECT ID
                                     FROM eZQuiz_Alternative WHERE QuestionID='$questionID' AND ID='$alternativeID'" );

            if ( is_numeric( $result[$db->fieldName( "ID" )] ) )
            {
                $ret = true;
            }
        }

        return $ret;
     }

    /*!
      Returns every alternative to this quiz question
      The alternatives is returned as an array of eZQuizAlternative objects.
    */
    function alternatives()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Alternative WHERE QuestionID='$this->ID' ORDER BY ID" );

        for ( $i = 0; $i < count( $questionArray ); $i++ )
        {
           $returnArray[$i] = new eZQuizAlternative( $questionArray[$i][$db->fieldName( "ID" )], true );
        }
        return $returnArray;
    }

    /*!
        This function returns the count of all the alternatives for a question. Returns
        the number of alternatives of false if there are none.
     */
    function countAlternatives()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $result, "SELECT count( ID ) AS COUNT FROM eZQuiz_Alternative WHERE QuestionID='$this->ID'" );

        $ret = false;

        if ( is_numeric( $result[$db->fieldName( "COUNT" )] ) )
        {
            $ret = $result[$db->fieldName( "COUNT" )];
        }

        return $ret;
    }

    /*!
        This function returns the count of all the alternatives for a question which are correct. It returns
        false if there are no correct alternatives.
     */
    function countCorrectAlternatives()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $result, "SELECT count( ID ) AS COUNT FROM eZQuiz_Alternative WHERE QuestionID='$this->ID' AND IsCorrect=1" );

        $ret = false;

        if ( is_numeric( $result[$db->fieldName( "COUNT" )] ) )
        {
            $ret = $result[$db->fieldName( "COUNT" )];
        }

        return $ret;
    }

    var $ID;
    var $Name;
    var $Description;
    var $Score;
    var $Game;
}

?>