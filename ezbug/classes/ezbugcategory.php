<?php
//
// $Id: ezbugcategory.php 8229 2001-11-01 20:33:09Z vl $
//
// Definition of eZBugCategory class
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
//! eZBugCategory handles bug categories.
/*!
  Example code:
  \code
  // include the class
  include_once( "ezbug/classes/ezbugcategory.php" );

  // create a new class object
  $category = new eZBugCategory();

  // Set some object values and store them to the database.
  $category->setName( "GUI related" );
  $category->setDescription( "This is bugs reported which are related to GUI issues." );
  $category->store();
  \endcode
  \sa eZBug eZBugModule
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZBugCategory
{
    /*!
      Constructs a new eZBugCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZBugCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        $db->begin();
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZBug_Category" );
			$this->ID = $db->nextID( "eZBug_Category", "ID" );
            $res = $db->query( "INSERT INTO eZBug_Category
                                       (ID, Name, Description) VALUES
                                       ('$this->ID', '$name', '$description')" );
            $db->unlock();

        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Category SET
		                      Name='$name',
                              Description='$description'
                              WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback();
        else
            $db->commit();


        return true;
    }

    /*!
      Deletes a eZBugGroup object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isSet( $this->ID ) )
        {
            $db->begin();
            // delete from BugCategoryLink
            $res[] = $db->query( "DELETE FROM eZBug_BugCategoryLink WHERE CategoryID='$this->ID'" );
            // delete actual group entry
            $res[] = $db->query( "DELETE FROM eZBug_Category WHERE ID='$this->ID'" );

            if ( in_array( false, $res ) )
                $db->rollback();
            else
                $db->commit();
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
            $db->array_query( $category_array, "SELECT * FROM eZBug_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName( "ID" )];
                $this->Name = $category_array[0][$db->fieldName( "Name" )];
                $this->Description = $category_array[0][$db->fieldName( "Description" )];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBugCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID FROM eZBug_Category ORDER BY Name" );

        for ( $i = 0; $i < count( $category_array ); $i++ )
        {
            $return_array[$i] = new eZBugCategory( $category_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the category.
    */
    function name( $html = true )
    {
       if ( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;
    }

    /*!
      Sets the name of the category.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Adds a bug to the category.
    */
    function addBug( $value )
    {
       if ( is_a( $value, "eZBug" ) )
       {
           $bugID = $value->id();

           $db =& eZDB::globalDatabase();
           $db->begin();
           $db->lock( "eZBug_BugCategoryLink" );
           $nextid = $db->nextID( "eZBug_BugCategoryLink", "ID" );
           $res = $query = "INSERT INTO eZBug_BugCategoryLink
                            (ID, CategoryID, BugID)
                            VALUES
                            ('$nextID','$this->ID','$bugID')";
           $db->query( $query );
           $db->unlock();
       }
       if ( $res == false )
           $db->rollback();
       else
           $db->commit();
    }

    /*!
      Returns every bug in a category as a array of eZBug objects.

      If $fetchUnhandled is set to true the bugs which is not yet handled are
      also returned.
    */
    function bugs( $sortMode="time",
                       $fetchUnhandled=true,
                       $offset=0,
                       $limit=50 )
    {
        $db =& eZDB::globalDatabase();

//         $OrderBy = "eZBug_Bug.Published DESC";
//         switch( $sortMode )
//         {
//             case "alpha" :
//             {
//                 $OrderBy = "eZBug_Bug.Name ASC";
//             }
//             break;
//         }

       $return_array = array();
       $bug_array = array();

       if ( $fetchUnhandled )
       {
           $excludedCode = " AND eZBug_Bug.IsHandled=0 ";
       }
       else
       {
           $excludedCode = "";
       }

       $db->array_query( $bug_array, "
                         SELECT eZBug_Bug.ID AS BugID,
                                eZBug_Category.ID,
                         FROM eZBug_Bug, eZBug_Category, eZBug_BugCategoryLink
                         WHERE
                         eZBug_BugCategoryLink.BugID = eZBug_Bug.ID
                         AND
                         eZBug_Category.ID = eZBug_BugCategoryLink.CategoryID
                         AND
                         eZBug_Category.ID='$this->ID'
                         $excludedCode
                         GROUP BY eZBug_Bug.ID, eZBug_Category.ID ORDER BY $OrderBy",
                         array( "Limit" => $limit, "Offset" => $offset ) );

       for ( $i = 0; $i < count( $bug_array ); $i++ )
       {
           $return_array[$i] = new eZBug( $bug_array[$i][$db->fieldName( "BugID" )], false );
       }

       return $return_array;
    }

    var $ID;
    var $Name;
    var $Description;
}

?>