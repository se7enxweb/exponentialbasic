<?php
//
// $Id: ezlocale.php 9160 2002-02-06 13:43:20Z jhe $
//
// Definition of eZLocale class
//
// Created on: <07-Sep-2000 14:33:48 bf>
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
//! The eZLocale class provides locale functions.
/*!
  eZLocale handles locale information and formats time, date, and currency
  information to the locale format.
<p>
  The following characters are recognized in the date/time format.
<pre>
a - "am" or "pm"
A - "AM" or "PM"
d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
D - day of the week, textual, 3 letters; i.e. "Fri"
E - day of the week, textual, long; i.e. "Friday"
F - month, textual, long; i.e. "January"
g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
h - hour, 12-hour format; i.e. "01" to "12"
H - hour, 24-hour format; i.e. "00" to "23"
i - minutes; i.e. "00" to "59"
j - day of the month without leading zeros; i.e. "1" to "31"
M - month, textual, 3 letters; i.e. "Jan"
m - month; i.e. "01" to "12"
n - month without leading zeros; i.e. "1" to "12"
s - seconds; i.e. "00" to "59"
t - number of days in the given month; i.e. "28" to "31"
Y - year, 4 digits; i.e. "1999"
y - year, 2 digits; i.e. "99"
</pre>

  The following are not yet implemented.
<pre>
I (capital i) - "1" if Daylight Savings Time, "0" otherwise.
L - boolean for whether it is a leap year; i.e. "0" or "1"
T - Timezone setting of this machine; i.e. "MDT"
U - seconds since the epoch
V - The ISO 8601:1988 week number of the current year as a decimal number,
    range 01 to 53, where week 1 is the first week that has at least 4 days
    in the current year, and with Monday as the first day of the week.
w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
W - week number of the current year as a decimal number, starting with the
    first Monday as the first day of the first week.
z - day of the year; i.e. "0" to "365"
Z - timezone offset in seconds (i.e. "-43200" to "43200")
</pre>

Example:

\code
include_once( "classes/ezdate.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/eztime.php" );

$locale = new eZLocale( "no_NO" );

$date = new eZDate( 2000, 9, 2 );

$date2 = new eZDate( );
$date2->setMySQLDate( "2000-12-02" );

$time = new eZTime( 12, 2, 23 );

$currency = new eZCurrency( 4333222111.998877 );

print( "Norwegian<br>" );
print( "Localized date: " . $locale->format( $date ) . "<br>" );
print( "Localized date: " . $locale->format( $date, false ) . "<br>" );
print( "Localized date: " . $locale->format( $date2 ) . "<br>" );
print( "Localized time: " . $locale->format( $time ) . "<br>" );
print( "Localized currency: " . $locale->format( $currency ) . "<br>" );

$time->setMySQLTime( "13:37:12" );

$locale = new eZLocale( );

print( "UK English<br>" );
print( "Localized date: " . $locale->format( $date ) . "<br>" );
print( "Localized date: " . $locale->format( $date, false ) . "<br>" );
print( "Localized time: " . $locale->format( $time ) . "<br>" );
print( "Localized currency: " . $locale->format( $currency ) . "<br>" );

\endcode

\sa eZDate eZDateTime eZTime eZCurrency eZNumber
*/

include_once( "classes/INIFile.php" );

