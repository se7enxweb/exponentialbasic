<?php
// $Id: ezpollchoice.php 8236 2001-11-01 21:32:06Z bf $
//
// Definition of eZPollChoice class
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
//! The eZPollChoice class handles the options for polls.
/*!
  The eZPollChoice class handles poll options. The class has function for storing and fetching from
  the database.

  Example code:
  \code
  
  
  \endcode
  \sa eZPoll eZVote
*/

// include_once( "classes/ezdb.php" );

class eZPollChoice
{
        /*!
      Constructor a new eZPoll object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function __construct( $id=-1 )
    {
        $this->Offset = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZPoll object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $name = $db->escapeString( $this->Name );
        
        if ( !isset ( $this->ID ) )
        {
            $db->lock( "eZPoll_PollChoice" );
            $nextID = $db->nextID( "eZPoll_PollChoice", "ID" );
            
            $res = $db->query( "INSERT INTO eZPoll_PollChoice
            ( ID, Name, PollID, Offs ) VALUES
            ( '$nextID',
              '$name',
              '$this->PollID',
              '$this->Offset' ) ");

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZPoll_PollChoice SET
                                 Name='$name',
                                 Offs='$this->Offset' WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZPollChoice object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset ( $this->ID ) )
        {
            $db->query( "DELETE FROM eZPoll_Vote WHERE ChoiceID='$this->ID'" );
            $db->query( "DELETE FROM eZPoll_PollChoice WHERE ID='$this->ID'" );
        }
        return true;
    }
   

    /*!
      Fetches the poll object from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != -1 )
        {
            $db->array_query( $poll_array, "SELECT * FROM eZPoll_PollChoice WHERE ID='$id' ORDER By ID" );

            if ( count( $poll_array ) > 1 )
            {
                die( "Error: Poll's wit the same ID was found in the database." );
            }
            else if ( count( $poll_array ) == 1 )
            {
                $this->ID = $poll_array[0][$db->fieldName("ID")];
                $this->Name = $poll_array[0][$db->fieldName("Name")];
                $this->PollID = $poll_array[0][$db->fieldName("PollID")];
                $this->Offset = $poll_array[0][$db->fieldName("Offs")];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Fetches the poll id from the database. And returns a array of eZPoll objects.
    */
    function getAll( $ID )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $poll_array = array();

        $db->array_query( $poll_array, "SELECT ID FROM eZPoll_PollChoice WHERE PollID='$ID' ORDER By ID" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPollChoice( $poll_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the name of the pollchoice.
    */
    function name( $html = true )
    {
        if( $html && !is_null( $this->Name ) )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

        /*!
      Returns the name of the pollchoice.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the pollchoice.
    */
    function offset()
    {
        return $this->Offset;
    }

    
    /*!
      Returns the PollID of the Pollchoice.
    */
    function pollID()
    {
        return $this->PollID;
    }

    /*!
      Sets the name of the poll.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the name of the poll.
    */
    function setOffset( $value )
    {
        $this->Offset = $value;
    }
    
    /*!
      Sets the name of the poll.
    */
    function setPollID( $value )
    {
        $this->PollID = $value;
    }

    /*!
      Fetches the number of votes for the current coice.
    */
    function voteCount( )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $votecount, "SELECT COUNT(*) AS NUMBER FROM eZPoll_Vote WHERE ChoiceID='$this->ID'" );
        
        return (int)$votecount[0][$db->fieldName("NUMBER")];
    }
    
    var $ID;
    var $Name;
    var $PollID;
    var $Offset;
}
?>