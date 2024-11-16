<?php
//
// $Id: linkselect.php 8145 2001-11-01 12:06:23Z ce $
//
// Created on: <30-Apr-2001 18:33:53 amos>
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

// ** Please see the linklist.php for variables to set before including this file. **
// Additional dynamic variables (which are normally taken from the url):
//  $ItemID = The id of the item which is being linked
//  $ModuleName = The name of the module being linked from (when browsing)
//  $Type = The name of the subtype being linked from (when browsing)
//  $SectionID = The section currently being worked on (when browsing)
//  $Category = The category currently visiting (when browsing)
//  $Offset = The offset being shown (when browsing)
//  $LinkID = The id of the link being edited

include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcachefile.php" );

if ( !isset( $ClientIntlDir ) )
{
    $ClientIntlDir = "classes/admin/intl/";
}
if ( !isset( $ClientRoot ) )
{
    $ClientRoot = "classes/";
}

if ( isset( $Back ) )
{
    eZHTTPTool::header( sprintf( "Location: " . $URLS["back"], $ItemID ) );
    exit();
}
include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( $INIGroup, "Language" );
$Modules = $ini->read_array( $INIGroup, "ModuleList" );
$DefaultSection = $ini->read_var( $INIGroup, "DefaultSectionName" );
$DefaultSections = $ini->read_array( $INIGroup, $DefaultSectionsName );

if ( isset( $ModuleType ) )
{
    include_once( "ezsession/classes/ezpreferences.php" );
    $preferences = new eZPreferences();
    $preferences->setVariable( $PreferencesSetting, $ModuleType );
}

include_once( "classes/ezlinksection.php" );
if ( is_array( $SectionIDList ) )
{
    foreach( $SectionIDList as $id )
    {
        $name = $SectionName[$id];
        $section = new eZLinkSection( $id, $ClientModuleName );
        $section->setName( $name );
        $section->store();
    }
}
$Funcs["delete"]( $ItemID );
if ( isset( $SubmitInfo ) )
{
    eZHTTPTool::header( sprintf( "Location: " . $URLS["back"], $ItemID ) );
    exit();
}

if ( isset( $DeleteLink ) )
{
    include_once( "classes/ezlinkitem.php" );
    include_once( "classes/ezlinksection.php" );
    include_once( "classes/ezmodulelink.php" );
    $deleted = false;
    if ( is_array( $DeleteLinkID ) )
    {
        foreach( $DeleteLinkID as $id )
        {
            eZLinkItem::delete( $id, $ClientModuleName );
            $deleted = true;
        }
    }
    if ( is_array( $DeleteSectionID ) )
    {
        $module = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
        foreach( $DeleteSectionID as $id )
        {
            $module->removeSection( $id );
            eZLinkSection::delete( $id, $ClientModuleName );
            $deleted = true;
        }
    }
    if ( $deleted )
    {
        $Funcs["delete"]( $ItemID );
    }
    eZHTTPTool::header( sprintf( "Location:" . $URLS["linklist"], $ItemID ) );
    exit();
}

include_once( "classes/ezmodulelink.php" );

$module_link = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
$sections =& $module_link->sections();

if ( isset( $NewSection ) or count( $sections ) == 0 )
{
    include_once( "classes/ezlinksection.php" );
    $section = new eZLinkSection( false, $ClientModuleName );
    $module = new eZModuleLink( $ClientModuleName, $ClientModuleType, $ItemID );
    $section_count = $module->sectionCount();
    $section_name = isset( $DefaultSections[$section_count] ) ? $DefaultSections[$section_count] : $DefaultSection;
    $section->setName( $section_name );

    $section->store();

    $SectionID = $section->id();
    $module->addSection( $section );

    $Funcs["delete"]( $ItemID );

    if ( isset( $NewSection ) )
    {
        eZHTTPTool::header( sprintf( "Location: " . $URLS["linklist"], $ItemID ) );
        exit();
    }
}

if ( !isset( $ModuleName ) or !isset( $Type ) )
{
    list($ModuleName,$Type) = explode( "/", $ModuleType );
    $module_lower = strtolower( $ModuleName );
    if ( $Type == "" )
        $Type = "null";
    if ( $module_lower == "std" )
    {
        eZHTTPTool::header( sprintf( "Location: " . $URLS["linkselect_std"], $ItemID, $module_lower, $Type, $SectionID, $LinkID ) );
    }
    else
    {
        eZHTTPTool::header( sprintf( "Location: " . $URLS["linkselect"], $ItemID, $module_lower, $Type, $SectionID, "0", $LinkID ) );
    }
    exit();
}

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

