<?php
//
// $Id: consultationedit.php 9529 2002-05-14 11:17:05Z jhe $
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
    Edit a consultation
 */

// include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );

// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// deletes the dayview cache file for a given day
function deleteCache( $SiteDesign, $language, $year, $month, $day, $userID )
{
    eZFile::unlink( "kernel/ezcalendar/user/cache/dayview.tpl-$SiteDesign-$language-$year-$month-$day-$userID.cache" );
    eZFile::unlink( "kernel/ezcalendar/user/cache/monthview.tpl-$SiteDesign-$language-$year-$month-$userID.cache" );
    eZFile::unlink( "kernel/ezcalendar/user/cache/dayview.tpl-$SiteDesign-$language-$year-$month-$day-$userID-private.cache" );
    eZFile::unlink( "kernel/ezcalendar/user/cache/monthview.tpl-$SiteDesign-$language-$year-$month-$userID-private.cache" );
}

//Adds a "0" in front of the value if it's below 10.
function addZero( $value )
{
    settype( $value, "integer" );
    $ret = $value;
    if ( $ret < 10 )
    {
        $ret = "0". $ret;
    }
    return $ret;
}

if ( isset( $new_consultation ) )
{
    unset( $new_consultation );
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/consultation/person/new" );
    exit();
}

$user =& eZUser::currentUser();
if ( !is_a( $user, "eZUser" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: contact/nopermission/consultation" );
    exit();
}

// include_once( "ezmail/classes/ezmail.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "ezcontact/classes/ezconsultation.php" );

if ( isset( $Action ) && $Action == "delete" or isset( $Delete ) )
{
    unset( $person );
    unset( $company );
    if ( isset( $ConsultationList ) )
    {
        foreach ( $ConsultationList as $consultation_id )
        {
            $consultation = new eZConsultation( $consultation_id );
            if ( !isset( $person ) and !isset( $company ) )
            {
                $person = $consultation->person( $user );
                $company = $consultation->company( $user );
            }
            $consultation->delete();
        }
        if ( is_numeric( $person ) )
        {
            $contact_type = "person";
            $contact_id = $person;
        }
        else if ( is_numeric( $company ) )
        {
            $contact_type = "company";
            $contact_id = $company;
        }
    }
    else
    {
        $contact_type = $SubAction;
        if ( $contact_type = "company" )
        {
            $contact_id = $CompanyID;
        }
        else
        {
            $contact_id = $PersonID;
        }
    }

    if ( isset( $contact_type ) && isset( $contact_id ) )
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/consultation/$contact_type/list/$contact_id" );
    }
    else
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/consultation/list" );
    }
    exit;
}

// include_once( "ezcontact/classes/ezconsultationtype.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );

$error = false;

$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl", $Language, "consultationedit.php" );
$t->setAllStrings();

$t->set_file( "consultation_edit", "consultationedit.tpl" );
$t->set_block( "consultation_edit", "consultation_item_tpl", "consultation_item" );

$t->set_block( "consultation_item_tpl", "consultation_date_item_tpl", "consultation_date_item" );
$t->set_block( "consultation_item_tpl", "group_notice_select_tpl", "group_notice_select" );
$t->set_block( "consultation_item_tpl", "day_item_tpl", "day_item" );

$t->set_block( "consultation_item_tpl", "no_status_item_tpl", "no_status_item" );
$t->set_block( "consultation_item_tpl", "status_item_tpl", "status_item" );
$t->set_block( "status_item_tpl", "status_select_tpl", "status_select" );

$t->set_block( "consultation_edit", "contact_item_tpl", "contact_item" );
$t->set_block( "contact_item_tpl", "company_contact_select_tpl", "company_contact_select" );
$t->set_block( "contact_item_tpl", "person_contact_select_tpl", "person_contact_select" );

$t->set_block( "consultation_edit", "company_contact_item_tpl", "company_contact_item" );
$t->set_block( "consultation_edit", "person_contact_item_tpl", "person_contact_item" );

$t->set_block( "consultation_edit", "hidden_company_contact_item_tpl", "hidden_company_contact_item" );
$t->set_block( "consultation_edit", "hidden_person_contact_item_tpl", "hidden_person_contact_item" );

