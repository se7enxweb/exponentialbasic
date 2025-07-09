<?php
// 
// $Id: ezformelementfixedvalue.php 7715 2001-10-09 09:04:27Z ce $
//
// ezformelementtype class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! eZForm
//! ezformelementtype documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "ezform/classes/ezform.php" );

class eZFormElementFixedValue
{

    /*!
      Constructs a new eZFormElementFixedValues object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZFormElementFixedValues object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $value =& $db->escapeString( $this->Value );
        
        if ( empty( $this->ID ) )
        {
            $db->lock( "eZForm_FormElementFixedValues" );
            $nextID = $db->nextID( "eZForm_FormElementFixedValues", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormElementFixedValues
                         ( ID, Value )
                         VALUES
                         ( '$nextID', '$value' )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res[] = $db->query( "UPDATE eZForm_FormElementFixedValues SET Value='$value' WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZFormElementFixedValues object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();


        $res[] = $db->query( "DELETE FROM eZForm_FormElementFixedValueLink WHERE FixedValueID=$formID" );
        $res[] = $db->query( "DELETE FROM eZForm_FormElementFixedValues WHERE ID=$formID" );
        eZDB::finish( $res, $db );
        
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "-1" )
        {
            $db->array_query( $formArray, "SELECT * FROM eZForm_FormElementFixedValues WHERE ID='$id'",
                              0, 1 );
                              
            if( count( $formArray ) == 1 )
            {
                $this->fill( $formArray[0] );
                $ret = true;
            }
            elseif( count( $formArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$formArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $formArray["ID"];
        $this->Value = $formArray["Value"];
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZFormElementFixedValues objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_FormElementFixedValues
                                           ORDER BY Value DESC
                                           " );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElementFixedValue( $formArray[$i][$db->fieldValue( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZForm_FormElementFixedValues" );
        $ret = $result[$db->fieldValue( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the value of the object.
    */
    function &value()
    {
        return htmlspecialchars( $this->Value );
    }

    /*!
      Sets the value of the object.
    */
    function setValue( &$value )
    {
       $this->Value = $value;
    }
    var $ID;
    var $Value;
}

?>