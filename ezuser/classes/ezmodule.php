<?php
// 
// $Id: ezmodule.php 9537 2002-05-15 16:02:04Z bf $
//
// Definition of eZModule class
//
// Created on: <27-Sep-2000 08:00:43 bf>
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
//! eZModule handles projects/modules used for permission control.
/*!

  Example code:
  \code
  $module = new eZModule();

  $module->setName( "eZTrade" );

  if ( !$module->exists( $module->name() ) )
  {
      print( "Creating module<br>" );
      $module->store();
  }
  else
  {
      print( "Error: count not create module, a module with that name already exists." );
  }

  \endcode
  \sa eZUser eZUserGroup eZPermission eZForgot
*/

class eZModule
{
    /*!
      Constructs a new eZModule object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZModule( $id=""  )
    {
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZModule object in the database.

      Returns false if the storing did not succeed.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );
        
        $name = $db->escapeString( $this->Name );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Module" );

            $nextID = $db->nextID( "eZUser_Module", "ID" );
            
            $res = $db->query( "INSERT INTO eZUser_Module
                                     ( ID, Name )
                                     VALUES
                                     ( '$nextID', '$name' )" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $this->Database->query( "UPDATE eZUser_Module SET
                                     Name='$name'
                                     WHERE ID='$this->ID'" );
        }
        

        $db->unlock();

        if ( $res == false )
            $dbError = true;
        
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZModule object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );
        
        if ( isset( $this->ID ) )
        {
            $res = $db->query( "DELETE FROM eZUser_Module WHERE ID='$this->ID'" );
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
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $module_array, "SELECT * FROM eZUser_Module WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Module's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][$db->fieldName("ID")];
                $this->Name = $module_array[0][$db->fieldName("Name")];
                $ret = true;
            }
        }
        
        return $ret;
    }

    /*!
      Fetches the user id from the database. And returns a array of eZModule objects.
    */
    function getAll( $returnOnlyActive=false)
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        if ( $returnOnlyActive == true )
        {
            $modules = array();
            $ini =& INIFile::globalINI();
            foreach ( $ini->read_array( "site", "EnabledModules") as $mod)
            {
                $modules[] = "'" . $db->escapeString( $mod ) . "'";
            }

            $allModules = join(',', $modules);

            $db->array_query( $module_array, "SELECT ID,Name FROM eZUser_Module WHERE Name IN ($allModules) ORDER BY Name" );
        }
        else
        {
            $db->array_query( $module_array, "SELECT ID,Name FROM eZUser_Module ORDER By Name" );

        } 
        
        for ( $i=0; $i<count ( $module_array ); $i++ )
        {
            $return_array[$i] = new eZModule( $module_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the module with the given name as a eZModule object.

      False (0) is returned if unsuccessful.
    */
    function exists( $name )
    {
        $db =& eZDB::globalDatabase();
        
        $ret = false;
        
        $db->array_query( $user_array, "SELECT * FROM eZUser_Module
                                                    WHERE Name='$name'" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZModule( $user_array[0][$db->fieldName("ID")] );
        }

        return $ret;        
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the module name.
    */
    function name()
    {
       return $this->Name;
       
    }

    /*!
      Sets the module name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }        

    var $ID;
    var $Name;
}

?>
