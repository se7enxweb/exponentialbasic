<?php
// 
// $Id: separator.php 6316 2001-07-30 13:22:37Z bf $
//
// Created on: <23-Jan-2001 16:06:07 bf>
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

include_once( "ezsession/classes/ezpreferences.php" );
$single_module = $preferences->variable( "SingleModule" ) == "enabled";

include_once( "ezmodule/classes/ezmodulehandler.php" );

include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZ" . ucfirst( $moduleName ) . "Main", "Language" );

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "design/admin/templates/" . $SiteDesign,
                     "ez" . $moduleName . "/admin/intl/", $Language, "menubox.php" );


$t->set_file( array(
    "separator_tpl" => "separator.tpl"
    ) );

$t->set_block( "separator_tpl", "left_spacer_tpl", "left_spacer_item" );
$t->set_block( "separator_tpl", "top_field_tpl", "top_field_item" );
$t->set_block( "top_field_tpl", "help_tpl", "help" );

$t->set_var( "site_style", $SiteDesign );

$t->set_var( "module_name", $moduleName );

$t->set_var( "current_url", $REQUEST_URI );

// check for help file
$helpFile = "ez" . $moduleName . "/admin/help/". $Language . "/" . $url_array[1] . "_" . $url_array[2] . ".hlp";

$t->set_var( "help", "" );

if ( eZFile::file_exists( $helpFile ) )
{
    $t->set_var( "help_url", "/help/" . $moduleName . "/" . $url_array[1] . "/" . $url_array[2]. "/" );
    $t->parse( "help", "help_tpl" );
}

$t->setAllStrings();

$t->set_var( "module_count", count ( $modules ) );
$t->set_var( "left_spacer_item", "" );

$moduletab = $ini->read_var( "site", "ModuleTab" );

if ( ( $moduletab == "enabled" ) && ( count ( $modules ) != 0 ) )
{
	$t->parse( "left_spacer_item", "left_spacer_tpl" );
}

$t->parse( "top_field_item", "top_field_tpl" );

$t->pparse( "output", "separator_tpl" );
    

?>