<?php
// 
// $Id: messageedit.php,v 1.5 2001/08/17 13:36:00 jhe Exp $
//
// Created on: <05-Jun-2001 17:19:01 bf>
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

// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztexttool.php" );

// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezmessage/classes/ezmessage.php" );
// include_once( "ezmessage/classes/ezmessagemessagedefinition.php" );

$MessageSent = false;
if ( isset( $SendMessage ) )
{
if ( substr( trim( $Receiver ) ,strlen( trim( $Receiver ) ) -1 ) == "," )
$Receiver = substr( trim( $Receiver ) , 0, strlen( trim( $Receiver ) ) -1 );

    $users = explode( ",", $Receiver );

    // check for valid users:
    $usersValid = true;
    foreach ( $users as $user )
    {
        $user = trim( $user );

        if ( !eZUser::exists( $user ) )            
            $usersValid = false;
    }
    
    if ( $usersValid == true )
    {
        foreach ( $users as $user )
        {
            $user = trim( $user );
            
            $message = new eZMessage( );
            if ( trim ( $Subject ) == "" )
            	$Subject = "None Subject";
            $message->setSubject( $Subject );
            if ( trim ( $Description ) == "" )
            	$Description = "None Description";
            $message->setDescription( $Description );
            $toUser = eZUser::getUser( $user );
            $message->setToUser( $toUser );

            $fromUser =& eZUser::currentUser();
            
            $message->setFromUser( $fromUser );

            $message->store();
$MessageID = $message->id();

$messageDefinition = new eZMessageDefinition();
$messageDefinition->setMessageID( $MessageID );
$messageDefinition->setToUserID( $toUser );
$messageDefinition->setFromUserID( $fromUser );
$messageDefinition->store();

$messageDefinition = new eZMessageDefinition();
$messageDefinition->setMessageID( $MessageID );
$messageDefinition->setToUserID( $fromUser );
$messageDefinition->setFromUserID( $toUser );
$messageDefinition->store();
            $MessageSent = true;
            
        }
    }    
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMessageMain", "Language" );
$t = new eZTemplate( "kernel/ezmessage/user/" . $ini->read_var( "eZMessageMain", "TemplateDir" ),
                     "kernel/ezmessage/user/intl", $Language, "messageedit.php" );

$locale = new eZLocale( $Language );



$t->set_file( "message_page_tpl", "messageedit.tpl" );

$t->set_block( "message_page_tpl", "error_tpl", "error" );
$t->set_block( "message_page_tpl", "message_sent_tpl", "message_sent" );
$t->set_block( "message_page_tpl", "message_verify_tpl", "message_verify" );
$t->set_block( "message_verify_tpl", "message_receiver_tpl", "message_receiver" );
$t->set_block( "message_page_tpl", "message_edit_tpl", "message_edit" );


$t->setAllStrings();

$Receiver = eZHTTPTool::getVar( "Receiver");
$Subject = eZHTTPTool::getVar( "Subject" );
$Description = eZHTTPTool::getVar( "Description" );
$Reply = eZHTTPTool::getVar( "Reply" );
$Edit = eZHTTPTool::getVar( "Edit" );
$Preview = eZHTTPTool::getVar( "Preview" );

$t->set_var( "receiver", $Receiver );
$t->set_var( "subject", $Subject );
$t->set_var( "description", $Description );

$mes = new eZMessage( );
$t->set_var( "show_description", $mes->render( $Description ) );
$t->set_var( "error", "" );

if ( isSet ( $Reply ) )
{
    $fromUser = new eZUser ( $FromUserID );
    $t->set_var( "full_name", $fromUser->firstName() );
    if ( $Message != "" )
{
    $t->set_var( "description", eZTextTool::addPre( $Message ), ">" );
}
else
{
    $t->set_var( "description", "" );
}
    $t->set_var( "receiver", $fromUser->login()  );
}

if ( $MessageSent == true )
{
    $t->parse( "message_sent", "message_sent_tpl" );
    $t->set_var( "message_verify", "" );
    $t->set_var( "message_edit", "" );
}
else if ( !isset( $Preview ) || isset( $Edit ) )
{
    $t->parse( "message_edit", "message_edit_tpl" );

if ($Receiver != "")
{
         $UserName = eZUser::getUser( $Receiver );
         $t->set_var( "full_name", $UserName->firstName()." ".$UserName->lastName() );
}
    $t->set_var( "message_verify", "" );
    $t->set_var( "message_sent", "" );
    
}
else
{

if ( substr( trim( $Receiver ) ,strlen( trim( $Receiver ) ) -1 ) == "," )
$Receiver = substr( trim( $Receiver ) , 0, strlen( trim( $Receiver ) ) -1 );
    $users = explode( ",", $Receiver );
    
    // check for valid users:
    $usersValid = true;
    foreach ( $users as $user )
    {
        $user = trim( $user );
        
        if ( !eZUser::exists( $user )  )            
            $usersValid = false;
    }
    
    if ( $usersValid == true )
    {
    	//$fromUser =& eZUser::currentUser();
    	//print_r($fromUser);
        foreach ( $users as $user )
        {
            $user = trim( $user );
            
            $toUser = eZUser::getUser( $user );

            $t->set_var( "login", $user );
            $t->set_var( "first_name", $toUser->firstName() );
            $t->set_var( "last_name", $toUser->lastName() );
            $t->parse( "message_receiver", "message_receiver_tpl", true );            
        }
    
        $t->parse( "message_verify", "message_verify_tpl" );
        $t->set_var( "message_edit", "" );
        $t->set_var( "message_sent", "" );        
    }
    else
    {
        // show error
        $t->parse( "error", "error_tpl" );
        $t->parse( "message_edit", "message_edit_tpl" );
        $t->set_var( "message_verify", "" );
        $t->set_var( "message_sent", "" );

    }
}

$t->pparse( "output", "message_page_tpl" );

if ( $MessageSent == true )
{
    include( "kernel/ezmessage/user/messagelist.php" );
}

?>

