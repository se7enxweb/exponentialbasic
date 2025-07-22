<?php
// 
// $Id: menucategorylist.php 7673 2001-10-05 12:37:22Z bf $
//
// Created on: <30-Sep-2001 15:53:38 bf>
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
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );
// include_once( "ezimagecatalogue/classes/ezimagecategory.php" );
// include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );
$ImageDir = $ini->read_var( "eZImageCatalogueMain", "ImageDir" );

$t = new eZTemplate( "kernel/ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "kernel/ezimagecatalogue/user/intl/", $Language, "menucategorylist.php" );

$t->set_file( "menu_category_list_tpl", "menucategorylist.tpl" );

$t->setAllStrings();

$t->set_block( "menu_category_list_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_tpl", "category" );

$category = new eZImageCategory( $CategoryID );

$user = eZUser::currentUser();
// Print out all the categories
$categoryList =& $category->getByParent( $category );

foreach ( $categoryList as $categoryItem )
{
    if ( eZObjectPermission::hasPermission( $categoryItem->id(), "imagecatalogue_category", "r", $user ) ||
         eZImageCategory::isOwner( $user, $categoryItem->id()) )
    {
        $t->set_var( "category_name", $categoryItem->name() );
        $t->set_var( "category_id", $categoryItem->id() );
        
        $t->parse( "category", "category_tpl", true );
    }
}

if ( count( $categoryList ) )
{
    $t->parse( "category_list", "category_list_tpl" );
}
else
{
    $t->set_var( "category_list", "" );
}


$t->pparse( "output", "menu_category_list_tpl" );

?>