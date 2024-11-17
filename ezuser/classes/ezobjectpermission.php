<?php
//
// $Id: ezobjectpermission.php 9458 2002-04-23 15:33:45Z bf $
//
// Definition of eZObjectPermission class
//
// Created on: <27-Feb-2001 08:05:56 fh>
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
//! eZObjectPermission holds user group permissions for objects like articles, article categories, bugs, e.g.
/*!

  Example code:
  \code
  Check if a user has read permission to an article object:
  if ( eZObjectPermission::hasPermission( $objectID, "article_article", 'r' ) )
  {
  currentuser has permission
  }
  else
  {
  He did not.
  }

  \endcode
  \sa eZUser eZUserGroup eZModule eZForgot
*/

include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "classes/ezdb.php" );

class eZObjectPermission
{
    /*!
      Constructs a new eZPermission object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( )
    {
    }

    /*
      \static
      Returns true if the user has the desired permission to the desired object.
      $objectID is the ID of the object you are interested in. This could be a bug, an article etc..
      $moduleTable is the nickname of the table where the permission is found. The nicknames can be found in site.ini
      $permission either 'r' for readpermission, 'w' for writepermission or 'u' for upload permission.
      $user (of type eZUser )is the user you want to check permissions for. Default is currentUser.

      NOTE: If you object has an owner, and this user allways should have rights, you must check this yourself.
     */
    static public function hasPermission( $objectID, $moduleTable, $permission, $user = false )
    {
        if ( !is_a( $user, "eZUser" ) )
            $user =& eZUser::currentUser();

        if ( is_object( $user ) && $user->hasRootAccess() )
            return true;

        if ( $permission != 'u' && $permission != 'w' && $permission != 'r' )
            return false;

        $SQLGroups = "GroupID = '-1'";
        if ( is_a( $user, "eZUser" ) )
        {
            $groups =& $user->groups( false );
            $first = true;
            if ( count( $groups ) > 0 )
            {
                foreach ( $groups as $groupItem )
                {
                    if ( $first == true )
                    {
                        $SQLGroups = "GroupID='$groupItem' ";
                    }
                    else
                    {
                        $SQLGroups .= "OR GroupID='$groupItem' ";
                    }
                    $first = false;
                }
                $SQLGroups .= "OR GroupID = '-1' ";
            }
        }

        $tableName = getTableName( $moduleTable );
        if ( $tableName == "" )
        {
            return false;
        }

        $SQLPermission= "";
        if ( $permission == 'r' )
        {
            $SQLPermission = "AND ReadPermission='1'";
        }
        else if ( $permission == 'w' )
        {
            $SQLPermission = "AND WritePermission='1'";
        }
        else if ( $permission == 'u' )
        {
            $SQLPermission = "AND UploadPermission='1'";
        }

        $query = "SELECT count( ID ) as ID FROM $tableName WHERE ObjectID='$objectID' AND ( $SQLGroups ) $SQLPermission";
        $database =& eZDB::globalDatabase();

        $database->query_single( $res, $query );
        if ( $res[$database->fieldName( "ID" )] != 0 )
            return true;

        return false;
    }


    static public function hasPermissionWithDefinition( $objectID, $moduleTable, $permission, $user=false, $categoryID=false )
    {
        if ( !is_a( $user, "eZUser" ) )
        {
            $user =& eZUser::currentUser();
        }

        if ( is_object( $user ) && $user->hasRootAccess() )
            return true;

        if ( !$categoryID )
            return false;

        if ( $permission != 'u' && $permission != 'w' && $permission != 'r' )
            return false;

        $SQLGroups = "Object.GroupID = '-1' AND Category.GroupID = '-1' ";
        if ( is_a( $user, "eZUser" ) )
        {
            $groups =& $user->groups( false );
            $first = true;
            if ( count( $groups ) > 0 )
            {
                foreach ( $groups as $groupItem )
                {
                    if ( $first == true )
                    {
                        $SQLGroups = "( Object.GroupID='$groupItem' ";
                    }
                    else
                    {
                        $SQLGroups .= "OR Object.GroupID='$groupItem' ";
                    }
                    $first = false;
                }

                $first = true;
                $SQLGroups .= ") AND ( Category.GroupID='-1' ";
                foreach ( $groups as $groupItem )
                {
                    $SQLGroups .= " OR Category.GroupID='$groupItem' ";

                }

                $SQLGroups .=  " ) ";
                foreach ( $groups as $groupItem )
                {
                    $SQLGroups .= "OR ( ( Object.GroupID = '-1' AND Category.GroupID = '-1' )
                               OR ( Object.GroupID = '$groupItem' AND Category.GroupID = '-1' )
                               OR ( Object.GroupID = '-1' AND Category.GroupID = '$groupItem' ) )";
                }

            }
        }

        $tableName = getTableName( $moduleTable, true );

        if ( $tableName == "" )
        {
            return false;
        }

