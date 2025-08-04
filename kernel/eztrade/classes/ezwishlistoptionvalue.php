<?php
//
// $Id: ezwishlistoptionvalue.php 6318 2001-07-31 11:33:12Z jhe $
//
// Definition of eZWishListOptionValue class
//
// Created on: <21-Oct-2000 18:08:22 bf>
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
//! eZWishListOptionValue handles option values.
/*!


*/

// include_once( "classes/ezdb.php" );

// include_once( "eztrade/classes/ezwishlistitem.php" );
// include_once( "eztrade/classes/ezoption.php" );
// include_once( "eztrade/classes/ezoptionvalue.php" );

class eZWishListOptionValue
{
    /*!
      Constructs a new eZWishListOptionValue object.

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
      Stores a wishlist option value to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_WishListOptionValue" );
            $nextID = $db->nextID( "eZTrade_WishListOptionValue", "ID" );

            $res[] = $db->query( "INSERT INTO eZTrade_WishListOptionValue
                                  ( ID, WishListItemID, OptionID, OptionValueID )
                                  VALUES
                                  ( '$nextID',
		                            '$this->WishListItemID',
		                            '$this->OptionID',
		                            '$this->OptionValueID' )
                                  " );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTrade_WishListOptionValue SET
		                          WishListItemID='$this->WishListItemID',
		                          OptionID='$this->OptionID',
		                          OptionValueID='$this->OptionValueID'
                                  WHERE ID='$this->ID'
                                  " );
        }
        eZDB::finish( $res, $db );
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
            $db->array_query( $wishlist_array, "SELECT * FROM eZTrade_WishListOptionValue WHERE ID='$id'" );
            if ( count( $wishlist_array ) > 1 )
            {
                die( "Error: Wishlist's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $wishlist_array ) == 1 )
            {
                $this->ID =& $wishlist_array[0][$db->fieldName( "ID" )];
                $this->WishListItemID =& $wishlist_array[0][$db->fieldName( "WishListItemID" )];
                $this->OptionID =& $wishlist_array[0][$db->fieldName( "OptionID" )];
                $this->OptionValueID =& $wishlist_array[0][$db->fieldName( "OptionValueID" )];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns the object id.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Returns the wishlist item object.
    */
    function &wishlistItem()
    {
       return new eZWishListItem( $this->WishListItemID );
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
      Sets the wishlist item object id.
    */
    function setWishListItem( &$wishlistItem )
    {
       if ( is_a( $wishlistItem, "eZWishListItem" ) )
       {
           $this->WishListItemID = $wishlistItem->id();
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
    var $WishListItemID;
    var $OptionID;
    var $OptionValueID;
}

?>