$t->set_block( "consultation_edit", "person_id_item_tpl", "person_id_item" );
$t->set_block( "consultation_edit", "company_id_item_tpl", "company_id_item" );

$t->set_block( "consultation_edit", "errors_tpl", "errors_item" );

$t->set_block( "errors_tpl", "error_company_person_item_tpl", "error_company_person_item" );
$t->set_block( "errors_tpl", "error_no_company_person_item_tpl", "error_no_company_person_item" );
$t->set_block( "errors_tpl", "error_no_status_item_tpl", "error_no_status_item" );
$t->set_block( "errors_tpl", "error_short_description_item_tpl", "error_short_description_item" );
$t->set_block( "errors_tpl", "error_description_item_tpl", "error_description_item" );
$t->set_block( "errors_tpl", "error_email_notice_item_tpl", "error_email_notice_item" );

$t->set_var( "consultation_date", "" );
$t->set_var( "consultation_date_item", "" );

$t->set_var( "short_description", "" );
$t->set_var( "description", "" );
$t->set_var( "email_notification", "" );

$t->set_var( "group_notice_id", "" );
$t->set_var( "is_selected", "" );
$t->set_var( "group_notice_name", "" );

$t->set_var( "consultant_type", "" );
$t->set_var( "person_id_item", "" );
$t->set_var( "company_id_item", "" );




if ( isset( $PersonID ) and !isset( $PersonContact ) )
    $PersonContact = $PersonID;
if ( isset( $CompanyID ) and !isset( $CompanyContact ) )
    $CompanyContact = $CompanyID;

if ( isset( $PersonID ) )
{
    $t->set_var( "consultant_type", "person/" );
    $t->set_var( "person_id", $PersonID );
    $t->parse( "person_id_item", "person_id_item_tpl" );
}
else if ( isset( $CompanyID ) )
{
    $t->set_var( "consultant_type", "company/" );
    $t->set_var( "company_id", $CompanyID );
    $t->parse( "company_id_item", "company_id_item_tpl" );
}

$t->set_var( "hidden_company_contact_item", "" );
$t->set_var( "hidden_person_contact_item", "" );

$t->set_var( "state_id", "" );

if ( $Action == "insert" || $Action == "update" )
{
    $t->set_var( "error_company_person_item", "" );
    $t->set_var( "error_no_company_person_item", "" );
    $t->set_var( "error_no_status_item", "" );
    $t->set_var( "error_short_description_item", "" );
    $t->set_var( "error_description_item", "" );
    $t->set_var( "error_email_notice_item", "" );

    if ( isset( $PersonContact ) && isset( $CompanyContact ) )
    {
        $t->parse( "error_company_person_item", "error_company_person_item_tpl" );
        $error = true;
    }

    if ( !isset( $PersonContact ) && !isset( $CompanyContact ) )
    {
        $t->parse( "error_no_company_person_item", "error_no_company_person_item_tpl" );
        $error = true;
    }

    if ( $StatusID == -1 )
    {
        $t->parse( "error_no_status_item", "error_no_status_item_tpl" );
        $error = true;
    }

    if ( empty( $ShortDescription ) )
    {
        $t->parse( "error_short_description_item", "error_short_description_item_tpl" );
        $error = true;
    }

    if ( empty( $Description ) )
    {
        $t->parse( "error_description_item", "error_description_item_tpl" );
        $error = true;
    }

//      if ( empty( $EmailNotice ) )
//      {
//          $t->parse( "error_email_notification_item", "error_email_notificiation_item_tpl" );
//          $error = true;
//      }

//      if ( empty( $GroupNotice[] ) )
//      {
//          $t->parse( "error_group_notification_item", "error_group_notification_item_tpl" );
//          $error = true;
//      }

    if ( $error == true )
    {
        $t->parse( "errors_item", "errors_tpl" );
   }
}

if ( $error == false )
{
    $t->set_var( "errors_item", "" );
}
else
{
    $Action = "formdata";
}

$user =& eZUser::currentUser();

if ( !$user )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /user/login" );
    exit();
}

$userID = $user->ID();

