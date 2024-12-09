<?php
// 
// $Id: prioritytypeedit.php 6232 2001-07-20 11:36:07Z jakobn $
//
// Created on: <18-Oct-2000 15:04:39 bf>
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
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "eztodo/classes/ezpriority.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
    exit();
}

if ( isset( $Action ) && $Action == "insert" )
{
    $type = new eZPriority();
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
}

// Updates a priority.
if ( isset( $Action ) && $Action == "update" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
    exit();
}

// Delete a priority.
if ( isset( $Action ) && $Action == "delete" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->delete();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
    exit();
}

$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "prioritytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "prioritytypeedit" => "prioritytypeedit.tpl"
    ) );

$t->set_var( "action_value", "insert" );

// Edit a priority.
if ( isset( $Action ) && $Action == "edit" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );

    $PriorityName = $type->name();

    {
        $type_array = $type->get( $PriorityID );
        if ( is_bool( $type_array ) )
            $type_array = array();

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Name" ] );
        }
    }

    $t->set_var( "priority_type_id", $PriorityID );
    $t->set_var( "action_value", "update" );
}

$t->set_var( "priority_type_name", $PriorityName );
$t->set_var( "head_line", isset( $headline ) ? $headline : false );
$t->set_var( "submit_text", isset( $submittext ) ? $submittext : false );
$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "prioritytypeedit" );
?>