<?php
// 
// $Id: menubox.php 6209 2001-07-19 12:36:31Z jakobn $
//
// Created on: <23-Mar-2001 10:57:04 fh>
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
// include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->variable( "eZBulkMailMain", "Language" );
    
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdb.php" );

$t = new eZTemplate( "kernel/ezbulkmail/user/" . $ini->variable( "eZBulkMailMain", "TemplateDir" ),
                     "kernel/ezbulkmail/user/intl", $Language, "menubox.php" );

$t->setAllStrings();
$t->set_var( "sitedesign", $GlobalSiteDesign );    

$t->set_file( array(
    "menu_box_tpl" => "menubox.tpl"
    ) );

$t->set_block( "menu_box_tpl", "normal_list_tpl", "normal_list" );
$t->set_block( "menu_box_tpl", "single_list_tpl", "single_list" );
$t->set_var( "normal_list", "" );
$t->set_var( "single_list", "" );

$category = eZBulkMailCategory::singleList( false );
if( $category == false )
{
    $t->parse( "normal_list", "normal_list_tpl" );
}
else
{
    $t->parse( "single_list", "single_list_tpl" );
} 

$t->pparse( "output", "menu_box_tpl" );

?>