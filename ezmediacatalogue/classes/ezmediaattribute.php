<?php
//
// $Id: ezmediaattribute.php 8138 2001-11-01 08:31:40Z ce $
//
// Definition of eZMediaAttribute class
//
// Created on: <29-Jun-2001 11:09:22 jhe>
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

//!! eZMediaAttribute
//! eZMediaAttribute handles the attributes for a media.
/*!
  Example code:
  \code
  // create a new country type and set some variables.
  $attribute = new eZMediaAttribute();
  $attribute->setName( "autoplay" );
  $attribute->setValue( true );
  $attribute->setType( $type );
  $attribute->store();
  \endcode
  \sa eZMedia eZMediaType eZMediaCategory

*/

include_once( "classes/ezdb.php" );
include_once( "ezmediacatalogue/classes/ezmediatype.php" );

class eZMediaAttribute
{
    /*!
      Constructs a new eZMediaAttribute object. Retrieves the data from the database
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
      Stores a eZMediaattribute object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
        $db->lock( "eZMediaCatalogue_Attribute" );

        if ( !isSet( $this->ID ) )
        {
            $db->array_query( $attribute_array, "SELECT Placement FROM eZMediaCatalogue_Attribute" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place["Placement"];
                $place++;
            }

            $timeStamp =& eZDateTime::timeStamp( true );

			$this->ID = $db->nextID( "eZMediaCatalogue_Attribute", "ID" );
            $res = $db->query( "INSERT INTO eZMediaCatalogue_Attribute
                                            (ID,
                                             Name,
                                             DefaultValue,
                                             TypeID,
                                             Placement,
                                             Unit,
                                             Created)
                                            VALUES
                                            ('$this->ID',
                                             '$this->Name',
                                             '$this->DefaultValue',
                                             '$this->TypeID',
                                             '$place',
                                             '$this->Unit',
                                             '$timeStamp')" );

        }
        else
        {
            $res = $db->query( "UPDATE eZMediaCatalogue_Attribute SET
		                                    Name='$this->Name',
		                                    DefaultValue='$this->DefaultValue',
                                            Created=Created,
		                                    Unit='$this->Unit',
		                                    TypeID='$this->TypeID' WHERE ID='$this->ID'" );

        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the media attribute object values from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $attribute_array, "SELECT * FROM eZMediaCatalogue_Attribute WHERE ID='$id'" );

            if ( count( $attribute_array ) > 1 )
            {
                die( "Error: Media attribute's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $attribute_array ) == 1 )
            {
                $this->ID =& $attribute_array[0][ $db->fieldName( "ID" ) ];
                $this->Name =& $attribute_array[0][ $db->fieldName( "Name" ) ];
                $this->DefaultValue =& $attribute_array[0][ $db->fieldName( "DefaultValue" ) ];
                $this->TypeID =& $attribute_array[0][ $db->fieldName( "TypeID" ) ];
                $this->Placement =& $attribute_array[0][ $db->fieldName( "Placement" ) ];
                $this->Unit =& $attribute_array[0][ $db->fieldName( "Unit" ) ];
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

        $db->array_query( $attribute_array, "SELECT ID FROM eZMediaCatalogue_Attribute ORDER BY Created" );

        for ( $i = 0; $i < count( $attribute_array ); $i++ )
        {
            $return_array[$i] = new eZMediaAttribute( $attribute_array[$i][ $db->fieldName( "ID" ) ], 0 );
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
        $res[] = $db->query( "DELETE FROM eZMediaCatalogue_AttributeValue WHERE AttributeID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZMediaCatalogue_Attribute WHERE ID='$this->ID'" );
        if ( in_array( false, $res ) )
            $db->rollback();
        else
            $db->commit();
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
    function &name()
    {
        return $this->Name;
    }

    /*!
      Returns the devault value of the attribute.
    */
    function &defaultValue()
    {
        return $this->DefaultValue;
    }

