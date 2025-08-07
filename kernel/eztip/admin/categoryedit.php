<?php
// 
// $Id: categoryedit.php,v 1.10 2001/07/19 11:56:33 jakobn Exp $
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezsitemanager/classes/ezsection.php" );

if ( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /tip/archive/0/" );
    exit();
}

if ( isset ( $DeleteCategories ) )
{
    $Action = "DeleteCategories";
}

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZTipMain", "Language" );

// include_once( "eztip/classes/eztip.php" );
// include_once( "eztip/classes/eztipcategory.php" );

if ( !isset( $ParentID ) )
{
    $ParentID = 0;
}

// Direct actions
if ( $Action == "Insert" )
{

    $category = new eZTipCategory();
    $category->setName( $Name );

    $parentCategory = new eZTipCategory();

	    if ( $parentCategory->get( $ParentID ) == true )                    
        $category->setParent( $parentCategory );

    $category->setParent( $ParentID );
    
    $category->setDescription( $Description );
    $category->setIsPublished( $IsPublished );
    $category->setSectionArray( $SectionArray );

    $category->setLocationID( $LocationID );
    
    $category->store();

    $categoryID = $category->id();

    eZHTTPTool::header( "Location: /tip/archive/$categoryID/" );
    exit();
}

if ( $Action == "Update" )
{
    $category = new eZTipCategory();
    $category->get( $CategoryID );
    $category->setName( $Name );

    $parentCategory = new eZTipCategory();
    $category->setParent( $ParentID );
    
    $category->setDescription( $Description );
    $category->setIsPublished( $IsPublished );
    $category->setSectionArray( $SectionArray );

    $category->setLocationID( $LocationID );

    $category->store();

    $categoryID = $category->id();

    eZHTTPTool::header( "Location: /tip/archive/$categoryID/" );
    exit();
}

if ( $Action == "Delete" )
{
    $category = new eZTipCategory();
    $category->get( $CategoryID );

    $category->delete();
    
    eZHTTPTool::header( "Location: /tip/archive/" );
    exit();
}

if ( $Action == "DeleteCategories" )
{
    if ( count ( $CategoryArrayID ) != 0 )
    {
        foreach( $CategoryArrayID as $ID )
        {
            $category = new eZTipCategory( $ID );
            $category->delete();
        }
    }

    eZHTTPTool::header( "Location: /tip/archive/" );
    exit();
}

$t = new eZTemplate( "kernel/eztip/admin/" . $ini->variable( "eZTipMain", "AdminTemplateDir" ),
                     "kernel/eztip/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array( "category_edit_tpl" => "categoryedit.tpl" ) );


//$t->set_block( "category_edit_tpl", "value_tpl", "value" );
               
$category = new eZTipCategory();

$categoryArray = $category->getAll( );

$t->set_var( "category_id", isset( $CategoryID ) ? $CategoryID : 0 );

// edit
if ( $Action == "Edit" )
{
    $category = new eZTipCategory();
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
    if ( $category->ispublished() == true )
    {
        $t->set_var( "is_published", "checked" );
    }

}

if ( $Action == "Edit" || $Action == "New")
{

    $t->set_var( "description_value", "" );
    $t->set_var( "name_value", "" );
    $t->set_var( "action_value", "insert" );
    // $t->set_var( "category_id", "" );

    $category = new eZTipCategory();

	//get category tree
    //	$tree = $category->getTree();

    //	foreach( $tree as $item )
    //	{
    //		if ($item[0]->id() != $CategoryID) {
    //		    $t->set_var( "option_value", $item[0]->id() );
    //		    $t->set_var( "option_name", $item[0]->name() );
            
    //		    if ( $item[1] > 0 )
    //		        $t->set_var( "option_level", str_repeat( "&nbsp;", $item[1] ) );
    //		    else
    //		        $t->set_var( "option_level", "" );
                
    //		    if ( $item[0]->id() == $parentID )
    //		    {
    //		        $t->set_var( "selected", "selected" );
    //		        $selected = true;
    //		    }
    //		    else
    //		    {
    //		        $t->set_var( "selected", "" );
    //		    }            
        
    //		    $t->parse( "value", "value_tpl", true );
    //		}
    //	}

	// section selector
	$section = new eZSection();
	$sectionList = $section->getAll();
	$category = new eZTipCategory();
	$category->get( $CategoryID );
	$sectionArrayList = $category->getSectionArray();
	
	$t->set_block( "category_edit_tpl", "section_item_tpl", "section_item" );
	$t->set_var( "selected", "" );
    
    if ( in_array( "0", $sectionArrayList ) )
	{
      $t->set_var( "all-selected", "selected" );
	}
	
	foreach ( $sectionList as $sectionItem )
	{
	    $t->set_var( "section_name", $sectionItem->name() );
	    $t->set_var( "section_id", $sectionItem->id() );
	    if ( in_array( $sectionItem->id(), $sectionArrayList ) )
	        $t->set_var( "selected", "selected" );
	    else
	        $t->set_var( "selected", "" );
	    $t->parse( "section_item", "section_item_tpl", true );
	}

	// Location selector
	$TipLocations = $ini->variable( "eZTipMain", "TipLocations" );
	//$LocationList = array();
	$LocationList = explode(";" , $TipLocations);
	$category = new eZTipCategory();
	$category->get( $CategoryID );
	
	$t->set_block( "category_edit_tpl", "location_item_tpl", "location_item" );
	$t->set_var( "selected", "" );
	$locID = 0;
	foreach ( $LocationList as $LocationItem )
	{
	    $t->set_var( "location_name", $LocationList[$locID] );
	    $t->set_var( "location_id", $locID );
	    if ( $category->locationID() == $locID)
	        $t->set_var( "selected", "selected" );
	    else
	        $t->set_var( "selected", "" );

	    $t->parse( "location_item", "location_item_tpl", true );
	    $locID ++;
	}
}

$t->pparse( "output", "category_edit_tpl" );

?>