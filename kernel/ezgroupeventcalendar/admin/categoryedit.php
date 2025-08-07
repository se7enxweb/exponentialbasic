<?php
// 
// $Id: categoryedit.php,v 1.5 2001/02/20 12:33:38 gl Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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


if ( isset( $Cancel ) )
{
    Header( "Location: /groupeventcalendar/categorylist/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezlog.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );
$LanguageIni = new eZINI( "kernel/ezgroupeventcalendar/admin/intl/" . $Language . "/categoryedit.php.ini", false );

// include_once( "kernel/ezgroupeventcalendar/classes/ezgroupevent.php" );
// include_once( "kernel/ezgroupeventcalendar/classes/ezgroupeventcategory.php" );


if ( $Action == "Insert" )
{
    $category = new eZGroupEventCategory();
    $category->setName( $Name );
    $category->setDescription( $Description );

    if ( $ParentID != 0 && $ParentID != $category->id() )
    {
        $category->setParent( new eZGroupEventCategory( $ParentID ) );
    }

    $category->store();

    eZHTTPTool::header( "Location: /groupeventcalendar/categorylist/" );
    exit();
}

if ( $Action == "Update" )
{
    $category = new eZGroupEventCategory( $CategoryID );
    $category->setName( $Name );
    $category->setDescription( $Description );

    if ( $ParentID != 0 && $ParentID != $category->id() )
    {
        $category->setParent( new eZGroupEventCategory( $ParentID ) );
    }

    $category->store();

    eZHTTPTool::header( "Location: /groupeventcalendar/categorylist/" );
    exit();
}

if ( $Action == "Delete" )
{
    if ( count ( $CategoryArrayID ) != 0 )
    {
        foreach( $CategoryArrayID as $CategoryID )
        {
            $category = new eZGroupEventCategory( $CategoryID );
            $categoryName = $category->name();

            $category->delete();

            eZLog::writeNotice( "Appointment Category deleted: $categoryName from IP: $REMOTE_ADDR" );
        }
        eZHTTPTool::header( "Location: /groupeventcalendar/categorylist/" );
        exit();
    }
}


$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "categoryedit.tpl" ) );

$t->set_block( "category_edit_tpl", "parent_item_tpl", "parent_item" );


$t->set_var( "parent_is_selected", "selected" );

if ( $Action == "Edit" )
{
    $category = new eZGroupEventCategory( $CategoryID );
    if ( $category->parentID() != 0 )
        $t->set_var( "parent_is_selected", "" );
}
else
{
    $category = new eZGroupEventCategory();
}

$t->set_var( "parent_name", "No parent" );
$t->set_var( "parent_id", "0" );
$t->parse( "parent_item", "parent_item_tpl", true );


$categoryList = $category->getTree();
foreach ( $categoryList as $categorySubList )
{
    $categoryItem = $categorySubList[0];
    $categoryLevel = $categorySubList[1];
    $indent = "";

    if ( $categoryLevel > 1 )
        $indent =  str_repeat( "&nbsp;&nbsp;", $categoryLevel - 1 );

    $t->set_var( "parent_name", $indent . $categoryItem->name() );
    $t->set_var( "parent_id", $categoryItem->id() );
    $t->set_var( "parent_is_selected", "" );

    if ( $Action == "Edit" && $category->parentID() == $categoryItem->id() )
        $t->set_var( "parent_is_selected", "selected" );

    if ( $category->id() != $categoryItem->id() )
        $t->parse( "parent_item", "parent_item_tpl", true );
}


if ( $Action == "Edit" )
{
    $t->set_var( "header", $LanguageIni->variable( "strings", "edit_appointment_category" ) );
    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "action_value", "Update" );
}
else
{
    $t->set_var( "header", $LanguageIni->variable( "strings", "new_appointment_category" ) );
    $t->set_var( "description_value", "" );
    $t->set_var( "name_value", "" );
    $t->set_var( "category_id", "" );
    $t->set_var( "action_value", "Insert" );
}


$t->pparse( "output", "category_edit_tpl" );

?>
