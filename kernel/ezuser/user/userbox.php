<?php
// 
// $Id: userbox.php 9518 2002-05-08 11:51:36Z vl $
//
// Created on: <20-Sep-2000 13:32:11 ce>
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

//require( "kernel/ezuser/user/usercheck.php" );


// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/eztexttool.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZUserMain", "Language" );
$UserWithAddress = $ini->variable( "eZUserMain", "UserWithAddress" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezsession/classes/ezsession.php" );

$user =& eZUser::currentUser();

if ( !$user ) 
{
    if ( !isset( $IntlDir ) )
        $IntlDir = "kernel/ezuser/user/intl";
    else if ( is_array( $IntlDir ) )
        $IntlDir[] = "kernel/ezuser/user/intl";
    if ( !isset( $IniFile ) )
        $IniFile = "userbox.php";
    else if ( is_array( $IniFile ) )
        $IniFile[] = "userbox.php";

    $t = new eZTemplate( "kernel/ezuser/user/" .  $ini->variable( "eZUserMain", "TemplateDir" ),
                         "kernel/ezuser/user/intl/", $Language, "/userbox.php" );

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
	  //$t->set_var( "user_edit_url", "/user/userwithaddress/new/" );
	  //$t->set_var( "user_edit_url", "/user/step1/" );
	  $t->set_var( "user_edit_url", "/user/new/" );
        }
        else
        {
	  $t->set_var( "user_edit_url", "/user/new/" );
	  //$t->set_var( "user_edit_url", "/user/step1/" );
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
    $t = new eZTemplate( "kernel/ezuser/user/" .  $ini->variable( "eZUserMain", "TemplateDir" ),
    "kernel/ezuser/user/intl", $Language, "userbox.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "userbox" => "userbox.tpl"
        ) );

	$t->set_block( "userbox", "message_switch_tpl", "message_switch" );
	$t->set_block( "message_switch_tpl", "message_switch_item_tpl", "message_switch_item" ); 

    if ( !isset( $GlobalSectionID ) )
        $GlobalSectionID = "";
    $t->set_var( "section_id", $GlobalSectionID );
    
    $t->set_var( "user_first_name", $user->firstName() );
    $t->set_var( "user_last_name", $user->lastName() );
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
      //$t->set_var( "user_edit_url", "/user/userwithaddress/edit/" );
      $t->set_var( "user_edit_url", "/user/step1/" );
    }
    else
    {
        //$t->set_var( "user_edit_url", "/user/user/edit/" );
        $t->set_var( "user_edit_url", "/user/step1/" );
    }
    $adminSiteAccessHostNameURL = $ini->variable( "site", "AdminSiteURL" );
    $isAdminShowLinkMarkup = "<tr><td width='1%' valign='top'><img src='/design/intranet/images/dot.gif' width='10' height='12' border='0' alt=''><br></td>
        <td width='99%'><a target='_blank' class='menu' href='https://$adminSiteAccessHostNameURL'>Administrator</a></td></tr>
    ";

    if ( eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        $t->set_var( "is_admin_show_link", $isAdminShowLinkMarkup );
    }
    else
    {
        $t->set_var( "is_admin_show_link", '' );
    }
    
    $t->set_var( "user_password_edit_url", '/user/passwordchange/' );

    // include_once( "ezmessage/classes/ezmessage.php" );
    $t->set_var( "message_switch", "" );
    $t->set_var( "message_switch_item", "" );
    
    $message = new eZMessage( );
    //$user = new eZUser();
    //$user->currentUser();
    $locale = new eZLocale( $Language );
    $messageArray =& $message->messagesToUser( $user );
    $i = 0;
    
    foreach ( $messageArray as $message )
    {    
        if ( $message->isRead() != true )
        {
            $i++;
            $created = $message->created();
            $t->set_var( "message_date", $locale->format( $created ) );
            $fromUser = $message->fromUser();
            $t->set_var( "message_from_user", $fromUser->firstName() . " " . $fromUser->lastName() );
            $t->set_var( "message_subject", $message->subject() ) ;
            $t->parse( "message_switch_item", "message_switch_item_tpl", true );
        }
        if ( $i > 0 )
        {
            $t->set_var( "new_message_count", $i );
            $t->parse( "message_switch", "message_switch_tpl" );
        }
    }
    
    $t->set_var( "new_message_count", $i );
    $t->set_var( "message_count", count($messageArray) );
    
    $t->set_var( "sitedesign", $GlobalSiteDesign );
    
    $t->pparse( "output", "userbox" );
} 

?>