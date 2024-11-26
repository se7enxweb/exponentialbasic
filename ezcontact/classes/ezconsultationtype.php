<?php
// 
// $Id: ezconsultationtype.php 6970 2001-09-05 11:57:07Z jhe $
//
// Definition of eZConsultationType class
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
//! eZConsultationType handles consultation types.
/*!

  Example code:
  \code
  $type = new eZConsultationType();
  $type->setName( "Negotiating" );
  $type->store();

  $stored_type = new eZConsultationType( $consultID );
  $types = eZConsultationType::findTypes();
  foreach ( $types as $type )
  {
      print( $type->name() );
  }

  \endcode

  \sa eZConsultation
*/

include_once( "classes/ezdb.php" );

class eZConsultationType
{
    /*!
      Constructs a new eZConsultationType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the consultation type to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->fieldName( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_ConsultationType ORDER BY ListOrder DESC", array( "Limit" => 1 ) );
            $listorder = $qry[ $db->fieldName( "ListOrder" ) ] + 1;
            $this->ListOrder = $listorder;
            $db->lock( "eZContact_ConsultationType" );
			$this->ID = $db->nextID( "eZContact_ConsultationType", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_ConsultationType
                                  (ID, Name, ListOrder)
                                  VALUES
                                  ('$this->ID', '$name', '$listorder')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZContact_ConsultationType SET
                                          Name='$name',
                                          ListOrder='$this->ListOrder'
                                          WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes an eZConsultation object from the database, if $relations is true all relations to this item is deleted too.
    */
    function delete( $relations = false )
    {
        if ( isSet( $this->ID ) )
        {
            $db =& eZDB::globalDatabase();
            if ( $relations )
            {
                $user =& eZUser::currentUser();
                $user_id = $user->id();
                $db->array_query( $consultations, "SELECT A.ID FROM eZContact_Consultation AS A, eZContact_ConsultationPersonUserDict AS B
                                                   WHERE A.ID = B.ConsultationID AND B.UserID='$user_id' AND A.StateID='$this->ID'" );
                foreach ( $consultations as $consultation )
                {
                    eZConsultation::delete( $consultation[ $db->fieldName( "ID" ) ] );
                }
            }
            $res[] = $db->query( "DELETE FROM eZContact_ConsultationType WHERE ID='$this->ID'" );
            eZDB::finish( $res, $db );
        }
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $ret = false;

        if ( $id != "" )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $consulttype_array, "SELECT * FROM eZContact_ConsultationType WHERE ID='$id'" );
            $this->ID = $consulttype_array[ $db->fieldName( "ID" ) ];
            $this->Name = $consulttype_array[ $db->fieldName( "Name" ) ];
            $this->ListOrder = $consulttype_array[ $db->fieldName( "ListOrder" ) ];

            $ret = true;
        }
        return $ret;
    }

    /*!
      Sets the name of the consultation type.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the id of the consultation type.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the consultation type.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the number of external items using this item.
    */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ID ) as Count FROM eZContact_Consultation WHERE StateID='$this->ID'" );
        return $qry[ $db->fieldName( "Count" ) ];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ConsultationType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC",
                           array( "Limit" => 1 ) );
        $listorder = $qry[ $db->fieldName( "ListOrder" ) ];
        $listid = $qry[ $db->fieldName( "ID" ) ];
        $res[] = $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $res, $db );

    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ConsultationType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC",
                           array( "Limit" => 1 ) );
        $listorder = $qry[ $db->fieldName( "ListOrder" ) ];
        $listid = $qry[ $db->fieldName( "ID" ) ];
        $res[] = $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $res[] = $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      \static
      Finds all consultation types.
      Returns an array with eZConsultationType objects taken from the database.
    */
    static public function findTypes()
    {
        $qry_array = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT ID FROM eZContact_ConsultationType ORDER BY ListOrder" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultationType( $qry[ $db->fieldName( "ID" ) ] );
        }
        return $ret_array;
    }

    var $ID;
    var $Name;
    var $ListOrder;
}

?>