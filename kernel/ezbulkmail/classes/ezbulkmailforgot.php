<?php
// 
// $Id: ezbulkmailforgot.php 9818 2003-05-16 13:15:58Z br $
//
// Created on: <20-Apr-2001 13:32:11 fh>
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

//!! eZBulkMail
//! eZBulkMailForgot handles forgotten password. The user send a mail and get a hash returned, the user use the hash to get a new password.
/*!

  Example code:
  \code

  // Create a forgot object and store it in the database.
  $forgot = new eZBulkMailForgot( $userID );
  
  $forgot->setUserID( $userID );
  $forgot->store(); // The store function generate a hash.

  // Return the hash
  $hashVaraiable = $forgot->hash();

  // Check if the hash is legal.
  $forgot->check( $hashVariable );

  \endcode
  \sa eZUser eZUserGroup eZPermission eZBulkMailForgot
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezsession/classes/ezsession.php" );

class eZBulkMailForgot
{
    /*!
      Constructs a new eZBulkMailForgot object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZBulkMailForgot object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $this->Hash = md5( microTime() );
        $password = $db->escapeString( $this->Password );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBulkMail_Forgot" );
            $nextID = $db->nextID( "eZBulkMail_Forgot", "ID" );

            $result = $db->query( "INSERT INTO eZBulkMail_Forgot
                        ( ID, Mail, Password, Hash )
                        VALUES
                        ( '$nextID',
                          '$this->Mail',
                          '$password',
                          '$this->Hash' )
                        " );
			$this->ID = $nextID;
            $db->unlock();
        }
        else
        {
            $db->query( "UPDATE eZBulkMail_Forgot SET
                                 Mail='$this->Mail',
                                 Password='$password',
                                 Hash='$this->Hash'
                                 WHERE ID='$this->ID'" );
        }

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Deletes a eZBulkMailForgot object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        
        if ( isset( $this->ID ) )
        {
            $db->begin();
            $result = $db->query( "DELETE FROM eZBulkMail_Forgot WHERE ID='$this->ID'" );
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $forgot_array, "SELECT * FROM eZBulkMail_Forgot WHERE ID='$id'" );
            if ( count( $forgot_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $forgot_array ) == 1 )
            {
                $this->ID = $forgot_array[0][$db->fieldName( "ID" )];
                $this->Mail = $forgot_array[0][$db->fieldName( "Mail" )];
                $this->Password = $forgot_array[0][$db->fieldName( "Password" )];
                $this->Time = $forgot_array[0][$db->fieldName( "Time" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      \static
      Returns object with the email if it exists. If it does't and the address is valid a new object is created and returned.
     */
    function getByEmail( $email )
    {
        $db =& eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $forgot_array, "SELECT ID FROM eZBulkMail_Forgot WHERE Mail='$email'" );

        $return_value = false;
        if( count( $forgot_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $forgot_array ) == 1 )
        {
            $id = $forgot_array[0][$db->fieldName( "ID" )];
            $return_value = new eZBulkMailForgot( $id );
        }
        else
        {
            $is_valid = new eZBulkMailForgot();
            $is_valid->setMail( $email );
            $return_value = $is_valid;
        }
        return $return_value;
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
        $db->array_query( $forgot_array, "SELECT ID FROM eZBulkMail_Forgot WHERE Hash='$hash'" );

        if ( count( $forgot_array ) == 1 )
        {
            $ret = $forgot_array[0][$db->fieldName( "ID" )];
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
      Returns the mail ID.
    */
    function mail( )
    {
       return $this->Mail;
    }

    /*!
      Sets the login.
    */
    function setMail( $value )
    {
       $this->Mail = $value;
    }

    /*!
      Sets the password in cleartext.
     */
    function setPassword( $value )
    {
       $this->Password = $value;
    }

    /*!
      Returns the encryptet password.
     */
    function password()
    {
        return $this->Password;
    }
    
    /*!
      Retuns the hash
    */
    function hash()
    {
       return $this->Hash;
    }

    var $Mail;
    var $Password;
    var $ID;
    var $Hash;
    var $Time;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>