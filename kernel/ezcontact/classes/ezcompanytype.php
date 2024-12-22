<?php
//
// $Id: ezcompanytype.php 6970 2001-09-05 11:57:07Z jhe $
//
// Definition of eZCompanyType class
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZContact
//! eZCompanyType handles the company types.
/*!
  Example code:
  \code
  $companyType = new eZCompanyType();
  $companyType->setName( "Companty type" );
  $companyType->store(); // Store or updates to the database.
  \code
  \sa eZOnlineType eZCompany eZPerson eZOnline eZPhone eZOnline

  This class handles the company types in the database. A company type can be used to
  describe different hiearchical information about a company. For example geographical
  area, business area, etc.
*/

// include_once( "classes/ezdb.php" );
// include_once( "ezcontact/classes/ezcompany.php" );

class eZCompanyType
{
    /*!
      Constructor of the eZCompanyType.
    */
    function __construct( $id = -1 )
    {
        $this->ImageID = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores the object in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $ret = false;
        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZContact_CompanyType" );
			$this->ID = $db->nextID( "eZContact_CompanyType", "ID" );
            $res[] = $db->query( "INSERT INTO eZContact_CompanyType
                                  (ID, Name, Description, ImageID, ParentID)
                                  VALUES
                                  ('$this->ID',
                                   '$name',
                                   '$description',
                                   '$this->ImageID',
                                   '$this->ParentID')" );
            $db->unlock();
            $ret = true;
        }
        else
        {
            $res[] = $db->query( "UPDATE eZContact_CompanyType set Name='$name', Description='$description', ImageID='$this->ImageID', ParentID='$this->ParentID' WHERE ID='$this->ID'" );

            $ret = true;
        }
        eZDB::finish( $res, $db );
        return $ret;
    }


    /*
      Delets the company type from the database.
     */
    function delete()
    {
        $sub_categories =& eZCompanyType::getByParentID( $this->ID );
        foreach ( $sub_categories as $category )
        {
            $category->delete();
        }
        $top_category = new eZCompanyType( 0 );
        $companies =& eZCompany::getByCategory( $this->ID );
        foreach ( $companies as $company )
        {
            $company->removeCategories();
            $top_category->addCompany( $company );
        }
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyType WHERE ID='$this->ID'" );
        eZDB::finish( $res, $db );
    }

