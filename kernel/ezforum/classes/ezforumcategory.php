<?php
//
// $Id: ezforumcategory.php 7428 2001-09-24 14:03:59Z jhe $
//
// Definition of eZForumCategory class
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

//!! eZForum
//! The eZForumCategory class handles forum categories.
/*!

  \sa eZForum
*/

/*!TODO

*/

// include_once( "classes/ezdb.php" );
// include_once( "ezforum/classes/ezforum.php" );

class eZForumCategory
{
    /*!
      Constructs a new eZForumCategory object.
    */
    function __construct( $id = "" )
    {
        if ( $id != "" )
        {
            $this->get( $id );
        }
    }

    /*!
      Stores a eZForumCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZForum_Category" );
            $nextID = $db->nextID( "eZForum_Category", "ID" );

            $res = $db->query( "INSERT INTO eZForum_Category
                         ( ID,  Name, Description, IsPrivate, SectionID )
                         VALUES
                         ( '$nextID',
                           '$name',
                           '$description',
                           '$this->IsPrivate',
                           '$this->SectionID')" );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZForum_Category SET
		                         Name='$name',
		                         Description='$description',
		                         IsPrivate='$this->IsPrivate',
                                 SectionID='$this->SectionID'
                                 WHERE ID='$this->ID'
                                 " );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZForumCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $forumList = $this->forums();

        foreach ( $forumList as $forum )
        {
            $forum->delete();
        }

        $db->query( "DELETE FROM eZForum_ForumCategoryLink WHERE CategoryID='$this->ID'" );
        $db->query( "DELETE FROM eZForum_Category WHERE ID='$this->ID'" );

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
            $db->array_query( $category_array, "SELECT * FROM eZForum_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName( "ID" )];
                $this->Name = $category_array[0][$db->fieldName( "Name" )];
                $this->Description = $category_array[0][$db->fieldName( "Description" )];
                $this->SectionID = $category_array[0][$db->fieldName( "SectionID" )];

                $ret = true;
            }
            else if ( count( $category_array ) == 0 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Returns every category as an array of eZForumCategory objects.
    */
    function getAll()
    {
        $ret = array();

        $db =& eZDB::globalDatabase();
        $db->array_query( $category_array, "SELECT ID FROM eZForum_Category" );

        $ret = array();
        foreach ( $category_array as $category )
        {
            $ret[] = new eZForumCategory( $category[$db->fieldName( "ID" )] );
        }

        return $ret;
    }

    /*!
      Returns every forum under the current category.
    */
    function forums()
    {
       $db =& eZDB::globalDatabase();

       $db->array_query( $forum_array, "SELECT ForumID FROM
                                                       eZForum_ForumCategoryLink
                                                       WHERE CategoryID='$this->ID'" );

       $ret = array();

       foreach ( $forum_array as $forum )
       {
           $ret[] = new eZForum( $forum[$db->fieldName( "ForumID" )] );
       }

       return $ret;
    }

    /*!
      Adds a forum to the current category.
    */
    function addForum( $forum )
    {
       if ( is_a( $forum, "eZForum" ) )
       {
           $db =& eZDB::globalDatabase();

           $forumID = $forum->id();
           $db->begin( );

           $db->lock( "eZForum_ForumCategoryLink" );
           $nextID = $db->nextID( "eZForum_ForumCategoryLink", "ID" );

           $res = $db->query( "INSERT INTO
                               eZForum_ForumCategoryLink
                               ( ID, CategoryID, ForumID )
                               VALUES
                               ( '$nextID', '$this->ID', '$forumID' )" );
           $db->unlock();

           if ( $res == false )
               $db->rollback( );
           else
               $db->commit();

           $ret = array();
       }
    }


    /*!
      Returns all forum categories.
    */
    function getAllCategories()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $category_array, "SELECT ID FROM eZForum_Category" );

        $ret = array();
        foreach ( $category_array as $category )
        {
            $ret[] = new eZForumCategory( $category[$db->fieldName("ID")] );
        }
        return $ret;
    }

    /*
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Sets the forum category name.
    */
    function setName( $newName )
    {
        $this->Name = $newName;
    }

    /*!
      Sets the section of the category
    */
    function setSectionID( $value )
    {
        $this->SectionID = $value;
    }

    /*!
      Returns the section of the category
    */
    function sectionID()
    {
        return $this->SectionID;
    }

    /*!
      \static
      Returns the Section ID. Returns false if the Category was not found.
    */
    static public function sectionIDStatic( $categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SectionID from eZForum_Category WHERE ID='$categoryID'");

        $sectionID = $res[ (int) $db->fieldName("SectionID")];

        if ( $sectionID > 0 )
            return $sectionID;
        else
            return false;
    }

    /*!
      Returns the forum name.
    */
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the forum category description.
    */
    function setDescription( $newDescription )
    {
        $this->Description = $newDescription;
    }

    /*!
      Returns the forum category description.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsPrivate;
    var $SectionID;

}
?>