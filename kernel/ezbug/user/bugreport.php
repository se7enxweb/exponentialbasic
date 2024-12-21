<?php
//
// $Id: bugreport.php 9512 2002-05-08 09:16:58Z vl $
//
// Created on: <27-Nov-2000 20:31:00 bf>
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

// include_once( "ezmail/classes/ezmail.php" );

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztexttool.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezfilemanager/classes/ezvirtualfile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZBugMain", "Language" );

// include_once( "ezbug/classes/ezbug.php" );
// include_once( "ezbug/classes/ezbugcategory.php" );
// include_once( "ezbug/classes/ezbugmodule.php" );

$t = new eZTemplate( "kernel/ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "kernel/ezbug/user/intl", $Language, "bugreport.php" );
$t->setAllStrings();
$t->set_var( "sitedesign", $GlobalSiteDesign );

$t->set_file( "bug_report_tpl", "bugreport.tpl" );

$t->set_block( "bug_report_tpl", "module_item_tpl", "module_item" );
$t->set_block( "bug_report_tpl", "category_item_tpl", "category_item" );
$t->set_block( "bug_report_tpl", "email_address_tpl", "email_address" );
$t->set_block( "bug_report_tpl", "all_fields_error_tpl", "all_fields_error" );
$t->set_block( "bug_report_tpl", "email_error_tpl", "email_error" );

$t->set_block( "bug_report_tpl", "delete_items_tpl", "delete_items" );
$t->set_var( "delete_items", "" );
$t->set_block( "bug_report_tpl", "inserted_files_tpl", "inserted_files" );
$t->set_block( "bug_report_tpl", "inserted_images_tpl", "inserted_images" );
$t->set_block( "inserted_files_tpl", "file_tpl", "file" );
$t->set_block( "inserted_images_tpl", "image_tpl", "image" );
$t->set_var( "inserted_files", "" );
$t->set_var( "inserted_images", "" );


// new inserts new bug
// update, updates the bug with new values.
// when updating and inserting values must be checked.
// you must save the bug before you can add images/files.
$successfull = 0;
$actionValue = "new";
if ( $Action == "New" )
{
    $bug = new eZBug();
    $bug->setName( $Name );
    $bug->setDescription( $Description );
    $bug->store();

    $category = new eZBugCategory( $BugCategoryID );
    $module = new eZBugModule( $ModuleID );
    $bug->removeFromCategories();
    $bug->removeFromModules();
    $category->addBug( $bug );
    $module->addBug( $bug );

    $user =& eZUser::currentUser();
    if ( $user )
        $bug->setUser( $user );
    else
        $bug->setUserEmail( $Email );

    if ( $IsPrivate == "true" )
        $bug->setIsPrivate( true );

    $bug->setVersion( $Version );
    $bug->setIsHandled( false );
    $bug->store();

    $actionValue = "update";
    $BugID = $bug->id();
}

if ( $Action == "Update" )
{
    $bug = new eZBug( $BugID );
    $bug->setName( $Name );
    $bug->setDescription( $Description );

    $category = new eZBugCategory( $BugCategoryID );
    $module = new eZBugModule( $ModuleID );
    $bug->removeFromCategories();
    $bug->removeFromModules();

    $category->addBug( $bug );
    $module->addBug( $bug );

    $user =& eZUser::currentUser();
    if ( $user )
        $bug->setUser( $user );
    else
        $bug->setUserEmail( $Email );

    if ( $IsPrivate == "true" )
        $bug->setIsPrivate( true );

    $bug->setVersion( $Version );
    $bug->setIsHandled( false );
    $bug->store();

    $actionValue = "update";
    $BugID = $bug->id();
    $Action = "Edit";
}


/* bug is now allways saved... lets check what the user really wanted to do..*/
if ( isSet( $Ok ) ) // here check for errors. and display them if nescacary
{
    $user =& eZUser::currentUser();
    if ( ( $Name != "" ) && ( $Description != "" ) )
    {
        if ( $user )
        {
            $successfull = 1;
            send_email( $bug, $ini, $Language );
            eZHTTPTool::header( "Location: /bug/reportsuccess/" );
            exit();
        }
        else
        {
            if ( $Email != "" )
            {
                $successfull = 2;
                send_email( $bug, $ini, $Language );
                eZHTTPTool::header( "Location: /bug/reportsuccess/" );
                exit();
            }
            else
            {
                $EmailError = true;
            }
        }
    }
    else
    {
        $AllFieldsError = true;
    }
}
else{
    $BugID = 0;
}

if ( isSet( $InsertFile ) )
{
    $session->setVariable( "CurrentBugEdit", $BugID );
    eZHTTPTool::header( "Location: /bug/report/fileedit/new/" . $BugID . "/" );
    exit();
}

if ( isSet( $InsertImage ) )
{
    $session->setVariable( "CurrentBugEdit", $BugID );
    eZHTTPTool::header( "Location: /bug/report/imageedit/new/" . $BugID . "/" );
    exit();
}

if ( isSet( $DeleteSelected ) )
{
    if ( count( $ImageArrayID ) > 0 )
    {
        foreach ( $ImageArrayID as $imageID )
        {
            $image = new eZImage( $imageID );
            $bug->deleteImage( $image );
        }
    }

    if ( count( $FileArrayID ) > 0 )
    {
        foreach ( $FileArrayID as $fileID )
        {
            $file = new eZVirtualFile( $fileID );
            $bug->deleteFile( $file );
        }
    }
}

/* user didn't press any buttons.. lets set up the view correctly then..*/
$catName = "";
$modName = "";

if( isset( $Description ) )
{
    $t->set_var( "description_value", $Description );
}
else
{
    $t->set_var( "description_value", '' );
}

if( isset( $Name ) )
{
    $t->set_var( "title_value", eZTextTool::htmlspecialchars( $Name ) );
}
else
{
    $t->set_var( "title_value", '' );
}

$t->set_var( "file", "" );
$t->set_var( "image", "" );
$t->set_var( "private_checked", "" );
$t->set_var( "version_value", "" );

if ( isset( $IsPrivate ) && $IsPrivate == "On" )
    $t->set_var( "private_checked", "checked" );

$user =& eZUser::currentUser();
if ( $user )
    $t->set_var( "usr_email", $user->email() );
else
    $t->set_var( "usr_email", '' );

if( $Action == "Edit" ) // load values from database
{
    $bug = new eZBug( $BugID );
    $module = $bug->module();
    if ( $module )
        $modName = $module->name();

    $category = $bug->category();
    if ( $category )
        $catName = $category->name();

    $user =& eZUser::currentUser();
    if ( !$user )
        $t->set_var( "usr_email", $bug->userEmail() );


    $t->set_var( "description_value", $bug->description() );
    $t->set_var( "title_value", $bug->name() );
    $t->set_var( "version_value", $bug->version() );

    if ( is_object( $bug ) && $bug->isPrivate() )
        $t->set_var( "private_checked", "checked" );

// get the files
    $files = $bug->files();
    $i = 0;
    foreach ( $files as $file )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "file_number", $i + 1 );
        $t->set_var( "file_id", $file->id() );

        $t->set_var( "file_name", "<a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/filemanager/download/" . $file->id() . "/" . $file->originalFileName() . "\">" .  $file->name() . "</a>" );

        $t->parse( "file", "file_tpl", true );

        $i++;
    }
    $anyDeleteItems = false;
    if ( count( $files ) > 0 )
    {
        $t->parse( "inserted_files", "inserted_files_tpl", false );
        $anyDeleteItems = true;
    }

    // get the images
    $images = $bug->images();
    $i = 0;
    foreach ( $images as $image )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "image_number", $i + 1 );
        $t->set_var( "image_id", $image->id() );

        $tmp = $image->caption();
        $t->set_var( "image_name", $image->caption() );

        $variation =& $image->requestImageVariation( 150, 150 );
        $t->set_var( "image_url", "/" .$variation->imagePath() );
        $t->set_var( "image_width", $variation->width() );
        $t->set_var( "image_height",$variation->height() );

        $t->parse( "image", "image_tpl", true );

        $i++;
    }
    $actionValue = "update";
}

