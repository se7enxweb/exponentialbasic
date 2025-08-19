<?php
// 
// $Id: sectionlist.php 6229 2001-07-20 11:26:45Z jakobn $
//
// Created on: <10-May-2001 15:33:23 ce>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlist.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZSiteManagerMain", "Language" );
$Limit = $ini->variable( "eZSiteManagerMain", "AdminListLimit" );

// include_once( "ezsitemanager/classes/ezsection.php" );

$t = new eZTemplate( "kernel/ezsitemanager/admin/" . $ini->variable( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "kernel/ezsitemanager/admin/" . "/intl", $Language, "sectionlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "section_page" => "sectionlist.tpl"
      ) );

$t->set_block( "section_page", "section_list_tpl", "section_list" );
$t->set_block( "section_list_tpl", "section_item_tpl", "section_item" );

if ( !isset( $Offset ) )
    $Offset = 0;

$t->set_var( "site_style", $SiteDesign );
$sectionList =& eZSection::getAll( $Offset, $Limit );
$totalCount =& eZSection::count();


if ( count ( $sectionList ) > 0 )
{
    $i=0;
    foreach( $sectionList as $section )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "section_id", $section->id() );
        $t->set_var( "section_name", $section->name() );
        $t->set_var( "section_description", $section->description() );
        
        $t->parse( "section_item", "section_item_tpl", true );
        $i++;
    }
    $t->parse( "section_list", "section_list_tpl" );
}
eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "section_page" );

$t->set_var( "section_start", $Offset + 1 );
$t->set_var( "section_end", min( $Offset + $Limit, $totalCount ) );
$t->set_var( "section_total", $totalCount );

$t->pparse( "output", "section_page" );

?>