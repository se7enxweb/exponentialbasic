<?php
//
// $Id: unacceptededit.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <21-Jan-2001 13:34:48 bf>
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

include_once( "classes/ezhttptool.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezlinkcategory.php" );

require( "ezuser/admin/admincheck.php" );

for( $i = 0; $i < count( $LinkArrayID ); $i++ )
{
    unset( $link );
    $link = new eZLink( $LinkArrayID[$i] );
    $link->setName( $Name[$i] );
    $link->setCategoryDefinition( $LinkCategoryID[$i] );

    // Calculate new and unused categories
    $old_maincategory = $link->categoryDefinition();
    $old_categories =& array_unique( array_merge( array( $old_maincategory->id() ),
                                                  $link->categories( false ) ) );

    $new_categories =& array_unique( array_merge( array( $LinkCategoryID[$i] ), array( $CategoryArray[$i] ) ) );
    $remove_categories = array_diff( $old_categories, $new_categories );
    $add_categories = array_diff( $new_categories, $old_categories );

    foreach ( $remove_categories as $category )
    {
        eZLinkCategory::removeLink( $link, $category );
    }
    foreach ( $add_categories as $category )
    {
        eZLinkCategory::addLink( $link, $category );
    }
    
    $link->setUrl( $Url[$i] );
    $link->setKeyWords( $Keywords[$i] );
    $link->setDescription( $Description[$i] );
    $link->setAccepted( false );

    if ( $ActionValueArray[$i] == "Defer" )
    {
    }
    else if ( $ActionValueArray[$i] == "Accept" )
    {
        $link->setAccepted( true );
        $link->update();
    }
    else if ( $ActionValueArray[$i] == "Delete" )
    {
        $link->delete();
    }
    else if ( $ActionValueArray[$i] == "Update" )
    {
        $link->update();
    }
}
eZHTTPTool::header( "Location: /link/unacceptedlist/" );
exit();

?>