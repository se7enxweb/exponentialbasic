<?php
// 
// $Id: categorytreelist.php 6233 2001-07-20 11:42:02Z jakobn $
//
// Created on: <12-Dec-2000 14:43:08 bf>
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
include_once( "classes/ezcurrency.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

$t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "categorytreelist.php" );

$t->set_file( "category_list_page_tpl", "categorytreelist.tpl" );

$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "top_category_tpl", "top_category" );
$t->set_block( "top_category_tpl", "level_1_category_tpl", "level_1_category" );


$t->setAllStrings();

$category = new eZProductCategory(  );
$category->get( $CategoryID );


$categoryList =& $category->getByParent( $category );

// categories
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "top_category_id", $categoryItem->id() );
    $t->set_var( "top_category_name", $categoryItem->name() );

    $subItemList =& $category->getByParent( $categoryItem );

    $t->set_var( "level_1_category", "" );
    
    // sub categories
    foreach ( $subItemList as $categoryItem )
    {
        $t->set_var( "category_id", $categoryItem->id() );
        $t->set_var( "category_name", $categoryItem->name() );
        $t->parse( "level_1_category", "level_1_category_tpl", true );
    }
    $t->parse( "top_category", "top_category_tpl", true );    
}

if ( count( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $t->parse( "category_list", "category_list_tpl" );
}

$t->pparse( "output", "category_list_page_tpl" );
?>
