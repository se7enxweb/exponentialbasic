<?php
// 
// $Id: login.php 9518 2002-05-08 11:51:36Z vl $
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
include_once( "classes/ezlog.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztexttool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$UserWithAddress = $ini->read_var( "eZUserMain", "UserWithAddress" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );

if ( isSet( $Forgot ) )
{
    eZHTTPTool::header( "Location: /user/forgot/" );
    exit();
}

if ( isSet( $Register ) )
{
    if ( $UserWidthAddress == "enabled" )
    {
        eZHTTPTool::header( "Location: /user/userwithaddress/new/" );
    }
    else
    {
        eZHTTPTool::header( "Location: /user/user/new/" );        
    }
    exit();
}

unset ( $t );

// Template
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl", $Language, "login.php" );

$t->setAllStrings();

$t->set_file( array("login" => "login.tpl") );

$t->set_block( "login", "buttons_tpl", "buttons" );

$t->set_var( "buttons", "" );
if ( $ini->read_var( "eZUserMain", "RequireUserLogin" ) != "enabled" )
    $t->parse( "buttons", "buttons_tpl" );
else
$t->set_var( "buttons", "" );


if ( $Action == "login" )
{
    $user = new eZUser();
    $user = $user->validateUser( $_REQUEST['Username'], $_REQUEST['Password'] );    

    if ( $user )
    {
        if ( $user->get( $user->id() ) )
        {
            $logins = $user->getLogins( $user->id() );
            $AllowSimultaneousLogins =  $ini->read_var( "eZUserMain", "SimultaneousLogins" );

            if ( $AllowSimultaneousLogins == "disabled" )
            {
                $MaxLogins = "1";
            }
            else
            {
                $MaxLogins = $user->simultaneousLogins();
            }
            
            if ( ( $MaxLogins  == "0" ) || ( $logins < $MaxLogins ) )
            {
                eZLog::writeNotice( "User login: $Username from IP: $REMOTE_ADDR" );
                eZUser::loginUser( $user );
                
                if ( $user->cookieLogin() == true )
                {
                    $user->setCookieValues();
                }

                $mainGroup = $user->groupDefinition( true );
                if ( ( $mainGroup ) && $mainGroup->groupURL() )
                {
                    eZHTTPTool::header( "Location: " . $mainGroup->groupURL() );
                    exit();
                }
                else if ( isSet( $_REQUEST['RedirectURL'] ) )
                {
                    $stringTmp = preg_split( "/", $_REQUEST['RedirectURL'] );
                    
                    if ( $stringTmp[2] == "norights" )
                    {
                        eZHTTPTool::header( "Location: /" );
                        exit();
                    }
                    else
                    {
                        if ( $_REQUEST['RedirectURL'] == "" )
                        {
                            $_REQUEST['RedirectURL'] = "/";
                        }

                        eZHTTPTool::header( "Location: ".$_REQUEST['RedirectURL'] );
                        exit();
                    }
                }
                else
                {
                    eZHTTPTool::header( "Location: /" );
                    exit();
                }
            }
            else
            {
                eZLog::writeWarning( "Max limit reached: $Username from IP: $REMOTE_ADDR" );
                eZHTTPTool::header( "Location: /user/norights/?Error=MaxLogins&RedirectURL=$RedirectURL" );
                exit();
            }
        }
        else
        {
            eZLog::writeError( "Couldn't recieve userinformastion on : $Username from IP: $REMOTE_ADDR" );
            eZHTTPTool::header( "Location: /user/norights/?Error=UnknownError&RedirectURL=$RedirectURL" );
            exit();
        }
    }
    else
    {
        eZLog::writeWarning( "Bad login: $Username from IP: $REMOTE_ADDR" );
        eZHTTPTool::header( "Location: /user/norights/?Error=WrongPassword&RedirectURL=$RedirectURL" );
        exit();
    }
    
}
else
{
}

if ( $Action == "logout" )
{
    eZUser::clearAutoCookieLogin();
    eZUser::logout();
    eZHTTPTool::header( "Location: /" );
    exit();
}

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
// $t->set_var( "redirect_url", $RedirectURL );
$t->set_var( "action_value", "login" );

$t->pparse( "output", "login" );

?>