class eZLocale
{
    /*!
      Constructs a new eZLocale object. If an ISO code is given as
      an argument the regional file for that language is used. Otherwise
      the default regional settings are used.
    */
    function __construct( $iso="" )
    {
        $ini =& INIFile::globalINI();

        if ( eZFile::file_exists( "classes/locale/" . $iso . ".ini" ) )
        {
             $this->LocaleIni = new INIFile( "classes/locale/" . $iso . ".ini", false );
        }
        else
        {
             $this->LocaleIni = new INIFile( "classes/locale/en_GB.ini", false );
        }

        $this->LanguageISO =& $this->LocaleIni->read_var( "RegionalSettings", "LanguageISO" );
        $this->CurrencySymbol =& $this->LocaleIni->read_var( "RegionalSettings", "CurrencySymbol" );
        $this->DecimalSymbol =& $this->LocaleIni->read_var( "RegionalSettings", "DecimalSymbol" );
        $this->ThousandsSymbol =& $this->LocaleIni->read_var( "RegionalSettings", "ThousandsSymbol" );
        $this->FractDigits =& $this->LocaleIni->read_var( "RegionalSettings", "FractDigits" );

        $this->PositivePrefixCurrencySymbol =& $this->LocaleIni->read_var( "RegionalSettings", "PositivePrefixCurrencySymbol" );
        $this->NegativePrefixCurrencySymbol =& $this->LocaleIni->read_var( "RegionalSettings", "NegativePrefixCurrencySymbol" );

        $this->TimeFormat =& $this->LocaleIni->read_var( "RegionalSettings", "TimeFormat" );
        $this->ShortTimeFormat =& $this->LocaleIni->read_var( "RegionalSettings", "ShortTimeFormat" );
        $this->DateFormat =& $this->LocaleIni->read_var( "RegionalSettings", "DateFormat" );
        $this->ShortDateFormat =& $this->LocaleIni->read_var( "RegionalSettings", "ShortDateFormat" );
        $this->MondayFirst =& $this->LocaleIni->read_var( "RegionalSettings", "MondayFirst" );

    }

    /*!
      Returns a nicely formatted string. This function automatically finds
      the appropriate format to use based on locale information and the type
      of object passed as an argument.

      If isShort is set to false then the long version of the string is used,
      if it exists.
    */
    function &format( &$obj, $isShort = true )
    {
        $returnString = "<b>Locale error</b>: object or type not supported.";

        // TODO: implement more options for the date and time format.
        $objClass = strtolower( get_class( $obj ) );
        switch ( $objClass )
        {
            case "ezdate" :
            case "eztime" :
            case "ezdatetime" :
            {
                if ( $isShort )
                {
                    $date = $this->ShortDateFormat;
                    $time = $this->ShortTimeFormat;
                }
                else
                {
                    $date = $this->DateFormat;
                    $time = $this->TimeFormat;
                }

                // ezdate or ezdatetime
                if ( $objClass != "eztime" )
                {
                    // d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
                    $date =& str_replace( "%d", "" . $this->addZero( $obj->day() ) . "", $date );

                    // j - day of the month without leading zeros; i.e. "1" to "31"
                    $date =& str_replace( "%j", "" . $obj->day() . "", $date );

                    // D - day of the week, textual, 3 letters; i.e. "Fri"
                    $date =& str_replace( "%D", "" .
                                          $this->dayName( $obj->dayOfWeek( $this->mondayFirst() ) ) .
                                          "", $date );

                    // E - day of the week, textual, long; i.e. "Friday"
                    $date =& str_replace( "%E", "" . $this->dayName(
                        $obj->dayOfWeek( $this->mondayFirst() ), false ) . "", $date );

                    // F - month, textual, long; i.e. "January"
                    $date =& str_replace( "%F", "" . $this->monthName( $obj->month(), false ) . "", $date );

                    // M - month, textual, 3 letters; i.e. "Jan"
                    $date =& str_replace( "%M", "" . $this->monthName( $obj->month() ) . "", $date );

                    // m - month; i.e. "01" to "12"
                    $date =& str_replace( "%m", "" . $this->addZero( $obj->month() ), $date );

                    // n - month without leading zeros; i.e. "1" to "12"
                    $date =& str_replace( "%n", "" . $obj->month(), $date );

                    // t - number of days in the given month; i.e. "28" to "31"
                    $date =& str_replace( "%t", "" . $obj->daysInMonth(), $date );

                    // Y - year, 4 digits; i.e. "1999"
                    $date =& str_replace( "%Y", "" . $obj->year(), $date );

                    // y - year, 2 digits; i.e. "99"
                    $date =& str_replace( "%y", "" . substr( $obj->year(), 2 ), $date );

                    $returnString =& $date;
                }

                // eztime or ezdatetime
                if ( $objClass != "ezdate" )
                {
                    // a - "am" or "pm"
                    $time =& str_replace( "%a", "" . $this->ampm( $obj->hour(), false ) . "", $time );

                    // A - "AM" or "PM"
                    $time =& str_replace( "%A", "" . $this->ampm( $obj->hour(), true ) . "", $time );

                    // g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
                    $time =& str_replace( "%g", "" . $this->twelveHour( $obj->hour() ) . "", $time );

                    // h - hour, 12-hour format; i.e. "01" to "12"
                    $time =& str_replace( "%h", "" . $this->addZero( $this->twelveHour( $obj->hour() ) )
                                          . "", $time );

                    // G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
                    $time =& str_replace( "%G", "" . $obj->hour() . "", $time );

                    // H - hour, 24-hour format; i.e. "00" to "23"
                    $time =& str_replace( "%H", "" . $this->addZero( $obj->hour() ) . "", $time );

                    // i - minutes; i.e. "00" to "59"
                    $time =& str_replace( "%i", "" . $this->addZero( $obj->minute() ) . "", $time );

                    // s - seconds; i.e. "00" to "59"
                    $time =& str_replace( "%s", "" . $this->addZero( $obj->second() ) . "", $time );

                    $returnString =& $time;
                }

                if ( $objClass == "ezdatetime" )
                {
                    $returnString = $date . " " . $time;
                }

                break;
            }

            case "ezcurrency" :
            {
                $value = $obj->value();

                $value =& number_format( $value, $this->FractDigits, $this->DecimalSymbol, $this->ThousandsSymbol );

                if ( $obj->isNegative() )
                {
                    if ( $this->NegativePrefixCurrencySymbol == "yes" )
                    {
                        $value = "-&nbsp;" . $this->CurrencySymbol . "&nbsp;" . $value;
                    }
                    else
                    {
                        $value = "-&nbsp;" . $value . "&nbsp;" . $this->CurrencySymbol;
                    }
                }
                else
                {
                    if ( $this->PositivePrefixCurrencySymbol == "yes" )
                    {
                        $value = $this->CurrencySymbol . "&nbsp;" . $value;
                    }
                    else
                    {
                        $value = $value . "&nbsp;" . $this->CurrencySymbol;
                    }
                }

                $returnString =& $value;



                break;
            }
        }
        return $returnString;
    }

