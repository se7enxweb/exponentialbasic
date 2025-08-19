<?php
// 
// $Id: vattypes.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <19-Feb-2001 13:34:10 bf>
//
// This source file is part of Exponential Basic, publishing software.
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


// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezvattype.php" );


if ( isset( $Action ) && $Action == "Store" )
{
    $i =0;
    foreach ( $VatID as $id )
    {
        $type = new eZVATType( $VatID[$i] );
        $type->setName( $VatName[$i] );
        $type->setValue( $VatValue[$i] );
        $type->store();
        $i++;
    }    
}

if ( isset( $Action ) && $Action == "Add" )
{
    $type = new eZVATType();
    $type->setName( "" );
    $type->setValue( 0 );
    $type->store();
}

if ( isset( $Action ) && $Action == "Delete" )
{
    foreach ( $VatArrayID as $id )
    {
        $type = new eZVATType( $id );
        $type->delete();
    }
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "vattypes.php" );

$t->setAllStrings();

$t->set_file( array( "vat_types_tpl" => "vattypes.tpl" ) );

$t->set_block( "vat_types_tpl", "vat_item_tpl", "vat_item" );


$type = new eZVATType();

$types =& $type->getAll();

$i=0;
foreach ( $types as $item )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->set_var( "vat_id", $item->id() );
    $t->set_var( "vat_name", $item->name() );
    $t->set_var( "vat_value", $item->value() );
    
    $t->parse( "vat_item", "vat_item_tpl", true );

    $i++;
}

if ( count ( $types ) == 0 )
    $t->set_var( "vat_item", "" );


$t->pparse( "output", "vat_types_tpl" );

?>