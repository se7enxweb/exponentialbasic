<?php
//
// $Id: personlist.php 9743 2002-11-21 08:52:13Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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
$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );
$Max = $ini->read_var( "eZContactMain", "MaxPersonList" );

if ( !is_numeric( $Max ) )
{
    $Max = 10;
}

include_once( "classes/eztemplate.php" );
include_once( "classes/ezuritool.php" );
include_once( "classes/ezlist.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "personedit.php" );
$t->setAllStrings();

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezprojecttype.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if ( !is_a( $user, "eZUser" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "PersonList" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/person/list" );
    exit();
}

$t->set_file( "person_page", "personlist.tpl" );
$t->set_block( "person_page", "no_persons_tpl", "no_persons" );

$t->set_block( "person_page", "person_table_tpl", "person_table" );

$t->set_block( "person_table_tpl", "person_item_tpl", "person_item" );

$t->set_block( "person_item_tpl", "person_state_tpl", "person_state" );
$t->set_block( "person_item_tpl", "no_person_state_tpl", "no_person_state" );
$t->set_block( "person_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "person_item_tpl", "person_view_button_tpl", "person_view_button" );
$t->set_block( "person_item_tpl", "no_person_view_button_tpl", "no_person_view_button" );
$t->set_block( "person_item_tpl", "person_buy_button_tpl", "person_buy_button" );
$t->set_block( "person_item_tpl", "person_folder_button_tpl", "person_folder_button" );
$t->set_block( "person_item_tpl", "person_consultation_button_tpl", "person_consultation_button" );
$t->set_block( "person_item_tpl", "person_edit_button_tpl", "person_edit_button" );
$t->set_block( "person_item_tpl", "person_delete_button_tpl", "person_delete_button" );
$t->set_block( "person_page", "person_new_button_tpl", "person_new_button" );

$t->set_block( "person_table_tpl", "person_list_tpl", "person_list" );

$t->set_block( "person_list_tpl", "person_list_previous_tpl", "person_list_previous" );
$t->set_block( "person_list_tpl", "person_list_item_list_tpl", "person_list_item_list" );
$t->set_block( "person_list_item_list_tpl", "person_list_item_tpl", "person_list_item" );
$t->set_block( "person_list_item_list_tpl", "person_list_inactive_item_tpl", "person_list_inactive_item" );
$t->set_block( "person_list_tpl", "person_list_next_tpl", "person_list_next" );
$t->set_block( "person_list_tpl", "person_list_previous_inactive_tpl", "person_list_previous_inactive" );
$t->set_block( "person_list_tpl", "person_list_next_inactive_tpl", "person_list_next_inactive" );

$t->set_var( "person_item", "" );

$session =& eZSession::globalSession();

if ( $session->fetch() != false )
{
    if ( !isSet( $LimitType ) )
    {
        if ( $session->variable( "PersonLimitType" ) == false )
            $session->setVariable( "PersonLimitType", "all" );
        $LimitType =& $session->variable( "PersonLimitType" );
    }
    else
    {
        $session->setVariable( "PersonLimitType", $LimitType );
    }
}

$t->set_var( "is_all_selected", "" );
$t->set_var( "is_without_selected", "" );
$t->set_var( "is_with_selected", "" );
switch ( $LimitType )
{
    case "all":
    {
        $t->set_var( "is_all_selected", "selected" );
        break;
    }
    case "standalone":
    default:
    {
        $t->set_var( "is_without_selected", "selected" );
        break;
    }
    case "connected":
    {
        $t->set_var( "is_with_selected", "selected" );
        break;
    }
}

$person = new eZPerson();

if ( !isSet( $Offset ) )
{
    $Offset = 0;
}
else if ( !is_numeric( $Offset ) )
{
    $Offset = 0;
}

$t->set_var( "action", $Action );

if ( !isSet( $SearchText ) )
{
    $total_persons = $person->getAllCount( "", $LimitType );
    $persons = $person->getAll( "", $Offset, $Max, $LimitType );
    $t->set_var( "search_form_text", "" );
    $t->set_var( "search_text", "" );
}
else
{
    $search_encoded = $SearchText;
    $search_encoded = eZURITool::encode( $search_encoded );
    $t->set_var( "search_form_text", $SearchText );
    $t->set_var( "search_text", $search_encoded );
    $total_persons = $person->getAllCount( $SearchText, $LimitType );
    $persons = $person->getAll( $SearchText, $Offset, $Max, $LimitType );
}

$count = count( $persons );

$t->set_var( "person_table", "" );
$t->set_var( "no_persons", "" );

$t->set_var( "person_consultation_button", "" );
$t->set_var( "person_buy_button", "" );
$t->set_var( "person_edit_button", "" );
$t->set_var( "person_delete_button", "" );
$t->set_var( "person_view_button", "" );
$t->set_var( "no_person_view_button", "" );

$t->parse( "person_folder_button", "person_folder_button_tpl" );
if ( eZPermission::checkPermission( $user, "eZContact", "Buy" ) )
    $t->parse( "person_buy_button", "person_buy_button_tpl" );
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

if ( $count == 0 )
{
    $t->parse( "no_persons", "no_persons_tpl" );
}
else
{
    for ( $i = 0; $i < $count && $i < $Max; $i++ )
    {
        $t->set_var( "bg_color", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

        $t->set_var( "person_id", $persons[$i]->id() );
        $stateid = $persons[$i]->projectState();
        $t->set_var( "state_id", $stateid );
        $t->set_var( "person_state", "" );
        $t->set_var( "no_person_state", "" );
        if ( $stateid )
        {
            $state = new eZProjectType( $stateid );
            $t->set_var( "person_state", $state->name() );
            $t->parse( "person_state", "person_state_tpl" );
        }
        else
        {
            $t->parse( "no_person_state", "no_person_state_tpl" );
        }
        $t->set_var( "person_firstname", $persons[$i]->firstName() );
        $t->set_var( "person_lastname", $persons[$i]->lastName() );

        $t->parse( "person_item", "person_item_tpl", true );
    }

    $t->parse( "person_table", "person_table_tpl" );
}

$t->set_var( "person_new_button", "" );
if ( eZPermission::checkPermission( $user, "eZContact", "PersonAdd" ) )
    $t->parse( "person_new_button", "person_new_button_tpl" );

eZList::drawNavigator( $t, $total_persons, $Max, $Offset, false,
array( "type_list" => "person_list",
       "next" => "person_list_next",
       "next_inactive" => "person_list_next_inactive",
       "next_index" => "item_next_index",
       "previous" => "person_list_previous",
       "previous_inactive" => "person_list_previous_inactive",
       "previous_index" => "item_previous_index",
       "item" => "person_list_item",
       "item_inactive" => "person_list_inactive_item",
       "item_index" => "item_index",
       "item_list" => "person_list_item_list",
       "item_name" => "item_name" ) );

$t->pparse( "output", "person_page" );

?>
