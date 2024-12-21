<?php
// 
// $Id: trustees.php 7815 2001-10-12 12:25:13Z jhe $
//
// Created on: <26-Jul-2001 14:26:26 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );

$ini =& $GLOBALS[ "GlobalSiteIni" ];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$locale = new eZLocale( $Language );

$user =& eZUser::currentUser();

if ( !$user )
{
    $text = "You do not have permission to trustees";
    $info = urlencode( $text );
    eZHTTPTool::header( "Location: /error/403?Info=$info" );
    exit();
}

$session =& eZSession::globalSession();
$session->fetch();
if ( isset( $Action ) && $Action == "edit" )
{
    $trustees = $user->trustees();
    if ( !isSet( $TrusteesList ) )
        $TrusteesList = array();
    $remove_trustees = array_diff( $trustees, $TrusteesList );
    $add_trustees = array_diff( $TrusteesList, $trustees );
    foreach ( $remove_trustees as $remove )
    {
        $user->removeTrustee( $remove );
    }
    foreach ( $add_trustees as $add )
    {
        $user->addTrustee( $add );
    }
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: " . $_SERVER["HTTP_REFERER"] );
    exit();
}

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "trustees.php" );

$t->set_file( "trustees_tpl", "trustees.tpl" );

$t->setAllStrings();

$t->set_block( "trustees_tpl", "user_item_tpl", "user_item" );

$t->set_var( "current_user_name", $user->name() );
$t->set_var( "current_user_id", $user->ID() );

$userList = $user->getAll();
$trustees = $user->trustees();
foreach ( $userList as $oneUser )
{
    if ( $oneUser->ID != $user->ID() )
    {
        $t->set_var( "user_id", $oneUser->ID() );
        $t->set_var( "user_name", $oneUser->name() );
        if ( in_array( $oneUser->ID(), $trustees ) )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        
        $t->parse( "user_item", "user_item_tpl", true );
    }
}

$t->pparse( "output", "trustees_tpl" );

?>