<?php
// 
// $Id: userbox.php 9518 2002-05-08 11:51:36Z vl $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

//require( "ezuser/user/usercheck.php" );


include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/eztexttool.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$UserWithAddress = $ini->read_var( "eZUserMain", "UserWithAddress" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );

$user =& eZUser::currentUser();

if ( !$user ) 
{
    if ( !isset( $IntlDir ) )
        $IntlDir = "ezuser/user/intl";
    else if ( is_array( $IntlDir ) )
        $IntlDir[] = "ezuser/user/intl";
    if ( !isset( $IniFile ) )
        $IniFile = "userbox.php";
    else if ( is_array( $IniFile ) )
        $IniFile[] = "userbox.php";

    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
                         "ezuser/user/intl/", $Language, "/userbox.php" );

    $t->setAllStrings();

    if ( isset( $template_array ) and isset( $block_array ) )
    {
        $standard_array = array( "login" => "loginmain.tpl" );
        $t->set_file( array_merge( $standard_array, $template_array ) );
        $t->set_file_block( $template_array );
        $t->parse( $block_array );
    }
    else
    {
        $t->set_file( "login", "loginmain.tpl" );
    }
    $t->set_block( "login", "standard_creation_tpl", "standard_creation" );
    $t->set_block( "login", "extra_creation_tpl", "extra_creation" );

    if ( !isset( $GlobalSectionID ) )
        $GlobalSectionID = "";
    $t->set_var( "section_id", $GlobalSectionID );

    $t->set_var( "standard_creation", "" );
    $t->set_var( "extra_creation", "" );
    if ( !isset( $no_address ) )
        $no_address = "";
    $t->set_var( "no_address", $no_address );
    
    if ( isset( $type_list ) )
    {
        $t->parse( "extra_creation", "extra_creation_tpl" );
    }
    else
    {
        if ( $UserWithAddress == "enabled" )
        {
            $t->set_var( "user_edit_url", "/user/userwithaddress/new/" );
        }
        else
        {
            $t->set_var( "user_edit_url", "/user/user/new/" );
        }
        $t->parse( "standard_creation", "standard_creation_tpl" );
    }

    if ( !isset( $RedirectURL ) or !$RedirectURL )
        $RedirectURL = $_SERVER['REQUEST_URI'];
    if ( preg_match( "#^/user/user/login.*#", $RedirectURL  ) ||
         preg_match( "#^/user/forgot.*#", $RedirectURL) )
    {
        $t->set_var( "redirect_url", "/" );
        
    }
    else
    {
	$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
    }
   
    $t->set_var( "action_value", "login" );

    $t->set_var( "sitedesign", $GlobalSiteDesign );

    $t->pparse( "output", "login" );
    
}
else
{
    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
    "ezuser/user/intl", $Language, "userbox.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "userbox" => "userbox.tpl"
        ) );

    if ( !isset( $GlobalSectionID ) )
        $GlobalSectionID = "";
    $t->set_var( "section_id", $GlobalSectionID );
    
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "user_id", $user->id() );
    if ( ! isset( $SiteDesign ) )
        $SiteDesign = "";
    $t->set_var( "style", $SiteDesign );
    
    if ( ! isset( $no_address ) )
        $no_address = "";
    $t->set_var( "no_address", $no_address );
    
    if ( ! isset( $RedirectURL ) )
        $RedirectURL = $_SERVER['REQUEST_URI'];
    if ( preg_match( "#^/user/user/login.*#", $RedirectURL  ) )
    {
        $t->set_var( "redirect_url", "/" );
        
    }
    else
    {
        $t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
    }

    if ( $UserWithAddress == "enabled" )
    {
        $t->set_var( "user_edit_url", "/user/userwithaddress/edit/" );
    }
    else
    {
        $t->set_var( "user_edit_url", "/user/user/edit/" );
    }
    

    $t->set_var( "sitedesign", $GlobalSiteDesign );
    
    $t->pparse( "output", "userbox" );
} 