$intl_dirs = array( $ClientIntlDir );
$php_files = array( "linkselect.php" );
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

$t = new eZTemplate( $ClientRoot . $ini->read_var( $INIGroup, "AdminTemplateDir" ),
                     $intl_dirs, $Language, $php_files );

$t->set_file( "link_select_tpl", "linkselect.tpl" );

$t->set_block( "link_select_tpl", "normal_select_tpl", "normal_select" );
$t->set_block( "normal_select_tpl", "tree_select_tpl", "tree_select" );
$t->set_block( "normal_select_tpl", "url_select_tpl", "url_select" );
$t->set_block( "url_select_tpl", "url_selector_tpl", "url_selector" );
$t->set_block( "url_selector_tpl", "url_value_tpl", "url_value" );

$t->set_block( "tree_select_tpl", "category_list_tpl", "category_list" );
$t->set_block( "tree_select_tpl", "path_item_tpl", "path_item" );
$t->set_block( "path_item_tpl", "path_arrow_item_tpl", "path_arrow_item" );
$t->set_block( "path_item_tpl", "path_slash_item_tpl", "path_slash_item" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );
$t->set_block( "category_item_tpl", "category_checkbox_item_tpl", "category_checkbox_item" );
$t->set_block( "category_item_tpl", "category_radio_item_tpl", "category_radio_item" );
$t->set_block( "tree_select_tpl", "tree_selector_tpl", "tree_selector" );
$t->set_block( "tree_selector_tpl", "tree_value_tpl", "tree_value" );

$t->set_block( "tree_select_tpl", "object_list_tpl", "object_list" );
$t->set_block( "object_list_tpl", "object_item_tpl", "object_item" );
$t->set_block( "object_item_tpl", "object_checkbox_item_tpl", "object_checkbox_item" );
$t->set_block( "object_item_tpl", "object_radio_item_tpl", "object_radio_item" );

$t->set_block( "link_select_tpl", "module_select_tpl", "module_select" );
$t->set_block( "module_select_tpl", "module_item_tpl", "module_item" );
$t->set_block( "module_select_tpl", "module_selector_tpl", "module_selector" );
$t->set_block( "module_selector_tpl", "module_value_tpl", "module_value" );

$t->set_var( "category_list", "" );
$t->set_var( "object_list", "" );

$t->set_var( "link_select_url", sprintf( $URLS["linkselect"], "", "", "", "", "", "" ) );
$t->set_var( "link_type_select_url", $URLS["linkselect_basic"] );
$t->set_var( "link_list_url", sprintf( $URLS["linklist"], "" ) );

$module = $ModuleName;
$type = $Type;

$t->set_var( "object_id", $ItemID );
$t->set_var( "module_type", "$ModuleName/$Type" );
$t->set_var( "category_id", $Category );
$t->set_var( "section_id", $SectionID );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

$t->set_var( "normal_select", "" );
$t->set_var( "module_select", "" );
$t->set_var( "tree_select", "" );
$t->set_var( "url_select", "" );

$t->set_var( "client_name", $ClientModuleName );
$t->set_var( "client_type", $ClientModuleType );

include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();
$LinkType = $preferences->variable( $PreferencesSetting );

if ( is_bool( $LinkType ) )
{
    $LinkType = $ModuleType;
}