if ( isset( $images ) && count( $images ) > 0 )
{
    $anyDeleteItems = true;
   $t->parse( "inserted_images", "inserted_images_tpl", false );
}

if (isset( $anyDeleteItems ) &&  $anyDeleteItems )
    $t->parse( "delete_items", "delete_items_tpl", false );

// if any errors are set, lets display them to the user.
if ( isset( $AllFieldsError ) && $AllFieldsError == true )
{
    $t->parse( "all_fields_error", "all_fields_error_tpl" );
}
else
{
    $t->set_var( "all_fields_error", "" );
}

if ( isset( $EmailError ) && $EmailError == true )
{
    $t->parse( "email_error", "email_error_tpl" );
}
else
{
    $t->set_var( "email_error", "" );
}


// insert values into fields.
$category = new eZBugCategory();
$module = new eZBugModule();

// show email address field if the user is not logged in
$user =& eZUser::currentUser();

if ( $user )
{
    $t->set_var( "email_address", "" );
}
else
{
    $t->parse( "email_address", "email_address_tpl" );
}

// list the categories
$categories = $category->getAll();
foreach ( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );

    if ( $category->name() == $catName )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "category_item", "category_item_tpl", true );
}

// list the modules
$modules = $module->getAll();
foreach ( $modules as $module )
{
    $t->set_var( "module_id", $module->id() );
    $t->set_var( "module_name", $module->name() );

    if ( $module->name() == $modName )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "module_item", "module_item_tpl", true );
}

