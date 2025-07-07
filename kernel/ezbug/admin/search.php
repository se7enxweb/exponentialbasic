<?php
// 
// $Id: search.php 8504 2001-11-19 09:46:46Z jhe $
//
// Created on: <04-Dec-2000 10:55:51 bf>
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
// include_once( "classes/ezlocale.php" );

// include_once( "ezbug/classes/ezbugcategory.php" );
// include_once( "ezbug/classes/ezbugmodule.php" );
// include_once( "ezbug/classes/ezbug.php" );

// include_once( "ezuser/classes/ezuser.php" );

$ini = INIFile::globalINI();

$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "kernel/ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "kernel/ezbug/admin/intl/", $Language, "search.php" );

$t->setAllStrings();

$t->set_file( array(
    "bug_list_page_tpl" => "search.tpl"
    ) );


// bug
$t->set_block( "bug_list_page_tpl", "bug_list_tpl", "bug_list" );
$t->set_block( "bug_list_tpl", "bug_item_tpl", "bug_item" );
$t->set_block( "bug_item_tpl", "bug_is_closed_tpl", "bug_is_closed" );
$t->set_block( "bug_item_tpl", "bug_is_open_tpl", "bug_is_open" );


// bugs
$bug = new eZBug();
$bugList = $bug->search( $SearchText );

$t->set_var( "query_text", $SearchText );

$locale = new eZLocale( $Language );
$i=0;
$t->set_var( "bug_list", "" );
foreach ( $bugList as $bug )
{
    $t->set_var( "bug_id", $bug->id() );
    $t->set_var( "bug_name", $bug->name() );

    $pri =& $bug->priority();
    $status =& $bug->status();
    
    if ( $pri )
    {    
        $t->set_var( "bug_priority", $pri->name() );
    }
    else
    {
        $t->set_var( "bug_priority", "" );
    }

    if ( $status )
    {    
        $t->set_var( "bug_status", $status->name() );
    }
    else
    {
        $t->set_var( "bug_status", "" );
    }

    if ( $bug->isClosed() == true )
    {
        $t->parse( "bug_is_closed", "bug_is_closed_tpl" );
        $t->set_var( "bug_is_open", "" );
    }
    else
    {
        $t->set_var( "bug_is_closed", "" );
        $t->parse( "bug_is_open", "bug_is_open_tpl" );
    }
    
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->parse( "bug_item", "bug_item_tpl", true );
    $i++;
}

if ( count( $bugList ) > 0 )    
    $t->parse( "bug_list", "bug_list_tpl" );
else
    $t->set_var( "bug_list", "" );

$t->pparse( "output", "bug_list_page_tpl" );

?>