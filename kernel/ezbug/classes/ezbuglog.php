<?php
//
// $Id: ezbuglog.php 6409 2001-08-09 14:17:42Z jhe $
//
// Definition of eZBugLog class
//
// Created on: <28-Nov-2000 21:44:41 bf>
//
// This source file is part of Exponential Basic, publishing software.
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

//!! eZBug
//! eZBug handles bug repports.
/*!
  \sa eZBug eZBugCategory eZBugModule eZBugPriority
*/

/*!TODO
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezuser.php" );

class eZBugLog
{
    /*!
      Constructs a new eZBugLog object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->get( $id );
        }
    }

    /*!
      Stores a eZBuglog object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $description = $db->escapeString( $this->Description );

        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBug_Log" );
            $timestamp = (new eZDateTime())->timeStamp( true );
			$this->ID = $db->nextID( "eZBug_Log", "ID" );
            $res = $db->query( "INSERT INTO eZBug_Log
                                (ID, Description, BugID, UserID, Created)
                                VALUES
                                ('$this->ID','$description','$this->BugID','$this->UserID', '$timestamp')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Log SET
                                Description='$description',
                                BugID='$this->BugID',
                                UserID='$this->UserID'
                                WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZBugLog object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZBug_Log WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = "" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $module_array, "SELECT * FROM eZBug_Log WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: BugLogs with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][$db->fieldName( "ID" )];
                $this->Description = $module_array[0][$db->fieldName( "Description" )];
                $this->UserID = $module_array[0][$db->fieldName( "UserID" )];
                $this->BugID = $module_array[0][$db->fieldName( "BugID" )];
                $this->Created = $module_array[0][$db->fieldName( "Created" )];
            }
        }
    }

    /*!
      Returns all the bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        $db->array_query( $module_array, "SELECT ID FROM eZBug_Log ORDER BY Created" );

        for ( $i = 0; $i < count( $module_array ); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns all the bugs found in the database which is assigned to
      the bug given as argument.

      The bugs are returned as an array of eZBug objects.
    */
    static public function getByBug( $bug )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        if ( is_a( $bug, "eZBug" ) )
        {
            $bugID = $bug->id();
            $db->array_query( $module_array, "SELECT ID FROM eZBug_Log
                                              WHERE BugID='$bugID'
                                              ORDER BY Created" );

            for ( $i = 0; $i < count( $module_array ); $i++ )
            {
                $return_array[$i] = new eZBugLog( $module_array[$i][$db->fieldName( "ID" )] );
            }
        }
        return $return_array;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }

    /*!
      Returns the creation time of the bug log message.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->Created );
       return $dateTime;
    }

    /*!
      Returns the user as a eZUser object.
    */
    function &user()
    {
       $user = new eZUser( $this->UserID );
       return $user;
    }

    /*!
      Returns the bug as a eZBug object.
    */
    function &bug()
    {
       $bug = new eZBug( $this->BugID );
       return $bug;
    }

    /*!
      Sets the description of the module.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the user who made the change.
    */
    function setUser( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Sets the bug the change belongs to.
    */
    function setBug( $bug )
    {
       if ( is_a( $bug, "eZBug" ) )
       {
           $this->BugID = $bug->id();
       }
    }

    var $ID;
    var $Description;
    var $IsHandled;
    var $Created;
    var $UserID;
    var $BugID;
}

?>