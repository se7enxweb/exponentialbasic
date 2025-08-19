<?php
//
// $Id: eztopic.php 9475 2002-04-25 11:42:50Z bf $
//
// Definition of eZTopic class
//
// Created on: <01-Jun-2001 12:03:53 bf>
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

//!! eZUser
//! eZTopic handles article topics
/*!

  \sa eZArticle eZArticeCategory
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezarticle/classes/ezarticle.php" );


class eZTopic
{
    /*!
      Constructs a new eZTopic object.
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
      Stores or updates a eZTopic object in the database.
    */
    function store()
    {
	$db =& eZDB::globalDatabase();

	$db->begin( );

	$name = $db->escapeString( $this->Name );
	$description = $db->escapeString( $this->Description );

	if ( !isset( $this->ID ) )
	{
	    $db->lock( "eZArticle_Topic" );
	    $nextID = $db->nextID( "eZArticle_Topic", "ID" );

	    $timeStamp =& (new eZDateTime())->timeStamp( true );

	    $res = $db->query( "INSERT INTO eZArticle_Topic
			 ( ID, Name, Created, Description )
			 VALUES
			 ( '$nextID',
				   '$name',
			   '$timeStamp',
			   '$description' )
		       " );

			$this->ID = $nextID;
	}
	else
	{
	    $res = $db->query( "UPDATE eZArticle_Topic SET
				 Name='$name',
			 Created=Created,
			 Description='$description'
			WHERE ID='$this->ID'" );
	}

	$db->unlock();

	if ( $res == false )
	    $db->rollback( );
	else
	    $db->commit();

	return true;
    }

    /*!
      Deletes a eZTopic object from the database.
    */
    function delete()
    {
	$db =& eZDB::globalDatabase();

	if ( isset( $this->ID ) )
	{
	    $db->query( "UPDATE eZArticle_Article SET TopicID=0 WHERE TopicID='$this->ID'" );
	    $db->query( "DELETE FROM eZArticle_Topic WHERE ID='$this->ID'" );

	}

	return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
		$db =& eZDB::globalDatabase();
	
		$ret = false;
		if ( $id != "" )
		{
		    $db->array_query( $author_array, "SELECT * FROM eZArticle_Topic WHERE ID='$id'" );
		    if( count( $author_array ) == 1 )
		    {
				$this->ID =& $author_array[0][$db->fieldName("ID")];
				$this->Name =& $author_array[0][$db->fieldName("Name")];
				$this->Description =& $author_array[0][$db->fieldName("Description")];
				$ret = true;
		    }
		    elseif( count( $author_array ) == 1 )
		    {
				$this->ID = 0;
		    }
		}
		return $ret;
    }


    /*!
	\static
      Fetches an eZTopic object from the database with the same name as entered.

      Always returns an object of type eZTopic, but with ID 0 if a suitable information
      isn't found in the db.
    */
    function &getByName( $name )
    {
		$db =& eZDB::globalDatabase();
	
		$topic = new eZTopic();
	
		$name = $db->fieldName( $name );
	
		if( $name != "" )
		{
		    $db->array_query( $author_array, "SELECT * FROM eZArticle_Topic WHERE Name='$name'" );
	
		    if( count( $author_array ) == 1 )
		    {
				$topic = new eZTopic( $author_array[0][$db->fieldName("ID")] );
		    }
		}
	
		return $topic;
    }

    /*!
      Fetches the user id from the database. And returns a array of eZTopic objects.
    */
    function &getAll(  )
    {
		$db =& eZDB::globalDatabase();
	
		$return_array = array();
		$topic_array = array();
	
	
		$db->array_query( $topic_array, "SELECT ID, Name FROM eZArticle_Topic
						ORDER By Name" );
	
		foreach ( $topic_array as $topic )
		{
		    $return_array[] = new eZTopic( $topic[$db->fieldName("ID")] );
		}
		return $return_array;
    }
    
    /*!
      Returns all articles with the current topic.
    */
    function &articles(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $article_array = array();


        $ExcludeCategories = "";

        $return_array = array();
        $article_array = array();

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        $groupSQL = "";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );

            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ) ";

        if ( $usePermission )
            $permissionSQL = $loggedInSQL;
        else
            $permissionSQL = "";

        $excludeSQL = " AND Category.ExcludeFromSearch = '0'";

        // fetch only published articles
        if ( $fetchNonPublished  == true )
        {
            $excludeSQL = "";
            if ( $permissionSQL == "" )
                $publishedSQL = " Article.IsPublished = '0' AND ";
            else
                $publishedSQL = " AND Article.IsPublished = '0' AND ";
        }
        // fetch only non-published articles
        else
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Article.IsPublished = '1' AND ";
            else
                $publishedSQL = " AND Article.IsPublished = '1' AND ";
        }

        // fetch only published articles
        if ( $fetchNonPublished  == "pending" )
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Article.IsPublished = '2' AND ";
            else
                $publishedSQL = " AND Article.IsPublished = '2' AND ";
        }


        if ($ExcludeCategories && $ExcludeCategories<>"") $excludeSQL .= " AND Category.ID NOT IN (".$ExcludeCategories.")";

        $query = "SELECT Article.ID as ID
                  FROM eZArticle_ArticleCategoryDefinition as Definition,
                       eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_CategoryPermission as CategoryPermission,
                       eZArticle_Category AS Category
                  WHERE TopicID='$this->ID' AND
                        $permissionSQL
                        $publishedSQL
                        Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        $excludeSQL
                        AND Definition.ArticleID=Article.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                 GROUP BY Article.ID, Article.IsPublished ORDER BY Article.Name";

		$db->array_query( $article_array, $query );
	
		foreach ( $article_array as $article )
		{
		    $return_array[] = new eZArticle( $article[$db->fieldName("ID")] );
		}
		return $return_array;
    }

    /*!
      Returns the object id.
    */
    function id()
    {
		return $this->ID;
    }

    /*!
      Returns the name.
    */
    function name( $html = true )
    {
		if( $html && isset( $this->Name ) )
		    return htmlspecialchars( $this->Name );
		return $this->Name;
    }


    /*!
      Returns the description.
    */
    function description( )
    {
       return $this->Description;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the description.
    */
    function setDescription( $value )
    {
       $this->Description = $value;
    }

    var $ID;
    var $Name;
    var $Description;
}

?>