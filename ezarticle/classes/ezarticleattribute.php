<?php
//
// $Id: ezarticleattribute.php 6471 2001-08-16 13:57:05Z jhe $
//
// Definition of eZArticleAttribute class
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
//! This class handles different article attributes.
/*!

  \code

  $attribute = new eZArticleAttribute();
  $attribute->setType( $type );
  $attribute->setName( "Doors" );
  $attribute->store();

  \endcode
  \sa eZArticle
*/

include_once( "classes/ezdb.php" );
include_once( "ezarticle/classes/ezarticletype.php" );

class eZArticleAttribute
{
    /*!
      Constructs a new eZArticleAttribute object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZArticleAttribute( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZArticleattribute object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );

        if ( !isset( $this->ID ) )
        {

            $db->lock( "eZArticle_Attribute" );

            $nextID = $db->nextID( "eZArticle_Attribute", "ID" );
            $timeStamp =& eZDateTime::timeStamp( true );

            $db->array_query( $attribute_array, "SELECT Placement FROM eZArticle_Attribute" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place[$db->fieldName("Placement")];
                $place++;
            }

            $res = $db->query( "INSERT INTO eZArticle_Attribute
                         ( ID, Name, TypeID, Placement, Created )
                         VALUES
                         ( '$nextID',
                          '$name',
		                  '$this->TypeID',
		                  '$place',
                          '$timeStamp' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZArticle_Attribute SET
		                        Name='$name',
		                        TypeID='$this->TypeID' WHERE ID='$this->ID'" );
        }


        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the article attribute object values from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != -1  )
        {
            $db->array_query( $attribute_array, "SELECT * FROM eZArticle_Attribute WHERE ID='$id'" );

            if ( count( $attribute_array ) > 1 )
            {
                die( "Error: Article attribute's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $attribute_array ) == 1 )
            {
                $this->ID =& $attribute_array[0][$db->fieldName("ID")];
                $this->Name =& $attribute_array[0][$db->fieldName("Name")];
                $this->TypeID =& $attribute_array[0][$db->fieldName("TypeID")];
                $this->Placement =& $attribute_array[0][$db->fieldName("Placement")];
            }
        }
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $attribute_array = array();

        $db->array_query( $attribute_array, "SELECT ID FROM eZArticle_Attribute ORDER BY Created" );

        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $return_array[$i] = new eZArticleAttribute( $attribute_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
        \static
        Returns the one, and only if one exists, attribute with the name

        Returns an object of eZArticleAttribute.
     */
    function getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic = new eZArticleAttribute();

        $name = $db->escapeString( $name );

        if ( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Attribute WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic = new eZArticleAttribute( $author_array[0][$db->fieldName("ID")] );
            }
        }

        return $topic;
    }


    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZArticle_AttributeValue WHERE AttributeID='$this->ID'" );
        $db->query( "DELETE FROM eZArticle_Attribute WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the attribute.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the type of the attribute.
    */
    function type()
    {
       $type = new eZArticleType( $this->TypeID );

       return $type;
    }


    /*!
      Sets the name of the attribute.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the type of the attribute.
    */
    function setType( $type )
    {
       if ( is_a( $type, "eZArticleType" ) )
       {
           $this->TypeID = $type->id();
       }
    }


    /*!
      Sets the attribute value for the given article.
    */
    function setValue( $article, $value )
    {
       if ( is_a( $article, "eZArticle" ) )
       {
           $db =& eZDB::globalDatabase();

           $db->begin( );

           $articleID = $article->id();

           // check if the attribute is already set, if so update
           $db->array_query( $value_array,
           "SELECT ID FROM eZArticle_AttributeValue WHERE ArticleID='$articleID' AND AttributeID='$this->ID'" );

           $value = $db->escapeString( $value );

           if ( count( $value_array ) > 0 )
           {
               $valueID = $value_array[0][$db->fieldName("ID")];


               $res = $db->query( "UPDATE eZArticle_AttributeValue SET
                                   Value='$value'
                                   WHERE ID='$valueID'" );
           }
           else
           {

               $db->lock( "eZArticle_AttributeValue" );

               $nextID = $db->nextID( "eZArticle_AttributeValue", "ID" );

               $res = $db->query( "INSERT INTO eZArticle_AttributeValue
                                   ( ID, ArticleID, AttributeID, Value )
                                   VALUES
                                   ( '$nextID',
                                      '$articleID',
                                      '$this->ID',
                                      '$value' )" );
           }

           $db->unlock();

           if ( $res == false )
               $db->rollback( );
           else
               $db->commit();

       }
    }

    /*!
      Returns the attribute value to the given article.
    */
    function value( $article )
    {
       $ret = "";
       if ( is_a( $article, "eZArticle" ) )
       {
           $db =& eZDB::globalDatabase();

           $articleID = $article->id();

           // check if the attribute is already set, if so update
           $db->array_query( $value_array,
           "SELECT Value FROM eZArticle_AttributeValue WHERE ArticleID='$articleID'
           AND AttributeID='$this->ID'" );

           if ( count( $value_array ) > 0 )
           {
               $ret = $value_array[0][$db->fieldName("Value")];
           }
       }
       return $ret;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement<'$this->Placement' AND TypeID = '$this->TypeID' ORDER BY Placement DESC LIMIT 1" );

        $listorder = $qry[$db->fieldName("Placement")];
        $listid = $qry[$db->fieldName("ID")];



        $db->query_single( $qry, "SELECT min( Placement ) as Min FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement" );
        $min = $qry[$db->fieldName("Min")];


        if( $min == $this->Placement )
        {
            $db->query_single( $qry, "SELECT max( Placement ) as Max FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC" );

            $max = $qry[$db->fieldName("Max")];

            $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement = '$max' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC" );

            $listorder = $qry[$db->fieldName("Placement")];
            $listid = $qry[$db->fieldName("ID")];
        }

        $db->query( "UPDATE eZArticle_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZArticle_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement>'$this->Placement' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC" );
        $listorder = $qry[$db->fieldName("Placement")];
        $listid = $qry[$db->fieldName("ID")];

        $db->query_single( $qry, "SELECT max( Placement ) as Max FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC" );
        $max = $qry[$db->fieldName("Max")];


        if( $max == $this->Placement )
        {
            $db->query_single( $qry, "SELECT min( Placement ) as Min FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC" );

            $min = $qry[$db->fieldName("Min")];

            $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement = '$min' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC" );

            $listorder = $qry[$db->fieldName("Placement")];
            $listid = $qry[$db->fieldName("ID")];
        }

        $db->query( "UPDATE eZArticle_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZArticle_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }


    var $ID;
    var $TypeID;
    var $Name;
    var $Placement;
}

?>