switch( $module )
{
    case "std":
    {
        if ( $type == "url" )
        {
            $LinkID = $Category;
            $t->set_var( "url_selector", "" );
            if ( isset( $LinkID ) and is_numeric( $LinkID ) )
            {
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
                            $module_lower = strtolower( $module );

                            $types =& $Supplier->urlTypes();
                            $t->set_var( "type_level", "" );
                            $t->set_var( "selected", $LinkType == $module_lower ? "selected" : "" );
                            $t->set_var( "module_select_type", $module_lower );
                            $t->set_var( "type_name", $Supplier->moduleName() );
                            $t->parse( "url_value", "url_value_tpl", true );
                            $t->set_var( "type_level", "&nbsp;" );
                            reset( $types );
                            while( list($key,$val) = each($types) )
                            {
                                $mod_type = "$module_lower/$key";
                                $t->set_var( "module_select_type", $mod_type );
                                $t->set_var( "type_name", $val );
                                $t->set_var( "selected", $LinkType == $mod_type ? "selected" : "" );
                                $t->parse( "url_value", "url_value_tpl", true );
                            }
                            $t->set_var( "url_selected", $LinkType == "std/url" ? "selected" : "" );
                        }
                    }
                }
                $t->parse( "url_selector", "url_selector_tpl" );
            }

            $t->set_var( "link_id", $LinkID );
            $link = new eZLinkItem( $LinkID, $ClientModuleName );
            $t->set_var( "url_name", $link->name() );
            $t->set_var( "url_src", $link->url() );
            $t->parse( "url_select", "url_select_tpl" );
            $t->parse( "normal_select", "normal_select_tpl" );
        }
        else
        {
            $module_lower = strtolower( $module );
            eZHTTPTool::header( sprintf( "Location: " . $URLS["linkselect"], $ItemID, $module_lower, $Type, $SectionID, "" ) );
            exit();
        }
        break;
    }

    default:
    {
        if ( $Type == "null" )
        {
            $t->set_var( "module_item", "" );
            $t->set_var( "module_selector", "" );
            if ( isset( $LinkID ) and is_numeric( $LinkID ) )
            {
                foreach( $Modules as $file_module )
                {
                    $module_lower = strtolower( $file_module );
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
                            $t->set_var( "type_name", $Supplier->moduleName() );
                            $t->parse( "module_value", "module_value_tpl", true );
                            $t->set_var( "type_level", "&nbsp;" );
                            reset( $types );
                            while( list($key,$val) = each($types) )
                            {
                                $mod_type = "$module_lower/$key";
                                $t->set_var( "module_type", $mod_type );
                                $t->set_var( "type_name", $val );
                                $t->set_var( "selected", $LinkType == $mod_type ? "selected" : "" );
                                $t->parse( "module_value", "module_value_tpl", true );
                            }
                            $t->set_var( "url_selected", $LinkType == "std/url" ? "selected" : "" );
                        }
                    }
                }
                $t->parse( "module_selector", "module_selector_tpl" );
            }
            $file = strtolower( $module ) . "/user/urlsupplier.php";
            if ( eZFile::file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    $module_lower = strtolower( $module );
                    $types =& $Supplier->urlTypes();
                    $t->set_var( "module_name", $module );
                    reset( $types );
                    while( list($key,$val) = each( $types ) )
                    {
                        $t->set_var( "module_type", $key );
                        $t->set_var( "module_type_name", $val );
                        $t->set_var( "link_select_url", sprintf( $URLS["linkselect"], $ItemID, $module, $key, $SectionID, 0, $LinkID ) );
                        $t->parse( "module_item", "module_item_tpl", true );
                    }
                }
            }
            $t->parse( "module_select", "module_select_tpl" );
        }
        else
        {
            $t->set_var( "tree_selector", "" );
            if ( isset( $LinkID ) and is_numeric( $LinkID ) )
            {
                $module_lower = strtolower( $module );
                foreach( $Modules as $file_module )
                {
                    $module_lower = strtolower( $file_module );
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
                            $t->set_var( "type_name", $Supplier->moduleName() );
                            $t->parse( "tree_value", "tree_value_tpl", true );
                            $t->set_var( "type_level", "&nbsp;" );
                            reset( $types );
                            while( list($key,$val) = each($types) )
                            {
                                $mod_type = "$module_lower/$key";
                                $t->set_var( "module_type", $mod_type );
                                $t->set_var( "type_name", $val );
                                $t->set_var( "selected", $LinkType == $mod_type ? "selected" : "" );
                                $t->parse( "tree_value", "tree_value_tpl", true );
                            }
                            $t->set_var( "url_selected", $LinkType == "std/url" ? "selected" : "" );
                        }
                    }
                }
                $t->parse( "tree_selector", "tree_selector_tpl" );
            }
            $module_lower = strtolower( $module );
            $file = strtolower( $module ) . "/user/urlsupplier.php";
            if ( eZFile::file_exists( $file ) )
            {
                unset( $Supplier );
                include( $file );
                if ( isset( $Supplier ) and get_class( $Supplier ) )
                {
                    $list =& $Supplier->urlList( $type, $Category, $Offset );
                    $categories =& $list["categories"];
                    $path =& $list["path"];
                    $t->set_var( "category_item", "" );
                    $i = 0;
                    $link = false;
                    if ( isset( $LinkID ) and is_numeric( $LinkID ) )
                    {
                        $link = new eZLinkItem( $LinkID, $ClientModuleName );
                    }
                    $t->set_var( "path_item", "" );
                    $path = array_merge( array( array( "id" => 0, "name" => "{intl-root}" ) ),
                                         $path );
                    $i = 0;
                    foreach( $path as $path_item )
                    {
                        $t->set_var( "path_arrow_item", "" );
                        $t->set_var( "path_slash_item", "" );
                        $url = sprintf( $URLS["linkselect"], $ItemID, $module_lower, $type, $SectionID, $path_item["id"], $LinkID );
                        $t->set_var( "path_url", $url );
                        $t->set_var( "path_name", $path_item["name"] );
                        if ( $i > 0 )
                            $t->parse( "path_slash_item", "path_slash_item_tpl" );
                        else
                            $t->parse( "path_arrow_item", "path_arrow_item_tpl" );
                        $t->parse( "path_item", "path_item_tpl", true );
                        ++$i;
                    }
                    foreach( $categories as $category )
                    {
                        $t->set_var( "category_checkbox_item", "" );
                        $t->set_var( "category_radio_item", "" );
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $category_id = $category["id"];
                        $url = sprintf( $URLS["linkselect"], $ItemID, $module_lower, $type, $SectionID, $category_id, $LinkID );
                        $t->set_var( "category_item_id", $category_id );
                        $t->set_var( "category_url", $url );
                        $t->set_var( "category_name", $category["name"] );
                        $t->set_var( "category_orig_url", $category["url"] );
                        $t->set_var( "radio_select", "selected" );
                        if ( is_a( $link, "eZLinkItem" ) and $link->url() == $category["url"] )
                        {
                            $t->set_var( "td_class", "bgcurrent" );
                            $t->set_var( "radio_select", "checked" );
                        }
                        if ( is_a( $link, "eZLinkItem" ) )
                        {
                            $t->parse( "category_radio_item", "category_radio_item_tpl" );
                        }
                        else
                        {
                            $t->parse( "category_checkbox_item", "category_checkbox_item_tpl" );
                        }
                        $t->parse( "category_item", "category_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $categories ) > 0 )
                        $t->parse( "category_list", "category_list_tpl" );

                    $objects =& $list["items"];
                    $t->set_var( "object_item", "" );
                    $i = 0;
                    foreach( $objects as $object )
                    {
                        $t->set_var( "object_checkbox_item", "" );
                        $t->set_var( "object_radio_item", "" );
                        $t->set_var( "td_class", ($i % 2 ) == 0 ? "bglight" : "bgdark" );
                        $object_id = $object["id"];
                        $module_lower = strtolower( $module );
                        $t->set_var( "item_id", $object_id );
                        $t->set_var( "object_name", $object["name"] );
                        $t->set_var( "object_orig_url", $object["url"] );
                        $t->set_var( "radio_select", "selected" );
                        if ( is_a( $link, "eZLinkItem" ) and $link->url() == $object["url"] )
                        {
                            $t->set_var( "td_class", "bgcurrent" );
                            $t->set_var( "radio_select", "checked" );
                        }
                        if ( is_a( $link, "eZLinkItem" ) )
                            $t->parse( "object_radio_item", "object_radio_item_tpl" );
                        else
                            $t->parse( "object_checkbox_item", "object_checkbox_item_tpl" );
                        $t->parse( "object_item", "object_item_tpl", true );
                        ++$i;
                    }
                    if ( count( $objects ) > 0 )
                        $t->parse( "object_list", "object_list_tpl" );

                    $TotalTypes = $list["item_total_count"];
                    eZList::drawNavigator( $t, $TotalTypes, $list["max_items_shown"], $Offset, "tree_select_tpl" );
                }
            }
            $t->parse( "tree_select", "tree_select_tpl" );
            $t->parse( "normal_select", "normal_select_tpl" );
        }
        break;
    }
}

$t->set_var( "link_id", $LinkID );

$t->set_var( "site_style", $SiteStyle );

$t->setAllStrings();

$t->pparse( "output", "link_select_tpl" );

?>
