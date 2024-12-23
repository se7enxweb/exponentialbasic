<?php
// 
// $Id: ezprojecttype.php 9506 2002-05-06 13:39:53Z jhe $
//
// Definition of eZProjectType class
//
// Created on: <19-Mar-2001 16:51:20 amos>
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

//!! eZContact
//! eZProjectType handles project types.
/*!

  Example code:
  \code
  $type = new eZProjectType();
  $type->setName( "Negotiating" );
  $type->store();

  $stored_type = new eZProjectType( $consultID );
  $types = eZProjectType::findTypes();
  foreach ( $types as $type )
  {
      print( $type->name() );
  }

  \endcode

  \sa eZProject
*/

// include_once( "classes/ezdb.php" );

class eZProjectType
{
    /*!
      Constructs a new eZProjectType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        $this->ExpiryTime = 0;
        $this->WarningTime = 0;

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
      Stores the project type to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_ProjectType ORDER BY ListOrder DESC",
                               array( "Limit" => 1 ) );

            if( is_array( $qry ) && count( $qry ) > 0 )
            {
                $listorder = $qry[$db->fieldName( "ListOrder" )] + 1;
                $this->ListOrder = $listorder;

                $db->lock( "eZContact_ProjectType" );
                $this->ID = $db->nextID( "eZContact_ProjectType", "ID" );
                $res[] = $db->query( "INSERT INTO eZContact_ProjectType
                                  (ID, Name, ListOrder, ExpiryTime, WarningTime)
                                  VALUES
                                  ('$this->ID', '$name', '$listorder', '$this->ExpiryTime', '$this->WarningTime')" );
            }
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZContact_ProjectType SET
                                                  Name='$name',
                                                  ListOrder='$this->ListOrder',
                                                  ExpiryTime='$this->ExpiryTime',
                                                  WarningTime='$this->WarningTime'
                                                  WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes an eZProject object from the database, if $relations is true all relations to this item is deleted too.
    */
    function delete( $relations = false )
    {
        if ( isset( $this->ID ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            $res[] = $db->query( "DELETE FROM eZContact_PersonProjectDict WHERE ProjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZContact_CompanyProjectDict WHERE ProjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZContact_ProjectType WHERE ID='$this->ID'" );
            eZDB::finish( $res, $db );
        }
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $ret = false;

        if ( $id != "" )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $consulttype_array, "SELECT * FROM eZContact_ProjectType WHERE ID='$id'" );
            $this->fill( $consulttype_array );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$consulttype_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $consulttype_array[$db->fieldName( "ID" )];
        $this->Name = $consulttype_array[$db->fieldName( "Name" )];
        $this->ListOrder = $consulttype_array[$db->fieldName( "ListOrder" )];
        $this->ExpiryTime = $consulttype_array[$db->fieldName( "ExpiryTime" )];
        $this->WarningTime = $consulttype_array[$db->fieldName( "WarningTime" )];
    }

    /*!
      Sets the name of the project type.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    function setExpiryTime( $value )
    {
        $this->ExpiryTime = $value;
    }

    function setWarningTime( $value )
    {
        $this->WarningTime = $value;
    }
    
    /*!
      Returns the id of the project type.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the project type.
    */
    function name()
    {
        return $this->Name;
    }

    function expiryTime()
    {
        return $this->ExpiryTime;
    }

    function warningTime()
    {
        return $this->WarningTime;
    }
    
    /*!
      Returns the number of external items using this item.
    */
    function &count()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $person_qry, "SELECT count( PersonID ) as Count
                                         FROM eZContact_PersonProjectDict
                                         WHERE ProjectID='$this->ID'" );
        $db->query_single( $company_qry, "SELECT count( CompanyID ) as Count
                                          FROM eZContact_CompanyProjectDict
                                          WHERE ProjectID='$this->ID'" );
        return $person_qry[$db->fieldName( "Count" )] + $company_qry[$db->fieldName( "Count" )];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ProjectType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC",
                                  array( "Limit" => 1 ) );
        $listorder = $qry[$db->fieldName( "ListOrder" )];
        $listid = $qry[$db->fieldName( "ID" )];
        $res[] = $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ProjectType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC",
                                  array( "Limit" => 1 ) );
        $listorder = $qry[$db->fieldName( "ListOrder" )];
        $listid = $qry[ $db->fieldName( "ID" ) ];
        $res[] = $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );
    }

    /*!
      \static
      Finds all project types.
      Returns an array with eZProjectType objects taken from the database.
    */
    static public function &findTypes( $as_object = true )
    {
        $qry_array = array();
        $db =& eZDB::globalDatabase();
        if ( $as_object )
            $select = "*";
        else
            $select = "ID";
        $db->array_query( $qry_array, "SELECT $select FROM eZContact_ProjectType ORDER BY ListOrder" );
        $ret_array = array();
        if ( $as_object )
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZProjectType( $qry );
            }
        }
        else
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = $qry[$db->fieldName( "ID" )];
            }
        }
        return $ret_array;
    }

    var $ID;
    var $Name;
    var $ListOrder;
    var $ExpiryTime;
    var $WarningTime;
}

?>