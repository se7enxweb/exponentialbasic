<?php
//
// $Id: ezlink.php 9782 2003-02-26 09:03:33Z br $
//
// Definition of eZLink class
//
// Created on: <15-Sep-2000 14:40:06 bf>
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
//! The eZLink class handles URL links.
/*!

  Example code:
  \code
  // Create a new link and set some values.
  $link = new eZLink();
  $link->setName( "ZEZ website" );
  $link->Description( "zez.org is a page dedicated to all kinds of computer programming." );
  $link->KeyWords( "code programing c++ php sql python" );
  $link->setAccepted( true );
  $link->setUrl( "zez.org" );

  // Store the link to the datavase.
  $link->store();

  // Check if the url exist in the database.
  $link->checkUrl( "zez.org" );

  // Get all the links in a category.
  $link->getByCategory( $linkCategoryID );

  // Get all the not accepted links.
  $link->getNotAccepted();

  \endcode

  \sa eZLinkCategory eZHit eZQuery
*/

include_once( "classes/ezquery.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdb.php" );

class eZLink
{
    /*!
      Constructor
    */
    function __construct( $id = -1  )
    {
        $this->ImageID = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a link to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $description = $db->escapeString( $this->Description );
        $name = $db->escapeString( $this->Name );
        $url = $db->escapeString( $this->Url );
        $keywords = $db->escapeString( $this->KeyWords );
        $timeStamp =& eZDateTime::timeStamp( true );

        if ( !isSet( $this->ID) )
        {
            $db->lock( "eZLink_Link" );
            $nextID = $db->nextID( "eZLink_Link", "ID" );

            $res = $db->query( "INSERT INTO eZLink_Link
                ( ID,
                  Name,
                  Description,
                  KeyWords,
                  Created,
                  Modified,
                  Url,
                  ImageID,
                  Accepted )
                VALUES
                ( '$nextID',
                  '$name',
                  '$description',
                  '$keywords',
                  '$timeStamp',
                  '$timeStamp',
                  '$url',
                  '$this->ImageID',
                  '$this->Accepted' )" );

            $this->ID = $nextID;

            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZLink_Link SET
                Name='$name',
                Description='$description',
                KeyWords='$keywords',
                Modified='$timeStamp',
                Url='$url',
                ImageID='$this->ImageID',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );
        }

        if ( $res == false )
        {
            $db->rollback();
        }
        else
        {
            $db->commit();
        }
    }

    /*!
      Sets the links type.
    */
    function setType( $type )
    {
        if ( is_a( $type, "eZLinkType" ) )
        {
            $db =& eZDB::globalDatabase();

            $db->begin();

            $typeID = $type->id();

            $res[] = $db->query( "DELETE FROM eZLink_AttributeValue
                                     WHERE LinkID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZLink_TypeLink
                                     WHERE LinkID='$this->ID'" );

            $db->lock( "eZLink_TypeLink" );

            $nextID = $db->nextID( "eZLink_TypeLink", "ID" );

            $query = "INSERT INTO eZLink_TypeLink
                      (ID, TypeID, LinkID)
                      VALUES
                      ('$nextID',
                       '$typeID',
                       '$this->ID')";

            $res[] = $db->query( $query );

            $db->unlock();

            if ( in_array( false, $res ) )
                $db->rollback();
            else
                $db->commit();

        }
    }

    /*!
      Returns the link's type.
    */
    function type()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT TypeID FROM
                                 eZLink_TypeLink WHERE LinkID='$this->ID'" );

        $type = false;

        if ( count( $res ) == 1 )
        {
            $type = new eZLinkType( $res[0][$db->fieldName("TypeID")] );
        }

        return $type;
    }

    /*!
      Removes the links type definition.
    */
    function removeType()
    {
        $db =& eZDB::globalDatabase();

        // delete values
        $db->query( "DELETE FROM eZLink_AttributeValue WHERE LinkID='$this->ID'" );

        $db->query( "DELETE FROM eZLink_TypeLink WHERE LinkID='$this->ID'" );

    }

    /*!
      Update to the database.
    */
    function update()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $description = $db->escapeString( $this->Description );
        $name = $db->escapeString( $this->Name );
        $url = $db->escapeString( $this->Url );
        $keywords = $db->escapeString( $this->KeyWords );

        $timeStamp =& eZDateTime::timeStamp( true );

