<?php
//
// $Id: ezusergroup.php 8704 2001-12-07 17:07:01Z br $
//
// Definition of eZCompany class
//
// Created on: <26-Sep-2000 18:45:40 bf>
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
//! eZUserGroup handles user groups.
/*!

  Example code:
  \code
  // create a new eZUsergroup object and set some values.
  $group = new eZUserGroup();
  $group->setName( "Administrator" );
  $group->setDescription( "Has root access" );

  // store it to the database.
  $group->store();

  // fetch user group with object id == 1
  $group->get( 1 );

  // fetch a user with object id == 42
  $user = new eZUser();
  $user->get( 42 );

  // add a user to the user group.
  if ( $group->adduser( $user ) )
  {
      print( "User added to group" );
  }
  else
  {
      print( "Error: count not add user." );
  }
  \endcode
  \sa eZUser eZPermission eZModule eZForgot
*/

/*!TODO
*/
class eZUserGroup
{

    /*!
      Constructs a new eZUser object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1, $fetch = true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores or updates a eZUserGroup object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZUser_Group" );

            $nextID = $db->nextID( "eZUser_Group", "ID" );

            $db->query( "INSERT INTO eZUser_Group
                         (ID, Name, Description, SessionTimeout, IsRoot, GroupURL)
                         VALUES
                         ('$nextID', '$name', '$description', '$this->SessionTimeout', '$this->IsRoot', '$this->GroupURL')" );

            $this->ID = $nextID;

        }
        else
        {
            $db->query( "UPDATE eZUser_Group SET
                                 Name='$name',
                                 Description='$description',
                                 SessionTimeout='$this->SessionTimeout',
                                 IsRoot='$this->IsRoot',
                                 GroupURL='$this->GroupURL'
                                 WHERE ID='$this->ID'" );
        }

        $db->unlock();

        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZUserGroup object from the database.
    */
    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        if ( isSet( $id ) )
        {
            $db->query( "DELETE FROM eZUser_UserGroupLink WHERE GroupID='$id'" );
            $db->query( "DELETE FROM eZUser_GroupPermissionLink WHERE GroupID='$id'" );
            $db->query( "DELETE FROM eZUser_Group WHERE ID='$id'" );
        }

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $user_group_array, "SELECT * FROM eZUser_Group WHERE ID='$id'",
                              array( "Offset" => 0, "Limit" => 1 ) );
            if ( count( $user_group_array ) == 1 )
            {
                $this->fill( $user_group_array[0] );
            }
        }
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$user_group_array )
    {
        $db =& eZDB::globalDatabase();

        $this->ID = $user_group_array[$db->fieldName( "ID" )];
        $this->Name = $user_group_array[$db->fieldName( "Name" )];
        $this->Description = $user_group_array[$db->fieldName( "Description" )];
        $this->GroupURL = $user_group_array[$db->fieldName( "GroupURL" )];
        $this->SessionTimeout = $user_group_array[$db->fieldName("SessionTimeout")];
        $this->IsRoot = $user_group_array[$db->fieldName("IsRoot")];
    }

    /*!
      \static
      Returns every user group from the database. The result is returned as an
      array of eZUserGroup objects.
    */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $group_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID,Name";

        $db->array_query( $group_array, "SELECT $select FROM eZUser_Group ORDER By Name" );

        if ( $as_object )
        {
            for ( $i = 0; $i < count( $group_array ); $i++ )
            {
                $return_array[$i] = new eZUserGroup( $group_array[$i] );
            }
        }
        else
        {
            for ( $i = 0; $i < count( $group_array ); $i++ )
            {
                $return_array[$i] =& $group_array[$db->fieldName( "ID" )];
            }
        }

        return $return_array;
    }

    /*!
      \static
      Fetches every group the user is a member of.

      The result is returned as an array of eZUserGroup object. An empty array
      is returned if there are none.
    */
    function getByUser( $user )
    {
        $return_array = array();

        if ( is_a( $user, "eZUser" ) )
        {
            $db =& eZDB::globalDatabase();

            $group_array = array();

            $userID = $user->id();

            $db->array_query( $group_array, "SELECT GroupID FROM eZUser_UserGroupLink WHERE UserID='$userID'" );

            for ( $i = 0; $i < count( $group_array ); $i++ )
            {
                $return_array[$i] = new eZUserGroup( $group_array[$i][$db->fieldName( "GroupID" )], 0 );
            }
        }
        return $return_array;
    }

    /*!
      Returns true if the user $user is a member of the group
      $user is of type eZUser
     */
    function isMember( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userList = $this->users( );
            if ( count( $userList ) > 0 )
            {
                foreach ( $userList as $usr )
                {
                    if ( $user->id() == $usr->id() )
                        return true;
                }
            }
        }
        return false;
    }

    /*!
      Returns the object ID to the user group.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the users who is a member of the eZUserGroup object.
    */
    function users( $GroupID = false, $order = "Login", $search = false )
    {
        switch ( $order )
        {
            case "name" :
            {
                $orderBy = "U.LastName, U.FirstName";
            }
            break;

            case "lastname" :
            {
                $orderBy = "U.LastName";
            }
            break;

            case "firstname" :
            {
                $orderBy = "U.FirstName";
            }
            break;

            case "email" :
            {
                $orderBy = "U.Email";
            }
            break;

            default :
                $orderBy = "U.Login";
            break;
        }

        if ( is_array( $GroupID ) )
        {
            $first = true;
            foreach ( $GroupID as $item )
            {
                if ( $first )
                    $userSQL = "UGL.GroupID='$item' ";
                else
                    $userSQL .= "OR UGL.GroupID='$item' ";
                $first = false;
            }
        }
        else if ( !is_numeric( $GroupID ) )
        {
            $GroupID = $this->ID;
            $userSQL = "UGL.GroupID='$GroupID'";
        }
        else
        {
            $userSQL = "UGL.GroupID='$GroupID'";
        }


        $ret = array();
        $db =& eZDB::globalDatabase();

        if ( $search )
        {
            $query = new eZQuery( array( "U.FirstName", "U.LastName",
                                         "U.Login", "U.Email" ), $search );


            $db->array_query( $user_array, "SELECT  UGL.UserID FROM eZUser_UserGroupLink AS UGL,
                                                               eZUser_User AS U
                                                   WHERE ( $userSQL ) AND UGL.UserID=U.ID
                                                   AND ( " . $query->buildQuery() . " )
                                                   GROUP BY UGL.UserID, U.Login
                                                   ORDER By $orderBy" );
        }
        else
        {
            $db->array_query( $user_array, "SELECT  UGL.UserID FROM eZUser_UserGroupLink AS UGL,
                                                               eZUser_User AS U
                                                   WHERE ( $userSQL ) AND UGL.UserID=U.ID
                                                   GROUP BY UGL.UserID, U.Login
                                                   ORDER By $orderBy" );

        }

        foreach ( $user_array as $user )
        {
            $ret[] = new eZUser( $user[$db->fieldName( "UserID" )] );
        }

        return $ret;
    }

    /*!
      Returns the email addresses of the users that are members of this group
     */
    function userEmails()
    {
        $db =& eZDB::globalDatabase();
        $mail_array = array();
        $db->array_query( $mail_array, "SELECT Email from eZUser_UserGroupLink, eZUser_User WHERE eZUser_User.ID=eZUser_UserGroupLink.UserID AND eZUser_UserGroupLink.GroupID='$this->ID'",
        0, -1, "Email" );
        return $mail_array;
    }

    /*!
      Returns true if this group has root permissions.
     */
    function isRoot()
    {
        return $this->IsRoot;
    }

    /*!
      Sets if this group has root permissions.
     */
    function setIsRoot( $value )
    {
        $this->IsRoot = $value;
    }

    /*!
      Returns the user group name.
    */
    function name( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the user group description.
    */
    function description( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Description );
        return $this->Description;
    }

    /*!
      Returns the user group url
    */
    function groupURL()
    {
        return $this->GroupURL;
    }

    /*!
      Returns the session timeout value in minutes.
    */
    function sessionTimeout()
    {
        return $this->SessionTimeout;
    }

    /*!
      Sets the name of the user group.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the description of the user group.
    */
    function setDescription( $value )
    {
       $this->Description = $value;
    }

    function setGroupURL( $url )
    {
        $this->GroupURL = $url;
    }

    /*!
      Sets the session timeout value, the value is in minutes.
    */
    function setSessionTimeout( $value )
    {
       $this->SessionTimeout = $value;

       setType( $this->SessionTimeout, "integer" );
    }

    /*!
      Adds a user to the current user group.

      Returns true if successful, false if not.
    */
    function addUser( $user )
    {
       $ret = false;

       if ( is_a( $user, "eZUser" ) )
       {
           $db =& eZDB::globalDatabase();

           $dbError = false;
           $db->begin( );

           $userID = $user->id();
           $db->lock( "eZUser_UserGroupLink" );
           $nextID = $db->nextID( "eZUser_UserGroupLink", "ID" );

//             if ( $this->ID > 1 )
           {
               $res = $db->query( "INSERT INTO eZUser_UserGroupLink
                            ( ID, UserID, GroupID )
                            VALUES
                            ( '$nextID', '$userID', '$this->ID' )" );


               if ( $res == false )
                   $dbError = true;
               $ret = true;
           }

           $db->unlock();
           if ( $dbError == true )
               $db->rollback( );
           else
               $db->commit();

       }
       return $ret;
    }

    var $ID;
    var $Name;
    var $Description;
    var $GroupURL;
    var $SessionTimeout;
    var $IsRoot;
}

?>
