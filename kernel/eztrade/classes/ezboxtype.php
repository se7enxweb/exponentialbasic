<?php
// 
// $Id: ezboxtype.php,v 1.8 2001/10/02 07:59:01 ce Exp $
//
// Definition of eZBoxType class
//
// Created on: <11-Jan-2004 rbs>
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


//!! eZTrade
//! This class handles the different Box Types.
/*!
  \sa eZProduct
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezproducttype.php" );

class eZBoxType
{
    /*!
      Constructs a new object.
    */
    function eZBoxType( $id=-1 )
    {
        $this->BoxValue = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores/updates the Box Type in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_BoxType" );
            $nextID = $db->nextID( "eZTrade_BoxType", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_BoxType
                               ( ID,
                                 Name,
		                         Length,
								 Height,
								 Width )
                               VALUES
		                       ( '$nextID',
                                 '$this->Name',
		                         '$this->Length',
								 '$this->Height',
								 '$this->Width' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_BoxType SET
		                         Name='$this->Name',
								 Length='$this->Length',
								 Height='$this->Height',
								 Width='$this->Width' 
								 WHERE ID='$this->ID'" );
        }
        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Fetches the Box Type from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $box_array, "SELECT * FROM eZTrade_BoxType WHERE ID='$id'" );
            
            if ( count( $box_array ) > 1 )
            {
                die( "Error: Box Types with the same ID was found in the database. This should not happen." );
            }
            else if( count( $box_array ) == 1 )
            {
                $this->ID =& $box_array[0][$db->fieldName("ID")];
                $this->Name =& $box_array[0][$db->fieldName("Name")];
				$this->Length =& $box_array[0][$db->fieldName("Length")];
				$this->Height =& $box_array[0][$db->fieldName("Height")];
				$this->Width =& $box_array[0][$db->fieldName("Width")];
            }
        }
    }

    /*!
      Retrieves all the box types from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $box_array = array();
        
        $db->array_query( $box_array, "SELECT ID FROM eZTrade_BoxType ORDER BY ID" );
        
        for ( $i=0; $i<count($box_array); $i++ )
        {
            $return_array[$i] = new eZBoxType( $box_array[$i][$db->fieldName("ID")], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a box type from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZTrade_BoxType WHERE ID='$this->ID'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the box type.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the length of the box type.
    */
    function length()
    {
        return $this->Length;
    }

    /*!
      Returns the height of the box type.
    */
    function height()
    {
        return $this->Height;
    }

    /*!
      Returns the width of the box type.
    */
    function width()
    {
        return $this->Width;
    }
	
    /*!
      Sets the name of the box type.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the length of the box type.
    */
    function setLength( $value )
    {
        $this->Length = $value;
    }

    /*!
      Sets the height of the box type.
    */
    function setHeight( $value )
    {
        $this->Height = $value;
    }

    /*!
      Sets the width of the box type.
    */
    function setWidth( $value )
    {
        $this->Width = $value;
    }
		
    var $ID;
    var $Name;
    var $Length;
    var $Height;    
    var $Width;
}
?>