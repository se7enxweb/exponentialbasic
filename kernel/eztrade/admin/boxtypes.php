<?php
// 
// $Id: boxtypes.php,v 1.4 2001/07/20 11:42:01 jakobn Exp $
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

// include_once( "eztrade/classes/ezboxtype.php" );


if ( isset( $Action ) && $Action == "Store" )
{
    $i =0;
    foreach ( $BoxID as $id )
    {
        $type = new eZBoxType( $BoxID[$i] );
        $type->setName( $BoxName[$i] );
        $type->setLength( $Length[$i] );
		$type->setHeight( $Height[$i] );
		$type->setWidth( $Width[$i] );
        $type->store();
        $i++;
    }    
}

if ( isset( $Action ) && $Action == "Add" )
{
    $type = new eZBoxType();
    $type->setName( "" );
    $type->setLength( 0 );
	$type->setHeight( 0 );
	$type->setWidth( 0 );
    $type->store();
}

if ( isset( $Action ) && $Action == "Delete" )
{
    foreach ( $BoxArrayID as $id )
    {
        $type = new eZBoxType( $id );
        $type->delete();
    }
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "boxtypes.php" );

$t->setAllStrings();

$t->set_file( array( "box_types_tpl" => "boxtypes.tpl" ) );

$t->set_block( "box_types_tpl", "box_item_tpl", "box_item" );


$type = new eZBoxType();

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
    
    $t->set_var( "box_id", $item->id() );
    $t->set_var( "box_name", $item->name() );
    $t->set_var( "length", $item->length() );
    $t->set_var( "width", $item->width() );
    $t->set_var( "height", $item->height() );
    
    $t->parse( "box_item", "box_item_tpl", true );

    $i++;
}

if ( count ( $types ) == 0 )
    $t->set_var( "box_item", "" );

$t->pparse( "output", "box_types_tpl" );

?>