        $SQLRead = "";
        $SQLWrite = "";
        if ( $permission == 'r' )
        {
            $SQLRead = "AND Object.ReadPermission='1' AND Category.ReadPermission='1'";
        }
        else if ( $permission == 'w' )
        {
            $SQLWrite = "AND Object.WritePermission='1' AND Category.WritePermission='1'";
        }

        $query = "SELECT count( Object.ID ) as ID FROM $tableName WHERE Object.ObjectID='$objectID' AND ( $SQLGroups ) $SQLRead $SQLWrite  AND Object.ObjectID=Definition.ArticleID AND Category.ObjectID=Definition.CategoryID AND Category.ObjectID='$categoryID' GROUP BY Object.ObjectID";

        $database =& eZDB::globalDatabase();

        $database->query_single( $res, $query );

        if ( $res[$database->fieldName( "ID" )] != 0 )
        {
            return true;
        }

        return false;
    }

    /*!
      \static
      Sets a permissions for on an object for a eZUserGroup. To set a permission for all use -1 as group.
      $group is of type eZUserGroup or the group ID and is the group that gets the permission
      $objectID is the ID of the object you are interested in. This could be a bug, an article etc..
      $moduleTable is the nickname of the table where the permission is found. The nicknames can be found in site.ini
      $permission either 'r' for readpermission, 'w' for writepermission or 'u' for upload permission.
    */
    static public function setPermission( $group, $objectID, $moduleTable, $permission  )
    {
        if ( is_a( $group, "eZUserGroup" ) )
        {
            $groupID = $group->id();
        }
        else if ( $group == -1 )
        {
            $groupID = -1;
        }
        else
        {
            $groupID = $group;
        }

        $SQLPermission = "";
        if ( $permission == 'r' )
        {
            $SQLPermission = "ReadPermission";
        }
        else if ( $permission == 'w' )
        {
            $SQLPermission = "WritePermission";
        }
        else if ( $permission == 'u' )
        {
            $SQLPermission = "UploadPermission";
        }
        else // bogus $permission input.
        {
            return false;
        }

        $tableName = getTableName( $moduleTable );
        if ( $tableName == "" )
        {
            return false;
        }

        $db =& eZDB::globalDatabase();
        $dbError = false;
        $db->begin( );

        $queryexists = "SELECT count( ID ) AS ID FROM $tableName WHERE ObjectID='$objectID' AND GroupID='$groupID'";
        $db->query_single( $res, $queryexists );

        if ( $res[$db->fieldName("ID")] == 0 )
        {
            $db->lock( $tableName );

            $nextID = $db->nextID( $tableName, "ID" );

            $query = "INSERT INTO $tableName ( ID, $SQLPermission, ObjectID, GroupID )
                      VALUES
                      ( '$nextID', '1', '$objectID', '$groupID' )";

            $res = $db->query( $query );
        }
        else if ( $res[$db->fieldName("ID")] == 1 )
        {
            $query = "UPDATE $tableName SET $SQLPermission='1' WHERE ObjectID='$objectID' AND GroupID='$groupID'";
            $res = $db->query( $query );
        }
        else
        {
            print( "Duplicate objects in database. Please contact your administrator" );
            exit();
        }

        $db->unlock();

        if ( $res == false )
            $dbError = true;

        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      \static
      Removes all permissions of a given type on an object.
     */
    static public function removePermissions( $objectID, $moduleTable, $permission )
    {
        $tableName = getTableName( $moduleTable );
        if ( $tableName == "" )
        {
            return false;
        }

        $i = 0;
        $SQLPermission = "SET ";
        if ( strstr( $permission, 'r' ) )
        {
            $SQLPermission = "ReadPermission='0'";
            $i++;
        }
        if ( strstr( $permission, 'w' ) )
        {
            if ( $i > 0 )
                $SQLPermission .= ", ";
            $SQLPermission = "WritePermission='0'";
            $i++;
        }
        if ( strstr( $permission, 'u' ) )
        {
            if ( $i > 0 )
                $SQLPermission .= ", ";
            $SQLPermission = "UploadPermission='0'";
            $i++;
        }
        if ( $i == 0 )
            return false;

        $query = "UPDATE $tableName SET $SQLPermission WHERE ObjectID='$objectID'";
        $database =& eZDB::globalDatabase();
        $database->query( $query );
    }

    /*!
      Returns all the groups that have permissions to a given object, if none are selected a empty array is returned.
      If one object with -1 is returned, everyone has access to the object.
      $group is of type eZUserGroup or a groupID, use -1 for objects everyone is allowed to see.
      $moduleTable is the nickname of the table where the permission is found. The nicknames can be found in site.ini
      $permission either 'r' for readpermission, 'w' for writepermission or 'u' for upload permission
     */
    static public function getGroups( $objectID, $moduleTable, $permission, $GroupReturn=true )
    {
        $ret = array();
        $tableName = getTableName( $moduleTable );
        if ( $tableName == "" )
        {
            return $ret;
        }

        $SQLPermission = "";
        if ( $permission == 'r' )
        {
            $SQLPermission = "ReadPermission='1'";
        }
        else if ( $permission == 'w' )
        {
            $SQLPermission = "WritePermission='1'";
        }
        else if ( $permission == 'u' )
        {
            $SQLPermission = "UploadPermission='1'";
        }
        else // bogus $permission input.
        {
            return $ret;
        }

        $query = "SELECT GroupID FROM $tableName WHERE ObjectID='$objectID' AND $SQLPermission";
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, $query );

        if ( count( $res ) > 0 )
        {
            $i = 0;
            foreach ( $res as $groupID )
            {
                $id = $groupID[$db->fieldName("GroupID")];
                if ( $id  == -1 )
                {
                    $res = array();
                    $res[0] = -1;
                    return $res;
                }
                $ret[$i] = $GroupReturn ? new eZUserGroup( $id ) : $id;
                $i++;
            }
        }
        return $ret;
    }

    /*!
      Returns all the ID's of objects that a given user has permission $permission to
      If one object with -1 is returned, everyone has access to the object.
      $group is of type eZUserGroup or a groupID, use -1 for objects everyone is allowed to see.
      $moduleTable is the nickname of the table where the permission is found. The nicknames can be found in site.ini
      $permission either 'r' for readpermission, 'w' for writepermission or 'u' for upload permission.
      $count if set to true the function will return the count of accessable objects.
      $user of type eZUser, if left out, currentuser is used.
     */
    static public function getObjects( $moduleTable, $permission, $count = false , $user=false )
    {
        $ret = array();

        if ( $user == false )
        {
            $user =& eZUser::currentUser();
        }


        $SQLReturn = $count == true ? "count( ObjectID ) AS ObjectID" : "ObjectID";

        $SQLGroups = "GroupID = '-1'";
        if ( is_a( $user, "eZUser" ) )
        {
            $groups =& $user->groups( false );
            $first = true;
            if ( count( $groups ) > 0 )
            {
                foreach ( $groups as $groupItem )
                {
                    if ( $first == true )
                    {
                        $SQLGroups = "GroupID='$groupItem' ";
                    }
                    else
                    {
                        $SQLGroups .= "OR GroupID='$groupItem' ";
                    }
                    $first = false;
                }
                $SQLGroups .= "OR GroupID = '-1' ";
            }
        }

        $tableName = getTableName( $moduleTable );
        if ( $tableName == "" )
        {
            return $ret;
        }

        $SQLPermission = "";
        if ( $permission == 'r' )
        {
            $SQLPermission = "ReadPermission='1'";
        }
        else if ( $permission == 'w' )
        {
            $SQLPermission = "WritePermission='1'";
        }
        else if ( $permission == 'u' )
        {
            $SQLPermission = "UploadPermission='1'";
        }
        else // bogus $permission input.
        {
            return $ret;
        }

        $db =& eZDB::globalDatabase();
        if ( is_a( $user, "eZUser" ) and $user->hasRootAccess() )
            $query =  "SELECT $SQLReturn FROM $tableName";
        else
            $query = "SELECT $SQLReturn FROM $tableName WHERE ( $SQLGroups ) AND $SQLPermission";


        $db->array_query( $res, $query );
        if ( $count == true )
        {
            return $res[0][$db->fieldName("ObjectID")];
        }
        else
        {
            if ( count( $res ) > 0 )
            {
                $i = 0;
                foreach ( $res as $groupID )
                {
                    $ret[$i] = $groupID[$db->fieldName("ObjectID")];
                    $i++;
                }
            }

        }
        return $ret;
    }
}


