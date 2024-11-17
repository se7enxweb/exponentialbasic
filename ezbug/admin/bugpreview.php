<?php
// 
// $Id: bugpreview.php 6208 2001-07-19 12:29:04Z jakobn $
//
// Created on: <03-Dec-2000 18:56:58 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/eztexttool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language =& $ini->read_var( "eZBugMain", "Language" );

include_once( "ezbug/classes/ezbug.php" );
include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbugpriority.php" );
include_once( "ezbug/classes/ezbugstatus.php" );
include_once( "ezbug/classes/ezbuglog.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "bugpreview.php" );
$t->setAllStrings();

$t->set_file( array(
    "bug_edit_tpl" => "bugpreview.tpl"
    ) );


$t->set_block( "bug_edit_tpl", "log_item_tpl", "log_item" );
$t->set_block( "bug_edit_tpl", "yes_tpl", "yes" );
$t->set_block( "bug_edit_tpl", "no_tpl", "no" );


$locale = new eZLocale( $Language );
$bug = new eZBug( $BugID );

$t->set_var( "bug_id", $bug->id() );
$t->set_var( "name_value", $bug->name() );
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
    $t->set_var( "priority_name", $pri->name() );
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
    $t->set_var( "module_name", $module->name() );
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


$bugLog = new eZBugLog();
$logList = $bugLog->getByBug( $bug );
if( count( $logList ) > 0 )
{
    foreach ( $logList as $log )
    {
        $date =& $log->created();
    
        $t->set_var( "log_date", $locale->format( $date ) );
    
    
        $t->set_var( "log_description", $log->description() );
    
        $t->parse( "log_item", "log_item_tpl", true );
    }
}
else
{
    $t->set_var( "log_item", "" );
}

$t->pparse( "output", "bug_edit_tpl" );

?>

