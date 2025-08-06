<?php
// 
// $Id: messageview.php,v 1.3 2001/07/20 11:19:36 jakobn Exp $
//
// Created on: <06-Jun-2001 10:27:02 bf>
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

// include_once( "ezmessage/classes/ezmessage.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMessageMain", "Language" );

$t = new eZTemplate( "kernel/ezmessage/user/" . $ini->read_var( "eZMessageMain", "TemplateDir" ),
                     "kernel/ezmessage/user/intl", $Language, "messageview.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "message_page_tpl", "messageview.tpl" );

$t->setAllStrings();

$message = new eZMessage( );

if ( $message->get( $MessageID ) )
{
    $toUser =& $message->toUser();
    $fromUser =& $message->fromUser();
    $currentUser =& eZUser::currentUser();

    if ( $currentUser->id() != $toUser->id() && $currentUser->id() != $fromUser->id()  )
    {
        // access denied
        eZHTTPTool::header( "Location: /error/403/" );
        exit();
    }
    if ( $currentUser->id() == $toUser->id() )
    {
    	$message->setIsRead( true );
    	$message->store();
    }
    $messageArray =& $message->messagesToUser( $user );

    $created = $message->created();
    $t->set_var( "message_date", $locale->format( $created ) );

    $fromUser = $message->fromUser();
$t->set_var( "message_id", $message->id() );
    $t->set_var( "from_user_first_name", $fromUser->firstName() );
    $t->set_var( "from_user_last_name", $fromUser->lastName() );
    $t->set_var( "message_user_id", $fromUser->id() );
    
    $t->set_var( "message_subject", $message->subject() );
    $t->set_var( "message_show_message", $message->render( $message->description() ) );
    $t->set_var( "message_message", $message->description() );

    $t->pparse( "output", "message_page_tpl" );
}
else
{
    // message not found
    eZHTTPTool::header( "Location: /error/404/" );
    exit();
}

?>

