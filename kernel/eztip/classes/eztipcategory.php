<?php
// 
// $Id: eztipcategory.php,v 1.27 2001/10/16 14:25:05 br Exp $
//
// Definition of eZTipCategory class
//
// Created on: <22-Nov-2000 20:32:30 bf>
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

//!! eZTip
//! eZTipCategory handles banner ad categories.
/*!

  \sa eZTip
*/

/*!TODO

 */

// include_once( "classes/ezdb.php" );
// include_once( "eztip/classes/eztip.php" );

class eZTipCategory
{
    /*!
      Constructs a new eZTipCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->ParentID = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZTipCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );


        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $isPublished = $db->escapeString( $this->IsPublished );
        
        if ( !isset( $this->ID ) )
	{
            $db->lock( "eZTip_Category" );
            $nextID = $db->nextID( "eZTip_Category", "ID" );
            
            $res = $db->query( "INSERT INTO eZTip_Category
                         ( ID, Name, Description, ParentID, SectionID, IsPublished, LocationID )
                         VALUES
                         ( '$nextID',
                           '$name',
                           '$description',
                           '$this->ParentID',
   			   '$this->SectionArray',
			   '$isPublished',
			   '$this->LocationID'
				    )" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZTip_Category SET
                         Name='$name',
                         Description='$description',
                         ParentID='$this->ParentID',
						 SectionID='$this->SectionArray',
						 IsPublished='$isPublished',
						 LocationID='$this->LocationID'
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
      Deletes a eZTipCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->array_query( $tip_id_array, "SELECT TipID FROM eZTip_TipCategoryLink WHERE
                                             CategoryID='$this->ID'");

            if ( count( $tip_id_array ) > 0 )
            {
                foreach( $tip_id_array as $tip_id )
                {
                    $link = new eZTip( $tip_id[$db->fieldName( "TipID" )] );
                    $link->delete();
                }
            }
            
            $db->query( "DELETE FROM eZTip_TipCategoryLink
                                     WHERE CategoryID='$this->ID'" );
            
            $db->query( "DELETE FROM eZTip_Category WHERE ID='$this->ID'" );            
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $ret = false;
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZTip_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $ret = true;
                $this->ID = $category_array[0][$db->fieldName("ID")];
                $this->Name = $category_array[0][$db->fieldName("Name")];
                $this->Description = $category_array[0][$db->fieldName("Description")];
                $this->ParentID = $category_array[0][$db->fieldName("ParentID")];
                $this->SectionID = $category_array[0][$db->fieldName("SectionID")];
                $this->IsPublished = $category_array[0][$db->fieldName("IsPublished")];
                $this->LocationID = $category_array[0][$db->fieldName("LocationID")];
            }
        }

        return $ret;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZTipCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID, Name FROM eZTip_Category ORDER BY Name" );
        
        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZTipCategory( $category_array[$i][$db->fieldName("ID")], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZTipCategory objects.
    */
    function getByParent( $parent  )
    {
        if ( get_class( $parent ) == "eZTipCategory" )
        {
            $db =& eZDB::globalDatabase();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZTip_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZTipCategory( $category_array[$i][$db->fieldName("ID")], 0 );
            }

            return $return_array;
        }
        else
        {
            return array();
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
            
        $category = new eZTipCategory( $categoryID );

        $path = array();

        $parent = $category->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
            // array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );                                
        
        return $path;
    }

