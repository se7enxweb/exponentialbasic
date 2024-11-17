<?php
// 
// $Id: ezvote.php 6225 2001-07-20 11:22:30Z jakobn $
//
// Definition of eZVote class
//
// Created on: <19-Sep-2000 17:37:53 ce>
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

//!! eZPoll
//! The eZVote class handles the options for votes.
/*!
  The eZVote class handles vote options. The class has function for storing and fetching from
  the database.

  Example code:
  \code
  
  
  \endcode
  \sa eZVoteChoice eZVote
*/

include_once( "classes/ezdb.php" );

class eZVote
{
    /*!
      Constructor a new eZVote object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function __construct( $id=-1 )
    {
        $this->UserID = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }
    /*!
      Stores a eZVote object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $db->lock( "eZPoll_Vote" );
        $nextID = $db->nextID( "eZPoll_Vote", "ID" );        
        
        $res = $db->query( "INSERT INTO eZPoll_Vote
        ( ID, VotingIP, PollID, ChoiceID, UserID )
        VALUES
        ( '$nextID',
          '$this->IP',
          '$this->PollID',
          '$this->ChoiceID',
          '$this->UserID' ) ");
        
		$this->ID = $nextID;

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the vote object from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1 )
        {
            $db->array_query( $vote_array, "SELECT * FROM eZPoll_Vote" );

            if ( count( $vote_array ) > 1 )
            {
                die( "Error: Vote's with the same ID was found in the database." );
            }
            else if( count( $vote_array ) == 1 )
            {
                $this->ID = $vote_array[0][$db->fieldName("ID")];
                $this->IP = $vote_array[0][$db->fieldName("VotingIP")];
                $this->PollID = $vote_array[0][$db->fieldName("PollID")];
                $this->ChoiceID = $vote_array[0][$db->fieldName("ChoiceID")];
                $this->UserID = $vote_array[0][$db->fieldName("UserID")];
            }

        }
    }

    /*!
      Fetches the vote id from the database. And returns a array of eZVote objects.
    */
    function getAll( $id )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $vote_array = array();

        $this->Database->array_query( $vote_array, "SELECT ID FROM eZPoll_Vote WHERE PollID='$id'" );

        for ( $i=0; $i<count( $vote_array ); $i++ )
        {
            $return_array[$i] = new eZVote( $vote_array[$i][$db->fieldName("ID")], 0 );
        }
    }
    
    /*!
      Returns the pollid of the vote.
    */
    function pollID()
    {
        return $this->PollID;
    }

    /*!
      Returns the pollid of the vote.
    */
    function choiceID()
    {
        return $this->choiceID;
    }

       
    /*!
      Returns the IP of the vote.
    */
    function votingIP()
    {
        return $this->IP;
    }

    /*!
      Returns the ID of the vote.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the ID of the vote.
    */
    function userID()
    {
        return $this->userID;
    }

    
    /*!
      Sets the IP of the vote.
    */
    function setVotingIP( $value )
    {
        $this->IP = $value;
    }

    /*!
      Sets the Pollid of the vote.
    */
    function setPollID( $value )
    {
        $this->PollID = $value;
    }

    /*!
      Sets the Pollid of the vote.
    */
    function setChoiceID( $value )
    {
        $this->ChoiceID = $value;
    }
    
    /*!
      Sets the ChoiceID of the vote.
    */
    function setUserID( $value )
    {
        $this->UserID = $value;
    }

    /*!
      \static
      Check if the user already have voted. If voted, return true.
    */
    function isVoted( $userID, $pollID )
    { 
        $ret = true;

        $vote_array = array();

        $db =& eZDB::globalDatabase();

        $db->array_query( $vote_array, "SELECT * FROM eZPoll_Vote
                                                    WHERE UserID='$userID' AND PollID='$pollID'" );
        if ( count( $vote_array ) == 0 )
        {
            $ret = false;
        }

        return $ret;
    }

    /*!
      \static
      Check if the user already have voted. If voted, return true.
    */
    function ipHasVoted( $IP, $pollID )
    { 
        $ret = true;

        $vote_array = array();

        $db =& eZDB::globalDatabase();

        $db->array_query( $vote_array, "SELECT * FROM eZPoll_Vote
                                                    WHERE VotingIP='$IP' AND PollID='$pollID'" );
        if ( count( $vote_array ) == 0 )
        {
            $ret = false;
        }

        return $ret;
    }

    var $ID;
    var $IP;
    var $PollID;
    var $ChoiceIP;
    var $UserID;
}

?>