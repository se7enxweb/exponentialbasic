<?php
// 
// $Id: folderedit.php 7641 2001-10-03 08:44:34Z fh $
//
// Created on: <16-Feb-2001 14:33:48 fh>
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

// include_once( "ezmail/classes/ezmailfolder.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );

// check that the folder beeing viewed is your folder
if ( $FolderID != 0 && !eZMailFolder::isOwner( eZUser::currentUser(), $FolderID ) )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMailMain", "Language" ); 

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /mail/folderlist" );
    exit();
}

if( isset( $Ok ) && $Name != "" )
{
    
    if( $FolderID == 0 )
    {
        $folder = new eZMailFolder();
    }
    else
    {
        $folder = new eZMailFolder( $FolderID );
        if( $folder->isChild( $ParentID, true ) )
        {
            eZHTTPTool::header( "Location: /mail/folderlist" );
            exit();
        }
    }

    $folder->setName( $Name );
    $folder->setParent( $ParentID );
    $folder->setUser( eZUser::currentUser() );
    $folder->store();
    $FolderID = $folder->id();
    eZHTTPTool::header( "Location: /mail/folder/$FolderID" );
    exit();
}


$t = new eZTemplate( "kernel/ezmail/user/" . $ini->variable( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl/", $Language, "folderedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_edit_page_tpl" => "folderedit.tpl"
    ) );

$t->set_block( "folder_edit_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_var( "folder_item", "" );
$t->set_var( "folder_name", "" );
$t->set_var( "current_folder_id", $FolderID );

if( $FolderID != 0 )
{
    $folder = new eZMailFolder( $FolderID );
    $parentID = $folder->parentID();
    $t->set_var( "folder_name", htmlspecialchars( $folder->name() ) );
}

$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    if( $folderItem->id() != $FolderID )
    {
        $t->set_var( "folder_parent_id", $folderItem->id() );
        $t->set_var( "folder_parent_name", $folderItem->name() );

        if( isset( $parentID ) && $folderItem->id() == $parentID )
            $t->set_var( "is_selected", "selected" );
        else
            $t->set_var( "is_selected", "" );

        $t->parse( "folder_item", "folder_item_tpl", true );
    }
}

$t->pparse( "output", "folder_edit_page_tpl" );

?>