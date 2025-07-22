<?php
// 
// $Id: ezforgot.php 9818 2003-05-16 13:15:58Z br $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

//!! eZUser
//! eZForgot handles forgotten password. The user send a mail and get a hash returned, the user use the hash to get a new password.
/*!

  Example code:
  \code

  // Create a forgot object and store it in the database.
  $forgot = new eZForgot( $userID );
  
  $forgot->setUserID( $userID );
  $forgot->store(); // The store function generate a hash.

  // Return the hash
  $hashVaraiable = $forgot->hash();

  // Check if the hash is legal.
  $forgot->check( $hashVariable );

  \endcode
  \sa eZUser eZUserGroup eZPermission eZForgot
*/

// include_once( "classes/ezdb.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezsession/classes/ezsession.php" );

class eZForgot
{
    /*!
      Constructs a new eZForgot object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZForgot( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZForgot object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $this->Hash = md5( microTime() );

        $dbError = false;
        $db->begin( );        
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Forgot" );

            $nextID = $db->nextID( "eZUser_Forgot", "ID" );

            $timeStamp = (new eZDateTime())->timeStamp( true );

            $res = $db->query( "INSERT INTO eZUser_Forgot
                                 ( ID, UserID, Hash, Time )
                                 VALUES ( '$nextID',
                                          '$this->UserID',
                                          '$this->Hash',
                                          '$timeStamp' )" );
            
			$this->ID = $nextID;
        }

        $db->unlock();
        
        if ( $res == false )
            $db->rollback();
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZForgot object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_Forgot WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != -1 )
        {
            $db->array_query( $forgot_array, "SELECT * FROM eZUser_Forgot WHERE ID='$id'" );

            if ( count( $forgot_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $forgot_array ) == 1 )
            {
                $this->ID = $forgot_array[0][$db->fieldName("ID")];
                $this->Time = $forgot_array[0][$db->fieldName("Time")];
                $this->UserID = $forgot_array[0][$db->fieldName("UserID")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Chech if hash is true or not.
      Returnes false if unsuccessful.
    */
    function check( $hash )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $hash = $db->escapeString( $hash );
        
        $db->array_query( $forgot_array, "SELECT ID FROM eZUser_Forgot WHERE Hash='$hash'" );

        if ( count( $forgot_array ) == 1 )
        {
            $ret = $forgot_array[0][$db->fieldName("ID")];
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
      Returns the users login.
    */
    function userID( )
    {
       return $this->UserID;
    }

    /*!
      Sets the login.
    */
    function setUserID( $value )
    {
       $this->UserID = $value;
    }

    /*!
      Retuns the hash
    */
    function hash()
    {
       return $this->Hash;
    }

    var $UserID;
    var $ID;
    var $Hash;
}

?>