<?php
//
// $Id: error.php 6970 2001-09-05 11:57:07Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

$ini =& $GlobalSiteIni;
$Language = $ini->variable( "eZContactMain", "Language" );
$DOC_ROOT = 'ezcontact';

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->variable( "eZContactMain", "AdminTemplateDir" ), $DOC_ROOT . "/admin/intl", $Language, "error.php" );
$t->setAllStrings();

$page_path = "/contact/error";
$item_error = true;

$t->set_file( array(
    "error_page" =>  "error.tpl",
    ) );
$t->set_block( "error_page", "uri_item_tpl", "uri_item" );

$t->set_var( "uri_item", "" );

if ( empty( $BackUrl ) )
{
    $back_command = "/";
}
else
{
    $back_command = $BackUrl;
}

// header( "Error $Type: " );

if ( !empty( $Uri ) )
{
    $t->set_var( "uri_data", "http://" . $_SERVER["HTTP_HOST"] . $Uri );
    $t->parse( "uri_item","uri_item_tpl" );
}

$t->pparse( "output", "error_page" );

?>