    /*!
      Returns the measuring unit of the attribute.
    */
    function &unit()
    {
        return $this->Unit;
    }

    /*!
      Returns the type of the attribute.
    */
    function &type()
    {
        $type = new eZMediaType( $this->TypeID );
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
      Sets the default value of the attribute.
    */
    function setDefaultValue( &$value )
    {
        $this->DefaultValue = $value;
    }

    /*!
      Sets the measuring unit of the attribute.
    */
    function setUnit( &$value )
    {
        $this->Unit = $value;
    }

    /*!
      Sets the type of the attribute.
    */
    function setType( &$type )
    {
        if ( is_a( $type, "eZMediaType" ) )
        {
            $this->TypeID = $type->id();
        }
    }

    /*!
      Sets the attribute value for the given media.
    */
    function setValue( &$media, &$value )
    {
        $db =& eZDB::globalDatabase();

        if ( is_a( $media, "eZMedia" ) )
        {
            $mediaID = $media->id();

            // check if the attribute is already set, if so update
            $db->array_query( $value_array,
            "SELECT ID FROM eZMediaCatalogue_AttributeValue WHERE MediaID='$mediaID' AND AttributeID='$this->ID'" );

            $db->begin();

            if ( count( $value_array ) > 0 )
            {
                $valueID = $value_array[0]["ID"];

                $res = $db->query( "UPDATE eZMediaCatalogue_AttributeValue SET
                                                Value='$value'
                                                WHERE ID='$valueID'" );
            }
            else
            {
                $db->lock( "eZMediaCatalogue_AttributeValue" );
                $nextID = $db->nextID( "eZMediaCatalogue_AttributeValue", "ID" );

                $res = $db->query( "INSERT INTO eZMediaCatalogue_AttributeValue
                                                (ID, MediaID, AttributeID, Value)
                                                VALUES
		                                        ('$nextID','$mediaID','$this->ID','$value')" );
                $db->unlock();
            }
            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();

        }
    }

    /*!
      Returns the attribute value to the given media.
    */
    function &value( &$media )
    {
        $db =& eZDB::globalDatabase();
        $ret = "";
        if ( is_a( $media, "eZMedia" ) )
        {
            $mediaID = $media->id();

            // check if the attribute is already set, if so update
            $db->array_query( $value_array,
            "SELECT Value FROM eZMediaCatalogue_AttributeValue WHERE MediaID='$mediaID'
             AND AttributeID='$this->ID'" );

            if ( count( $value_array ) > 0 )
            {
                $ret = $value_array[0][ $db->fieldName( "Value" ) ];
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
        $db->query_single( $qry, "SELECT ID, Placement FROM eZMediaCatalogue_Attribute
                                  WHERE Placement<'$this->Placement' ORDER BY Placement DESC",
                                  array( "Limit" => 1 ) );
        $listorder = $qry[ $db->fieldName( "Placement" ) ];
        $listid = $qry[ $db->fieldName( "ID" ) ];
        $db->begin();
        $db->lock( "eZMediaCatalogue_Attribute" );
        $res[] = $db->query( "UPDATE eZMediaCatalogue_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZMediaCatalogue_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
        $db->unlock();

        if ( in_array( false, $res ) )
            $db->rollback();
        else
            $db->commit();
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZMediaCatalogue_Attribute
                                  WHERE Placement>'$this->Placement' ORDER BY Placement ASC",
                                  array( "Limit" => 1 ) );
        $listorder = $qry[ $db->fieldName( "Placement" ) ];
        $listid = $qry[ $db->fieldName( "ID" ) ];
        $db->begin();
        $db->lock( "eZMediaCatalogue_Attribute" );
        $res[] = $db->query( "UPDATE eZMediaCatalogue_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZMediaCatalogue_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
        $db->unlock();
        if ( in_array( false, $res ) )
            $db->rollback();
        else
            $db->commit();
    }

    var $ID;
    var $TypeID;
    var $Name;
    var $Placement;
    var $Unit;
    var $DefaultValue;

}

?>