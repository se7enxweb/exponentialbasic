<?php
// 
// $Id: attributeedit.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <20-Dec-2000 18:45:08 bf>
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
    eZHTTPTool::header( "Location: /trade/productedit/edit/$ProductID/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezcurrency.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezproduct.php" );

// include_once( "eztrade/classes/ezproducttype.php" );
// include_once( "eztrade/classes/ezproductattribute.php" );


if ( $Action == "Update" )
{
    $product = new eZProduct( $ProductID );
    
//      print( "update<br>" );
    
//      print( $TypeID );

    if ( $TypeID == -1 )
    {
        $product->removeType();
    }
    else
    {
        $product->setType( new eZProductType( $TypeID ) );

        $i =0;
        if ( count( $AttributeValue ) > 0 )
        {
            foreach ( $AttributeValue as $attribute )
            {
                $att = new eZProductAttribute( $AttributeID[$i] );
                
                $att->setValue( $product, $attribute );

                $i++;
            }
        }
    }
    
    if ( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /trade/productedit/edit/$ProductID/" );
        exit();
    }
}

$product = new eZProduct( $ProductID );

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "attributeedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "attribute_edit_page" => "attributeedit.tpl"
    ) );

$t->set_block( "attribute_edit_page", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_tpl", "attribute" );

$t->set_block( "attribute_edit_page", "type_tpl", "type" );


//default values
    
if ( $Action == "Edit" )
{    
    
}

$type = new eZProductType( );
$types = $type->getAll();

$type = $product->type();


foreach ( $types as $typeItem )
{
    if ( $type )
    {
        if ( $type->id() == $typeItem->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
    }
    
    $t->set_var( "type_id", $typeItem->id( ) );
    $t->set_var( "type_name", $typeItem->name( ) );
    
    $t->parse( "type", "type_tpl", true );
}


if ( $type )    
{
    $attributes = $type->attributes();

    foreach ( $attributes as $attribute )
    {
        $t->set_var( "attribute_id", $attribute->id( ) );
        $t->set_var( "attribute_name", $attribute->name( ) );
        $t->set_var( "attribute_value", $attribute->value( $product ) );
        
        $t->parse( "attribute", "attribute_tpl", true );
    }
}

if ( count ( $attributes ) > 0 )
{
    $t->parse( "attribute_list", "attribute_list_tpl" );
}
else
{
    $t->set_var( "attribute_list", "" );
}

$t->set_var( "product_name", $product->name( ) );
$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "attribute_edit_page" );

?>