<?php
//
// $Id: regionedit.php,v 1.1.2.1.4.1 2002/02/05 00:06:45 prana Exp $
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

						//print_r($_POST); exit();
												
$language_file = "region.php";
$item_type = new eZRegion( $RegionID );
if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZRegion( $item_id );
    }
}

$page_path = "/address/region";
$typeedit = "typeedit.tpl";
$template_array = array( "region_tpl" => "regionedit.tpl" );
$block_array = array( "extra_type_input" => "region_tpl" );

$func_call = array( "item_id" => "id",
                    "item_name" => "name",
                    "item_has_vat" => "hasVAT",
                    "item_abbreviation" => "Abbreviation" );

$func_call_set = array( "setName" => "ItemName",
                        "setHasVAT" => "ItemHasTax",
                        "setAbbreviation" => "ItemAbbreviation" );

include( "ezaddress/admin/typeedit.php" );

?>
