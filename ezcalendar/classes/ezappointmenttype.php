<?php
//
// $Id: ezappointmenttype.php 7443 2001-09-25 08:17:19Z jhe $
//
// Definition of eZAppointmentType class
//
// Created on: <08-Jan-2001 09:47:13 bf>
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

//!! eZCalendar
//! eZAppointmentType handles appointment types.
/*!

*/

/*!TODO
  Implement activeAppointments();
*/

include_once( "classes/ezdb.php" );

class eZAppointmentType
{
    /*!
      Constructs a new eZAppointmentType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZAppointmentType object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZCalendar_AppointmentType" );
            $this->ID = $db->nextID( "eZCalendar_AppointmentType", "ID" );
            $queryText = "INSERT INTO eZCalendar_AppointmentType
                                  (ID, Name, Description, ParentID)
                                  VALUES
                                  ('$this->ID', '$this->Name', '$this->Description', '$this->ParentID')";
            var_dump( $queryText );
            $res[] = $db->query( $queryText );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZCalendar_AppointmentType SET
		                          Name='$this->Name',
                                  Description='$this->Description',
                                  ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZAppointmentGroup object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( isSet( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZCalendar_AppointmentType WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $AppointmentType_array, "SELECT * FROM eZCalendar_AppointmentType WHERE ID='$id'" );
            if ( count( $AppointmentType_array ) > 1 )
            {
                die( "Error: AppointmentType's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $AppointmentType_array ) == 1 )
            {
                $this->ID = $AppointmentType_array[0][ $db->fieldName( "ID" ) ];
                $this->Name = $AppointmentType_array[0][ $db->fieldName( "Name" ) ];
                $this->Description = $AppointmentType_array[0][ $db->fieldName( "Description" ) ];
                $this->ParentID = $AppointmentType_array[0][ $db->fieldName( "ParentID" ) ];
                $this->ExcludeFromSearch = $AppointmentType_array[0][ $db->fieldName( "ExcludeFromSearch" ) ];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZAppointmentType objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $AppointmentType_array = array();

        $db->array_query( $AppointmentType_array, "SELECT ID FROM eZCalendar_AppointmentType ORDER BY Name" );

        for ( $i = 0; $i < count( $AppointmentType_array ); $i++ )
        {
            $return_array[$i] = new eZAppointmentType( $AppointmentType_array[$i][ $db->fieldName( "ID" ) ], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the types with the AppointmentType given as parameter as parent.

      If $showAll is set to true every AppointmentType is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZAppointmentType objects.
    */
    function &getByParent( $parent, $showAll=false, $sortby="name" )
    {
        if ( is_a( $parent, "eZAppointmentType" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $appointmenttype_array = array();

            $parentID = $parent->id();

            if ( $showAll == true )
            {
                $db->array_query( $appointmenttype_array, "SELECT ID, Name FROM eZCalendar_AppointmentType
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }
            else
            {
                $db->array_query( $appointmenttype_array, "SELECT ID, Name FROM eZCalendar_AppointmentType
                                          WHERE ParentID='$parentID' AND ExcludeFromSearch='false'
                                          ORDER BY Name" );
            }

            for ( $i = 0; $i < count( $appointmenttype_array ); $i++ )
            {
                $return_array[$i] = new eZAppointmentType( $appointmenttype_array[$i][ $db->fieldName( "ID" ) ], 0 );
            }
            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $AppointmentTypeID=0 )
    {
        if ( $AppointmentTypeID == 0 )
        {
            $AppointmentTypeID = $this->ID;
        }

        $AppointmentType = new eZAppointmentType( $AppointmentTypeID );

        $path = array();

        $parent = $AppointmentType->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $AppointmentType->name() );
        }

        if ( $AppointmentTypeID != 0 )
            array_push( $path, array( $AppointmentType->id(), $AppointmentType->name() ) );

        return $path;
    }

    /*!
      Returns the categories sorted as a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {
        $AppointmentType = new eZAppointmentType( $parentID );

        $AppointmentTypeList =& $AppointmentType->getByParent( $AppointmentType, true );

        $tree = array();
        $level++;
        foreach ( $AppointmentTypeList as $AppointmentType )
        {
            array_push( $tree, array( $return_array[] = new eZAppointmentType( $AppointmentType->id() ), $level ) );

            if ( $AppointmentType != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $AppointmentType->id(), $level ) );
            }
        }

        return $tree;
    }

    /*!
      Returns the object ID to the AppointmentType. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the AppointmentType.
    */
    function name( $htmlchars=true )
    {
        if ( $htmlchars == true )
        {
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the group description.
    */
    function description( $htmlchars=true )
    {
        if ( $htmlchars == true )
        {
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }

    /*!
      Returns the parentID, which is 0 if there is no parent.
    */
    function parentID()
    {
        return $this->ParentID;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZAppointmentType( $this->ParentID );
       }
       else
       {
           return 0;
       }
    }

    /*!
      Sets the name of the AppointmentType.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the AppointmentType.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent AppointmentType.
    */
    function setParent( $value )
    {
        if ( is_a( $value, "eZAppointmentType" ) )
        {
            $this->ParentID = $value->id();
        }
        else if ( is_numeric( $value ) )
        {
            $this->ParentID = $value;
        }
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $ExcludeFromSearch;

}

?>