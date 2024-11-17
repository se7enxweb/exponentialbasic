<?php
// 
// $Id: useredit.php 9518 2002-05-08 11:51:36Z vl $
//
// Created on: <10-Oct-2000 12:52:42 bf>
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

require( "ezuser/user/usercheck.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztexttool.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

$user =& eZUser::currentUser();

if ( $Action == "Insert" )
{
    // check for valid data
    if ( $Login != "" &&
    $Email != "" &&
    $FirstName != "" &&
    $LastName != "" )
    {
        $user = new eZUser();

        if ( !$user->exists( $Login ) )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 3 ) )
            {
                if ( eZMail::validate( $Email ) )
                {
                    $user->setLogin( $Login );
                    $user->setPassword( $Password );
                    $user->setEmail( $Email );
                    
                    if ( $InfoSubscription == "on" )
                        $user->setInfoSubscription( true );
                    else
                        $user->setInfoSubscription( false );
                    
                    $user->setFirstName( $FirstName );
                    $user->setLastName( $LastName );

                    $user->store();

                    if ( isset( $UserGroups ) )
                    {
                        if ( !is_array( $UserGroups ) )
                            $UserGroups = array( $UserGroups );
                        if ( $AppendAnon )
                            $UserGroups[] = $AnonymousUserGroup;
                        foreach ( $UserGroups as $groupid )
                        {
                            // add user to usergroups
                            setType( $groupid, "integer" );

                            $group = new eZUserGroup( $groupid );
                            $group->addUser( $user );
                        }
                    }
                    else
                    {
                        // add user to usergroup
                        setType( $AnonymousUserGroup, "integer" );

                        $group = new eZUserGroup( $AnonymousUserGroup );
                        $group->addUser( $user );
                    }

                    // log in the user
                    $user->loginUser( $user );

                    eZLog::writeNotice( "Anonyous user created: $FirstName $LastName ($Login) $Email from IP: $REMOTE_ADDR" );                    
                    eZLog::writeNotice( "User login: $Login from IP: $REMOTE_ADDR" );

                    if ( isSet( $RedirectURL )  && ( $RedirectURL != "" ) )
                    {
                        eZHTTPTool::header( "Location: $RedirectURL" );
                        exit();
                    }
                    else
                    {
                        $redirect = $ini->read_var( "eZUserMain", "DefaultRedirect" );
                        eZHTTPTool::header( "Location: $redirect" );
                        exit();
                    }
                }
                else
                {
                    $EmailError = true;
                }                
            }
            else
            {
                $PasswordError = true;
            }
        }
        else
        {
            $UserExistsError = true;
        }
    }
    else
    {
        $Error = true;
    }
}

if ( $Action == "Update" )
{
    $user =& eZUser::currentuser();
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
    
    if ( eZMail::validate( $Email ) )
    {
        $user->setEmail( $Email );
        $user->setFirstName( $FirstName );
        $user->setLastName( $LastName );

        if ( $InfoSubscription == "on" )
            $user->setInfoSubscription( true );
        else
            $user->setInfoSubscription( false );
        
        if ( $Password )
        {
            if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 3 ) )
            {
                if ( ( $Password != "dummy" ) )
                {
                    $user->setPassword( $Password );
                }
            }
            else
            {
                $PasswordError = true;
            }
        }
        if ( $PasswordError == false )
        {
            $user->store();
        }
    }
    else
    {
        $EmailError = true;
    }
    if ( $EmailError == false )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
}
        
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl/", $Language, "useredit.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "useredit.tpl"
    ) );

if ( !isset( $ModuleName ) )
    $ModuleName = "user";
if ( !isset( $ModuleUserNew ) )
    $ModuleUserNew = "user";

$headline = new INIFIle( "ezuser/user/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );
$t->set_var( "module", $ModuleName );
$t->set_var( "user_new", $ModuleUserNew );

$actionValue = "insert";

if ( $Action == "Edit" )
{
    $user =& eZUser::currentuser();
    if ( !$user )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
    if ( !$UserID )
    {
        $getUser =& eZUser::currentUser();
        if ( !$getUser )
        {
            eZHTTPTool::header( "Location: /user/login" );
            exit();
        }
        else
        {
            $UserID = $getUser->id();
        }
    }

    if( $user->infoSubscription() == "true" )
        $InfoSubscription = "checked";
    else
        $InfoSubscription = "";
    
    $Login = $user->login();
    $Email = $user->email();
    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Password = "dummy";
    $VerifyPassword = "dummy";
    $t->set_var( "read_only", "readonly=readonly" );
    $actionValue = "update";
    $headline = new INIFIle( "ezuser/user/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );
}

$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );
$t->set_block( "user_edit_tpl", "email_error_tpl", "email_error" );

$t->set_block( "user_edit_tpl", "login_edit_tpl", "login_edit" );
$t->set_block( "user_edit_tpl", "login_view_tpl", "login_view" );

$t->set_var( "login_edit", "" );
$t->set_var( "login_view", "" );

if ( $Action != "Edit" )
{
    $t->parse( "login_edit", "login_edit_tpl" );
}
else
{
    $t->parse( "login_view", "login_view_tpl" );
}

if ( $Error == true )
{
    $t->parse( "required_fields_error", "required_fields_error_tpl" );
}
else
{
   $t->set_var( "required_fields_error", "" );
}

if ( $UserExistsError == true )
{
    $t->parse( "user_exists_error", "user_exists_error_tpl" );
}
else
{
   $t->set_var( "user_exists_error", "" );
}

if ( $PasswordError == true )
{
    $t->parse( "password_error", "password_error_tpl" );
}
else
{
   $t->set_var( "password_error", "" );
}

if ( $EmailError == true )
{
    $t->parse( "email_error", "email_error_tpl" );
}
else
{
   $t->set_var( "email_error", "" );
}

$t->set_var( "user_id", $UserID );
$t->set_var( "login_value", $Login );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );
$t->set_var( "info_subscription", $InfoSubscription );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

$t->set_var( "action_value", $actionValue );

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );

$t->pparse( "output", "user_edit_tpl" );

?>
