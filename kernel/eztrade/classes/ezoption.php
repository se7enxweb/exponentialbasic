<?php
//
// $Id: ezoption.php 6849 2001-09-03 11:13:38Z ce $
//
// Definition of eZOption class
//
// Created on: <12-Sep-2000 15:01:53 bf>
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
//! The eZOption class handles options for products and product categories.
/*!
  eZOption class handles product options. The class has functions for storing
  to the database and fetching from the database.


  You can add option values to options, and get the values for a option.

  Example code:
  \code
  // Create a new option object
  $option = new eZOption();

  $option->setName( "Color" );
  $option->setDescription( "The color of the product" );

  // Store the option to the database
  $option->store();

  // Fetch all the options from the database.
  $optionArray = $option->getAll();

  //print out all the options.
  foreach ( $optionArray as $optionItem )
  {
    print( "Option: " . $optionItem->name() . "<br>" );
  }

  // Create some values
  $value1 = new eZOptionValue();
  $value1->setName( "Red" );

  $value2 = new eZOptionValue();
  $value2->setName( "Green" );

  $value3 = new eZOptionValue();
  $value3->setName( "Blue" );

  // Add them to the option
  $option->addValue( $value1 );
  $option->addValue( $value2 );
  $option->addValue( $value3 );

  \endcode
  \sa eZProductCategory eZOptionValue
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezoptionvalue.php" );

class eZOption
{
    /*!
      Constructs a new eZOption object. Retrieves the data from the database
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
      Stores a eZOption object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Name = $db->escapeString( $this->Name );
        $this->Description = $db->escapeString( $this->Description );

        if ( !is_numeric( $this->ID ) )
        {
            $db->lock( "eZTrade_Option" );
            $nextID = $db->nextID( "eZTrade_Option", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_Option
                               ( ID,
		                         Name,
                                 Description,
                                 RemoteID )
                               VALUES
                               ( '$nextID',
                                 '$this->Name',
                                 '$this->Description',
                                  '$this->RemoteID')" );
            $db->unlock();
			$this->ID = $nextID;

        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_Option SET
		                         Name='$this->Name',
                                 Description='$this->Description', RemoteID='$this->RemoteID'  WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the option object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $option_array, "SELECT * FROM eZTrade_Option WHERE ID='$id'" );

            if ( count( $option_array ) > 1 )
            {
                die( "Error: Option's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $option_array ) == 1 )
            {
                $this->ID =& $option_array[0][$db->fieldName( "ID" )];
                $this->Name =& $option_array[0][$db->fieldName( "Name" )];
                $this->Description =& $option_array[0][$db->fieldName( "Description" )];
                $this->RemoteID =& $option_array[0][$db->fieldName( "RemoteID" )];
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
        $option_array = array();

        $db->array_query( $option_array, "SELECT ID FROM eZTrade_Option ORDER BY Name" );

        for ( $i=0; $i < count($option_array); $i++ )
        {
            $return_array[$i] = new eZOption( $option_array[$i][$db->fieldName( "ID" )], 0 );
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

        $db->array_query( $option_array, "SELECT ID FROM eZTrade_OptionValue WHERE OptionID='$this->ID'" );

        foreach( $option_array as $option_value )
        {
            $option_id = $option_value[$db->fieldName( "ID" )];
            $res[] = $db->query( "DELETE FROM eZTrade_OptionValueContent WHERE ValueID='$option_id'" );
        }
        $res[] = $db->query( "DELETE FROM eZTrade_OptionValue WHERE OptionID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_ProductOptionLink WHERE OptionID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_Option WHERE ID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_ProductPriceLink WHERE OptionID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZTrade_OptionValueHeader WHERE OptionID='$this->ID'" );

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
      Returns the name of the option.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the option description.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the option remote id.
    */
    function remoteID()
    {
        return $this->RemoteID;
    }

    /*!
      Returns all the values to the current option.

      The values are returned as an array of eZOptionValue objects.
    */
    function &values( )
    {
        $value = new eZOptionValue();
        return $value->getByOption( $this );
    }

    /*!
      Sets the name of the option.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the option.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the remote id of the option.
    */
    function setRemoteID( $value )
    {
        $this->RemoteID = $value;
    }


    function &descriptionHeaders( $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
        $db->array_query( $qry_array,
                          "SELECT Name FROM eZTrade_OptionValueHeader
                           WHERE OptionID='$id' ORDER BY Placement ASC" );
        $ret = array();
        foreach( $qry_array as $row )
        {
            $ret[] = $row[$db->fieldName( "Name" )];
        }
        return $ret;
    }

    function removeHeaders( $id = false )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !$id )
            $id = $this->ID;
        $res[] = $db->query( "DELETE FROM eZTrade_OptionValueHeader WHERE OptionID='$id'" );

        eZDB::finish( $res, $db );
    }

    function addHeader( $header, $id = false )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();


        if ( !$id )
            $id = $this->ID;
        if ( !is_array( $header ) )
            $header = array( $header );

        $db->array_query( $qry_array, "SELECT Placement FROM eZTrade_OptionValueHeader
                                       WHERE OptionID='$id' ORDER BY Placement DESC",
                                       array( "Limit" => 1, "Offset" => 0 ) );

        $placement = count( $qry_array ) == 0 ? 1 : $qry_array[0][$db->fieldName( "Placement" )] + 1;

        $db->lock( "eZTrade_OptionValueHeader" );

        foreach( $header as $header_val )
        {
            $header_val = $db->escapeString( $header_val );
            $nextID = $db->nextID( "eZTrade_OptionValueHeader", "ID" );
            $res[] = $db->query( "INSERT INTO eZTrade_OptionValueHeader
                         ( ID,
                           Name,
                           OptionID,
                           Placement )
                         VALUES
                         ( '$nextID',
                           '$header_val',
                           '$id',
                           '$placement' )" );
            $placement++;
        }
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    /*!
      Removes all values connected to this option.
    */
    function removeValues( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT ID FROM eZTrade_OptionValue WHERE OptionID='$id'" );
        foreach( $qry_array as $row )
        {
            $row_id = $row[$db->fieldName( "ID" )];
            eZOptionValue::delete( $row_id );
        }
    }

    /*!
      Removes all values connected to this option.
    */
    function removeValue( $id )
    {
        if ( is_a( $id, "eZOptionValue" ) )
            $id = $id->id();
        eZOptionValue::delete( $id );
    }

    /*!
      Adds a value to the option. The value must be of eZOptionValue type.

      NOTE: It stores the value object to the database.
    */
    function addValue( &$value )
    {
        if ( is_a( $value, "eZOptionValue" ) )
        {
            $value->setOptionID( $this->ID );
            $value->store();
        }
    }

    function getByRemoteID( $id )
    {
        $db =& eZDB::globalDatabase();

        $value = false;

        $db->array_query( $res, "SELECT ID FROM
                                            eZTrade_Option
                                            WHERE RemoteID='$id'" );
        if ( count( $res ) == 1 )
        {
            $value = new eZOption( $res[0][$db->fieldName("ID")] );
        }

        return $value;
    }

    var $ID;
    var $Name;
    var $Description;
    var $RemoteID;

}

?>