<?php
//
// $Id: ezproductcategory.php 9895 2004-04-06 11:08:44Z br $
//
// Definition of eZProductCategory class
//
// Created on: <11-Sep-2000 22:10:06 bf>
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

//!! eZTrade
//! eZProductCategory handles product categories.
/*!
  The eZProductCategory class handles product groups, the relation to products
  in the productgroup and the options connected to the product group.

  Example of usage:

  \code
  // inserts a new Category to the database:
  $category = new eZProductCategory();

  $category->setName( "Category name" );
  $category->setDescription( "This is a brief description of the category" );

  $category->store();

  // Fetches every category found in the database

  $categoryArray = $category->getAll();

  // Prints out every category's name and description , notice it's an array
  // of objects.
  foreach ( $categoryArray as $catItem )
  {
      print( $catItem->name() . "<br>" . $catItem->description() . "..<br>" );
  }

  // Create a option
  $option = new eZOption();

  $option->setName( "Color" );
  $option->setDescription( "This is the color of the product" );

  // Add a option to the category
  $category->addOption( $option );

  $pathArray = $category->path();

  // Get the current path of a category
  $pathArray = $category->path();

  // print the number of levels in the path
  print( count( $pathArray ) );

  // print out the path with slashes if you use $path[0] you get
  // the id of the path
  foreach ( $pathArray as $path )
  {
      print( $path[1] . " / " );
  }

  \endcode

  \sa eZProduct eZOption eZOptionValue
*/

// include_once( "classes/ezdb.php" );
// include_once( "eztrade/classes/ezproduct.php" );

class eZProductCategory
{
    /*!
      Constructs a new eZProductCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        $this->ImageID = 0;
	$this->Parent = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZProductGroup object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        $remoteID = $db->escapeString( $this->RemoteID );

	if( $this->Parent == null )
           $this->Parent = 0;

        if ( $this->ID == false )
        {

            $db->lock( "eZTrade_Category" );
            $nextID = $db->nextID( "eZTrade_Category", "ID" );
            $query = "INSERT INTO eZTrade_Category
                                ( ID, Name, Description, SortMode, RemoteID, ImageID, SectionID, Parent )
                                VALUES
                                ( '$nextID',
                                  '$name',
                                  '$description',
                                  '$this->SortMode',
                                  '$remoteID',
                                  '$this->ImageID',
                                  '$this->SectionID',
                                  '$this->Parent' ) ";
             echo $query;

            $res = $db->query( $query );

            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $query = "UPDATE eZTrade_Category SET
		                         Name='$name',
                                 Description='$description',
                                 SortMode='$this->SortMode',
                                 RemoteID='$this->RemoteID',
                                 ImageID='$this->ImageID',
                                 SectionID='$this->SectionID',
                                 Parent='$this->Parent' WHERE ID='$this->ID'";
             $res = $db->query( $query );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZProductGroup object from the database.

    */
    function delete( $catID=-1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $catID == -1 )
            $catID = $this->ID;

        $category = new eZProductCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach ( $categoryList as $categoryItem )
        {
            $this->delete( $categoryItem->id() );
        }

        $categoryID = $category->id();

