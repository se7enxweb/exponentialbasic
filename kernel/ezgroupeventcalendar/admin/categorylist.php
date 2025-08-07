<?
// 
// $Id: categorylist.php,v 1.3 2001/02/19 22:13:10 gl Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
// include_once( "classes/ezlocale.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZGroupEventCalendarMain", "Language" );
/*
include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventcategory.php" );
*/
$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl/", $Language, "categorylist.php" );

$t->setAllStrings();

$t->set_file( array(
    "category_list_page_tpl" => "categorylist.tpl"
    ) );


$t->set_block( "category_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_var( "site_style", $SiteDesign );

$category = new eZGroupEventCategory();
$categoryList = $category->getTree();

$i=0;
foreach ( $categoryList as $categorySubList )
{
    $categoryItem = $categorySubList[0];
    $categoryLevel = $categorySubList[1];
    $indent = "";

    while ( $categoryLevel > 1 )
    {
        $indent = $indent . "&nbsp;&nbsp;&nbsp;";
        $categoryLevel--;
    }

    $t->set_var( "category_name", $indent . $categoryItem->name() );
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_description", $categoryItem->description() );

    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )    
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


$t->pparse( "output", "category_list_page_tpl" );

?>