        $res = $db->query( "UPDATE eZLink_Link SET
                Name='$name',
                Description='$description',
                KeyWords='$keywords',
                Modified='$timeStamp',
                Url='$url',
                ImageID='$this->ImageID',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );


        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

    }

    /*!
      Remove the link and the hits that belongs to the link.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->begin();

        $res[] = $db->query( "DELETE FROM eZLink_Hit WHERE Link='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZLink_Link WHERE ID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZLink_TypeLink WHERE LinkID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZLink_AttributeValue WHERE LinkID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryDefinition WHERE LinkID='$this->ID'" );
        $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryLink WHERE LinkID='$this->ID'" );

        if ( in_array( $res, false ) )
            $db->rollback();
        else
            $db->commit();
    }

    /*!
      Fetches out informasjon from the daatabase where ID=$id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $link_array = array();
            $db->array_query( $link_array, "SELECT * FROM eZLink_Link WHERE ID='$id'" );
            if ( count( $link_array ) > 1 )
            {
                die( "Error: more than one link with the same ID found" );
            }
            else if ( count( $link_array ) == 1 )
            {
                $this->ID =& $link_array[0][$db->fieldName("ID")];
                $this->Name =& $link_array[0][$db->fieldName("Name")];
                $this->Description =& $link_array[0][$db->fieldName("Description")];
                $this->KeyWords =& $link_array[0][$db->fieldName("KeyWords")];
                $this->Created =& $link_array[0][$db->fieldName("Created")];
                $this->Modified =& $link_array[0][$db->fieldName("Modified")];
                $this->Accepted =& $link_array[0][$db->fieldName("Accepted")];
                $this->Url =& $link_array[0][$db->fieldName("Url")];
                $this->ImageID =& $link_array[0][$db->fieldName("ImageID")];
            }
        }

    }

    /*!
      Fetchs out the links where the linkcategory=$id. Fetchs only accepted links.
    */
    function &getByCategory( $id )
    {
        $db =& eZDB::globalDatabase();

        $link_array = array();
        $return_array = array();

        $db->array_query( $link_array, "SELECT eZLink_Link.ID FROM eZLink_Link, eZLink_LinkCategoryLink
                                        WHERE CategoryID='$id' AND eZLink_Link.ID=LinkID AND Accepted='1' ORDER BY Name" );

        for( $i=0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Fetches out the links that is not accepted.

      Default limit is set to 30.
    */
    function &getNotAccepted( $offset = 0, $limit = 30 )
    {
        $db =& eZDB::globalDatabase();

        $link_array = array();
        $return_array = array();

        $db->array_query( $link_array, "SELECT ID, Name FROM eZLink_Link
                                        WHERE Accepted='0' ORDER BY Name",
                          array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i = 0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      Returns the total numbers of links that is not accepted.
    */
    function unAcceptedCount()
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT count( ID ) AS Count
                  FROM eZLink_Link WHERE Accepted='0'";

        $db->array_query( $linkArray, $query );

        return $linkArray[0][$db->fieldName( "Count" )];
    }


    /*!
      Fetches out the last teen accpeted links.
    */
    function &getLastTenDate( $limit, $offset )
    {
        $db =& eZDB::globalDatabase();

        $link_array = array();
        $return_array = array();

        $db->array_query( $link_array,
            "SELECT * FROM eZLink_Link WHERE Accepted='1' ORDER BY Created DESC",
            array( "Limit" => $limit, "Offset" => $offset ) );

        for( $i=0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName( "ID" )] );
        }
        return $return_array;
    }

    /*!
      Fetches out the last teen accpeted links.
    */
    function &getLastTen( $limit, $offset )
    {
        $db =& eZDB::globalDatabase();
        $link_array = 0;

        $db->array_query( $link_array,
            "SELECT * FROM eZLink_Link WHERE Accepted='1' ORDER BY Name DESC",
             array( "Limit" => $limit, "Offset" => $offset ) );

        return $link_array;
    }

    /*!
      Fetches the links that matches the $query.

      Default limit is set to 25.
    */
    function &getQuery( $query, $limit, $offset )
    {
        $db =& eZDB::globalDatabase();
        $link_array = array();
        $return_array = array();

        $query = new eZQuery( array( "KeyWords", "Name", "Description" ), $query );
        $query->setPartialCompare( true );
        $query_str = "SELECT ID FROM eZLink_Link WHERE (" .
                      $query->buildQuery()  .
                     ") AND Accepted='1' GROUP BY Name, ID ORDER BY Name";

        $db->array_query( $link_array, $query_str,
                          array( "Limit" => $limit, "Offset" => $offset ) );

        $ret = array();

        foreach( $link_array as $linkItem )
        {
            $ret[] = new eZLink( $linkItem[$db->fieldName( "ID" )] );
        }
        return $ret;
    }


    /*!
      Returns the total count of a query.
    */
    function &getQueryCount( $query  )
    {
        $db =& eZDB::globalDatabase();
        $link_array = 0;

        $query = new eZQuery( array( "KeyWords", "Name", "Description" ), $query );

        $query_str = "SELECT count(ID) AS Count, Name FROM eZLink_Link WHERE (" .
             $query->buildQuery()  .
             ") AND Accepted='1' GROUP BY Name ORDER BY Name";

        $db->array_query( $link_array, $query_str );

        $ret = 0;
        if ( count( $link_array ) == 1 )
            $ret = $link_array[0][$db->fieldName("Count")];

        return $ret;
    }


    /*!
      Fetches all the links.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $category_array = 0;

        $db->array_query( $category_array, "SELECT * FROM eZLink_Link ORDER BY Name" );

        return $category_array;
    }

    /*!
      Check if the url exists.
    */
    function &checkUrl( $url )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $url_array, "SELECT url FROM eZLink_Link WHERE url='$url'" );

        return count( $url_array );
    }

    /*!
      Set's the link's defined category. This is the main category for the link.
      Additional categories can be added with eZLinkCategory::addLink();
    */
    function setCategoryDefinition( $value )
    {
        if ( is_a( $value, "eZLinkCategory" ) )
            $categoryID = $value->id();
        else if ( is_numeric( $value ) )
            $categoryID = $value;
        else
            return false;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $res[] = $db->query( "DELETE FROM eZLink_LinkCategoryDefinition
                            WHERE LinkID='$this->ID'" );
        $db->lock( "eZLink_LinkCategoryDefinition" );
        $nextID = $db->nextID( "eZLink_LinkCategoryDefinition", "ID" );
        $res[] = $db->query( "INSERT INTO eZLink_LinkCategoryDefinition
                           (ID, LinkID, CategoryID) VALUES
                           ('$nextID','$this->ID','$categoryID')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }


    /*!
      Returns the link's definition category
    */
    function categoryDefinition( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $res, "SELECT CategoryID FROM
                            eZLink_LinkCategoryDefinition
                            WHERE LinkID='$this->ID'" );

        $category = false;
        if ( count( $res ) == 1 )
        {
            if ( $as_object )
                $category = new eZLinkCategory( $res[0][$db->fieldName( "CategoryID" )] );
            else
                $category = $res[0][$db->fieldName( "CategoryID" )];
        }
        else
        {
            print ( "<br><b>Failed to get link category definition for ID $this->ID</b>" );
        }

        return $category;
    }

    /*!
      Returns the categories an article is assigned to.
      The categories are returned as an array of eZLinkCategory objects
     */
    function categories( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $ret = array();
        $category_array = array();
        $db->array_query( $category_array, "SELECT * FROM eZLink_LinkCategoryLink
                                       WHERE LinkID='$this->ID'" );
        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZLinkCategory( $category[$db->fieldName( "CategoryID" )] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category[$db->fieldName( "CategoryID" )];
            }
        }
        return $ret;
    }

    /*!
      Sets the link name.
    */
    function setName( &$value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the link description
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }


    /*!
      Sets the link keywords.
    */
    function setKeyWords( &$value )
    {
        $this->KeyWords = ( $value );
    }

    /*!
      Sets if the link is accepted, true/false.
    */
    function setAccepted( $value )
    {
        if ( $value == true )
            $this->Accepted = "1";
        else
            $this->Accepted = "0";
    }

    /*!
      Sets the link URL.
    */
    function setUrl( &$value )
    {
        $this->Url = ( $value );
    }

    /*!
      Returns the link name.
    */
    function &name()
    {
        return htmlspecialchars( $this->Name );
    }


    /*!
      Returns the link description.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the link keywords.
    */
    function &keyWords()
    {
	if( !is_null( $this->KeyWords ) )
        return htmlspecialchars( $this->KeyWords );

	return $this->KeyWords;
    }

    /*!
      Returns the date when the link was created.
    */
    function &created()
    {
        return $this->Created;
    }

    /*!
      Returns the date when the link was modified.
    */
    function &modified()
    {
        return $this->Modified;
    }

    /*!
      Returns true if the link is Accepted, false if not.
    */
    function accepted()
    {
        if ( $this->Accepted == 1 )
            return true;
        else
            return false;
    }

    /*!
      Retruns the url of the link.
    */
    function &url()
    {
        return $this->Url;
    }

    /*!
      Returns the id of the link.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
        Set an image for this category.
     */
    function setImage( &$value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $this->ImageID = $value->id();
        }
        elseif ( is_numeric( $value ) )
        {
            $this->ImageID = $value;
        }
    }

    /*!
      Returns the image as a eZImage object.

      false is returned if no link is assigned to the link.
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
      Delete the current image that belong to this eZLink object.
    */
    function deleteImage()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $result, "SELECT ImageID FROM eZLink_Link WHERE ID='$this->ID'" );

        $db->query( "UPDATE eZLink_Link set ImageID='0' WHERE ID='$this->ID'" );
    }

    var $ID;
    var $Name;
    var $Description;
    var $KeyWords;
    var $Created;
    var $Modified;
    var $Accepted;
    var $ImageID;
    var $Url;
    var $url_array;

}
?>
