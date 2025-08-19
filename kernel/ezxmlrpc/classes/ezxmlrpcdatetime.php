<?php
// 
// $Id: ezxmlrpcdatetime.php 2835 2001-01-25 09:23:53Z bf $
//
// Definition of eZXMLRPCDateTime class
//
// B�rd Farstad <bf@ez.no>
// Created on: <17-Dec-2000 17:28:13 bf>
//
// This source file is part of Exponential Basic, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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


//!! eZXMLRPC
//! eZXMLRPCDatetime hadles encoding and decoding of an XML-RPC iso8601 date and time.
/*!

 */

class eZXMLRPCDatetime
{
    /*!
      Creates a new eZXMLRPCDatetime object.

      If 
    */
    function __construct( $year=0, $month=0, $day=0, $hour=0, $minute=0, $second=0  )
    {
        if ( ( $year == 0 )  && ( $month == 0 ) && ( $day == 0 ) && ( $hour == 0 ) && ( $minute == 0 ) && ( $second == 0 ) )
        {
            $now = getdate();
            $this->Year = $now[ "year" ];
            $this->Month = $now[ "mon" ];
            $this->Day = $now[ "mday" ];
            $this->Hour = $now[ "hours" ];
            $this->Minute = $now[ "minutes" ];
            $this->Second = $now[ "seconds" ];
        }
        else
        {        
            $this->setYear( $year );
            $this->setMonth( $month );
            $this->setDay( $day );
            $this->setHour( $hour );
            $this->setMinute( $minute );
            $this->setSecond( $second );
        }
    }

    /*!
      This function will encode the datetime into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $year = $this->year();
        $month = $this->addZero( $this->month() );
        $day = $this->addZero( $this->day() );
        
        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );                
        
        $value = $year . $month . $day . "T" . $hour . ":" . $minute . ":" . $second;
        
        $ret = "<value>";
        $ret .= "<dateTime.iso8601>";
        $ret .= $value;
        $ret .= "</dateTime.iso8601>";
        $ret .= "</value>";

        return $ret;             
    }
    
    /*!
      Returns the string value.

      The parts can also be fetched directly by use of year(), month()...
    */
    function value()
    {
        $year = $this->year();
        $month = $this->addZero( $this->month() );
        $day = $this->addZero( $this->day() );
        
        $hour = $this->addZero( $this->hour() );
        $minute = $this->addZero( $this->minute() );
        $second = $this->addZero( $this->second() );                
        
        $value = $year . "-" .  $month . "-"  . $day . " " . $hour . ":" . $minute . ":" . $second;
        
        return $value;
    }
    
    /*!
      The year is returned.
    */
    function year()
    {
        return $this->Year;
    }

    /*!
      The month value is returned.      
    */
    function month()
    {
        return $this->Month;
    }

    /*!
      Returns the day of the month.
    */
    function day()
    {
        return $this->Day;
    }

    /*!
      Returns the hours.
    */
    function hour()
    {
        return $this->Hour;
    }

    /*!
      Returns the minutes.
    */
    function minute()
    {
        return $this->Minute;
    }


    /*!
      Returns the seconds.
    */
    function second()
    {
        return $this->Second;
    }
    

    /*!
      Sets the year value.
    */
    function setYear( $value )
    {
        $this->Year = $value;
        setType( $this->Year, "integer" );
    }

    /*!
      Sets the month value.
    */
    function setMonth( $value )
    {
        $this->Month = $value;
        setType( $this->Month, "integer" );
    }

    /*!
      Sets the day value.
    */
    function setDay( $value )
    {
        $this->Day = $value;
        setType( $this->Day, "integer" );
    }

    /*!
      Sets the Hour value.
    */
    function setHour( $value )
    {
        $this->Hour = $value;
        setType( $this->Hour, "integer" );
    }

    /*!
      Sets the minute value.
    */
    function setMinute( $value )
    {
        $this->Minute = $value;
        setType( $this->Minute, "integer" );
    }

    /*!
      Sets the second value.
    */
    function setSecond( $value )
    {
        $this->Second = $value;
        setType( $this->Second, "integer" );
    }
    

    /*!
      Decodes the datetime encoded string and sets the internal value.
    */
    function decode( $value )
    {
        if ( ereg( "([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
            $this->setHour( $valueArray[4] );
            $this->setMinute( $valueArray[5] );
            $this->setSecond( $valueArray[6] );
        }        
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
    
    
    var $Year;
    var $Month;
    var $Day;
    var $Hour;
    var $Minute;
    var $Second;
    
}

?>