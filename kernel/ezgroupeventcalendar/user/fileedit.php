<?php
// 
// $Id: fileedit.php,v 1.7 2001/08/30 07:55:56 bf Exp $
//
// Created on: <21-Dec-2000 18:01:48 bf>
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

// include_once( "classes/ezfile.php" );

// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );

//include_once( "ezgroupeventcalendar/classes/ezgroupeventcategory.php" );
//include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );

if ( isset( $DeleteSelected ) )
    $Action = "Delete";

if ( $Action == "Insert" )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $event = new eZGroupEvent( $EventID );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

		eZObjectPermission::removePermissions( $uploadedFile->id(), "filemanager_file", "r" );
		eZObjectPermission::removePermissions( $uploadedFile->id(), "filemanager_file", "w" );

		eZObjectPermission::setPermission( -1, $uploadedFile->id(), "filemanager_file", "r" );
		eZObjectPermission::setPermission( 1, $uploadedFile->id(), "filemanager_file", "w" );
		
        $event->addFile( $uploadedFile );

        eZPBLog::writeNotice( "File added to event $EventID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/filelist/" . $EventID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    {
        $event = new eZGroupEvent( $EventID );

        $oldFile = new eZFile( $FileID );
        $event->deleteFile( $oldFile );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );

        $uploadedFile->store();

        $event->addFile( $uploadedFile );
    }
    else
    {
        $uploadedFile = new eZVirtualFile( $FileID );
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );
        $uploadedFile->store();
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/filelist/" . $EventID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $event = new eZGroupEvent( $EventID );

    if ( count ( $FileArrayID ) != 0 )
    {
        foreach( $FileArrayID as $FileID )
        {
            $file = new eZVirtualFile( $FileID );
            $event->deleteFile( $file );
        }
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /groupeventcalendar/eventedit/filelist/" . $EventID . "/" );
    exit();    
}

$t = new eZTemplate( "kernel/ezgroupeventcalendar/user/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/user/intl/", $Language, "fileedit.php" );

$t->setAllStrings();

$t->set_file( "file_edit_page", "fileedit.tpl" );


//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "file", "" );

if ( $Action == "Edit" )
{
    $event = new eZGroupEvent( $EventID );
    $file = new eZVirtualFile( $FileID );

    $t->set_var( "event_name", $event->name() );

    $t->set_var( "file_id", $file->id() );
    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "action_value", "Update" );
}

$event = new eZGroupEvent( $EventID );
    
$t->set_var( "event_name", $event->name() );
$t->set_var( "event_id", $event->id() );

$t->pparse( "output", "file_edit_page" );

?>