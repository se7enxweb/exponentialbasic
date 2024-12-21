<?php
// 
// $Id: useredit.php 9390 2002-04-04 16:14:47Z br $
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

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$error_msg = false;
$error = new INIFIle( "kernel/ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

// include_once( "ezmail/classes/ezmail.php" );
// include_once( "classes/ezlog.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

require( "kernel/ezuser/admin/admincheck.php" );

if ( isSet( $_POST['DeleteUsers'] ) )
{
    $Action = "DeleteUsers";
}

if ( isSet( $_POST['Back'] ) )
{
    eZHTTPTool::header( "Location: /user/userlist/" );
    exit();
}


// do not allow editing users with root access while you do not.
$currentUser = eZUser::currentUser();

if( isset( $_POST['UserID'] ) && $_POST['UserID'] != '' )
{
    $editUser = new eZUser( $_POST['UserID'] );
    if( !$currentUser->hasRootAccess() && $editUser->hasRootAccess() )
    {
        $info = urlencode( "Can't edit a user with root priveliges." );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
        exit();
    }
}

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserAdd" ) )
    {
        if ( $_POST['Login'] != "" &&
             $_POST['Email'] != "" &&
             $_POST['FirstName'] != "" &&
             $_POST['LastName'] != "" &&
             $_POST['SimultaneousLogins'] != "")
        {
            if ( ( $_POST['Password'] == $_POST['VerifyPassword'] ) && ( strlen( $_POST['VerifyPassword'] ) > 2 ) )
            {
                $user = new eZUser();
                $user->setLogin( $_POST['Login'] );
                if ( !$user->exists( $user->login() ) )
                {
                    $tmp[0] = $_POST['Email'];
                    if ( eZMail::validate( $tmp[0] ) )
                    {
                        $user->setPassword( $_POST['Password'] );
                        $user->setEmail( $_POST['Email'] );
                        $user->setFirstName( $_POST['FirstName'] );
                        $user->setLastName( $_POST['LastName'] );
                        $user->setSignature( $_POST['Signature'] );
                        $user->setSimultaneousLogins( $_POST['SimultaneousLogins'] );

                        if ( $_POST['InfoSubscription'] == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );
                        
                        $user->store();
                        eZLog::writeNotice( "User created: " . $_POST['FirstName'] . " " . $_POST['LastName'] ." (" . $_POST['Login'] .") ". $_POST['Email'] . " " . $_POST['SimultaneousLogins'] . " from IP: " . $_SERVER['REMOTE_ADDR'] );
                        
                        // Add user to groups
                        $GroupArray = array_unique( array_merge( $_POST['GroupArray'], $_POST['MainGroup'] ) );
                        $group = new eZUserGroup();
                        $user->get( $user->id() );
                        $user->removeGroups();
                        foreach ( $GroupArray as $GroupID )
                        {
                            $group = new eZUserGroup();
//                            $user->get( $user->id() );
//                            $user->removeGroups();
                            $group->get( $GroupID );
                            if ( ( $group->isRoot() && $currentUser->hasRootAccess() ) || !$group->isRoot() )
                            {
                                $group->adduser( $user );
                                $groupname = $group->name();
                                eZLog::writeNotice( "User added to group: $groupname from IP: " . $_SERVER['REMOTE_ADDR'] );
                            }
                        }
                        
                        $user->setGroupDefinition( $MainGroup );
			$Action = false;
                        eZHTTPTool::header( "Location: /user/userlist/" );
                        exit();
                    }
                    else
                    {
                        $error_msg = $error->read_var( "strings", "error_email" );
                    }
                }
                else
                {
                    $error_msg = $error->read_var( "strings", "error_user_exists" );
                }
                
            }
            else
            {
                $error_msg = $error->read_var( "strings", "error_password" );
            }
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}

if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserModify" ) )
    {
        if( isset( $_POST['Login'] ) )
    	    $Login = $_POST['Login'];
    	if( isset( $_POST['Email'] ) )
            $Email = $_POST['Email'];
        if( isset( $_POST['FirstName'] ) )
            $FirstName = $_POST['FirstName'];
        if( isset( $_POST['LastName'] ) )
       	    $LastName = $_POST['LastName'];
        if( isset( $_POST['Signature'] ) )
       	    $Signature = $_POST['Signature'];
        if( isset( $_POST['SimultaneousLogins'] ) )
       	    $SimultaneousLogins = $_POST['SimultaneousLogins'];
        if( isset( $_POST['InfoSubscription'] ) )
            $InfoSubscription = $_POST['InfoSubscription'];
        if( isset( $_POST['Password'] ) )
            $Password = $_POST['Password'];
        if( isset( $_POST['VerifyPassword'] ) )
            $VerifyPassword = $_POST['VerifyPassword'];
        if( isset( $_POST['UserID'] ) )
            $UserID = $_POST['UserID'];

        if ( $Email != "" &&
        $FirstName != "" &&
        $LastName != "" &&
        $SimultaneousLogins != "")
        {
            if (  ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) ) ||
                  ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) == 0 ) ) )
            {
                $user->setLogin( $Login );
                {
                    if ( eZMail::validate( $Email ) )
                    {
                        $user = new eZUser();
                        $user->get( $UserID );
                        
                        $user->setEmail( $Email );
                        $user->setSignature( $Signature );

                        if ( $InfoSubscription == "on" )
                            $user->setInfoSubscription( true );
                        else
                            $user->setInfoSubscription( false );

                        $user->setFirstName( $FirstName );
                        $user->setLastName( $LastName );

                        $user->setSimultaneousLogins( $SimultaneousLogins );
                        
                        if ( strlen( $Password ) > 0 )
                        {
                            $user->setPassword( $Password );
                        }
                            
                        $user->store();
                        eZLog::writeNotice( "User updated: $FirstName $LastName ($Login) $Email from IP: " . $_SERVER['REMOTE_ADDR'] );

                        // Remove user from groups
                        $user->removeGroups();
                        
                        // Add user to groups
			if( isset( $_POST['GroupArray'] ) && isset( $_POST['MainGroup'] ) )
                        {
		 	    $GroupArray = array_unique( array_merge( $_POST['GroupArray'], $_POST['MainGroup'] ) );
			}
			else {
		 	    $GroupArray[] = $_POST['MainGroup'];
			}
                        $group = new eZUserGroup();
                        $user->get( $user->id() );
                        $user->removeGroups();
                        foreach ( $GroupArray as $GroupID )
                        {
                            $group = new eZUserGroup();
//                            $user->get( $user->id() );
//                            $user->removeGroups();
                            $group->get( $GroupID );
//                            if ( ( $group->isRoot() && $currentUser->hasRootAccess() ) || !$group->isRoot() )
                            {
                                $group->adduser( $user );
                                $groupname = $group->name();
                                eZLog::writeNotice( "User added to group: $groupname from IP: " . $_SERVER['REMOTE_ADDR'] );
                            }
                        }

                        $user->setGroupDefinition( $_POST['MainGroup'] );
                        eZHTTPTool::header( "Location: /user/userlist/" );
                        exit();
                    }
                    else
                    {
                        $error_msg = $error->read_var( "strings", "error_email" );
                    }
                }
                
            }
            else
            {
                $error_msg = $error->read_var( "strings", "error_password" );
            }
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
    $ActionValue = "update";
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZUser", "UserDelete" ) )
    {
        $user = new eZUser();
        $user->get( $UserID );
        $firstName = $user->firstName();
        $lastName = $user->lastName();
        $email = $user->email();
        $login = $user->login();
        $simultaneousLogins = $user->simultaneousLogins();
        
        $user->delete();
        
        eZLog::writeNotice( "User deleted: $firstname $lastname ($login) $email $simultaneousLogins from IP: $REMOTE_ADDR" );
        eZHTTPTool::header( "Location: /user/userlist/" );
        exit();
    }
    else
    {
        $error_msg = $error->read_var( "strings", "error_norights" );
    }
}
$currentUser = eZUser::currentUser();
if ( $Action == "DeleteUsers" )
{
    if( eZPermission::checkPermission( $user, "eZUser", "UserDelete" ) )
    {
        if ( count ( $UserArrayID ) != 0 )
        {
            foreach( $UserArrayID as $UserID )
            {
                $user = new eZUser( $UserID );
                $login = $user->login();
                if( $user->hasRootAccess() && !$currentUser->hasRootAccess() )
                {
                    $currentLogin = $currentUser->login();
                    eZLog::writeNotice( "$currentLogin failed to delete user $login since he can't delete users with root privelidges." );
                }
                else
                {
                    $firstName = $user->firstName();
                    $lastName = $user->lastName();
                    $email = $user->email();
                    $login = $user->login();
                    $simultaneousLogins = $user->simultaneousLogins();
                
                    $user->delete();
            
                    eZLog::writeNotice( "User deleted: $firstname $lastname ($login) $email $simultaneousLogins from IP: $REMOTE_ADDR" );
                }
            }
        }
    }
    eZHTTPTool::header( "Location: /user/userlist/" );
    exit();
}

