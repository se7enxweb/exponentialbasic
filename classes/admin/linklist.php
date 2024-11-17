<?php
// 
// $Id: linklist.php 8145 2001-11-01 12:06:23Z ce $
//
// Created on: <30-Apr-2001 18:50:47 amos>
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

// To use the general link list you need to initialize some variables before
// including this file. The variables are:
//
// Static variables:
//  $INIGroup = The group name of the module in the site.ini file, eg. "eZTradeMain"
//  $DefaultSectionsName = site.ini item name which contains the default sections names, eg. "ProductLinkSections"
//  $PreferencesSetting = Preferences variable name which contains the type selection, eg. "ProductLinkType"
//  $ClientModuleName = Name of the module in use, eg. "eZTrade"
//  $ClientModuleType = Name of the module sub type in use, eg. "Product"
//  $root = Helper variable for the url list, not used by linklist, eg. "/trade/productedit"
//  $URLS = Array of urls which are used in navigating the link pages.
//  Must contain these keys (values are examples only),
//  the values must always contain the same amount of % items as in the examples, and in the same order.
//  array( "back" => "$root/edit/%s",
//         "linklist" => "$root/link/list/%s",
//         "linkmoveup" => "$root/link/moveup/link/%d/%d/%d",
//         "linkmovedown" => "$root/link/movedown/link/%d/%d/%d",
//         "sectionmoveup" => "$root/link/moveup/section/%d/%d",
//         "sectionmovedown" => "$root/link/movedown/section/%d/%d",
//         "linkselect" => "$root/link/select/%s/%s/%s/%s/%s/0/%s",
//         "linkselect_basic" => "$root/link/select/",
//         "linkselect_std" => "$root/link/select/%s/%s/%s/%s/%s",
//         "urledit" => "$root/link/select/%s/%s/%s/%s",
//         "linkedit" => "$root/link/select/%s/%s/%s/0/0/%s" );
//  $Funcs = Array of functions to call, currently only "delete" is used.
//  eg. array( "delete" => "deleteCacheHelper" );
// Variables which you may set to use a different template and/or intl file.
//  $ClientRoot = The root of the module, default is "classes/", eg. "eztrade/admin/"
//  $ClientIntlDir = The intl dir of the module, default is "classes/admin/intl/", eg. "eztrade/admin/intl/"

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezhttptool.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( $INIGroup, "Language" );
$Modules = $ini->read_array( $INIGroup, "ModuleList" );

if ( !isset( $ClientRoot ) )
{
    $ClientRoot = "classes/";
}
if ( !isset( $ClientIntlDir ) )
{
    $ClientIntlDir = "classes/admin/intl/";
}

$intl_dirs = array( $ClientIntlDir );
$php_files = array( "linklist.php" );
foreach( $Modules as $module )
{
    $dir = strtolower( $module ) . "/user/intl/";
    $file = strtolower( $module ) . "/user/urlsupplier.php";
    if ( eZFile::file_exists( $file ) )
    {
        $intl_dirs[] = $dir;
        $php_files[] = "urlsupplier.php";
    }
}

include_once( "classes/ezmodulelink.php" );
include_once( "classes/ezlinksection.php" );
include_once( "classes/ezlinkitem.php" );

if ( isset( $ItemInsert ) )
{
    list($ModuleName,$Type) = explode( "/", $ModuleType );
    $module_lower = strtolower( $ModuleName );
    if ( !isset( $LinkID ) )
         $LinkID = false;
    switch( $ModuleName )
    {
        case "std":
        {
            if ( $Type == "url" )
            {
                $link_item = new eZLinkItem( $LinkID, $ClientModuleName );
                $link_item->setName( $URLName );
                $link_item->setURL( $URL );
                $link_item->setSection( $SectionID );

                $link_item->setType( $ModuleName,$Type );

                $link_item->store();
                $Funcs["delete"]( $ItemID );
            }
            else
            {
            }
            break;
        }

        default:
        {
            $file = $module_lower . "/user/urlsupplier.php";
            if ( eZFile::file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    if ( !is_array( $CategorySelect ) )
                        $CategorySelect = array();
                    if ( !is_array( $ItemSelect ) )
                        $ItemSelect = array();
                    foreach( $CategorySelect as $category )
                    {
                        $category_info = $Supplier->item( $Type, $category, true );
                        $link_item = new eZLinkItem( $LinkID, $ClientModuleName );
                        $link_item->setName( $category_info["name"] );
                        $link_item->setURL( $category_info["url"] );
                        $link_item->setSection( $SectionID );
                        $link_item->setType( $ModuleName,$Type );
                        $link_item->store();
                    }
                    foreach( $ItemSelect as $item )
                    {
                        if ( isset( $LinkID ) and $item < 0 )
                        {
                            $item_info = $Supplier->item( $Type, -$item, true );
                        }
                        else
                            $item_info = $Supplier->item( $Type, $item, false );
                        $link_item = new eZLinkItem( $LinkID, $ClientModuleName );
                        $link_item->setName( $item_info["name"] );
                        $link_item->setURL( $item_info["url"] );
                        $link_item->setSection( $SectionID );
                        $link_item->setType( $ModuleName,$Type );
                        $link_item->store();
                    }
                    $Funcs["delete"]( $ItemID );
                }
            }
            break;
        }
    }
}

