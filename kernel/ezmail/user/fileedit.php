<?php
// 
// $Id: fileedit.php 8504 2001-11-19 09:46:46Z jhe $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );

// include_once( "classes/ezfile.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );

if( isset( $Ok ) )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $mail = new eZMail( $MailID );

        $uploadedFile = new eZVirtualFile();
//        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        $uploadedFile->setName( $uploadedFile->originalFileName() );
        
        $uploadedFile->store();

        $mail->addFile( $uploadedFile );

        eZPBLog::writeNotice( "File added to mail $MailID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mail/mailedit/" . $MailID . "/" );
    exit();
}

if( isset( $Cancel ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mail/mailedit/" . $MailID . "/" );
    exit();
}

$ini = eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMailMain", "Language" );
$session = new eZSession();
$t = new eZTemplate( "kernel/ezmail/user/" . $ini->variable( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl", $Language, "fileedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "file_edit_page" => "fileedit.tpl",
    ) );

$t->set_var( "mail_id", $MailID );
$mail = new eZMail( $MailID );
$t->set_var( "mail_subject", $mail->subject() );

$t->pparse( "output", "file_edit_page" );
?>
