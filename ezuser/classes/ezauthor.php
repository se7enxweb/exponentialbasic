<?php
// 
// $Id: ezauthor.php 9705 2002-08-15 14:20:29Z gl $
//
// Definition of eZAuthor class
//
// Created on: <31-May-2001 13:52:30 bf>
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

//!! eZUser
//! eZAuthor handles authors/photographers.
/*!
  \sa eZUser eZUserGroup eZPermission eZModule eZForgot
*/

include_once( "classes/ezdb.php" );
include_once( "classes/eztexttool.php" );


class eZAuthor
{
    /*!
      Constructs a new eZAuthor object.
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
      Stores or updates a eZAuthor object in the database.
    */
    function store()
    {
        $ret = false;
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        
        $name = $db->escapeString( $this->Name );
        $email = $db->escapeString( $this->EMail );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZUser_Author" );
            $nextID = $db->nextID( "eZUser_Author", "ID" );

            $res = $db->query( "INSERT INTO eZUser_Author
                         ( ID, Name, EMail )
                         VALUES 
		                 ( '$nextID', '$name', '$email' )" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZUser_Author SET
		                 Name='$name',
                         EMail='$email'
                        WHERE ID='$this->ID'" );
            

        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
        {
            $db->commit();
            $ret = true;
        }

        return $ret;
    }

    /*!
      Deletes a eZAuthor object from the database.
    */
    function delete( $id )
    {
        $db =& eZDB::globalDatabase();

        if ( is_numeric( $id ) )
        {
            $db->query( "DELETE FROM eZUser_Author WHERE ID='$id'" );
        }
        else if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_Author WHERE ID='$this->ID'" );
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
            $db->array_query( $author_array, "SELECT * FROM eZUser_Author WHERE ID='$id'" );
            if( count( $author_array ) == 1 )
            {                
                $this->ID =& $author_array[0][$db->fieldName("ID")];
                $this->Name =& $author_array[0][$db->fieldName("Name")];
                $this->EMail =& $author_array[0][$db->fieldName("EMail")];
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
      Fetches the authour with the given name.

      True is retuned if successful, false (0) if not.
    */
    function getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        $db->array_query( $author_array, "SELECT * FROM eZUser_Author WHERE Name='$name'" );
        if( count( $author_array ) == 1 )
        {
            $this->ID =& $author_array[0][$db->fieldName("ID")];
            $this->Name =& $author_array[0][$db->fieldName("Name")];
            $this->EMail =& $author_array[0][$db->fieldName("EMail")];
            $ret = true;
        }
        return $ret;
    }

    /*!
      Fetches the user id from the database. And returns a array of eZAuthor objects.
    */
    function &getAll(  )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $author_array = array();


        $db->array_query( $author_array, "SELECT ID,Name FROM eZUser_Author
                                        ORDER By Name" );

        foreach ( $author_array as $author )
        {
            $return_array[] = new eZAuthor( $author[$db->fieldName("ID")] );
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
        if ( $html )
            return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
        return $this->Name;
    }

    /*!
      Returns the authors e-mail address.
    */
    function email( )
    {
       return $this->EMail;
    }

    /*!
      Sets the name.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the email address to the user.
    */
    function setEmail( $value )
    {
       $this->EMail = $value;
    }

    var $ID;
    var $Name;
    var $EMail;
}

?>
