<?php
// 
// $Id: ezphonetype.php 6204 2001-07-19 12:06:56Z jakobn $
//
// Definition of eZAddressType class
//
// Created on: <26-Jun-2001 13:40:19 ce>
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

//!! eZAddressType
//! eZAddressType handles address types.
/*!
  
  Example code:
  \code
  // create a new phone type and set some variables.
  $phoneType = new eZPhoneType();
  $phoneType->setName( "Home phone" );
  $phoneType->store();

  \endcode

  \sa eZAddressType
*/

class eZPhoneType
{
    /*
      Constructs a new eZPhoneType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id= -1 )
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
      Stores or updates a eZPhoneType object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder FROM eZAddress_PhoneType ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
            $listorder = $qry[ $db->fieldName( "ListOrder" ) ] + 1;
            $this->ListOrder = $listorder;

            $db->lock( "eZAddress_PhoneType" );
            $nextID = $db->nextID( "eZAddress_PhoneType", "ID" );

            $result = $db->query( "INSERT INTO eZAddress_PhoneType
                         ( ID, Name, ListOrder )
                         VALUES ( '$nextID',
                                  '$name',
                                  '$this->ListOrder') " );

            $db->unlock();
			$this->ID = $nextID;

        }
        else
        {
            $result = $db->query( "UPDATE eZAddress_PhoneType set Name='$name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );
        }
        

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*
      Deletes the eZPhoneType object for the database,
    */
    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;

        $db->begin();
        $result = $db->query( "UPDATE eZAddress_PhoneType SET Removed=1 WHERE ID='$id'" );

        if ( $result != true )
            $db->rollback( );
        else
            $db->commit();
    }

    /*
      Fetches the eZPhoneType object information from the database.

      True is retuned if successful, false (0) if not.
    */  
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $phone_type_array, "SELECT * FROM eZAddress_PhoneType WHERE ID='$id'",
                              0, 1 );
            if ( count( $phone_type_array ) == 1 )
            {
                $this->fill( $phone_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$phone_type_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $phone_type_array[$db->fieldName("ID")];
        $this->Name = $phone_type_array[$db->fieldName("Name")];
        $this->ListOrder = $phone_type_array[$db->fieldName("ListOrder")];
    }

    /*
      \static
      Fetches the addresstype id from the database. And returns a array of eZAddressType objects.
    */
    static public function getAllCount()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $phone_type_array,
                          "SELECT Count( ID ) AS Count FROM eZAddress_PhoneType" );
        return $phone_type_array[$db->fieldName("Count")];
    }

    /*
      \static
      Fetches the phonetype id from the database. And returns a array of eZPhoneType objects.
    */
    static public function getAll( $as_object = true, $offset = 0, $max = -1 )
    {
        $db =& eZDB::globalDatabase();

        $phone_type_edit = array();
        $limit = array();
        $return_array = array();

        if ( $max >= 0 && is_numeric( $offset ) && is_numeric( $max ) )
        {
            $limit = array( "Limit" => $max,
                            "Offset" => $offset );
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $phone_type_array,
                          "SELECT $select FROM eZAddress_PhoneType
                                          WHERE Removed=0
                                          ORDER BY ListOrder", $limit 
                        );

        if ( $as_object )
        {
            foreach( $phone_type_array as $phoneTypeItem )
            {
                $return_array[] = new eZPhoneType( $phoneTypeItem );
            }
        }
        else
        {
            foreach( $phone_type_array as $phoneTypeItem )
            {
                $return_array[] = $phoneTypeItem[ $db->fieldName( "ID" ) ];
            }
        }
        return $return_array;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Returns the name.
    */
    function name(  )
    {
        return $this->Name;
    }  

    /*!
      Returns the id.
    */
    function id(  )
    {
        return $this->ID;
    }  
    
    /*!
      Returns the number of external items using this item.
    */
    function &count()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry,  "SELECT COUNT( Ph.ID ) as Count
                                         FROM eZAddress_Phone AS Ph, eZAddress_PhoneType AS PT
                                         WHERE Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
        $cnt = 0;
        if ( count( $qry ) > 0 )
            $cnt += $qry[0][ $db->fieldName( "Count" ) ];
        return $cnt;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_PhoneType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
        $listorder = $qry[$db->fieldName("ListOrder")];
        $listid = $qry[$db->fieldName("ID")];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_PhoneType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC", array( "Limit" => "1" ) );
        $listorder = $qry[$db->fieldName("ListOrder")];
        $listid = $qry[$db->fieldName("ID")];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    var $ID;
    var $Name;
    var $ListOrder;
}

?>