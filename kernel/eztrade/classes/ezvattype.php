<?php
// 
// $Id: ezvattype.php 7626 2001-10-02 07:59:01Z ce $
//
// Definition of eZVATType class
//
// Created on: <19-Feb-2001 13:41:53 bf>
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
//! This class handles the different VAT Types.
/*!
  \sa eZProduct
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "eztrade/classes/ezproducttype.php" );

class eZVATType
{
    /*!
      Constructs a new object.
    */
    function __construct( $id=-1 )
    {
        $this->VATValue = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores/updates the VATType in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        
        if ( !isset( $this->ID ) )
        {
            $timeStamp =& (new eZDateTime())->timeStamp( true );
            $db->lock( "eZTrade_VATType" );
            $nextID = $db->nextID( "eZTrade_VATType", "ID" );
            $ret[] = $db->query( "INSERT INTO eZTrade_VATType
                               ( ID,
                                 Name,
		                         VATValue,
		                         Created )
                               VALUES
		                       ( '$nextID',
                                 '$this->Name',
		                         '$this->VATValue',
		                         '$timeStamp' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_VATType SET
		                         Name='$this->Name',
		                         VATValue='$this->VATValue',
		                         Created=Created
                                 WHERE ID='$this->ID'" );
        }
        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Fetches the VAT Type from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $vat_array, "SELECT * FROM eZTrade_VATType WHERE ID='$id'" );
            
            if ( count( $vat_array ) > 1 )
            {
                die( "Error: VAT Types's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $vat_array ) == 1 )
            {
                $this->ID =& $vat_array[0][$db->fieldName("ID")];
                $this->Name =& $vat_array[0][$db->fieldName("Name")];
                $this->VATValue =& $vat_array[0][$db->fieldName("VATValue")];
            }
        }
    }

    /*!
      Retrieves all the VAT types from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $vat_array = array();
        
        $db->array_query( $vat_array, "SELECT ID FROM eZTrade_VATType ORDER BY Created" );
        
        for ( $i=0; $i<count($vat_array); $i++ )
        {
            $return_array[$i] = new eZVATType( $vat_array[$i][$db->fieldName("ID")], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a VAT Type from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $ret[] = $db->query( "DELETE FROM eZTrade_VATType WHERE ID='$this->ID'" );
        eZDB::finish( $ret, $db );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the vat type.
    */
    function name()
    {
        return $this->Name;
    }


    /*!
      Returns the vat value of the vat type.
    */
    function &value()
    {
        return $this->VATValue;
    }


    /*!
      Sets the name of the vat type.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the value of the vat type.
    */
    function setValue( $value )
    {
        $this->VATValue = $value;
    }

    var $ID;
    var $Name;
    var $VATValue;
    
}

?>