<?php
// 
// $Id: typeedit.php 6206 2001-07-19 12:19:22Z jakobn $
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
    eZHTTPTool::header( "Location: /article/type/list/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );
$move_item = true;

// include_once( "ezarticle/classes/ezarticletype.php" );
// include_once( "ezarticle/classes/ezarticleattribute.php" );

if( isset( $Ok ) || isset( $NewAttribute ) )
{
    if( is_numeric( $TypeID ) )
    {
        $type = new eZArticleType( $TypeID );
    }
    else
    {
        $type = new eZArticleType();
    }

    $type->setName( htmlspecialchars( $Name ) );
    $type->store();

    $TypeID = $type->id();

    // update attributes
    $i =0;
    if ( isset( $AttributeName ) && count( $AttributeName ) > 0 )
    {

        foreach ( $AttributeName as $attribute )
        {
            $att = new eZArticleAttribute( $AttributeID[$i] );
            $att->setName( htmlspecialchars( $attribute ) );
            $att->setType( $type );
            $att->store();            

            $i++;
        }
    }
    
    $Action = "edit";
    $ActionValue = "update";
}

if ( isset( $NewAttribute ) )
{
    $attribute = new eZArticleAttribute();
    $attribute->setType( $type );
    $attribute->setName( "New attribute" );
    $attribute->store();
    $ActionValue = "update";
    $Action = "edit";
}


if( isset( $Action ) && $Action == "up" && isset( $AttributeID ) )
{
    $attribute = new eZArticleAttribute( $AttributeID );
    $attribute->moveUp();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /article/type/edit/$TypeID/?$Action=update" );
    exit();
}

if( isset( $Action ) && $Action == "down" && isset( $AttributeID ) )
{
    $attribute = new eZArticleAttribute( $AttributeID );
    $attribute->moveDown();
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /article/type/edit/$TypeID/?$Action=update" );
    exit();
}

if( isset( $Ok ) )
{
    eZHTTPTool::header( "Location: /article/type/list/" );
    exit();
}

if ( isset ( $DeleteSelected ) )
{
    if ( count ( $DeleteAttributes ) > 0 )
    {
        foreach ( $DeleteAttributes as $attID )
        {
            $attribute = new eZArticleAttribute( $attID );
            $attribute->delete();
        }
    }
    $Action = "edit";
    $ActionValue = "update";
}


if ( $Action == "delete" )
{
    $type = new eZArticleType();
    $type->get( $TypeID );

    $type->delete();
    
    eZHTTPTool::header( "Location: /article/type/list/" );
    exit();
}

$t = new eZTemplate( "kernel/ezarticle/admin/" . $ini->variable( "eZArticleMain", "AdminTemplateDir" ),
                     "kernel/ezarticle/admin/intl/", $Language, "typeedit.php" );

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


$type = new eZArticleType();

$typeArray = $type->getAll( );

$t->set_var( "attribute_list", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "type_id", "" );

if( !isset( $ActionValue ) )
{
    $ActionValue = "insert";
}
// edit
if ( $Action == "edit" )
{
    $type = new eZArticleType();
    $type->get( $TypeID );

    $t->set_var( "name_value", $type->name() );
    
    $t->set_var( "action_value", $ActionValue );
    $t->set_var( "type_id", $TypeID );


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

       
        if ( isset( $move_item ) )
        {
            $t->parse( "item_move_up", "item_move_up_tpl" );
        }
        
        if ( isset( $move_item ) )
        {
            $t->parse( "item_separator", "item_separator_tpl" );
        }
        
        if ( isset( $move_item ) )
        {
            $t->parse( "item_move_down", "item_move_down_tpl" );
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