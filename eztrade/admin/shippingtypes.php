<?php
//
// $Id: shippingtypes.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <22-Feb-2001 11:38:37 bf>
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


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezshippingtype.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

include_once( "eztrade/classes/ezvattype.php" );


if ( $Action == "Store" )
{
    if ( is_numeric( $DefaultTypeID ) )
    {
        $type = new eZShippingType( $DefaultTypeID );
        $type->setAsDefault();
    }


    if ( is_array( $TypeID ) )
    {
        $i = 0;
        foreach ( $TypeID as $id )
        {
            $vatType = new eZVATType( $VATTypeID[$i]  );

            $shippingType = new eZShippingType( $id );
            $shippingType->setName( $TypeName[$i]  );
            $shippingType->setVATType( $vatType  );
            $shippingType->store();
            $i++;
        }
    }


    if ( is_array( $GroupID ) )
    {
        $i = 0;
        foreach ( $GroupID as $id )
        {
            $shippingGroup = new eZShippingGroup( $id );
            $shippingGroup->setName( $GroupName[$i]  );
            $shippingGroup->store();
            $i++;
        }
    }

    if ( is_array( $ValueGroupID ) )
    {
        $i = 0;
        foreach ( $ValueGroupID as $groupID )
        {
            $shippingType = new eZShippingType( $ValueTypeID[$i] );
            $shippingGroup = new eZShippingGroup( $groupID );
            $shippingGroup->setStartAddValue( $shippingType, $StartValue[$i], $AddValue[$i] );
            $i++;
        }
    }
}

if ( $Action == "AddType" )
{
    $shippingType = new eZShippingType();
    $shippingType->setName( "" );
    $shippingType->store();
}

if ( $Action == "AddGroup" )
{
    $shippingType = new eZShippingGroup();
    $shippingType->setName( "" );
    $shippingType->store();
}


if ( $Action == "DeleteSelected" )
{
    if ( count( $DeleteType ) > 0 )
    foreach ( $DeleteType as $id )
    {
        $shippingType = new eZShippingType( $id );
        $shippingType->delete();
    }

    if ( count( $DeleteGroup ) > 0 )
    foreach ( $DeleteGroup as $id )
    {
        $shippingGroup = new eZShippingGroup( $id );
        $shippingGroup->delete();
    }
}


$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "shippingtypes.php" );

$t->setAllStrings();

$t->set_file( array( "shipping_types_tpl" => "shippingtypes.tpl" ) );

$t->set_block( "shipping_types_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "vat_item_tpl", "vat_item" );
$t->set_block( "shipping_types_tpl", "group_item_tpl", "group_item" );
$t->set_block( "shipping_types_tpl", "header_item_tpl", "header_item" );
$t->set_block( "group_item_tpl", "type_group_item_tpl", "type_group_item" );


$shippingGroup = new eZShippingGroup();
$groups =& $shippingGroup->getAll();

$shippingType = new eZShippingType();
$shippingTypes =& $shippingType->getAll();


$t->set_var( "type_item", "" );
$t->set_var( "header_item", "" );

// set the header
foreach ( $shippingTypes as $type )
{
    $t->set_var( "shipping_type_name", $type->name() );
    $t->set_var( "type_id", $type->id() );
    if ( $type->isDefault() )
        $t->set_var( "default_checked", "checked" );
    else
        $t->set_var( "default_checked", "" );


    $currentVATType =& $type->vatType();

    $vatType = new eZVATType();

    $types =& $vatType->getAll();

    $i=0;
    $t->set_var( "vat_item", "" );
    foreach ( $types as $item )
    {
        if ( is_a( $currentVATType, "eZVATType" ) )
        {
            if ( $currentVATType->id() == $item->id() )
            {
                $t->set_var( "vat_selected", "selected" );
            }
            else
            {
                $t->set_var( "vat_selected", "" );
            }
        }
        else
        {
            $t->set_var( "vat_selected", "" );
        }
        $t->set_var( "vat_id", $item->id() );
        $t->set_var( "vat_name", $item->name() );
        $t->set_var( "vat_value", $item->value() );

        $t->parse( "vat_item", "vat_item_tpl", true );

        $i++;
    }

    $t->parse( "type_item", "type_item_tpl", true );
    $t->parse( "header_item", "header_item_tpl", true );
}

$t->set_var( "group_item", "" );

$i=0;
foreach ( $groups as $group )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "group_id", $group->id() );
    $t->set_var( "shipping_group_name", $group->name() );

    $t->set_var( "type_group_item", "" );
    foreach ( $shippingTypes as $type )
    {
        $values =& $group->startAddValue( $type );

        $t->set_var( "value_group_id", $group->id() );
        $t->set_var( "value_type_id", $type->id() );


        $t->set_var( "start_value", $values["StartValue"] );
        $t->set_var( "add_value", $values["AddValue"] );

        $t->parse( "type_group_item", "type_group_item_tpl", true );
    }

    $t->parse( "group_item", "group_item_tpl", true );
    $i++;
}

$t->pparse( "output", "shipping_types_tpl" );

?>