$t->set_var( "action_value", $actionValue );

$t->set_var( "bug_id", $BugID );

$t->pparse( "output", "bug_report_tpl" );


function send_email( $bug, $ini, $Language )
{
    // set up some values that we need
    $module = $bug->module();
    // find the owners of the group, if their is now owner group, no need to send mail.
    $ownerGroup = $module->ownerGroup();
    if ( !is_a( $ownerGroup, "eZUserGroup" ) )
    {
        eZHTTPTool::header( "Location: /bug/reportsuccess/" );
        exit();
    }
    $users = $ownerGroup->users();
    $userEmail = "";
    foreach ( $users as $userItem )
    {
        if ( $userEmail = "" )
            $userEmail = $userItem->email();
        else
            $userEmail = $userEmail . ", " . $userItem->email();
    }

    $mail = new eZMail();
    if ( $succesfull == 1 )
        $mail->setFrom( $user->email() );
    else
        $mail->setFrom( $bug->userEmail() );

    $mailTemplate = new eZTemplate( "kernel/ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                                    "kernel/ezbug/user/intl", $Language, "mailnewbug.php" );
    $headerInfo = getallheaders();

    $mailTemplate->set_file( "mailnewbug", "mailnewbug.tpl" );
    $mailTemplate->setAllStrings();

    $host = preg_replace( "/^admin\./", "", $headerInfo["Host"] );

    $mailTemplate->set_var( "bug_url", "http://" . $host . "/bug/bugview/" . $bug->id() );
    $mailTemplate->set_var( "bug_id", $bug->id() );
    $mailTemplate->set_var( "bug_title", $bug->name( false ) );
    $mailTemplate->set_var( "bug_module", $module->name( false ) );
    if ( $user )
        $mailTemplate->set_var( "bug_reporter", $user->namedEmail() );
    else
        $mailTemplate->set_var( "bug_reporter", $Email );
    $mailTemplate->set_var( "bug_description", $bug->description( false ) );

    $mail->setSubject( "[Bug][" . $bug->id() ."] " . $bug->name( false ) );
    $bodyText = ( $mailTemplate->parse( "dummy", "mailnewbug" ) );
    $mail->setBody( $bodyText );

    if ( $userEmail != "" )
    {
        $mail->setTo( $userEmail  );
        print( $userEmail );
        $mail->send();
    }
}

?>