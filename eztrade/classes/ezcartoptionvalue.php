<?php
//
// $Id: ezcartoptionvalue.php 6856 2001-09-03 12:27:23Z ce $
//
// Definition of eZCartOptionValue class
//
// Created on: <27-Sep-2000 15:19:13 bf>
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
//! eZCartOptionValue handles option values.
/*!


*/

// include_once( "classes/ezdb.php" );

// include_once( "eztrade/classes/ezcartitem.php" );
// include_once( "eztrade/classes/ezoption.php" );
// include_once( "eztrade/classes/ezoptionvalue.php" );


class eZCartOptionValue
{
    /*!
      Constructs a new eZCartOptionValue object.

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
        else
        {
            $this->Count = 1;
        }
    }

    /*!
      Stores a cart option value to the database.
    */
    function store()
    {
       $db =& eZDB::globalDatabase();
       $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_CartOptionValue" );
            $nextID = $db->nextID( "eZTrade_CartOptionValue", "ID" );

            $res = $db->query( "INSERT INTO eZTrade_CartOptionValue
                             ( ID, CartItemID, OptionID, RemoteID, OptionValueID, Count )
                             VALUES ( '$nextID','$this->CartItemID','$this->OptionID','$this->RemoteID','$this->OptionValueID', '$this->Count' )
                             " );
            $db->unlock();

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_CartOptionValue SET
		                         CartItemID='$this->CartItemID',
		                         OptionID='$this->OptionID',
		                         OptionValueID='$this->OptionValueID',
		                         Count='$this->Count'
                                 WHERE ID='$this->ID'
                                 " );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

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
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_CartOptionValue WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID =& $cart_array[0][$db->fieldName( "ID" )];
                $this->CartItemID =& $cart_array[0][$db->fieldName( "CartItemID" )];
                $this->OptionID =& $cart_array[0][$db->fieldName( "OptionID" )];
                $this->RemoteID =& $cart_array[0][$db->fieldName( "RemoteID" )];
                $this->OptionValueID =& $cart_array[0][$db->fieldName( "OptionValueID" )];
                $this->Count =& $cart_array[0][$db->fieldName( "Count" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Deletes a eZCartOptionValue object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZTrade_CartOptionValue WHERE ID='$this->ID'" );

        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }


    /*!
      Returns the object id.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Returns the object remoteID.
    */
    function remoteID( )
    {
        return $this->RemoteID;
    }

    /*!
      Returns the cart item object.
    */
    function &cartItem()
    {
       return new eZCartItem( $this->CartItemID );
    }

    /*!
      Returns the option object.
    */
    function &option()
    {
       return new eZOption( $this->OptionID );
    }

    /*!
      Returns the option value object.
    */
    function &optionValue()
    {
       return new eZOptionValue( $this->OptionValueID );
    }

    /*!
      Returns the count value object.
    */
    function count()
    {
       return $this->Count;
    }

    /*!
      Sets the cart item object id.
    */
    function setCartItem( &$cartItem )
    {
       if ( is_a( $cartItem, "eZCartItem" ) )
       {
           $this->CartItemID = $cartItem->id();
       }
    }

    /*!
      Sets the option object id.
    */
    function setOption( &$option )
    {
       if ( is_a( $option, "eZOption" ) )
       {
           $this->OptionID = $option->id();
       }
    }

    /*!
      Sets the option object id.
    */
    function setRemoteID( $value )
    {
        $this->RemoteID = $value;
    }

    /*!
      Sets the count object id.
    */
    function setCount( $value )
    {
        $this->Count = $value;
    }

    /*!
      Sets the option value object id.
    */
    function setOptionValue( &$optionValue )
    {
       if ( is_a( $optionValue, "eZOptionValue" ) )
       {
           $this->OptionValueID = $optionValue->id();
       }
    }

    var $ID;
    var $CartItemID;
    var $OptionID;
    var $OptionValueID;
    var $RemoteID;
    var $Count;
}

?>