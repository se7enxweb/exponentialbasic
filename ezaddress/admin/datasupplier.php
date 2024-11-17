<?php
//
// $Id: datasupplier.php 6204 2001-07-19 12:06:56Z jakobn $
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

//print $REQUEST_URI;

include_once( "classes/ezuritool.php" );

$url_array = eZURITool::split( $REQUEST_URI );
$ListType = $url_array[2];
switch ( $ListType )
{
    case "phonetype":
    {
        $PhoneTypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezaddress/admin/phonetypeedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezaddress/admin/phonetypelist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezaddress/admin/phonetypelist.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /address/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "addresstype":
    {
        $AddressTypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezaddress/admin/addresstypeedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezaddress/admin/addresstypelist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezaddress/admin/addresstypelist.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /address/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }
    
    case "onlinetype":
    {
        $Action = $url_array[3];
        $OnlineTypeID = $url_array[4];
        
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezaddress/admin/onlinetypeedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezaddress/admin/onlinetypelist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezaddress/admin/onlinetypelist.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /address/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "country":
    {
        $CountryID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezaddress/admin/countryedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezaddress/admin/countrylist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezaddress/admin/countrylist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /address/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "error":
    {
        include( "ezaddress/admin/error.php" );
        break;
    }

    default :
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /address/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
        break;
}

?>
