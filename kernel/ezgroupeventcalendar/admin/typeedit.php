<?
// 
// $Id: typeedit.php,v 1.5 2001/02/20 12:33:38 gl Exp $
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
    Header( "Location: /groupeventcalendar/typelist/" );
    exit();
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/ezlog.php" );
$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );
$LanguageIni = new eZINI( "kernel/ezgroupeventcalendar/admin/intl/" . $Language . "/typeedit.php.ini", false );

// include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );

if ( $Action == "Insert" )
{
    $type = new eZGroupEventType();
    $type->setName( $Name );
    $type->setDescription( $Description );

    if ( $ParentID != 0 && $ParentID != $type->id() )
    {
        $type->setParent( new eZGroupEventType( $ParentID ) );
    }

    $type->store();

    eZHTTPTool::header( "Location: /groupeventcalendar/typelist/" );
    exit();
}

if ( $Action == "Update" )
{
    $type = new eZGroupEventType( $TypeID );
    $type->setName( $Name );
    $type->setDescription( $Description );

    if ( $ParentID != 0 && $ParentID != $type->id() )
    {
        $type->setParent( new eZGroupEventType( $ParentID ) );
    }

    $type->store();

    eZHTTPTool::header( "Location: /groupeventcalendar/typelist/" );
    exit();
}

if ( $Action == "Delete" )
{
    if ( count ( $TypeArrayID ) != 0 )
    {
        foreach( $TypeArrayID as $TypeID )
        {
            $type = new eZGroupEventType( $TypeID );
            $typeName = $type->name();

            $type->delete();

            eZPBLog::writeNotice( "Appointment Type deleted: $typeName from IP: $REMOTE_ADDR" );
        }
        eZHTTPTool::header( "Location: /groupeventcalendar/typelist/" );
        exit();
    }
}


$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl/", $Language, "typeedit.php" );

$t->setAllStrings();

$t->set_file( array( "type_edit_tpl" => "typeedit.tpl" ) );

$t->set_block( "type_edit_tpl", "parent_item_tpl", "parent_item" );


$t->set_var( "parent_is_selected", "selected" );

if ( $Action == "Edit" )
{
    $type = new eZGroupEventType( $TypeID );
    if ( $type->parentID() != 0 )
        $t->set_var( "parent_is_selected", "" );
}
else
{
    $type = new eZGroupEventType();
}

$t->set_var( "parent_name", "No parent" );
$t->set_var( "parent_id", "0" );
$t->parse( "parent_item", "parent_item_tpl", true );


$typeList = $type->getTree();
foreach ( $typeList as $typeSubList )
{
    $typeItem = $typeSubList[0];
    $typeLevel = $typeSubList[1];
    $indent = "";

    if ( $typeLevel > 1 )
        $indent =  str_repeat( "&nbsp;&nbsp;", $typeLevel - 1 );

    $t->set_var( "parent_name", $indent . $typeItem->name() );
    $t->set_var( "parent_id", $typeItem->id() );
    $t->set_var( "parent_is_selected", "" );

    if ( $Action == "Edit" && $type->parentID() == $typeItem->id() )
        $t->set_var( "parent_is_selected", "selected" );

    if ( $type->id() != $typeItem->id() )
        $t->parse( "parent_item", "parent_item_tpl", true );
}


if ( $Action == "Edit" )
{
    $t->set_var( "header", $LanguageIni->variable( "strings", "edit_appointment_type" ) );
    $t->set_var( "name_value", $type->name() );
    $t->set_var( "description_value", $type->description() );
    $t->set_var( "type_id", $type->id() );
    $t->set_var( "action_value", "Update" );
}
else
{
    $t->set_var( "header", $LanguageIni->variable( "strings", "new_appointment_type" ) );
    $t->set_var( "description_value", "" );
    $t->set_var( "name_value", "" );
    $t->set_var( "type_id", "" );
    $t->set_var( "action_value", "Insert" );
}


$t->pparse( "output", "type_edit_tpl" );

?>
