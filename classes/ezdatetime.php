<?php
//
// $Id: ezdatetime.php 7544 2001-09-28 06:29:32Z jhe $
//
// Definition of eZDateTime class
//
// Created on: <07-Sep-2000 15:20:51 bf>
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

//!! eZCommon
//! The eZDateTime class provides date and functions.
/*!
  Example:
  \code
  //Create a new eZDateTime object, and set the date and time from a MySQL date time.
  $datetime = new eZDateTime();
  $datetime->setMySQLDateTime( "2000-10-07 16:45:32" );

  // print the date and time in localized format
  print( "Date and time:" . $locale->format( $datetime ) . "<br>" );

  // print the day and month names in localized format
  print( "Day:" . $locale->dayName( $datetime->dayName( $locale->mondayFirst() ) ) . "<br>" );
  print( "Month:" . $locale->monthName( $datetime->monthName() ) . "<br>" );
  \endcode
  \sa eZDate eZTime eZLocale
*/

/*!TODO
*/

include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );

class eZDateTime
{
    /*!
      Constructs a new eZDateTime object. If the parameters are set the date and
      time is set accordingly. If not the current local time and date are used.
    */
    function __construct( $year=-1, $month=-1, $day=-1, $hour=-1, $minute=-1, $second=-1 )
    {
        if ( ( $year == -1 ) && ( $month == -1 ) && ( $day == -1 ) && ( $hour == -1 ) && ( $minute == -1 ) && ( $second == -1 ) )
        {
            $now = getdate();
            $this->Date = new eZDate( $now[ "year" ], $now[ "mon" ], $now[ "mday" ] );
            $this->Time = new eZTime( $now[ "hours" ], $now[ "minutes" ], $now[ "seconds" ] );
        }
        else
        {
            $this->Date = new eZDate( $year, $month, $day );
            $this->Time = new eZTime( $hour, $minute, $second );
        }
    }

    /*!
      The year is returned in Y2K compatible format.
    */
    function year()
    {
        return $this->Date->year();
    }

    /*!
      The month value is returned.
    */
    function month()
    {
        return $this->Date->month();
    }

    /*!
      Returns the day of the month.
    */
    function day()
    {
        return $this->Date->day();
    }

    /*!
      Returns the hours.
    */
    function hour()
    {
        return $this->Time->hour();
    }

    /*!
      Returns the minutes.
    */
    function minute()
    {
        return $this->Time->minute();
    }

    /*!
      Returns the seconds.
    */
    function second()
    {
        return $this->Time->second();
    }

    /*!
      Get the current datetime in MySQL format.
    */
    function mySQLDateTime( )
    {
        if ( $this->month() < "10" )
        {
            $month = ( "0" . $this->month() );
        }
        else
        {
            $month = $this->month();
        }

        if ( $this->day() < "10" )
        {
            $day = ( "0" . $this->day() );
        }
        else
        {
            $day = $this->day();
        }

        if ( $this->hour() < "10" )
        {
            $hour = ( "0" . $this->hour() );
        }
        else
        {
            $hour = $this->hour();
        }

        if ( $this->minute() < "10" )
        {
            $minute = ( "0" . $this->minute() );
        }
        else
        {
            $minute = $this->minute();
        }

        if ( $this->second() < "10" )
        {
            $second = ( "0" . $this->second() );
        }
        else
        {
            $second = $this->second();
        }
        $current = ( $this->year() . "-" .  $month . "-" . $day . " " .  $hour . ":" . $minute . ":" . $second );
        return $current;
    }

    /*!
      Sets the year value.
    */
    function setYear( $value )
    {
        $this->Date->setYear( $value );
    }

    /*!
      Sets the month value.
    */
    function setMonth( $value )
    {
        $this->Date->setMonth( $value );
    }

    /*!
      Sets the day value.
    */
    function setDay( $value )
    {
        $this->Date->setDay( $value );
    }

