<?php
// 
// $Id: appointmentview.php 7697 2001-10-08 14:39:10Z jhe $
//
// Created on: <08-Jan-2001 11:53:05 bf>
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
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezdatetime.php" );
// include_once( "classes/ezdate.php" );
// include_once( "classes/eztime.php" );
// include_once( "classes/ezlocale.php" );

// include_once( "ezcalendar/classes/ezappointment.php" );
// include_once( "ezcalendar/classes/ezappointmenttype.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZCalendarMain", "Language" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "appointmentview.php" );

$t->set_file( "appointment_view_tpl", "appointmentview.tpl" );

$t->setAllStrings();

$t->set_block( "appointment_view_tpl", "error_tpl", "error" );
$t->set_block( "appointment_view_tpl", "view_tpl", "view" );
$t->set_block( "view_tpl", "public_tpl", "public" );
$t->set_block( "view_tpl", "private_tpl", "private" );
$t->set_block( "view_tpl", "low_tpl", "low" );
$t->set_block( "view_tpl", "normal_tpl", "normal" );
$t->set_block( "view_tpl", "high_tpl", "high" );

$user =& eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

$t->set_var( "view", "" );
if ( $user == false )
    $userID = false;
else
    $userID = $user->id();

if ( isset( $GetByUserID ) && $GetByUserID == false )
{
    $GetByUserID = $userID;
}

if ( ( $session->variable( "ShowOtherCalendarUsers" ) == false ) || ( isSet( $GetByUser ) ) )
{
    $session->setVariable( "ShowOtherCalendarUsers", $GetByUserID );
}

$tmpUser = new eZUser( $session->variable( "ShowOtherCalendarUsers" ) );

if ( $tmpUser->id() == $userID )
{
    $showPrivate = true;
}
else
{
    $showPrivate = false;
}

$appointment = new eZAppointment( $AppointmentID );
$foundAppointment = false;

if ( $appointment->id() > 0 )
{
    $ownerUser = new eZUser( $appointment->userID() );
    $trusteeList = array_merge( $ownerUser->trustees(), array( $ownerUser->id() ) );
    if ( in_array( $user->id(), $trusteeList ) )
        $foundAppointment = true;
}

if ( $foundAppointment == false )
{
    $t->parse( "error", "error_tpl" );
}
else
{
    $appointment = new eZAppointment( $AppointmentID );
    $appointmentType = $appointment->type();
    $datetime = $appointment->dateTime();

    $t->set_var( "appointment_id", $appointment->id() );
    $t->set_var( "appointment_title", $appointment->name() );
    $t->set_var( "appointment_type", $appointmentType->name() );
    $t->set_var( "appointment_date", $locale->format( $datetime->date() ) );
    $t->set_var( "appointment_starttime", $locale->format( $appointment->startTime(), true ) );
    $t->set_var( "appointment_stoptime", $locale->format( $appointment->stopTime(), true ) );
    $t->set_var( "appointment_description", $appointment->description() );

    $userID = $appointment->userID();
    if ( $userID != false )
    {
        $owner = new eZUser( $userID );
        $t->set_var( "appointment_owner", $owner->name() );
    }
    else
        $t->set_var( "appointment_owner", "unknown user" );

    if ( $appointment->isPrivate() == true )
    {
        $t->parse( "private", "private_tpl" );
        $t->set_var( "public", "" );
    }
    else
    {
        $t->parse( "public", "public_tpl" );
        $t->set_var( "private", "" );
    }
    switch ( $appointment->priority() )
    {
        case 0:
        {
            $t->parse( "low", "low_tpl" );
            $t->set_var( "normal", "" );
            $t->set_var( "high", "" );
        }
        break;
        case 1:
        {
            $t->parse( "normal", "normal_tpl" );
            $t->set_var( "low", "" );
            $t->set_var( "high", "" );
        }
        break;
        case 2:
        {
            $t->parse( "high", "high_tpl" );
            $t->set_var( "low", "" );
            $t->set_var( "normal", "" );
        }
        break;
    }

    $t->set_var( "error", "" );

    $t->parse( "view", "view_tpl" );
}

$t->pparse( "output", "appointment_view_tpl" );

?>