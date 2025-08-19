<?php
// 
// $Id: ezbugpriority.php 6409 2001-08-09 14:17:42Z jhe $
//
// Definition of eZBugPriority class
//
// Created on: <28-Nov-2000 20:30:36 bf>
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
//! eZBugPriority handles bug categories.
/*!
  Example code:
  \code
  // include the class
  // include_once( "ezbug/classes/ezbugpriority.php" );

  // create a new class object
  $priority = new eZBugPriority();

  // Set some object values and store them to the database.
  $priority->setName( "Urgent" );
  $priority->store();
  \endcode
  \sa eZBug eZBugModule eZBugCategory
*/

/*!TODO
*/

// include_once( "classes/ezdb.php" );


class eZBugPriority
{
    /*!
      Constructs a new eZBugPriority object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBugPriority object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBug_Priority", "ID" );
            $this->ID = $db->nextID( "eZBug_Priority", "ID" );
            $res = $db->query( "INSERT INTO eZBug_Priority (ID, Name)
                                            VALUES ('$this->ID', '$name')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Priority SET
		                        Name='$name' WHERE ID='$this->ID'" );
        }
        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZBugPriority object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( isset( $this->ID ) )
        {
            // remove all bugs from the database that have this priority.
            $res[] = $db->query( "DELETE FROM eZBug_Bug WHERE PriorityID='$this->ID'" );
            // remove the priority itself. 
            $res[] = $db->query( "DELETE FROM eZBug_Priority WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $priority_array, "SELECT * FROM eZBug_Priority WHERE ID='$id'" );
            if ( count( $priority_array ) > 1 )
            {
                die( "Error: Priority's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $priority_array ) == 1 )
            {
                $this->ID = $priority_array[0][ $db->fieldName( "ID" ) ];
                $this->Name = $priority_array[0][ $db->fieldName( "Name" ) ];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBugPriority objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $priority_array = array();
        
        $db->array_query( $priority_array, "SELECT ID FROM eZBug_Priority ORDER BY Name" );
        
        for ( $i = 0; $i < count( $priority_array ); $i++ )
        {
            $return_array[$i] = new eZBugPriority( $priority_array[$i][ $db->fieldName( "ID" ) ], 0 );
        }
        return $return_array;
    }
    
    /*!
      Returns the object ID to the priority. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the priority.
    */
    function name( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Sets the name of the priority.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    var $ID;
    var $Name;

}

?>