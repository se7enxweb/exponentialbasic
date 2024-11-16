<?php
//
// $Id: ezarticletype.php 6206 2001-07-19 12:19:22Z jakobn $
//
// Definition of eZArticleType class
//
// Created on: <01-Jun-2001 13:43:02 pkej>
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


//!! eZArticle
//! This class handles different article types.
/*!
  A article type is a group of articles with the same special attributes. For example
  a article type could be cars, with the defined attributes: horsepower, weight ...

  \code

  \endcode
  \sa eZArticle
*/

include_once( "classes/ezdb.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );

class eZArticleType
{
    /*!
      Constructs a new eZArticleType object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZArticleType( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZArticletype object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZArticle_Type" );

            $nextID = $db->nextID( "eZArticle_Type", "ID" );

            $res = $db->query( "INSERT INTO eZArticle_Type
                         ( ID, Name )
                         VALUES
                         ( '$nextID', '$name' )" );

			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZArticle_Type SET
		                         Name='$name' WHERE ID='$this->ID'" );
        }

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the article type object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != -1  )
        {
            $db->array_query( $type_array, "SELECT * FROM eZArticle_Type WHERE ID='$id'" );

            if ( count( $type_array ) > 1 )
            {
                die( "Error: Article type's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $type_array ) == 1 )
            {
                $this->ID =& $type_array[0][$db->fieldName("ID")];
                $this->Name =& $type_array[0][$db->fieldName("Name")];

                $ret = true;
            }
        }
        return $ret;
    }

    /*!
        \static
      Fetches the article type object based on name.

      Returns an article type object.
    */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $type = new eZArticleType();

        $name = $db->escapeString( $name );

        if ( $name != ""  )
        {
            $db->array_query( $type_array, "SELECT * FROM eZArticle_Type WHERE Name='$name'" );

            if ( count( $type_array ) == 1 )
            {
                $type = new eZArticleType($type_array[0][$db->fieldName("ID")]);
            }
        }

        return $type;
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $type_array = array();

        $db->array_query( $type_array, "SELECT ID, Name FROM eZArticle_Type ORDER BY Name" );

        for ( $i=0; $i<count($type_array); $i++ )
        {
            $return_array[$i] = new eZArticleType( $type_array[$i][$db->fieldName("ID")], 0 );
        }

        return $return_array;
    }

    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        // delete all attributes and values
        $attributes = $this->attributes();
        foreach ( $attributes as $attribute )
        {
            $attribute->delete();
        }

        $db->query( "DELETE FROM eZArticle_ArticleTypeLink WHERE TypeID='$this->ID'" );
        $db->query( "DELETE FROM eZArticle_Type WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the option.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the name of the option.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Returns an array of eZArticleAttribute objects which
      are associated with the current article type.
    */
    function attributes( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $attribute_array = array();

        $db->array_query( $attribute_array, "SELECT ID, Placement
                                                      FROM eZArticle_Attribute
                                                      WHERE TypeID='$this->ID' ORDER BY Placement" );

        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $attrib_id = $attribute_array[$i][$db->fieldName("ID")];
            $return_array[$i] = $as_object ? new eZArticleAttribute( $attrib_id, false ) : $attrib_id;
        }

        return $return_array;
    }

    /*!
      Returns every attribute belonging to an article as an array of eZArticleAttribute objects.
    */
    function attributesByArticle( $article )
    {
        $ret = false;

        if ( is_a( $article, "eZArticle" ) )
        {
            $db =& eZDB::globalDatabase();

            $articleID = $article->id();

            $return_array = array();
            $attribute_array = array();

            $db->array_query( $attribute_array, "
            SELECT Attribute.ID, Attr.TypeID, Attr.Placement
            FROM
                eZArticle_AttributeValue AS Value,
                eZArticle_Attribute AS Attr
            WHERE
                Value.ArticleID='$articleID'
            AND Value.AttributeID=Attr.ID
            AND Attr.TypeID='$this->ID'
            ORDER BY Attr.TypeID, Attr.Placement" );

            for ( $i=0; $i < count( $attribute_array ); $i++ )
            {
                $return_array[$i] = new eZArticleAttribute( $attribute_array[$i][$db->fieldName("AttributeID")] );
            }

            $ret = true;
        }
        return $ret;
    }

    var $ID;
    var $Name;
}

?>
