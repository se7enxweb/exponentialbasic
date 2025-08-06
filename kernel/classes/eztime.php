<?php
//
// $Id: eztime.php 9777 2003-02-20 08:28:46Z br $
//
// Definition of eZCompany class
//
// Created on: <08-Sep-2000 13:54:17 bf>
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
//! The eZTime class provides time functions.
/*!
  eZTime handles 24 hour time format.

  \sa eZLocale eZTimeDate eZDate
*/

class eZTime
{
    /*!
      Constructs a new eZTime object.
    */
    function __construct( $hour=-1, $minute=-1, $second=-1 )
    {
        if ( ( $hour == -1 )  && ( $minute == -1 ) && ( $second == -1 ) )
        {
            $this->SecondsElapsed = 0;
//            $now = getdate();
//            $this->setSecondsElapsedHMS( $now["hours"], $now["minutes"], $now["seconds"] );
        }
        else
        {
            $this->setSecondsElapsedHMS( $hour, $minute, $second );
        }
    }

    /*!
      Returns the hour value in 24 hour format.
    */
    function hour()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;
        $value = ( $value - $second ) / 60;

        $minute = $value % 60;
        $value = ( $value - $minute ) / 60;

        $hour = $value % 24;

        return $hour;
    }

    /*!
      Returns the minute value in 0..59.
    */
    function minute()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;
        $value = ( $value - $second ) / 60;

        $minute = $value % 60;

        return $minute;
    }

    /*!
      Return the second value in 0..59.
    */
    function second()
    {
        $value = $this->SecondsElapsed;

        $second = $value % 60;

        return $second;
    }

    /*!
      Returns the number of seconds elapsed since midnight.
    */
    function secondsElapsed()
    {
        return $this->SecondsElapsed;
    }

    /*!
      Sets the hour value.
    */
    function setHour( $value )
    {
        $value = min( $value, 23 );
        $this->SecondsElapsed = ( ( $value * 3600 ) + ( $this->minute() * 60 ) + $this->second() );
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $value = min( $value, 59 );
        $this->SecondsElapsed = ( ( $this->hour() * 3600 ) + ( $value * 20 ) + $this->second() );
    }

    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $value = min( $value, 59 );
        $this->SecondsElapsed = ( ( $this->hour() * 3600 ) + ( $this->minute() * 60 ) + $value );
    }

    /*!
      Sets the number of seconds elapsed since midnight.
    */
    function setSecondsElapsed( $value )
    {
        $this->SecondsElapsed = $value;
    }

    /*!
      Sets the number of seconds elapsed since midnight.
    */
    function setSecondsElapsedHMS( $hour, $minute, $second )
    {
        $this->SecondsElapsed = ( ( $hour * 3600 ) + ( $minute * 60 ) + $second );
        setType( $this->SecondsElapsed, "integer" );
    }

    /*!
      Adds the value of the given eZTime object to the internal time,
      and returns the result. Does not change the internal time.

      Returns false if the requested object was not an eZTime object.
    */
    function add( $time )
    {
        $ret = false;
        if ( is_a( $time, "eZTime" ) )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );

            $secondsElapsed = ( $this->SecondsElapsed + $time->secondsElapsed() ) % 86400;
            $tmpTime->setSecondsElapsed( $secondsElapsed );

            $ret = $tmpTime;
        }
        else if ( is_numeric( $time ) )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );
            $tmpTime->setSecondsElapsed( $this->secondsElapsed() + $time );

            $ret = $tmpTime;
        }

        return $ret;
    }

    /*!
      \private
      Adds a "0" infront of the value if it's below 10.
    */
    function addZero( $value )
    {
        $ret = $value;
        if ( $ret < 10 )
        {
            $ret = "0". $ret;
        }

        return $ret;
    }

    /*!
      Subtracts the value of the given eZTime object from the internal time,
      and returns the result. Does not change the internal time.

      Returns false if the requested object was not an eZTime object.
      If the given time is greater than the internal time, the subtraction will
      wrap around midnight.
    */
    function subtract( $time )
    {
        $ret = false;
        if ( is_a( $time, "eZTime" ) )
        {
            $tmpTime = new eZTime( $this->hour(), $this->minute(), $this->second() );

            $secondsElapsed = ( ( 86400 + $this->SecondsElapsed ) - $time->secondsElapsed() ) % 86400;
            $tmpTime->setSecondsElapsed( $secondsElapsed );

            $ret = $tmpTime;
        }

        return $ret;
    }

    /*!
      Returns true if the eZTime object given as argument is
      greater than the internal values.

      If $equal is set to true then true is returned if the time
      is greater than or equal.

      Returns false is the object is not a eZTime object.
    */
    function isGreater( &$time, $equal=false )
    {
        $ret = false;

        if ( is_a( $time, "eZTime" ) )
        {
            if ( $equal == false )
            {
                if ( $time->secondsElapsed() > $this->SecondsElapsed )
                {
                    $ret = true;
                }
            }
            else
            {
                if ( $time->secondsElapsed() >= $this->SecondsElapsed )
                {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    /*!
      Returns true if the eZTime object given as argument is
      equals to the internal values.

      Returns false is the object is not a eZTime object.
    */
    function equals( &$time )
    {
        $ret = false;

        if ( is_a( $time, "eZTime" ) )
        {
            if ( $time->secondsElapsed() == $this->SecondsElapsed )
            {
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Sets the time according to the MySQL time given as a
      parameter. If the value is invalid an error message is
      printed and no values are set.
    */
    function setMySQLTime( $value )
    {
        if ( preg_match( "/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $value, $valueArray ) )
        {
            $this->setSecondsElapsedHMS( $valueArray[1], $valueArray[2], $valueArray[3] );
        }
        else
        {
            print( "<b>Error:</b> eZTime::setMySQLTime() received wrong MySQL time format." );
        }
    }

    /*!
      Returns the MySQL times equivalent to the time stored
      in the object.
    */
    function mysqlTime()
    {
        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );

        return $hour . ":" . $minute . ":" . $second;
    }

    /*!
      Sets the time according to the UNIX timestamp given as argument.
    */
    function setTimeStamp( $value )
    {
        $formattedTime =& date('His', $value );

        if ( preg_match( "/([0-9]{2})([0-9]{2})([0-9]{2})/", $formattedTime, $valueArray ) )
        {
            $this->setHour( min( $valueArray[1], 23 ) );
            $this->setMinute( min( $valueArray[2], 59 ) );
            $this->setSecond( min( $valueArray[3], 59 ) );
        }
        else
        {
            print( "<b>Error:</b> eZTime::setTimeStamp() received wrong time format." );
        }

    }

    /*!
      \static
      Returns the time as a UNIX timestamp.

      If returnNow is set to true a timestamp of the current time is returned.
    */
    public function timeStamp( $returnNow = false )
    {
        if ( $returnNow == true )
            return time();
        else
            return $this->hour() * 3600 + $this->minute() * 60 + $this->second();
    }

    /// Store the number of seconds since 00:00:00
    var $SecondsElapsed;
    var $Time;
}

?>