<?php
// 
// $Id: menufilelist.php 9722 2002-09-02 12:33:26Z bf $
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


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFileManagerMain", "Language" );
$ImageDir = $ini->read_var( "eZFileManagerMain", "ImageDir" );

$t = new eZTemplate( "ezfilemanager/user/" . $ini->read_var( "eZFileManagerMain", "TemplateDir" ),
                     "ezfilemanager/user/intl/", $Language, "menufilelist.php" );

$t->set_file( "file_list_page_tpl", "menufilelist.tpl" );

$t->setAllStrings();

$t->set_block( "file_list_page_tpl", "file_list_tpl", "file_list" );
$t->set_block( "file_list_tpl", "file_tpl", "file" );

$folder = new eZVirtualFolder( $FolderID );
$fileList =& $folder->files();

$user =& eZUser::currentUser();

foreach ( $fileList as $file )
{
    if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) ||
         eZVirtualFile::isOwner( $user, $file->id() ) )
    {
        $t->set_var( "file_name", $file->name() );
        $t->set_var( "file_id", $file->id() );

        $t->parse( "file", "file_tpl", true );
    }
}

if ( count( $fileList ) > 0 )
{
    $t->parse( "file_list", "file_list_tpl" );
}
else
{
    $t->set_var( "file_list", "" );
}
$t->pparse( "output", "file_list_page_tpl" );

?>

