<?php
// 
// $Id: buglist.php 7766 2001-10-10 20:18:53Z fh $
//
// Created on: <04-Dec-2000 11:36:41 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbug.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "ezbug/user/intl/", $Language, "buglist.php" );

$t->setAllStrings();

$t->set_file( array(
    "bug_list_page_tpl" => "buglist.tpl"
    ) );

// path
$t->set_block( "bug_list_page_tpl", "path_item_tpl", "path_item" );

// module
$t->set_block( "bug_list_page_tpl", "module_list_tpl", "module_list" );
$t->set_block( "module_list_tpl", "module_item_tpl", "module_item" );

// bug
$t->set_block( "bug_list_page_tpl", "bug_list_tpl", "bug_list" );
$t->set_block( "bug_list_tpl", "bug_item_tpl", "bug_item" );
$t->set_block( "bug_item_tpl", "bug_is_closed_tpl", "bug_is_closed" );
$t->set_block( "bug_item_tpl", "bug_is_open_tpl", "bug_is_open" );
$t->set_block( "bug_item_tpl", "bug_edit_tpl", "bug_edit" );

$t->set_block( "bug_list_page_tpl", "bug_edit_buttons_tpl", "bug_edit_buttons" );
$module = new eZBugModule( $ModuleID );

$t->set_var( "current_module_id", $module->id() );
$t->set_var( "current_module_name", $module->name() );
$t->set_var( "current_module_description", $module->description() );

// check user rights
$user =& eZUser::currentUser();
$gotRights = false;

if ( eZObjectPermission::hasPermission( $module->id(), "bug_module", "w", $user ) )
{
    $gotRights = true;
}

// check if delete was pressed && if the user has rights to delete
if( isset( $Delete ) && $gotRights == true )
{
if( count( $BugArrayID ) > 0 )
    {
        foreach( $BugArrayID as $doomedBugID )
        {
            $bug = new eZBug( $doomedBugID );
            $bug->delete();
        }
    }

}

// path
$pathArray = $module->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "module_id", $path[0] );

    $t->set_var( "module_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$moduleList =& $module->getByParent( $module, true );


// categories
$i=0;
$t->set_var( "module_list", "" );
foreach ( $moduleList as $moduleItem )
{
    $t->set_var( "module_id", $moduleItem->id() );

    $t->set_var( "module_name", $moduleItem->name() );

    $parent =& $moduleItem->parent();

    $totalCount = $moduleItem->countBugs( true, false , true );
    $t->set_var( "bug_count", $totalCount );

    $openCount = $moduleItem->countBugs( true, true , true );
    $t->set_var( "open_bug_count", $openCount );
    

    
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }    
    
    $t->set_var( "module_description", $moduleItem->description() );

    $t->parse( "module_item", "module_item_tpl", true );
    $i++;
}

if ( count( $moduleList ) > 0 )    
    $t->parse( "module_list", "module_list_tpl" );
else
    $t->set_var( "module_list", "" );


// bugs
$bugList =& $module->bugs( "time", true );
$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "bug_list", "" );
foreach ( $bugList as $bug )
{
    $t->set_var( "bug_id", $bug->id() );
    $t->set_var( "bug_name", $bug->name() );

    $pri =& $bug->priority();
    $status =& $bug->status();
    
    if ( $pri )
    {    
        $t->set_var( "bug_priority", $pri->name() );
    }
    else
    {
        $t->set_var( "bug_priority", "" );
    }

    if ( $status )
    {    
        $t->set_var( "bug_status", $status->name() );
    }
    else
    {
        $t->set_var( "bug_status", "" );
    }

    if ( $bug->isClosed() == true )
    {
        $t->parse( "bug_is_closed", "bug_is_closed_tpl" );
        $t->set_var( "bug_is_open", "" );
    }
    else
    {
        $t->set_var( "bug_is_closed", "" );
        $t->parse( "bug_is_open", "bug_is_open_tpl" );
    }

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
        $td_class = "bglight";
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
        $td_class = "bgdark";
    }

    if( $gotRights == true ) // insert the checkbox and edit button
    {
        $t->parse( "bug_edit", "bug_edit_tpl" );
    }
    else
    {
        $t->set_var( "bug_edit", "" );
    }
    
    $t->parse( "bug_item", "bug_item_tpl", true );
    $i++;
}


if ( count( $bugList ) > 0 )    
    $t->parse( "bug_list", "bug_list_tpl" );
else
    $t->set_var( "bug_list", "" );

if( $gotRights == true )  // insert the buttons
{
    $t->parse( "bug_edit_buttons", "bug_edit_buttons_tpl" );
}
else
{
    $t->set_var( "bug_edit_buttons", "" );
}

$t->pparse( "output", "bug_list_page_tpl" );
?>
