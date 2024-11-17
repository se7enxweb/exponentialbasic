<?php
// 
// $Id: ezlinktype.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Definition of eZLinkType class
//
// Created on: <29-Jun-2001 11:13:12 jhe>
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

include_once( "classes/ezdb.php" );
include_once( "ezlink/classes/ezlinkattribute.php" );

class eZLinkType
{
    /*!
      Constructs a new eZLinkType object. Retrieves the data from the database
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
      Stores a eZLinkType object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLink_Type" );

            $this->ID = $db->nextID( "eZLink_Type", "ID" );
            
            $res = $db->query( "INSERT INTO eZLink_Type (ID,Name) VALUES
     		                         ('$this->ID','$this->Name')" );
            $db->unlock();
        }
        else
        {
            $db->query( "UPDATE eZLink_Type SET
    		                         Name='$this->Name' WHERE ID='$this->ID'" );
        }

    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Fetches the link type object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != -1  )
        {
            $db->array_query( $type_array, "SELECT * FROM eZLink_Type WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Link types with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName("ID")];
                $this->Name =& $type_array[0][$db->fieldName("Name")];
                $ret = true;
            }
        }
        
        return $ret;
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $type_array = array();
        
        $db->array_query( $type_array, "SELECT ID FROM eZLink_Type ORDER BY Name" );
        
        for ( $i=0; $i<count($type_array); $i++ )
        { 
            $return_array[$i] = new eZLinkType( $type_array[$i][$db->fieldName("ID")], 0 );
        } 
        
        return $return_array;
    }

    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        // delete all attributes and values
        $attributes = $this->attributes();
        foreach ( $attributes as $attribute )
        {
            $attribute->delete();
        }

        $db->query( "DELETE FROM eZLink_TypeLink WHERE TypeID='$this->ID'" );
        $db->query( "DELETE FROM eZLink_Type WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the option.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the option description.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Sets the name of the option.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the option.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Returns an array of eZLinkAttribute objects which
      are associated with the current link type.
    */
    function attributes( )
    {
        $db =& eZDB::globalDatabase();
       
        $return_array = array();
        $attribute_array = array();
        
        $db->array_query( $attribute_array, "SELECT ID
                                                      FROM eZLink_Attribute
                                                      WHERE TypeID='$this->ID' ORDER BY Placement" );
        
        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $return_array[$i] = new eZLinkAttribute( $attribute_array[$i][$db->fieldName("ID")], false );
        }
        
        return $return_array;
    }

    var $ID;
    var $Name;

}

?>
