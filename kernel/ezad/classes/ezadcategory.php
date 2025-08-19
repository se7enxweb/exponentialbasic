<?php
//
// $Id: ezadcategory.php 7906 2001-10-16 14:25:05Z br $
//
// Definition of eZAdCategory class
//
// Created on: <22-Nov-2000 20:32:30 bf>
//
// This source file is part of Exponential Basic, publishing software.
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

//!! eZAd
//! eZAdCategory handles banner ad categories.
/*!

  \sa eZAd
*/

/*!TODO

 */

// include_once( "classes/ezdb.php" );
// include_once( "ezad/classes/ezad.php" );

class eZAdCategory
{
    /*!
      Constructs a new eZAdCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->ParentID = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZAdCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAd_Category" );
            $nextID = $db->nextID( "eZAd_Category", "ID" );

            $res = $db->query( "INSERT INTO eZAd_Category
                         ( ID, Name, Description, ParentID )
                         VALUES
                         ( '$nextID',
                           '$name',
                           '$description',
                           '$this->ParentID' )" );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZAd_Category SET
                         Name='$name',
                         Description='$description',
                        ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZAdCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->array_query( $ad_id_array, "SELECT AdID FROM eZAd_AdCategoryLink WHERE
                                            CategoryID='$this->ID'");

            if ( count( $ad_id_array ) > 0 )
            {
                foreach( $ad_id_array as $ad_id )
                {
                    $link = new eZAd( $ad_id[$db->fieldName( "AdID" )] );
                    $link->delete();
                }
            }

            $db->query( "DELETE FROM eZAd_AdCategoryLink
                                     WHERE CategoryID='$this->ID'" );

            $db->query( "DELETE FROM eZAd_Category WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZAd_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $ret = true;
                $this->ID = $category_array[0][$db->fieldName("ID")];
                $this->Name = $category_array[0][$db->fieldName("Name")];
                $this->Description = $category_array[0][$db->fieldName("Description")];
                $this->ParentID = $category_array[0][$db->fieldName("ParentID")];
            }
        }

        return $ret;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZAdCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID, Name FROM eZAd_Category ORDER BY Name" );

        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZAdCategory( $category_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZAdCategory objects.
    */
    function getByParent( $parent  )
    {
        if ( is_a( $parent, "eZAdCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZAd_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZAdCategory( $category_array[$i][$db->fieldName("ID")], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
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

        $category = new eZAdCategory( $categoryID );

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

    function getTree( $parentID=0, $level=0 )
    {
        $category = new eZAdCategory( $parentID );

        $categoryList = $category->getByParent( $category );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $returnObj = new eZAdCategory( $category->id() ), $level ) );

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
    function name( $html = true )
    {
       if( $this->Name != '' && $html )
           return htmlspecialchars( $this->Name );
        return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
        if( $this->Description != '' && $html )
           return htmlspecialchars( $this->Description );
        return $this->Description;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZAdCategory( $this->ParentID );
       }
       else
       {
           return 0;
       }
    }


    /*!
      Returns true if the category is to be excluded
      from search, false if not.
    */
    function excludeFromSearch( )
    {
       $ret = false;
       if ( $this->ExcludeFromSearch  == "true" )
       {
           $ret = true;
       }

       return $ret;
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
      Sets the parent category.
    */
    function setParent( $value )
    {
       if ( is_a( $value, "eZAdCategory" ) )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
     Sets the exclude from search bit.
     The argumen can be true or false.
    */
    function setExcludeFromSearch( $value )
    {
       if ( $value == true )
       {
           $this->ExcludeFromSearch = "1";
       }
       else
       {
           $this->ExcludeFromSearch = "0";
       }
    }

    /*!
      Adds a ad to the category.
    */
    function addAd( $value )
    {
       if ( is_a( $value, "eZAd" ) )
       {
           $db =& eZDB::globalDatabase();

            $db->begin( );

            $db->lock( "eZAd_AdCategoryLink" );
            $nextID = $db->nextID( "eZAd_AdCategoryLink", "ID" );

            $adID = $value->id();

            $query = "INSERT INTO
                           eZAd_AdCategoryLink
                      ( ID, CategoryID, AdID )
                      VALUES
                      ( '$nextID',
                        '$this->ID',
                        '$adID' )";

            $res = $db->query( $query );

            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
       }
    }

    /*!
      Returns every ad in a category as a array of eZAd objects.

      It does not return unactive ads unless $fetchUnActive is set to true.
    */
    function &adlist( $sortMode="name",
                   $fetchUnActive=false,
                   $offset=0,
                   $limit=50 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $ad_array = array();

        $fetchActiveSQL = "";
        if ( $fetchUnActive == false )
        {
            $fetchActiveSQL = "AND eZAd_Ad.IsActive = '1'";
        }

        $orderBySQL = "Ad.Name ASC";

        $db->array_query( $ad_array,
        "SELECT Ad.ID, Ad.Name
         FROM eZAd_Ad AS Ad, eZAd_AdCategoryLink AS ACL
         WHERE Ad.ID=ACL.AdID AND ACL.CategoryID='$this->ID'
         ORDER BY $orderBySQL", array( "Limit" => $limit, "Offset" => $offset ) );

        foreach( $ad_array as $ad )
        {
            $return_array[] = new eZAd( $ad[$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Returns every ad in a category as a array of eZAd objects.

      It does not return unactive ads unless $fetchUnActive is set to true.
    */
    function &ads( $sortMode="name",
                   $fetchUnActive=false,
                   $offset=0,
                   $limit=50 )
    {
        $db =& eZDB::globalDatabase();

       $return_array = array();
       $ad_array = array();

       $fetchActiveSQL = "";
       if ( $fetchUnActive == false )
       {
           $fetchActiveSQL = "AND eZAd_Ad.IsActive = '1'";
       }

       if ( $sortMode == "name" )
           $orderBySQL = "eZAd_Ad.Name ASC";
       else
           $orderBySQL = "eZAd_View.ViewOffsetCount ASC";

       $db->array_query( $ad_array,
       "SELECT eZAd_Ad.ID, eZAd_View.ViewCount, eZAd_Ad.Name
        FROM
            eZAd_Ad,
            eZAd_AdCategoryLink,
            eZAd_View
        WHERE
           eZAd_AdCategoryLink.AdID = eZAd_Ad.ID
           AND eZAd_View.AdID = eZAd_Ad.ID
           $fetchActiveSQL
           AND eZAd_AdCategoryLink.CategoryID='$this->ID'
            ORDER BY $orderBySQL",
       array( "Limit" => $limit, "Offset" => $offset ) );

       if ( count( $ad_array ) > 0 )
       {
           for ( $i=0; $i < count($ad_array); $i++ )
           {
               $return_array[$i] = new eZAd( $ad_array[$i][$db->fieldName("ID")] );
           }
       }

       return $return_array;
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $ExcludeFromSearch;

}

?>