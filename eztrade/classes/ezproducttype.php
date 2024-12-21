<?php
// 
// $Id: ezproducttype.php 6318 2001-07-31 11:33:12Z jhe $
//
// Definition of eZProductType class
//
// Created on: <20-Dec-2000 13:31:12 bf>
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


//!! eZTrade
//! This class handles different product types.
/*!
  A product type is a group of products with the same special attributes. For example
  a product type could be cars, with the defined attributes: horsepower, weight ...

  \code

  \endcode  
  \sa eZProduct
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezproductattribute.php" );

class eZProductType
{
    /*!
      Constructs a new eZProductType object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function __construct( $id=-1, $fetch=true )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZProducttype object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        $this->Description = $db->escapeString( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Type" );
            $nextID = $db->nextID( "eZTrade_Type", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_Type
                               ( ID,                               
		                         Name,
                                 Description )
                               VALUES
                               ( '$nextID',
		                         '$this->Name',
                                 '$this->Description' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_Type SET
		                         Name='$this->Name',
                                 Description='$this->Description' WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the product type object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != -1  )
        {
            $db->array_query( $type_array, "SELECT * FROM eZTrade_Type WHERE ID='$id'" );
            
            if ( count( $type_array ) > 1 )
            {
                die( "Error: Product type's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName( "ID" )];
                $this->Name =& $type_array[0][$db->fieldName( "Name" )];
                $this->Description =& $type_array[0][$db->fieldName( "Description" )];
                
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
        
        $db->array_query( $type_array, "SELECT ID FROM eZTrade_Type ORDER BY Name" );
        
        for ( $i=0; $i<count($type_array); $i++ )
        {
            $return_array[$i] = new eZProductType( $type_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        // delete all attributes and values
        $attributes = $this->attributes();
        foreach ( $attributes as $attribute )
        {
            $attribute->delete();
        }

        $res[] = $db->query( "DELETE FROM eZTrade_ProductTypeLink WHERE TypeID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_Type WHERE ID='$this->ID'" );

        eZDB::finish( $res, $db );
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
      Returns an array of eZProductAttribute objects which
      are associated with the current product type.
    */
    function attributes( )
    {
       $db =& eZDB::globalDatabase();
       
       $return_array = array();
       $attribute_array = array();
       
       $db->array_query( $attribute_array, "SELECT ID
                                                      FROM eZTrade_Attribute
                                                      WHERE TypeID='$this->ID' ORDER BY Placement" );

       for ( $i=0; $i<count($attribute_array); $i++ )
       {
           $return_array[$i] = new eZProductAttribute( $attribute_array[$i][$db->fieldName( "ID" )], false );
       }
       
       return $return_array;
    }


    var $ID;
    var $Name;
    var $Description;

}

?>