    /*!
      Sets the Hour value.
    */
    function setHour( $value )
    {
        $this->Time->setHour( $value );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $this->Time->setMinute( $value );
    }

    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $this->Time->setSecond( $value );
    }

    /*!
      Sets the number of seconds elapsed since midnight.
    */
    function setSecondsElapsed( $value )
    {
        $this->Time->setSecondsElapsed( $value );
    }

    /*!
      Sets the number of seconds elapsed since midnight, user HH MM SS
    */
    function setSecondsElapsedHMS( $hour, $minute, $second )
    {
        $this->Time->setSecondsElapsedHMS( $hour, $minute, $second );
    }

    /*!
      Returns the date component of the date time object
      as an eZDate object.
    */
    function &date()
    {
        return $this->Date;
    }

    /*!
      Returns the time component of the date time object
      as an eZTime object.
    */
    function &time()
    {
        return $this->Time;
    }

    /*!
      Sets the data according to the MySQL date given as paramenter.
      If the paramenter is invalid nothing is set and an error is printed.
    */
    function setMySQLDateTime( $value )
    {
        if ( preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }
        else
        {
            print( "<b>Error:</b> eZDateTime::setMySQLDateTime() received wrong MySQL datetime format." );
        }
    }

    /*!
      Sets the data according to the MySQL timestamp given as parameter.
    */
    function setMySQLTimeStamp( $value )
    {
        if ( preg_match( "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }
        else
        {
            print( "<b>Error:</b> eZDateTime::setMySQLTimeStamp() received wrong MySQL timestamp format." );
        }
    }

    /*!
      Sets the data according to the UNIX timestamp given as argument.
    */
    function setTimeStamp( $value )
    {
        $formattedTime = date('YmdHis', $value );

        if ( preg_match( "/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", $formattedTime, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }
        else
        {
            print( "<b>Error:</b> eZDateTime::setMySQLTimeStamp() received wrong MySQL timestamp format." );
        }

    }

    /*!
      \static
      Returns the timestamp as a UNIX timestamp.

      If returnNow is set to true a timestamp of the current time is returned.
    */
    static public function timeStamp( $returnNow=false )
    {
        if ( $returnNow == true )
            return time();
        else
        {
            /* return mktime( $this->hour(), $this->minute(), $this->second(),
        $this->month(), $this->day(), $this->year() ); */
            return time();
        }

    }


    /*!
      Returns the MySQL timestamp equivalent to the date and time stored
      in the object.
    */
    function mysqlTimeStamp()
    {
        $year = $this->year();

        $month = $this->addZero( $this->month() );
        $day = $this->addZero( $this->day() );

        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );

        return $year . $month . $day . $hour . $minute . $second;
    }

    /*!
      \private
      Adds a "0" infront of the value if it's below 10.
    */
    static public function addZero( $value )
    {
        $ret = $value;
        if ( $ret < 10 )
        {
            $ret = "0". $ret;
        }

        return $ret;
    }

    /*!
      Returns the number of days in the current month.
    */
    function daysInMonth()
    {
        return $this->Date->daysInMonth();
    }

    /*!
      Returns the day of week. ( 1..7 )
      If mondayFirst is true, the week starts on Monday, else on Sunday.
    */
    function dayOfWeek( $mondayFirst )
    {
        return $this->Date->dayOfWeek( $mondayFirst );
    }

    /*!
      Returns the name of the weekday in three letters.
      If mondayFirst is true, the week starts on Monday, else on Sunday.
    */
    function dayName( $mondayFirst )
    {
        return $this->Date->dayName( $mondayFirst );
    }


    /*!
      Returns the name of the month in three letters.
    */
    function monthName( )
    {
        return $this->Date->monthName();
    }

    /*!
      Returns true if the current date is valid.
    */
    function isValid()
    {
        return $this->Date->isValid();
    }

    /*!
      Returns true if the current date equals the supplied date.
    */
    function dateEquals( $date )
    {
        return $this->Date->equals( $date );
    }

    /*!
      Returns true if the eZDateTime object given as argument is
      greater than the internal values.

      If $equal is set to true then true is returned if the datetime
      is greater than or equal.

      Returns false is the object is not a eZDateTime object.
    */
    function isGreater( &$datetime, $equal=false )
    {
        $ret = false;

        if ( is_a( $datetime, "eZDateTime" ) )
        {
            if ( $this->Date->equals( $datetime->date() ) == true )
                $ret = $this->Time->isGreater( $datetime->time(), $equal );
            else
                $ret = $this->Date->isGreater( $datetime->date(), false );
        }
        return $ret;
    }


    var $Date;
    var $Time;

}