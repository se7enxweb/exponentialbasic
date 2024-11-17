<?php
//
// $Id: typelist.php 6204 2001-07-19 12:06:56Z jakobn $
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
  This code can be reused for simple type lists. It requires an object with the following functions:
  name(): Used for reading the name of the type.
  id(): Used for retrieving the id of the type in the database

  The object list must be initialized in the $item_type_array variable.
  Also these following variables must be set properly.
  $language_file: The file used for reading language translations, for example: consultationtype.php
  $page_path: The base name of the url, for example: /address/consultationtype
  You can also enable item placement by setting the $move_item variable to true, to make sure an
  item can be moved it must implement the moveUp() and moveDown() functions.
  If the $SortPage variable is set all items will have hyperlinked names linked to the variable content.
  If $Searchable is set to true a search button is added.
  If $template_array, $variable_array and $block_array is set they are used for extending the
  list with extra information.
*/

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAddressMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZAddressMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlist.php" );

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

if ( !isset( $typelist ) )
    $typelist = "typelist.tpl";

if ( isset( $template_array ) and isset( $variable_array ) and
     is_array( $template_array ) and is_array( $variable_array ) )
{
    $standard_array = array( "list_page" => $typelist );
    $t->set_file( array_merge( $standard_array, $template_array ) );
    $t->set_file_block( $template_array );
    if ( isset( $block_array ) and is_array( $block_array ) )
        $t->set_block( $block_array );
    $t->parse( $variable_array );
}
else
{
    $t->set_var( "extra_type_header", "" );
    $t->set_var( "extra_type_item", "" );
    $t->set_file( "list_page", $typelist );
}

$t->set_block( "list_page", "list_item_tpl", "list_item" );
$t->set_block( "list_item_tpl", "line_item_tpl", "line_item" );
$t->set_block( "list_page", "no_line_item_tpl", "no_line_item" );
$t->set_block( "list_page", "search_item_tpl", "search_item" );

$t->set_block( "line_item_tpl", "item_plain_tpl", "item_plain" );
$t->set_block( "line_item_tpl", "item_linked_tpl", "item_linked" );
$t->set_block( "line_item_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "line_item_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "line_item_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "line_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "line_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "line_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$t->set_var( "no_line_item", "" );    
$t->set_var( "line_item", "" );    
$t->set_var( "list_item", "" );    
$t->set_var( "search_item", "" );    

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

$t->set_var( "action", $Action );
$t->set_var( "type", $ListType );

$SearchText = stripslashes( $SearchText );
$t->set_var( "search_form_text", $SearchText );
$t->set_var( "search_text", $search_encoded );

if ( isset( $Searchable ) )
    $t->parse( "search_item", "search_item_tpl" );    

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

    if ( ( $i %2 ) == 0 )
        $t->set_var( "bg_color", "bglight" );
    else
        $t->set_var( "bg_color", "bgdark" );

    if ( isset( $func_call ) and is_array( $func_call ) )
    {
        reset( $func_call );
        while( list($key,$val) = each( $func_call ) )
        {
            $t->set_var( $key, $item->$val() );
        }
    }
    else
    {
        $t->set_var( "item_id", $item->id() );
        $t->set_var( "item_name", $item->name() );
    }

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

eZList::drawNavigator( $t, $total_types, $Max, $Index, "list_page" );

$t->pparse( "output", "list_page" );
?>
