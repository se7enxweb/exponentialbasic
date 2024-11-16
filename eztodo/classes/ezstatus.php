<?php
//
// $Id: ezstatus.php 7992 2001-10-22 09:22:31Z  $
//
// Definition of eZStatus class
//
// Created on: <28-Mar-2001 21:00:00 ce>
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

//!! eZTodo
//! The eZStatus handles the ToDo status information. (Done, not done)
/*!
  Handles the to do status information stored in the database. All the todo's have this status.
*/

class eZStatus
{
    /*!
      eZStatus Constructor.
    */
    function eZStatus( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the status object to the database.
      Returnes the ID to the eZStatus object if the store is a success.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZTodo_Status" );
			$this->ID = $db->nextID( "eZTodo_Status", "ID" );
            $res[] = $db->query( "INSERT INTO eZTodo_Status
                                             (ID, Name, Description)
                                             VALUES
                                             ('$this->ID', '$name', '$description')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTodo_Status SET
                                  ID='$this->ID',
                                  Name='$name',
                                  Description='$description'
                                  WHERE ID='$this->ID' ");
        }
        eZDB::finish( $res, $db );
        return true;
    }
        

    /*!
      Deletes the status object in the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZTodo_Status WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Gets a status object from the database, where ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $status_array, "SELECT * FROM eZTodo_Status WHERE ID='$id'" );
            if ( count( $status_array ) > 1 )
            {
                die( "Error: More then one status with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $status_array ) == 1 )
            {
                $this->ID = $status_array[0][$db->fieldName( "ID" )];
                $this->Name = $status_array[0][$db->fieldName( "Name" )];
                $this->Description = $status_array[0][$db->fieldName( "Description" )];
                $ret = true;
            }
        }
        
        return $ret;
    }

    /*!
      Gets all the status informasjon from the database.
      Returns the array in $status_array ordered by name.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $status_array = 0;

        $return_array = array();
        $status_array = array();

        $db->array_query( $status_array, "SELECT ID FROM eZTodo_Status ORDER by Name" );

        for ( $i = 0; $i < count( $status_array ); $i++ )
        { 
            $return_array[$i] = new eZStatus( $status_array[$i][$db->fieldName( "ID" )], 0 );
        } 
        return $return_array;
    }
    
    /*!
      Tilte of the status.
      Returns the name of the status as a string.
    */ 
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the name of the status.
      The new name of the status is passed as a paramenter ( $value ).
     */
    function setName( $value )
    {        
        $this->Name = $value;
    }

    /*!
      Description of the status.
      Returns the description of the status as a string.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the description of the status.
      The new description of the status is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        $this->Description = $value;
    }
 
    /*!
      Id of the priority.
      Returns the id of the status as a string.
    */
    function id()
    {
        return $this->ID;
    }

    var $ID;
    var $Name;
    var $Description;
}

?>
