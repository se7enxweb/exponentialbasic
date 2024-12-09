<?php
//
// $Id: ezdate.php 7544 2001-09-28 06:29:32Z jhe $
//
// Definition of eZCompany class
//
// Created on: <06-Sep-2000 16:20:20 bf>
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
//! The eZDate class provides date functions.
/*!
  Example:
  \code
  // Create new eZDate objects.
  $date = new eZDate( 2000, 9, 2 );
  $date2 = new eZDate( );
  $date2->setMySQLDate( "2000-12-02" );

  // print out the current date
  print( $date->year() . " " . $date->month() . " " . $date->day() );
  \endcode
  \sa eZDateTime eZTime eZLocale
*/

class eZDate
{
    /*!
      Constructs a new eZDate object. If the parameters are set the date
      is set accordingly. If not the current local time is used.
    */
    function __construct( $year = 0, $month = 0, $day = 0, $year_add = 0, $month_add = 0, $day_add = 0 )
    {
        if ( is_a( $year, "eZDate" ) )
        {
            $this->setYear( $year->year() );
            $this->setMonth( $year->month() );
            $this->setDay( $year->day() );
        }
        else if ( ( $year == 0 )  && ( $month == 0 ) && ( $day == 0 ) )
        {
            $now = getdate();
            $this->setYear( $now["year"] );
            $this->setMonth( $now["mon"] );
            $this->setDay( $now["mday"] );
        }
        else
        {
            $this->setYear( $year );
            $this->setMonth( $month );
            $this->setDay( $day );
        }
        $this->move( $year_add, $month_add, $day_add );
    }

    /*!
      The year is returned in Y2K compatible format.
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
      Sets the day value;
    */
    function setDay( $value )
    {
        $this->Day = $value;
        setType( $this->Day, "integer" );
    }