    /*
        Fetches a company type with the  ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $company_type_array, "SELECT * FROM eZContact_CompanyType WHERE ID='$id'" );

            if ( count( $company_type_array ) > 1 )
            {
                die( "Error: More than one company type with the same ID found. Major problem, clean up the table eZContact_CompanyType. " );
            }
            else if ( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ $db->fieldName( "ID" ) ];
                $this->Name = $company_type_array[ 0 ][ $db->fieldName( "Name" ) ];
                $this->Description = $company_type_array[ 0 ][ $db->fieldName( "Description" ) ];
                $this->ParentID = $company_type_array[ 0 ][ $db->fieldName( "ParentID" ) ];
                $this->ImageID = $company_type_array[ 0 ][ $db->fieldName( "ImageID" ) ];
           }
        }
    }

    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    static public function getAll( $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $db =& eZDB::globalDatabase();

        switch ( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "parentid":
            case "pid":
                $OrderBy = "ORDER BY ParentID";
                break;
            case "id":
            case "typeid":
                $OrderBy = "ORDER BY ID";
                break;
            default:
                $OrderBy = "ORDER BY ID";
                break;
        }

        if ( is_numeric( $LimitStart ) )
        {
            $LimitArray = array( "Offset" => $LimitStart );

            if ( is_numeric( $LimitBy ) )
            {
                $LimitArray =& array_merge( $LimitArray, array( "Limit" => $LimitBy ) );
            }
        }
        else
        {
            $LimitArray = array();
        }

        $company_type_array = array();
        $return_array = array();


        $db->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType $OrderBy", $LimitClause );

        foreach ( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCompanyType( $companyTypeItem[ $db->fieldName( "ID" ) ] );
        }
        return $return_array;
    }

    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    static public function &getByParentID( $parent = 0, $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $db =& eZDB::globalDatabase();
        $LimitArray = false;

        if ( is_a( $parent, "eZCompanyType" ) )
        {
            $id = $parent->id();
        }
        else
        {
            $id = $parent;
        }

        switch ( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "parentid":
            case "pid":
                $OrderBy = "ORDER BY ParentID";
                break;
            case "id":
            case "typeid":
                $OrderBy = "ORDER BY ID";
                break;
            default:
                $OrderBy = "ORDER BY ID";
                break;
        }

        if ( is_numeric( $LimitStart ) )
        {
            $LimitArray = array( "Offset" => $LimitStart );

            if ( is_numeric( $LimitBy ) )
            {
                $LimitArray =& array_merge( $LimitArray, array( "Limit" => $LimitBy ) );
            }
            else
                $LimitArray =& array_merge( $LimitArray, array( "Limit" => -1 ) );
        }
        else
        {
            $LimitClause = "";
        }

        $company_type_array = array();
        $return_array = array();

        $db->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType WHERE ParentID='$id' $OrderBy", $LimitArray );

        foreach ( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCompanyType( $companyTypeItem[ $db->fieldName( "ID" ) ] );
        }
        return $return_array;
    }

    /*!
        Check if this item has children
     */
    static public function hasChildren( &$childrenCount, $id = "this" )
    {
        $ret = false;

        if ( $id == "this" )
        {
            $id = $this->ID;
        }

        if ( is_numeric( $id ) )
        {
            $db =& eZDB::globalDatabase();

            $company_type_array = array();
            $db->array_query( $company_type_array, "SELECT ParentID FROM eZContact_CompanyType WHERE ParentID='$id'" );
            $childrenCount = count( $company_type_array );

            if ( $childrenCount != 0 )
            {
                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Print out the group path.
    */
    static public function path( $categoryID = 0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = false;
        }

        $category = new eZCompanyType( $categoryID );

        $path = array();

        $parent = $category->parentID();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, eZCompanyType::path( $parent ) );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );

        return $path;
    }

    static public function &getTree( $parentID=0, $level=0, $add_top = false, $name = false )
    {
        if ( $add_top )
        {
            $tree = array();
            $level++;
            $category = new eZCompanyType();
            $category->ID = 0;
            $category->setName( $name );
            array_push( $tree, array( $category, $level ) );
            $tree = array_merge( $tree, eZCompanyType::getTree( 0, $level ) );
        }
        else
        {
            $categoryList =& eZCompanyType::getByParentID( $parentID );

            $tree = array();
            $level++;
            foreach ( $categoryList as $category )
            {
                array_push( $tree, array( $category , $level ) );

                if ( $category != 0 )
                {
                    $tree = array_merge( $tree, eZCompanyType::getTree( $category->id(), $level ) );
                }
            }

        }
        return $tree;
    }

    /*!
      Adds a company to the current user category.

      Returns true if successful, false if not.
    */
    function addCompany( $company )
    {
        $ret = false;

        if ( get_class( $company ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();
            $companyID = $company->id();
            $res[] = $db->query( "INSERT INTO eZContact_CompanyTypeDict
                                  (CompanyID, CompanyTypeID)
                                  VALUES
                                  ('$companyID', '$this->ID')" );
            eZDB::finish( $res, $db );
            $ret = true;
        }
        return $ret;
    }

    /*!
        Set an image for this category.
     */
    function setImageID( $value )
    {
        if ( is_a( $value, "eZImage" ) )
        {
            $this->ImageID = $value->id();
        }
        else if ( is_numeric( $value ) )
        {
            $this->ImageID = $value;
        }
    }


    /*!
      Set the name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }
    /*!
      Set the description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Set parent
    */
    function setParentID( $value )
    {
        $this->ParentID = $value;
    }


    /*!
      Returns the ID of the company type.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name.
    */
    function name( )
    {
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
      Returns the parent.
    */
    function parentID( )
    {
        return $this->ParentID;
    }


    /*!
      Returns the image id.
    */
    function imageID( )
    {
        return $this->ImageID;
    }

    var $ID;
    var $ParentID;
    var $Name;
    var $Description;
    var $ImageID;
}

?>