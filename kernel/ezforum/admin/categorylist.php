<?php
// $Id: categorylist.php 7371 2001-09-21 11:34:16Z jhe $
//
// Created on: Created on: <14-Jul-2000 13:41:35 lw>
//
// This source file is part of Exponential Basic, publishing software.
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

//include( "ezforum/dbsettings.php" );

// include_once( "classes/INIFile.php" );
$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZForumMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );

require( "kernel/ezuser/admin/admincheck.php" );
  
$cat = new eZForumCategory();

$t = new eZTemplate( "kernel/ezforum/admin/" . $ini->variable( "eZForumMain", "AdminTemplateDir" ),
"kernel/ezforum/admin/" . "/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( "category_page", "categorylist.tpl" );

$t->set_block( "category_page", "category_item_tpl", "category_item" );

$t->set_var( "site_style", $SiteDesign );

$category = new eZForumCategory();
$categoryList = $category->getAll();

if ( !$categoryList )
{
    $languageIni = new eZINI( "kernel/ezforum/admin/" . "intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $languageIni->variable( "strings", "noitem" );

    $t->set_var( "category_item", $noitem );
}
else
{
    $i = 0;
    foreach ( $categoryList as $categoryItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bgdark" );
        else
            $t->set_var( "td_class", "bglight" );
        
        $t->set_var( "category_id", $categoryItem->id() );
        $t->set_var( "category_name", $categoryItem->name() );
        $t->set_var( "category_description", $categoryItem->description() );
        
        $t->parse( "category_item", "category_item_tpl", true );
        $i++;
    }
} 

$t->pparse( "output", "category_page" );

?>