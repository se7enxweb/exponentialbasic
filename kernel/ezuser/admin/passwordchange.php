<?php
// 
// $Id: passwordchange.php 6484 2001-08-17 13:36:01Z jhe $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZUserMain", "Language" );
$errorIni = new eZINI( "kernel/ezuser/admin/intl/" . $Language . "/passwordchange.php.ini", false );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezsession/classes/ezsession.php" );

require( "kernel/ezuser/admin/admincheck.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /" );
}

// Template
$t = new eZTemplate( "kernel/ezuser/admin/" . $ini->variable( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezuser/admin/" . "/intl", $Language, "passwordchange.php" );
$t->setAllStrings();

$t->set_file( array(
    "change" => "passwordchange.tpl"
    ) );

$user =& eZUser::currentUser();
if ( !$user ) 
{
    eZHTTPTool::header( "Location: /user/login/" );
    exit();
}

if ( isset( $Action ) && $Action == "update" )
{
    $checkuser = $user->validateUser( $user->login(), $OldPassword );
    
    if ( !$checkuser )
    {
        $error_msg = $errorIni->variable( "strings", "error_wrong" );
    }
    else if ( $NewPassword != $VerifyPassword )
    {
        $error_msg = $errorIni->variable( "strings", "error_nomach" );
    }
    else if ( isset( $OldPassword ) && isset( $NewPassword ) && $OldPassword != $NewPassword )
    {
        $user->setPassword( $NewPassword );
        $user->store();
        $error_msg = $errorIni->variable( "strings", "password_update" );

    }
}

$t->set_var( "error_msg", isset( $error_msg ) ? $error_msg : false );

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->set_var( "action_value", "update" );
$t->pparse( "output", "change" );

?>