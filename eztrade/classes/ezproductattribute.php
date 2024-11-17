<?php
//
// $Id: ezproductattribute.php 6318 2001-07-31 11:33:12Z jhe $
//
// Definition of eZProductAttribute class
//
// Created on: <20-Dec-2000 13:43:02 bf>
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
//! This class handles different product attributes.
/*!

  \code

  $attribute = new eZProductAttribute();
  $attribute->setType( $type );
  $attribute->setName( "Doors" );
  $attribute->store();

  \endcode
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezproducttype.php" );

class eZProductAttribute
{
    /*!
      Constructs a new eZProductAttribute object. Retrieves the data from the database
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
      Stores a eZProductattribute object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $this->Name = $db->escapeString( $this->Name );
        $this->Unit = $db->escapeString( $this->Unit );

        if ( !isset( $this->ID ) )
        {

            $db->array_query( $attribute_array, "SELECT Placement FROM eZTrade_Attribute" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place[$db->fieldName( "Placement" )];
                $place++;
            }
            $timeStamp = eZDateTime::timeStamp( true );
            $db->lock( "eZTrade_Attribute" );
            $nextID = $db->nextID( "eZTrade_Attribute", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_Attribute
                               ( ID,
		                         Name,
		                         TypeID,
		                         AttributeType,
		                         Placement,
		                         Unit,
		                         Created )
                               VALUES
                               ( '$nextID',
		                         '$this->Name',
		                         '$this->TypeID',
		                         '$this->AttributeType',
		                         '$place',
		                         '$this->Unit',
		                         '$timeStamp' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_Attribute SET
		                         Name='$this->Name',
		                         Created=Created,
		                         AttributeType='$this->AttributeType',
		                         Unit='$this->Unit',
		                         TypeID='$this->TypeID' WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );

        return true;
    }

    /*!
      Fetches the product attribute object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $attribute_array, "SELECT * FROM eZTrade_Attribute WHERE ID='$id'" );

            if ( count( $attribute_array ) > 1 )
            {
                die( "Error: Product attribute's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $attribute_array ) == 1 )
            {
                $this->ID =& $attribute_array[0][$db->fieldName( "ID" )];
                $this->Name =& $attribute_array[0][$db->fieldName( "Name" )];
                $this->TypeID =& $attribute_array[0][$db->fieldName( "TypeID" )];
                $this->AttributeType =& $attribute_array[0][$db->fieldName( "AttributeType" )];
                $this->Placement =& $attribute_array[0][$db->fieldName( "Placement" )];
                $this->Unit =& $attribute_array[0][$db->fieldName( "Unit" )];

            }
        }
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $attribute_array = array();

        $db->array_query( $attribute_array, "SELECT ID FROM eZTrade_Attribute ORDER BY Created" );

        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $return_array[$i] = new eZProductAttribute( $attribute_array[$i][$db->fieldName( "ID" )], 0 );
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

        $res[] = $db->query( "DELETE FROM eZTrade_AttributeValue WHERE AttributeID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_Attribute WHERE ID='$this->ID'" );

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
      Returns the name of the attribute.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the measuring unit of the attribute.
    */
    function unit()
    {
        return $this->Unit;
    }

    /*!
      Returns the class of the attribute.

      1 = normal attribute
      2 = header
    */
    function attributeType()
    {
       return $this->AttributeType;
    }


    /*!
      Returns the type of the attribute.
    */
    function type()
    {
       $type = new eZProductType( $this->TypeID );
       return $type;
    }


    /*!
      Sets the name of the attribute.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the measuring unit of the attribute.
    */
    function setUnit( $value )
    {
        $this->Unit = $value;
    }

    /*!
      Sets the type of the attribute.
    */
    function setType( $type )
    {
       if ( is_a( $type, "eZProductType" ) )
       {
           $this->TypeID = $type->id();
       }
    }

    /*!
      Sets the type of the attribute.

      1 = normal attribute
      2 = header
    */
    function setAttributeType( $attributeType )
    {
       if ( $attributeType == 2 )
       {
           $db =& eZDB::globalDatabase();
           $db->begin();

           $res[] = $db->query( "DELETE FROM eZTrade_AttributeValue WHERE AttributeID='$this->ID'" );

           eZDB::finish( $res, $db );
       }

       $this->AttributeType = $attributeType;

    }

    /*!
      Sets the attribute value for the given product.
    */
    function setValue( $product, $value )
    {
        $db =& eZDB::globalDatabase();

        if ( is_a( $product, "eZProduct" ) )
        {
            $value = $db->escapeString( $value );
            $productID = $product->id();

            // check if the attribute is already set, if so update
            $db->array_query( $value_array,
            "SELECT ID FROM eZTrade_AttributeValue WHERE ProductID='$productID' AND AttributeID='$this->ID'" );

            if ( count( $value_array ) > 0 )
            {
                $valueID = $value_array[0][$db->fieldName( "ID" )];

                $res = $db->query( "UPDATE eZTrade_AttributeValue SET
                                 Value='$value'
                                 WHERE ID='$valueID'" );
            }
            else
            {
                $db->lock( "eZTrade_AttributeValue" );
                $nextID = $db->nextID( "eZTrade_AttributeValue", "ID" );
                $res[] = $db->query( "INSERT INTO eZTrade_AttributeValue
                               ( ID,
                                 ProductID,
                                 AttributeID,
                                 Value )
                               VALUES
                               ( '$nextID',
                                 '$productID',
                                 '$this->ID',
                                 '$value' )" );
                $db->unlock();
            }
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the attribute value to the given product.
    */
    function value( $product )
    {
        $db =& eZDB::globalDatabase();
        $ret = "";
        if ( is_a( $product, "eZProduct" ) )
        {
            $productID = $product->id();

            // check if the attribute is already set, if so update
            $db->array_query( $value_array,
            "SELECT Value FROM eZTrade_AttributeValue WHERE ProductID='$productID'
             AND AttributeID='$this->ID'" );

            if ( count( $value_array ) > 0 )
            {
                $ret = $value_array[0][$db->fieldName( "Value" )];
            }
        }
        return $ret;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_Attribute
                                  WHERE Placement<'$this->Placement' ORDER BY Placement DESC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZTrade_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZTrade_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_Attribute
                                  WHERE Placement>'$this->Placement' ORDER BY Placement ASC", array( "Limit" => 1, "Offset" => 0 ) );
        $listorder = $qry[$db->fieldName( "Placement" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZTrade_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZTrade_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );

        eZDB::finish( $res, $db );
    }


    var $ID;
    var $TypeID;
    var $Name;
    var $AttributeType;
    var $Placement;
    var $Unit;
}
?>