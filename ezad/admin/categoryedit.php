<?php
// 
// $Id: categoryedit.php 6203 2001-07-19 11:56:33Z jakobn $
//
// Created on: <18-Sep-2000 14:46:19 bf>
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

include_once( "classes/ezhttptool.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /ad/archive/$categoryID/" );
    exit();
}

if ( isset ( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZAdMain", "Language" );

include_once( "ezad/classes/ezad.php" );
include_once( "ezad/classes/ezadcategory.php" );


// Direct actions
if ( $Action == "Insert" )
{

    $category = new eZAdCategory();
    $category->setName( $Name );

    $parentCategory = new eZAdCategory();
    if ( $parentCategory->get( $ParentID ) == true )                    
        $category->setParent( $parentCategory );
    
    $category->setDescription( $Description );

    
    $category->store();

    $categoryID = $category->id();

    eZHTTPTool::header( "Location: /ad/archive/$categoryID/" );
    exit();
}

if ( $Action == "Update" )
{
    $category = new eZAdCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );

    $parentCategory = new eZAdCategory();
    if ( $parentCategory->get( $ParentID ) == true )                    
        $category->setParent( $parentCategory );
    
    $category->setDescription( $Description );

    $category->store();

    $categoryID = $category->id();

    eZHTTPTool::header( "Location: /ad/archive/$categoryID/" );
    exit();
}

if ( $Action == "Delete" )
{
    $category = new eZAdCategory();
    $category->get( $CategoryID );

    $category->delete();
    
    eZHTTPTool::header( "Location: /ad/archive/" );
    exit();
}

if ( $Action == "DeleteCategories" )
{
    if ( count ( $CategoryArrayID ) != 0 )
    {
        foreach( $CategoryArrayID as $ID )
        {
            $category = new eZAdCategory( $ID );
            $category->delete();
        }
    }

    eZHTTPTool::header( "Location: /ad/archive/" );
    exit();
}

$t = new eZTemplate( "ezad/admin/" . $ini->read_var( "eZAdMain", "AdminTemplateDir" ),
                     "ezad/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "categoryedit.tpl" ) );


$t->set_block( "category_edit_tpl", "value_tpl", "value" );
               
$category = new eZAdCategory();

$categoryArray = $category->getAll( );

$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "action_value", "insert" );
$t->set_var( "category_id", "" );


// edit
if ( $Action == "Edit" )
{
    $category = new eZAdCategory();
    $category->get( $CategoryID );

    $t->set_var( "name_value", $category->name() );
    $t->set_var( "description_value", $category->description() );
    $t->set_var( "action_value", "update" );
    $t->set_var( "category_id", $category->id() );

    $parent = $category->parent();
    
    if( is_object( $parent ) )
    {
        $parentID = $parent->id();
    }
    else
    {
        $parentID = 0;
    }
    if ( $category->excludeFromSearch() == true )
    {
        $t->set_var( "exclude_checked", "checked" );
    }
}

$category = new eZAdCategory();

$tree = $category->getTree();

foreach( $tree as $item )
{
    $t->set_var( "option_value", $item[0]->id() );
    $t->set_var( "option_name", $item[0]->name() );

    if ( $item[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $item[1] ) );
    else
        $t->set_var( "option_level", "" );
    
    if ( $item[0]->id() == $parentID )
    {
        $t->set_var( "selected", "selected" );
        $selected = true;
    }
    else
    {
        $t->set_var( "selected", "" );
    }            


    $t->parse( "value", "value_tpl", true );
}



$t->pparse( "output", "category_edit_tpl" );

?>
