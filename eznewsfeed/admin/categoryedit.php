<?php
// 
// $Id: categoryedit.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <16-Nov-2000 13:02:32 bf>
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );

include_once( "eznewsfeed/classes/eznews.php" );

include_once( "classes/ezdatetime.php" );

include_once( "eznewsfeed/classes/eznews.php" );
include_once( "eznewsfeed/classes/eznewscategory.php" );

if ( $Action == "Insert" )
{
    $category = new eZNewsCategory();
    $category->setName( $CategoryName );
    $category->setDescription( $CategoryDescription );
    $category->store();
    
    eZHTTPTool::header( "Location: /newsfeed/archive/" );
    exit();
}

if ( $Action == "Update" )
{
    $category = new eZNewsCategory( $CategoryID );
    $category->setName( $CategoryName );
    $category->setDescription( $CategoryDescription );
    $category->store();

    eZHTTPTool::header( "Location: /newsfeed/archive/" );
    exit();
}

if ( $Action == "Delete" )
{
    $category = new eZNewsCategory( $CategoryID );
    $category->delete();

    eZHTTPTool::header( "Location: /newsfeed/archive/" );
    exit();
}
$news = new eZNews();

$ini = INIFile::globalINI();

$Language = $ini->read_var( "eZNewsfeedMain", "Language" );
$ImageDir = $ini->read_var( "eZNewsfeedMain", "ImageDir" );

$t = new eZTemplate( "eznewsfeed/admin/" . $ini->read_var( "eZNewsfeedMain", "AdminTemplateDir" ),
                     "eznewsfeed/admin/intl/", $Language, "categoryedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "category_edit_page_tpl" => "categoryedit.tpl"
    ) );

//  $t->set_block( "news_edit_page_tpl", "news_edit_tpl", "head_line" );

$t->set_var( "category_name_value", "" );
$t->set_var( "category_description_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "category_id", "" );

if ( $Action == "Edit" )
{
    $category = new eZNewsCategory( $CategoryID );
    $t->set_var( "action_value", "Update" );
    $t->set_var( "category_id", $category->id() );

    $t->set_var( "category_name_value", $category->name() );
    $t->set_var( "category_description_value", $category->description() );    
}


$t->pparse( "output", "category_edit_page_tpl" );

?>



