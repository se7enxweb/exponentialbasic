<?php
//
// $Id: ezpoll.php 8744 2001-12-12 15:26:03Z br $
//
// Definition of eZPoll class
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
//! The eZPoll class handles the options for polls.
/*!
  The eZPoll class handles poll options. The class has function for storing and fetching from
  the database.

  Example code:
  \code

  // Create a new poll object.
  $poll = new eZPoll();

  $poll->setName( "Politics" );
  $poll->setDescription(  "What do you think about Carl I. Hagen" );

  // Stores the poll information into the database.
  $poll->store();

  // Fetch all the poll information from the database.
  $poll->getAll();

  // Print out all the polls
  foreach( $pollArray as pollItem )
  {
      print( "Poll: " . $pollItem->name() . "<br>" );
  }

  \endcode
  \sa eZPollChoice eZVote
*/

include_once( "classes/ezdb.php" );
include_once( "ezforum/classes/ezforum.php" );

class eZPoll
{
    /*!
      Constructor a new eZPoll object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function __construct( $id=-1 )
    {
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

        $dbError = false;
        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZPoll_Poll" );

            $nextID = $db->nextID( "eZPoll_Poll", "ID" );

            $res = $db->query( "INSERT INTO eZPoll_Poll
            ( ID, Name, Description, IsEnabled, ShowResult, Anonymous, IsClosed )
            VALUES
            ( '$nextID',
              '$name',
              '$description',
              '$this->IsEnabled',
              '$this->ShowResult',
              '$this->Anonymous',
              '$this->IsClosed' ) ");

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZPoll_Poll SET
                                 Name='$name',
                                 Description='$description',
                                 IsEnabled='$this->IsEnabled',
                                 ShowResult='$this->ShowResult',
                                 Anonymous='$this->Anonymous',
                                 IsClosed='$this->IsClosed' WHERE ID='$this->ID'" );

        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZPol object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZPoll_Vote WHERE PollID='$this->ID'" );
            $db->query( "DELETE FROM eZPoll_PollChoice WHERE PollID='$this->ID'" );
            $db->query( "DELETE FROM eZPoll_Poll WHERE ID='$this->ID'" );
            $db->query( "DELETE FROM eZPoll_PollForumLink WHERE PollID='$this->ID'" );
        }
        return true;
    }

    /*!
      Fetches the poll object from the database.
    */
    function get( $id=-1 )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        if ( $id != -1 )
        {
            $db->array_query( $poll_array, "SELECT * FROM eZPoll_Poll WHERE ID='$id'" );

            if ( count( $poll_array ) > 1 )
            {
                die( "Error: Poll's with the same ID was found in the database." );
            }

            else if ( count( $poll_array ) == 1 )
            {
                $ret = true;
                $this->ID = $poll_array[0][$db->fieldName("ID")];
                $this->Name = $poll_array[0][$db->fieldName("Name")];
                $this->Description = $poll_array[0][$db->fieldName("Description")];
                $this->Anonymous = $poll_array[0][$db->fieldName("Anonymous")];
                $this->IsEnabled = $poll_array[0][$db->fieldName("IsEnabled")];
                $this->IsClosed = $poll_array[0][$db->fieldName("IsClosed")];
                $this->ShowResult =  $poll_array[0][$db->fieldName("ShowResult")];
            }
        }
        return $ret;
    }

    /*!
      Fetches the poll id from the database. And returns a array of eZPoll objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $poll_array = array();

        $db->array_query( $poll_array, "SELECT ID, Name FROM eZPoll_Poll ORDER BY Name" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPoll( $poll_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Fetches the poll id from the database where active=true. And returns a array of eZPoll objects.
    */
    function getAllActive()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $poll_array = array();

        $db->array_query( $poll_array, "SELECT ID,Name FROM eZPoll_Poll WHERE IsEnabled='1' ORDER BY Name" );

        for ( $i=0; $i<count( $poll_array ); $i++ )
        {
            $return_array[$i] = new eZPoll( $poll_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }


    /*!
      Returns the id of the poll.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the poll.
    */
    function name( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the poll description.
    */
    function description( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Description );
        else
            return $this->Description;
    }

    /*!
      Returns the isEnabled.
    */
    function isEnabled()
    {
        if ( $this->IsEnabled == 1 )
            return true;
        else
            return false;
    }

    /*!
      Returns the isClosed.
    */
    function isClosed()
    {
        if ( $this->IsClosed == 1 )
            return true;
        else
            return false;
    }

    /*!
      Returns the ShowResult.
    */
    function showResult()
    {
        if ( $this->ShowResult == 1 )
            return true;
        else
            return false;
    }

    /*!
      Returns the Anonymous.
    */
    function anonymous()
    {
        if ( $this->Anonymous == 1 )
            return true;
        else
            return false;
    }


    /*!
      Returns the main poll as a eZPoll object.

      False is returned if no poll is set as main poll.
    */
    function mainPoll(  )
    {
        $db =& eZDB::globalDatabase();

        // sets the current poll as main poll
        $db->array_query( $poll_array, "SELECT PollID FROM eZPoll_MainPoll" );

        $ret = false;
        if ( count( $poll_array ) == 1 )
        {
            $ret = new eZPoll( $poll_array[0][$db->fieldName("PollID")] );
        }
        return $ret;
    }

    /*!
      Sets the name of the poll.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the poll.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the isenabled of the poll.
    */
    function setIsEnabled( $value )
    {
        if ( $value )
            $this->IsEnabled = 1;
        else
            $this->IsEnabled = 0;
    }

    /*!
      Sets the isclosed of the poll.
    */
    function setIsClosed( $value )
    {
        if ( $value  )
            $this->IsClosed = 1;
        else
            $this->IsClosed = 0;
    }

    /*!
      Sets the showresult of the poll.
    */
    function setShowResult( $value )
    {
        if ( $value )
            $this->ShowResult = 1;
        else
            $this->ShowResult = 0;
    }

    /*!
      Sets the anonymous of the poll.
    */
    function setAnonymous( $value )
    {
        if ( $value )
            $this->Anonymous = 1;
        else
            $this->Anonymous = 0;
    }

    /*!
      Fetches the total number of votes for the poll.
    */
    function totalVotes( )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $votecount, "SELECT COUNT(*) AS NUMBER FROM eZPoll_Vote WHERE PollID='$this->ID'" );

        return (int)$votecount[0][$db->fieldName("NUMBER")];
    }

    /*!
      Sets the active poll to the current poll.
    */
    function setMainPoll( $poll )
    {
        if ( is_a( $poll, "eZPoll" ) )
        {
            $db =& eZDB::globalDatabase();

            // delete old main poll
            $db->query( "DELETE FROM eZPoll_MainPoll" );

            // sets the current poll as main poll
            $db->query( "INSERT INTO eZPoll_MainPoll ( ID, PollID ) VALUES ( '1', '$this->ID' )" );
        }
    }


    /*!
      Returns the forum for the poll.
    */
    function &forum()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT ForumID FROM
                                            eZPoll_PollForumLink
                                            WHERE PollID='$this->ID'" );

        $forum = false;
        if ( count( $res ) == 1 )
        {
            $forum = new eZForum( $res[0][$db->fieldName("ForumID")] );
        }
        else
        {
            $forum = new eZForum();
            $forum->setName( $db->escapeString( $this->Name ) );
            $forum->store();

            $forumID = $forum->id();

            $db->begin( );

            $db->lock( "eZPoll_PollForumLink" );

            $nextID = $db->nextID( "eZPoll_PollForumLink", "ID" );

            $res = $db->query( "INSERT INTO eZPoll_PollForumLink
                                ( ID, PollID, ForumID )
                                VALUES
                                ( '$nextID', '$this->ID', '$forumID' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();


            $forum = new eZForum( $forumID );
        }

        return $forum;
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsEnabled;
    var $IsClosed;
    var $ShowResult;
    var $Anonymous;

}

?>
