<?php
//
// $Id: ezlinkcategory.php 8926 2002-01-11 09:33:44Z kaid $
//
// Definition of eZLinkCategory class
//
// Created on: <27-Jun-2001 12:41:48 br>
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

//!! eZLink
//! The eZLinkCategory class handles URL links.


include_once( "classes/ezdb.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZLinkCategory
{
    /*!
      Constructor
    */
    function __construct( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->ImageID = 0;
            $this->get( $this->ID );
        }
    }

    /*!
      Saves a group to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        $db->begin();

        $db->lock( "eZLink_Category" );

        $nextID = $db->nextID( "eZLink_Category", "ID" );
        $queryText = "INSERT INTO eZLink_Category
                (ID, Parent, Name, ImageID, Description, SectionID)
                VALUES
                ('$nextID',
                 '$this->Parent',
                 '$name',
                 '$this->ImageID',
                 '$description',
                 '$this->SectionID' )";

        $res = $db->query( $queryText );

        $db->unlock();

        $this->ID = $nextID;

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Update the database.
    */
    function update()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        $db->begin( );

        $res = $db->query( "UPDATE eZLink_Category SET
                Name='$name',
                Description='$description',
                Parent='$this->Parent',
                ImageID='$this->ImageID',
                SectionID='$this->SectionID'
                WHERE ID='$this->ID'" );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Remove links from the database.
    */
    static public function removeLink( $value, $categoryid = false )
    {
        if( is_a( $value, "eZLink" ) )
            $linkID = $value->id();
        else if (is_Numeric( $value ) )
            $linkID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = 0;

        $db =& eZDB::globaldatabase();
        $db->query( "DELETE FROM eZLink_LinkCategoryLink
                     WHERE CategoryID='$categoryid' AND
                           LinkID='$linkID'" );
    }


    /*!
      Add a link to the database.
    */
    static public function addLink( $value, $categoryid = false )
    {
        if ( is_a( $value, "eZLink" ) )
            $linkID = $value->id();
        else if ( is_nummeric( $value ) )
            $linkID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = 0;

        $db =& eZDB::globalDatabase();

        $db->begin();
        $db->lock( "eZLink_LinkCategoryLink" );

        $nextID = $db->nextID( "eZLink_LinkCategoryLink", "ID" );
        $res[] = $db->query( "INSERT INTO eZLink_LinkCategoryLink
                              (ID, LinkID, CategoryID)
                              VALUES
                              ('$nextID', '$linkID', '$categoryid') ");
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    /*!
      Delete from database.
    */
/*    function delete( $id = -1 )
    {
        if( $id == -1 )
            $id = $this->ID;

        // delete children, and links
        $children = eZLinkCategory::getByParent( $id );
        foreach( $children as $child )
        {
            $child->delete();
        }
        $links = eZLinkCategory::links( -1, -1, true, $id );
        foreach( $links as $link )
        {
            $link->delete();
        }
        // delete self
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZLink_LinkCategoryLink WHERE CategoryID='$id'" );
        $db->query( "DELETE FROM eZLink_LinkCategoryDefinition WHERE CategoryID='$id'" );
        $db->query( "DELETE FROM eZLink_Category WHERE ID='$id'" );
        }*/

    /*!
      Delete from database.
    */
    function delete( $id = -1 )
    {
        if( $id == -1 )
            $id = $this->ID;

        $res = array(); // put the database results in here.

        // get all children categories recursivly
        $categories = eZLinkCategory::getByParent( $id, true, true );
        $categories[] = $id; // add self

        // get all the links
        $links = array();
        foreach( $categories as $category )
        {
            $links = array_merge( eZLinkCategory::links( -1, -1, true, $category, true ), $links );
        }

        // now lets delete all the links..
        $db =& eZDB::globalDatabase();
        $db->begin();
        foreach( $links as $link )
        {
            $res[] = $db->query( "DELETE FROM eZLink_Hit WHERE Link='$link'" );
            $res[] = $db->query( "DELETE FROM eZLink_Link WHERE ID='$link'" );
            $res[] = $db->query( "DELETE FROM eZLink_TypeLink WHERE LinkID='$link'" );
            $res[] = $db->query( "DELETE FROM eZLink_AttributeValue WHERE LinkID='$link'" );
            $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryDefinition WHERE LinkID='$link'" );
            $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryLink WHERE LinkID='$link'" );
        }
        // then we delete the categories
        foreach( $categories as $category )
        {
            $res[] = $db->query( "DELETE FROM eZLink_Category WHERE ID='$category'" );
            // these two should not be nesseccary but we run them anyway, so we are sure that
            // everything is cleaned up.
            $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryLink WHERE CategoryID='$category'" );
            $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryDefinition WHERE CategoryID='$category'" );
        }
        eZDB::finish( $res, $db );
    }


    /*!
      Fetch out a group from the database.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $linkcategory_array,  "SELECT * FROM eZLink_Category WHERE ID='$id'" );
        if ( count( $linkcategory_array ) > 1 )
        {
            die( "error, more groups with the same id" );
        }
        else if ( count( $linkcategory_array ) == 1 )
        {
            $this->ID =& $linkcategory_array[0][$db->fieldName("ID")];
            $this->Name =& $linkcategory_array[0][$db->fieldName("Name")];
            $this->Description =& $linkcategory_array[0][$db->fieldName("Description")];
            $this->Parent =& $linkcategory_array[0][$db->fieldName("Parent")];
            $this->ImageID =& $linkcategory_array[0][$db->fieldName("ImageID")];
            $this->SectionID =& $linkcategory_array[0][$db->fieldName("SectionID")];
        }
    }

    /*!
      Print out the group path.
    */
    function &path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
			if ( isset( $this->ID ) )
	            $categoryID = $this->ID;
			else
				$categoryID = false;
        }

        $category = new eZLinkCategory( $categoryID );

        $path = array();

        $parent = $category->parent();

        if ( $parent != 0 && $parent != '' )
        {
            $path = array_merge( $path, $this->path( $parent ) );
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
      \static
      Fetch out parent.
    */
    function getByParent( $value, $idOnly = false, $recursive = false )
    {
        if ( is_a( $value, 'eZLinkCategory' ) )
            $id = $value->id();
        else
            $id = $value;

        $db =& eZDB::globalDatabase();
        $parent_array = array();
        $return_array = array();

        $db->array_query( $parent_array, "SELECT ID FROM eZLink_Category
                                                 WHERE Parent='$id' ORDER BY Name" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $catID = $parent_array[$i][$db->fieldName("ID")];
            if( $idOnly )
                $return_array[] = $catID;
            else
                $return_array[] = new eZLinkCategory( $catID );

            if( $recursive )
                $return_array = array_merge( eZLinkCategory::getByParent( $catID, true, true ), $return_array );
        }

        return $return_array;
    }


    /*!
      Return the count of links in incoming.
     */
    function &getTotalIncomingLinks()
    {
        $db =& eZDB::globalDatabase();

        $count = 0;
        $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE Accepted='0'" );
        $count = $link_count[0][$db->fieldName("LinkCount")];

        return $count;
    }

    /*!
      Fetch everything.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $parnet_array = array();
        $return_array = array();

        $db->array_query( $parent_array, "SELECT ID FROM eZLink_Category ORDER BY Name" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[$i] = new eZLinkCategory( $parent_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Fetch everything and return the result in a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZLinkCategory( $parentID );

        $categoryList = $category->getByParent( $category );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZLinkCategory( $category->id() ), $level ) );

            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }
        }
        return $tree;
    }

    /*!
      Returns all the links in the current category.
      If limit or offset is set to -1 all links are returned.

      Default limit is set to 30.
    */
    function links( $offset=0, $limit=30, $fetchUnAccepted=false, $id = -1, $idOnly = false )
    {
        $db =& eZDB::globalDatabase();

        if( $id == -1 )
            $id = $this->id();
        $returnArray = array();

        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND eZLink_Link.Accepted='1' ";

        $query = "SELECT eZLink_Link.ID, eZLink_Link.Name
                           FROM eZLink_LinkCategoryLink, eZLink_Link
                           WHERE
                                eZLink_Link.ID=eZLink_LinkCategoryLink.LinkID AND
                                eZLink_LinkCategoryLink.CategoryID='$id'
                               $fetchUnAccepted
                           ORDER BY eZLink_Link.Name";

        if( $limit != -1 && $offset != -1 )
            $db->array_query( $linkArray, $query ,
                    array( "Limit" => $limit, "Offset" => $offset ) );
        else
            $db->array_query( $linkArray, $query );

        foreach( $linkArray as $link )
        {
            if( $idOnly )
                $returnArray[] =  $link[$db->fieldName("ID")];
            else
                $returnArray[] = new eZLink( $link[$db->fieldName("ID")] );
        }
        return $returnArray;
    }


    /*!
      Returns the total numbers of links in the current category.
    */
    function linkCount( $fetchUnAccepted=false )
    {
        $db =& eZDB::globalDatabase();

        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND eZLink_Link.Accepted='1' ";

        $query = "SELECT count( eZLink_Link.ID ) AS Count
                  FROM  eZLink_LinkCategoryLink, eZLink_Link
                  WHERE
                        eZLink_Link.ID=eZLink_LinkCategoryLink.LinkID AND
                        eZLink_LinkCategoryLink.CategoryID='$this->ID'
                        $fetchUnAccepted";

        $db->array_query( $linkArray, $query );

        return $linkArray[0][$db->fieldName("Count")];
    }

    /*!
      Return the id of the group.
    */
    function id()
    {
		if ( isset( $this->ID ) )
	        return $this->ID;
		else
			return "";
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
    function sectionIDStatic($categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SectionID from eZLink_Category WHERE ID='$categoryID'");

        $sectionID = $res[$db->fieldName("SectionID")];

        if ( $sectionID > 0 )
            return $sectionID;
        else
            return false;
    }

    /*!
      Sets the name of a group.
    */
    function setName( &$value )
    {
        $this->Name = ( $value );
    }

    /*!
      Sets the description of a group.
    */
    function setDescription( &$value )
    {
        $this->Description = ( $value );
    }

    /*!
      Sets the parentID of a group.
    */
    function setParent( $value )
    {
        $this->Parent = ( $value );
    }

    /*!
      Sets the section of the category
    */
    function setSectionID( $value )
    {
        $this->SectionID = $value;
    }

   /*!
      Return the name of the link.
    */
    function name()
    {
	if( !is_null( $this->Name ) )
        return htmlspecialchars( $this->Name );

	return $this->Name;
    }

    /*!
      Return the name of the link.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Return the parent id of the group.
    */
    function parent()
    {
		if ( isset( $this->Parent ) )
	        return $this->Parent;
		else
			return "";
    }

    /*!
        Set an image for this category.
     */
    function setImage( $value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $this->ImageID = $value->id();
        }
        elseif( is_numeric( $value ) )
        {
            $this->ImageID = $value;
        }
    }

    /*!
      Returns the image id.
    */
    function &image( )
    {
        $ret = false;
        if ( $this->ImageID != 0 )
        {
            $ret = new eZImage( $this->ImageID );
        }

        return $ret;
    }

    /*!
      Delete the current image that belong to this eZLinkGroup object.
    */
    function deleteImage()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $result, "SELECT ImageID FROM eZLink_Category WHERE ID='$this->ID'" );

        foreach ( $result as $item )
        {
            $image = new eZImage( $item[$db->fieldName("ImageID")] );
            $image->delete();
        }

        $db->query( "UPDATE eZLink_Category set ImageID='0' WHERE ID='$this->ID'" );
    }

    var $ID;
    var $Name;
    var $Description;
    var $Parent;
    var $ImageID;
    var $SectionID;
}

?>