if ( ( isset( $Action ) && $Action == "insert" || isset( $Action ) && $Action == "update" ) && $error == false )
{
    if ( isset( $ConsultationID ) && $ConsultationID != 'new' && $ConsultationID > 0 )
    {
        $consultation = new eZConsultation( $ConsultationID );
        $oldDate = $consultation->date();
        if( !is_null( $oldDate ) )
        {
            deleteCache( "default", $Language, $oldDate->year(), addZero( $oldDate->month() ), addZero( $oldDate->day() ), $userID );
            deleteCache( "default", $Language, $ConsultationYear, addZero( $ConsultationMonth ), addZero( $ConsultationDay ), $userID );
        }
    }
    else
    {
        $consultation = new eZConsultation();
    }
    $consultation->setShortDescription( $ShortDescription );
    $consultation->setDescription( $Description );
    $consultation->setDate( new eZDateTime( $ConsultationYear, $ConsultationMonth, $ConsultationDay ) );
    $consultation->setState( $StatusID );
    $consultation->setEmail( $EmailNotice );
    $consultation->store();

    if ( isset( $CompanyContact ) )
    {
        $contact_type = "company";
        $contact_id = $CompanyContact;
        $consultation->removeConsultationFromCompany( $CompanyContact, $user->id() );
        $consultation->addConsultationToCompany( $CompanyContact, $user->id() );
    }
    else if ( isset( $PersonContact ) )
    {
        $contact_type = "person";
        $contact_id = $PersonContact;
        $consultation->removeConsultationFromPerson( $PersonContact, $user->id() );
        $consultation->addConsultationToPerson( $PersonContact, $user->id() );
    }

    $consultation->removeGroups();
    foreach ( $GroupNotice as $group )
    {
        $consultation->addGroup( $group );
    }

    $ConsultationID = $consultation->id();

    $t->set_var( "consultation_id", $ConsultationID );

    $consult_id = $ConsultationID . "-" . $user->id();

    if ( isset( $CompanyContact ) )
    {
        $company = new eZCompany( $CompanyContact );
        $consult_name = $company->name();
        $consult_id .= "-" . $CompanyContact;
    }
    else if ( isset( $PersonContact ) )
    {
        $person = new eZPerson( $PersonContact );
        $consult_name = $person->name();
        $consult_id .= "-" . $PersonContact;
    }

    $mail_t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                              "kernel/ezcontact/admin/intl", $Language, "consultationmail" );
    $mail_t->setAllStrings();

    $mail_t->set_file( array( "consultation_mail" => "consultationmail.tpl" ) );
    $mail_t->set_block( "consultation_mail", "subject_tpl", "subject" );
    $mail_t->set_block( "consultation_mail", "body_tpl", "body" );

    $stateid = $consultation->state();
    $state = new eZConsultationType( $stateid );

    $mail_t->set_var( "consultation_name", $consult_name );
    $mail_t->set_var( "short_description", $consultation->shortDescription() );
    $mail_t->set_var( "type_name", $state->name() );
    $mail_t->set_var( "description", $consultation->description() );
    $mail_t->set_var( "consultation_id", $consult_id );
    $mail_t->set_var( "signature", $user->signature() );

    $mail = new eZMail();
    $mail->setFromName( $user->name() );
    $mail->setFrom( $user->email() );

    $mail_t->parse( "subject", "subject_tpl" );
    $mail_t->parse( "body", "body_tpl" );

    $mail->setSubject( $mail_t->get_var( "subject" ) );
    $mail->setBody( $mail_t->get_var( "body" ) );

    $emails = $consultation->emailList();
    foreach ( $emails as $email )
    {
        $mail->setTo( $email );
        $mail->send();
    }

    foreach ( $GroupNotice as $groupid )
    {
        $users = eZUserGroup::users( $groupid );
        foreach ( $users as $mail_user )
        {
            $mail->setTo( $mail_user->namedEmail() );
            $mail->send();
        }
    }

    if ( isset( $contact_type ) && isset( $contact_id ) )
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/consultation/$contact_type/list/$contact_id" );
    }
    else
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/consultation/list" );
    }
    exit();
}

/*
    The user wants to create a new consultation.

    We present an empty form.
 */
