<?
//
// $Id: ezmenu.php 7507 2001-09-27 10:43:55Z ce $
//
// eZMenu class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <27-Sep-2001 10:24:02 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! ezmail
//! ezmail documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "eztrade/classes/ezvoucher.php" );
include_once( "eztrade/classes/ezpreorder.php" );

include_once( "ezaddress/classes/ezonline.php" );

class eZMenu
{

    /*!
      Constructs a new eZMenu object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZMenu object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name =& $db->escapeString( $this->Name );
        $link =& $db->escapeString( $this->Link );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZSiteManager_Menu" );
            $nextID = $db->nextID( "eZSiteManager_Menu", "ID" );

            $res = $db->query( "INSERT INTO eZSiteManager_Menu
                      ( ID, Name, Link, Type, ParentID )
                      VALUES
                      ( '$nextID',
                        '$name',
                        '$link',
                        '$this->Type',
                        '$this->ParentID'
                         )
                     " );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZSiteManager_Menu SET
                                     Name='$name',
                                     Link='$link',
                                     Type='$this->Type',
                                     ParentID='$this->ParentID'
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
      Deletes a ezmenu object from the database.
    */
    function delete( $catID=-1 )
    {
        $ret = false;
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $ret = $db->query( "DELETE FROM eZSiteManager_Menu WHERE ID='$this->ID'" );

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();
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
            $db->array_query( $quizArray, "SELECT * FROM eZSiteManager_Menu WHERE ID='$id'",
                              0, 1 );
            if( count( $quizArray ) == 1 )
            {
                $this->fill( $quizArray[0] );
                $ret = true;
            }
            elseif( count( $quizArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$value )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $value[$db->fieldName( "ID" )];
        $this->Name =& $value[$db->fieldName( "Name" )];
        $this->Link =& $value[$db->fieldName( "Link" )];
        $this->Type =& $value[$db->fieldName( "Type" )];
        $this->ParentID =& $value[$db->fieldName( "ParentID" )];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of ezmenu objects.
    */
    static public function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        $db->array_query( $quizArray, "SELECT ID
                                           FROM eZSiteManager_Menu
                                           ORDER BY Type DESC",
        array( "Limit" => $limit,
               "Offset" => $offset  ) );

        for ( $i=0; $i < count($quizArray); $i++ )
        {
            $returnArray[$i] = new eZMenu( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of ezmenu objects.
    */
    static public function &getByParent( $parent=0, $offset=0, $limit=20 )
    {
        $parentID = 0;
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $quizArray = array();

        if ( is_a( $parent, "eZMenu" ) )
            $parentID = $parent->id();
        elseif ( is_numeric( $parent ) )
            $parentID = $parent;

        $db->array_query( $quizArray, "SELECT ID
                                           FROM eZSiteManager_Menu
                                           WHERE ParentID='$parentID' ORDER BY Type DESC",
        array( "Limit" => $limit,
               "Offset" => $offset  ) );

        for ( $i=0; $i < count($quizArray); $i++ )
        {
            $returnArray[$i] = new eZMenu( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    static public function count( $parent=0 )
    {
        $parentID = 0;
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( is_a( $parent, "eZMenu" ) )
            $parentID = $parent->id();
        else if ( is_numeric ( $parent ) )
            $parentID = $parent;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZSiteManager_Menu WHERE ParentID='$parentID'" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function &path( $menuID=0 )
    {
        if ( $menuID == 0 )
        {
            $menuID = $this->ID;
        }

        $menu = new eZMenu( $menuID );

        $path = array();

        $parent = $menu->parent();
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );

        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $menuID != 0 )
            array_push( $path, array( $menu->id(), $menu->name() ) );

        return $path;
    }


    /*!
      Returns the object ID to the menu. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the menu.
    */
    function &name()
    {
        return $this->Name;
    }

    /*!
      Sets the description.
    */
    function setName( &$value )
    {
        $this->Name = $value;
    }

    /*!
      Returns the url link of the menu.
    */
    function &link()
    {
        return $this->Link;
    }

    /*!
      Sets the description.
    */
    function setLink( &$value )
    {
        $this->Link = $value;
    }

    /*!
      Sets the parent.
    */
    function setParent( &$value )
    {
        if ( is_a( $value, "eZMenu" ) )
            $this->ParentID = $value->id();
        else if ( is_numeric ( $value ) )
            $this->ParentID = $value;
    }

    /*!
      Returns the parent
    */
    function parent(  )
    {
        if ( $this->ParentID != 0 )
        {
            return new eZMenu( $this->ParentID );
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the menu type
      1 = header
      2 = item
    */
    function type( )
    {
        return $this->Type;
    }

    /*!
      Sets the type of the menu.
      1 = header
      2 = item
    */
    function setType( $value )
    {
        $this->Type = $value;
    }

    var $ID;
    var $Name;
    var $Link;
    var $Type;
    var $ParentID;
}

?>