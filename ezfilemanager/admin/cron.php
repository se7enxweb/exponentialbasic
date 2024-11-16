<?php
// 
// $Id: cron.php 8109 2001-10-31 08:02:17Z jhe $
//
// Created on: <01-Aug-2001 09:34:19 jhe>
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
include_once( "classes/ezfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );
include_once( "ezfilemanager/classes/ezvirtualfolder.php" );

$ini =& INIFile::globalINI();

function getFiles( $dir )
{
    while ( $file = $dir->read() )
    {
        if ( $file != "." && $file != ".." )
        {
            if ( is_dir( $dir->path . "/" . $file ) )
                getFiles( dir( $dir->path . "/" .  $file ) );
            else
                addFile( $dir->path, $file );
        }
    }
}

function addFile( $dir, $file )
{
    global $ini;
    $readGroup = $ini->read_var( "eZFileManagerMain", "SyncronizeReadGroup" );
    $writeGroup = $ini->read_var( "eZFileManagerMain", "SyncronizeWriteGroup" );
    $syncUser = $ini->read_var( "eZFileManagerMain", "SyncronizedFilesOwner" );
    if ( !eZVirtualFile::fileExists( $dir, $file ) )
    {
        $folder = new eZVirtualFolder( eZVirtualFolder::getByName( $dir, 0, true, $readGroup, $writeGroup ) );
        $virtualFile = new eZVirtualFile();
        $localFile = new eZFile();
        $localFile->getFile( $dir . "/" . $file );
        $virtualFile->setFile( $localFile );
        $virtualFile->setOriginalFileName( $file );
        $virtualFile->setUser( $syncUser );
        $virtualFile->store();
        $folder->addFile( $virtualFile );
    }
}

$autoSync = $ini->read_var( "eZFileManagerMain", "AutoSyncronize" );

if ( $autoSync || $autoSync == "true" )
{
    $localDir = $ini->read_var( "eZFileManagerMain", "LocalSyncronizeDir" );
    getFiles( eZFile::dir( $localDir, false ) );
}

?>
