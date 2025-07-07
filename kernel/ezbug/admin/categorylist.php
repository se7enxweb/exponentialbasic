<?php
//
// $Id: categorylist.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

/*
  Viser liste over prioriteringer
*/
// include_once( "classes/INIFile.php" );
$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );
$LanguageIni = new INIFIle( "kernel/ezbug/admin/intl/" . $Language . "/categorylist.php.ini", false );


// include_once( "classes/eztemplate.php" );

// include_once( "ezbug/classes/ezbugcategory.php" );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "category_page" =>  "categorylist.tpl"
    ) );

$t->set_block( "category_page", "category_item_tpl", "category_item" );

$t->set_var( "site_style", $SiteDesign );

if( isset( $Ok ) || isset( $AddCategory ) )
{
    $i = 0;
    if( count( $CategoryID ) > 0 )
    {
        foreach( $CategoryID as $itemID )
        {
            $category = new eZBugCategory( $itemID );
            $category->setName( $CategoryName[$i] );
            $category->store();
            $i++;
        }
    }
}

if( isset( $AddCategory ) )
{
    $newItem = new eZBugCategory();
    $newName = $LanguageIni->read_var( "strings", "newcategory" );
    $newItem->setName($newName);
    $newItem->store();
}

if( isset( $DeleteCategories ) )
{
    if( count( $CategoryArrayID ) > 0 )
    {
        foreach( $CategoryArrayID as $deleteItemID )
        {
            $item = new eZBugCategory( $CategoryID[ $deleteItemID ] );
            $item->delete();
        }
    }
}



$category = new eZBugCategory();
$categoryList = $category->getAll();

$i=0;
foreach( $categoryList as $categoryItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );
    $t->set_var( "index_nr", $i );
    
    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
} 

$t->pparse( "output", "category_page" );

?>