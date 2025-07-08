<?php
//
// $Id: consultationlist.php 9529 2002-05-14 11:17:05Z jhe $
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

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$ini =& INIFile::globalINI();

$user =& eZUser::currentUser();
if ( is_a( $user, "eZUser" ) &&
     eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
{
    // include_once( "ezcontact/classes/ezconsultation.php" );
    // include_once( "ezcontact/classes/ezcompany.php" );
    // include_once( "ezcontact/classes/ezperson.php" );

    // include_once( "classes/INIFile.php" );
    $max = $ini->read_var( "eZContactMain", "LastConsultations" );
    if ( !is_numeric( $max ) )
    {
        $max = 5;
    }

    $view_company = eZPermission::checkPermission( $user, "eZContact", "CompanyView" );
    $view_person = eZPermission::checkPermission( $user, "eZContact", "PersonView" );

    // include_once( "classes/INIFile.php" );

    $Language = $ini->read_var( "eZContactMain", "Language" );

    // include_once( "classes/eztemplate.php" );

    $t = new eZTemplate( "kernel/ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                         "kernel/ezcontact/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( "consultation_tpl", "consultation.tpl" );

    $t->set_block( "consultation_tpl", "last_consultations_item_tpl", "last_consultations_item" );
    $t->set_block( "last_consultations_item_tpl", "consultation_item_tpl", "consultation_item" );

    $t->set_block( "consultation_item_tpl", "consultation_person_item_tpl", "consultation_person_item" );
    $t->set_block( "consultation_item_tpl", "consultation_no_person_item_tpl", "consultation_no_person_item" );
    $t->set_block( "consultation_item_tpl", "consultation_company_item_tpl", "consultation_company_item" );
    $t->set_block( "consultation_item_tpl", "consultation_no_company_item_tpl", "consultation_no_company_item" );

    $t->set_var( "consultation_item", "" );
    $t->set_var( "last_consultations_item", "" );

    if ( $ini->read_var( "eZContactMain", "ShowAllConsultations" ) == "enabled" )
    {
        if ( $ini->read_var( "eZContactMain", "ShowRelatedConsultations" ) == "enabled" )
            $consultations = eZConsultation::findLatestConsultations( -1, $max, "ID", true, 0, -1, true );
        else
            $consultations = eZConsultation::findLatestConsultations( -1, $max );
    }
    else
    {
        if ( $ini->read_var( "eZContactMain", "ShowRelatedConsultations" ) == "enabled" )
            $consultations = eZConsultation::findLatestConsultations( $user->id(), $max, "ID", true, 0, -1, true );
        else
            $consultations = eZConsultation::findLatestConsultations( $user->id(), $max );
    }

    foreach ( $consultations as $consultation )
    {
        $t->set_var( "consultation_desc", $consultation->shortDescription() );
        $t->set_var( "consultation_id", $consultation->id() );
        $company_id = $consultation->company( $user->id() );
        $t->set_var( "consultation_company_item", "" );
        $t->set_var( "consultation_no_company_item", "" );
        $t->set_var( "consultation_person_item", "" );
        $t->set_var( "consultation_no_person_item", "" );
        if ( $company_id )
        {
            $company = new eZCompany( $company_id );
            $t->set_var( "contact_name", $company->name() );
            $t->set_var( "company_id", $company->id() );
            if ( $view_company )
                $t->parse( "consultation_company_item", "consultation_company_item_tpl" );
            else
                $t->parse( "consultation_no_company_item", "consultation_no_company_item_tpl" );
        }
        else
        {
            $person_id = $consultation->person( $user->id() );
            if ( $person_id )
            {
                $person = new eZPerson( $person_id );
                $t->set_var( "person_id", $person->id() );
                $t->set_var( "contact_lastname", $person->lastName() );
                $t->set_var( "contact_firstname", $person->firstName() );
                if ( $view_person )
                    $t->parse( "consultation_person_item", "consultation_person_item_tpl" );
                else
                    $t->parse( "consultation_no_person_item", "consultation_no_person_item_tpl" );
            }
            else
            {
            }
        }
        $t->parse( "consultation_item", "consultation_item_tpl", true );
    }

    if ( count( $consultations ) > 0 )
    {
        $t->parse( "last_consultations_item", "last_consultations_item_tpl" );
    }

    $t->pparse( "output", "consultation_tpl" );
}

?>