    /*!
      Sets the date according to the MySQL date given as parameter.
      If the parameter is invalid nothing is set and an error is printed.
    */
    function setMySQLDate( $value )
    {
        if ( preg_match( "/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $value, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
        }
        else
        {
            print( "<b>Error:</b> eZDate::setMySQLDate() received wrong MySQL date format." );
        }
    }

    /*!
        Returns the date formatted for mySQL...
     */
    function mySQLDate()
    {
        $return = $this->Year;
        $return = $return . "-" . $this->Month;
        $return = $return . "-" . $this->Day;

        return $return;
    }

    /*!
      Sets the data according to the UNIX timestamp given as argument.
    */
    function setTimeStamp( $value )
    {
        $formattedTime =& date('Ymd', $value );

        if ( preg_match( "/([0-9]{4})([0-9]{2})([0-9]{2})/", $formattedTime, $valueArray ) )
        {
            $this->setYear( $valueArray[1] );
            $this->setMonth( $valueArray[2] );
            $this->setDay( $valueArray[3] );
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
            return time();
            // return mktime( 0, 0, 0,
            //               $this->month(), $this->day(), $this->year() );
    }


    /*!
      Returns the number of days in the current month.
    */
    function daysInMonth()
    {
        $lastday = mktime( 2, 0, 0, $this->Month + 1, 0, $this->Year );

        return date( "%d", $lastday );
    }

    /*!
      Returns the day of week. ( 1..7 )
      If mondayFirst is true, the week starts on Monday, else on Sunday.
    */
    function dayOfWeek( $mondayFirst = true )
    {
        $weekday = date( "w", mktime( 1, 0, 0, $this->Month, $this->Day, $this->Year ) );

        if ( $mondayFirst == true )
        {
            if ( $weekday == 0 )
                $weekday = 7;
        }
        else
        {
            $weekday = $weekday + 1;
        }

        return $weekday;
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
      Returns the name of the weekday in three letters.
      If mondayFirst is true, the week starts on Monday, else on Sunday.
    */
    function dayName( $mondayFirst )
    {
        $day = "unknown";

        if ( $mondayFirst == true )
        {
            switch( $this->dayOfWeek( $mondayFirst ) )
            {
                case 1 :
                {
                    $day = "mon";
                }
                break;

                case 2 :
                {
                    $day = "tue";
                }
                break;

                case 3 :
                {
                    $day = "wed";
                }
                break;

                case 4 :
                {
                    $day = "thu";
                }
                break;

                case 5 :
                {
                    $day = "fri";
                }
                break;

                case 6 :
                {
                    $day = "sat";
                }
                break;

                case 7 :
                {
                    $day = "sun";
                }
                break;
            }
        }
        else
        {
            switch( $this->dayOfWeek( $mondayFirst ) )
            {
                case 1 :
                {
                    $day = "sun";
                }
                break;

                case 2 :
                {
                    $day = "mon";
                }
                break;

                case 3 :
                {
                    $day = "tue";
                }
                break;

                case 4 :
                {
                    $day = "wed";
                }
                break;

                case 5 :
                {
                    $day = "thu";
                }
                break;

                case 6 :
                {
                    $day = "fri";
                }
                break;

                case 7 :
                {
                    $day = "sat";
                }
                break;
            }
        }

        return $day;
    }

    /*!
      Returns the name of the month in three letters.
    */
    function monthName( )
    {
        $month = "unknown";

        switch( $this->Month )
        {
            case 1 :
            {
                $month = "jan";
            }
            break;

            case 2 :
            {
                $month = "feb";
            }
            break;

            case 3 :
            {
                $month = "mar";
            }
            break;

            case 4 :
            {
                $month = "apr";
            }
            break;

            case 5 :
            {
                $month = "may";
            }
            break;

            case 6 :
            {
                $month = "jun";
            }
            break;

            case 7 :
            {
                $month = "jul";
            }
            break;

            case 8 :
            {
                $month = "aug";
            }
            break;

            case 9 :
            {
                $month = "sep";
            }
            break;

            case 10 :
            {
                $month = "oct";
            }
            break;

            case 11 :
            {
                $month = "nov";
            }
            break;

            case 12 :
            {
                $month = "dec";
            }
            break;
        }
        return $month;
    }

    /*!
      Returns true if the current date is valid.
    */
    function isValid()
    {
        return checkdate( $this->Month(), $this->Day(), $this->Year() );
    }

    /*!
      Returns true if the current date equals the supplied date.
    */
    function equals( $date )
    {
        if ( $this->Year == $date->year() &&
             $this->Month == $date->month() &&
             $this->Day == $date->day() )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
      Returns true if the eZDate object given as argument is
      greater than the internal values.

      If $equal is set to true then true is returned if the date
      is greater than or equal.

      Returns false is the object is not a eZDate object.
    */
    function isGreater( &$date, $equal=false )
    {
        $ret = false;

        if ( is_a( $date, "eZDate" ) )
        {
            if ( $date->year() < $this->Year )
                $ret = false;
            else if ( $date->year() > $this->Year )
                $ret = true;
            else
            {
                if ( $date->month() < $this->Month )
                    $ret = false;
                else if ( $date->month() > $this->Month )
                    $ret = true;
                else
                {
                    if ( $equal == false )
                    {
                        if ( $date->day() > $this->Day )
                        {
                            $ret = true;
                        }
                    }
                    else
                    {
                        if ( $date->day() >= $this->Day )
                        {
                            $ret = true;
                        }
                    }
                }
            }
        }
        return $ret;
    }

    /*!
      Moves the current date n days, m months and o years forward, or backward if negative.
    */
    function move( $year_num, $month_num, $day_num )
    {
        $date = getdate( mktime( 0, 0, 0,
                                 $this->Month + $month_num,
                                 $this->Day + $day_num,
                                 $this->Year + $year_num ) );
        $this->Year = $date["year"];
        $this->Month = $date["mon"];
        $this->Day = $date["mday"];
    }

    var $Year;
    var $Month;
    var $Day;
}
?>