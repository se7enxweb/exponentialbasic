<?php
//
// $Id: ezarticleform.php 6206 2001-07-19 12:19:22Z jakobn $
//
// ezarticleform class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! eZArticle
//! ezarticleform documentation.
/*!

  Example code:
  \code
  \endcode

*/

// include_once( "ezarticle/classes/ezarticle.php" );

class eZArticleForm
{

    /*!
      Constructs a new eZArticleForm object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1, $fetch=true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a eZArticleForm object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if( is_a( $this->Form, "eZForm" ) )
        {
            $FormID = $this->Form->id();
        }

        if( is_a( $this->Article, "eZArticle" ) )
        {
            $ArticleID = $this->Article->id();
        }

        $setValues = "
            FormID='$FormID',
            ArticleID='$ArticleID'
        ";

        if ( empty( $this->ID ) )
        {
            $db->query( "INSERT INTO eZArticle_ArticleFormDict SET $setValues" );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZArticle_ArticleFormDict SET $setValues WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZArticleForm object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZArticle_ArticleFormDict WHERE ID=$formID" );
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
            $db->array_query( $formArray, "SELECT * FROM eZArticle_ArticleFormDict WHERE ID='$id'",
                              0, 1 );
            if( count( $formArray ) == 1 )
            {
                $this->fill( $formArray[0] );
                $ret = true;
            }
            elseif( count( $formArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$formArray )
    {
        $this->ID =& $formArray[ "ID" ];

        $this->Article = new eZArticle( $formArray[ "ArticleID" ] );
        $this->Form = new eZForm( $formArray[ "FormID" ] );
    }

    /*!
      \static
      Returns all the objects found in the database.

      The objects are returned as an array of eZArticleForm objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $formArray = array();

        if ( $limit == false )
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZArticle_ArticleFormDict
                                           " );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZArticle_ArticleFormDict
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZArticleForm( $formArray[$i]["ID"] );
        }

        return $returnArray;
    }

    /*!
      \static
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZArticle_ArticleFormDict" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the form of the object.
    */
    function &form()
    {
        return $this->Form;
    }

    /*!
      Returns the article of the object.
    */
    function &receiver()
    {
        return $this->Article;
    }

   /*!
      Sets the article of the object.
    */
    function setArticle( &$object )
    {
        if( is_a( $object, "eZArticle" ) )
        {
            $this->Article = $object;
        }
    }

   /*!
      Sets the form of the object.
    */
    function setForm( &$object )
    {
        if( is_a( $object, "eZForm" ) )
        {
            $this->Form = $object;
        }
    }

    /*!
        \static
        Returns the form if the article has a form.

        The article is sent in as an eZArticle object.
        The form is returned as an eZForm object.
    */
    function &articleHasForm( &$object )
    {
        $returnArray = array();
        $formArray = array();

        if( is_a( $object, "eZArticle" ) )
        {
            $ArticleID = $object->id();
        }

        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT FormID FROM eZArticle_ArticleFormDict WHERE ArticleID='$ArticleID'" );

        if (is_array($qry)) {
        	$ret = new eZForm( $qry["FormID"] );
        } else {
        	$ret = new eZForm( );
        }

        return $ret;
    }

    /*!
        \static
        Returns the article if the form has an article.

        The form is sent in as an eZForm object.
        The article is returned as an eZArticle object.
    */
    function &formHasArticle( &$object )
    {
        $returnArray = array();
        $formArray = array();

        if( is_a( $object, "eZForm" ) )
        {
            $FormID = $object->id();
        }

        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ArticleID FROM eZArticle_ArticleFormDict WHERE FormID='$FormID'" );

        $ret =& eZArticle( $qry["ArticleID"] );

        return $ret;
    }

    var $ID;
    var $Form;
    var $Article;
}

?>