<?php
// 
// $Id: ezorderstatustype.php 6318 2001-07-31 11:33:12Z jhe $
//
// Definition of eZOrderStatus class
//
// Created on: <02-Oct-2000 15:06:32 bf>
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
//! eZOrderStatusType handles order status types.
/*!

  \sa eZOrder eZOrderStatus
*/

/*!TODO
  Add documentation.
    
*/

include_once( "classes/ezdb.php" );

class eZOrderStatusType
{
    /*!
      Constructs a new eZOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order status  to the database.
      \return true if successful, false if not
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        
        $ret = false;
        if ( $this->Name != "" )
        {
            if ( !isset( $this->ID ) )
            {
                $db->lock( "eZTrade_OrderStatusType" );
                $nextID = $db->nextID( "eZTrade_OrderStatusType", "ID" );
                $res[] = $db->query( "INSERT INTO eZTrade_OrderStatusType
                               ( ID,
		                         Name )
                               VALUES
                               ( '$nextID'
                                 '$this->Name' )" );
                $db->unlock();
				$this->ID = $nextID;

                $ret = true;
            }
            else
            {
                $res[] = $db->query( "UPDATE eZTrade_OrderStatusType SET
		                         Name='$this->Name'
                                 " );

                $ret = true;                
            }
        }
        eZDB::finish( $res, $db );
        return $ret;
    }    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $status_type_array, "SELECT * FROM eZTrade_OrderStatusType WHERE ID='$id'" );
            if ( count( $status_type_array ) > 1 )
            {
                die( "Error: Status_type's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $status_type_array ) == 1 )
            {
                $this->ID =& $status_type_array[0][$db->fieldName("ID")];
                $this->Name =& $status_type_array[0][$db->fieldName("Name")];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Fetches every status type and returns them as an array of eZOrderStatusType
      objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        $ret = array();

        $db->array_query( $status_type_array, "SELECT ID FROM eZTrade_OrderStatusType ORDER BY Name" );

        foreach ( $status_type_array as $status_type )
        {
            $ret[] = new eZOrderStatusType( $status_type[$db->fieldName("ID")] );
        }
        return $ret;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the status type.
    */
    function name()
    {
       return $this->Name;
    }

    /*!
      Sets the status type name
    */
    function setName( $value )
    {
       $this->Name = $value;
    }
    
    /*!
      Returns the eZOrderStatusObject to the status matching name given as
      argument. false is returned if none is found.
    */
    function getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $db->array_query( $value_array, "SELECT ID FROM eZTrade_OrderStatusType WHERE Name='$name'" );

        if ( count( $value_array ) == 1 )
        {
            $ret = new eZOrderStatusType( $value_array[0][$db->fieldName( "ID" )] );
        }
        else
        {
            return false;
        }

        return $ret;
    }
    
    var $ID;
    var $Name;
}

?>