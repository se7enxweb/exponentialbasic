<?php
// 
// $Id: typelist.php 6220 2001-07-20 11:15:21Z jakobn $
//
// Created on: <21-Jun-2001 11:55:24 jhe>
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
$Language = $ini->read_var( "eZLinkMain", "Language" );

include_once( "ezlink/classes/ezlinkcategory.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezlinktype.php" );

if( isset( $Delete ) )
{
    foreach( $DeleteArrayID as $typeid )
    {
        $typed = new eZLinkType( $typeid );
        $typed->delete();
    }
}

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
                     "ezlink/admin/intl/", $Language, "typelist.php" );

$t->setAllStrings();

$t->set_file( "type_list_page_tpl", "typelist.tpl" );

// type
$t->set_block( "type_list_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );

$t->set_var( "site_style", $SiteDesign );

$type = new eZLinkType();

$typeList = $type->getAll();



// categories
$i=0;
$t->set_var( "type_list", "" );
foreach ( $typeList as $typeItem )
{
    $t->set_var( "type_id", $typeItem->id() );

    $t->set_var( "type_name", $typeItem->name() );


    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
    
    $t->parse( "type_item", "type_item_tpl", true );
    $i++;
}

if ( count( $typeList ) > 0 )    
    $t->parse( "type_list", "type_list_tpl" );
else
    $t->set_var( "type_list", "" );


$t->pparse( "output", "type_list_page_tpl" );

?>