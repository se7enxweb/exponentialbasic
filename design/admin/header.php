<?php
//
// $Id: header.php 9803 2003-04-10 13:20:10Z br $
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


//include_once( "kernel/ezmodule/classes/ezmodulehandler.php" );

//include_once( "kernel/classes/eztemplate.php" );
//include_once( "kernel/classes/ezlocale.php" );
//include_once( "kernel/classes/ezpublish.php" );

$ini =& eZINI::instance('site.ini');
$Language =& $ini->variable( "eZUserMain", "Language" );
$Locale = new eZLocale( $Language );
//$iso = $Locale->languageISO();

$SiteURL =& $ini->variable( "site", "SiteURL" );
$AdminSiteURL =& $ini->variable( "site", "AdminSiteURL" );
$AdminSiteProtocol =& $ini->variable( "site", "AdminSiteProtocol" );
$UserSiteProtocol =& $ini->variable( "site", "UserSiteProtocol" );

//$site_modules = $ini->read_array( "site", "EnabledModules" );
$site_modules = eZModuleHandler::all();
//include_once( "kernel/ezmodule/classes/ezmodulehandler.php" );

$ModuleTab = eZModuleHandler::activeTab();
$singleModule = eZPreferences::variable( 'SingleModule' );

//include_once( "kernel/ezsession/classes/ezpreferences.php" );

$session =& eZSession::globalSession();

if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();
}

if ( isset( $page_charset ) )
{
    $session->setVariable( "charsetLanguage", $page_charset );
}

$charsetLanguage =& $session->variable( "charsetLanguage" );

// Fix to avoid setting the session variable when sections change charsets.
// This means that moving away from the section enabled pages will refetch the
// old setting.
//EP: autoswitch charsets in admin ------------------------------------------
if ( isset($url_array[2]) && ($url_array[2] == "archive" || $url_array[2] == "articleedit" ))
{
    $CategoryID = isset($url_array[4])?$url_array[4]:'';
    if ( $url_array[2] == "articleedit" )
    {
        // include_once( "kernel/ezarticle/classes/ezarticle.php" );
        $CategoryID = eZArticle::categoryDefinitionStatic( $CategoryID );
    }

    // include_once( "kernel/ezarticle/classes/ezarticlecategory.php" );
    // include_once( "kernel/ezsitemanager/classes/ezsection.php" );

    $GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );

    if ($GlobalSectionID) {
        $charsetLanguage = eZSection::language( $GlobalSectionID );
    }
}
else if ( isset($url_array[2]) && ($url_array[2] == "image" && ( $url_array[3] == "list" || $url_array[3] == "edit" )) )
{
    $CategoryID = $url_array[4];
    if ( $url_array[3] == "edit" )
    {
        // include_once( "kernel/ezimagecatalogue/classes/ezimage.php" );
        $img = new eZImage( $CategoryID );
        $Category = $img->categoryDefinition();
        if ( is_a( $Category, "eZImageCategory" ))
        {
            $CategoryID = $Category->id();
        }
    }

    // include_once( "kernel/ezimagecatalogue/classes/ezimagecategory.php" );
    // include_once( "kernel/ezsitemanager/classes/ezsection.php" );

    $GlobalSectionID = eZImageCategory::sectionIDStatic( $CategoryID );

    if ($GlobalSectionID) {
        $charsetLanguage = eZSection::language ( $GlobalSectionID );
    }
}
//EP ------------------------------------------------------------------------

if ( $charsetLanguage == "" )
{
    $charsetLanguage =& $ini->variable( "eZUserMain", "Language" );
}

$charsetLocale = new eZLocale( $charsetLanguage );
$iso = $charsetLocale->languageISO();

$preferences = new eZPreferences();
$modules =& eZModuleHandler::active();
// $modules =& $preferences->variableArray( "EnabledModules" );
$single_module = eZModuleHandler::useSingleModule();

$t = new eZTemplate( "design/admin/templates/" . $SiteDesign,
                     "design/admin/intl/", $Language, "header.php" );

$t->set_file( "header_tpl", "header.tpl" );