    function getTree( $parentID=0, $level=0 )
    {
        $category = new eZTipCategory( $parentID );

        $categoryList = $category->getByParent( $category );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $returnObj = new eZTipCategory( $category->id() ), $level ) );

            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }
        }
        return $tree;
    }

    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the category.
    */
    function name( $html = true )
    {
       if( $html && $this->Name != "" )
           return htmlspecialchars( $this->Name );
        return $this->Name;
    }

    /*!
      Returns the tip description.
    */
    function description( $html = true )
    {
       if( $html && $this->Description != "" )
           return htmlspecialchars( $this->Description );
        return $this->Description;
    }
    
    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->ParentID != 0 )
       {
           return new eZTipCategory( $this->ParentID );
       }
       else
       {
           return 0;           
       }
    }


    /*!
      Returns true if the category is to be published, false if not.
    */
    function ispublished( )
    {
       $ret = false;
       if ( $this->IsPublished  == "1" )
       {
           $ret = true;
       }

       return $ret;
    }
    /*!
      Returns the Location ID of the tip category.
    */
    function locationID( )
    {
       return $this->LocationID;
    }
    /*!
      Sets the name of the category.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( $value )
    {
        $this->ParentID = $value;
    }

    /*!
      Sets the Location ID.
    */
    function setLocationID( $value )
    {
        $this->LocationID = $value;
    }

    /*!
     Sets the exclude from search bit.
     The argumen can be true or false.
    */
    function setIsPublished( $value )
    {
       if ( $value == true )
       {
           $this->IsPublished = "1";
       }
       else
       {
           $this->IsPublished = "0";           
       }
    }
	
	function setSectionArray( $value )
	{
		$ret = ",";
        if ( isset( $value ) )
        {
            if ( $value[0] == "0" )
            {
                $ret="0";
            }
            else
            {
                foreach ( $value as $section )
                {
                    $ret .= "$section,";
                }
                // $ret = substr("$ret", 0, -1);
            }
        }
		$this->SectionArray = $ret;
	}

    /*!
      Returns the section list.
    */
    function getSectionArray()
    {
		$ret = Array();

		$this->get();
		$temp = $this->SectionID;

        if ( !is_null( $temp ) )
        {
		    $ret = explode( "," , $temp);
        }

        return $ret;
    }

    /*!
      Tipds a ad to the category.
    */
    function addTip( $value )
    {
       if ( get_class( $value ) == "eZTip" )
       {
           $db =& eZDB::globalDatabase();

            $db->begin( );

            $db->lock( "eZTip_TipCategoryLink" );
            $nextID = $db->nextID( "eZTip_TipCategoryLink", "ID" );
            
            $tipID = $value->id();
            
            $query = "INSERT INTO
                           eZTip_TipCategoryLink
                      ( ID, CategoryID, TipID )
                      VALUES
                      ( '$nextID',
                        '$this->ID',
                        '$tipID' )";
            
            $res = $db->query( $query );

            $db->unlock();
    
            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
       }       
    }

    /*!
      Returns every ad in a category as a array of eZTip objects.

      It does not return unactive tips unless $fetchUnActive is set to true.
    */
    function &tiplist( $sortMode="name",
                   $fetchUnActive=false,
                   $offset=0,
                   $limit=50 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $tip_array = array();
       
        $fetchActiveSQL = "";
        if ( $fetchUnActive == false )
        {
            $fetchActiveSQL = "AND eZTip_Tip.IsActive = '1'";
        }

        $orderBySQL = "Tip.Name ASC";

        $db->array_query( $tip_array,
        "SELECT Tip.ID, Tip.Name
         FROM eZTip_Tip AS Tip, eZTip_TipCategoryLink AS ACL
         WHERE Tip.ID=ACL.TipID AND ACL.CategoryID='$this->ID'
         ORDER BY $orderBySQL", array( "Limit" => $limit, "Offset" => $offset ) );
        
        foreach( $tip_array as $tip )
        {
            $return_array[] = new eZTip( $tip[$db->fieldName("ID")] );
        }

        return $return_array;
    }
    
    /*!
      Returns every ad in a category as a array of eZTip objects.

      It does not return unactive tips unless $fetchUnActive is set to true.
    */
    function &tips( $sortMode="name",
                   $fetchUnActive=false,
                   $offset=0,
                   $limit=50,
				   $tipLocationID=0 )
       {
	   global $GlobalSectionID;
       $db =& eZDB::globalDatabase();

	   // section selector
	   $section = new eZSection();
	   $sectionID = $section->ID;
		
       $return_array = array();
       $tip_array = array();

       $fetchActiveSQL = "";
       if ( $fetchUnActive == false )
       {
           $fetchActiveSQL = "AND eZTip_Tip.IsActive = '1'";
       }
       if ( $sortMode == "name" )
           $orderBySQL = "eZTip_Tip.Name ASC";
       else       
           $orderBySQL = "eZTip_View.ViewOffsetCount ASC";

	   $sectionCheckSQL = "AND (eZTip_Category.SectionID LIKE '%,$GlobalSectionID,%' OR eZTip_Category.SectionID = '0')";
	   $locationCheckSQL = "AND (eZTip_Category.LocationID LIKE $tipLocationID)";

       $db->array_query( $tip_array,
       "SELECT eZTip_Tip.ID, eZTip_View.ViewCount, eZTip_Tip.Name
        FROM
            eZTip_Tip,
            eZTip_TipCategoryLink,
            eZTip_View,
			eZTip_Category
        WHERE
           eZTip_TipCategoryLink.TipID = eZTip_Tip.ID
		   AND eZTip_TipCategoryLink.CategoryID = eZTip_Category.ID
           AND eZTip_View.TipID = eZTip_Tip.ID
		   AND eZTip_Category.IsPublished = 1
		   $sectionCheckSQL
		   $fetchActiveSQL
		   $locationCheckSQL
           ORDER BY $orderBySQL",
	   array( "Limit" => $limit, "Offset" => $offset ) ); 

	      
       if ( count( $tip_array ) > 0 )
       {
           for ( $i=0; $i < count($tip_array); $i++ )
           {
               $return_array[$i] = new eZTip( $tip_array[$i][$db->fieldName("ID")] );
           }
       }
       return $return_array;
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
	var $IsPublished;
	var $SectionID;
    // corrected $tipLOcationID
	var $tipLocationID;
    var $LocationID;
    var $SectionArray; // used to store the section array as a string
}

?>