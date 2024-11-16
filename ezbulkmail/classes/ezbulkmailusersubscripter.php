<?php
//
// $Id: ezbulkmailusersubscripter.php 8074 2001-10-29 15:54:16Z fh $
//
// eZBulkMailUserSubscription class
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
//! eZBulkMailUserSubscription documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezuser/classes/ezuser.php" );

class eZBulkMailUserSubscripter
{
    /*!
    */
    function eZBulkMailUserSubscripter( $user, $categoryID=false )
    {
        if ( get_class ( $user ) )
            $this->setUser( $user );
        else if ( is_numeric ( $user ) )
            $this->setUser( new eZUser ( $user ) );

        $this->CategoryID = $categoryID;
    }

    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $this->User = $user;
        }
    }

    function user()
    {
        return $this->User;
    }

    /*!
      Returns the category id.
    */
    function categoryID()
    {
        return $this->CategoryID;
    }

    /*!
      Returns all the categories that this user is subscribed to as an array of eZBulkMailCategory objects. If you just want the ID's supply false as the first argument.
     */
    function subscriptions( $asObjects = true )
    {
        $db =& eZDB::globalDatabase();
        $final_result = array();
        $userID = $this->User->id();
        $db->array_query( $result_array, "SELECT CategoryID FROM eZBulkMail_UserCategoryLink WHERE UserID='$userID'" );
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

        $userID = $this->User->id();
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZBulkMail_UserCategoryLink" );
        $db->array_query( $check, "SELECT UserID
                                               FROM eZBulkMail_UserCategoryLink
                                               WHERE CategoryID='$categoryID'
                                               AND UserID='$userID'
                                               " );
        $result = false;
        if ( count( $check ) == 0 )
        {
            $result = $db->query( "INSERT INTO eZBulkMail_UserCategoryLink ( UserID, CategoryID ) VALUES ( '$userID', '$categoryID' )" );

            if ( $result == false )
                $db->rollback( );
            else
                $db->commit();

            $db->unlock();
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
        $userID = $this->User->id();
        if( is_a( $category, "eZBulkMailCategory" ) )
        {
            $categoryID = $category->id();
            $db->query( "DELETE FROM eZBulkMail_UserCategoryLink WHERE UserID='$userID' AND CategoryID='$categoryID'" );
        }
        else if( $category == true )
        {
            $db->query( "DELETE FROM eZBulkMail_UserCategoryLink WHERE UserID='$userID'" );
        }
    }

    /*!
      Returns the email of the given user.
     */
    function eMail()
    {
        $ret_val = "unknown";
        if( is_a( $this->User, "eZUser" ) )
        {
            $ret_val = $this->User->eMail();
        }
        return $ret_val;
    }

    /*!
      Unsubscribes this user from the given category. If the supplied argument is true, the user is unsubscibed from all categories.
     */
    function addDelay( $category, $delay )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZBulkMail_UserCategorySettings" );
        $userID = $this->User->id();

        $res[] = $db->query( "DELETE FROM eZBulkMail_UserCategorySettings WHERE CategoryID='$category'" );
        $nextID = $db->nextID( "eZBulkMail_UserCategorySettings", "ID" );
        $res[] = $db->query( "INSERT INTO  eZBulkMail_UserCategorySettings ( ID, CategoryID, UserID, Delay ) VALUES ( '$nextID','$category','$userID','$delay' )" );

        $db->unlock();
        if ( in_array( false, $res ) )
            $db->rollback();
        else
            $db->commit();
    }

    var $ID;
    var $CategoryID;
    var $User;
}

?>
