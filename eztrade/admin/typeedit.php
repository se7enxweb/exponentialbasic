<?php
// 
// $Id: typeedit.php 7163 2001-09-12 10:50:36Z br $
//
// Created on: <20-Dec-2000 18:24:06 bf>
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

// include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /trade/typelist/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$move_item = true;

// include_once( "eztrade/classes/ezproducttype.php" );
// include_once( "eztrade/classes/ezproductattribute.php" );

if ( $Action == "Insert" )
{
    $type = new eZProductType();
    $type->setName( $Name );
    $type->setDescription( $Description );

    $type->store();

    $TypeID = $type->id();
    $Action = "Edit";
}


if ( ( $Action == "Update" ) || ( isset ( $Update ) ) )
{
    $type = new eZProductType( $TypeID );
    $type->setName( $Name );
    $type->setDescription( $Description );

    $type->store();

    // update attributes
    $i =0;
    if ( count( $AttributeName ) > 0 )
    {

        foreach ( $AttributeName as $attribute )
        {
            $att = new eZProductAttribute( $AttributeID[$i] );
            $att->setName( $attribute );
            $att->setAttributeType( $AttributeType[$i] );
            $att->setUnit( $Unit[$i] );
            $att->store();            

            $i++;
        }
    }
    $Action="Edit";
}

if( $Action == "up" )
{
    $attribute = new eZProductAttribute( $AttributeID );
    $attribute->moveUp();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/typeedit/edit/$TypeID" );
    exit();
}

if( $Action == "down" )
{
    $attribute = new eZProductAttribute( $AttributeID );
    $attribute->moveDown();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/typeedit/edit/$TypeID" );
    exit();
}

if( isset( $Ok ) )
{
    eZHTTPTool::header( "Location: /trade/typelist/" );
    exit();
}

if ( isset ( $DeleteSelected ) )
{
    if ( count ( $DeleteAttributes ) > 0 )
    {
        foreach ( $DeleteAttributes as $attID )
        {
            $attribute = new eZProductAttribute( $attID );
            $attribute->delete();
        }
    }
    $Action = "Edit";
}


if ( isset( $NewAttribute ) )
{
    $attribute = new eZProductAttribute();
    $attribute->setType( $type );
    $attribute->setAttributeType( 1 );
    $attribute->setName( "New attribute" );
    $attribute->store();
}


if ( $Action == "Delete" )
{
    $type = new eZProductType();
    $type->get( $TypeID );

    $type->delete();
    
    eZHTTPTool::header( "Location: /trade/typelist/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "typeedit.php" );

$t->setAllStrings();

$t->set_file( array( "type_edit_tpl" => "typeedit.tpl" ) );


$t->set_block( "type_edit_tpl", "value_tpl", "value" );

$t->set_block( "type_edit_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "attribute_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "attribute_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "attribute_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "attribute_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "attribute_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "attribute_tpl", "no_item_move_down_tpl", "no_item_move_down" );


$type = new eZProductType();

$typeArray = $type->getAll( );

$t->set_var( "attribute_list", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "type_id", "" );
$t->set_var( "action_value", "Insert" );

// edit
if ( $Action == "Edit" )
{
    $type = new eZProductType();
    $type->get( $TypeID );

    $t->set_var( "name_value", $type->name() );
    $t->set_var( "description_value", $type->description() );
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
        $t->set_var( "attribute_unit", $attribute->unit( ) );

        $t->set_var( "is_1_selected", "" );
        $t->set_var( "is_2_selected", "" );
        
        if ( $attribute->attributeType() == 1 )
            $t->set_var( "is_1_selected", "checked" );
        elseif ( $attribute->attributeType() == 2 )
            $t->set_var( "is_2_selected", "checked" );
        
       
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
        $t->set_var( "attribute_list", "" );
    }
    
}


$t->pparse( "output", "type_edit_tpl" );

?>
