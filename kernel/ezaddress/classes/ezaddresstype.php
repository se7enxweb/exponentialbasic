<?php
// 
// $Id: ezaddresstype.php 6204 2001-07-19 12:06:56Z jakobn $
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
  // create a new address type and set some variables.
  $addressType = new eZAddressType();
  $addressType->setName( "Home address" );
  $addressType->store();        

  \endcode

  \sa eZPhoneType
*/

class eZAddressType
{
    /*!
      Constructs a new eZAddressType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
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
      Stores or updates a eZAddressType object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $db->begin( );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZAddress_AddressType" );
            $nextID = $db->nextID( "eZAddress_AddressType", "ID" );

            $db->query_single( $qry, "SELECT ListOrder FROM eZAddress_AddressType ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
            $listorder = $qry[$db->fieldName("ListOrder")] + 1;
            $this->ListOrder = $listorder;

            $result = $db->query( "INSERT INTO eZAddress_AddressType
                      ( ID, Name, ListOrder )
                      VALUES ( '$nextID',
                               '$name',
                               '$this->ListOrder') " );

			$this->ID = $nextID;

        }
        else
        {
            $result = $db->query( "UPDATE eZAddress_AddressType set Name='$name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();

        return $dbError;
    }

    /*
      Deletes a eZAddressType object from the database.
     */

    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;

        $db->begin( );
        $res[] = $result = $db->query( "UPDATE eZAddress_AddressType SET Removed=1 WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }
    
    /*
      Fetches the eZAddressType object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $address_type_array, "SELECT * FROM eZAddress_AddressType
                                                    WHERE ID='$id'",
                              0, 1 );
            if ( count( $address_type_array ) == 1 )
            {
                $this->fill( $address_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Fills in information to the eZAddressType object taken from the array.
    */
    function fill( &$address_type_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $address_type_array[$db->fieldName("ID")];
        $this->Name = $address_type_array[$db->fieldName("Name")];
        $this->ListOrder = $address_type_array[$db->fieldName("ListOrder")];
    }

    /*
      \static
      Fetches the addresstype id from the database. And returns a array of eZAddressType objects.
    */
    static public function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $online_type_array = 0;

        $address_type_array = array();
        $return_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";
    
        $db->array_query( $address_type_array, "SELECT $select FROM eZAddress_AddressType
                                                WHERE Removed=0
                                                ORDER BY ListOrder" );

        if ( $as_object )
        {
            foreach( $address_type_array as $addressTypeItem )
            {
                $return_array[] = new eZAddressType( $addressTypeItem );
            }
        }
        else
        {
            foreach( $address_type_array as $addressTypeItem )
            {
                $return_array[] = $addressTypeItem[ $db->fieldName("ID")];
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
        $db->array_query( $qry, "SELECT COUNT( Ad.ID ) as Count
                                 FROM  eZAddress_Address AS Ad, eZAddress_AddressType AS AT
                                 WHERE Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
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
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_AddressType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC", array( "Limit" => "1" ) );
        $listorder = $qry[ $db->fieldName( "ListOrder" ) ];
        $listid = $qry[ $db->fieldName( "ID" ) ];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_AddressType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC", array( "Limit" => "1" ) );
        $listorder = $qry[$db->fieldName("ListOrder")];
        $listid = $qry[$db->fieldName("ID")];

        $db->begin();
        $res[] = $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    var $ID;
    var $Name;
    var $ListOrder;
}
?>