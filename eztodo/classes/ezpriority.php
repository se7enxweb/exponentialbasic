<?php
//
// $Id: ezpriority.php 7992 2001-10-22 09:22:31Z  $
//
// Definition of eZPriority class
//
// Created on: <04-Sep-2000 16:53:15 ce>
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
//! The eZPriority handles the priority informasjon.
/*!
  Handles the priority informasjon stored in the database. All the todo's have a priority status.
*/
class eZPriority
{
    //! eZPriority
    /*!
      eZPriority Constructor.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    //! store
    /*!
      Stores the priority object to the database.
      Returnes the ID to the eZPriority object if the store is a success.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( !isSet ( $this->ID ) )
        {
            $db->lock( "eZTodo_Priority" );
			$this->ID = $db->nextID( "eZTodo_Priority", "ID" );
            $res[] = $db->query( "INSERT INTO eZTodo_Priority
                                  (ID, Name)
                                  VALUES
                                  ('$this->ID', '$name')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTodo_Priority SET
                                     ID='$this->ID',
                                     Name='$name'
                                     WHERE ID='$this->ID' ");
        }
        eZDB::finish( $res, $db );
        return $this->ID;
    }

    //! delete
    /*!
      Deletes the priority object in the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZTodo_Priority WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    //! get
    /*!
      Gets a priority object from the database, where ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $priority_array, "SELECT * FROM eZTodo_Priority WHERE ID='$id'" );
            if ( count( $priority_array ) > 1 )
            {
                die( "Error: Priority's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $priority_array ) == 1 )
            {
                $this->ID = $priority_array[0][$db->fieldName( "ID" )];
                $this->Name = $priority_array[0][$db->fieldName( "Name" )];

                $ret = true;
            }
        }
        return $ret;
    }

    //! getAll
    /*!
      Gets all the priority informasjon from the database.
      Returns the array in $priority_array ordered by name.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        $priority_array = 0;

        $return_array = array();
        $priority_array = array();

        $db->array_query( $priority_array, "SELECT ID FROM eZTodo_Priority ORDER BY Name" );
        
        for ( $i = 0; $i < count( $priority_array ); $i++ )
        {
            $return_array[$i] = new eZPriority( $priority_array[$i][$db->fieldName( "ID" )], 0 );
        } 
        return $return_array;
    }


    //! name
    /*!
      Tilte of the priority.
      Returns the name of the priority as a string.
    */
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    //! setName
    /*!
      Sets the name of the priority.
      The new name of the priority is passed as a paramenter ( $value ).
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    //! id
    /*!
      Id of the priority.
      Returns the id of the priority as a string.
    */
    function id()
    {
        return $this->ID;
    }
    
    var $ID;
    var $Name;

}

?>