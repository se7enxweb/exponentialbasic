<?php
//
// $Id: accountedit.php 6221 2001-07-20 11:18:28Z jakobn $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezmail/classes/ezmailaccount.php" );

if( !eZMailAccount::isOwner( eZUser::currentUser(), $AccountID ) )
    $AccountID = 0;

if( isset( $Ok ) )
{
    if( $AccountID == 0 )
    {
        $account = new eZMailAccount();
        $account->setUser( eZUser::currentUser() );
        $account->setIsActive( true );
    }
    else
    {
        $account = new eZMailAccount( $AccountID );
    }

    $account->setName( $Name );
    if( $Password != "" )
        $account->setPassword( $Password );
    $account->setLoginName( $Login );
    $account->setServer( $Server );
    $account->setServerPort( $Port );
    if( isset( $DelFromServer ) )
        $account->setDeleteFromServer( 1 );
    else
        $account->setDeleteFromServer( 0 );

    $account->store();
    eZHTTPTool::header( "Location: /mail/config" );
    exit();
}

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mail/config" );
    exit();
}


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMailMain", "Language" ); 

$t = new eZTemplate( "kernel/ezmail/user/" . $ini->variable( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl/", $Language, "accountedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "account_edit_page_tpl" => "accountedit.tpl"
    ) );

$t->set_var( "current_account_id", $AccountID );
$t->set_var( "name_value", "" );
$t->set_var( "login_value", "" );
$t->set_var( "password_value", "" );
$t->set_var( "port_value", "110" );
$t->set_var( "server_value" ,"" );
$t->set_var( "delete_from_server_checked", "" );

if( $AccountID != 0 ) // TODO: check that user really is the owner of the account.
{
    $account = new eZMailAccount( $AccountID );
    $t->set_var( "name_value", htmlspecialchars( $account->name() ) );
    $t->set_var( "login_value", htmlspecialchars( $account->loginName() ) );
    $t->set_var( "password_value", htmlspecialchars( $account->password() ) );
    $t->set_var( "server_value", htmlspecialchars( $account->server() ) );
    $t->set_var( "port_value", $account->serverPort() );
    if( $account->deleteFromServer() )
        $t->set_var( "delete_from_server_checked", "checked" );
}

$t->pparse( "output", "account_edit_page_tpl" );

?>