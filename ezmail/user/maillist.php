<?php
// 
// $Id: maillist.php 7641 2001-10-03 08:44:34Z fh $
//
// Created on: <19-Mar-2000 20:25:22 fh>
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
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

include_once( "classes/ezlist.php" );
include_once( "ezsession/classes/ezpreferences.php" );

// check that the folder beeing viewed is your folder
if ( !eZMailFolder::isOwner( eZUser::currentUser(), $FolderID ) )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}


if ( isSet( $NewFolder ) )
{
    eZHTTPTool::header( "Location: /mail/folderedit/" );
    exit();
}

if ( isSet( $Move ) && $FolderSelectID != -1 && count( $MailArrayID ) > 0 ) // really move to other folder
{
    $folder = new eZMailFolder( $FolderSelectID );
    foreach ( $MailArrayID as $mailitemID )
        $folder->addMail( $mailitemID );
}

$ini =& INIFile::globalINI();
if( isset( $NumMessages ) )
{
    eZPreferences::setVariable( "eZMail_MessagesPerPage", $NumMessages );
}
else
{
    $NumMessages = eZPreferences::variable( "eZMail_MessagesPerPage" );
    if( !$NumMessages )
    {
        $NumMessages = $ini->read_var( "eZMailMain", "MailPerPageDefault" );
        if( !$NumMessages )
            $NumMessages = 20; // hardcoded default in case all other fails.
    }
}

$Language = $ini->read_var( "eZMailMain", "Language" ); 
$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "maillist.php" );
$t->setAllStrings();

$t->set_file( "mail_list_page_tpl", "maillist.tpl" );

$t->set_var( "site_style", $SiteDesign );
$t->set_block( "mail_list_page_tpl", "mail_item_tpl", "mail_item" );
$t->set_block( "mail_item_tpl", "mail_edit_item_tpl", "mail_edit_item" );
$t->set_block( "mail_list_page_tpl", "mail_item_unread_tpl", "mail_item_unread" );
$t->set_block( "mail_list_page_tpl", "mail_render_tpl", "mail_render" );
$t->set_block( "mail_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_block( "mail_list_page_tpl", "num_mail_element_tpl", "num_mail_element" );
$t->set_var( "mail_edit_item", "" );
$t->set_var( "mail_item", "" );
$t->set_var( "mail_item_unread", "" );
$t->set_var( "mail_render", "" );

// set the pic blocks..
$t->set_block( "mail_item_tpl", "mail_unread_tpl", "mail_unread" );
$t->set_block( "mail_item_tpl", "mail_read_tpl", "mail_read" );
$t->set_block( "mail_item_tpl", "mail_forwarded_tpl", "mail_forwarded" );
$t->set_block( "mail_item_tpl", "mail_replied_tpl", "mail_replied" );
$t->set_block( "mail_item_tpl", "mail_repliedall_tpl", "mail_repliedall" );
$t->set_block( "mail_item_tpl", "mail_status_renderer_tpl", "mail_status_renderer" );
$t->set_var( "mail_unread", "" );
$t->set_var( "mail_read", "" );
$t->set_var( "mail_forwarded", "" );
$t->set_var( "mail_replied", "" );
$t->set_var( "mail_repliedall", "" );
$t->set_var( "mail_status_renderer", "" );

$user =& eZUser::currentUser();

$limit_array = array_unique( array( 20, 30, 40, 50, 60, 80, 100, 150, 200, $NumMessages ) );
$currentMethod = eZPreferences::variable( "eZMail_MessagesPerPage" );
sort( $limit_array );
foreach ( $limit_array as $element_number )
{
    $t->set_var( "messages_number", $element_number );
    if ( $element_number == $NumMessages )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );
    $t->parse( "num_mail_element", "num_mail_element_tpl", true );
}


$folder = new eZMailFolder( $FolderID );
$isDraftsFolder = false;
if ( $folder->folderType() == DRAFTS )
    $isDraftsFolder = true;


// check if the sort mode is changed...
$preferences = new eZPreferences();
if ( isSet( $SortMethod ) ) // the sorting method has changed..
{
    $currentMethod = $preferences->variable( "MailSortMethod" );
    $newMethod = "";
    switch ( $SortMethod )
    {
        case "subject" :
        {
            $newMethod = ( $currentMethod == "subject_asc" ) ? "subject_desc" : "subject_asc";
        }
        break;
        case  "from" :
        {
            $newMethod = ( $currentMethod == "from_asc" ) ? "from_desc" : "from_asc";
        }
        break;
        case "date" :
        {
            $newMethod = ( $currentMethod == "date_asc" ) ? "date_desc" : "date_asc";
        }
        break;
        case "size" :
        {
            $newMethod = ( $currentMethod == "size_asc" ) ? "size_desc" : "size_asc";
        }
        break;
        default :
        {
            $newMethod = "date_asc";
        }
    }
    
    $preferences->setVariable( "MailSortMethod", $newMethod );
}

$t->set_var( "current_folder_id", $FolderID );
$t->set_var( "current_folder_name", htmlspecialchars( $folder->name() ) );

$sort = $preferences->variable( "MailSortMethod");
$mail = $folder->mail( $sort, $Offset, $NumMessages );
$mailCount = $folder->mailCount();
$i = 0;
foreach ( $mail as $mailItem )
{
    $t->set_var( "mail_id", $mailItem->id() );
    $t->set_var( "mail_subject", htmlspecialchars( $mailItem->subject() ), "-" );
    $t->set_var( "mail_sender", htmlspecialchars( $mailItem->sender() ) );

    switch ( $mailItem->status() )
    {
        case UNREAD : $t->parse( "mail_status_renderer", "mail_unread_tpl", false ); break;
        case READ : $t->parse( "mail_status_renderer", "mail_read_tpl", false ); break;
        case REPLIED : $t->parse( "mail_status_renderer", "mail_replied_tpl", false ); break;
        case FORWARDED : $t->parse( "mail_status_renderer", "mail_forwarded_tpl", false ); break;
        case MAIL_SENT : $t->parse( "mail_status_renderer", "mail_read_tpl", false ); break;
    }

    $siSize = $mailItem->siSize();
    $t->set_var( "mail_size" , $siSize["size-string"] . $siSize["unit"] );
    $t->set_var( "mail_date", date("D M d H:i Y ", $mailItem->uDate() ) );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    if ( $mailItem->status() == UNREAD )
        $t->parse( "mail_render", "mail_item_unread_tpl", true );
    else
    {
        $isDraftsFolder ? $t->parse( "mail_edit_item", "mail_edit_item_tpl", false ) : $t->set_var( "mail_edit_item", "&nbsp;" );
        $t->parse( "mail_render", "mail_item_tpl", true );
    }

    $i++;
}

/* insert the standard folders first */
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

eZList::drawNavigator( $t, $mailCount, $NumMessages, $Offset, "mail_list_page_tpl" );

$t->pparse( "output", "mail_list_page_tpl" );

?>