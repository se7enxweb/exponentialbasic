<?php
//
// $Id: folderlist.php 6221 2001-07-20 11:18:28Z jakobn $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezmail/classes/ezmailaccount.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezmail/classes/ezmailfolder.php" );

/** If user wants to move folders **/
if( isset( $Move ) && count( $FolderArrayID ) > 0 && $FolderSelectID != -1)
{
    foreach( $FolderArrayID as $folderID )
    {
        $folder = new eZMailFolder( $folderID );
        if( $folder->folderType() == USER && $folder->id() != $FolderSelectID )
        {
            $folder->setParent( $FolderSelectID );
            $folder->store();
        }
    }
    eZHTTPTool::header( "Location: /mail/folderlist/" );
    exit();
}

/** If user wants to delete folders **/
if( isset( $Delete ) && count( $FolderArrayID ) > 0 )
{
    foreach( $FolderArrayID as $folderID )
        eZMailFolder::delete( $folderID );
}

/** If user wants to create a folder **/
if( isset( $NewFolder ) )
{
    eZHTTPTool::header( "Location: /mail/folderedit/" );
    exit();
}

/** User pressed EmptyTrash **/
if( isset( $EmptyTrash ) )
{
    $trash = eZMailFolder::getSpecialFolder( TRASH );
    $trash->deleteAll();
}

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMailMain", "Language" ); 

$t = new eZTemplate( "kernel/ezmail/user/" . $ini->variable( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl/", $Language, "folderlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_list_page_tpl" => "folderlist.tpl"
    ) );

$t->set_block( "folder_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_block( "folder_list_page_tpl", "folders_item_tpl", "folders_item" );
$t->set_block( "folders_item_tpl", "folders_item_edit_tpl", "folders_item_edit" );
$t->set_block( "folders_item_tpl", "edit_empty_tpl", "edit_empty" );
$t->set_var( "folders_item_edit", "" );

$t->set_var( "site_style", $SiteDesign );

/** insert the special folders **/
$i=0;
foreach( array( 1, 2, 3, 4 ) as $specialfolder )
{
    $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem->name() ) );
    $t->set_var( "folder_unread_mail_total", $folderItem->count( true ) );
    $t->set_var( "folder_mail_total", $folderItem->count() );
    $t->set_var( "indent", "");

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "edit_empty", "edit_empty_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}
/** insert the user folders **/
$userFolders = eZMailFolder::getTree( 0, -1);
foreach( $userFolders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem[0] );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem[1] ) );
    $t->set_var( "folder_unread_mail_total", (new eZMailFolder())->count( true, $folderItem[0] ) );
    $t->set_var( "folder_mail_total", (new eZMailFolder())->count(false, $folderItem[0] ) );
    $t->set_var( "indent", str_repeat( "&nbsp;", 3 * $folderItem[2] ) );
    $t->set_var( "edit_empty", "" );

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "folders_item_edit", "folders_item_edit_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}

/** insert folders into the move dialog **/
$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}

$t->pparse( "output", "folder_list_page_tpl" );

?>