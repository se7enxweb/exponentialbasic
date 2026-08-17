<?php
//
// $Id: datasupplier.php 9529 2002-05-14 11:17:05Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
//
// This source file is part of Exponential Basic, publishing software.
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
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();
if ( !eZPermission::checkPermission( $user, "eZContact", "ModuleEdit" ) )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = eZURITool::split( $_SERVER['REQUEST_URI'] );
$url_array_count = count( $url_array );

for( $i = $url_array_count; $i <= 25; $i++ )
{
    $url_array[$i] = false;
}

$action                 = eZHTTPTool::getVar( 'Action' );
$actionValue            = eZHTTPTool::getVar( 'Action_value' );
$addressDelete          = eZHTTPTool::getVar( 'AddressDelete' );
$addressDeleteValues    = eZHTTPTool::getVar( 'AddressDeleteValues' ) ?? [];
$addressID              = eZHTTPTool::getVar( 'AddressID' );
$addressMinimum         = eZHTTPTool::getVar( 'AddressMinimum' );
$addressTypeID          = eZHTTPTool::getVar( 'AddressTypeID' );
$addressWidth           = eZHTTPTool::getVar( 'AddressWidth' );
$back                   = eZHTTPTool::getVar( 'Back' );
$birth                  = eZHTTPTool::getVar( 'Birth' );
$birthDay               = eZHTTPTool::getVar( 'BirthDay' );
$birthMonth             = eZHTTPTool::getVar( 'BirthMonth' );
$birthYear              = eZHTTPTool::getVar( 'BirthYear' );
$buyButton              = eZHTTPTool::getVar( 'BuyButton' );
$comment                = eZHTTPTool::getVar( 'Comment' );
$companyCategoryID      = eZHTTPTool::getVar( 'CompanyCategoryID' );
$companyContact         = eZHTTPTool::getVar( 'CompanyContact' );
$companyEditLogin       = eZHTTPTool::getVar( 'CompanyEditLogin' );
$companyID              = eZHTTPTool::getVar( 'CompanyID' );
$companyImageID         = eZHTTPTool::getVar( 'CompanyImageID' );
$companyNo              = eZHTTPTool::getVar( 'CompanyNo' );
$companyOrder           = eZHTTPTool::getVar( 'CompanyOrder' );
$companyViewLogin       = eZHTTPTool::getVar( 'CompanyViewLogin' );
$confirm                = eZHTTPTool::getVar( 'Confirm' );
$consultationDay        = eZHTTPTool::getVar( 'ConsultationDay' );
$consultationID         = eZHTTPTool::getVar( 'ConsultationID' );
$consultationMonth      = eZHTTPTool::getVar( 'ConsultationMonth' );
$consultationYear       = eZHTTPTool::getVar( 'ConsultationYear' );
$contactArrayID         = eZHTTPTool::getVar( 'ContactArrayID' ) ?? [];
$contactGroupID         = eZHTTPTool::getVar( 'ContactGroupID' );
$contactID              = eZHTTPTool::getVar( 'ContactID' );
$contactPersonType      = eZHTTPTool::getVar( 'ContactPersonType' );
$contactType            = eZHTTPTool::getVar( 'ContactType' );
$country                = eZHTTPTool::getVar( 'Country' );
$dateType               = eZHTTPTool::getVar( 'DateType' );
$day                    = eZHTTPTool::getVar( 'Day' );
$delete                 = eZHTTPTool::getVar( 'Delete' );
$deleteImage            = eZHTTPTool::getVar( 'DeleteImage' );
$deleteLogo             = eZHTTPTool::getVar( 'DeleteLogo' );
$description            = eZHTTPTool::getVar( 'Description' );
$emailNotice            = eZHTTPTool::getVar( 'EmailNotice' );
$fileButton             = eZHTTPTool::getVar( 'FileButton' );
$firstName              = eZHTTPTool::getVar( 'FirstName' );
$groupNotice            = eZHTTPTool::getVar( 'GroupNotice' );
$id                     = eZHTTPTool::getVar( 'Id' );
$imageID                = eZHTTPTool::getVar( 'ImageID' );
$itemID                 = eZHTTPTool::getVar( 'ItemID' );
$itemName               = eZHTTPTool::getVar( 'ItemName' );
$language               = eZHTTPTool::getVar( 'Language' );
$lastName               = eZHTTPTool::getVar( 'LastName' );
$limitBy                = eZHTTPTool::getVar( 'LimitBy' );
$limitStart             = eZHTTPTool::getVar( 'LimitStart' );
$limitType              = eZHTTPTool::getVar( 'LimitType' );
$listConsultation       = eZHTTPTool::getVar( 'ListConsultation' );
$logoImageID            = eZHTTPTool::getVar( 'LogoImageID' );
$mailButton             = eZHTTPTool::getVar( 'MailButton' );
$max                    = eZHTTPTool::getVar( 'Max' );
$month                  = eZHTTPTool::getVar( 'Month' );
$name                   = eZHTTPTool::getVar( 'Name' );
$newAddress             = eZHTTPTool::getVar( 'NewAddress' );
$newCompany             = eZHTTPTool::getVar( 'NewCompany' );
$newCompanyCategory     = eZHTTPTool::getVar( 'NewCompanyCategory' );
$newConsultation        = eZHTTPTool::getVar( 'NewConsultation' );
$newOnline              = eZHTTPTool::getVar( 'NewOnline' );
$newParentID            = eZHTTPTool::getVar( 'NewParentID' );
$newPhone               = eZHTTPTool::getVar( 'NewPhone' );
$nextYear               = eZHTTPTool::getVar( 'NextYear' );
$ok                     = eZHTTPTool::getVar( 'OK' );
$offset                 = eZHTTPTool::getVar( 'Offset' );
$online                 = eZHTTPTool::getVar( 'Online' );
$onlineDelete           = eZHTTPTool::getVar( 'OnlineDelete' );
$onlineDeleteValues     = eZHTTPTool::getVar( 'OnlineDeleteValues' ) ?? [];
$onlineID               = eZHTTPTool::getVar( 'OnlineID' );
$onlineList             = eZHTTPTool::getVar( 'OnlineList' );
$onlineMinimum          = eZHTTPTool::getVar( 'OnlineMinimum' );
$onlineTypeID           = eZHTTPTool::getVar( 'OnlineTypeID' );
$onlineWidth            = eZHTTPTool::getVar( 'OnlineWidth' );
$orderBy                = eZHTTPTool::getVar( 'OrderBy' );
$parentID               = eZHTTPTool::getVar( 'ParentID' );
$personContact          = eZHTTPTool::getVar( 'PersonContact' );
$personID               = eZHTTPTool::getVar( 'PersonID' );
$personLimit            = eZHTTPTool::getVar( 'PersonLimit' );
$personOffset           = eZHTTPTool::getVar( 'PersonOffset' );
$personTypeDescription  = eZHTTPTool::getVar( 'PersonTypeDescription' );
$personTypeName         = eZHTTPTool::getVar( 'PersonTypeName' );
$phone                  = eZHTTPTool::getVar( 'Phone' );
$phoneDelete            = eZHTTPTool::getVar( 'PhoneDelete' );
$phoneDeleteValues      = eZHTTPTool::getVar( 'PhoneDeleteValues' ) ?? [];
$phoneID                = eZHTTPTool::getVar( 'PhoneID' );
$phoneMinimum           = eZHTTPTool::getVar( 'PhoneMinimum' );
$phoneTypeID            = eZHTTPTool::getVar( 'PhoneTypeID' );
$phoneWidth             = eZHTTPTool::getVar( 'PhoneWidth' );
$place                  = eZHTTPTool::getVar( 'Place' );
$prevYear               = eZHTTPTool::getVar( 'PrevYear' );
$projectID              = eZHTTPTool::getVar( 'ProjectID' );
$refreshUsers           = eZHTTPTool::getVar( 'RefreshUsers' );
$searchText             = eZHTTPTool::getVar( 'SearchText' );
$searchType             = eZHTTPTool::getVar( 'SearchType' );
$searchable             = eZHTTPTool::getVar( 'Searchable' );
$selectParentID         = eZHTTPTool::getVar( 'SelectParentID' );
$sendMail               = eZHTTPTool::getVar( 'SendMail' );
$shortDescription       = eZHTTPTool::getVar( 'ShortDescription' );
$showCompanyContact     = eZHTTPTool::getVar( 'ShowCompanyContact' );
$showCompanyStatus      = eZHTTPTool::getVar( 'ShowCompanyStatus' );
$siteDesign             = eZHTTPTool::getVar( 'SiteDesign' ) ?? $siteDesign;
$siteURL                = eZHTTPTool::getVar( 'SiteURL' );
$sortPage               = eZHTTPTool::getVar( 'SortPage' );
$statusID               = eZHTTPTool::getVar( 'StatusID' );
$street1                = eZHTTPTool::getVar( 'Street1' );
$street2                = eZHTTPTool::getVar( 'Street2' );
$typeDescription        = eZHTTPTool::getVar( 'TypeDescription' );
$typeName               = eZHTTPTool::getVar( 'TypeName' );
$userID                 = eZHTTPTool::getVar( 'UserID' );
$userSearch             = eZHTTPTool::getVar( 'UserSearch' );
$year                   = eZHTTPTool::getVar( 'Year' );
$zip                    = eZHTTPTool::getVar( 'Zip' );

