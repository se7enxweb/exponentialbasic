<?php
// 
// $Id: search.php 9805 2003-04-10 14:48:45Z br $
//
// Created on: <13-Aug-2001 10:17:53 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFIle::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" );

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( "search_tpl", "search.tpl" );

$t->set_block( "search_tpl", "mail_line_tpl", "mail_line" );
$t->set_block( "search_tpl", "folder_item_tpl", "folder_item" );

$t->set_block( "mail_line_tpl", "mail_item_tpl", "mail_item" );
$t->set_block( "mail_line_tpl", "mail_item_unread_tpl", "mail_item_unread" );

$t->set_block( "mail_item_tpl", "mail_unread_tpl", "mail_unread" );
$t->set_block( "mail_item_tpl", "mail_read_tpl", "mail_read" );
$t->set_block( "mail_item_tpl", "mail_forwarded_tpl", "mail_forwarded" );
$t->set_block( "mail_item_tpl", "mail_replied_tpl", "mail_replied" );
$t->set_block( "mail_item_tpl", "mail_repliedall_tpl", "mail_repliedall" );
$t->set_block( "mail_item_tpl", "mail_edit_item_tpl", "mail_edit_item" );
$t->set_var( "mail_unread", "" );
$t->set_var( "mail_read", "" );
$t->set_var( "mail_forwarded", "" );
$t->set_var( "mail_replied", "" );
$t->set_var( "mail_repliedall", "" );
$t->set_var( "mail_edit_item", "" );

$t->set_var( "site_style", $SiteDesign );
$t->set_var( "search_string", htmlspecialchars( $SearchText ) );

if ( isSet( $Move ) )
{
    $folder = new eZMailFolder( $FolderSelectID );
    foreach ( $MailArrayID as $mailitemID )
        $folder->addMail( $mailitemID );
}

$mailList = eZMail::search( $SearchText );

$i = 0;
foreach ( $mailList as $mailItem )
{
    $t->set_var( "mail_id", $mailItem->id() );
    $t->set_var( "mail_subject", htmlspecialchars( $mailItem->subject() ), "-" );
    $t->set_var( "mail_sender", htmlspecialchars( $mailItem->sender() ) );

    $folder = $mailItem->folder( true );
    if ( $folder )
    {
        $t->set_var( "mail_folder_id", $folder->id() );
        $t->set_var( "mail_folder", $folder->name() );
        if ( $folder->folderType() == DRAFTS )
            $t->parse( "mail_edit_item", "mail_edit_item_tpl" );
        else
            $t->set_var( "mail_edit_item", "" );
    }
    else
    {
        $t->set_var( "mail_folder_id", "" );
        $t->set_var( "mail_folder", "" );
    }


    switch ( $mailItem->status() )
    {
        case UNREAD : $t->parse( "mail_status_renderer", "mail_unread_tpl", false ); break;
        case READ : $t->parse( "mail_status_renderer", "mail_read_tpl", false ); break;
        case REPLIED : $t->parse( "mail_status_renderer", "mail_replied_tpl", false ); break;
        case FORWARDED : $t->parse( "mail_status_renderer", "mail_forwarded_tpl", false ); break;
        case MAIL_SENT : $t->parse( "mail_status_renderer", "mail_read_tpl", false ); break;
    }
    
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );

    if ( $mailItem->status() == UNREAD )
        $t->parse( "mail_line", "mail_item_unread_tpl", true );
    else
        $t->parse( "mail_line", "mail_item_tpl", true );

    $i++;
}

foreach ( array( INBOX, SENT, DRAFTS, TRASH ) as $specialfolder )
{
    $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$folders = eZMailFolder::getByUser();
foreach ( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$t->pparse( "output", "search_tpl" );

?>