/*!
  Returns table names.
*/
function getTableName( $name, $withDefinition=false )
{
    $ret = "";
    switch ( $name )
    {
        case "article_article" :
            if ( $withDefinition )
                $ret = "eZArticle_ArticlePermission as Object, eZArticle_CategoryPermission as Category, eZArticle_ArticleCategoryDefinition as Definition";
            else
                $ret = "eZArticle_ArticlePermission";
        break;

        case "article_category" :
            $ret = "eZArticle_CategoryPermission";
        break;

        case "trade_product" :
            if ( $withDefinition )
                $ret = "eZTrade_ProductPermission as Object, eZTrade_CategoryPermission as Category, eZTrade_ProductCategoryDefinition as Definition";
            else
                $ret = "eZTrade_ProductPermission";
            break;

        case "trade_category" :
            $ret = "eZTrade_CategoryPermission";
        break;

        case "imagecatalogue_image" :
            $ret = "eZImageCatalogue_ImagePermission";
        break;

        case "imagecatalogue_category" :
            $ret = "eZImageCatalogue_CategoryPermission";
        break;

        case "filemanager_folder" :
            $ret = "eZFileManager_FolderPermission";
        break;

        case "filemanager_file" :
            $ret = "eZFileManager_FilePermission";
        break;

        case "mediacatalogue_category" :
            $ret = "eZMediaCatalogue_CategoryPermission";
        break;

        case "mediacatalogue_media" :
            $ret = "eZMediaCatalogue_MediaPermission";
        break;

        case "bug_module" :
            $ret = "eZBug_ModulePermission";
        break;

        default :
            $ret = "";
        break;
    }
    return $ret;
}

?>