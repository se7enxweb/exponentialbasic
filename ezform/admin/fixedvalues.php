<?php
// 
// $Id: fixedvalues.php 7715 2001-10-09 09:04:27Z ce $
//
// Created on: <12-Jun-2001 13:07:24 pkej>
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
include_once( "classes/ezlist.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformelementfixedvalue.php" );

$ActionValue = "list";
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

$element = new eZFormElement( $ElementID );

if( isset( $AddValue ) )
{
    $value = new eZFormElementFixedValue();
    $value->store();
    $element->addValue( $value );
}


if( isset( $Store ) || isset ( $AddValue ) || isset ( $DeleteSelected ) || isset ( $OK ) )
{
    $i=0;
    if ( count ( $ValueID ) > 0 )
    {
        foreach( $ValueID as $ID )
        {
            $value = new eZFormElementFixedValue( $ID );
            $value->setValue( $Value[$i] );
            $value->store();
            $i++;
        }
    }
}

if( isset( $DeleteSelected ) )
{
    foreach( $ValueDeleteID as $ID )
    {
        $value = new eZFormElementFixedValue( $ID );
        $value->delete();
    }
}

if( isset( $OK ) )
{
    eZHTTPTool::header( "Location: /form/form/edit/$FormID" );
    exit();
}

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "fixedvalues.php" );
$t->setAllStrings();

$t->set_file( array(
    "value_list_page_tpl" => "fixedvalues.tpl"
    ) );

$t->set_block( "value_list_page_tpl", "no_values_item_tpl", "no_values_item" );
$t->set_block( "value_list_page_tpl", "value_list_tpl", "value_list" );
$t->set_block( "value_list_tpl", "value_item_tpl", "value_item" );

$t->set_var( "value_item", "" );
$t->set_var( "value_list", "" );
$t->set_var( "no_values_item", "" );

$t->set_var( "element_name", $element->name() );
$t->set_var( "element_id", $element->id() );

$t->set_var( "form_id", $FormID );

$values =& $element->fixedValues();

if( count( $values ) == 0 )
{
    $t->parse( "no_values_item", "no_values_item_tpl" );
}
else
{
    $i = 0;
    foreach( $values as $value )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->set_var( "value_id", $value->id() );
        $t->set_var( "value", $value->value() );

        $t->parse( "value_item", "value_item_tpl", true );
        
        $i++;
    }
    
    
    $t->parse( "value_list", "value_list_tpl" );
}

$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "value_list_page_tpl" );

?>
