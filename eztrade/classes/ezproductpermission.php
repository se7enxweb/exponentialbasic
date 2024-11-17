<?php
//
// $Id: ezproductpermission.php 7382 2001-09-21 14:28:49Z jhe $
//
// Definition of eZProductPermission class
//
// Created on: <06-Aug-2001 10:01:09 jhe>
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

//!!
//! The class eZProductPermission handles permissions of products
/*!

*/

include_once( "classes/ezdb.php" );

class eZProductPermission
{
    function hasPermission( $productID, $user = false )
    {
        $db =& eZDB::globalDatabase();

        if ( is_a( $user, "eZUser" ) )
        {
            $user =& eZUser::currentUser();
        }

        if ( !$user )
            return false;
        $ret = false;

        $groups =& $user->groups( false );
        foreach ( $groups as $groupItem )
        {
            $db->array_query( $permissions, "SELECT * FROM eZTrade_ProductPermissionLink
                                             WHERE GroupID='" . $groupItem ."'" );
            if ( count( $permissions ) > 0 )
            {
                $ret = true;
            }
        }
        return $ret;
    }

    function getPermissionList( $productID )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $list, "SELECT * FROM eZTrade_ProductPermissionLink
                                  WHERE ProductID='$productID'" );
        $ret = array();
        foreach ( $list as $element )
        {
            $ret[] = $element[$db->fieldName( "GroupID" )];
        }
        return $ret;
    }

    function setPermission( $productID, $groupID )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZTrade_ProductPermissionLink" );
        $nextID = $db->nextID( "eZTrade_ProductPermissionLink" );
        $res[] = $db->query( "INSERT INTO eZTrade_ProductPermissionLink (ID, ProductID, GroupID)
                              VALUES ('$nextID', '$productID', '$groupID')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    function removePermissions( $productID )
    {
        $db =& eZDB::globalDatabase();

        $db->begin();
        $res[] = $db->query( "DELETE FROM eZTrade_ProductPermissionLink
                              WHERE ProductID='$productID'" );
        eZDB::finish( $res, $db );
    }
}

?>

