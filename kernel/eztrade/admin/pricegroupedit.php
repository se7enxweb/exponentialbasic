<?php
// 
// $Id: pricegroupedit.php 9167 2002-02-07 08:39:29Z jhe $
//
// Created on: <23-Feb-2001 15:32:27 amos>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztexttool.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );

// include_once( "eztrade/classes/ezpricegroup.php" );

$price = new eZPriceGroup( $PriceID );

if ( isset( $Cancel ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/pricegroups/list" );
    exit();
}

if ( isset( $OK ) )
{
    $price->setName( $Name );
    $price->setDescription( $Description );
    $price->store();

    $price->removeUserGroups();
    foreach ( $GroupID as $group )
    {
        $price->addUserGroup( $group );
    }
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /trade/pricegroups/list" );
    exit();
}
else if ( isset( $PriceID ) and is_numeric( $PriceID ) )
{
    $Action = "edit";
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "pricegroupedit.php" );

$t->setAllStrings();

$t->set_file( "price_group_page", "pricegroupedit.tpl" );

$t->set_block( "price_group_page", "value_tpl", "value_item" );

if ( $Action == "edit" )
{
    $Name = $price->name();
    $Description = $price->description();
    $GroupID = $price->userGroups( false, false );
}

$t->set_var( "price_id", $PriceID );
$t->set_var( "name", eZTextTool::htmlspecialchars( $Name ) );
$t->set_var( "description", eZTextTool::htmlspecialchars( $Description ) );

if ( !isset( $GroupID ) )
    $GroupID = array();

$groups = eZUserGroup::getAll();
foreach ( $groups as $group )
{
    $t->set_var( "group_id", $group->id() );
    $t->set_var( "group_name", eZTextTool::htmlspecialchars( $group->name() ) );
    $t->set_var( "selected", in_array( $group->id(), $GroupID ) ? "selected" : "" );
    $t->parse( "value_item", "value_tpl", true );
}

$t->pparse( "output", "price_group_page" );

?>
