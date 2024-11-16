<?php
//
// $Id: ezimagecategory.php 9794 2003-03-25 14:49:48Z br $
//
// Definition of eZImageCategory class
//
// Created on: <11-Dec-2000 15:24:35 bf>
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

//!! eZImageCatalogue
//! eZImageCategory manages virtual folders.
/*!

*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );


class eZImageCategory
{
    /*!
      Constructs a new eZImageCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1 )
    {
        $this->ExcludeFromSearch = "false";
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZImageCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZImageCatalogue_Category" );

            $this->ID = $db->nextID( "eZImageCatalogue_Category", "ID" );

            $db->query( "INSERT INTO eZImageCatalogue_Category
                                     ( ID, Name, Description, UserID, ParentID, SectionID ) VALUES
                                     ( '$this->ID', '$name', '$description', '$this->UserID', '$this->ParentID', '$this->SectionID' )" );
            $db->unlock();
        }
        else
        {
            $db->query( "UPDATE eZImageCatalogue_Category SET
                                     Name='$name',
                                     Description='$description',
                                     UserID='$this->UserID',
                                     ParentID='$this->ParentID',
                                     SectionID='$this->SectionID' WHERE ID='$this->ID'" );
        }


        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();


        return true;
    }

    function &search( $name, $literal = false, $sortby='name', $user = false )
    {
        $db =& eZDB::globalDatabase();
        $topic = array();

        $sortbySQL = "Category.Name";
        switch ( $sortby )
        {
            case "name" : $sortbySQL = "Category.Name"; break;
        }

        $query = new eZQuery( array( "Category.Name", "Category.Description" ),
                              $name );
        $query->setIsLiteral( $literal );
        $where =& $query->buildQuery();

        if ( is_a( $user, "eZUser" ) )
            $user =& eZUser::currentUser();

        $show_str = "";
        $usePermission = true;

        if ( $user )
        {
            $groups =& $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= " Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
                $i++;
            }
            $currentUserID = $user->id();

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        if ( $usePermission )
            $permissionSQL = "( ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' ) AND ";
        else
            $permissionSQL = "";

        $db->array_query( $author_array,
                          "SELECT Category.ID
                           FROM eZImageCatalogue_Category AS Category,
                                eZImageCatalogue_CategoryPermission AS Permission
                           WHERE $permissionSQL $where
                                 AND Permission.ObjectID=Category.ID
                           GROUP BY Category.ID
                           ORDER BY $sortbySQL" );

        foreach ( $author_array as $author )
        {
            $topic[] = new eZImageCategory( $author[$db->fieldName( "ID" )] );
        }
        return $topic;
    }

    /*!
      Deletes a eZImageCategory object from the database.
    */
    function delete( $catID=-1 )
    {

        if ( $catID == -1 )
            $catID = $this->ID;

        $category = new eZImageCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach ( $categoryList as $category )
        {
            $category->delete();
        }

        foreach ( $this->images() as $image )
        {
            $image->delete();
        }

        $categoryID = $category->id();

        $db =& eZDB::globalDatabase();

        $db->begin( );

        $res1 = $db->query( "DELETE FROM eZImageCatalogue_Category WHERE ID='$categoryID'" );
        $res2 = $db->query( "DELETE FROM eZImageCatalogue_CategoryPermission WHERE ObjectID='$this->ID'" );

        if ( ( $res1 == false )  || ( $res2 == false ) )
            $db->rollback( );
        else
            $db->commit();

    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZImageCatalogue_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                eZLog::writeNotice( "Error: Category's with the same ID was found in the database. This shouldent happen." );
                return false;
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][$db->fieldName("ID")];
                $this->Name =& $category_array[0][$db->fieldName("Name")];
                $this->Description =& $category_array[0][$db->fieldName("Description")];
                $this->ParentID =& $category_array[0][$db->fieldName("ParentID")];
                $this->UserID =& $category_array[0][$db->fieldName("UserID")];
                $this->SectionID =& $category_array[0][$db->fieldName("SectionID")];
            }
        }
        return true;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZImageCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT ID, Name FROM eZImageCatalogue_Category ORDER BY Name" );

        for ( $i = 0; $i < count( $category_array ); $i++ )
        {
            $return_array[$i] = new eZImageCategory( $category_array[$i][$db->fieldName( "ID" )] );
        }

        return $return_array;
    }

    /*!
      \Static
      Returns all images in a category
    */
    function &getImages( $user, $category = false )
    {
        return eZImageCategory::images( "time", 0, -1, $category );
    }

    /*!
      Returns the number of categories in the the category given as parameter as parent.
    */
    function countByParent( $parent  )
    {
        if ( is_a( $parent, "eZImageCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $parentID = $parent->id();

            $db->query_single( $count, "SELECT count( ID ) AS Count FROM eZImageCatalogue_Category
                                        WHERE ParentID='$parentID'", "Count" );

            return $count;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZImageCategory objects.
    */
    function getByParent( $parent, $offset = 0, $max = -1 )
    {
        if ( is_a( $parent, "eZImageCategory" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $db->array_query( $category_array, "SELECT ID, Name FROM eZImageCatalogue_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name", array( "Limit" => $max,
                                                                 "Offset" => $offset ) );

            for ( $i = 0; $i < count( $category_array ); $i++ )
            {
                $return_array[$i] = new eZImageCategory( $category_array[$i][$db->fieldName( "ID" )] );
            }

            return $return_array;
        }
        else
        {
            return array();
        }
    }

    /*!
        \static
        Returns the one, and only if one exists, category with the name

        Returns an object of eZImageCategory.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic = new eZImageCategory();

        if ( $name != "" )
        {
            $db->array_query( $author_array, "SELECT ID, Name FROM eZImageCatalogue_Category WHERE Name='$name'" );

            if ( count( $author_array ) == 1 )
            {
                $topic = new eZImageCategory( $author_array[0][$db->fieldName( "ID" )] );
            }
        }

        return $topic;
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

        $category = new eZImageCategory( $categoryID );

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
      Recursive function that returns an array containing an int (tree position) and an array ( all items on that level )
     */
    function getTree( $parentID=0, $level=0 )
    {
        $category = new eZImageCategory( $parentID );

        $categoryList = $category->getByParent( $category );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZImageCategory( $category->id() ), $level ) );

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
       if ( $html )
           return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
       else
           return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description( $html = true )
    {
       if ( $html )
           return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Description ) );
       else
           return $this->Description;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent( $as_object = true )
    {
        if ( !$as_object )
            return $this->ParentID;
        if ( $this->ParentID != 0 )
        {
            return new eZImageCategory( $this->ParentID );
        }
        else
        {
            return 0;
        }
    }


    /*!
      Returns a eZUser object.
    */
    function user()
    {
        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }
        return $ret;
    }

    /*!
      \Static
      Returns true if the given user is the owner of the given object.
      $user is either a userID or an eZUser.
      $imagecategory is the ID of the image category.
     */
    function isOwner( $user, $imagecategory )
    {
        if ( !is_a( $user, "eZUser" ) )
            return false;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT UserID from eZImageCatalogue_Category WHERE ID='$imagecategory'");
        $userID = $res[$db->fieldName("UserID")];
        if ( $userID == $user->id() )
            return true;

        return false;
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
       if ( is_a( $value, "eZImageCategory" ) )
       {
           $this->ParentID = $value->id();
       }
    }

    /*!
      Sets the user of the file.
    */
    function setUser( $user )
    {
        if ( is_a( $user, "eZUser" ) )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      \static
      Adds a file to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addImage( $value, $categoryid = false )
    {
       if ( is_a( $value, "eZImage" ) )
           $imageID = $value->id();
       else if ( is_numeric( $value ) )
           $imageID = $value;
       else
           return false;

       if ( !$categoryid )
           $categoryid = $this->ID;

       $db =& eZDB::globalDatabase();

       $imageID = $value->id();

       $db->begin( );
       $db->lock( "eZImageCatalogue_ImageCategoryLink" );

//       $query = "DELETE FROM eZImageCatalogue_ImageCategoryLink WHERE
//                CategoryID='$categoryid' AND ImageID='$imageid'";

       $query = "DELETE FROM eZImageCatalogue_ImageCategoryLink WHERE
                ImageID='$imageid'";

       $db->query( $query );

       $nextID = $db->nextID( "eZImageCatalogue_ImageCategoryLink", "ID" );

       $query = "INSERT INTO eZImageCatalogue_ImageCategoryLink ( ID, CategoryID, ImageID )
                 VALUES ( '$nextID', '$categoryid', '$imageID' )";

       $res = $db->query( $query );

       $db->unlock();

       if ( $res == false )
           $db->rollback( );
       else
           $db->commit();
    }

    /*!
      \static
      Removes an image from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeImage( $value, $categoryid = false )
    {
        if ( is_a( $value, "eZImage" ) )
            $imageID = $value->id();
        else if ( is_numeric( $value ) )
            $imageID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZImageCatalogue_ImageCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        ImageID='$imageID'";

        $db->begin( );

        $res = $db->query( $query );

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }


    /*!
      Returns every images in a category as a array of eZImage objects.
    */
    function imageCount( $check_write = false )
    {
        if ( !isset( $limit ) or $limit == 0 )
        {
            $ini =& INIFile::globalINI();
            $limit = $ini->read_var( "eZImageCatalogueMain", "ListImagesPerPage" );
        }

        $db =& eZDB::globalDatabase();

        $user =& eZUser::currentUser();
        $usePermission = true;
        $groupSQL = false;
        if ( $user )
        {
            $groups =& $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "( Permission.GroupID=$group AND ( CategoryPermission.GroupID=$group OR CategoryPermission.GroupID='-1' ) ) OR\n";
                else
                    $groupSQL .= " ( Permission.GroupID=$group AND ( CategoryPermission.GroupID=$group OR CategoryPermission.GroupID='-1' ) ) OR";

                $i++;
            }

            $groupSQL .= " (Image.UserID='" . $user->id() . "') OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        $having_str = "";

        $sel_str = "count( DISTINCT Image.ID ) AS Count";
        if ( $usePermission )
        {
            if ( $check_write )
            {
                $permissionSQL = "( ($groupSQL Permission.GroupID='-1') )
 AND Image.ID = Permission.ObjectID
 AND eZImageCatalogue_Category.ID = CategoryPermission.ObjectID
 AND ";
                $sel_str = "max( Permission.ReadPermission ) AS MaxReadPerm, max( Permission.WritePermission ) AS MaxWritePerm,
  MAX(CategoryPermission.WritePermission) AS MaxCatWritePerm, MAX(CategoryPermission.ReadPermission) AS MaxCatReadPerm,
 MAX(CategoryPermission.UploadPermission) AS MaxCatUploadPerm";
                $having_str = "GROUP BY Image.ID
 HAVING MaxWritePerm=1 AND MaxReadPerm=1 AND MaxCatWritePerm=1 AND MaxCatReadPerm=1 AND MaxCatUploadPerm=1 ";
            }
            else
                $permissionSQL = "( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND
                                    Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1') AND
                                  Image.ID = Permission.ObjectID AND ";

            $fromTablePermissionsSQL = ", eZImageCatalogue_ImagePermission as Permission, " .
                 "eZImageCatalogue_CategoryPermission as CategoryPermission";

        }
        else
        {
            $permissionSQL = "";
            $fromTablePermissionsSQL = "";
        }

        $sql = "
                SELECT $sel_str
                FROM eZImageCatalogue_Image as Image,
                     eZImageCatalogue_Category,
                     eZImageCatalogue_ImageCategoryLink
                     $fromTablePermissionsSQL
                WHERE $permissionSQL
                      eZImageCatalogue_ImageCategoryLink.ImageID = Image.ID
                      AND eZImageCatalogue_Category.ID = eZImageCatalogue_ImageCategoryLink.CategoryID
                      AND eZImageCatalogue_Category.ID='$this->ID'
                      AND eZImageCatalogue_ImageCategoryLink.CategoryID='$this->ID'
                      $having_str";

//         eZLog::writeNotice( "Count sql: $sql" );

        if ( $usePermission and $check_write )
        {
            $db->array_query( $file_array, $sql );
            $cnt = count( $file_array );
        }
        else
        {
            $db->query_single( $file_array, $sql,
                               "Count" );
            $cnt = $file_array;
        }
        return $cnt;
    }

    /*!
      Returns every images in a category as a array of eZImage objects.
    */
    function images( $sortMode = "time", $offset = 0, $limit = -1, $category=false, $check_write = false )
    {
       $db =& eZDB::globalDatabase();

       if ( is_a ( $category, "eZImageCategory" ) )
       {
           $catID = $category->id();
       }
       elseif ( is_numeric ( $category ) )
           $catID = $category;
       else
           $catID = $this->ID;
       $return_array = array();
       $article_array = array();
       $user =& eZUser::currentUser();
       $usePermission = true;
       if ( $user )
       {
           $groups =& $user->groups( false );

           if ( $user->hasRootAccess() )
           {
               $usePermission = false;
           }
           else
           {
               $i = 0;
               foreach ( $groups as $group )
               {
                   if ( $i == 0 )
                       $groupSQL .= "( Permission.GroupID=$group AND ( CategoryPermission.GroupID=$group OR CategoryPermission.GroupID='-1' ) ) OR";
                   else
                       $groupSQL .= " ( Permission.GroupID=$group AND ( CategoryPermission.GroupID=$group OR CategoryPermission.GroupID='-1' ) ) OR";
                   $i++;
               }

               $groupSQL .= " (Image.UserID='" . $user->id() . "') OR";
           }
       }

       $having_str = "";
       $perm_str = "";
       if ( $usePermission )
       {
           $fromTablePermissionsSQL = ", eZImageCatalogue_ImagePermission as Permission, " .
                                      "eZImageCatalogue_CategoryPermission as CategoryPermission";

           if ( $check_write )
           {
               $perm_str = ", MAX(Permission.WritePermission) AS MaxWritePerm, MAX(Permission.ReadPermission) AS MaxReadPerm,
 MAX(CategoryPermission.WritePermission) AS MaxCatWritePerm, MAX(CategoryPermission.ReadPermission) AS MaxCatReadPerm,
 MAX(CategoryPermission.UploadPermission) AS MaxCatUploadPerm";
               $permissionSQL = "( ($groupSQL Permission.GroupID='-1') )
 AND Image.ID = Permission.ObjectID
 AND eZImageCatalogue_Category.ID = CategoryPermission.ObjectID
 AND ";
               $having_str = "HAVING MaxWritePerm=1 AND MaxReadPerm=1 AND MaxCatWritePerm=1 AND MaxCatReadPerm=1 AND MaxCatUploadPerm=1 ";
           }
           else
               $permissionSQL = "( ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND " .
                   "Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) AND " .
                   "Image.ID = Permission.ObjectID AND ";
       }
       else
       {
           $fromTablePermissionsSQL = "";
           $permissionSQL = "";
       }

       $sql = " SELECT Image.ID AS ImageID,
                       Image.OriginalFileName $perm_str
                FROM eZImageCatalogue_Image as Image,
                     eZImageCatalogue_Category,
                     eZImageCatalogue_ImageCategoryLink
                     $fromTablePermissionsSQL
                WHERE $permissionSQL
                      eZImageCatalogue_ImageCategoryLink.ImageID = Image.ID
                      AND eZImageCatalogue_Category.ID = eZImageCatalogue_ImageCategoryLink.CategoryID
                      AND eZImageCatalogue_Category.ID='$catID'
               GROUP BY Image.ID, Image.OriginalFileName
               $having_str
               ORDER BY Image.OriginalFileName";
//         eZLog::writeNotice( "List sql: $sql" );

       $db->array_query( $file_array, $sql,
       array( "Limit" => $limit,
              "Offset" => $offset ) );

       for ( $i = 0; $i < count( $file_array ); $i++ )
       {
           $return_array[$i] = new eZImage( $file_array[$i][$db->fieldName( "ImageID" )], false );
       }

       return $return_array;
    }


   /*!
      Sets the section of the category
    */
    function setSectionID( $value )
    {
        $this->SectionID = $value;
    }

    /*!
      Returns the section of the category
    */
    function sectionID()
    {
        return $this->SectionID;
    }

    static public function sectionIDStatic( $categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SectionID from eZImageCatalogue_Category WHERE ID='$categoryID'" );

        $sectionID = $res[$db->fieldName( "SectionID" )];

        if ( $sectionID > 0 )
        {
            return $sectionID;
        }
        else
        {
            return false;
        }
    }

    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $UserID;
    var $SectionID;
    var $ExcludeFromSearch;
}

?>