if ( isset( $Action ) && $Action == "new" )
{
    if ( $ConsultationID != 0 ) // 1
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/consultation/edit/$ConsultationID" );
        exit();
    }

    if ( isset( $CompanyID ) )
    {
        $t->set_var( "company_contact", $CompanyID );
        $t->parse( "hidden_company_contact_item", "hidden_company_contact_item_tpl" );
        $t->set_var( "hidden_person_contact_item", "" );
    }
    else if ( isset( $PersonID ) )
    {
        $t->set_var( "person_contact", $PersonID );
        $t->parse( "hidden_person_contact_item", "hidden_person_contact_item_tpl" );
        $t->set_var( "hidden_company_contact_item", "" );
    }
    else
    {
        $t->set_var( "hidden_company_contact_item", "" );
        $t->set_var( "hidden_person_contact_item", "" );
    }

    $currentDate = new eZDateTime();
    for ( $i = 1; $i <= 31; $i++ )
    {
        $t->set_var( "day_id", $i );
        $t->set_var( "day_value", $i );
        if ( $currentDate->day() == $i )
            $t->set_var( "day_selected", "selected" );
        else
            $t->set_var( "day_selected", "" );

        $t->parse( "day_item", "day_item_tpl", true );
    }

    if ( $currentDate->month() == 1 )
        $t->set_var( "select_january", "selected" );
    else
        $t->set_var( "select_january", "" );

    if ( $currentDate->month() == 2 )
        $t->set_var( "select_february", "selected" );
    else
        $t->set_var( "select_february", "" );

    if ( $currentDate->month() == 3 )
        $t->set_var( "select_march", "selected" );
    else
        $t->set_var( "select_march", "" );

    if ( $currentDate->month() == 4 )
        $t->set_var( "select_april", "selected" );
    else
        $t->set_var( "select_april", "" );

    if ( $currentDate->month() == 5 )
        $t->set_var( "select_may", "selected" );
    else
        $t->set_var( "select_may", "" );

    if ( $currentDate->month() == 6 )
        $t->set_var( "select_june", "selected" );
    else
        $t->set_var( "select_june", "" );

    if ( $currentDate->month() == 7 )
        $t->set_var( "select_july", "selected" );
    else
        $t->set_var( "select_july", "" );

    if ( $currentDate->month() == 8 )
        $t->set_var( "select_august", "selected" );
    else
        $t->set_var( "select_august", "" );

    if ( $currentDate->month() == 9 )
        $t->set_var( "select_september", "selected" );
    else
        $t->set_var( "select_september", "" );

    if ( $currentDate->month() == 10 )
        $t->set_var( "select_october", "selected" );
    else
        $t->set_var( "select_october", "" );

    if ( $currentDate->month() == 11 )
        $t->set_var( "select_november", "selected" );
    else
        $t->set_var( "select_november", "" );

    if ( $currentDate->month() == 12 )
        $t->set_var( "select_december", "selected" );
    else
        $t->set_var( "select_december", "" );

    $t->set_var( "consultationyear", $currentDate->year() );

    $Action_value = "insert";
    $t->set_var( "consultation_id", "0" );
    $t->parse( "consultation_item", "consultation_item_tpl" );
}

$status_id = 0;
$groups = array();

/*
    The user wants to edit an existing person.

    We present a form with the info.
 */
