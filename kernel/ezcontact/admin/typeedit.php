<?php
//
// $Id: typeedit.php 6927 2001-09-04 12:06:17Z jhe $
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
  This code can be reused for simple type edits. It requires an object with the following functions:
  name() and setName(): Used for reading and setting the name of the type.
  id(): Used for retrieving the id of the type in the database
  count(): Used for calculating the number of external items dependent on this type

  The object must be initialized in the $item_type variable.
  Also these following variables must be set properly.
  $language_file: The file used for reading language translations, for example: consultationtype.php
  $page_path: The base name of the url, for example: /contact/consultationtype
*/

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

// include_once( "classes/eztemplate.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, $language_file );
$t->setAllStrings();

$item_error = true;

if ( empty( $HTTP_REFERER ) )
{
    if ( empty( $BackUrl ) )
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

if ( $Action == "delete" )
{
    // Check to see if the count has changed since the confirmation was done
    $item_id = $item_type->id();
    $reconfirm = "Location: $page_path/confirm/$item_id";
    $count = $item_type->count();
    if ( !isSet( $TypeCount ) )
    {
        $Action = "confirm";
        $TypeError = true;
    }
    else if ( $count != $TypeCount )
    {
        $Action = "confirm";
    }
    else
    {
        // The counts are the same as when confirming so we can delete

        $item_type->delete( true );
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: $page_path/list" );
        exit();
    }
}

if ( $Action == "up" )
{
    $item_type->moveUp();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if ( $Action == "down" )
{
    $item_type->moveDown();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if ( $Action == "insert" or $Action == "update" )
{
    if ( $Action == "insert" )
        unset( $item_type->ID );

    if ( isSet( $func_call_set ) and is_array( $func_call_set ) )
    {
        reset( $func_call_set );
        while( list( $key, $val ) = each( $func_call_set ) )
        {
            $item_type->$key( ${$val} );
        }
    }
    else
    {
        $item_type->setName( $ItemName );
    }
    $item_type->store();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
}

if ( !isSet( $typeedit ) )
    $typeedit = "typeedit.tpl";
if ( isSet( $template_array ) and isSet( $block_array ) and
     is_array( $template_array ) and is_array( $block_array ) )
{
    $standard_array = array( "list_page" => $typeedit );
    $t->set_file( array_merge( $standard_array, $template_array ) );
    $t->set_file_block( $template_array );
    $t->parse( $block_array );
}
else
{
    $t->set_var( "extra_type_input", "" );
    $t->set_file( "list_page", $typeedit );
}
$t->set_block( "list_page", "type_edit_tpl", "type_edit" );
$t->set_block( "list_page", "type_confirm_tpl", "type_confirm" );

$t->set_block( "type_edit_tpl", "line_item_tpl", "line_item" );
$t->set_block( "type_edit_tpl", "no_line_item_tpl", "no_line_item" );

$t->set_block( "type_confirm_tpl", "errors_tpl", "errors" );
$t->set_block( "errors_tpl", "error_count_change_item_tpl", "error_count_change_item" );
$t->set_block( "errors_tpl", "error_no_confirm_item_tpl", "error_no_confirm_item" );

$t->set_var( "no_line_item", "" );
$t->set_var( "line_item", "" );

$t->set_var( "item_edit_command", "$page_path/edit" );
$t->set_var( "item_delete_command", "$page_path/delete" );
$t->set_var( "item_view_command", "$page_path/view" );
$t->set_var( "item_list_command", "$page_path/list" );
$t->set_var( "item_new_command", "$page_path/new" );

$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );

$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

if ( $Action == "confirm" )
{
    $t->set_var( "error_count_change_item", "" );
    $t->set_var( "error_no_confirm_item", "" );

    if ( isSet( $TypeCount ) )
    {
        if ( $TypeCount != $item_type->count() )
        {
            $t->parse( "error_count_change_item", "error_count_change_item_tpl" );
            $error = true;
        }
    }
    if ( isSet( $TypeError ) )
    {
        $t->parse( "error_no_confirm_item", "error_no_confirm_item_tpl" );
        $error = true;
    }
        
    if ( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
    }
}

if ( $error == false )
{
    $t->set_var( "errors", "" );
}

if ( isSet( $func_call ) and is_array( $func_call ) )
{
    reset( $func_call );
    while( list( $key,$val ) = each( $func_call ) )
    {
        $t->set_var( $key, $item_type->$val() );
    }
}
else
{
    if ( is_numeric( $item_type->id() ) )
    {
        $t->set_var( "item_id", $item_type->id() );
        $t->set_var( "item_name", $item_type->name() );
    }
}

if ( $Action == "edit" )
{
    $action_value = "update";

    if ( is_numeric( $item_type->id() ) )
    {
        $item_error = false;
    }
}

if ( $Action == "confirm" )
{
    $action_value = "delete";
    $t->set_var( "confirm_item", $item_type->name() );
    $t->set_var( "item_count", $item_type->count() );
}

if ( $Action == "new" )
{
    $action_value = "insert";
    $item_error = false;
}

if ( $item_error == true )
{
    $t->parse( "no_line_item", "no_line_item_tpl" );
}
else
{
    $t->parse( "line_item", "line_item_tpl" );
}

if ( $Action == "confirm" )
{
    $t->set_var( "type_edit", "" );
    $t->parse( "type_confirm", "type_confirm_tpl", true );
}
else
{
    $t->parse( "type_edit", "type_edit_tpl", true );
    $t->set_var( "type_confirm", "" );
}

$t->set_var( "form_path", $page_path );
$t->set_var( "action_value", $action_value );
$t->pparse( "output", "list_page" );

?>
