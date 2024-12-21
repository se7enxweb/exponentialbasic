<?php
// 
// $Id: settings.php 6484 2001-08-17 13:36:01Z jhe $
//
// Created on: <11-Apr-2001 16:53:40 amos>
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
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );

// include_once( "ezsession/classes/ezpreferences.php" );
$preferences = new eZPreferences();

// include_once( "ezmodule/classes/ezmodulehandler.php" );

$single_module = eZModuleHandler::useSingleModule();
$module_tab = eZModuleHandler::activeTab( true );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezsession/classes/ezsession.php" );

require( "kernel/ezuser/admin/admincheck.php" );

$url = $GLOBALS["RefURL"];
if ( $url == "/user/settings" )
    $url = "/";
if( $url == '' )
    $url == '/';

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: $url" );
    exit();
}

// Template
$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "settings.php" );
$t->setAllStrings();

$t->set_file( "settings", "settings.tpl" );

$t->set_block( "settings", "module_tab_item_tpl", "module_tab_item" );

$t->set_var( "ref_url", $url );

$user =& eZUser::currentUser();
if ( !$user ) 
{
    eZHTTPTool::header( "Location: /user/login/" );
    exit();
}

if ( isset( $Action ) && $Action == "update" )
{
    $preferences->setVariable( "SingleModule", $SingleModule != "" ? "enabled" : "disabled" );
    $preferences->setVariable( "ModuleTab", $ModuleTabBar != "" ? "enabled" : "disabled" );

    eZHTTPTool::header( "Location: $url" );
    exit();
}

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->set_var( "action_value", "update" );

$t->set_var( "module_tab", $module_tab ? "checked" : "" );
$t->set_var( "single_module", $single_module ? "checked" : "" );

$t->set_var( "module_tab_item", "" );
if ( eZModuleHandler::hasTab() )
    $t->parse( "module_tab_item", "module_tab_item_tpl" );

$t->pparse( "output", "settings" );

?>