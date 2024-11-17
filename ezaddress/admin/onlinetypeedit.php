<?php
//
// $Id: onlinetypeedit.php 6204 2001-07-19 12:06:56Z jakobn $
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

include_once( "ezaddress/classes/ezonlinetype.php" );

$language_file = "onlinetype.php";
$item_type = new eZOnlineType( $OnlineTypeID );
if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZOnlineType( $item_id );
    }
}

$page_path = "/address/onlinetype";

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAddressMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZAddressMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZAddressMain", "AdminTemplateDir" ),
                     $DOC_ROOT . "admin/intl", $Language, $language_file );
$t->setAllStrings();

$item_error = true;

if( empty( $HTTP_REFERER ) )
{
    if( empty( $BackUrl ) )
    {
        $back_command = "$page_path/list";
    }
    else
    {
        $back_command = $BackUrl;
    }
}
else
{
    $back_command = $HTTP_REFERER;
}

if ( isset( $Delete ) and isset( $ItemArrayID ) and isset( $item_types ) )
{
    foreach( $item_types as $item_type )
    {
        $item_type->delete( false );
    }
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if( $Action == "up" )
{
    $item_type->moveUp();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if( $Action == "down" )
{
    $item_type->moveDown();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if( $Action == "insert" || $Action == "update" )
{
    if ( $Action == "insert" )
        unset( $item_type->ID );
    $item_type->setName( $ItemName );
    $item_type->setURLPrefix( $Prefix );
    $item_type->setPrefixLink( $PrefixLink );
    $item_type->setPrefixVisual( $PrefixVisual );
    $item_type->store();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
}

$t->set_file( array(
    "list_page" =>  "onlineedit.tpl",
    ) );
$t->set_block( "list_page", "type_edit_tpl", "type_edit" );

$t->set_block( "type_edit_tpl", "line_item_tpl", "line_item" );
$t->set_block( "type_edit_tpl", "no_line_item_tpl", "no_line_item" );

$t->set_var( "no_line_item", "" );
$t->set_var( "line_item", "" );

$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );
$t->set_var( "prefix", $Prefix );
$t->set_var( "prefix_link_checked", $PrefixLink );
$t->set_var( "prefix_visual_checked", $PrefixVisual );
$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

if( $error == false )
{
    $t->set_var( "errors", "" );
}

if( is_numeric( $item_type->id() ) )
{

    print( $item_type->name() );
    $t->set_var( "item_id", $item_type->id() );
    $t->set_var( "item_name", $item_type->name() );
    $t->set_var( "prefix", $item_type->urlPrefix() );
    if ( $item_type->prefixLink() )
        $t->set_var( "prefix_link_checked", "checked" );
    else
        $t->set_var( "prefix_link_checked", "" );
    if ( $item_type->prefixVisual() )
        $t->set_var( "prefix_visual_checked", "checked" );
    else
        $t->set_var( "prefix_visual_checked", "" );
}

if( $Action == "edit" )
{
    $action_value = "update";

    if( is_numeric( $item_type->id() ) )
    {
        $item_error = false;
    }
}

if( $Action == "new" )
{
    $action_value = "insert";

    $item_error = false;
}

if( $item_error == true )
{
    $t->parse( "no_line_item", "no_line_item_tpl" );
}
else
{
    $t->parse( "line_item", "line_item_tpl" );
}

$t->parse( "type_edit", "type_edit_tpl", true );

$t->set_var( "form_path", $page_path );
$t->set_var( "action_value", $action_value );
$t->pparse( "output", "list_page" );

?>