        foreach( $this->products() as $product )
        {
            $categoryDefinition = $product->categoryDefinition();

            if ( $categoryDefinition->id() == $category->id() )
            {
                $db->query( "DELETE FROM eZTrade__ProductCategoryDefinition WHERE CategoryID='$categoryID'" );
                $db->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );

                $product->delete();
            }
            else
            {
                $db->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );
            }
        }

        $db->query( "DELETE FROM eZTrade_Category WHERE ID='$categoryID'" );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        $category_array = array();
        $ret = false;

        if ( $id != "" && $id != -1 )
        {
            $query = "SELECT * FROM eZTrade_Category WHERE ID='$id'";
            $db->array_query( $category_array, $query );

            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This should not happen." );
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][$db->fieldName( "ID" )];
                $this->Name =& $category_array[0][$db->fieldName( "Name" )];
                $this->Description =& $category_array[0][$db->fieldName( "Description" )];
		if( $category_array[0][$db->fieldName( "Parent" )] != NULL )
                $this->Parent =& $category_array[0][$db->fieldName( "Parent" )];
                $this->SortMode =& $category_array[0][$db->fieldName( "SortMode" )];
                $this->RemoteID =& $category_array[0][$db->fieldName( "RemoteID" )];
                $this->ImageID =& $category_array[0][$db->fieldName( "ImageID" )];
                $this->SectionID =& $category_array[0][$db->fieldName( "SectionID" )];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZProductCategory objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID FROM eZTrade_Category ORDER BY Name" );

        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZProductCategory( $category_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZProductCategory objects.
    */
    function &getByParent( $parent, $sortby='name' )
    {
        if ( is_a( $parent, "eZProductCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();
            $query = "SELECT ID, Name FROM eZTrade_Category WHERE Parent='$parentID' ORDER BY Name";

            $db->array_query( $category_array, $query );

            for ( $i = 0; $i < count( $category_array ); $i++ )
            {
                $return_array[$i] = new eZProductCategory( $category_array[$i][$db->fieldName( "ID" )], 0 );
            }

            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function &path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }

        $category = new eZProductCategory( $categoryID );

        $path = array();

        $parent = $category->parent();
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
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
    */
    static public function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZProductCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZProductCategory( $category->id() ), $level ) );
            if ( $category != 0 )
            {
                $tree = array_merge( $tree, eZProductCategory::getTree( $category->id(), $level ) );
            }
        }
        return $tree;
    }


    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        $ret = $this->ID;
        return $ret;
    }


    /*!
      Returns the name of the category.
    */
    function &name()
    {
        $ret = stripslashes( $this->Name );
        return htmlspecialchars( $ret );
    }

    /*!
      Returns the remote ID of the category.
    */
    function remoteID()
    {
        return $this->RemoteID;
    }

    /*!
      Returns the remote ID of the category.
    */
    function sectionID()
    {
        return $this->SectionID;
    }

    /*!
      \static
      Returns the Section ID. Returns false if the Category was not found.
    */
    static public function sectionIDStatic( $categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SectionID from eZTrade_Category WHERE ID='$categoryID'");

        if ( is_array( $res ) )
            $sectionID = $res[$db->fieldName("SectionID")];
        else
            $sectionID = 0;
        
        if ( $sectionID > 0 )
            return $sectionID;
        else
            return false;
    }

    /*!
      Returns the group description.
    */
    function &description()
    {
        if( $this->Description != null )
            $description = stripslashes( $this->Description );

        return htmlspecialchars( $description );
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function &parent()
    {
       if ( is_a($this->Parent, 'eZProductCategory'))
       {
           return new eZProductCategory( $this->Parent );
       }
       else
       {
           return null;
       }
    }

    /*!
      Returns the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      4 - absolute placement
    */
    function &sortMode()
    {
       switch( $this->SortMode )
       {
           case 1 :
           {
               $SortMode = "time";
           }
           break;

           case 2 :
           {
               $SortMode = "alpha";
           }
           break;

           case 3 :
           {
               $SortMode = "alphadesc";
           }
           break;

           case 4 :
           {
               $SortMode = "absolute_placement";
           }
           break;

           default :
           {
               $SortMode = "time";
           }
       }

       return $SortMode;
    }

    /*!
      Sets the name of the category.
    */
    function setName( &$value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the remote ID of the category.
    */
    function setRemoteID( &$value )
    {
        $this->RemoteID = $value;
    }

    /*!
      Sets the remote ID of the category.
    */
    function setSectionID( &$value )
    {
        $this->SectionID = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( $parent )
    {
        if ( is_a( $parent, "eZProductCategory" ) )
        {
            $this->Parent = $parent->id();
        }
        else
        {
            $this->Parent = $parent;
        }
    }

    /*!
      Sets the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      4 - absolute placement
    */
    function setSortMode( $value )
    {
       $this->SortMode = $value;
    }

    /*!
      \static
      Adds a product to the category.
      Can be used as a static function if $categoryid is supplied
    */
    static public function addProduct( &$value, $categoryid = false )
    {
        if ( is_a( $value, "eZProduct" ) )
            $prodID = $value->id();
        else if ( is_numeric( $value ) )
            $prodID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->array_query( $qry, "SELECT Placement FROM eZTrade_ProductCategoryLink
                                             ORDER BY Placement DESC", array( "Limit" => 1 ) );
        $placement = count( $qry ) == 1 ? $qry[0][$db->fieldName( "Placement" )] + 1 : 1;


        $db->begin();
        $db->lock( "eZTrade_ProductCategoryLink" );
        $nextID = $db->nextID( "eZTrade_ProductCategoryLink", "ID" );

        $query = ( "INSERT INTO eZTrade_ProductCategoryLink
                    (ID, CategoryID, ProductID, Placement)
                    VALUES ('$nextID', '$categoryid', '$prodID', '$placement')" );

        $res = $db->query( $query );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      \static
      Removes a product from the category.
      Can be used as a static function if $categoryid is supplied
    */
    static public function removeProduct( &$value, $categoryid = false )
    {
        if ( is_a( $value, "eZProduct" ) )
            $prodID = $value->id();
        else if ( is_numeric( $value ) )
            $prodID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();

        $query = "DELETE FROM eZTrade_ProductCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        ProductID='$prodID'";
        $res = $db->query( $query );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Returns the total number of products in a category.
    */
    function productCount( $sortMode="time",
                            $fetchNonActive=false,
                            $fetchDiscontinued=false,
                            $categoryID=false)
    {
        $db =& eZDB::globalDatabase();

        if ( $categoryID != false )
            $catID = $categoryID;
        else
            $catID = $this->ID;

        switch ( $sortMode )
        {
            case "time" :
            {
                $OrderBy = "eZTrade_Product.Published DESC";
            }
            break;

            case "alpha" :
            {
                $OrderBy = "eZTrade_Product.Name ASC";
            }
            break;

            case "alphadesc" :
            {
                $OrderBy = "eZTrade_Product.Name DESC";
            }
            break;

            case "absolute_placement" :
            {
                $OrderBy = "eZTrade_ProductCategoryLink.Placement ASC";
            }
            break;

            default :
            {
                $OrderBy = "eZTrade_Product.Published DESC";
            }
        }

       $permissionSQLArray = eZProductCategory::generatePermissionSQL( $catID);
       $permissionTableSQL = $permissionSQLArray["TableSQL"];
       $permissionSQL = $permissionSQLArray["SQL"];


        $nonActiveCode = $fetchNonActive ? "" : " eZTrade_Product.ShowProduct='1' AND";
        $discontinuedCode = "";
        if ( !$fetchDiscontinued )
            $discontinuedCode = " eZTrade_Product.Discontinued='0' AND";
        $query = "SELECT COUNT( DISTINCT eZTrade_Product.ID ) AS Count
                FROM eZTrade_Product, eZTrade_ProductCategoryLink $permissionTableSQL
                WHERE
                eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                AND
                $nonActiveCode
                $discontinuedCode
                $permissionSQL
                eZTrade_ProductCategoryLink.CategoryID='$catID'";

        $db->query_single( $products, $query );
        return $products[$db->fieldName( "Count" )];
    }

    /*!
      Returns every product to a category as a array of eZProduct objects.
    */
    static public function &products( $sortMode="time",
                        $fetchNonActive=false,
                        $offset=0,
                        $limit=50,
                        $fetchDiscontinued=false,
                        $categoryID=0 )
    {
        if ( $categoryID != -1 )
            $catID = $categoryID;
        else
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

       switch( $sortMode )
       {
           case "time" :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
           break;

           case "alpha" :
           {
               $OrderBy = "eZTrade_Product.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $OrderBy = "eZTrade_Product.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $OrderBy = "eZTrade_ProductCategoryLink.Placement ASC";
           }
           break;

           default :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
       }

       $return_array = array();
       $product_array = array();

       $permissionSQLArray = eZProductCategory::generatePermissionSQL( $catID );
       $permissionTableSQL = $permissionSQLArray["TableSQL"];
       $permissionSQL = $permissionSQLArray["SQL"];

       $user =& eZUser::currentUser();
       if ( $user )
       {
           $groups = $user->groups();
       }
       else
       {
           $groups = array();
       }
       
       if ( $fetchNonActive  == true )
       {
           $nonActiveCode = "";
       }
       else
       {
           $nonActiveCode = " eZTrade_Product.ShowProduct='1' AND";
       }
       $discontinuedCode = "";

       if ( !$fetchDiscontinued )
           $discontinuedCode = " eZTrade_Product.Discontinued='0' AND";

       $query = "SELECT DISTINCT eZTrade_Product.ID AS ProductID, eZTrade_Product.Name,
                       eZTrade_Category.ID, eZTrade_Category.Name
                FROM eZTrade_Product, eZTrade_Category,
                     eZTrade_ProductCategoryLink $permissionTableSQL
                WHERE
                eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                AND
                $nonActiveCode
                $discontinuedCode
                $permissionSQL
                eZTrade_Category.ID = eZTrade_ProductCategoryLink.CategoryID
                AND
                eZTrade_Category.ID='$catID'
                ORDER BY $OrderBy";

       $db->array_query( $product_array, $query, array( "Limit" => $limit, "Offset" => $offset ) );

       for ( $i = 0; $i < count( $product_array ); $i++ )
       {
           $return_array[$i] = new eZProduct( $product_array[$i][$db->fieldName( "ProductID" )], false );
       }
       return $return_array;
    }

    static public function generatePermissionSQL( $categoryID = false )
    {
        $catID = $categoryID;

        $user =& eZUser::currentUser();
        $hasRootAccess = false;
        if ( $user )
        {
            $groups = $user->groups();
            $hasRootAccess = $user->hasRootAccess();
        }
        else
        {
            $groups = array();
        }
        $permission = array( 'SQL' => '', 'TableSQL' => '');
        if ( $hasRootAccess == false )
        {

            $productGroupSQL = " (PP.GroupID='-1'";
            $categoryGroupSQL = " AND (PCP.GroupID='-1'";
            if ( $user )
            {
                $groups =& $user->groups( false );
                $first = true;

                foreach ( $groups as $group )
                {
                    $productGroupSQL .= " OR PP.GroupID='$group'";
                    $categoryGroupSQL .= " OR PCP.GroupID='$group'";
                }

                if ( $user->hasRootAccess() )
                    $usePermission = false;
            }
            $productGroupSQL .= ") AND PP.ReadPermission='1'";
            $categoryGroupSQL .= ") AND PCP.ReadPermission='1'";

            if ( is_numeric( $catID ) && $catID > 0 )
            {
                $permission["SQL"] = "$productGroupSQL $categoryGroupSQL AND PP.ObjectID=eZTrade_Product.ID AND PCP.ObjectID='$catID' AND";
                $permission["TableSQL"] = ", eZTrade_ProductPermission as PP, eZTrade_CategoryPermission as PCP";
            }
            else
            {
                $permission["SQL"] = "$productGroupSQL AND PP.ObjectID=eZTrade_Product.ID AND";
                $permission["TableSQL"] = ", eZTrade_ProductPermission as PP";
            }
        }
        return $permission;
    }


    /*!
      Returns every active product to a category as a array of eZProduct objects.
    */
    function &activeProducts( $sortMode="time",
                              $offset=0,
                              $limit=50,
                              $categoryID=false )
    {
        return $this->products( $sortMode, false, $offset, $limit, false, $categoryID );
    }


    /*!
      Adds a option to the category.
    */
    function addOption( $value )
    {
        if ( is_a( $value, "eZOption" ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $optionID = $value->id();
            $value->store();

            $db->lock( "eZTrade_CategoryOptionLink" );
            $nextID = $db->nextID( "eZTrade_CategoryOptionLink", "ID" );

            $res = $db->query( "INSERT INTO eZTrade_CategoryOptionLink
                             ( ID, CategoryID, OptionID )
                             VALUES
                             ( '$nextID',
                               '$this->ID',
                               '$optionID' )
                             " );
            $db->unlock();

            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
        }
    }

    /*!
      Returns every option to a category as a array of eZOption objects.
    */
    function &options()
    {
        $db =& eZDB::globalDatabase();

       $return_array = array();
       $option_array = array();

       $db->array_query( $option_array, "SELECT OptionID FROM eZTrade_CategoryOptionLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i < count($option_array); $i++ )
       {
           $return_array[$i] = new eZOption( $option_array[$i][$db->fieldName( "OptionID" )], false );
       }

       return $return_array;
    }

    /*!
      Moves the product placement with the given ID up.
    */
    function moveUp( $id )
    {
       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZTrade_ProductCategoryLink
                                  WHERE ProductID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry[$db->fieldName( "ID" )] ) )
       {
           $linkID = $qry[$db->fieldName( "ID" )];

           $placement = $qry[$db->fieldName( "Placement" )];

           $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_ProductCategoryLink
                                    WHERE Placement<'$placement' AND
                                    eZTrade_ProductCategoryLink.CategoryID='$this->ID'
                                    ORDER BY Placement DESC", array( "Limit" => 1 ) );

           $newPlacement = $qry[$db->fieldName( "Placement" )];
           $listid = $qry[$db->fieldName( "ID" )];

           if ( is_numeric( $listid ) )
           {
               $db->begin();
               $res[] = $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
               $res[] = $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               if ( in_array( false, $res ) )
                   $db->rollback( );
               else
                   $db->commit();
           }
       }
    }

    /*!
      Moves the product placement with the given ID down.
    */
    function moveDown( $id )
    {
       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZTrade_ProductCategoryLink
                                  WHERE ProductID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry[$db->fieldName( "ID" )] ) )
       {
           $linkID = $qry[$db->fieldName( "ID" )];

           $placement = $qry[$db->fieldName( "Placement" )];

           $db->query_single( $qry_2, "SELECT ID, Placement FROM eZTrade_ProductCategoryLink
                                    WHERE Placement>'$placement' AND eZTrade_ProductCategoryLink.CategoryID='$this->ID' ORDER BY Placement ASC", array( "Limit" => 1 ) );

           $newPlacement = $qry_2[$db->fieldName("Placement" )];
           $listid = $qry_2[$db->fieldName("ID" )];

           if ( is_numeric( $listid ) )
           {
               $db->begin();
               $res[] = $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
               $res[] = $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               if ( in_array( false, $res ) )
                   $db->rollback( );
               else
                   $db->commit();
           }
       }
    }


    /*!
      Check if there are a category where RemoteID == $id. Return the category if true.
    */
    function getByRemoteID( $id, $parent=false )
    {
        $db =& eZDB::globalDatabase();

        $category = false;

        if ( $parent )
        {
            $db->array_query( $res, "SELECT ID FROM
                                            eZTrade_Category
                                            WHERE Parent='$parent' AND RemoteID='$id'" );

        }
        else
        {
            $db->array_query( $res, "SELECT ID FROM
                                            eZTrade_Category
                                            WHERE RemoteID='$id'" );

        }

       if ( count( $res ) == 1 )
       {
           $category = new eZProductCategory( $res[0][$db->fieldName( "ID" )] );
       }

       return $category;
    }

    /*!
      Clone a current object.
    */
    function cloneObject( )
    {
        if ( is_object ( $this ) )
        {
            $clone = $this;
            $clone->ID = false;
            return $clone;
        }
        else
            return false;
    }


    /*!
      Check if there are a category where RemoteID == $id. Return the category if true.
    */
    function copy( $recursive=true, $id=false  )
    {
    }

    /*!
      Sets the image of the category.
    */
    function setImage( $value )
    {
       if ( is_a( $value, "eZImage" ) )
           $value = $value->id();

       $this->ImageID = $value;
    }

    /*!
      Returns the Image ID.
    */
    function &image( $AsObject = true )
    {
        if ( $AsObject )
            $image = new eZImage( $this->ImageID );
        else
            $image = $this->ImageID;

        return $image;
    }

    var $ID;
    var $Name;
    var $Parent;
    var $Description;
    var $OptionArray;
    var $SortMode;
    var $RemoteID;
    var $ImageID;
    var $SectionID;
}

?>