$t->set_block( "header_tpl", "module_list_tpl", "module_list" );
$t->set_block( "module_list_tpl", "module_item_tpl", "module_item" );
$t->set_block( "module_list_tpl", "module_control_tpl", "module_control" );
$t->set_block( "header_tpl", "menu_tpl", "menu_item" );
$t->set_block( "header_tpl", "charset_switch_tpl", "charset_switch" );
$t->set_block( "charset_switch_tpl", "charset_switch_item_tpl", "charset_switch_item" );

$user =& eZUser::currentUser();

if ( $user )
{
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "user_id", $user->id() );
}
else
{
    $t->set_var( "first_name", "" );
    $t->set_var( "last_name", "" );
    $t->set_var( "user_id", "" );
}

$uri = $_SERVER["REQUEST_URI"];

$t->set_var( "charset_switch", "" );

// The following is the charset switch - to view languages with different charsets

$CharsetSwitch =& $ini->variable( "site", "CharsetSwitch" );

if ( $CharsetSwitch == "enabled" )
{

    $charsets =& $ini->variable( "site", "Charsets" );
    $charsets_array = explode( ";", $charsets );

    foreach ( $charsets_array as $charset_item )
    {
        $charset_item_array = explode( "-", $charset_item );
        $t->set_var( "charset_submit_url", $uri );
	$t->set_var( "charset_code", $charset_item_array[0] );
	$t->set_var( "charset_description", $charset_item_array[1] );
	if ( $charset_item_array[0] == $charsetLanguage )
	{
	    $t->set_var( "charset_selected", "selected" );
	}
	else
	{
	    $t->set_var( "charset_selected", "" );
	}
	if ( $charset_item_array[0] != "" )
	{
	    $t->parse( "charset_switch_item", "charset_switch_item_tpl", true );
        }
    }
    $t->parse( "charset_switch", "charset_switch_tpl" );
}


$t->set_var( "site_url", $SiteURL );

$t->set_var( "site_style", $SiteDesign );

$t->set_var( "module_name", isset($moduleName)?$moduleName:'' );

$t->set_var( "charset", $iso );

if ( $iso != false )
    header( "Content-type: text/html;charset=$iso" );


$t->set_var( "module_list", "" );
$t->set_var( "module_item", "" );
$t->set_var( "module_control", "" );

$t->set_var( "ref_url", $uri );

if ( $ModuleTab == true )
{
    foreach( $site_modules as $site_module )
    {
        $module = strtolower( $site_module );
        if ( file_exists( "kernel/" . $module ) )
        {
            if ( $single_module )
            {
                $t->set_var( "module_action", "activate" );
            }
            else
            {
                $t->set_var( "module_action", in_array( $site_module, $modules ) ? "deactivate" : "activate" );
            }
            $t->set_var( "ez_module_name", $site_module );
            $t->set_var( "ez_dir_name", $module );
            $moduleSettingName = $site_module . "Main";
            $moduleLanguage = $ini->variable( $moduleSettingName, "Language" );
            if ( !$moduleLanguage )
                $moduleLanguage = $Language;
            $lang_file = new eZINI( "kernel/$module/admin/intl/$moduleLanguage/menubox.php.ini" );
            $mod_name = $lang_file->variable( "strings", "module_name" );
            $t->set_var( "module_name", $mod_name );
            $t->parse( "module_item", "module_item_tpl", true );
        }
    }
    if ( !$single_module )
        $t->parse( "module_control", "module_control_tpl" );
    $t->parse( "module_list", "module_list_tpl" );
}

$t->setAllStrings();

$t->set_var( "module_count", count ( $modules ) );
$t->set_var( "ezpublish_version", eZPublish::version() );
$t->set_var( "ezpublish_installation_version", eZPublish::installationVersion() );

$t->set_var( "ip_address", $_SERVER["REMOTE_ADDR"] );
$t->set_var( "admin_site_protocol", $AdminSiteProtocol );
$t->set_var( "admin_site_host", $AdminSiteURL );
$t->set_var( "user_site_protocol", $UserSiteProtocol );
$t->set_var( "user_site_host", $SiteURL );

$t->set_var( "menu_item", "" );

$moduletab = $ini->variable( "site", "ModuleTab" );

if ( ( $moduletab == "enabled" ) && ( count ( $modules ) != 0 ) )
{
	$t->parse( "menu_item", "menu_tpl" );
}

$t->pparse( "output", "header_tpl" );

?>