<?php
// 
// $Id: optionedit.php 8119 2001-10-31 11:19:09Z ce $
//
// Created on: <20-Sep-2000 10:18:33 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezcachefile.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );
$StdHeaders = $ini->read_array( "eZTradeMain", "StandardOptionHeaders" );
$MinHeaders = $ini->read_var( "eZTradeMain", "MinimumOptionHeaders" );
$MinValues = $ini->read_var( "eZTradeMain", "MinimumOptionValues" );
$SimpleOptionHeaders = $ini->read_var( "eZTradeMain", "SimpleOptionHeaders" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );
include_once( "eztrade/classes/ezoptionvalue.php" );
include_once( "eztrade/classes/ezpricegroup.php" );

if ( !isset( $OptionPrice ) or !is_array( $OptionPrice ) )
    $OptionPrice = array();

if ( isset( $DeleteOption ) )
{
    foreach( $DeleteOptionID as $option_id )
    {
        $option = new eZOption( $option_id );
        $option->delete();
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, NULL ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    eZHTTPTool::header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();
}

$product = new eZProduct( $ProductID );

if ( isset( $Delete ) )
{
    if ( isset( $OptionDelete ) )
    {
        foreach( $OptionDelete as $del )
        {
            unset( $OptionValue[$del] );
            unset( $OptionValueID[$del] );
            $OptionValueID = array_values( $OptionValueID );
            unset( $OptionPrice[$del] );
            unset( $OptionMainPrice[$del] );
            unset( $OptionQuantity[$del] );
        }
    }
    if ( isset( $OptionDescriptionDelete ) )
    {
        foreach( $OptionDescriptionDelete as $del )
        {
            unset( $OptionValueDescription[$del] );
            $count = count( $OptionValue );
            for ( $i = 0; $i < $count; $i++ )
            {
                unset( $OptionValue[$i][$del] );
            }
            $ValueCount = max( $MinHeaders, $ValueCount - 1 );
        }
    }
}


if ( isset( $Abort ) )
{
    eZHTTPTool::header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();
}

if ( isset( $OK ) )
{
    $option = new eZOption( $OptionID );
    $option->setName( $OptionName );
    $option->setDescription( $Description );

    $option->store();

    if ( !is_numeric( $OptionID ) )
        $product->addOption( $option );

    $option->removeHeaders();
    $option->addHeader( $OptionValueDescription );

//      $option->removeValues();
    $i = 0;
    $option_ids = array();
    foreach ( $OptionValue as $name )
    {
        if ( $name != "" )
        {
            if ( is_numeric( $OptionValueID[$i] ) and $OptionValueID[$i] > 0 )
                $option_ids[] = $OptionValueID[$i];
        }
        $i++;
    }
    $orig_option_ids = eZOptionValue::getByOption( $option, false );
    $old_option_ids = array_diff( $orig_option_ids, $option_ids );
    foreach( $old_option_ids as $id )
    {
        eZOptionValue::delete( $id );
    }

    $i = 0;
    foreach ( $OptionValue as $name )
    {
        if ( $name != "" )
        {
            if ( is_numeric( $OptionValueID[$i] ) and $OptionValueID[$i] > 0 )
                $value = new eZOptionValue( $OptionValueID[$i] );
            else
                $value = new eZOptionValue();
            $value->setPrice( $OptionMainPrice[$i] );
            $value->setOptionID( $option->ID );
            $value->store();

            if ( $ShowQuantity )
            {
                $value->setTotalQuantity( is_numeric( $OptionQuantity[$i] ) ? $OptionQuantity[$i] : false );
            }

            $value->removeDescriptions();
            $value->addDescription( $name );
            $option_price = $OptionPrice[$i];

            eZPriceGroup::removePrices( $ProductID, $option->id(), $value->id() );

            if ( count( $option_price ) > 0 )
            {
                reset( $option_price );
                while( list($group,$price) = each( $option_price ) )
                {
                    if ( is_numeric( $price ) )
                    {
                        eZPriceGroup::addPrice( $ProductID, $group, $price, $option->id(), $value->id() );
                    }
                }
            }
        }
        $i++;
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, NULL ),
                                 "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

    eZHTTPTool::header( "Location: /trade/productedit/optionlist/$ProductID/" );
    exit();
}

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "optionedit.php" );

$t->setAllStrings();

$t->set_file( "option_edit_page", "optionedit.tpl" );

$t->set_block( "option_edit_page", "value_header_item_tpl", "value_header_item" );
$t->set_block( "option_edit_page", "group_item_tpl", "group_item" );
$t->set_block( "option_edit_page", "option_quantity_header_tpl", "option_quantity_header" );

$t->set_block( "option_edit_page", "value_headers_tpl", "value_headers" );

$t->set_block( "value_headers_tpl", "value_description_item_tpl", "value_description_item" );
$t->set_block( "value_description_item_tpl", "value_description_item_checkbox_tpl", "value_description_item_checkbox" );

$t->set_block( "option_edit_page", "option_item_tpl", "option_item" );
$t->set_block( "option_item_tpl", "value_item_tpl", "value_item" );
$t->set_block( "option_item_tpl", "option_price_item_tpl", "option_price_item" );
$t->set_block( "option_item_tpl", "option_quantity_item_tpl", "option_quantity_item" );

$t->set_block( "option_edit_page", "new_description_tpl", "new_description" );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "option_values", "" );
$t->set_var( "hidden_fields", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );

$t->set_var( "product_name", $product->name() );

$groups = eZPriceGroup::getAll( false );
$t->set_var( "group_item", "" );
foreach( $groups as $group )
{
    $price_group = new eZPriceGroup( $group );
    $t->set_var( "price_group_name", $price_group->name() );
    $t->parse( "group_item", "group_item_tpl", true );
}
$count = count ( $groups );

