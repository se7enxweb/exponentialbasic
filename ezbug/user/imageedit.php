<?php
// 
// $Id: imageedit.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <16-Feb-2001 14:32:36 fh>
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
include_once( "classes/ezlog.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );
include_once( "ezbug/classes/ezbug.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini = INIFile::globalINI();

$Language = $ini->read_var( "eZBugMain", "Language" );

$session = new eZSession();

//$BugID = $session->variable( "BugID" );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "userfile" ) )
    {
        $bug = new eZBug( $BugID );
        $image = new eZImage();
        if( $image->checkImage( $file ) && $image->setImage( $file ) )
        {
            $image->setName( $Name );
            $image->setCaption( $Caption );

            $image->store();
        
            $bug->addImage( $image );
            eZLog::writeNotice( "Picture added to bug: $BugID  from IP: $REMOTE_ADDR" );
        }
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/report/edit/" . $BugID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZImageFile();
    
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $bug = new eZBug( $BugID );
        $image = new eZImage();
        if( $image->checkImage( $file ) && $image->setImage( $file ) )
        {
            $oldImage = new eZImage( $ImageID );
            $bug->deleteImage( $oldImage );
        
            $image->setName( $Name );
            $image->setCaption( $Caption );
            $image->store();
        
            $bug->addImage( $image );
        }
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/report/edit/" . $BugID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $bug = new eZBug( $ButID );
    $image = new eZImage( $ImageID );
        
    $bug->deleteImage( $image );
    
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/report/edit/" . $BugID . "/" );
    exit();    
}

// store the image definition
if ( $Action == "StoreDef" )
{
//    $article = new eZArticle( $ArticleID );
//
//    if ( isset( $ThumbnailImageID ) &&  ( $ThumbnailImageID != 0 ) &&  ( $ThumbnailImageID != "" ) )
//    {
//        $thumbnail = new eZImage( $ThumbnailImageID );
//        $article->setThumbnailImage( $thumbnail );
//    }
//
//    if ( isset( $NewImage ) )
//    {
//        print( "new image" );
//        include_once( "classes/ezhttptool.php" );
//        eZHTTPTool::header( "Location: /article/articleedit/imageedit/new/$ArticleID/" );
//        exit();
//    }
//
//    include_once( "classes/ezhttptool.php" );
//    eZHTTPTool::header( "Location: /article/articleedit/edit/" . $ArticleID . "/" );
//    exit();
}

$t = new eZTemplate( "ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "ezbug/user/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );


$t->set_block( "image_edit_page", "image_tpl", "image" );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "image", "" );

if ( $Action == "Edit" )
{
    $bug = new eZBug( $BugID );
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "action_value", "Update" );


    $t->set_var( "image_alt", $image->caption() );

    $variation = $image->requestImageVariation( 150, 150 );
    
    $t->set_var( "image_src", "/" .$variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    $t->set_var( "image_file_name", $image->originalFileName() );
    $t->parse( "image", "image_tpl" );
}

$bug = new eZBug( $BugID );
    
$t->set_var( "bug_name", $bug->name() );
$t->set_var( "bug_id", $bug->id() );



$t->pparse( "output", "image_edit_page" );

?>
