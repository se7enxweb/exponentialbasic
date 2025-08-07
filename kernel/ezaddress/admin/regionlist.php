<?php
//
// $Id: regionlist.php,v 1.1.2.1.4.1 2002/02/05 00:06:45 prana Exp $
//
// Created on: <2-Feb-2002 18:06:56 ghb>
//
// Copyright (C) 2002 Katalyst.  All rights reserved.
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


// include_once( "ezaddress/classes/ezregion.php" );

$ini =& eZINI::instance( 'site.ini' );
$Max = $ini->variable( "eZAddressMain", "MaxRegionList" );

include( "kernel/ezaddress/admin/typelist_pre.php" );

$language_file = "region.php";
$page_path = "/address/region";
$typelist = "typelist.tpl";
$template_array = array( "region_tpl" => "regionlist.tpl" );
$variable_array = array( "region_header" => "region_header_tpl",
                         "region_item" => "region_item_tpl",
                         "extra_type_header" => "region_header",
                         "extra_type_item" => "region_item" );
$block_array = array( array( "region_tpl", "region_header_tpl", "region_header" ),
                      array( "region_tpl", "region_item_tpl", "region_item" ) );

$item_type_array = eZRegion::getAll( true, $SearchText, $Index, $Max );

$total_types = eZRegion::getAllCount( $SearchText );
$func_call = array( "item_id" => 'id',
                    "item_name" => 'name',
                    "item_abbreviation" => 'Abbreviation' );
$Searchable = true;

include( "kernel/ezaddress/admin/typelist.php" );

?>