if ( isset( $Action ) && $Action == "edit" )
{
    $Action_value = "update";
    $consultation = new eZConsultation( $ConsultationID );

    $t->set_var( "short_description", $consultation->shortDescription() );
    $t->set_var( "description", $consultation->description() );
    $t->set_var( "email_notification", $consultation->emails() );
    $status_id = $consultation->state();

    $companyid = $consultation->company( $user->id() );
    $personid = $consultation->person( $user->id() );
    if ( $companyid )
    {
        $CompanyID = $companyid;
        $t->set_var( "company_contact", $CompanyID );
        $t->parse( "hidden_company_contact_item", "hidden_company_contact_item_tpl" );
        $t->set_var( "hidden_person_contact_item", "" );
    }
    else if ( $personid )
    {
        $PersonID = $personid;
        $t->set_var( "person_contact", $PersonID );
        $t->parse( "hidden_person_contact_item", "hidden_person_contact_item_tpl" );
        $t->set_var( "hidden_company_contact_item", "" );
    }

    $currentDate = $consultation->date();
    if( $currentDate == null )
        $currentDate = new eZDateTime();

    if( $currentDate != null )
    {
        for ( $i = 1; $i <= 31; $i++ )
        {
            $t->set_var( "day_id", $i );
            $t->set_var( "day_value", $i );
            if ( $currentDate->day() == $i )
                $t->set_var( "day_selected", "selected" );
            else
                $t->set_var( "day_selected", "" );

            $t->parse( "day_item", "day_item_tpl", true );
        }

        if ( $currentDate->month() == 1 )
            $t->set_var( "select_january", "selected" );
        else
            $t->set_var( "select_january", "" );

        if ( $currentDate->month() == 2 )
            $t->set_var( "select_february", "selected" );
        else
            $t->set_var( "select_february", "" );

        if ( $currentDate->month() == 3 )
            $t->set_var( "select_march", "selected" );
        else
            $t->set_var( "select_march", "" );

        if ( $currentDate->month() == 4 )
            $t->set_var( "select_april", "selected" );
        else
            $t->set_var( "select_april", "" );

        if ( $currentDate->month() == 5 )
            $t->set_var( "select_may", "selected" );
        else
            $t->set_var( "select_may", "" );

        if ( $currentDate->month() == 6 )
            $t->set_var( "select_june", "selected" );
        else
            $t->set_var( "select_june", "" );

        if ( $currentDate->month() == 7 )
            $t->set_var( "select_july", "selected" );
        else
            $t->set_var( "select_july", "" );

        if ( $currentDate->month() == 8 )
            $t->set_var( "select_august", "selected" );
        else
            $t->set_var( "select_august", "" );

        if ( $currentDate->month() == 9 )
            $t->set_var( "select_september", "selected" );
        else
            $t->set_var( "select_september", "" );

        if ( $currentDate->month() == 10 )
            $t->set_var( "select_october", "selected" );
        else
            $t->set_var( "select_october", "" );

        if ( $currentDate->month() == 11 )
            $t->set_var( "select_november", "selected" );
        else
            $t->set_var( "select_november", "" );

        if ( $currentDate->month() == 12 )
            $t->set_var( "select_december", "selected" );
        else
            $t->set_var( "select_december", "" );

        $t->set_var( "consultationyear", $currentDate->year() );
    }

    $groups = $consultation->groupIDList();

    $t->set_var( "consultation_id", $ConsultationID );

    $t->parse( "consultation_item", "consultation_item_tpl" );
}

if ( isset( $Action ) && $Action == "formdata" )
{
    if( !isset( $ConsultationID ) or $ConsultationID == "new" )
        $ConsultationID = 0;
    $Action_value = "insert";
    $t->set_var( "consultation_id", $ConsultationID );
    $t->set_var( "short_description", $ShortDescription );
    $t->set_var( "description", $Description );
    $t->set_var( "email_notification", $EmailNotice );



    for ( $i = 1; $i <= 31; $i++ )
    {
        $t->set_var( "day_id", $i );
        $t->set_var( "day_value", $i );
        if ( isset( $ConsultationDay ) && $ConsultationDay == $i )
            $t->set_var( "day_selected", "selected" );
        else
            $t->set_var( "day_selected", "" );

        $t->parse( "day_item", "day_item_tpl", true );
    }

    if ( $ConsultationMonth == 1 )
        $t->set_var( "select_january", "selected" );
    else
        $t->set_var( "select_january", "" );

    if ( $ConsultationMonth == 2 )
        $t->set_var( "select_february", "selected" );
    else
        $t->set_var( "select_february", "" );

    if ( $ConsultationMonth == 3 )
        $t->set_var( "select_march", "selected" );
    else
        $t->set_var( "select_march", "" );

    if ( $ConsultationMonth == 4 )
        $t->set_var( "select_april", "selected" );
    else
        $t->set_var( "select_april", "" );

    if ( $ConsultationMonth == 5 )
        $t->set_var( "select_may", "selected" );
    else
        $t->set_var( "select_may", "" );

    if ( $ConsultationMonth == 6 )
        $t->set_var( "select_june", "selected" );
    else
        $t->set_var( "select_june", "" );

    if ( $ConsultationMonth == 7 )
        $t->set_var( "select_july", "selected" );
    else
        $t->set_var( "select_july", "" );

    if ( $ConsultationMonth == 8 )
        $t->set_var( "select_august", "selected" );
    else
        $t->set_var( "select_august", "" );

    if ( $ConsultationMonth == 9 )
        $t->set_var( "select_september", "selected" );
    else
        $t->set_var( "select_september", "" );

    if ( $ConsultationMonth == 10 )
        $t->set_var( "select_october", "selected" );
    else
        $t->set_var( "select_october", "" );

    if ( $ConsultationMonth == 11 )
        $t->set_var( "select_november", "selected" );
    else
        $t->set_var( "select_november", "" );

    if ( $ConsultationMonth == 12 )
        $t->set_var( "select_december", "selected" );
    else
        $t->set_var( "select_december", "" );

    $t->set_var( "consultationyear", $ConsultationYear );

    $status_id = $StatusID;
    $groups = $GroupNotice;
    if ( !isset( $groups ) )
        $groups = array();

    // Group list here

    $t->parse( "consultation_item", "consultation_item_tpl" );
}

