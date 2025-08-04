<?php
//
// $Id: ezwishlist.php 8861 2002-01-03 15:39:06Z bf $
//
// Definition of eZWishList class
//
// Created on: <21-Oct-2000 18:06:52 bf>
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
//! eZWishList handles a shopping wishlist
/*!

  Example:
  \code

  // Create a new wishlist
  $wishlist = new eZWishList();
  $wishlist->setUser( $user );

  // Store the wishlist to the database
  $wishlist->store();

  // Fetch all wishlist items
  $items = $wishlist->items();

  // print contents of the wishlist if it exists
  if  ($items )
  {
      foreach ( $items as $item )
      {
          $product = $item->product();
          print( $product->name() . "<br>");
      }
  }

  \endcode
  \sa eZWishListItem eZProductCategory eZOption
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezwishlistitem.php" );

class eZWishList
{
    /*!
      Constructs a new eZWishList object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id="" )
    {
        $this->IsConnected = false;
        $this->IsPublic = 0;
        $this->IsBought = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a wishlist to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_WishList" );
            $nextID = $db->nextID( "eZTrade_WishList", "ID" );

            $res = $db->query( "INSERT INTO eZTrade_WishList
                                  ( ID, UserID, IsPublic )
                                  VALUES
                                  ( '$nextID',
                                    '$this->UserID',
                                    '$this->IsPublic' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTrade_WishList SET
		                         UserID='$this->UserID',
		                         IsPublic='$this->IsPublic'
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
            $db->array_query( $wishlist_array, "SELECT * FROM eZTrade_WishList WHERE ID='$id'" );
            if ( count( $wishlist_array ) > 1 )
            {
                die( "Error: WishList's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $wishlist_array ) == 1 )
            {
                $this->ID = $wishlist_array[0][$db->fieldName( "ID" )];
                $this->UserID = $wishlist_array[0][$db->fieldName( "UserID" )];
                $this->IsPublic = $wishlist_array[0][$db->fieldName( "IsPublic" )];
                $ret = $this;
            }
        }
        return $ret;
    }


    /*!
      Returns a eZWishList object.
    */
    function getByUser( $user  )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( is_a( $user, "eZUser" ) )
        {
            $sid = $user->id();
            $db->array_query( $wishlist_array, "SELECT * FROM
                                                    eZTrade_WishList
                                                    WHERE UserID='$sid'" );

            if ( count( $wishlist_array ) == 1 )
            {
                $ret = new eZWishList( $wishlist_array[0][$db->fieldName( "ID" )] );
            }

        }
        return $ret;
    }

    /*!
      Deletes a eZWishList object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $items = $this->items();

        if  ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }

        $res = $db->query( "DELETE FROM eZTrade_WishList WHERE ID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();


        return true;
    }

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }

    /*!
      Returns the owner of the wishlist as an eZUser object.
    */
    function &user()
    {
       $user = new eZUser( $this->UserID );

       return $user;
    }

    /*!
      Sets the user the wishlist belongs to.

      Return false if the applied argument is not and eZUser object.
    */
    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $this->UserID = $user->id();
        }
    }

    /*!
      Sets the wishlist to be public or private.
    */
    function setIsPublic( $value )
    {
       if ( $value == true )
           $this->IsPublic = 1;
       else
           $this->IsPublic = 0;
    }


    /*!
      Returns true if the wishlist is public, false if not.
    */
    function isPublic( )
    {
       $ret = 0;
       if ( $this->IsPublic == 1 )
       {
           $ret = true;
       }

       return $ret;
    }

    /*!
      Returns all the wishlist items in the wishlist.

      An array of eZWishListItem objects are retunred if successful, an empty array.
    */
    function &items( )
    {
       $ret = array();

       $db =& eZDB::globalDatabase();

       $db->array_query( $wishlist_array, "SELECT * FROM
                                                    eZTrade_WishListItem
                                                    WHERE WishListID='$this->ID'" );

       if ( count( $wishlist_array ) > 0 )
       {
           $return_array = array();
           foreach ( $wishlist_array as $item )
           {
               $ret[] = new eZWishListItem( $item[$db->fieldName( "ID" )] );
           }
       }

       return $ret;
    }

    /*!
      Empties out the wishlist.
    */
    function clear()
    {
       $db =& eZDB::globalDatabase();
       $db->begin();

       $items = $this->items();

       // delete the option values and wishlist items
       foreach ( $items as $item )
       {
           $itemID = $item->id();
           $res[] = $db->query( "DELETE FROM
                                eZTrade_WishListOptionValue
                                WHERE WishlistItemID='$itemID'" );

           $res[] = $db->query( "DELETE FROM
                                eZTrade_WishListItem
                                WHERE ID='$itemID'" );
       }
       if ( in_array( false, $res ) )
           $db->rollback( );
       else
           $db->commit();

       $this->delete();
    }

    /*!
      Searches the public wishlists and returns an array with
      eZWishList objects which matched the search.
    */
    function search( $queryText )
    {
       $ret = array();

       $db =& eZDB::globalDatabase();

       $db->array_query( $wishlist_array,
       "SELECT eZTrade_WishList.ID AS ID FROM eZTrade_WishList, eZUser_User
        WHERE eZTrade_WishList.UserID=eZUser_User.ID
        AND eZTrade_WishList.IsPublic=1
        AND ( eZUser_User.Email='$queryText'
        OR eZUser_User.FirstName='$queryText'
        OR eZUser_User.LastName='$queryText' )
        GROUP BY eZUser_User.ID, eZTrade_WishList.ID" );

       foreach ( $wishlist_array as $item )
       {
               $ret[] = new eZWishList( $item[$db->fieldName( "ID" )] );
       }

       return $ret;

    }

    var $ID;
    var $UserID;
    var $IsPublic;
    var $IsBought;
    var $IsConnected;
}
?>