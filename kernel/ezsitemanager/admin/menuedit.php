<?php
// 
// $Id: menuedit.php 7507 2001-09-27 10:43:55Z ce $
//
// Created on: <27-Sep-2001 12:38:58 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezsitemanager/classes/ezmenu.php" );

if ( isSet ( $OK ) )
{
    $Action = "Insert";
}
if ( isSet ( $Delete ) )
{
    $Action = "Delete";
}
if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /sitemanager/menu/list/" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "menuedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "menu_edit_page" => "menuedit.tpl"
      ) );

$t->set_block( "menu_edit_page", "menu_item_tpl", "menu_item" );

$t->set_var( "menu_item", "" );
$t->set_var( "menu_name", "" );
$t->set_var( "menu_link", "" );

if ( ( $Action == "Insert" ) || ( $Action == "Update" ) && ( $user ) )
{
    if ( is_numeric( $MenuID ) )
        $menu = new eZMenu( $MenuID);
    else
        $menu = new eZMenu();

    $menu->setName( $Name );
    $menu->setLink( $Link );
    $menu->setParent( $ParentID );
    $menu->setType( $Type );
    $menu->store();

    eZHTTPTool::header( "Location: /sitemanager/menu/list/" );
    exit();
}

if ( $Action == "Delete" )
{
    if ( count ( $MenuArrayID ) > 0 )
    {
        foreach( $MenuArrayID as $MenuID )
        {
            $menu = new eZMenu( $MenuID );
            $menu->delete();
        }
    }
    eZHTTPTool::header( "Location: /sitemanager/menu/list/" );
    exit();
}

if ( is_numeric( $MenuID ) )
{
    $menu = new eZMenu( $MenuID );

    $t->set_var( "menu_id", $menu->id() );
    $t->set_var( "menu_name", $menu->name() );
    $t->set_var( "menu_link", $menu->link() );

    if ( $menu->type() == 1 )
        $t->set_var( "1_checked", "checked" );
    else
        $t->set_var( "2_checked", "checked" );
    $parent = $menu->parent();

}

$menuList =& eZMenu::getAll();

foreach( $menuList as $menuItem )
{
    $t->set_var( "select_id", $menuItem->id() );
    $t->set_var( "select_name", $menuItem->name() );
    

    if ( $parent )
    {
        if ( $parent->id() == $menuItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
        $t->set_var( "root_select", "selected" );

    if ( $MenuID != $menuItem->id() )
        $t->parse( "menu_item", "menu_item_tpl", true );
}

$t->pparse( "output", "menu_edit_page" );
?>
