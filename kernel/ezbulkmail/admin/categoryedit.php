<?php
// 
// $Id: categoryedit.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <18-Apr-2001 11:15:33 fh>
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

// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );

if( isset( $Cancel ) ) // cancel pressed, redirect to categorylist page...
{
    eZHTTPTool::header( "Location: /bulkmail/categorylist/" );
    exit();
}

if( isset( $Ok ) ) // cancel pressed, redirect to categorylist page...
{
    if( $CategoryID == 0 )
    {
        $category = new eZBulkMailCategory();
    }
    else
    {
        $category = new eZBulkMailCategory( $CategoryID );
    }
    $category->setDescription( $Description );
    $category->setName( $Name );

    if( isset( $PublicList ) )
        $category->setIsPublic( true );
    else
        $category->setIsPublic( false );
    
    $category->store();

    $category->removeGroupSubscription( true );
    if( count( $SubscriptionGroupsArrayID ) > 0 )
    {
        foreach( $SubscriptionGroupsArrayID as $groupID )
            $category->addGroupSubscription( $groupID );
    }
    $id = $category->id();
    eZHTTPTool::header( "Location: /bulkmail/categorylist/$id" );
    exit();
}


$t = new eZTemplate( "kernel/ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "kernel/ezbulkmail/admin/intl", $Language, "categoryedit.php" );
$t->set_file( array(
    "category_edit_tpl" => "categoryedit.tpl"
    ) );

$t->setAllStrings();
$t->set_block( "category_edit_tpl", "subscribe_group_item_tpl", "subscribe_group_item" );
$t->set_var( "site_style", $SiteDesign );

$t->set_var( "category_name", "" );
$t->set_var( "description", "" );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "subscribe_group_item", "" );
$t->set_var( "checked", "checked" );

$subscribedGroups = array();
if( $CategoryID != 0  )
{
    $category = new eZBulkMailCategory( $CategoryID );
    if( is_object( $category ) )
    {
        $t->set_var( "category_name", $category->name() );
        $t->set_var( "description", $category->description() );
        $subscribedGroups = $category->groupSubscriptions( false );

        if( $category->isPublic() )
            $t->set_var( "checked", "checked" );
        else
            $t->set_var( "checked", "" );
    }
}

// show all user groups in the list!
// Print out all the groups.
$groups =& eZUserGroup::getAll();
foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", $group->name() );

    $t->set_var( "selected", "" );
    if( in_array( $group->id(), $subscribedGroups ) )
        $t->set_var( "selected", "selected" );

    $t->parse( "subscribe_group_item", "subscribe_group_item_tpl", true );
}

$t->pparse( "output", "category_edit_tpl" );

?>