if ( !( isset( $CompanyID ) || isset( $PersonID ) ) )
{
    $company = new eZCompany();
    $companies = $company->getAll();
    if ( !is_array( $companies ) )
        $companies = array();
    foreach ( $companies as $company )
    {
        $t->set_var( "contact_id", $company->id() );
        $t->set_var( "contact_name", $company->name() );
        if ( isset( $CompanyContact ) && $CompanyContact == $company->id() )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );

        $t->parse( "company_contact_select", "company_contact_select_tpl", true );
    }

    $person = new eZPerson();
    $persons = $person->getAll();
    if ( !is_array( $persons ) )
        $persons = array();
    foreach ( $persons as $person )
    {
        $t->set_var( "contact_id", $person->id() );
        $t->set_var( "contact_firstname", $person->firstName() );
        $t->set_var( "contact_lastname", $person->lastName() );
        if ( isset( $PersonContact ) && $PersonContact == $person->id() )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        $t->parse( "person_contact_select", "person_contact_select_tpl", true );
    }

    $t->parse( "contact_item", "contact_item_tpl" );
    $t->set_var( "company_contact_item", "" );
    $t->set_var( "person_contact_item", "" );
}
else
{
    $t->set_var( "contact_item" );
    if ( isset( $CompanyID ) )
    {
        $t->parse( "company_contact_item", "company_contact_item_tpl" );
        $t->set_var( "person_contact_item", "" );
        $company = new eZCompany( $CompanyID );
        $t->set_var( "company_name", $company->name() );
    }
    else if ( isset( $PersonID ) )
    {
        $t->parse( "person_contact_item", "person_contact_item_tpl" );
        $t->set_var( "company_contact_item", "" );
        $person = new eZPerson( $PersonID );
        $t->set_var( "person_firstname", $person->firstName() );
        $t->set_var( "person_lastname", $person->lastName() );
    }
    else
    {
    }
}

// Create consultation types

$types = eZConsultationType::findTypes();
if ( count( $types ) > 0 )
{
    foreach ( $types as $type )
    {
        $t->set_var( "status_id", $type->id() );
        if ( $type->id() == $status_id )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        $t->set_var( "status_name", $type->name() );

        $t->parse( "status_select", "status_select_tpl", true );
    }
    $t->parse( "status_item", "status_item_tpl" );
    $t->set_var( "no_status_item", "" );
}
else
{
    $t->parse( "no_status_item", "no_status_item_tpl" );
    $t->set_var( "status_item", "" );
}

// Group list
$group = new eZUserGroup();
$group_list = $group->getAll();
foreach ( $group_list as $group_item )
{
    if ( in_array( $group_item->id(), $groups ) )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );
    $t->set_var( "group_notice_id", $group_item->id() );
    $t->set_var( "group_notice_name", $group_item->name() );

    $t->parse( "group_notice_select", "group_notice_select_tpl", true );
}


// Template variabler.

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "consultation_edit"  );

?>