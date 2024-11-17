<?php
//
// $Id: onlinetypelist.php 6204 2001-07-19 12:06:56Z jakobn $
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
$page_path = "/address/onlinetype";

$item_type = new eZOnlineType();
$item_type_array = $item_type->getAll();
$move_item = true;

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAddressMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZAddressMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZAddressMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, $language_file );
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

$t->set_file( array(
    "list_page" =>  "onlinelist.tpl",
    ) );
$t->set_block( "list_page", "list_item_tpl", "list_item" );
$t->set_block( "list_item_tpl", "line_item_tpl", "line_item" );
$t->set_block( "list_page", "no_line_item_tpl", "no_line_item" );

$t->set_block( "line_item_tpl", "item_plain_tpl", "item_plain" );
$t->set_block( "line_item_tpl", "item_linked_tpl", "item_linked" );
$t->set_block( "line_item_tpl", "item_link_true_tpl", "item_link_true" );
$t->set_block( "line_item_tpl", "item_link_false_tpl", "item_link_false" );
$t->set_block( "line_item_tpl", "item_visual_true_tpl", "item_visual_true" );
$t->set_block( "line_item_tpl", "item_visual_false_tpl", "item_visual_false" );
$t->set_block( "line_item_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "line_item_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "line_item_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "line_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "line_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "line_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$t->set_var( "no_line_item", "" );    
$t->set_var( "line_item", "" );    
$t->set_var( "list_item", "" );    

$t->set_var( "item_up_command", "$page_path/up" );
$t->set_var( "item_down_command", "$page_path/down" );
$t->set_var( "item_edit_command", "$page_path/edit" );
$t->set_var( "item_delete_command", "$page_path/delete" );
$t->set_var( "item_view_command", "$page_path/view" );
$t->set_var( "item_list_command", "$page_path/list" );
$t->set_var( "item_new_command", "$page_path/new" );
$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );
$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

$count = count( $item_type_array );


$i = 0;
foreach( $item_type_array as $item )
{
    $t->set_var( "item_move_up", "" );
    $t->set_var( "no_item_move_up", "" );
    $t->set_var( "item_move_down", "" );
    $t->set_var( "no_item_move_down", "" );
    $t->set_var( "item_separator", "" );
    $t->set_var( "no_item_separator", "" );

    $t->set_var( "item_plain", "" );
    $t->set_var( "item_linked", "" );
    $t->set_var( "item_link_true", "" );
    $t->set_var( "item_link_false", "" );
    $t->set_var( "item_visual_true", "" );
    $t->set_var( "item_visual_false", "" );

    if ( ( $i %2 ) == 0 )
        $t->set_var( "bg_color", "bglight" );
    else
        $t->set_var( "bg_color", "bgdark" );

    $t->set_var( "item_id", $item->id() );
    $t->set_var( "item_name", $item->name() );
    $t->set_var( "item_prefix", $item->urlPrefix() );

    if ( $item->prefixLink() )
        $t->parse( "item_link_true", "item_link_true_tpl" );
    else
        $t->parse( "item_link_false", "item_link_false_tpl" );
    if ( $item->prefixVisual() )
        $t->parse( "item_visual_true", "item_visual_true_tpl" );
    else
        $t->parse( "item_visual_false", "item_visual_false_tpl" );

    if ( isset( $SortPage ) )
    {
        $t->set_var( "item_sort_command", $SortPage );
        $t->parse( "item_linked", "item_linked_tpl" );
    }
    else
    {
        $t->parse( "item_plain", "item_plain_tpl" );
    }

    if ( $i > 0 && isset( $move_item ) )
    {
        $t->parse( "item_move_up", "item_move_up_tpl" );
    }
    else
    {
        $t->parse( "no_item_move_up", "no_item_move_up_tpl" );
    }

    if ( $i > 0 && $i < $count - 1 && isset( $move_item ) )
    {
        $t->parse( "item_separator", "item_separator_tpl" );
    }
    else
    {
        $t->parse( "no_item_separator", "no_item_separator_tpl" );
    }

    if ( $i < $count - 1 && isset( $move_item ) )
    {
        $t->parse( "item_move_down", "item_move_down_tpl" );
    }
    else
    {
        $t->parse( "no_item_move_down", "no_item_move_down_tpl" );
    }

    $t->parse( "line_item", "line_item_tpl", true );

    $i++;
} 

if( $count < 1 )
{
    $t->parse( "no_line_item", "no_line_item_tpl" );
}
else
{
    $t->parse( "list_item", "list_item_tpl" );
}

$t->pparse( "output", "list_page" );

?>
