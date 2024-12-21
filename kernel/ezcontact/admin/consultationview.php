<?php
//
// $Id: consultationview.php 9529 2002-05-14 11:17:05Z jhe $
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

/*
    View a consultation
 */

// include_once( "classes/INIFile.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztexttool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );

// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();
if ( !is_a( $user, "eZUser" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "Consultation" ) &&
     $ini->read_var( "eZContactMain", "ShowAllConsultations" ) != "enabled" )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/consultation" );
    exit();
}
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "ezcontact/classes/ezconsultation.php" );
// include_once( "ezcontact/classes/ezconsultationtype.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl", $Language, "consultationedit.php" );
$t->setAllStrings();

$t->set_file( "consultation_view", "consultationview.tpl" );
$t->set_block( "consultation_view", "consultation_item_tpl", "consultation_item" );

$t->set_block( "consultation_item_tpl", "consultation_date_item_tpl", "consultation_date_item" );
$t->set_block( "consultation_item_tpl", "group_notice_select_tpl", "group_notice_select" );
$t->set_block( "consultation_item_tpl", "no_group_notice_tpl", "no_group_notice" );

$t->set_block( "consultation_view", "company_contact_item_tpl", "company_contact_item" );
$t->set_block( "consultation_view", "person_contact_item_tpl", "person_contact_item" );

$t->set_var( "consultation_date", "" );
$t->set_var( "consultation_date_item", "" );

$t->set_var( "short_description", "" );
$t->set_var( "description", "" );
$t->set_var( "email_notification", "" );

$t->set_var( "group_notice_name", "" );

$t->set_var( "state_id", "" );

$user =& eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /user/login" );
    exit();
}

$status_id = 0;

/*
    The user wants to edit an existing person.

    We present a form with the info.
*/

if ( !eZConsultation::belongsTo( $ConsultationID, $user->id() ) &&
     $ini->read_var( "eZContactMain", "ShowAllConsultations" ) != "enabled" )
{
    print( "<h1>Sorry, This page isn't for you. </h1>" );
}
else
{
    $consultation = new eZConsultation( $ConsultationID );

    $t->set_var( "short_description", eZTextTool::htmlspecialchars( $consultation->shortDescription() ) );
    $t->set_var( "description", eZTextTool::htmlspecialchars( $consultation->description() ) );
    $t->set_var( "email_notification", eZTextTool::htmlspecialchars( $consultation->emails() ) );
    $status_id = $consultation->state();

    $companyid = $consultation->company();
    $personid = $consultation->person();
    if ( $companyid )
    {
        $t->parse( "company_contact_item", "company_contact_item_tpl" );
        $t->set_var( "person_contact_item", "" );
        $company = new eZCompany( $companyid );
        $t->set_var( "company_name", eZTextTool::htmlspecialchars( $company->name() ) );
        $owner = new eZUser( $consultation->owner( -1, false ) );
    }
    else if ( $personid )
    {
        $t->set_var( "company_contact_item", "" );
        $t->parse( "person_contact_item", "person_contact_item_tpl" );
        $person = new eZPerson( $personid );
        $t->set_var( "person_lastname", eZTextTool::htmlspecialchars( $person->lastName() ) );
        $t->set_var( "person_firstname", eZTextTool::htmlspecialchars( $person->firstName() ) );
        $owner = new eZUser( $consultation->owner( -1, true ) );
    }
    $t->set_var( "owner", $owner->name() );
    $t->set_var( "consultation_id", $ConsultationID );

    $t->parse( "consultation_item", "consultation_item_tpl" );

// Create consultation types

    $type = new eZConsultationType( $status_id );
    $t->set_var( "status_name", eZTextTool::htmlspecialchars( $type->name() ) );

// Group list
    $groups = $consultation->groupList();
    foreach ( $groups as $group )
    {
        $t->set_var( "group_notice_name", eZTextTool::htmlspecialchars( $group->name() ) );
        $t->parse( "group_notice_select", "group_notice_select_tpl", true );
    }

    if ( count( $groups ) == 0 )
    {
        $t->set_var( "group_notice_select", "" );
        $t->parse( "no_group_notice", "no_group_notice_tpl" );
    }
    else
    {
        $t->set_var( "no_group_notice", "" );
    }

// Template variabler.
    $t->set_var( "action_value", $Action_value );
    $t->pparse( "output", "consultation_view"  );
}

?>
