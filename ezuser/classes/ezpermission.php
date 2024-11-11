<?php
//
// $Id: ezpermission.php 7133 2001-09-10 23:00:02Z fh $
//
// Definition of eZPermission class
//
// Created on: <27-Sep-2000 08:05:56 bf>
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
//! eZPermission haldes user group permissions.
/*!

  Example code:
  \code
  // First off, the fast version of permission checking.
  // the $group variable is a eZUserGroup object.
  if ( eZPermission::checkPermission( $group, "eZTrade", "AddProducts" ) )
  {
      print( "Access granted" );
  }
  else
  {
      print( "Access denied" );
  }

  // fetch a module from the database.
  $module->get( 1 );

  // create a new eZPermission object and set some values
  $permission = new eZPermission();
  $permission->setName( "AddProducts" );
  $permission->setModule( $module );

  // store the object and repport the success/failure
  if ( $permission->store() )
  {
      print( "Permission stored successfully<br>" );
  }
  else
  {
      print( "Error: could not store permission." );
  }

  // fetch a permission
  if ( $permission->get( 1 ) )
  {
      print( "Permission successfully fetched" );
  }

  // fetch a user group
  $group = new eZUserGroup();
  $group->get( 1 );

  // set a group permission
  $permission->setEnabled( $group, false );


  // check some permissions
  if ( $permission->isEnabled( $group ) )
  {
      print( "Access granted.<br>" );
  }
  else
  {
      print( "Access denied.<br>" );
  }

  \endcode
  \sa eZUser eZUserGroup eZModule eZForgot
*/

include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

