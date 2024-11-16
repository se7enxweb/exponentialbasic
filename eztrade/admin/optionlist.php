<?php
// 
// $Id: optionlist.php 6233 2001-07-20 11:42:02Z jakobn $
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezoption.php" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "optionlist.php" );

$t->setAllStrings();

$t->set_file( array(
    "option_list_tpl" => "optionlist.tpl"
    ) );

$t->set_block( "option_list_tpl", "option_tpl", "option" );

$t->set_var( "site_style", $SiteStyle );

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );

$options = $product->options();

if ( !$options )
{
    $noitem = new INIFIle( "eztrade/admin/intl/" . $Language . "/optionlist.php.ini", false );
    $t->set_var( "option", $noitem->read_var( "strings", "no_option" ) );

}

$i=0;
foreach ( $options as $option )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );
    $t->set_var( "option_name", $option->name() );
    $t->set_var( "option_id", $option->id() );
    $t->set_var( "product_id", $ProductID );

    $t->parse( "option", "option_tpl", true );
    $i++;
}

$t->set_var( "product_id", $ProductID );

$t->pparse( "output", "option_list_tpl" );

?>
