<?php
// 
// $Id: customerlist.php 7848 2001-10-15 11:32:18Z ce $
//
// Created on: <21-Sep-2001 16:06:44 bf>
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
// include_once( "classes/ezcurrency.php" );
// include_once( "classes/ezlist.php" );

// include_once( "eztrade/classes/ezorder.php" );
// include_once( "ezuser/classes/ezuser.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTradeMain", "Language" );

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->variable( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "customerlist.php" );

$t->setAllStrings();

$t->set_file( "customer_list_tpl", "customerlist.tpl" );

$t->set_block( "customer_list_tpl", "customer_item_list_tpl", "customer_item_list" );
$t->set_block( "customer_item_list_tpl", "customer_item_tpl", "customer_item" );

$t->set_var( "customer_item", "" );

if ( isset( $SearchText) && $SearchText != "" )
	{
		$SearchText = addslashes (trim ($SearchText) );
		$customers =& eZOrder::customers( $SearchText );
	}
else
$customers =& eZOrder::customers();

$i=0;
foreach ( $customers as $customer )
{
    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->set_var( "customer_id", $customer->id() );
    $t->set_var( "customer_first_name", $customer->firstName() );
    $t->set_var( "customer_last_name", $customer->lastName() );

    $i++;
    $t->parse( "customer_item", "customer_item_tpl", true );
}

if ( count( $customers ) > 0 )
    $t->parse( "customer_list", "customer_list_tpl" );
else
$t->set_var( "customer_list", "" );

$t->parse( "customer_item_list", "customer_item_list_tpl" );

$t->pparse( "output", "customer_list_tpl" );

?>
