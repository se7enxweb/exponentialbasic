<?php
//
// $Id: ezorderoptionvalue.php 6773 2001-08-30 11:38:31Z ce $
//
// Definition of eZOrderOptionValue class
//
// Created on: <28-Sep-2000 16:40:01 bf>
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
//! eZOrderOptionValue handles selected options for the order items.
/*!

  \sa eZOrder eZOrderItem
*/

/*!TODO

*/

// include_once( "classes/ezdb.php" );

class eZOrderOptionValue
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
      Stores a order to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->OptionName = $db->escapeString( $this->OptionName );
        $this->ValueName = $db->escapeString( $this->ValueName );
        $this->RemoteID = $db->escapeString( $this->RemoteID );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_OrderOptionValue" );
            $nextID = $db->nextID( "eZTrade_OrderOptionValue", "ID");
            $ret[] = $db->query( "INSERT INTO eZTrade_OrderOptionValue
                               ( ID,
		                         OrderItemID,
		                         OptionName,
		                         RemoteID,
		                         ValueName )
                               VALUES
                               ( '$nextID',
		                         '$this->OrderItemID',
		                         '$this->OptionName',
		                         '$this->RemoteID',
		                         '$this->ValueName' )" );

            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_OrderOptionValue SET
		                         OrderItemID='$this->OrderItemID',
		                         OptionName='$this->OptionName',
		                         RemoteID='$this->RemoteID',
		                         ValueName='$this->ValueName'
                                 WHERE ID='$this->ID'
                                 " );
        }
        eZDB::finish( $ret, $db );
        return true;
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
            $db->array_query( $option_value_array, "SELECT * FROM eZTrade_OrderOptionValue WHERE ID='$id'" );
            if ( count( $option_value_array ) > 1 )
            {
                die( "Error: Option_value's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $option_value_array ) == 1 )
            {
                $this->ID =& $option_value_array[0][$db->fieldName("ID")];
                $this->OrderItemID =& $option_value_array[0][$db->fieldName("OrderItemID")];
                $this->OptionName =& $option_value_array[0][$db->fieldName("OptionName")];
                $this->ValueName =& $option_value_array[0][$db->fieldName("ValueName")];
                $this->RemoteID =& $option_value_array[0][$db->fieldName("RemoteID")];

                $ret = true;
            }
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
      Returns the option name.
    */
    function optionName( )
    {
       return $this->OptionName;
    }

    /*!
      Returns the value name.
    */
    function valueName( )
    {
       return $this->ValueName;
    }

    /*!
      Returns the value name.
    */
    function remoteID( )
    {
       return $this->RemoteID;
    }

    /*!
      Sets the order item.
    */
    function setOrderItem( $orderItem )
    {
       if ( is_a( $orderItem, "eZOrderItem" ) )
       {
           $this->OrderItemID = $orderItem->id();
       }
    }

    /*!
      Sets the option name.
    */
    function setOptionName( $value )
    {
       $this->OptionName = $value;
    }

    /*!
      Sets the value name.
    */
    function setValueName( $value )
    {
       $this->ValueName = $value;
    }

    /*!
      Sets the option name.
    */
    function setRemoteID( $value )
    {
       $this->RemoteID = $value;
    }


    var $ID;
    var $OrderItemID;
    var $OptionName;
    var $ValueName;
    var $RemoteID;

}

?>