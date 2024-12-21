<?php
// $Id: categoryedit.php 7370 2001-09-21 11:12:52Z jhe $
//
// Created on: Created on: <14-Jul-2000 13:41:35 lw>
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
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );
$error = new INIFIle( "kernel/ezforum/admin/intl/" . $Language . "/categoryedit.php.ini", false );

// include_once( "classes/ezdb.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezcachefile.php" );

// include_once( "ezforum/classes/ezforumcategory.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

require( "kernel/ezuser/admin/admincheck.php" );

if ( isset( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

$cat = new eZForumCategory();


if ( $Action == "insert" )
{

    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        // clear the menu cache
        $files =& eZCacheFile::files( "ezforum/cache/",
                                      array( "menubox",
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
        
        if ( $Name != "" && $Description != "" )
        {
            $cat = new eZForumCategory();
            $cat->setName( $Name );
            $cat->setDescription( $Description );
            $cat->setSectionID( $SectionID );
   
            $cat->store();
            eZLog::writeNotice( "Forum category created: $Name from IP: $REMOTE_ADDR" );
            eZHTTPTool::header( "Location: /forum/categorylist/" );
        }
        else
        {
            eZLog::writeWarning( "Forum category not created: missing data from IP: $REMOTE_ADDR" );
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryDelete" ) )
    {
        // clear the menu cache
        $files =& eZCacheFile::files( "ezforum/cache/",
                                      array( "menubox",
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
        
        if ( $CategoryID != "" )
        {
            $cat = new eZForumCategory();
            $cat->get( $CategoryID );
            $categoryName = $cat->name();
            $cat->delete( );
            eZLog::writeNotice( "Forum category deleted: $categoryName from IP: $REMOTE_ADDR" );
            eZHTTPTool::header( "Location: /forum/categorylist/" );
        }
        else
        {
            eZLog::writeWarning( "Forum category not deleted: id not found from IP: $REMOTE_ADDR" );
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
}

if ( $Action == "DeleteCategories" )
{
    // clear the menu cache
    $files =& eZCacheFile::files( "ezforum/cache/",
                                  array( "menubox",
                                         NULL ),
                                  "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }

    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryDelete" ) )
    {
        if ( count( $CategoryArrayID ) != 0 )
        {
            foreach ( $CategoryArrayID as $CategoryID )
            {
                $cat = new eZForumCategory( $CategoryID );
                $categoryName = $cat->name();
                $cat->delete( );
                eZLog::writeNotice( "Forum category deleted: $categoryName from IP: $REMOTE_ADDR" );
            }
            eZHTTPTool::header( "Location: /forum/categorylist/" );
            exit();
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
}


if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryModify" ) )
    {
        // clear the menu cache
        $files =& eZCacheFile::files( "ezforum/cache/",
                                      array( "menubox",
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
        
        if ( $Name != "" &&
        $Description != "" )
        {
            $cat = new eZForumCategory();
            $cat->get( $CategoryID );
            $cat->setName( $Name );
            $cat->setDescription( $Description );
            $cat->setSectionID( $SectionID );
            $cat->store();
            eZLog::writeNotice( "Forum category updated: $Name from IP: $REMOTE_ADDR" );
            eZHTTPTool::header( "Location: /forum/categorylist/" );
        }
        else
        {
            eZLog::writeWarning( "Forum category not updated: missing data from IP: $REMOTE_ADDR" );
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
}

$t = new eZTemplate( "kernel/ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
                     "kernel/ezforum/admin/" . "/intl", $Language, "categoryedit.php" );
$t->setAllStrings();

$t->set_file( "category_page", "categoryedit.tpl" );

$t->set_block( "category_page", "section_item_tpl", "section_item" );

$t->set_var( "category_name", "" );
$t->set_var( "category_description", "" );
$t->set_var( "category_id", $CategoryID );
$action_value = "update";

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
    $action_value = "insert";
}

$languageIni = new INIFile( "kernel/ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
$headline =  $languageIni->read_var( "strings", "head_line_insert" );

if ( $Action == "edit" )
{
    $languageIni = new INIFile( "kernel/ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
    $headline =  $languageIni->read_var( "strings", "head_line_edit" );

    if ( !eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
    }
    else
    {
        $cat = new eZForumCategory();
        $cat->get( $CategoryID );    
        $t->set_var( "category_name", $cat->name() );
        $t->set_var( "category_description", $cat->description() );
        $action_value = "update";
        $sectionID = $cat->sectionID();
    }
}


$sectionList =& eZSection::getAll();

if ( count( $sectionList ) > 0 )
{
    foreach ( $sectionList as $section )
    {
        $t->set_var( "section_id", $section->id() );
        $t->set_var( "section_name", $section->name() );
        
        if ( $sectionID == $section->id() )
            $t->set_var( "section_is_selected", "selected" );
        else
            $t->set_var( "section_is_selected", "" );
        
        $t->parse( "section_item", "section_item_tpl", true );
    }
}
else
    $t->set_var( "section_item", "" );

$t->set_var( "action_value", $action_value );
$t->set_var( "error_msg", isset( $error_msg ) ? $error_msg : false );
$t->set_var( "headline", $headline );

$t->pparse( "output", "category_page" );

?>