    /*!
      Returns the day name, translated to the local language.

      $day must either be a number from 1-7, or the three first letters of the day
      name in English, lowercase.
      If isShort is set to false then the complete version of the name is used,
      otherwise a three letter version is used.
    */
    function &dayName( $day, $isShort=true )
    {
        $errorString = "<b>Locale error</b>: unknown day name";
        $name = "";

        if ( $day == "unknown" )
            return $errorString;

        if ( $isShort )
            $long = "";
        else
            $long = "long";

        if ( is_numeric( $day ) == true )
        {
            switch ( $day )
            {
                case 1 :
                {
                    $day = ( $this->mondayFirst() ) ? "mon" : "sun";
                    break;
                }
                case 2 :
                {
                    $day = ( $this->mondayFirst() ) ? "tue" : "mon";
                    break;
                }
                case 3 :
                {
                    $day = ( $this->mondayFirst() ) ? "wed" : "tue";
                    break;
                }
                case 4 :
                {
                    $day = ( $this->mondayFirst() ) ? "thu" : "wed";
                    break;
                }
                case 5 :
                {
                    $day = ( $this->mondayFirst() ) ? "fri" : "thu";
                    break;
                }
                case 6 :
                {
                    $day = ( $this->mondayFirst() ) ? "sat" : "fri";
                    break;
                }
                case 7 :
                {
                    $day = ( $this->mondayFirst() ) ? "sun" : "sat";
                    break;
                }
            }
        }

        $name =& $this->LocaleIni->read_var( "RegionalSettings", $long . $day );

        if ( $name )
        {
            return $name;
        }
        else
        {
            return $errorString;
        }
    }

