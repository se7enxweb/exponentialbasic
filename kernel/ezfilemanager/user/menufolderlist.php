<?php
// 
// $Id: menufolderlist.php 7649 2001-10-04 10:04:54Z ce $
//
// Created on: <30-Sep-2001 15:43:27 bf>
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
// include_once( "classes/ezlog.php" );

// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );


$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZFileManagerMain", "Language" );
$ImageDir = $ini->variable( "eZFileManagerMain", "ImageDir" );

$t = new eZTemplate( "kernel/ezfilemanager/user/" . $ini->variable( "eZFileManagerMain", "TemplateDir" ),
                     "kernel/ezfilemanager/user/intl/", $Language, "menufolderlist.php" );

$t->set_file( "folder_list_page_tpl", "menufolderlist.tpl" );

$t->setAllStrings();

$t->set_block( "folder_list_page_tpl", "folder_list_tpl", "folder_list" );
$t->set_block( "folder_list_tpl", "folder_tpl", "folder" );

$folder = new eZVirtualFolder( $FolderID );

$user =& eZUser::currentUser();

// Print out the folders.
$folderList =& $folder->getByParent( $folder );

foreach ( $folderList as $folderItem )
{
    if ( eZObjectPermission::hasPermission( $folderItem->id(), "filemanager_folder", "r", $user ) ||
         eZVirtualFolder::isOwner( $user, $folderItem->id() ) )
    {
        $t->set_var( "folder_name", $folderItem->name() );
        $t->set_var( "folder_id", $folderItem->id() );
        
        $t->parse( "folder", "folder_tpl", true );
    }
}

if ( count( $folderList ) > 0 )
{
    $t->parse( "folder_list", "folder_list_tpl" );
}
else
{
    $t->set_var( "folder_list", "" );
}

$t->pparse( "output", "folder_list_page_tpl" );

?>