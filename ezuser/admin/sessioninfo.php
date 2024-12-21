<?php
// 
// $Id: sessioninfo.php 9665 2002-07-09 07:25:42Z bf $
//
// Created on: <01-Nov-2000 14:34:30 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/eztime.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

require( "ezuser/admin/admincheck.php" );


if ( $Action == "Delete" && count( $SessionArrayID ) > 0 )
{
    foreach( $SessionArrayID as $sessionID )
    {
        $session = new eZSession( $sessionID );
        $session->delete();
    }
    eZHTTPTool::header( "Location: /user/sessioninfo/" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );


$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/" . "/intl", $Language, "sessioninfo.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_list_page" => "sessioninfo.tpl"
      ) );

$t->set_block( "user_list_page", "user_item_tpl", "user_item" );

$t->set_block( "user_list_page", "group_item_tpl", "group_item" );

$user = new eZUser();

$userSessionList =& eZUser::currentUsers();

$t->set_var( "user_count", count( $userSessionList ) );

$locale = new eZLocale( $Language );

$i=0;
foreach( $userSessionList as $userSessionItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->set_var( "first_name", $userSessionItem[0]->firstName() );
    $t->set_var( "last_name", $userSessionItem[0]->lastName() );
    $t->set_var( "email", $userSessionItem[0]->email() );
    $t->set_var( "user_id", $userSessionItem[0]->id() );

    $t->set_var( "session_id", $userSessionItem[1]->id() );

    $t->set_var( "session_ip", $userSessionItem[1]->variable( "SessionIP" ) );

    $idle = $userSessionItem[1]->idle();

    if ( $idle == 0 )
        $idle = 1;

    $seconds = $idle % 60;
    $minutes = ( ( $idle - $seconds ) / 60 ) % 60;
    $hours = ( $idle - $seconds - ( $minutes * 60 ) ) / (60*60);
    $time = eZLocale::addZero( $hours ) . ":" . eZLocale::addZero( $minutes ) . ":" . eZLocale::addZero( $seconds );

    $t->set_var( "idle", $time  );

    $t->parse( "user_item", "user_item_tpl", true );
    $i++;
}


$t->pparse( "output", "user_list_page" );

?>
