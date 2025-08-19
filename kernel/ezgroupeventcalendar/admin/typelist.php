<?
// 
// $Id: typelist.php,v 1.3 2001/02/19 22:13:10 gl Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// This source file is part of Exponential Basic, publishing software.
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

// include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
// include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );

$t = new eZTemplate( "kernel/ezgroupeventcalendar/admin/" . $ini->variable( "eZGroupEventCalendarMain", "AdminTemplateDir" ),
                     "kernel/ezgroupeventcalendar/admin/intl/", $Language, "typelist.php" );

$t->setAllStrings();

$t->set_file( array(
    "type_list_page_tpl" => "typelist.tpl"
    ) );


$t->set_block( "type_list_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );

$t->set_var( "site_style", $SiteDesign );

$type = new eZGroupEventType();
$typeList = $type->getTree();

$i=0;
foreach ( $typeList as $typeSubList )
{
    $typeItem = $typeSubList[0];
    $typeLevel = $typeSubList[1];
    $indent = "";

    while ( $typeLevel > 1 )
    {
        $indent = $indent . "&nbsp;&nbsp;&nbsp;";
        $typeLevel--;
    }

    $t->set_var( "type_name", $indent . $typeItem->name() );
    $t->set_var( "type_id", $typeItem->id() );
    $t->set_var( "type_description", $typeItem->description() );

    if ( ( $i % 2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->parse( "type_item", "type_item_tpl", true );
    $i++;
}

if ( count( $typeList ) > 0 )    
    $t->parse( "type_list", "type_list_tpl" );
else
    $t->set_var( "type_list", "" );


$t->pparse( "output", "type_list_page_tpl" );

?>
