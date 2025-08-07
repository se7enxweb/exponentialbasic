<?php
// 
// $Id: categorylist.php 7428 2001-09-24 14:03:59Z jhe $
//
// Christoffer A. Elo
// Created on: <17-Oct-2000 13:50:26 ce>
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
$ini =& eZINI::instance( 'site.ini' );

$Language = $ini->variable( "eZForumMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );
// include_once( "ezforum/classes/ezforumcategory.php" );

$t = new eZTemplate( "kernel/ezforum/user/" . $ini->variable( "eZForumMain", "TemplateDir" ),
                     "kernel/ezforum/user/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( Array( "categorylist" => "categorylist.tpl" ) );

$t->set_block( "categorylist", "category_item_tpl", "category_item" );

$category = new eZForumCategory();
$categoryList = $category->getAllCategories();
if ( !$categoryList )
{
    $languageIni = new eZINI( "kernel/ezforum/user/intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $languageIni->variable( "strings", "noitem" );

    $t->set_var( "next", "" );
    $t->set_var( "previous", "" );
    $t->set_var( "category_item", $noitem );
}
else
{
    $i=0;
    foreach ( $categoryList as $categoryItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bgdark" );
        else
            $t->set_var( "td_class", "bglight" );
        
        $t->set_var( "category_id", $categoryItem->id() );
        $t->set_var( "category_name", $categoryItem->name() );
        $t->set_var( "category_description", $categoryItem->description() );
        $i++;
        
        $t->parse( "category_item", "category_item_tpl", true);
    }
} 

$t->pparse( "output", "categorylist" );

?>