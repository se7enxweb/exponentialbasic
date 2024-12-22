<?php
//
// $Id: ezquizalternative.php 8687 2001-12-06 10:19:29Z jhe $
//
// eZQuizAlternative class
//
// Created on: <22-May-2001 16:22:08 ce>
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
//! eZQuizAlternative documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "classes/ezdate.php" );

class eZQuizAlternative
{

    /*!
      Constructs a new eZQuizAlternative object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->Name = "";
            $this->IsCorrect = 0;
        }
    }

    /*!
      Stores a eZQuizAlternative object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name =& $db->escapeString( $this->Name );
        $questionID = $this->Question->id();


        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZQuiz_Alternative" );
            $this->ID = $db->nextID( "eZQuiz_Alternative", "ID" );
            $res[] = $db->query( "INSERT INTO eZQuiz_Alternative
                                  (ID, Name, QuestionID, IsCorrect)
                                  VALUES
                                  ('$this->ID', '$name', '$questionID', '$this->IsCorrect')" );
            $db->unlock();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZQuiz_Alternative SET
                                     Name='$name',
                                     QuestionID='$questionID',
                                     IsCorrect='$this->IsCorrect'
                                     WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZQuizAlternative object from the database.
    */
    function delete( $catID = -1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

//        $answers =& $this->answers();

        if ( is_array ( $answers ) )
        {
            foreach ( $answers as $answer )
            {
                $answer->delete();
            }
        }

        $res[] = $db->query( "DELETE FROM eZQuiz_Alternative WHERE ID='$catID'" );
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

        if( $id == -1 )
        {
            $id = $this->ID;
        }

        if ( $id != "" )
        {
            $db->array_query( $alternativeArray, "SELECT * FROM eZQuiz_Alternative WHERE ID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );

            if ( count( $alternativeArray ) == 1 )
            {
                $this->fill( $alternativeArray[0] );
                $ret = true;
            }
            elseif ( count( $alternativeArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$alternativeArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $alternativeArray[$db->fieldName( "ID" )];
        $this->Name =& $alternativeArray[$db->fieldName( "Name" )];
        $this->IsCorrect =& $alternativeArray[$db->fieldName( "IsCorrect" )];
        $this->Question = new eZQuizQuestion( $alternativeArray[$db->fieldName( "QuestionID" )] );
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZQuizAlternative objects.
    */
    function getAll( $offset = 0, $limit = 20)
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $alternativeArray = array();

        $db->array_query( $alternativeArray, "SELECT ID FROM eZQuiz_Alternative" );

        for ( $i = 0; $i < count( $alternativeArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizAlternative( $alternativeArray[$i][$db->fieldName( "ID" )] );
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

        $db->query_single( $result, "SELECT COUNT(ID) as Count FROM eZQuiz_Alternative" );
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
    function isCorrect()
    {
        if ( $this->IsCorrect == 1 )
            return true;
        elseif ( $this->IsCorrect == 0 )
            return false;
    }

    /*!
      Returns the question object of the game.
    */
    function question()
    {
        return $this->Question;
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
    function setIsCorrect( $value )
    {
        if ( $value == true )
            $this->IsCorrect = 1;
        else
            $this->IsCorrect = 0;
    }

    /*!
        Gets the question id
     */
    function questionID()
    {
        $ret = 0;

        if ( is_a( $this->Question, "eZQuizQuestion" ) )
        {
            $ret = $this->Question->id();
        }

        return $ret;
   }

    /*!
      Returns the name of the game.
    */
    function setQuestion( &$question )
    {
        if ( is_a( $question, "eZQuizQuestion" ) )
            $this->Question = $question;
    }

    /*!
      Returns every answers to this quiz alternative
      The alternatives is returned as an array of eZQuizAnswer objects.
    */
    function answers()
    {
        $returnArray = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $questionArray, "SELECT ID FROM eZQuiz_Answer WHERE AlternativeID='$this->ID'" );

        for ( $i = 0; $i < count( $questionArray ); $i++ )
        {
            $returnArray[$i] = new eZQuizAnswer( $questionArray[$i][$db->fieldName( "ID" )], true );
        }
        return $returnArray;
    }

    var $ID;
    var $Name;
    var $IsCorrect;
    var $Question;
}

?>