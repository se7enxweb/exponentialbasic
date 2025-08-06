<?php
// 
// $Id: messagelist.php,v 1.4 2001/07/30 13:22:37 bf Exp $
//
// Created on: <05-Jun-2001 16:42:09 bf>
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
// include_once( "ezmessage/classes/ezmessagemessagedefinition.php" );

$t = new eZTemplate( "kernel/ezmessage/admin/" . $ini->read_var( "eZMessageMain", "AdminTemplateDir" ),
                     "kernel/ezmessage/admin/intl", $Language, "messagesend.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "message_page_tpl", "messagesend.tpl" );

if ( isSet( $Delete ) && isSet( $DelMessage ) && count( $DelMessage ) > 0 )
{
	foreach( $DelMessage as $value )
	{
		$message = new eZMessage( $value );
		$toUser = $message->toUser();
		$fromUser = $message->fromUser();
		$user =& eZUser::currentUser();
		
		if ( $fromUser->id() == $user->id() )
		{
			$messageDefinition = new eZMessageDefinition();
			$definitionArray = $messageDefinition->getMessageID( $value );
//			print_r($definitionArray);

			foreach ( $definitionArray as $definition )
			{
				$to_user = $definition->toUserID();
				if ( $to_user->id() == $user->id() )
				{
					$definition->delete();
				}
			}
			if ( count( $definitionArray ) == 1 || $toUser == $fromUser )
				$message->delete();

		}
	}
}


$t->setAllStrings();

$t->set_block( "message_page_tpl", "message_list_tpl", "message_list" );
$t->set_block( "message_list_tpl", "message_item_tpl", "message_item" );
$t->set_block( "message_item_tpl", "message_read_tpl", "message_read" );
$t->set_block( "message_item_tpl", "message_unread_tpl", "message_unread" );

$user =& eZUser::currentUser();
$t->set_var( "user_first_name", $user->firstName() );
$t->set_var( "user_last_name", $user->lastName() );

$message = new eZMessage( );

$messageArray =& $message->messagesFromUser( $user );

$i = 0;
foreach ( $messageArray as $key => $message )
{
    $t->set_var( "message_id", $message->id() );

    $created = $message->created();
    $t->set_var( "message_date", $locale->format( $created ) );

    $toUser = $message->toUser();
    $t->set_var( "message_to_user", $toUser->firstName() . " " . $toUser->lastName() );

    $t->set_var( "message_subject", $message->subject() );

    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    if ( $message->isRead() == true )
    {
        $t->set_var( "message_disabled", "" );
        $t->set_var( "message_unread", "" );
        $timeread = $message->TimeRead();
        $t->set_var( "time_read", $locale->format( $timeread ) );
        $t->parse( "message_read", "message_read_tpl" );
    }
    else
    {
    	$t->set_var( "message_disabled", "disabled" );
        $t->set_var( "message_read", "" );
        $t->parse( "message_unread", "message_unread_tpl" );
    }

	$i++;
    $t->parse( "message_item", "message_item_tpl", true );
}
if ( count( $messageArray ) > 0 )
    $t->parse( "message_list", "message_list_tpl" );
else
    $t->set_var( "message_list", "" );


$t->pparse( "output", "message_page_tpl" );

?>