$t = new eZTemplate( "kernel/ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
 "kernel/ezuser/admin/" . "/intl", $Language, "useredit.php" );
$t->setAllStrings();

$t->set_file( array(
    "user_edit" => "useredit.tpl"
     ) );

$t->set_block( "user_edit", "main_group_item_tpl", "main_group_item" );
$t->set_block( "user_edit", "group_item_tpl", "group_item" );

if ( $Action == "new" )
{
    $FirstName = "";
    $Lastname = "";
    $Email = "";
    $Login = "";
    $UserID = eZUser::currentUser()->ID;
    $SimultaneousLogins = $ini->read_var( "eZUserMain", "DefaultSimultaneousLogins" );
}

$ActionValue = "insert";

if ( $Action == "update" )
{
    $ActionValue = "update";
}

$headline = new INIFIle( "kernel/ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
$t->set_var( "head_line", $headline->read_var( "strings", "head_line_insert" ) );

$group = new eZUserGroup();

$groupList = $group->getAll();


$user = 0;
$t->set_var( "read_only", "" );
$user = new eZUser();

if( isset( $_POST['UserID'] ) )
    $UserID = $_POST['UserID'];

$user->get( $UserID );

if ( $Action == "edit" )
{
    if( $user->infoSubscription() == true )
        $InfoSubscription = "checked";
    else
        $InfoSubscription = "";
    
    $FirstName = $user->firstName();
    $LastName = $user->lastName();
    $Email = $user->email();
    $Login = $user->login();
    $Signature = $user->signature();
    $SimultaneousLogins = $user->simultaneousLogins();
    
    $headline = new INIFile( "kernel/ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );
    $t->set_var( "head_line", $headline->read_var( "strings", "head_line_edit" ) );

    $t->set_var( "read_only", "readonly=readonly" );

    $ActionValue = "update";
}
else // either new or failed edit... must put htmlspecialchars on stuff we got from form.
{
    $Login = false;
    $Email = false;
    $FirstName = false;
    $LastName = false;
    $Signature = false;
    $SimultaneousLogins = false;
    $InfoSubscription = false;


    if( isset( $_POST['Login'] ) )
        $Login = $_POST['Login'];
    if( isset( $_POST['Email'] ) )
        $Email = $_POST['Email'];
    if( isset( $_POST['FirstName'] ) )
        $FirstName = $_POST['FirstName'];
    if( isset( $_POST['LastName'] ) )
        $LastName = $_POST['LastName'];
    if( isset( $_POST['Signature'] ) )
        $Signature = $_POST['Signature'];
    if( isset( $_POST['SimultaneousLogins'] ) )
        $SimultaneousLogins = $_POST['SimultaneousLogins'];
    if( isset( $_POST['InfoSubscription'] ) )
        $InfoSubscription = $_POST['InfoSubscription'];
    if( isset( $_POST['UserID'] ) )
        $UserID = $_POST['UserID'];

    $FirstName = htmlspecialchars( $FirstName );
    $LastName = htmlspecialchars( $LastName );
    $Login = htmlspecialchars( $Login );
    $Signature = htmlspecialchars( $Signature );
    $Email = htmlspecialchars( $Email );
}

$mainGroup = $user->groupDefinition();
$groupArray = $user->groups();
foreach ( $groupList as $groupItem )
{
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );
    
    if ( $mainGroup == $groupItem->id() )
        $t->set_var( "main_selected", "selected" );
    else
        $t->set_var( "main_selected", "" );
    
    // add validation code here. $user->isValid();
    if ( $user )
    {
        $found = false;
        foreach ( $groupArray as $group )
        {
            if ( $group->id() == $groupItem->id() && $group->id() != $mainGroup )
            {
                $found = true;
            }
        }
        if ( $found == true )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->parse( "main_group_item", "main_group_item_tpl", true );
    $t->parse( "group_item", "group_item_tpl", true );
}

$t->set_var( "info_subscription", $InfoSubscription );
$t->set_var( "error", $error_msg );
$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );
$t->set_var( "email_value", $Email );
$t->set_var( "login_value", $Login );
$t->set_var( "signature", $Signature );
$t->set_var( "password_value", "" );
$t->set_var( "verify_password_value", "" );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "user_id", $UserID );
$t->set_var( "simultaneouslogins_value", $SimultaneousLogins );
$t->pparse( "output", "user_edit" );

?>
