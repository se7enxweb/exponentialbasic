<?php
// 
// $Id: typeedit.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <29-Jan-2001 11:44:23 jhe>
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

include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /link/typelist/" );
    exit();
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZLinkMain", "Language" );
$move_item = true;

include_once( "ezlink/classes/ezlinktype.php" );
include_once( "ezlink/classes/ezlinkattribute.php" );

if (isset( $Action ) && $Action == "Insert" )
{
    $type = new eZLinkType();
    $type->setName( $Name );

    $type->store();

    $TypeID = $type->id();
    $Action = "Edit";
}


if ( ( isset( $Action ) && $Action == "Update" ) || ( isset ( $Update ) ) )
{
    $type = new eZLinkType( $TypeID );
    $type->setName( $Name );

    $type->store();

    // update attributes
    $i =0;
    if ( count( $AttributeName ) > 0 )
    {

        foreach ( $AttributeName as $attribute )
        {
            $att = new eZLinkAttribute( $AttributeID[$i] );
            $att->setName( $attribute );
            $att->store();

            $i++;
        }
    }
    $Action = "Edit";
}

if( isset( $Action ) && $Action == "up" )
{
    $attribute = new eZLinkAttribute( $AttributeID );
    $attribute->moveUp();
    eZHTTPTool::header( "Location: /link/typeedit/edit/$TypeID" );
    exit();
}

if( isset( $Action ) && $Action == "down" )
{
    $attribute = new eZLinkAttribute( $AttributeID );
    $attribute->moveDown();
    eZHTTPTool::header( "Location: /link/typeedit/edit/$TypeID" );
    exit();
}

if( isset( $Ok ) )
{
    eZHTTPTool::header( "Location: /link/typelist/" );
    exit();
}

if ( isset ( $DeleteSelected ) )
{
    if ( count ( $DeleteAttributes ) > 0 )
    {
        foreach ( $DeleteAttributes as $attID )
        {
            $attribute = new eZLinkAttribute( $attID );
            $attribute->delete();
        }
    }
    $Action = "Edit";
}


if ( isset( $NewAttribute ) )
{
    $attribute = new eZLinkAttribute();
    $attribute->setType( $type );
    $attribute->setName( "New attribute" );
    $attribute->store();
}


if ( isset( $Action ) && $Action == "Delete" )
{
    $type = new eZProductType();
    $type->get( $TypeID );

    $type->delete();
    
    eZHTTPTool::header( "Location: /link/typelist/" );
    exit();
}

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "ezlink/admin/intl/", $Language, "typeedit.php" );

$t->setAllStrings();

$t->set_file( "type_edit_tpl", "typeedit.tpl" );


$t->set_block( "type_edit_tpl", "value_tpl", "value" );

$t->set_block( "type_edit_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "attribute_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "attribute_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "attribute_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "attribute_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "attribute_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "attribute_tpl", "no_item_move_down_tpl", "no_item_move_down" );


$type = new eZLinkType();

$typeArray = $type->getAll( );

$t->set_var( "attribute_list", "" );
$t->set_var( "name_value", "" );
$t->set_var( "type_id", "" );
$t->set_var( "action_value", "Insert" );

// edit
if ( isset( $Action ) && $Action == "Edit" )
{
    $type = new eZLinkType();
    $type->get( $TypeID );

    $t->set_var( "name_value", $type->name() );
    $t->set_var( "action_value", "Update" );
    $t->set_var( "type_id", $type->id() );

    $attributes = $type->attributes();

    $count = count ( $attributes );
    $i = 0;
    foreach ( $attributes as $attribute )
    {
        $t->set_var( "item_move_up", "" );
        $t->set_var( "no_item_move_up", "" );
        $t->set_var( "item_move_down", "" );
        $t->set_var( "no_item_move_down", "" );
        $t->set_var( "item_separator", "" );
        $t->set_var( "no_item_separator", "" );

        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );

        $t->set_var( "is_1_selected", "" );
        $t->set_var( "is_2_selected", "" );
        
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
        
		if ( ( $i % 2 ) == 0 )
	    {
	        $t->set_var( "td_class", "bglight" );
	    }
	    else
	    {
	        $t->set_var( "td_class", "bgdark" );
	    }
	    $t->set_var( "counter", $i );
        $t->parse( "attribute", "attribute_tpl", true );
        $i++;
    }

    if ( count( $attributes ) > 0 )
    {
        $t->parse( "attribute_list", "attribute_list_tpl", true );
    }
    else
    {
        $t->set_var( "attribute_list", "", true );
    }
    
}


$t->pparse( "output", "type_edit_tpl" );

?>