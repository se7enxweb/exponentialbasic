<?php
// 
// $Id: menulist.php 7507 2001-09-27 10:43:55Z ce $
//
// Created on: <10-May-2001 15:33:23 ce>
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
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );
$Limit = $ini->read_var( "eZSiteManagerMain", "AdminListLimit" );

include_once( "ezsitemanager/classes/ezmenu.php" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/" . "/intl", $Language, "menulist.php" );
$t->setAllStrings();

$t->set_file( array(
    "menu_page" => "menulist.tpl"
      ) );

$t->set_block( "menu_page", "menu_list_tpl", "menu_list" );
$t->set_block( "menu_list_tpl", "menu_item_tpl", "menu_item" );

// path
$t->set_block( "menu_page", "path_item_tpl", "path_item" );


if ( !isset( $Offset ) )
    $Offset = 0;

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "menu_list", "" );
$t->set_var( "path_item", "" );
$menuList =& eZMenu::getByParent( $ParentID, $Offset, $Limit );
$totalCount =& eZMenu::count( $ParentID );


$menu = new eZMenu( $ParentID );
// path
$pathArray =& $menu->path();
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

if ( count ( $menuList ) > 0 )
{
    $i=0;
    foreach( $menuList as $menu )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "menu_id", $menu->id() );
        $t->set_var( "menu_name", $menu->name() );
        $t->set_var( "menu_link", $menu->link() );
        
        $t->parse( "menu_item", "menu_item_tpl", true );
        $i++;
    }
    $t->parse( "menu_list", "menu_list_tpl" );
}
eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "menu_page" );

$t->set_var( "menu_start", $Offset + 1 );
$t->set_var( "menu_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "menu_total", $totalCount );


$t->pparse( "output", "menu_page" );

?>