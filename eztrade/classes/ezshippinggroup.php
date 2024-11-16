<?php
//
// $Id: ezshippinggroup.php 7217 2001-09-14 12:29:11Z ce $
//
// Definition of eZShippingGroup class
//
// Created on: <22-Feb-2001 14:06:14 bf>
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
//! This class handles shipping groups.
/*!
  Shipping groups is grouping of selected products which
  have the same shipping parameters, e.g. weight.

  \sa eZProduct
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );


class eZShippingGroup
{
    /*!
      Constructs a new object.
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
      Stores/updates the Shipping group in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );

        if ( !isset( $this->ID ) )
        {
            $timeStamp =& eZDateTime::timeStamp( true );
            $db->lock( "eZTrade_ShippingGroup" );
            $nextID = $db->nextID( "eZTrade_ShippingGroup", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_ShippingGroup
                       ( ID,
		                 Name,
		                 Created )
                       VALUES
		               ( '$nextID',
                         '$this->Name',
		                 '$timeStamp' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_ShippingGroup SET
                        Name='$this->Name',
  	                    Created=Created
                        WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the Shipping Group from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $shipping_array, "SELECT * FROM eZTrade_ShippingGroup WHERE ID='$id'" );

            if ( count( $shipping_array ) > 1 )
            {
                die( "Error: Shipping Groups's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $shipping_array ) == 1 )
            {
                $this->ID =& $shipping_array[0][$db->fieldName( "ID" )];
                $this->Name =& $shipping_array[0][$db->fieldName( "Name" )];
            }
        }
    }

    /*!
      Retrieves all the VAT groups from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $shipping_array = array();

        $db->array_query( $shipping_array, "SELECT ID FROM eZTrade_ShippingGroup ORDER BY Created" );

        for ( $i=0; $i<count($shipping_array); $i++ )
        {
            $return_array[$i] = new eZShippingGroup( $shipping_array[$i][$db->fieldName( "ID" )], 0 );
        }
        return $return_array;
    }

    /*!
      Deletes a Shipping Group from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZTrade_ShippingValue WHERE ShippingGroupID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_ShippingGroup WHERE ID='$this->ID'" );

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
      Returns the name of the vat group.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the shipping start and add value for the given eZShippingType to the
      current eZShippingGroup with the $value .
    */
    function setStartAddValue( $type, $startValue, $addValue )
    {
        if ( is_a( $type, "eZShippingType" ) )
        {
            $db =& eZDB::globalDatabase();
            $typeID = $type->id();

            $value_array = array();

            $db->array_query( $value_array, "SELECT ID FROM eZTrade_ShippingValue
            WHERE ShippingTypeID='$typeID' AND ShippingGroupID='$this->ID'" );

            if ( count( $value_array ) == 1 )
            {
                $vid = $value_array[0][$db->fieldName( "ID" )];

                $res[] = $db->query( "UPDATE eZTrade_ShippingValue  SET StartValue='$startValue',
                AddValue='$addValue'
                WHERE ID='$vid'
                " );
            }
            else
            {
                $db->lock( "eZTrade_ShippingValue" );
                $nextID = $db->nextID( "eZTrade_ShippingValue", "ID" );
                $res[] = $db->query( "INSERT INTO eZTrade_ShippingValue
                               ( ID,
                                 StartValue,
                                 AddValue,
                                 ShippingTypeID,
                                 ShippingGroupID )
                               VALUES
                               ( '$nextID',
                                 '$startValue',
                                 '$addValue',
                                 '$typeID',
                                 '$this->ID' )" );
                $db->unlock();
            }
        }
        eZDB::finish( $res, $db );
    }

    /*!
      Returns the start and add values for the given shipping type.

      An empty array is returned if not found.
    */
    function &startAddValue( $type )
    {
        $ret = array();

        if ( is_a( $type, "eZShippingType" ) )
        {
            $db =& eZDB::globalDatabase();
            $typeID = $type->id();

            $value_array = array();

            $db->array_query( $value_array, "SELECT * FROM eZTrade_ShippingValue
            WHERE ShippingTypeID='$typeID' AND ShippingGroupID='$this->ID'" );

            $ret = array( "StartValue" => $value_array[0][$db->fieldName( "StartValue" )],
                          "AddValue" => $value_array[0][$db->fieldName( "AddValue" )]);
        }

        return $ret;
    }


    /*!
      Sets the name of the vat group.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    var $ID;
    var $Name;
}

?>