<?php
//
// $Id: ezbugmodule.php 7766 2001-10-10 20:18:53Z fh $
//
// Definition of eZBugModule class
//
// Created on: <27-Nov-2000 19:08:57 bf>
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

//!! eZBug
//! eZBugModule handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugmodule.php" );

  // create a new class object
  $module = new eZBugModule();

  // Set some object values and store them to the database.
  $module->setName( "eZ trade" );
  $module->setDescription( "Bugs reported here are related to the eZ trade module." );
  $module->store();
  \endcode
  \sa eZBug eZBugCategory
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "ezbug/classes/ezbug.php" );
include_once( "ezuser/classes/ezusergroup.php" );

class eZBugModule
{
    /*!
      Constructs a new eZBugModule object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBugModule object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $db->begin();

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZBug_Module", "ID" );
			$this->ID = $db->nextID( "eZBug_Module", "ID" );
            $res = $db->query( "INSERT INTO eZBug_Module
                                (ID, Name, Description, ParentID, OwnerGroupID)
                                VALUES
                                ('$this->ID',
                                 '$name',
                                 '$description',
                                 '$this->ParentID',
                                 '$this->OwnerGroupID')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Module SET
		                        Name='$name',
                                Description='$description',
                                ParentID='$this->ParentID',
                                OwnerGroupID='$this->OwnerGroupID'
                                WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZBugModule object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        if ( isSet( $this->ID ) )
        {
            // delete all bugs!
            $bugs = array();
            $db->array_query( $bugs, "SELECT eZBug_BugModuleLink.BugID FROM eZBug_BugModuleLink WHERE ModuleID='$this->ID'" );
            foreach ( $bugs as $bugID )
            {
                $doomedBug =  new eZBug( $bugID[ $db->fieldName( "BugID" ) ] );
                $doomedBug->delete();
            }

            // delete the modules that have this module as parent.
            $doomedModules = $this->getByParent( $this );
            if ( count( $doomedModules ) > 0 )
            {
                foreach ( $doomedModules as $module )
                {
                    $module->delete();
                }
            }

            $res[] = $db->query( "DELETE FROM eZBug_ModulePermission WHERE ObjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZBug_BugModuleLink WHERE ModuleID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZBug_Module WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $module_array, "SELECT * FROM eZBug_Module WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Module's with the same ID was found in the database. This should not happen." );
            }
            else if ( count( $module_array ) == 1 )
            {
                $this->ID = $module_array[0][$db->fieldName( "ID" )];
                $this->Name = $module_array[0][$db->fieldName( "Name" )];
                $this->Description = $module_array[0][$db->fieldName( "Description" )];
                $this->ParentID = $module_array[0][$db->fieldName( "ParentID" )];
                $this->OwnerGroupID = $module_array[0][$db->fieldName( "OwnerGroupID" )];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBugModule objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        $db->array_query( $module_array, "SELECT ID FROM eZBug_Module ORDER BY Name" );

        for ( $i = 0; $i < count( $module_array ); $i++ )
        {
            $return_array[$i] = new eZBugModule( $module_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the categories with the module given as parameter as parent.

      The categories are returned as an array of eZBugModule objects.
    */
    function getByParent( $parent, $sortby=name, $recursive=false )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $module_array = array();

        $parentID = $parent->id();

        $db->array_query( $module_array, "SELECT ID, Name FROM eZBug_Module
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

        if ( is_array( $recursive ) )
        {
            $recursive[] = $parentID;
        }

        for ( $i = 0; $i < count( $module_array); $i++ )
        {
            if ( is_array( $recursive ) )
            {
                $mod = new eZBugModule( $module_array[$i][$db->fieldName( "ID" )] );
                $recursive = $mod->getByParent( $mod, "name", $recursive );
            }
            else
            {
                $return_array[$i] = new eZBugModule( $module_array[$i][$db->fieldName( "ID" )], 0 );
            }
        }

        if ( !is_array( $recursive ) )
        {
            return $return_array;
        }
        else
        {
            return $recursive;
        }
    }

