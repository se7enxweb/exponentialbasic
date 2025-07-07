<?php
// 
// $Id: bugview.php 7590 2001-09-29 12:35:15Z kaid $
//
// Created on: <04-Dec-2000 11:44:31 bf>
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
// include_once( "classes/ezlocale.php" );
// include_once( "classes/eztexttool.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZBugMain", "Language" );

// include_once( "ezbug/classes/ezbug.php" );
// include_once( "ezbug/classes/ezbugcategory.php" );
// include_once( "ezbug/classes/ezbugmodule.php" );
// include_once( "ezbug/classes/ezbugpriority.php" );
// include_once( "ezbug/classes/ezbugstatus.php" );
// include_once( "ezbug/classes/ezbuglog.php" );

$t = new eZTemplate( "kernel/ezbug/user/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "kernel/ezbug/user/intl", $Language, "bugview.php" );
$t->setAllStrings();

$t->set_file( array(
    "bug_edit_tpl" => "bugview.tpl"
    ) );

// path
$t->set_block( "bug_edit_tpl", "path_item_tpl", "path_item" );

$t->set_block( "bug_edit_tpl", "log_item_tpl", "log_item" );
$t->set_block( "bug_edit_tpl", "yes_tpl", "yes" );
$t->set_block( "bug_edit_tpl", "no_tpl", "no" );

$t->set_block( "bug_edit_tpl", "screenshots_tpl", "screenshots" );
$t->set_block( "screenshots_tpl", "screenshot_item_tpl", "screenshot_item" );
$t->set_block( "bug_edit_tpl", "patches_tpl", "patches" );
$t->set_block( "patches_tpl", "patch_item_tpl", "patch_item" );
$t->set_block( "bug_edit_tpl", "version_number_tpl", "version_number" );

$t->set_var( "version_number", "" );

$locale = new eZLocale( $Language );
$bug = new eZBug( $BugID );

// path
$module = $bug->module();
if( $module == false )
{
    eZHTTPTool::header( "Location: /bug/archive/0" );
    exit();
}

$pathArray = $module->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "module_id", $path[0] );

    $t->set_var( "module_name", $path[1]  );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

if( $bug->version() != "" )
{
    $t->set_var( "version_number_value", $bug->version() );
    $t->parse( "version_number", "version_number_tpl", false );
}

$t->set_var( "bug_id", $bug->id() );

$tmp = $bug->name();
$t->set_var( "name_value", $tmp );

$bug_reporter = $bug->user();
if( $bug_reporter )
{
    $t->set_var( "reporter_name_value", $bug_reporter->name() );
}
else
{
    $t->set_var( "reporter_name_value", "Unknown" );
}

$t->set_var( "description_value", eZTextTool::nl2br( $bug->description() ) );
$t->set_var( "action_value", "Update" );

$date =& $bug->created();
$t->set_var( "bug_date", $locale->format( $date ) );    

$pri =& $bug->priority();
$status =& $bug->status();
$module =& $bug->module();
$category =& $bug->category();

if ( $pri )
{    
    $t->set_var( "priority_name",  $pri->name() );
}
else
{
    $t->set_var( "priority_name", "" );
}

if ( $status )
{
    $t->set_var( "status_name", $status->name() );
}
else
{
    $t->set_var( "status_name", "" );
}

if ( $module )
{
    $t->set_var( "module_name",  $module->name() );
}
else
{
    $t->set_var( "module_name", "" );
}

if ( $category )
{
    $t->set_var( "category_name", $category->name() );
}
else
{
    $t->set_var( "category_name", "" );
}

if ( $bug->isClosed() == true )
{
    $t->parse( "yes", "yes_tpl" );
    $t->set_var( "no", "" );
}
else
{
    $t->parse( "no", "no_tpl" );
    $t->set_var( "yes", "" );
}

/* screenshot */
$images = $bug->images();
if( count( $images ) > 0 )
{
    $i = 0;
    foreach( $images as $image )
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
        $t->set_var( "image_name", "<a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/imagecatalogue/imageview/" . $image->id()
                     . "?RefererURL=/bug/bugview/$BugID" . "\">" . $tmp . "</a>" );
        $t->parse( "screenshot_item", "screenshot_item_tpl", true );
    
        $i++;
    }
    $t->parse( "screenshots", "screenshots_tpl", true );

}
else
{
    $t->set_var( "screenshots", "" );
}

/* Paths */
    $files = $bug->files();
if( count( $files ) > 0 )
{
    $i = 0;
    foreach( $files as $file )
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
//        $t->set_var( "file_id", $file->id() );
        $tmp = $file->name();
        $t->set_var( "file_name", "<a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/filemanager/download/" . $file->id() . "/" . $file->originalFileName() . "\">" .  $tmp . "</a>" );
    
        $t->parse( "patch_item", "patch_item_tpl", true );
    
        $i++;
    }
    $t->parse( "patches", "patches_tpl", true );
}
else
{
    $t->set_var( "patches", "" );
}


$bugLog = new eZBugLog();
$logList = $bugLog->getByBug( $bug );


foreach ( $logList as $log )
{
    $date =& $log->created();
    
    $t->set_var( "log_date", $locale->format( $date ) );
    
    $t->set_var( "log_description", $log->description() );
    
    $t->parse( "log_item", "log_item_tpl", true );
}

if ( count( $logList  ) == 0 )
{
    $t->set_var( "log_item", "" );
}

$t->pparse( "output", "bug_edit_tpl" );

?>