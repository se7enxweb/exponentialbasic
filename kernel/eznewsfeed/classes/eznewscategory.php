<?php
//
// $Id: eznewscategory.php 6224 2001-07-20 11:21:41Z jakobn $
//
// Definition of eZNewsCategory class
//
// Created on: <18-Oct-2000 14:05:56 bf>
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

//!! eZNewsFeed
//! eZNewsCategory handles news categories.
/*!

*/

/*!TODO

 */

// include_once( "classes/ezdb.php" );

class eZNewsCategory
{
    /*!
      Constructs a new eZNewsCategory object.

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
      Stores a eZNewsCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZNewsFeed_Category" );
            $nextID = $db->nextID( "eZNewsFeed_Category", "ID" );
            $ret[] = $db->query( "INSERT INTO eZNewsFeed_Category
                               ( ID,
                                 Name,
                                 Description,
                                 ParentID )
                               VALUES
                               ( '$nextID',
                                 '$name',
                                 '$description',
                                 '$this->ParentID' )" );
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZNewsFeed_Category SET
		                         Name='$name',
                                 Description='$description',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }

        eZDB::finish( $ret, $db );

        return $ret;
    }

    /*!
      Deletes a eZNewsCategory object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isset( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZNewsFeed_NewsCategoryLink WHERE CategoryID='$this->ID'" );

            $res[] = $db->query( "DELETE FROM eZNewsFeed_Category WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );

        return in_array( false, $res );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZNewsFeed_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName("ID")];
                $this->Name = $category_array[0][$db->fieldName("Name")];
                $this->Description = $category_array[0][$db->fieldName("Description")];
                $this->ParentID = $category_array[0][$db->fieldName("ParentID")];
            }
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZNewsCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID FROM eZNewsFeed_Category ORDER BY Name" );

        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZNewsCategory( $category_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown.

      The categories are returned as an array of eZNewsCategory objects.
    */
    function getByParent( $parent, $showAll=false, $sortby="name" )
    {
        if ( is_a( $parent, "eZNewsCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            if ( $showAll == true )
            {
                $db->array_query( $category_array, "SELECT ID, Name FROM eZNewsFeed_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }
            else
            {
                $db->array_query( $category_array, "SELECT ID, Name FROM eZNewsFeed_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }


            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZNewsCategory( $category_array[$i][$db->fieldName("ID")], 0 );
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

        $category = new eZNewsCategory( $categoryID );

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
        {
            array_push( $path, array( $category->id(), $category->name() ) );
        }

        return $path;
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
    function name()
    {
       return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZNewsCategory( $this->ParentID );
       }
       else
       {
           return 0;
       }
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
       if ( is_a( $value, "eZNewsCategory" ) )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Adds a news to the category.
    */
    function addNews( $value )
    {
       if ( is_a( $value, "eZNews" ) )
       {
           $db =& eZDB::globalDatabase();
           $db->begin();
           $newsID = $value->id();
           $db->lock( "eZNewsFeed_NewsCategoryLink" );
           $nextID = $db->nextID( "eZNewsFeed_NewsCategoryLink" );
           $ret[] = $db->query( "INSERT INTO eZNewsFeed_NewsCategoryLink
                          ( ID,
                            CategoryID,
                            NewsID )
                          VALUES
                          ( '$nextID',
                            '$this->ID',
                            '$newsID' )" );

           eZDB::finish( $ret, $db );
       }
    }

    /*!
      Returns every news in a category as a array of eZNews objects.

      If $fetchNonPublished is set to "yes" the news which is not published is
      also returned.

      If $fetchNonPublished is set to "no" the news which is not published is not
      returned.

      If $fetchNonPublished is set to "only" only the news which is not published are returned.
    */
    function &newsList( $sortMode="time",
                       $fetchNonPublished="no",
                       $offset=0,
                       $limit=50 )
    {

        $db =& eZDB::globalDatabase();

        $OrderBy = "eZNewsFeed_News.PublishingDate DESC";
        switch( $sortMode )
        {
            case "alpha" :
           {
               $OrderBy = "eZNewsFeed_Name.Name ASC";
           }
           break;
        }

        $return_array = array();
        $news_array = array();


        if ( $fetchNonPublished  == "yes" )
        {
            $db->array_query( $news_array, "
                SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name, eZNewsFeed_Category.ID, eZNewsFeed_Category.Name
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'
                ORDER BY $OrderBy", array( "Limit" => $limit, "Offset" => $offset ) );
       }

        if ( $fetchNonPublished  == "no" )
        {
            $db->array_query( $news_array, "
                SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name, eZNewsFeed_Category.ID, eZNewsFeed_Category.Name
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_News.IsPublished = '1'
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'
                ORDER BY $OrderBy", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        if ( $fetchNonPublished  == "only" )
        {
           $db->array_query( $news_array, "
                SELECT eZNewsFeed_News.ID AS NewsID, eZNewsFeed_News.Name, eZNewsFeed_Category.ID, eZNewsFeed_Category.Name
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_News.IsPublished = '0'
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'
                ORDER BY $OrderBy", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i<count($news_array); $i++ )
        {
            $return_array[$i] = new eZNews( $news_array[$i][$db->fieldName("NewsID")], false );
        }
        return $return_array;
    }


    /*!
      Returns the number of news in a category.

      If $fetchNonPublished is set to "yes" the news which is not published is
      also counted.

      If $fetchNonPublished is set to "no" the news which is not published is not
      counted.

      If $fetchNonPublished is set to "only" only the news which is not published
      are are.
    */
    function &newsListCount( $sortMode="time",
                       $fetchNonPublished="no" )
    {

        $db =& eZDB::globalDatabase();

       $return_array = array();
       $news_array = array();


       if ( $fetchNonPublished  == "yes" )
       {
           $db->array_query( $news_array, "
                SELECT count( eZNewsFeed_News.ID ) AS Count
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'" );
       }

       if ( $fetchNonPublished  == "no" )
       {
           $db->array_query( $news_array, "
                SELECT count( eZNewsFeed_News.ID ) AS Count
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_News.IsPublished = '1'
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'" );
       }

       if ( $fetchNonPublished  == "only" )
       {
           $db->array_query( $news_array, "
                SELECT count( eZNewsFeed_News.ID ) AS Count
                FROM eZNewsFeed_News, eZNewsFeed_Category, eZNewsFeed_NewsCategoryLink
                WHERE
                eZNewsFeed_NewsCategoryLink.NewsID = eZNewsFeed_News.ID
                AND
                eZNewsFeed_News.IsPublished = '0'
                AND
                eZNewsFeed_Category.ID = eZNewsFeed_NewsCategoryLink.CategoryID
                AND
                eZNewsFeed_Category.ID='$this->ID'
                " );
       }


       return $news_array[0][$db->fieldName("Count")];
    }


    var $ID;
    var $ParentID;
    var $Name;
    var $Description;
}

?>