$listType = $url_array[2];
switch ( $listType )
{
    case "nopermission":
    {
        $type = $url_array[3];
        switch ( $type )
        {
            case "company":
            {
                $action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
            }
            break;

            case "category":
            {
                $action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
            }
            break;

            case "person":
            {
                $action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
            }
            break;

            case "login":
            case "consultation":
            {
                include( "kernel/ezcontact/admin/nopermission.php" );
            }
            break;

            case "type":
            {
                $action = $url_array[4];
                include( "kernel/ezcontact/admin/nopermission.php" );
            }
            break;

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "setup":
    {
        include( "kernel/ezcontact/admin/setup.php" );
    }
    break;

    case "search":
    {
        $searchType = $url_array[3];
        switch ( $searchType )
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

    case "company":
    {
        $companyID = $url_array[4];
        $action = $url_array[3];
        switch ( $action )
        {
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                $companyEdit = true;
                if ( isset( $sendMail ) )
                {
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else if ( isset( $mailButton ) )
                {
                    $contactArrayID = array( $personID );
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else
                {
                    if ( isset( $newCompany ) )
                        $action = "new";
                    if ( $action == "new" )
                        if ( isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                            $newCompanyCategory = $url_array[4];
                        else
                            if ( !isset( $companyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                                $companyID = $url_array[4];
                    include( "kernel/ezcontact/admin/companyedit.php" );
                }
            }
            break;

            case "view":
            {
                if ( !isset( $companyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $companyID = $url_array[4];
                $personOffset = $url_array[5];
                include( "kernel/ezcontact/admin/companyview.php" );
            }
            break;

            case "stats":
            {
                $year = $url_array[6];
                $month = $url_array[7];
                $day = $url_array[8];
                $dateType = $url_array[4];
                if ( !isset( $companyID ) and isset( $url_array[5] ) and is_numeric( $url_array[5] ) )
                    $companyID = $url_array[5];
                include( "kernel/ezcontact/admin/companystats.php" );
            }
            break;

            case "list":
            {
                $typeID = $url_array[4];
                $offset = $url_array[5];
                $showStats = true;
                include( "kernel/ezcontact/admin/companytypelist.php" );
            }
            break;

            case "folder":
            {
                $item_id = $url_array[4];
                $companyEdit = true;
                include( "kernel/ezcontact/admin/folder.php" );
            }
            break;

            case "buy":
            {
                include( "kernel/ezcontact/admin/buy.php" );
            }
            break;

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "companycategory" :
    {
        $typeID = $url_array[4];
        $action = $url_array[3];
        switch ( $action )
        {
            // intentional fall through
            case "new":
            {
                $newParentID = $url_array[4];
                unset( $typeID );
                include( "kernel/ezcontact/admin/companytypeedit.php" );
            }
            break;

            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "kernel/ezcontact/admin/companytypeedit.php" );
            }
            break;
                
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "person":
    {
        $personID = $url_array[4];
        $action = $url_array[3];
        switch ( $action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                $companyEdit = false;
                if ( isset( $sendMail ) )
                {
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else if ( isset( $mailButton ) )
                {
                    $contactArrayID = array( $personID );
                    include( "kernel/ezcontact/admin/sendmail.php" );
                }
                else
                {
                    include( "kernel/ezcontact/admin/personedit.php" );
                }
                    
            }
            break;
                
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                include( "kernel/ezcontact/admin/personlist.php" );
            }
            break;

            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $searchText ) )
                {
                    $searchText = $url_array[5];
                    $searchText = eZURITool::decode( $searchText );
                }
                include( "kernel/ezcontact/admin/personlist.php" );
            }
            break;

            case "view":
            {
                include( "kernel/ezcontact/admin/personview.php" );
            }
            break;

            case "folder":
            {
                $item_id = $url_array[4];
                include( "kernel/ezcontact/admin/folder.php" );
            }
            break;

            case "buy":
            {
                include( "kernel/ezcontact/admin/buy.php" );
            }
            break;
                
            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "consultation":
    {
        if ( !isset( $consultationID ) or !is_numeric( $consultationID ) )
            $consultationID = $url_array[4];

        $action = $url_array[3];
        switch ( $action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "kernel/ezcontact/admin/consultationedit.php" );
            }
            break;

            case "view":
            {
                include( "kernel/ezcontact/admin/consultationview.php" );
            }
            break;

            case "list":
            {
                include( "kernel/ezcontact/admin/consultationlist.php" );
            }
            break;

            case "company":
            {
                $subAction = $url_array[3];
                $action = $url_array[4];
                if ( !isset( $companyID ) or !is_numeric( $companyID ) )
                    $companyID = $url_array[5];
                switch ( $action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $consultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "kernel/ezcontact/admin/consultationedit.php" );
                    }
                    break;

                    case "list":
                    {
                        $consultationList = true;
                        include( "kernel/ezcontact/admin/consultationlist.php" );
                    }
                    break;

                    case "view":
                    {
                        include( "kernel/ezcontact/admin/consultationview.php" );
                    }
                    break;
                }
            }
            break;

            case "person":
            {
                $subAction = $url_array[3];
                $action = $url_array[4];
                if ( !isset( $personID ) )
                    $personID = $url_array[5];
                switch ( $action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $consultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "kernel/ezcontact/admin/consultationedit.php" );
                    }
                    break;

                    case "list":
                    {
                        $consultationList = true;
                        include( "kernel/ezcontact/admin/consultationlist.php" );
                    }
                    break;

                    case "view":
                    {
                        include( "kernel/ezcontact/admin/consultationview.php" );
                    }
                    break;
                }
                break;
            }

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "consultationtype":
    {
        $consultationTypeID = $url_array[4];
        $action = $url_array[3];
        switch ( $action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "kernel/ezcontact/admin/consultationtypeedit.php" );
            }
            break;

            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                else
                    $offset = false;
                include( "kernel/ezcontact/admin/consultationtypelist.php" );
            }
            break;
            
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $searchText ) )
                {
                    $searchText = $url_array[5];
                    $searchText = eZURITool::decode( $searchText );
                }
                include( "kernel/ezcontact/admin/consultationtypelist.php" );
            }
            break;

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "projecttype":
    {
        $projectTypeID = $url_array[4];
        $action = $url_array[3];
        switch ( $action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "kernel/ezcontact/admin/projecttypeedit.php" );
            }
            break;

            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                else
                    $offset = false;

                include( "kernel/ezcontact/admin/projecttypelist.php" );
            }
            break;

            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $offset = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $searchText ) )
                {
                    $searchText = $url_array[5];
                    $searchText = eZURITool::decode( $searchText );
                }
                include( "kernel/ezcontact/admin/projecttypelist.php" );
            }
            break;

            default:
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
            }
            break;
        }
    }
    break;

    case "error":
    {
        include( "kernel/ezcontact/admin/error.php" );
    }
    break;

    default :
    {
        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( 'Location: /contact/error?Type=404&Uri=' . urlencode( $_SERVER['REQUEST_URI'] ) . '&Query=' . urlencode( $_SERVER['QUERY_STRING'] ?? '' ) . '&BackUrl=' . urlencode( $_SERVER['HTTP_REFERER'] ?? '' ) );
    }
    break;
}

?>