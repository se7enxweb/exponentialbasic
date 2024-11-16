<?php
// 
// $Id: ezbugstatus.php 6409 2001-08-09 14:17:42Z jhe $
//
// Definition of eZBugStatus class
//
// Created on: <28-Nov-2000 20:55:02 bf>
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

//!! eZBug
//! eZBugStatus handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugstatus.php" );

  // create a new class object
  $status = new eZBugStatus();

  // Set some object values and store them to the database.
  $status->setName( "Done" );
  $status->store();
  \endcode
  \sa eZBug eZBugModule eZBugCategory
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZBugStatus
{
    /*!
      Constructs a new eZBugStatus object.

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
      Stores a eZBugStatus object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        $name = $db->escapeString( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZBug_Status", "ID" );
			$this->ID = $db->nextID( "eZBug_Status", "ID" );
            $res = $db->query( "INSERT INTO eZBug_Status (ID, Name)
		                        VALUES ('$this->ID', '$name')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Status SET
		                        Name='$name' WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZBugStatus object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        
        if ( isSet( $this->ID ) )
        {
            $db->begin();
            // remove all bugs that have this status
            $res[] = $db->query( "DELETE FROM eZBug_Bug WHERE StatusID='$this->ID'" );
            // remove the actual status
            $res[] = $db->query( "DELETE FROM eZBug_Status WHERE ID='$this->ID'" );
            if ( in_array( false, $res ) )
                $db->rollback();
            else
                $db->commit();
        }
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
            $db->array_query( $status_array, "SELECT * FROM eZBug_Status WHERE ID='$id'" );
            if ( count( $status_array ) > 1 )
            {
                die( "Error: Status's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $status_array ) == 1 )
            {
                $this->ID = $status_array[0][ $db->fieldName( "ID" ) ];
                $this->Name = $status_array[0][ $db->fieldName( "Name" ) ];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBugStatus objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $status_array = array();
        
        $db->array_query( $status_array, "SELECT ID FROM eZBug_Status ORDER BY Name" );
        
        for ( $i = 0; $i < count( $status_array ); $i++ )
        {
            $return_array[$i] = new eZBugStatus( $status_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object ID to the status. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the status.
    */
    function name( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Sets the name of the status.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    var $ID;
    var $Name;

}

?>