    /*!
      Returns the month name, translated to the local language.

      $month must either be a number from 1-12, or the three first letters of the month
      name in English, lowercase.
      If isShort is set to false then the complete version of the name is used,
      otherwise a three letter version is used.
    */
    function &monthName( $month, $isShort=true )
    {
        $errorString = "<b>Locale error</b>: unknown month name";
        $name = "";

        if ( $month == "unknown" )
            return $errorString;

        if ( $isShort )
            $long = "";
        else
            $long = "long";

        if ( is_numeric( $month ) == true )
        {
            switch ( $month )
            {
                case 1 :
                {
                    $month = "jan";
                    break;
                }
                case 2 :
                {
                    $month = "feb";
                    break;
                }
                case 3 :
                {
                    $month = "mar";
                    break;
                }
                case 4 :
                {
                    $month = "apr";
                    break;
                }
                case 5 :
                {
                    $month = "may";
                    break;
                }
                case 6 :
                {
                    $month = "jun";
                    break;
                }
                case 7 :
                {
                    $month = "jul";
                    break;
                }
                case 8 :
                {
                    $month = "aug";
                    break;
                }
                case 9 :
                {
                    $month = "sep";
                    break;
                }
                case 10 :
                {
                    $month = "oct";
                    break;
                }
                case 11 :
                {
                    $month = "nov";
                    break;
                }
                case 12 :
                {
                    $month = "dec";
                    break;
                }
            }
        }

        $name =& $this->LocaleIni->read_var( "RegionalSettings", $long . $month );

        if ( $name == false )
        {
            return $errorString;
        }
        else
        {
            return $name;
        }
    }

    function formatNumber( $value )
    {
        if ( is_numeric( $value ) )
        {
            if ( $value == round( $value ) )
                $value =& number_format( $value, 0, $this->DecimalSymbol, $this->ThousandsSymbol );
            else
                $value =& number_format( $value, $this->FractDigits, $this->DecimalSymbol, $this->ThousandsSymbol );
        }
        return $value;
    }

    /*!
      Returns true if monday is the first day of the week, else false.
    */
    function mondayFirst()
    {
        if ( $this->MondayFirst == "yes" )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
      Returns the ISO code of the current language, or false if it is not specified.
      (it should be)
    */
    function languageISO()
    {
        if ( $this->LanguageISO != "" )
            return $this->LanguageISO;
        else
            return false;
    }

    /*!
      \private
      Adds a "0" in front of the value if it's below 10.
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
      Sets the currency symbol.
    */
    function setSymbol( $value )
    {
        $this->CurrencySymbol = $value;
    }

    /*!
      Sets both negative and possitive prefix symbol.
    */
    function setPrefixSymbol( $value )
    {
        if ( $value == true )
        {
            $this->PositivePrefixCurrencySymbol = "yes";
            $this->NegativePrefixCurrencySymbol = "yes";
        }
        else
        {
            $this->PositivePrefixCurrencySymbol = "no";
            $this->NegativePrefixCurrencySymbol = "no";
        }
    }

    /*!
      Returns AM or PM based on the supplied hour value.
      $hour must be between 0 and 23.
      If $uppercase is true, the string will be returned in upper case, else in lower case.
    */
    function ampm( $hour, $uppercase = true )
    {
        if ( $hour < 12 )
            $ret = "AM";
        else
            $ret = "PM";

        if ( $uppercase == false )
            return strtolower( $ret );
        else
            return $ret;
    }

    /*!
      Returns the hour of the day in 12-hour format.
      $hour must be between 0 and 23.
    */
    function twelveHour( $hour )
    {
        if ( $hour == 0 )
            return 12;
        else if ( $hour > 12 )
            return $hour % 12;
        else
            return $hour;
    }


    var $PositivePrefixCurrencySymbol;
    var $NegativePrefixCurrencySymbol;

    var $LanguageISO;
    var $CurrencySymbol;
    var $DecimalSymbol;
    var $ThousandsSymbol;
    var $FractDigits;
    var $TimeFormat;
    var $ShortTimeFormat;
    var $DateFormat;
    var $ShortDateFormat;
    var $MondayFirst;

    var $LocaleIni;
}


?>
