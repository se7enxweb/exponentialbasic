<?php
//
// $Id: moduleedit.php 9165 2002-02-07 08:12:04Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

/*
  Edit a module type.
*/

// include_once( "classes/INIFile.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$ini = eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZBugMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezbug/classes/ezbugmodule.php" );

if( !isset( $ModuleID ) )
    $ModuleID = 0;

if ( isset( $Action ) && $Action == "insert" )
{
    $module = new eZBugModule();
    $module->setName( $Name );
    $parent = new eZBugModule( $ParentID );
    $module->setParent( $parent );
    $module->setOwnerGroup( $WriteGroupArrayID );
    $module->store();
    $ModuleID = $module->id();
    eZObjectPermission::removePermissions( $ModuleID, "bug_module", "w" );
    foreach ( $WriteGroupArrayID as $moduleOwner )
    {
        eZObjectPermission::setPermission( $moduleOwner, $ModuleID, "bug_module", 'w' );
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/module/list/" );
    exit();
}

// Updates a module.
if ( isset( $Action ) &&  $Action == "update" )
{
    $module = new eZBugModule( $ModuleID );
    $parent = new eZBugModule( $ParentID );
    if ( $module->isChild( $ParentID, true ) != true )
    {
        $module->setName( $Name );
        $module->setParent( $parent );

        eZObjectPermission::removePermissions( $ModuleID, "bug_module", "w" );
        foreach ( $WriteGroupArrayID as $moduleOwner )
        {
            eZObjectPermission::setPermission( $moduleOwner, $ModuleID, "bug_module", 'w' );
        }

        if ( isset( $Recursive ) )
        {
            $recursiveList = $module->getByParent( $module, "name", array() );

            foreach ( $recursiveList as $itemID )
            {
                eZObjectPermission::removePermissions( $itemID, "bug_module", "w" );
                if ( count ( $WriteGroupArrayID ) > 0 )
                {
                    foreach ( $WriteGroupArrayID as $Write )
                    {
                        if ( $Write == -1 )
                            $group = -1;
                        else
                            $group = new eZUserGroup( $Write );

                        eZObjectPermission::setPermission( $group, $itemID, "bug_module", "w" );
                    }
                }
            }
        }

        $module->store();
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/module/list/" );
    exit();
}

// Delete a module.
if ( isset( $Action ) && $Action == "delete" )
{
    $module = new eZBugModule( $ModuleID );
    $module->delete();

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/module/list/" );
    exit();
}

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->variable( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "moduleedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "moduleedit" => "moduleedit.tpl"
    ) );

$t->set_block( "moduleedit", "module_item_tpl", "module_item" );
$t->set_block( "moduleedit", "module_owner_tpl", "module_owner" );

$t->set_block( "moduleedit", "write_group_item_tpl", "write_group_item" );

if ( isset( $Action ) && $Action == "new" )
{
    $parent = new eZBugModule( $ParentID );
    $t->set_var( "module_name", "" );
    $t->set_var("module_id", 0);
    $t->set_var( "action_value", "insert" );
}

// Edit a module.
if ( isset( $Action ) && $Action == "edit" )
{
    $module = new eZBugModule( $ModuleID );

    $parent = $module->parent();
    $t->set_var( "module_name", $module->name() );
    $t->set_var( "module_id", $module->id() );

    $writeGroupArrayID =& eZObjectPermission::getGroups( $module->id(), "bug_module", "w", false );

    $t->set_var( "action_value", "update" );
}

// Category selector

$module = new eZBugModule();

$moduleList = $module->getAll();

foreach ( $moduleList as $moduleItem )
{
    if ( $ModuleID != $moduleItem->id() )
    {
        $t->set_var( "module_parent_name", $moduleItem->name() );
        $t->set_var( "module_parent_id", $moduleItem->id() );


        if ( is_a( $parent, "eZBugModule" ) )
        {
            if ( $parent->id() == $moduleItem->id() )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );
            }
        }
        else
        {
            $t->set_var( "is_selected", "" );
        }

        $t->parse( "module_item", "module_item_tpl", true );
    }
}

// group selector
$group = new eZUserGroup();
$groupList =& $group->getAll();

foreach ( $groupList as $groupItem )
{
    $t->set_var( "group_id", $groupItem->id() );
    $t->set_var( "group_name", $groupItem->name() );

    $t->set_var( "is_write_selected1", "" );

    if ( isset( $writeGroupArrayID ) && $writeGroupArrayID )
    {
        foreach ( $writeGroupArrayID as $writeGroup )
        {
            if ( $writeGroup == $groupItem->id() )
            {
                $t->set_var( "is_write_selected1", "selected" );
            }
            elseif ( $writeGroup == -1 )
            {
                $t->set_var( "write_everybody", "selected" );
            }
            else
            {
                $t->set_var( "is_write_selected", "" );
            }
        }
    }

    $t->parse( "write_group_item", "write_group_item_tpl", true );
}

$t->pparse( "output", "moduleedit" );

?>