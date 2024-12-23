<?php
// 
// $Id: ezproductcurrency.php 7848 2001-10-15 11:32:18Z ce $
//
// Definition of eZProductCurrency class
//
// Created on: <23-Feb-2001 16:47:27 bf>
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
//! This class handles alternative product currencies.
/*!
  
  \sa eZProduct
*/

// include_once( "classes/ezdb.php" );

class eZProductCurrency
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
      Stores/updates the alternative currency in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        $this->Sign = $db->escapeString( $this->Sign );

        if ( $this->Value == "" )
        {
            $this->Value = 1;
        }
        
        if ( !isset( $this->ID ) )
        {
            $timeStamp =& (new eZDateTime())->timeStamp( true );
            $db->lock( "eZTrade_AlternativeCurrency" );
            $nextID = $db->nextID( "eZTrade_AlternativeCurrency", "ID" );
                        
            $res[] = $db->query( "INSERT INTO eZTrade_AlternativeCurrency
                       ( ID,
		                 Name,
		                 Sign,
		                 Value,
		                 Created,
		                 PrefixSign )
                       VALUES
		               ( '$nextID',
                         '$this->Name',
		                 '$this->Sign',
		                 '$this->Value',
		                 '$timeStamp',
		                 '$this->PrefixSign' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_AlternativeCurrency SET
		                 Name='$this->Name',
		                 Sign='$this->Sign',
		                 Value='$this->Value',
		                 Created=Created,
		                 PrefixSign='$this->PrefixSign'
                         WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the alternative currency from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $currency_array, "SELECT * FROM eZTrade_AlternativeCurrency WHERE ID='$id'" );
            
            if ( count( $currency_array ) > 1 )
            {
                die( "Error: Product currencies with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $currency_array ) == 1 )
            {
                $this->ID =& $currency_array[0][$db->fieldName( "ID" )];
                $this->Name =& $currency_array[0][$db->fieldName( "Name" )];
                $this->Value =& $currency_array[0][$db->fieldName( "Value" )];
                $this->Sign =& $currency_array[0][$db->fieldName( "Sign" )];
                $this->PrefixSign =& $currency_array[0][$db->fieldName( "PrefixSign" )];
            }
        }
    }

    /*!
      Retrieves all the alternative currencies from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $currency_array = array();
        
        $db->array_query( $currency_array, "SELECT ID FROM eZTrade_AlternativeCurrency ORDER BY Created" );
        
        for ( $i=0; $i < count($currency_array); $i++ )
        {
            $return_array[$i] = new eZProductCurrency( $currency_array[$i][$db->fieldName( "ID" )], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a alternative currency from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZTrade_AlternativeCurrency WHERE ID='$this->ID'" );

        eZDB::finish( $res, $db );

        
    }

    /*!
      Returns the object ID to the currency.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the currency.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the value of the currency.
    */
    function value()
    {
        return number_format( $this->Value, 4, ".", "" );
    }

    /*!
      Returns the sign of the currency.
    */
    function sign()
    {
        return $this->Sign;
    }

    /*!
      Returns true if the sign should prefix the value, false if not.
    */
    function prefixSign()
    {
        if ( $this->PrefixSign == 1 )
            return true;
        else
            return false;
    }
    
    /*!
      Sets the name of the currency.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the value of the currency.
    */
    function setValue( $value )
    {
        $newValue = number_format( $value, 4, ".", "" );
        $value = (double) $newValue;
        $this->Value = $value;
    }


    /*!
      Sets the sign of the currency.
    */
    function setSign( $value )
    {
        $this->Sign = $value;
    }

    /*!
      Set to true if the sign should prefix theb value, false if not.
    */
    function setPrefixSign( $value )
    {
        if ( $value == true )            
            $this->PrefixSign = 1;
        else
            $this->PrefixSign = 0;
    }
    
    var $ID;
    var $Name;
    var $Sign;
    var $PrefixSign;
    var $Value;
}

?>