$t = new eZTemplate( $ClientRoot . $ini->read_var( $INIGroup, "AdminTemplateDir" ),
                     $intl_dirs, $Language, $php_files );

$t->set_file( "link_list_tpl", "linklist.tpl" );

$t->set_block( "link_list_tpl", "value_tpl", "value" );

$t->set_block( "link_list_tpl", "section_item_tpl", "section_item" );
$t->set_block( "section_item_tpl", "link_item_tpl", "link_item" );

$t->set_block( "link_item_tpl", "link_edit_item_tpl", "link_edit_item" );

$t->set_var( "value", "" );

$t->set_var( "client_name", $ClientModuleName );
$t->set_var( "client_type", $ClientModuleType );

include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();
$LinkType = $preferences->variable( $PreferencesSetting );
if ( is_bool( $LinkType ) )
    $LinkType = $ModuleType;

$t->set_var( "url_selected", "" );
foreach( $Modules as $module )
{
    $module_lower = strtolower( $module );
    $file = $module_lower . "/user/urlsupplier.php";
    if ( eZFile::file_exists( $file ) )
    {
        unset( $Supplier );
        include( $file );
        if ( isset( $Supplier ) and get_class( $Supplier ) )
        {
            $types =& $Supplier->urlTypes();
            $t->set_var( "type_level", "" );
            $t->set_var( "selected", $LinkType == $module_lower ? "selected" : "" );
            $t->set_var( "module_type", $module_lower );
            $t->set_var( "type_name", $module );
            $t->parse( "value", "value_tpl", true );
            $t->set_var( "type_level", "&nbsp;" );
            reset( $types );
            while( list($key,$val) = each($types) )
            {
                $type = "$module_lower/$key";
                $t->set_var( "module_type", $type );
                $t->set_var( "type_name", $val );
                $t->set_var( "selected", $LinkType == $type ? "selected" : "" );
                $t->parse( "value", "value_tpl", true );
            }
            $t->set_var( "url_selected", $LinkType == "std/url" ? "selected" : "" );
        }
    }
}
$t->set_var( "url", $REQUEST_URI );
$t->set_var( "item_id", $ItemID );

$t->set_var( "section_item", "" );
$module_link = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
$sections =& $module_link->sections();
$item = 0;
if ( !isset( $SectionID ) or ( $SectionID <= 0 ) )
{
    if ( count( $sections ) > 0 )
        $SectionID = $sections[0]->id();
    else
        $SectionID = 1;
}
foreach( $sections as $section )
{
    $t->set_var( "link_item", "" );
    $t->set_var( "section_name", $section->name() );
    $t->set_var( "section_id", $section->id() );
    $t->set_var( "section_checked", $section->id() == $SectionID ? "checked" : "" );
    $links =& $section->links();
    $i = 0;
    foreach( $links as $link )
    {
        $t->set_var( "link_edit_item", "" );
        $t->set_var( "td_class", ($i%2) == 0 ? "bglight" : "bgdark" );
        $t->set_var( "link_name", $link->name() );
        $t->set_var( "link_url", $link->url() );
        $t->set_var( "link_id", $link->id() );
        $m_name = "std";
        $m_type = "url";
        if ( $link->type() != 0 )
        {
            $type_array = $link->type( true );
            $m_name = $type_array["Module"];
            $m_type = $type_array["Type"];
        }
        $t->set_var( "link_module_name", $m_name );
        $t->set_var( "link_module_type", $m_type );
        if ( $m_name != "std" )
        {
            $url_str = $URLS["linkedit"];
        }
        else
        {
            $url_str = $URLS["urledit"];
        }
        $t->set_var( "item_edit_command", sprintf( $url_str, $ItemID , "$m_name/$m_type", $section->id(), $link->id() ) );
        $t->parse( "link_edit_item", "link_edit_item_tpl" );
        $t->set_var( "item_up_command", sprintf( $URLS["linkmoveup"], $ItemID, $section->id(), $link->id() ) );
        $t->set_var( "item_down_command", sprintf( $URLS["linkmovedown"], $ItemID, $section->id(), $link->id() ) );
        $t->parse( "link_item", "link_item_tpl", true );
        ++$i;
    }
    $t->set_var( "item_up_command", sprintf( $URLS["sectionmoveup"], $ItemID, $section->id() ) );
    $t->set_var( "item_down_command", sprintf( $URLS["sectionmovedown"], $ItemID, $section->id() ) );
    $t->parse( "section_item", "section_item_tpl", true );
    ++$item;
}

$t->set_var( "link_list_url", $URLS["linkselect_basic"] );

$t->setAllStrings();

$t->pparse( "output", "link_list_tpl" );

?>
