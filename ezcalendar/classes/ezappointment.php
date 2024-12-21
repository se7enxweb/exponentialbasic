<?php
//
// $Id: ezappointment.php 7215 2001-09-14 12:21:35Z jhe $
//
// Definition of eZAppointment class
//
// Created on: <03-Jan-2001 16:05:37 bf>
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

//!! eZCalendar
//! eZAppointment handles appointments.
/*!

*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztime.php" );

class eZAppointment
{
    /*!
      Constructs a new eZAppointment object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id = -1 )
    {
        $this->AllDay = 0;
        $this->EMailNotice = 0;

        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZAppointment object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZCalendar_Appointment" );
            $this->ID = $db->nextID( "eZCalendar_Appointment", "ID" );
            $query = "INSERT INTO eZCalendar_Appointment
  		                          (ID,
                                   UserID,
                                   Name,
                                   Description,
                                   Date,
                                   Duration,
                                   EMailNotice,
                                   IsPrivate,
                                   Priority,
                                   AppointmentTypeID,
                                   AllDay)
                                  VALUES
                                  ('$this->ID',
                                   '$this->UserID',
		                           '$this->Name',
                                   '$this->Description',
                                   '$this->Date',
                                   '$this->Duration',
                                   '$this->EMailNotice',
                                   '$this->IsPrivate',
                                   '$this->Priority',
                                   '$this->AppointmentTypeID',
                                   '$this->AllDay')";
            $res[] = $db->query( $query );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZCalendar_Appointment SET
		                          UserID='$this->UserID',
		                          Name='$this->Name',
                                  Description='$this->Description',
                                  Date='$this->Date',
                                  Duration='$this->Duration',
                                  EMailNotice='$this->EMailNotice',
                                  IsPrivate='$this->IsPrivate',
                                  Priority='$this->Priority',
                                  AppointmentTypeID='$this->AppointmentTypeID',
                                  AllDay='$this->AllDay'
                                  WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Deletes a eZAppontment object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        if ( isSet( $this->ID ) )
        {
            $res[] = $db->query( "DELETE FROM eZCalendar_Appointment WHERE ID='$this->ID'" );
        }
        eZDB::finish( $res, $db );
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id != "" )
        {
            $db->array_query( $appointment_array, "SELECT * FROM eZCalendar_Appointment WHERE ID='$id'" );
            if ( count( $appointment_array ) > 1 )
            {
                die( "Error: Appointment's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $appointment_array ) == 1 )
            {
                $this->ID =& $appointment_array[0][$db->fieldName( "ID" )];
                $this->Name =& $appointment_array[0][$db->fieldName( "Name" )];
                $this->Description =& $appointment_array[0][$db->fieldName( "Description" )];
                $this->AppointmentTypeID =& $appointment_array[0][$db->fieldName( "AppointmentTypeID" )];
                $this->Date =& $appointment_array[0][$db->fieldName( "Date" )];
                $this->Duration =& $appointment_array[0][$db->fieldName( "Duration" )];
                $this->IsPrivate =& $appointment_array[0][$db->fieldName( "IsPrivate" )];
                $this->UserID =& $appointment_array[0][$db->fieldName( "UserID" )];
                $this->Priority =& $appointment_array[0][$db->fieldName( "Priority" )];
                $this->AllDay =& $appointment_array[0][$db->fieldName( "AllDay" )];
            }
        }
    }

    /*!
      Returns all the appointments found in the database.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $appointment_array = array();

        $db->array_query( $appointment_array, "SELECT ID FROM eZCalendar_Appointment" );

        for ( $i = 0; $i < count( $appointment_array ); $i++ )
        {
            $return_array[$i] = new eZAppointment( $appointment_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }

    /*!
      Returns the appointments thats belongs to another user found in the database.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getAllByOthers()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $appointment_array = array();

        $db->array_query( $appointment_array,
        "SELECT ID FROM eZCalendar_Appointment WHERE ID='$id' AND IsPrivate='0'" );

        for ( $i = 0; $i < count( $appointment_array ); $i++ )
        {
            $return_array[$i] = new eZAppointment( $appointment_array[$i][$db->fieldName( "ID" )], 0 );
        }

        return $return_array;
    }


    /*!
      Returns all the appointments for the given user on the given date.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getByDate( $date, $user, $showPrivate = false )
    {
        $ret = array();

        if ( is_a( $date, "eZDate" ) && is_a( $user, "eZUser" ) )
        {
            $db =& eZDB::globalDatabase();
            $userID = $user->ID();
            $return_array = array();
            $appointment_array = array();

            $endDate = new eZDateTime();
            $endDate->setTimeStamp( strtotime('+1 day', $date->timeStamp() ) );

            if ( $showPrivate == true )
            {
                $query = "SELECT ID FROM eZCalendar_Appointment
                 WHERE Date>='" . $date->timeStamp() . "'
                 AND Date<'" . $endDate->timeStamp() . "'
                 AND IsPrivate='0' AND UserID='$userID' ORDER BY Date ASC";
                $db->array_query( $appointment_array, $query, true );
            }
            else
            {
                $query = "SELECT ID FROM eZCalendar_Appointment
                 WHERE Date>='" . $date->timeStamp() . "'
                 AND Date<'" . $endDate->timeStamp() . "'
                 AND UserID='$userID' ORDER BY Date ASC";

                $db->array_query( $appointment_array, $query );
            }

            for ( $i = 0; $i < count( $appointment_array ); $i++ )
            {
                $return_array[] = new eZAppointment( $appointment_array[$i][$db->fieldName( "ID" )], 0 );
            }

            $ret =& $return_array;
        }

        return $ret;
    }

    /*!
      Returns all the appointments for the given user.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getByUser( $user, $showPrivate=false )
    {
        $ret = array();

        if ( is_a( $user, "eZUser" ) )
        {
            $db =& eZDB::globalDatabase();

            $return_array = array();
            $appointment_array = array();

            $userID = $user->id();

            if ( $showPrivate == false )
            {
                $db->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE IsPrivate='0' AND UserID='$userID' ORDER BY Date ASC", true );
            }
            else
            {
                $db->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE UserID='$userID' ORDER BY Date ASC" );
            }

            for ( $i = 0; $i < count( $appointment_array ); $i++ )
            {
                $return_array[] = new eZAppointment( $appointment_array[$i][$db->fieldName( "ID" )], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns the object ID to the appointment. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the appointment.
    */
    function name( $htmlchars=true )
    {
        if ( $htmlchars == true )
        {
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the type description.
    */
    function description( $htmlchars = true )
    {
        if ( $htmlchars == true )
        {
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }

    /*!
      Returns the date and time of the appointment.
    */
    function dateTime()
    {
       $date = new eZDateTime();
       $date->setTimeStamp( $this->Date );

       return $date;
    }

    /*!
      Returns the duration of the appointment as an eZTime object.
    */
    function duration()
    {
       return $this->Duration;
    }

    /*!
      Returns the start time of the appointment.
    */
    function &startTime()
    {
        $date = new eZDateTime();
        $date->setTimeStamp( $this->Date );

        $time = new eZTime();
        $time->setHour( $date->hour() );
        $time->setMinute( $date->minute() );
        $time->setSecond( 0 );

        return $time;
    }

    /*!
      Returns the end time of the appointment.
    */
    function &stopTime()
    {
       $date = new eZDateTime();
       $date->setTimeStamp( $this->Date );

       $start = $this->startTime();
       $time = $start->add( $this->duration() );

       return $time;
    }

    /*!
      Returns if the appointment lasts all day
    */
    function allDay()
    {
        return $this->AllDay;
    }

    /*!
      Sets if the appointment lasts all day
    */
    function setAllDay( $value )
    {
        if ( is_bool( $value ) && $value == true )
            $value = 1;

        if ( is_bool( $value ) && $value == false )
            $value = 0;

        $this->AllDay = $value;
    }

    /*!
      Returns the priority, can be 0,1,2.
    */
    function priority()
    {
       return $this->Priority;
    }

    /*!
      Sets the appointment type.
    */
    function type()
    {
       $type = new eZAppointmentType( $this->AppointmentTypeID );
       return $type;
    }

    /*!
      Returns true if the appointment is private.
    */
    function &isPrivate()
    {
       if ( $this->IsPrivate == 0 )
           $ret = false;
       else
           $ret = true;

       return $ret;
    }

    /*!
      Sets the name of the appointment.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }


    /*!
      Sets the description of the appointment.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the priority of the appointment.
    */
    function setPriority( $value )
    {
        $this->Priority = $value;
    }

    /*!
      Sets the appointment date and time. Takes an eZDateTime object
      as argument.
    */
    function setDateTime( $dateTime )
    {
       if ( is_a( $dateTime, "eZDateTime" ) )
       {
           $this->Date = $dateTime->timeStamp();
       }
       else
       {
           echo "Error: The Parameter Passed is not of eZDateTime type!";
       }
    }

    /*!
      Sets the appointment type.
    */
    function setType( $type )
    {
       if ( is_a( $type, "eZAppointmentType" ) )
       {
           $this->AppointmentTypeID = $type->id();
       }
    }

    /*!
      Sets the appointment owner.
    */
    function setOwner( $user )
    {
       if ( is_a( $user, "eZUser" ) )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Returns the user ID of the appointment owner.
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
     Sets the appointment to private or not.
    */
    function setIsPrivate( $value )
    {
       if ( $value == true )
       {
           $this->IsPrivate = 1;
       }
       else
       {
           $this->IsPrivate = 0;
       }
    }

    /*!
      Sets the appointment duration.
    */
    function setDuration( $time )
    {
       if ( is_numeric( $time ) )
       {
           $this->Duration = $time;
       }
    }

    var $ID;
    var $Name;
    var $Description;
    var $AllDay;
    var $UserID;
    var $Date;
    var $Duration;
    var $AppointmentTypeID;
    var $EMailNotice;

    /// boolean stored as an int
    var $IsPrivate;

    /// The priority of the appointment, values can be 0, 1, 2 where 1 is normal.
    var $Priority;
}

?>