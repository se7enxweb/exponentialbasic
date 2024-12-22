<?php
// 
// $Id: login.php 6389 2001-08-07 13:26:40Z jhe $
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
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "kernel/ezuser/admin/" .  $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "kernel/ezuser/admin/" . "/intl", $Language, "login.php" );
$t->setAllStrings();

$t->set_file( array(
    "login_tpl" => "login.tpl"
    ) );

$t->set_block( "login_tpl", "error_message_tpl", "error_message" );
$t->set_block( "login_tpl", "max_message_tpl", "max_message" );

if ( $Action == "login" )
{
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    $remoteAddress = $_SERVER['REMOTE_ADDR'];

    $user = new eZUser();
    $user = $user->validateUser( $_POST['Username'], $_POST['Password'] );

    if ( ( $user )  && eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        if ( $user->get( $user->ID ) )
        {
            $logins = $user->getLogins( $user->ID );
            $AllowSimultaneousLogins =  $ini->read_var( "eZUserMain", "SimultaneousLogins" );

            if ( $AllowSimultaneousLogins == "disabled" )
            {
                $MaxLogins = "1";
            }
            else
            {
                $MaxLogins = $user->simultaneousLogins();
            }

            if ( ( $logins < $MaxLogins ) || ( $MaxLogins == 0 ) )
            {
                eZLog::writeNotice( "Admin login: $Username from IP: $remoteAddress" );

                eZUser::loginUser( $user );
                if ( !isset( $RefererURL ) )
                    $RefererURL = "/";
                
                // Show password change dialog, if admin is using default login
                if ( $Username == "admin" && $Password == "publish" )
                {
                    $RefererURL = "/user/passwordchange/";
                }

                eZHTTPTool::header( "Location: $RefererURL" );
                exit();
            }
            else
            {
                eZLog::writeWarning( "Max limit reached: $Username from IP: $remoteAddress" );
        
                $maxerror = true;    
            }
        }
        else
        {
            ezLog::writeError( "Couldn't receive admin information on : $Username from IP: $remoteAddress" );

            $error = true;
        }
    }
    else
    {
        eZLog::writeWarning( "Bad admin login: $Username from IP: $remoteAddress" );
        
        $error = true;
    }
}

if ( !isset( $RefererURL ) )
    $RefererURL = $_SERVER['REQUEST_URI'];
    if ( preg_match( "#^/user/login.*#", $RefererURL  ) )
    {
        $RefererURL = "/";
        
    }

$t->set_var( "referer_url", $RefererURL );

if ( $Action == "logout" )
{
    eZUser::logout();
    eZHTTPTool::header( "Location: /" );
    exit();
}

if ( isset( $error ) && $error )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

if ( isset( $maxerror ) && $maxerror )
{
    $t->parse( "max_message", "max_message_tpl" );
}
else
{
    $t->set_var( "max_message", "" );
}

$t->set_var( "action_value", "login" );
$t->pparse( "output", "login_tpl" );