<?php
//
// $Id: ezmediacategory.php 8138 2001-11-01 08:31:40Z ce $
//
// Definition of eZMediaCategory class
//
// Created on: <24-Jul-2001 10:28:22 ce>
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

//!! eZMediaCatalogue
//! eZMediaCategory manages virtual folders.
/*!
  Example code:
  \code
  // create a new country type and set some variables.
  $category = new eZMediaAttribute();
  $category->setName( "My media files" );
  $category->setDescription( "Just some media files" );
  $category->setParent( $parentCategory );
  $category->setUser( $user );
  $category->store();
  \endcode
  \sa eZMedia eZMediaType eZMediaAttribute

*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );

include_once( "ezmediacatalogue/classes/ezmedia.php" );

class eZMediaCategory
{
    /*!
      Constructs a new eZMediaCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->ExcludeFromSearch = "false";
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZMediaCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( $this->ID == false )
        {
            $db->lock( "eZMediaCatalogue_Category" );

            $this->ID = $db->nextID( "eZMediaCatalogue_Category", "ID" );

            $db->query( "INSERT INTO eZMediaCatalogue_Category
                                     ( ID, Name, Description, UserID, ParentID ) VALUES
                                     ( '$this->ID', '$name', '$description', '$this->UserID', '$this->ParentID' )" );
            $db->unlock();
        }
        else
        {
            $db->query( "UPDATE eZMediaCatalogue_Category SET
                                     Name='$name',
                                     Description='$description',
                                     UserID='$this->UserID',
                                     ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }


        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();


        return true;
    }

    /*!
      Deletes a eZMediaCategory object from the database.
    */
    function delete( $catID=-1 )
    {

        if ( $catID == -1 )
            $catID = $this->ID;

        $category = new eZMediaCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach ( $categoryList as $category )
        {
            $this->delete( $category->id() );
        }

        foreach ( $this->media() as $media )
        {
            $media->delete();
        }

        $categoryID = $category->id();

        $db =& eZDB::globalDatabase();

        $db->begin( );

        $res1 = $db->query( "DELETE FROM eZMediaCatalogue_Category WHERE ID='$categoryID'" );
        $res2 = $db->query( "DELETE FROM eZMediaCatalogue_CategoryPermission WHERE ObjectID='$this->ID'" );

        if ( ( $res1 == false)  || ( $res2 == false ) )
            $db->rollback( );
        else
            $db->commit();

    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZMediaCatalogue_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][$db->fieldName("ID")];
                $this->Name =& $category_array[0][$db->fieldName("Name")];
                $this->Description =& $category_array[0][$db->fieldName("Description")];
                $this->ParentID =& $category_array[0][$db->fieldName("ParentID")];
                $this->UserID =& $category_array[0][$db->fieldName("UserID")];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZMediaCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID, Name FROM eZMediaCatalogue_Category ORDER BY Name" );

        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZMediaCategory( $category_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      \Static
      Returns all media in a category
    */
    function &getMedia( &$user, $category=false )
    {
        if ( !$category )
            $category = $this->ID;

        $db =& eZDB::globalDatabase();

        $return_array = array();
        $media_array = array();

        $db->array_query( $media_array, "SELECT MediaID, CategoryID FROM eZMediaCatalogue_MediaCategoryLink WHERE CategoryID='$category' ORDER BY MediaID DESC" );

        for ( $i = 0; $i < count( $media_array ); $i++ )
        {
            $media = new eZMedia( $media_array[$i][$db->fieldName( "MediaID" )] );
            if ( $media->hasReadPermissions( $user ) )
            {
                array_push( $return_array, $media );
            }
        }
        return $return_array;
    }

    /*!
      Returns the number of categories in the the category given as parameter as parent.
    */
    function countByParent( &$parent  )
    {
        if ( is_a( $parent, "eZMediaCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $parentID = $parent->id();

            $db->query_single( $count, "SELECT count( ID ) AS Count FROM eZMediaCatalogue_Category
                                        WHERE ParentID='$parentID'", "Count" );

            return $count;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZMediaCategory objects.
    */
    function &getByParent( &$parent, $offset = 0, $max = -1 )
    {
        if ( is_a( $parent, "eZMediaCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZMediaCatalogue_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name", array( "Limit" => $max,
                                                                 "Offset" => $offset ) );

            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZMediaCategory( $category_array[$i][$db->fieldName("ID")] );
            }

            return $return_array;
        }
        else
        {
            return array();
        }
    }

    /*!
        \static
        Returns the one, and only if one exists, category with the name

        Returns an object of eZMediaCategory.
     */
    function &getByName( &$name )
    {
        $db =& eZDB::globalDatabase();

        $topic = new eZMediaCategory();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT ID, Name FROM eZMediaCatalogue_Category WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic = new eZMediaCategory( $author_array[0][$db->fieldName("ID")] );
            }
        }

        return $topic;
    }


    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }

        $category = new eZMediaCategory( $categoryID );

        $path = array();

        $parent = $category->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );

        return $path;
    }

    /*!
      Recursive function that returns an array containing an int (tree position) and an array ( all items on that level )
     */
    function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZMediaCategory( $parentID );

        $categoryList = $category->getByParent( $category );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZMediaCategory( $category->id() ), $level ) );

            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }

        }

        return $tree;
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
    function &name( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Name );
       else
           return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function &description( $html = true )
    {
       if( $html )
           return htmlspecialchars( $this->Description );
       else
           return $this->Description;

    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function &parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZMediaCategory( $this->ParentID );
       }
       else
       {
           return 0;
       }
    }


    /*!
      Returns a eZUser object.
    */
    function &user()
    {
        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }

        return $ret;
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $mediacategory is the ID of the media category.
     */
    function isOwner( &$user, &$mediacategory )
    {
        if( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZMediaCatalogue_Category WHERE ID='$mediacategory'");
        $userID = $res[$db->fieldName("UserID")];
        if(  $userID == $user->id() )
            return true;

        return false;
    }


    /*!
      Sets the name of the category.
    */
    function setName( &$value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( &$value )
    {
       if ( is_a( $value, "eZMediaCategory" ) )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Sets the user of the file.
    */
    function setUser( &$user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      \static
      Adds a file to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addMedia( &$value, $categoryid = false )
    {
       if ( is_a( $value, "eZMedia" ) )
           $mediaID = $value->id();
       else if ( is_numeric( $value ) )
           $mediaID = $value;
       else
           return false;

       if ( !$categoryid )
           $categoryid = $this->ID;

       $db =& eZDB::globalDatabase();

       $mediaID = $value->id();

       $db->begin( );
       $db->lock( "eZMediaCatalogue_MediaCategoryLink" );

       $query = "DELETE FROM eZMediaCatalogue_MediaCategoryLink WHERE
                 CategoryID='$categoryid' AND MediaID='$mediaid'";

       $db->query( $query );

       $nextID = $db->nextID( "eZMediaCatalogue_MediaCategoryLink", "ID" );

       $query = "INSERT INTO eZMediaCatalogue_MediaCategoryLink ( ID, CategoryID, MediaID )
                 VALUES ( '$nextID', '$categoryid', '$mediaID' )";

       $res = $db->query( $query );

       $db->unlock();

       if ( $res == false )
           $db->rollback( );
       else
           $db->commit();
    }

    /*!
      \static
      Removes an media from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeMedia( &$value, $categoryid = false )
    {
        if ( is_a( $value, "eZMedia" ) )
            $mediaID = $value->id();
        else if ( is_numeric( $value ) )
            $mediaID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZMediaCatalogue_MediaCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        MediaID='$mediaID'";

        $db->begin( );

        $res = $db->query( $query );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }


    /*!
      Returns every media in a category as a array of eZMedia objects.
    */
    function mediaCount()
    {
        if ( !isset($limit) || isset($limit) && $limit == 0 )
        {
            $ini =& INIFile::globalINI();
            $limit = $ini->read_var( "eZMediaCatalogueMain", "ListMediaPerPage" );
        }

        $db =& eZDB::globalDatabase();

        $db->query_single( $count, "
                SELECT count( DISTINCT eZMediaCatalogue_Media.ID ) AS Count
                FROM eZMediaCatalogue_Media, eZMediaCatalogue_Category, eZMediaCatalogue_MediaCategoryLink
                WHERE
                eZMediaCatalogue_MediaCategoryLink.MediaID = eZMediaCatalogue_Media.ID
                AND
                eZMediaCatalogue_Category.ID = eZMediaCatalogue_MediaCategoryLink.CategoryID
                AND
                eZMediaCatalogue_Category.ID='$this->ID'", "Count" );

        return $count;
    }

    /*!
      Returns every media in a category as a array of eZMedia objects.
    */
    function &media( $sortMode = "time", $offset = 0, $limit = -1 )
    {
       $db =& eZDB::globalDatabase();

       $return_array = array();
       $article_array = array();

       $db->array_query( $file_array, "
                SELECT eZMediaCatalogue_Media.ID AS MediaID, eZMediaCatalogue_Media.OriginalFileName
                FROM eZMediaCatalogue_Media, eZMediaCatalogue_Category, eZMediaCatalogue_MediaCategoryLink
                WHERE
                eZMediaCatalogue_MediaCategoryLink.MediaID = eZMediaCatalogue_Media.ID
                AND
                eZMediaCatalogue_Category.ID = eZMediaCatalogue_MediaCategoryLink.CategoryID
                AND
                eZMediaCatalogue_Category.ID='$this->ID'
                GROUP BY eZMediaCatalogue_Media.ID, eZMediaCatalogue_Media.OriginalFileName ORDER BY eZMediaCatalogue_Media.OriginalFileName",
       array( "Limit" => $limit,
              "Offset" => $offset ) );

       for ( $i = 0; $i < count( $file_array ); $i++ )
       {
           $return_array[$i] = new eZMedia( $file_array[$i][$db->fieldName("MediaID")], false );
       }

       return $return_array;
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $UserID;
    var $ExcludeFromSearch;
}

?>