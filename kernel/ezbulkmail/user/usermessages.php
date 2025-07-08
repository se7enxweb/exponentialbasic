<?php
// 
// $Id: usermessages.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <02-Mar-2001 10:19:02 ce>
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
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "kernel/ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "kernel/ezbulkmail/user/intl/", $Language, "usermessages.php" );

$t->setAllStrings();
$t->set_file( array( "message" => "usermessages.tpl" ) );
$languageIni = new INIFIle( "kernel/ezbulkmail/user/intl/" . $Language . "/usermessages.php.ini", false );


if( isset( $mailConfirm ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "mail_sent_header" ) );
    $t->set_var( "body",  $languageIni->read_var( "strings", "mail_sent_message" ) );
}
if( isset( $unsuccessfull ) )
{
    $t->set_var( "header", "" );
    $t->set_var( "body", "" );
}
if( isset( $unsubscribemail ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "unsubscribe_mail_header" ) );
    $t->set_var( "body", $languageIni->read_var( "strings", "unsubscribe_mail_message" ) );
}
if( isset( $unsubscribed ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "unsubscribed_header" ) );
    $t->set_var( "body", $languageIni->read_var( "strings", "unsubscribed_message" ) );
}
if( isset( $subscribed ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "subscribed_header" ) );
    $t->set_var( "body", $languageIni->read_var( "strings", "subscribed_message" ) );
}
if( isset( $hasherror ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "unvalid_hash_header" ) );
    $t->set_var( "body", $languageIni->read_var( "strings", "unvalid_hash_message" ) );
}
if( isset( $subscriptionerror ) )
{
    $t->set_var( "header", $languageIni->read_var( "strings", "address_allready_subscribed_header" ) );
    $t->set_var( "body", $languageIni->read_var( "strings", "address_allready_subscribed_message" ) );
}

$t->pparse( "output", "message" );

?>