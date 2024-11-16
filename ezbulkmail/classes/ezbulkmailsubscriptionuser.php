<?php
//
// $Id: ezbulkmailsubscriptionuser.php 7100 2001-09-08 12:16:19Z ce $
//
// eZBulkMailSubscriptionUser class
//
// Created on: <17-Apr-2001 13:21:53 fh>
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
//! eZBulkMailSubscriptionUser documentation.
/*!

  Example code:
  \code
  \endcode

*/

class eZBulkMailSubscriptionUser
{
    /*!
    */
    function __construct( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
            $this->CategoryID = $categoryID;
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $password = md5( $this->Password );

        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZBulkMail_SubscriptionUser" );
            $nextID = $db->nextID( "eZBulkMail_Mail", "ID" );

            $result = $db->query( "INSERT INTO eZBulkMail_SubscriptionUser ( ID, EMail, Password )
                                 VALUES
                                 (
                                  '$nextID',
                                  '$this->UserID'
                                 )
                                 " );
			if( $result )
                $this->ID = $nextID;
        }
        else
        {
            $result = $db->query( "UPDATE eZBulkMail_SubscriptionUser SET
                                 UserID='$this->UserID'
                                 WHERE ID='$this->ID'" );
        }

        $db->unlock();

        if ( $result == false )
            $db->rollback( );
        else
            $db->commit();
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;

        $db->begin();
        // delete from BulkMailCategoryLink
        $results[] = $db->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE UserID='$id'" );
        // delete actual group entry
        $results[] = $db->query( "DELETE FROM eZBulkMail_SubscriptionUser WHERE ID='$id'" );
        $commit = true;
        foreach(  $results as $result )
        {
            if ( $result == false )
                $commit = false;
        }
        if ( $commit == false )
            $db->rollback( );
        else
            $db->commit();

    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "-1" )
        {
            $db->array_query( $address_array, "SELECT * FROM eZBulkMail_SubscriptionUser WHERE ID='$id'" );
            if( count( $address_array ) > 1 )
            {
                die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $address_array ) == 1 )
            {
                $this->ID = $address_array[0][$db->fieldName( "ID" ) ];
                $this->EMail = $address_array[0][$db->fieldName("EMail" ) ];
                $this->Password = $address_array[0][$db->fieldName("Password" ) ];
            }
        }
    }

    /*!
      \static
      Returns object with the email if it exists. If it doesn't and the address is valid a new object is created and returned.
     */
    function getByEmail( $email )
    {
        $db =& eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $address_array, "SELECT ID FROM eZBulkMail_SubscriptionUser WHERE EMail='$email'" );


        $return_value = false;
        if( count( $address_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $address_array ) == 1 )
        {

            $id = $address_array[0][$db->fieldName("ID")];
            $return_value = new eZBulkMailSubscriptionUser( $id );
        }
        else
        {
            $is_valid = new eZBulkMailSubscriptionUser();
            if( $is_valid->setEMail( $email ) )
            {
//                $is_valid->store();
                $return_value = $is_valid;
            }
        }
        return $return_value;
    }

    /*!
      Returns true if the address exists. False if not.
     */
    function addressExists( $email )
    {
        $db = eZDB::globalDatabase();
        $email = addslashes( $email );
        $db->array_query( $address_array, "SELECT ID FROM eZBulkMail_SubscriptionUser WHERE EMail='$email'" );

        $return_value = false;
        if( count( $address_array ) > 1 )
        {
            die( "Error: Subscription addresses with the same ID was found in the database. This shouldn't happen." );
        }
        else if( count( $address_array ) == 1 )
        {
            $return_value = true;
        }
        else
        {
            $return_value = false;
        }
        return $return_value;
    }

    /*!
      Returns the email address of this user.
    */
    function eMail()
    {
        return $this->EMail;
    }

    /*!
      Returns the email address of this user.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the category id.
    */
    function categoryID()
    {
        return $this->CategoryID;
    }

    /*!
      Sets the email address.
      Returns true if the address is set and valid, false othervise.
     */
    function setEMail( $value )
    {
        $pos = ( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $value) );
        if( $pos == true )
        {
            $this->EMail = $value;
            return true;
        }
        return false;
    }

    /*!
      Sets an allready encryptet password.
     */
    function setEncryptetPassword( $value )
    {
        $this->Password = $value;
    }

    /*!
      Returns all the categories that this user is subscribed to as an array of eZBulkMailCategory objects. If you just want the ID's supply false as the first argument.
     */
    function subscriptions( $asObjects = true )
    {
        $db =& eZDB::globalDatabase();
        $final_result = array();
        $db->array_query( $result_array, "SELECT CategoryID FROM eZBulkMail_SubscriptionLink WHERE UserID='$this->ID'" );
        if( count( $result_array ) > 0 )
        {
            foreach( $result_array as $result )
                $final_result[] = $asObjects ? new eZBulkMailCategory( $result[$db->fieldName( "CategoryID" )] ) : $result[$db->fieldName( "CategoryID" )];
        }
        return $final_result;
    }

    /*!
      Subscribes this address to a category.
     */
    function subscribe( $categoryID )
    {
        if( is_a( $categoryID, "eZBulkMailCategory" ) )
            $categoryID = $categoryID->id();

        $db = eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZBulkMail_SubscriptionLink" );
        $db->array_query( $check, "SELECT UserID
                                               FROM eZBulkMail_SubscriptionLink
                                               WHERE CategoryID='$categoryID'
                                               AND UserID='$this->ID'
                                               " );
        $result = false;
        if ( count ( $check ) == 0 )
        {
            $result = $db->query( "INSERT INTO eZBulkMail_SubscriptionLink ( UserID, CategoryID ) VALUES ( '$this->ID', '$categoryID' )" );

            $db->unlock();
            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();
            return true;
        }
        else
        {
            $db->rollback( );
            $db->unlock();
            return false;
        }
    }

    /*!
      Unsubscribes this user from the given category. If the supplied argument is true, the user is unsubscibed from all categories.
     */
    function unsubscribe( $category )
    {
        $db =& eZDB::globalDatabase();
        if( is_a( $category, "eZBulkMailCategory" ) )
        {
            $categoryID = $category->id();
            $db->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE UserID='$categoryID'" );
        }
        else if( $category == true )
        {
            $db->query( "DELETE FROM eZBulkMail_SubscriptionLink WHERE UserID='$this->ID'" );
        }
    }

    /*!
      Unsubscribes this user from the given category. If the supplied argument is true, the user is unsubscibed from all categories.
     */
    function addDelay( $category, $delay )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZBulkMail_SubscriptionCategorySettings" );

        $res[] = $db->query( "DELETE FROM eZBulkMail_SubscriptionCategorySettings WHERE CategoryID='$category'" );
        $nextID = $db->nextID( "eZBulkMail_SubscriptionCategorySettings", "ID" );
        $res[] = $db->query( "INSERT INTO  eZBulkMail_SubscriptionCategorySettings ( ID, CategoryID, UserID, Delay ) VALUES ( '$nextID','$category','$this->ID','$delay' )" );

        $db->unlock();
        if ( in_array( false, $res ) )
            $db->rollback();
        else
            $db->commit();
    }

    /*!
      Checks if the email and passwords have a match. Returns true if yes, and false if not.
    */
    function validate( $email, $password )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $md5 = md5( $password );
        $db->array_query( $subscription_array, "SELECT * FROM eZBulkMail_SubscriptionUser
                                                    WHERE EMail='$email'
                                                    AND Password='$md5'" );
        if ( count( $subscription_array ) == 1 )
            $ret = true;

        return $ret;
    }

    var $ID;
    var $EMail;
    var $Password;
    var $CategoryID;
}

?>