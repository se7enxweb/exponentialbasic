<?php
// 
// $Id: ezcategory.php 7992 2001-10-22 09:22:31Z  $
//
// Definition of eZCategory class
//
// Created on: <26-Jun-2001 16:53:12 ce>
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

//!! eZTodo
//! The eZCategory handles the category informasjon.
/*!
  Handles the category informasjon stored in the database. All the todo's are grouped in to categorys.
*/

class eZCategory
{
    /*!
      eZCategory Constructor.
    */
    function __construct( $id = -1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the category object to the database.
      Returnes the ID to the eZCategory object if the store is a success.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZTodo_Category" );
			$this->ID = $db->nextID( "eZTodo_Category" );
            $res[] = $db->query( "INSERT INTO eZTodo_Category
                                  (ID, Name, Description)
                                  VALUES
                                  ('$this->ID', '$name','$description')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZTodo_Category SET
                                  ID='$this->ID',
                                  Name='$name',
                                  Description='$description'
                                  WHERE ID='$this->ID' ");
        }
        eZDB::finish( $res, $db );
        return true;
    }
        

    /*!
      Deletes the category object in the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZTodo_Category WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Gets a category object from the database, where ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZTodo_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName( "ID" )];
                $this->Name = $category_array[0][$db->fieldName( "Name" )];
                $this->Description = $category_array[0][$db->fieldName( "Description" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Gets all the category informasjon from the database.
      Returns the array in $cateogry_array ordered by name.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $category_array = 0;

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID FROM eZTodo_Category ORDER by Name" );

        for ( $i = 0; $i < count( $category_array ); $i++ )
        { 
            $return_array[$i] = new eZCategory( $category_array[$i][$db->fieldName( "ID" )], 0 );
        } 
        return $return_array;
    }

    /*! 
      Tilte of the category.
      Returns the name of the category as a string.
    */
    function name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the name of the category.
      The new name of the category is passed as a paramenter ( $value ).
     */
    function setName( $value )
    {        
        $this->Name = $value;
    }

    /*!
      Description of the category.
      Returns the description of the category as a string.
    */
    function description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the description of the category.
      The new description of the category is passed as a paramenter ( $value ).
     */
    function setDescription( $value )
    {
        $this->Description = $value;
    }
 
    /*!
      Id of the priority.
      Returns the id of the category as a string.
    */
    function id()
    {
        return $this->ID;
    }

    var $ID;
    var $Name;
    var $Description;

}

?>