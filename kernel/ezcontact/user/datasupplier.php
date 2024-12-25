<?php
//
// $Id: datasupplier.php 7816 2001-10-12 12:27:35Z jhe $
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

// include_once( "classes/ezuritool.php" );

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZContactMain", "DefaultSection" );

$url_array = eZURITool::split( $REQUEST_URI );
$url_array_count = count( $url_array );

for( $i = $url_array_count; $i <= 25; $i++ )
{
    $url_array[$i] = false;
}

if ( is_object( $user ) )
{
    $UserID = $user->id();
}

if ( $UserID > 0 )
{
    $Add_User = false;
}
else
{
    $Add_User = true;
}

switch ( $url_array[2] )
{
    case "nopermission":
    {
        $Type = $url_array[3];
        switch ( $Type )
        {
            case "company":
            {
                $Action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
                break;
            }
            case "category":
            {
                $Action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
                break;
            }
            case "person":
            {
                $Action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
                break;
            }
            case "login":
            case "consultation":
            {
                include( "kernel/ezcontact/admin/nopermission.php" );
                break;
            }
            case "type":
            {
                $Action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
                break;
            }
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "search":
    {
        $SearchType = $url_array[3];
        switch ( $SearchType )
        {
            case "company":
            {
                include( "kernel/ezcontact/user/companysearch.php" );
                break;
            }
            case "person":
            {
                include( "kernel/ezcontact/user/personsearch.php" );
                break;
            }
        }
        break;
    }

    case "person":
    {
        $Action = $url_array[3];
        $PersonID = $url_array[4];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                $CompanyEdit = false;
                if ( isset( $SendMail ) )
                {
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else if ( isset( $MailButton ) )
                {
                    $ContactArrayID = array( $PersonID );
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else
                {
                    if ( isset( $NewPerson ) )
                        $Action = "new";
                    include( "kernel/ezcontact/admin/personedit.php" );
                }
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Offset = $url_array[4];
                include( "kernel/ezcontact/admin/personlist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Offset = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = eZURITool::decode( $url_array[5] );
                }
                include( "kernel/ezcontact/admin/personlist.php" );
                break;
            }
            case "view":
            {
                include( "kernel/ezcontact/admin/personview.php" );
                break;
            }
            case "folder":
            {
                $item_id = $url_array[4];
                include( "kernel/ezcontact/admin/folder.php" );
                break;
            }
            case "buy":
            {
                include( "kernel/ezcontact/admin/buy.php" );
                break;
            }
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "company":
    {
        $Action = $url_array[3];
        $CompanyID = $url_array[4];
        switch ( $Action )
        {
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                $CompanyEdit = true;
                if ( isset( $SendMail ) )
                {
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else if ( isset( $MailButton ) )
                {
                    $ContactArrayID = array( $CompanyID );
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else
                {
                    if ( isset( $NewCompany ) )
                    {
                        // include_once( "classes/ezhttptool.php" );
                        eZHTTPTool::header( "Location: /contact/company/new/$CompanyID" );
                        exit;
                    }
                    if ( $Action == "new" )
                        if ( isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                            $NewCompanyCategory = $url_array[4];
//                        else if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
//                            $CompanyID = $url_array[4];
                    include( "kernel/ezcontact/admin/companyedit.php" );
                }
                break;
            }
            case "view":
            {
                if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $CompanyID = $url_array[4];
                $PersonOffset = $url_array[5];
                // include_once( "ezcontact/classes/ezcompany.php" );
                eZCompany::addViewHit( $CompanyID );
                include( "kernel/ezcontact/admin/companyview.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                $Offset = $url_array[5];
                $ShowStats = false;
                include( "kernel/ezcontact/admin/companytypelist.php" );
                break;
            }
            case "folder":
            {
                $item_id = $url_array[4];
                $CompanyEdit = true;
                include( "kernel/ezcontact/admin/folder.php" );
                break;
            }
            case "buy":
            {
                include( "kernel/ezcontact/admin/buy.php" );
                break;
            }
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "companycategory" :
    {
        $TypeID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            {
                $NewParentID = $url_array[4];
                unset( $TypeID );
                include( "kernel/ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "kernel/ezcontact/admin/companytypeedit.php" );
                break;
            }
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "consultation":
    {
        if ( isset( $url_array[4] ) && ( !isset( $ConsultationID ) or !is_numeric( $ConsultationID ) ) )
            $ConsultationID = $url_array[4];
        if ( isset( $new_consultation ) )
        {
            // include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /contact/consultation/new" );
            exit();
        }
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "kernel/ezcontact/admin/consultationedit.php" );
                break;
            }
            case "view":
            {
                include( "kernel/ezcontact/admin/consultationview.php" );
                break;
            }
            case "list":
            {
                include( "kernel/ezcontact/admin/consultationlist.php" );
                break;
            }
            case "company":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                if ( !isset( $CompanyID ) or !is_numeric( $CompanyID ) )
                    $CompanyID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "kernel/ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "kernel/ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "kernel/ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
                break;
            }
            case "person":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                if ( !isset( $PersonID ) or !is_numeric( $PersonID ) )
                    $PersonID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "kernel/ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "kernel/ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "kernel/ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
                break;
            }

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    default :
        print( "<h1>Sorry, This page isn't for you. </h1>" );
        break;
}

?>