class eZPermission
{
    /*!
      Constructs a new eZPermission object.

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
      Stores or updates a eZPermission object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );


        $ret = false;
        $name = addslashes( $this->Name );

        if ( ( $this->ModuleID != "" ) && ( $this->ModuleID != 0 ) )
        {
            $db->array_query( $value_array, "SELECT * FROM eZUser_Permission
                                                    WHERE Name='$name' AND ModuleID='$this->ModuleID'" );
            if ( count( $value_array ) == 0 )
            {
                $db->lock( "eZUser_Permission" );

                $nextID = $db->nextID( "eZUser_Permission", "ID" );

                $ret = true;
                if ( !isset( $this->ID ) )
                {
                    $db->query( "INSERT INTO eZUser_Permission ( ID, Name, ModuleID )
                    VALUES
                    ( '$nextID', '$name', '$this->ModuleID' " );

					$this->ID = $nextID;
                }
                else
                {
                    $db->query( "UPDATE eZUser_Permission SET
                                 Name='$name',
                                 ModuleID='$this->ModuleID'
                                 WHERE ID='$this->ID'" );
                }
            }
        }

        $db->unlock();

        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        return $ret;
    }

    /*!
      Deletes a eZPermission object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_GroupPermissionLink WHERE PermissionID='$this->ID'" );

            $db->query( "DELETE FROM eZUser_Permission WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Fetches the object information from the database.

      Returns false if unsuccessful.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $permission_array, "SELECT * FROM eZUser_Permission WHERE ID='$id'" );
            if ( count( $permission_array ) > 1 )
            {
                die( "Error: Permission's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $permission_array ) == 1 )
            {
                $this->ID = $permission_array[0][$db->fieldName("ID")];
                $this->Name = $permission_array[0][$db->fieldName("Name")];
                $this->ModuleID = $permission_array[0][$db->fieldName("ModuleID")];
                $ret = true;
            }

            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Fetches the id from the database where ModuleID == $id, and returns an array of eZPermission objects.
    */
    function getAllByModule( $module )
    {
        if ( is_a( $module, "eZModule" ) )
        {
            $db =& eZDB::globalDatabase();

            $moduleID = $module->id();

            $return_array = array();
            $permission_array = array();

            $db->array_query( $permission_array, "SELECT ID,Name FROM eZUser_Permission
                                                              WHERE ModuleID='$moduleID'
                                                              ORDER BY Name" );


            for ( $i=0; $i < count( $permission_array ); $i++ )
            {
                $return_array[$i] = new eZPermission( $permission_array[$i][$db->fieldName("ID")], 0 );
            }

            return $return_array;
        }
    }

    /*!
      Fetches the id from the database, and returns an array of eZPermission objects.
    */
    function getAll( )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $permission_array = array();

        $db->array_query( $permission_array, "SELECT ID FROM eZUser_Permission" );

        for ( $i=0; $i < count( $permission_array ); $i++ )
        {
            $return_array[$i] = new eZPermission( $permission_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }



    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the permission name.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       return $this->Name;
    }

    /*!
      Returns the module as a eZModule object.

      False is returned if unsuccessful.
    */
    function module()
    {
       $ret = false;
       $module = new eZModule( );

       if ( $module->get( $this->ModuleID ) )
       {
           $ret = $module;
       }

       return $module;
    }


    /*!
      Sets the permission name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the module.

      Returns false if unsuccessful, else true.
    */
    function setModule( $module )
    {
       $ret = false;
        if ( is_a( $module, "eZModule" ) )
       {
           $this->ModuleID = $module->id();
           $ret = true;
       }
       return $ret;
    }

    /*!
      Sets the permission value.

      Takes a eZUserGroup object and a bool as arguments.

      Returned false if unsuccessful.
    */
    function setEnabled( $group, $value )
    {
       $ret = false;

       if ( is_a( $group, "eZUserGroup" ) )
       {
           $db =& eZDB::globalDatabase();
           $dbError = false;
           $db->begin( );


           $groupID = $group->id();

           if ( $value )
           {
               $isEnabled = "1";
           }
           else
           {
               $isEnabled = "0";
           }

           $db->array_query( $value_array, "SELECT ID FROM eZUser_GroupPermissionLink
                                                    WHERE PermissionID='$this->ID' AND GroupID='$groupID'" );
           if ( count( $value_array ) == 1 )
           {
               $valueID = $value_array[0][$db->fieldName("ID")];
               $res = $db->query( "UPDATE eZUser_GroupPermissionLink SET
		                         IsEnabled='$isEnabled' WHERE ID='$valueID'
                                 " );
           }
           else
           {
               $db->lock( "eZUser_GroupPermissionLink" );

               $nextID = $db->nextID( "eZUser_GroupPermissionLink", "ID" );

               $res = $db->query( "INSERT INTO eZUser_GroupPermissionLink
                                  ( ID, PermissionID, GroupID, IsEnabled )
                                  VALUES
 		                          ( '$nextID', '$this->ID', '$groupID', '$isEnabled' )" );
           }

           if ( $res == false )
               $dbError = true;
           else
               $ret = true;

           $db->unlock();

           if ( $dbError == true )
               $db->rollback( );
           else
               $db->commit();

       }

       return $ret;
    }

    /*!
      Returns true if the permission is enabled.

      False is returned if the permission does not exits or if the permission is disabled.
    */
    function isEnabled( $group )
    {
       $ret = false;
       if ( is_a( $group, "eZUserGroup" ) )
       {
           $db =& eZDB::globalDatabase();

           $groupID = $group->id();


           $db->array_query( $value_array, "SELECT * FROM eZUser_GroupPermissionLink
                                                    WHERE PermissionID='$this->ID' AND GroupID='$groupID'" );
           if ( count( $value_array ) == 1 )
           {
               if ( $value_array[0][$db->fieldName("IsEnabled")] == "1" )
               {
                   $ret = true;
               }
           }
       }
       return $ret;
    }

    /*!
      \static
      Static function for checking permissions.
      Note: If the user has root permissions this function allways returns true.
    */
    static public function checkPermission( $user, $moduleName, $permissionName )
    {
        $module = new eZModule();
        $module = $module->exists( $moduleName );

        if ( !is_a( $user, "eZUser" ) )
            return false;

        if ( $user->hasRootAccess() )
            return true;

        $ret = false;

        if ( $module )
        {
            $db =& eZDB::globalDatabase();

            $moduleID = $module->id();

            $test = "SELECT * FROM eZUser_Permission WHERE Name='$permissionName' AND ModuleID='$moduleID'";

            $db->array_query( $value_array, "SELECT * FROM eZUser_Permission
                                                    WHERE Name='$permissionName' AND ModuleID='$moduleID'", true );

            if ( count( $value_array ) == 1 )
            {
                $permission = new eZPermission();
                $permission->get( $value_array[0][$db->fieldName( "ID" )] );

                if ( is_a( $user, "eZUser" ) )
                {
                    $group = new eZUserGroup();
                    $groupArray = $group->getByUser( $user );
                    foreach ( $groupArray as $group )
                    {
                        if ( $permission->isEnabled( $group ) )
                        {
                            $ret = true;
                            break;
                        }
                    }
                }
            }
        }
        return $ret;
    }


    var $ID;
    var $Name;
    var $ModuleID;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
}

?>
