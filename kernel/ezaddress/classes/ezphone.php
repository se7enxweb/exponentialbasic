<?php
//
// $Id: ezphone.php 6204 2001-07-19 12:06:56Z jakobn $
//
// Definition of eZAddressType class
//
// Created on: <26-Jun-2001 13:40:19 ce>
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

//!! eZAddress
//!
/*!

*/

// include_once( "classes/ezdb.php" );
// include_once( "ezaddress/classes/ezphonetype.php" );

class eZPhone
{
    /*

    */
    function __construct( $id = "" )
    {
        if ( !empty( $id ) )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*
      Lagrer et telefonnummer link i databasen.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAddress_Phone" );
			$this->ID = $db->nextID( "eZAddress_Phone", "ID" );
            $res[] = $db->query( "INSERT INTO eZAddress_Phone
                                  (ID, Number, PhoneTypeID)
                                  VALUES
                                  ('$this->ID',
                                   '$this->Number',
                                   '$this->PhoneTypeID')" );
            $db->unlock();
            $ret = true;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAddress_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' WHERE ID='$this->ID' " );

            $ret = true;
        }
        eZDB::finish( $res, $db );
        return $ret;
    }

    /*
      Sletter.
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$id' " );
        eZDB::finish( $res, $db );
    }

    /*
      Henter ut telefonnummer med ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $phone_array, "SELECT * FROM eZAddress_Phone WHERE ID='$id'" );
            if ( count( $phone_array ) > 1 )
            {
                die( "Feil: Flere telefonnummer med samme ID funnet i database, dette skal ikke v�re mulig. " );
            }
            else if ( count( $phone_array ) == 1 )
            {
                $this->ID = $phone_array[ 0 ][ $db->fieldName( "ID" ) ];
                $this->Number = $phone_array[ 0 ][ $db->fieldName( "Number" ) ];
                $this->PhoneTypeID = $phone_array[ 0 ][ $db->fieldName( "PhoneTypeID" ) ];
            }
        }
    }

    function setNumber( $value )
    {
        $this->Number = $value;
    }

    function setPhoneTypeID( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }

        if( is_a( $value, "eZPhoneType" ) )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    function setPhoneType( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }

        if( is_a( $value, "eZPhoneType" ) )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    function setID( $value )
    {
        $this->ID = $value;
    }

    function number( )
    {
        return $this->Number;
    }

    function phoneTypeID( )
    {
        return $this->PhoneTypeID;
    }

    function phoneType( )
    {
        $phoneType = new eZPhoneType( $this->PhoneTypeID );
        return $phoneType;
    }

    function id( )
    {
        return $this->ID;
    }

    var $ID;
    var $Number;
    var $PhoneTypeID;
}

?>