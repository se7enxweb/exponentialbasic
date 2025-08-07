<?php
// 
// $Id: categorytypeedit.php 6232 2001-07-20 11:36:07Z jakobn $
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztemplate.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezmodule.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// include_once( "eztodo/classes/ezcategory.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->variable( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->variable( "eZTodoMain", "DocumentRoot" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

if ( isset( $Action ) && $Action == "insert" )
{
    $type = new eZCategory();
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

// Update a category.
if ( isset( $Action ) && $Action == "update" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

// Delete a category.
if ( isset( $Action ) && $Action == "delete" )
{

    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->delete();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

$t = new eZTemplate( "kernel/eztodo/admin/" . $ini->variable( "eZTodoMain", "AdminTemplateDir" ),
                     "kernel/eztodo/admin/intl", $Language, "categorytypeedit.php" );
$t->set_file( array(
    "categorytypeedit" => "categorytypeedit.tpl"
    ) );

$t->setAllStrings();

$t->set_var( "action_value", "insert" );

// Edit a category.
if ( isset( $Action ) && $Action == "edit" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );

    $CategoryName = $type->name();

    {
        $type_array = $type->get( $CategoryID );
        if ( is_bool( $type_array ) )
            $type_array = array();

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Name" ] );
        }
    }

    $t->set_var( "category_type_id", $CategoryID );
    $t->set_var( "action_value", "update" );
}

$t->set_var( "category_type_name", $CategoryName );
$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "categorytypeedit" );

?>