if ( $Action == "New" )
{
    $OptionValueDescription = $StdHeaders;
    $OptionValue = array();
    $OptionMainPrice = array();
    $OptionPrice = array();
    $NewValue = true;
}

if ( $Action == "Edit" )
{
    $option = new eZOption( $OptionID );
    $values = $option->values();

    $OptionValueDescription = $option->descriptionHeaders();
    $i = 0;
    foreach( $StdHeaders as $header )
    {
        if ( !isset( $OptionValueDescription[$i] ) )
            $OptionValueDescription[$i] = $header;
        $i++;
    }
    $hiddenArray = "";
    $valueText = "";
    $OptionValue = array();
    $OptionMainPrice = array();
    $OptionPrice = array();
    $OptionQuantity = array();
    $i = 0;
    foreach ( $values as $value )
    {
        $OptionValue[$i] = $value->descriptions();
        $OptionValue[$i][] = "";
        $OptionMainPrice[] = $value->price();
        $OptionValueID[$i] = $value->id();
        $OptionQuantity[$i] = $value->totalQuantity();
        $valueid = $value->id();
        $ValueID[] = $valueid;
        $prices = eZPriceGroup::prices( $ProductID, $OptionID, $value->id() );
        foreach( $groups as $group )
        {
            foreach( $prices as $price )
            {
                if ( $price["PriceID"] == $group )
                    $OptionPrice[$valueid][$group] = $price["Price"];
            }
        }
        $i++;
    }
    $ValueCount = max( $MinHeaders, count( $OptionValueDescription ) );
    if ( $SimpleOptionHeaders )
        $ValueCount = $MinHeaders;

    $OptionName = $option->name();
    $Description = $option->description();
}

if ( isset( $NewValue ) )
{
    $OptionValue[] = array();
    $OptionValueID[] = "";
    $ValueID[] = "";
    $option_price = array();
    for( $i = 0; $i < $count; ++$i )
    {
        $option_price[$groups[$i]] = "";
    }
    $OptionPrice[] = $option_price;
}

while( max( count( $OptionValue ), count( $ValueID ), count( $OptionPrice ) ) < $MinValues )
{
    $OptionValue[] = array();
    $OptionValueID[] = "";
    $ValueID[] = "";
    $option_price = array();
    for( $i = 0; $i < $count; ++$i )
    {
        $option_price[$groups[$i]] = "";
    }
    $OptionPrice[] = $option_price;
}

if ( isset( $NewDescription ) )
{
    for( $i = 0; $i < count( $OptionValue ); $i++ )
    {
        $OptionValue[$i][] = "";
    }
    $OptionValueDescription[] = "";
    $ValueCount = max( $MinHeaders, $ValueCount + 1 );
}

$value_count = max( $MinHeaders, $ValueCount );

$t->set_var( "option_quantity_header", "" );
if ( $ShowQuantity )
    $t->parse( "option_quantity_header", "option_quantity_header_tpl" );

$t->set_var( "value_count", $value_count );

$t->set_var( "value_headers", "" );
if ( !$SimpleOptionHeaders )
{
    reset( $OptionValueDescription );
    $value_header_item = each( $OptionValueDescription );
    $t->parse( "value_description_item_checkbox", "value_description_item_checkbox_tpl" );
    for ( $i = 0; $i < max( $MinHeaders, $value_count ); $i++ )
    {
        $t->set_var( "option_description_value", $value_header_item[1] );
        $t->set_var( "value_description_index", $i );
        $t->parse( "value_description_item", "value_description_item_tpl", true );
        $value_header_item = each( $OptionValueDescription );
    }

    $t->parse( "value_headers", "value_headers_tpl" );
}

reset( $OptionPrice );
$index = 0;
$j = 0;
$t->set_var( "option_item", "" );
$t->set_var( "group_count", $count );
$main_price = each( $OptionMainPrice );
foreach ( $OptionValue as $value )
{
    $t->set_var( "value_pos", $index + 1 );
    $t->set_var( "value_index", $index );
    $t->set_var( "number_run", $j );
    $t->set_var( "option_value_id", $OptionValueID[$index] );
    $t->set_var( "value_item", "" );
    reset( $value );
    $value_item = each( $value );
    for( $i = 0; $i < max( $MinHeaders, $value_count ); $i++ )
    {
        $t->set_var( "option_value", $value_item[1] );
        $t->parse( "value_item", "value_item_tpl", true );
        $value_item = each( $value );
    }
    $t->set_var( "main_price_value", $main_price[1] );

    $t->set_var( "option_price_item", "" );
    $option_price = each( $OptionPrice );
    $i = 0;
    foreach( $groups as $group )
    {
        $t->set_var( "price_value", $option_price[1][$group] );
        $t->set_var( "price_group", $group );
        $t->set_var( "value_index", $i );
        $t->parse( "option_price_item", "option_price_item_tpl", true );
        $i++;
    }

    $t->set_var( "option_quantity_item", "" );
    if ( $ShowQuantity )
    {
        $t->set_var( "quantity_value", $OptionQuantity[$index] );
        $t->parse( "option_quantity_item", "option_quantity_item_tpl" );
    }

    $t->parse( "option_item", "option_item_tpl", true );
    $main_price = each( $OptionMainPrice );
    $index++;
    $j++;
}

$t->set_var( "option_id", $OptionID );
$t->set_var( "name_value", $OptionName );
$t->set_var( "description_value", $Description );

$t->set_var( "new_description", "" );
if ( !$SimpleOptionHeaders )
    $t->parse( "new_description", "new_description_tpl" );

$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "option_edit_page" );

?>
