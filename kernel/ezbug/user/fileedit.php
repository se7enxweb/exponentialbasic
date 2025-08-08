<?php
// 
// $Id: fileedit.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <16-Feb-2001 14:33:48 fh>
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
// include_once( "ezbug/classes/ezbug.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );

$ini = eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZBugMain", "Language" );

$session = new eZSession();

// Turns out this is not needed.
// $BugID = $session->variable( "BugID" );

if ( $Action == "Insert" )
{
    $file = new eZPBFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $bug = new eZBug( $BugID );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

        $bug->addFile( $uploadedFile );

        eZPBLog::writeNotice( "File added to bug $BugID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    // include_once( "classes/ezhttptool.php" );
    echo "Location: /bug/report/edit/" . $BugID . "/";
    die();
    eZHTTPTool::header( "Location: /bug/report/edit/" . $BugID . "/" );
    exit();
}

if ( $Action == "Update" )
{
//      $file = new eZFilerFile();
    
//      if ( $file->getUploadedFile( "userfile" ) )
//      {
//          $article = new eZArticle( $ArticleID );

//          $oldFiler = new eZFiler( $FilerID );
//          $article->deleteFiler( $oldFiler );
        
//          $filer = new eZFiler();
//          $filer->setName( $Name );
//          $filer->setCaption( $Caption );

//          $filer->setFiler( $file );
        
//          $filer->store();
        
//          $article->addFiler( $filer );
//      }
//      else
//      {
//          $filer = new eZFiler( $FilerID );
//          $filer->setName( $Name );
//          $filer->setCaption( $Caption );
//          $filer->store();
//      }
    
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/edit/edit/" . $BugID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $bug = new eZBug( $BugID );
    $file = new eZVirtualFile( $FileID );
        
    $bug->deleteFile( $file );
    
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/edit/" . $BugID . "/" );
    exit();    
}


$t = new eZTemplate( "kernel/ezbug/user/" . $ini->variable( "eZBugMain", "TemplateDir" ),
                     "kernel/ezbug/user/intl", $Language, "fileedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "file_edit_page" => "fileedit.tpl",
    ) );


//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "file", "" );

if ( $Action == "Edit" )
{
    $bug = new eZBug( $BugID );
    $file = new eZVirtualFile( $FileID );

    $t->set_var( "bug_name", $bug->name() );

    $t->set_var( "file_id", $file->id() );
    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "action_value", "Update" );
}

$bug = new eZBug( $BugID );
    
$t->set_var( "bug_name", $bug->name() );
$t->set_var( "bug_id", $bug->id() );



$t->pparse( "output", "file_edit_page" );

?>