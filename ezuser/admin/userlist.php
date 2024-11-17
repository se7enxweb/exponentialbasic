<?php
//
// $Id: userlist.php 9671 2002-07-14 14:11:58Z kaid $
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlist.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$errorIni = new INIFile( "ezuser/admin/intl/" . $Language . "/userlist.php.ini", false );

$Max = $ini->read_var( "eZUserMain", "MaxUserList" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/" . "/intl", $Language, "userlist.php" );
$t->setAllStrings();

$t->set_file( "user_list_page", "userlist.tpl" );

$t->set_block( "user_list_page", "user_item_tpl", "user_item" );
$t->set_block( "user_item_tpl", "user_email_item_tpl", "user_email_item" );
$t->set_block( "user_item_tpl", "user_empty_email_item_tpl", "user_empty_email_item" );

$t->set_block( "user_list_page", "group_item_tpl", "group_item" );

$t->set_var( "site_style", $SiteDesign );
$t->set_var( "OldSearchText", "" );

$user = new eZUser();

$OrderBy = "name";

if( !isset( $LastName ) )
    $LastName = false;
if( !isset( $FirstName ) )
    $FirstName = false;
if( !isset( $Login ) )
    $Login = false;
if( !isset( $EMail ) )
    $EMail = false;
if( !isset( $Login ) )
    $Login = false;
if( !isset( $SearchText ) )
    $SearchText = false;

$LastName = addslashes(trim($LastName));
$FirstName = addslashes(trim($FirstName));
$Login = addslashes(trim($Login));
$EMail = addslashes(trim($EMail));
$SearchText = addslashes (trim ($SearchText) );

if ( !is_numeric( $Max ) )
    $Max = 10;
if ( !is_numeric( $Index ) )
    $Index = 0;

if ( isSet( $Search ) && $SearchText != "" )
{
    $userList = $user->search( $SearchText, $OrderBy );
    $TotalTypes =  count( $userList );
}
else if (   $FirstName != "" 
		  or $LastName != ""
		  or $Login != ""
		  or $EMail != ""
		 )
{
	$userList = $user->search( $SearchText, $OrderBy, $LastName, $FirstName, $EMail, $Login, $match);
	$TotalTypes =  count( $userList );
}
else if ( $GroupID == 0 )
{
    $userList = $user->getAll( $OrderBy, true, false, $Max, $Index );
    $TotalTypes = $user->getAllCount();
}
else
{
    $usergroup = new eZUserGroup();
    $userList = $usergroup->users( $GroupID, $OrderBy );
    $TotalTypes =  count( $userList );
}

$t->set_var( "user_count", count( $userList ) );
$t->set_var( "total_user_count", $TotalTypes );

if ( count( $userList ) == 0 )
{
    $error = $errorIni->read_var( "strings", "no_users" );
    $t->set_var( "user_item", $error );
}
else
{
    $i = 0;
    foreach ( $userList as $userItem )
    {
        $t->set_var( "user_email_item", "" );
        $t->set_var( "user_empty_email_item", "" );

        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        
        $t->set_var( "first_name", $userItem->firstName() );
        $t->set_var( "last_name", $userItem->lastName() );
        $t->set_var( "login_name", $userItem->login() );
        $email = $userItem->email();
        if ( empty( $email ) )
        {
            $t->parse( "user_empty_email_item", "user_empty_email_item_tpl" );
        }
        else
        {
            $t->set_var( "email", $email );
            $t->parse( "user_email_item", "user_email_item_tpl" );
        }
        $t->set_var( "email", $userItem->email() );
        $t->set_var( "user_id", $userItem->id() );
        
//      if ( $userItem->infoSubscription( ) == true )
//      {
//          print( $userItem->email() . "<br>" );
//      }
        
        $t->parse( "user_item", "user_item_tpl", true );
        $i++;
    }
}

eZList::drawNavigator( $t, $TotalTypes, $Max, $Index, "user_list_page" );

$group = new eZUserGroup();
$groupList = $group->getAll();
    

foreach ( $groupList as $groupItem )
{

//  print( $GroupID . " " . $groupItem->id() . "<br>" );
   
    if ( $groupItem->id() == $GroupID )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    $t->parse( "group_item", "group_item_tpl", true );
}

$t->set_var( "current_group_id", $GroupID );
$t->set_var( "sort_order", $OrderBy );

$t->pparse( "output", "user_list_page" );

?>