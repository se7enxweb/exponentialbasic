<?php
//
// $Id: countryedit.php 7938 2001-10-17 12:19:26Z ce $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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


include_once( "ezaddress/classes/ezcountry.php" );

$language_file = "country.php";
$item_type = new eZCountry( $CountryID );
if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZCountry( $item_id );
    }
}

$page_path = "/address/country";
$typeedit = "typeedit.tpl";
$template_array = array( "country_tpl" => "countryedit.tpl" );
$block_array = array( "extra_type_input" => "country_tpl" );

$func_call = array( "item_id" => "id",
                    "item_name" => "name",
                    "item_has_vat" => "hasVAT",
                    "item_iso" => "iso" );

$func_call_set = array( "setName" => "ItemName",
                        "setHasVAT" => "ItemHasVAT",
                        "setISO" => "ItemISO" );

include( "ezaddress/admin/typeedit.php" );

?>
