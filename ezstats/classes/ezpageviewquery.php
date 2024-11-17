<?php
//
// $Id: ezpageviewquery.php 9901 2004-07-08 16:15:25Z br $
//
// Definition of eZPageViewQuery class
//
// Created on: <04-Jan-2001 18:00:08 bf>
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

//!! eZStats
//! The eZPageViewQuery handled queries on the stored page views.
/*!
  \sa eZPageView
*/

/*!TODO
 */

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );
include_once( "classes/ezdate.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezstats/classes/ezpageview.php" );

class eZPageViewQuery
{
    /*!
      Constructs a new eZPageViewQuery object.
    */
    function __construct()
    {
    }

    /*!
      Returns the total number of pageviews.
    */
    function totalPageViews()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $pageview, "SELECT SUM(Count) AS Count FROM eZStats_Archive_PageView" );
        $ret = $pageview[0][$db->fieldName( "Count" )];
        $db->array_query( $pageview_array, "SELECT COUNT(ID) AS Count FROM eZStats_PageView" );
        $ret += $pageview_array[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns the total number of pageviews on the given date.

      returns 0 if the argument is not a eZDate object.
    */
    function totalPageViewsDay( $dayObject )
    {
        $db =& eZDB::globalDatabase();
        $ret = 0;

        if ( is_a( $dayObject, "eZDate" ) )
        {
            $year = $dayObject->year();
            $month = $dayObject->month();
            $day = $dayObject->day();

            if ( $month < 10 )
                $month = "0" . $month;

            if ( $day < 10 )
                $day = "0" . $day;

            $dateStamp = new eZDateTime( $year, $month, $day, 0, 0, 0 );

            $endStamp = new eZDateTime( $year, $month, $day, 23, 59, 59 );
            $db->array_query( $pageview_array,
            "SELECT count(ID) AS Count
             FROM eZStats_PageView
             WHERE Date > '" . $dateStamp->timeStamp() .
            "' AND Date < '" . $endStamp->timeStamp() . "' ");

            $ret = $pageview_array[0][$db->fieldName( "Count" )];

            $db->array_query( $pageview_array,
            "SELECT SUM(Count) as Count
             FROM eZStats_Archive_PageView
             WHERE Hour > '" . $dateStamp->timeStamp() .
            "' AND Hour < '" . $endStamp->timeStamp() . "' ");

            $ret = $ret + $pageview_array[0][$db->fieldName( "Count" )];
        }

        return $ret;
    }


    /*!
      Returns the total number of pageviews on the given month.

      returns 0 if the argument is not a eZDate object.
    */
    function totalPageViewsMonth( $dayObject )
    {
        $db =& eZDB::globalDatabase();
        $ret = 0;

        if ( is_a( $dayObject, "eZDate" ) )
        {
            $year = $dayObject->year();
            $month = $dayObject->month();

            if ( $month < 10 )
                $month = "0" . $month;

            $dateStamp = new eZDateTime( $year, $month, 1, 0, 0, 0 );

            if ( $month == 12 )
                $endDate = new eZDateTime( $year + 1, 1, 1, 0, 0, 0 );
            else
                $endDate = new eZDateTime( $year, $month + 1, 1, 0, 0, 0 );

            $db->array_query( $pageview_array,
            "SELECT COUNT(ID) AS Count
             FROM eZStats_PageView
             WHERE Date > '" . $dateStamp->timeStamp() .
            "' AND Date < '" . $endDate->timeStamp() . "'");

            $ret = $pageview_array[0][$db->fieldName( "Count" )];
            $db->array_query( $pageview_array,
            "SELECT SUM(Count) AS Count
             FROM eZStats_Archive_PageView
             WHERE Hour > '" . $dateStamp->timeStamp() .
            "' AND Hour < '" . $endDate->timeStamp() . "'");

            $ret = $ret + $pageview_array[0][$db->fieldName( "Count" )];
        }
        return $ret;
    }

    /*!
      \static
      Return the sum of Count BrowserType
    */
    function &sumBrowserTypeCount()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $pageview_array,
        "SELECT Count FROM eZStats_Archive_BrowserType" );

        $sum = 0;
        foreach ( $pageview_array as $pageview )
        {
            $sum += $pageview[$db->fieldName( "Count" )];
        }
        return $sum;
    }


    /*!
      \static
      Returns the latest pageviews.

      The files are returned as an array of eZPageView objects.
    */
    function &latest( $limit = 20, $offset = 0 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();

        $db->array_query( $pageview_array,
        "SELECT ID, Date FROM eZStats_PageView
         ORDER BY Date DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i < count($pageview_array); $i++ )
        {
            $return_array[$i] = new eZPageView( $pageview_array[$i][$db->fieldName( "ID" )], 0 );
        }
        return $return_array;
    }

    /*!
      \static
      Returns the latest pageview count.
    */
    function &latestCount()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $pageview_array,
        "SELECT COUNT(ID) AS Count FROM eZStats_PageView" );
        $ret = $pageview_array[0][$db->fieldName( "Count" )];

        $db->array_query( $pageview_array,
        "SELECT COUNT(ID) AS Count FROM eZStats_Archive_PageView" );
        $ret = $ret + $pageview_array[0][$db->fieldName( "Count" )];

        return $ret;
    }

    /*!
      Returns the visitors which has viewed most pages.

      The files are returned as an assiciative array of
      array( ID => $id, IP => $ip, HostName => $hostName, Count => $count ).
    */
    function &topVisitors( $limit = 20, $offset = 0 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $db->array_query( $visitor_array, "SELECT * FROM eZStats_Archive_RemoteHost ORDER BY Count DESC",
                                           array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($visitor_array); $i++ )
        {
            $id = $visitor_array[$i][$db->fieldName( "ID" )];
            $ip = (string)$visitor_array[$i][$db->fieldName( "IP" )];
            $hostName = (string)$visitor_array[$i][$db->fieldName( "HostName" )];
            $count = $visitor_array[$i][$db->fieldName( "Count" )];

            // check if the domain name is fetched, if not try to fetch it
            // and store the result in the table.
            if ( $hostName = "NULL" )
            {
                $db->begin();
                if ( $ip )
                {
                    $sendIP = $ip;
                    $hostName =& eZPageView::getHostByAddr( $sendIP );
                    $result = $db->query( "UPDATE eZStats_Archive_RemoteHost SET HostName='$hostName'
                                         WHERE ID='$id'" );
                    if ( $result == false )
                        $db->rollback( );
                    else
                        $db->commit();
                }
                else
                {
                    $hostName = "";
                }
            }
            $return_array[] = array( "ID" => (int)$id,
                                       "IP" => (string)$ip,
                                       "HostName" => (string)$hostName,
                                       "Count" => (int)$count );
        }
        return $return_array;
    }

    /*!
      Returns the number of visitors which has viewed most pages.
    */
    function &topVisitorsCount()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $db->array_query( $visitor_array,
                          "SELECT count( ID ) AS Count FROM eZStats_Archive_RemoteHost" );

        return $visitor_array[0][$db->fieldName( "Count" )];
    }

    /*!
      Returns the referers which are most frequent.

      The files are returned as an assiciative array of
      array( Domain => $domain, URI => $uri, Count => $count ).
    */
    function &topReferers( $limit = 40, $excludeDomain = "", $offset = 0 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();
        $excludeTableName = "";

        if ( $excludeDomain != "" )
        {
            $query = new eZQuery( array( "eZStats_Archive_RefererURL.Domain", "eZStats_Archive_RemoteHost.IP" ),
                                  $excludeDomain );
            $search_text =  "WHERE " . $query->buildQuery();
            $excludeTableName = ", eZStats_Archive_RemoteHost";
        }

        $db->array_query( $visitor_array,
        "SELECT eZStats_Archive_RefererURL.Domain, eZStats_Archive_RefererURL.URI , eZStats_Archive_RefererURL.Count
         FROM eZStats_Archive_RefererURL $excludeTableName $search_text GROUP BY Domain, URI ORDER BY Count DESC, URI ASC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i < count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "Domain" => $visitor_array[$i][$db->fieldName( "Domain" )],
                                       "URI" => $visitor_array[$i][$db->fieldName( "URI" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )] );
        }
        return $return_array;
    }

    /*!
      Returns the number of referers which are most frequent.
    */
    function &topReferersCount( $excludeDomain = "")
    {
        $db =& eZDB::globalDatabase();

        $search_text = "";
        $excludeTableName = "";

        if ( $excludeDomain != "" )
        {
            $query = new eZQuery( array( "eZStats_Archive_RefererURL.Domain", "eZStats_Archive_RemoteHost.IP" ),
                                  $excludeDomain );
            $search_text = "WHERE " . $query->buildQuery();
            $excludeTableName = ", eZStats_Archive_RemoteHost";
        }

        $db->array_query( $visitor_array,
        "SELECT count( eZStats_Archive_RefererURL.ID ) as ID
                FROM eZStats_Archive_RefererURL $excludeTableName
                $search_text
                GROUP BY Domain, URI" );

        return count( $visitor_array );
    }

    /*!
      Returns the browsers which are most frequent.

      The files are returned as an assiciative array of
      array( ID => $id, Name => $name, Count => $count ).
    */
    function &topBrowsers( $limit = 25, $offset = 0 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();

        $db->array_query( $visitor_array,
        "SELECT Count, Browser
         FROM eZStats_Archive_BrowserType
         GROUP BY Browser, Count
         ORDER BY Count DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i < count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i][$db->fieldName( "ID" )],
                                       "Name" => $visitor_array[$i][$db->fieldName( "Browser" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )] );
        }

        return $return_array;
    }

    /*!
      Returns the number of browsers which are most frequent.
    */
    function &topBrowsersCount()
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();

        $db->array_query( $visitor_array,
        "SELECT count(ID) AS Count, Browser FROM eZStats_Archive_BrowserType GROUP BY Browser" );

        return $visitor_array[0][ $db->fieldName( "Count" )];
    }

    /*!
      Returns the requests which is most frequent.

      The files are returned as an assiciative array of
      array( ID => $id, URI => $uri, Count => $count ).
    */
    function &topRequests( $limit = 20, $offset = 0 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $db->array_query( $visitor_array,
        "SELECT Count, URI, Month
         FROM eZStats_Archive_RequestedPage
         ORDER BY Count DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i < count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i][$db->fieldName( "ID" )],
                                       "URI" => $visitor_array[$i][$db->fieldName( "URI" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )],
                                       "Month" => $visitor_array[$i][$db->fieldName( "Month" )] );
        }
        return $return_array;
    }

    /*!
      Returns the number of requests which are most frequent.
    */
    function &topRequestsCount()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $visitor_array,
        "SELECT count(ID) AS Count
         FROM eZStats_Archive_RequestedPage
         GROUP BY ID" );

        return count( $visitor_array );
    }

    /*!
      Returns the most frequent viewed products.
    */
    function &topProductRequests( $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();

        $db->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         AND eZStats_RequestPage.URI LIKE '/trade/productview/%'
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i][$db->fieldName( "ID" )],
                                       "URI" => $visitor_array[$i][$db->fieldName( "URI" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )] );
        }

        return $return_array;
    }

    /*!
      Returns the most frequent products added to the cart.
    */
    function &topProductAddToCart( $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();

        $db->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         AND eZStats_RequestPage.URI LIKE '/trade/cart/add/%'
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i][$db->fieldName( "ID" )],
                                       "URI" => $visitor_array[$i][$db->fieldName( "URI" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )] );
        }
        return $return_array;
    }

    /*!
      Returns the most frequent products added to the wishlist.
    */
    function &topProductAddToWishlist( $limit=20 )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();

        $db->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         AND eZStats_RequestPage.URI LIKE '/trade/wishlist/add/%'
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC",
        array( "Limit" => $limit,
               "Offset" => $offset ) );

        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i][$db->fieldName( "ID" )],
                                       "URI" => $visitor_array[$i][$db->fieldName( "URI" )],
                                       "Count" => $visitor_array[$i][$db->fieldName( "Count" )] );
        }

        return $return_array;
    }

    /*!
      Returns the statistics for one year.

      Returns an array of months with the statistics as an associative array:
      array( "Months" => array( "Count" => $count )
    */
    function &yearStats( $year )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();
        $month_array = array();

        $TotalPages = 0;
        // loop over the days
        for ( $month = 1; $month <= 12; $month++ )
        {
            $stamp = new eZDateTime( $year, $month, 1, 0, 0, 0 );
            if ( $smonth == 12 )
                $end = new eZDateTime( $year + 1, 1, 1, 0, 0, 0 );
            else
                $end = new eZDateTime( $year, $month + 1, 1, 0, 0, 0 );

            $db->array_query( $visitor_array,
            "SELECT SUM(Count) AS Count FROM eZStats_Archive_PageView
             WHERE Hour > '" . $stamp->timeStamp() . "' AND Hour < '" .
            $end->timeStamp() . "'" );

            $TotalPages += $visitor_array[0][$db->fieldName( "Count" )];
            $month_array[] = array( "Count" => $visitor_array[0][$db->fieldName( "Count" )] );
        }
        $now = getdate();
        $return_array = array( "TotalPages" => $TotalPages,
                               "PagesPrMonth" => round( $TotalPages/max( $now["mon"], 1 ) ),
                               "Months" => $month_array );
        return $return_array;
    }

    /*!
      Returns the statistics for one month.

      Returns an array of days with the statistics as an associative array:
      array( "Days" => array( "Count" => $count )
    */
    function &monthStats( $year, $month )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();
        $day_array = array();

        $date = new eZDate( $year, $month, 1 );

        if ( $month < 10 )
            $month = "0" . $month;

        $TotalPages = 0;
        // loop over the days
        for ( $day = 1; $day <= $date->daysInMonth(); $day++ )
        {
            if ( $day < 10 )
                $sday = "0" . $day;
            else
                $sday = $day;

            $stamp = new eZDateTime( $year, $month, $sday, 0, 0 ,0 );
            if ( $sday == $date->daysInMonth() )
                $end = new eZDateTime( $year, $month + 1, 1, 0, 0, 0 );
            else
                $end = new eZDateTime( $year, $month, $sday + 1, 0, 0, 0 );

            $db->array_query( $visitor_array,
            "SELECT SUM(Count) AS Count FROM eZStats_Archive_PageView
             WHERE Hour > '" . $stamp->timeStamp() . "' AND Hour < '" .
            $end->timeStamp() . "'" );

            $TotalPages += $visitor_array[0][$db->fieldName( "Count" )];
            $day_array[] = array( "Count" => $visitor_array[0][$db->fieldName( "Count" )] );
        }
        $now = getdate();
        $return_array = array( "TotalPages" => $TotalPages,
                               "PagesPrDay" => round( $TotalPages/max( $now["mday"], 1 ) ),
                               "Days" => $day_array );

        return $return_array;
    }

    /*!
      Returns the statistics for one month.

      Returns an array of hours with the statistics as an associative array:
      array( "Hours" => array( "Count" => $count )
    */
    function &dayStats( $year, $month, $day )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();
        $hour_array = array();

        $TotalPages = 0;
        // loop over the days
        for ( $hour = 0; $hour <= 23; ++$hour )
        {
            $stampStart = new eZDateTime( $year, $month, $day, $hour, 0, 0 );
            $stampEnd = new eZDateTime( $year, $month, $day, $hour, 59, 59 );
            $db->array_query( $visitor_array,
            "SELECT Count FROM eZStats_Archive_PageView WHERE
                               Hour>='" . $stampStart->timeStamp() . "' AND
                               Hour<='" . $stampEnd->timeStamp()  . "'" );

            $TotalPages += $visitor_array[0][$db->fieldName( "Count" )];
            $hour_array[] = array( "Count" => $visitor_array[0][$db->fieldName( "Count" )] );
        }
        $now = getdate();
        $return_array = array( "TotalPages" => $TotalPages,
                               "PagesPrHour" => round( $TotalPages/max( $now["hours"], 1 ) ),
                               "Hours" => $hour_array );

        return $return_array;
    }

    /*!
      Returns the top exit pages.
    */
    function &topExitPage( )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();

        $db->array_query( $visitor_array,
        "SELECT Hit.ID, Page.URI, Page.ID AS PageID, Hit.RemoteHostID, Concat( Year(Hit.Date), DayOfYear(Hit.Date)) AS Date
         FROM eZStats_PageView AS Hit, eZStats_RequestPage AS Page
         WHERE Hit.RequestPageID=Page.ID
         ORDER BY Hit.RemoteHostID, Hit.Date ASC" );

        foreach ( $visitor_array as $visit )
        {
            $idx = $visit["Date"] . $visit["RemoteHostID"];

            $return_array[$idx] = $visit["PageID"];

        }

        return $return_array;

    }

    /*!
      Returns the top entry pages.
    */
    function &topEntryPage( )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $visitor_array = array();

        $db->array_query( $visitor_array,
        "SELECT Hit.ID, Page.URI, Page.ID AS PageID, Hit.RemoteHostID, Concat( Year(Hit.Date), DayOfYear(Hit.Date)) AS Date
         FROM eZStats_PageView AS Hit, eZStats_RequestPage AS Page
         WHERE Hit.RequestPageID=Page.ID
         ORDER BY Hit.RemoteHostID, Hit.Date DESC" );

        foreach ( $visitor_array as $visit )
        {
            $idx = $visit["Date"] . $visit["RemoteHostID"];

            $return_array[$idx] = $visit["PageID"];

        }

        return $return_array;

    }
}

?>