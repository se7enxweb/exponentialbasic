<?php
//
// $Id: personsearch.php 9805 2003-04-10 14:48:45Z br $
//
// Created on: <25-Jul-2001 12:43:04 jhe>
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
    Searches for companies.
*/
// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "ezcontact/classes/ezperson.php" );
//// include_once( "ezcontact/classes/ezpersontype.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZContactMain", "Language" );
$errorIni = new eZINI( "kernel/ezcontact/user/intl/" . $Language . "/personsearch.php.ini", false );

$t = new eZTemplate( "kernel/ezcontact/user/" . $ini->variable( "eZContactMain", "TemplateDir" ),
                     "kernel/ezcontact/user/intl/", $Language, "personsearch.php" );

$t->set_file( "search", "personsearch.tpl" );

$t->set_block( "search", "current_type_tpl", "current_type" );
$t->set_block( "search", "list_tpl", "list" );
$t->set_block( "search", "not_root_tpl", "not_root" );
$t->set_block( "search", "category_list_tpl", "category_list" );
$t->set_block( "search", "no_type_item_tpl", "no_type_item" );
$t->set_block( "search", "no_category_item_tpl", "no_category_item" );
$t->set_block( "search", "person_item_tpl", "person_item" );
$t->set_block( "search", "no_companies_tpl", "no_companies" );
$t->set_block( "search", "companies_table_tpl", "companies_table" );
$t->set_block( "search", "person_new_button_tpl", "person_new_button" );

$t->set_block( "companies_table_tpl", "person_stats_header_tpl", "person_stats_header" );

$t->set_block( "person_item_tpl", "person_view_button_tpl", "person_view_button" );
$t->set_block( "person_item_tpl", "no_person_view_button_tpl", "no_person_view_button" );
$t->set_block( "person_item_tpl", "person_consultation_button_tpl", "person_consultation_button" );
$t->set_block( "person_item_tpl", "person_edit_button_tpl", "person_edit_button" );
$t->set_block( "person_item_tpl", "person_delete_button_tpl", "person_delete_button" );
$t->set_block( "person_item_tpl", "person_stats_item_tpl", "person_stats_item" );

$t->set_var( "search_box", "" );
$t->set_var( "search_results", "" );
$t->set_var( "result_item", "" );
$t->set_var( "category_option", "" );
$t->set_var( "result_category", "" );
$t->set_var( "companies_table", "" );
$t->set_var( "command_type", "person" );

$t->set_var( "search_text", htmlspecialchars( $SearchText ) );
$t->set_var( "current_id", isset( $SearchCategory ) ? $SearchCategory : false );

$Action = "new";
$results = "false";

if ( isset( $SearchObject ) && $SearchObject == "person" )
{
    $Action = "search";
}

if ( !empty( $SearchText ) )
{
    $Action = "search";
}
$person = new eZPerson();
$result = false;
$personArray = array();
if ( $Action == "search" )
{
    $personArray = $person->search( $SearchText );
    $count = count( $personArray );
    if ( $count > 0 )
    {
        $results = true;
    }
}

if ( $results )
{
    $count = count( $personArray );
    $t->set_var( "results", $count );
    $i = 0;

    $can_view_stats = eZPermission::checkPermission( $user, "eZContact", "PersonStats" ) && isset( $ShowStats ) && $ShowStats;
    $t->set_var( "person_stats_header", "" );
    if ( $can_view_stats )
        $t->parse( "person_stats_header", "person_stats_header_tpl" );
    $t->set_var( "person_stats_item", "" );

    
    $t->set_var( "person_consultation_button", "" );
    $t->set_var( "person_edit_button", "" );
    $t->set_var( "person_delete_button", "" );
    $t->set_var( "person_view_button", "" );
    $t->set_var( "no_person_view_button", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
        $t->parse( "person_consultation_button", "person_consultation_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonModify" ) )
        $t->parse( "person_edit_button", "person_edit_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonDelete" ) )
        $t->parse( "person_delete_button", "person_delete_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonView" ) )
    {
        $t->parse( "person_view_button", "person_view_button_tpl" );
    }
    else
    {
        $t->parse( "no_person_view_button", "no_person_view_button_tpl" );
    }

    if ( !isset( $personList ) || isset( $personList ) && count( $personList ) == 0 )
    {
        $t->set_var( "person_item", "" );
        $t->set_var( "companies_table", "" );
        $t->parse( "no_companies", "no_companies_tpl" );
    }
    else
    {
        $can_view_stats = eZPermission::checkPermission( $user, "eZContact", "PersonStats" ) && isset( $ShowStats ) && $ShowStats;
        $t->set_var( "person_stats_header", "" );
        if ( $can_view_stats )
            $t->parse( "person_stats_header", "person_stats_header_tpl" );
        $t->set_var( "person_stats_item", "" );
        $t->set_var( "no_companies", "" );
        $t->parse( "companies_table", "companies_table_tpl" );
    }

    foreach ( $personArray as $person )
    {
        if ( $can_view_stats )
        {
            $count = $personList[$i]->totalViewCount();
            $t->set_var( "person_views", $count );
            $t->parse( "person_stats_item", "person_stats_item_tpl" );
        }

        unSet( $logoObj );
        
        $t->set_var( "no_companies", "" );
        
        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        $i++;
        $t->set_var( "person_name", $person->name() );
        $t->set_var( "person_id", $person->id() );
        $t->set_var( "item_description", $person->comment() );
        $t->set_var( "item_view_path", "/contact/person/view" );
        $t->set_var( "item_delete_path", "/contact/person/delete" );
        $t->set_var( "item_edit_path", "/contact/person/edit" );
        
        $t->set_var( "result_category", "" );
        $t->parse( "person_item", "person_item_tpl", true );
        
    }
    
    if ( $count > 0 )
    {
        $t->parse( "companies_table", "companies_table_tpl" );
    }
}

$t->parse( "list", "list_tpl" );
$t->setAllStrings();

$t->pparse( "output", "search");

?>