    function path( $moduleID=0 )
    {
        if ( $moduleID == 0 )
        {
            $moduleID = $this->ID;
        }

        $module = new eZBugModule( $moduleID );

        $path = array();

        $parent = $module->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $module->name() );
        }

        if ( $moduleID != 0 )
            array_push( $path, array( $module->id(), $module->name() ) );

        return $path;
    }

    /*!
      Returns the object ID to the module. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the module.
    */
    function name( $html = true )
    {
        if ( $this->Name == false )
            return "";
        else if ( $html )
            return htmlspecialchars( $this->Name );
        else
            return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
        if ( !is_null( $this->Description ) && $html )
            return htmlspecialchars( $this->Description );
        else
            return $this->Description;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
        if ( $this->ParentID != 0 )
        {
            return new eZBugModule( $this->ParentID );
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the owner group of this module as an eZOwnerGroup object.
      If the object doesn't have an owner it returns 0.
     */
    function ownerGroup()
    {
        $group = new eZUserGroup( $this->OwnerGroupID );
        return $group;
    }

    /*!
      Sets the name of the module.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the module.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent module.
      Parameter must be an eZBugModule object.
    */
    function setParent( $value )
    {
       if ( is_a( $value, "eZBugModule" ) )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Sets the owner group of this module.
      Parameter $newOwner must be an eZUserGroup object.
      if $recursive is true the function will also set the owner group for all submodules. These will be stored automaticly.
     */
    function setOwnerGroup( $newOwner, $recursive = false )
    {
        if( is_a( $newOwner, "eZUserGroup" ) )
        {
            $this->OwnerGroupID = $newOwner->id();
            if( $recursive == true )
            {
                $modules = $this->getByParent( $this );
                foreach( $modules as $moduleItem )
                {
                    $moduleItem->setOwnerGroup( $newOwner, true );
                    $moduleItem->store();
                }
            }
        }
    }

    /*!
      Adds a bug to the module.
    */
    function addBug( $value )
    {
        if ( is_a( $value, "eZBug" ) )
        {
            $db =& eZDB::globalDatabase();

            $bugID = $value->id();

            $db->begin();
            $db->lock( "eZBug_BugModuleLink" );
            $nextID = $db->nextID( "eZBug_BugModuleLink", "ID" );

            $res = $query = "INSERT INTO eZBug_BugModuleLink
                             (ID, ModuleID, BugID) VALUES
                             ('$nextID', '$this->ID', '$bugID')";

            $db->query( $query );
            $db->unlock();

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }
    }

    /*!
      Returns every bug in a module as a array of eZBug objects.

      If $fetchUnhandled is set to true the bugs which is not yet handled are
      also returned.
    */
    function &bugs( $sortMode="time",
                       $fetchUnhandled=true,
                       $withPrivate=false,
                       $offset=0,
                       $limit=50)
    {
        $db =& eZDB::globalDatabase();

        $OrderBy = "eZBug_Bug.Created DESC";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "eZBug_Bug.Name ASC";
            }
            break;
        }

        $return_array = array();
        $bug_array = array();

        $unhandledSQL = "";
        if ( $fetchUnhandled == false )
        {
            $unhandledSQL = "AND IsHandled='0'";
        }
        $privateSQL = "";
        if( $withPrivate == false )
        {
            $privateSQL="AND IsPrivate='0'";
        }

        $db->array_query( $bug_array, "
                SELECT eZBug_Bug.ID AS BugID, eZBug_Bug.Name, eZBug_Module.ID, eZBug_Module.Name
                FROM eZBug_Bug, eZBug_Module, eZBug_BugModuleLink
                WHERE
                eZBug_BugModuleLink.BugID = eZBug_Bug.ID
                AND
                eZBug_Module.ID = eZBug_BugModuleLink.ModuleID
                $unhandledSQL
                $privateSQL
                AND
                eZBug_Module.ID='$this->ID'
                GROUP BY eZBug_Bug.ID ORDER BY $OrderBy LIMIT $offset,$limit" );

        for ( $i=0; $i < count( $bug_array ); $i++ )
        {
            $return_array[$i] = new eZBug( $bug_array[$i][ $db->fieldName( "BugID" ) ], false );
        }

        return $return_array;
    }

    /*!
      Returns the bug count in the module.

      If $countUnhandled == true all bugs are counted if not, only
      handled bugs are counted.

      If $excludeClosed == true the closed bugs does not get counted.

      If $recursive == true it will also count the bug in the submodules.
    */
    function countBugs( $countUnhandled=true, $excludeClosed=false, $recursive=false, $withPrivate=false )
    {
        $db =& eZDB::globalDatabase();

        $unhandledSQL = "";
        if ( $countUnhandled == false )
        {
            $unhandledSQL = "AND eZBug_Bug.IsHandled='1'";
        }

        $openSQL = "";
        if ( $excludeClosed == true )
        {
            $openSQL = "AND eZBug_Bug.IsClosed!='1'";
        }

        $privateSQL = "";
        if( $withPrivate == false )
        {
            $privateSQL = "AND eZBug_Bug.IsPrivate!='1'";
        }

        $query = "
                SELECT count( * ) AS Count
                FROM eZBug_Bug, eZBug_Module, eZBug_BugModuleLink
                WHERE
                eZBug_BugModuleLink.BugID = eZBug_Bug.ID
                AND
                eZBug_Module.ID = eZBug_BugModuleLink.ModuleID
                $unhandledSQL
                $openSQL
                $privateSQL
                AND
                eZBug_Module.ID='$this->ID'
                ";

        $db->array_query( $bug_array, $query );
        $ret =& $bug_array[0][ $db->fieldName( "Count" ) ];

        if( $recursive == true )  // Count bugs in modules under this one.
        {
            $query = "
                    SELECT ID
                    FROM eZBug_Module
                    WHERE
                    eZBug_Module.ParentID = '$this->ID'
                    ";
            $db->array_query( $modules, $query );
            for ( $i = 0; $i < count( $modules ); $i++ )
            {
                $mod = new eZBugModule( $modules[$i][ $db->fieldName( "ID" ) ] );
                $ret += $mod->countBugs( $countUnhandled, $excludeClosed, true );
            }
        }
        return $ret;
    }

    /*!
      Returns true if the given module is a child (doesn't have to be first level) of this module.
      If the second parameter is set to true, the function also checks if the module given is itself.
     */
    function isChild( $moduleID, $check_for_self = false )
    {
        $return_value = false;
        $db =& eZDB::globalDatabase();

        if ( is_a( $moduleID, "eZBugModule" ) )
            $moduleID = $moduleID->id();

        if ( $check_for_self == true && $moduleID == $this->ID )
            return true;

        while ( $moduleID != 0 )
        {
            $db->query_single( $result, "SELECT ParentID FROM eZBug_Module WHERE ID='$moduleID'" );
            $moduleID = $result[ $db->fieldName( "ParentID" ) ];
            if ( $moduleID == $this->ID )
                return true;
        }
        return $return_value;
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $OwnerGroupID;
}

?>