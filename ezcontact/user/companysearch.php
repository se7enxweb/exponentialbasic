<?php
//
// $Id: companysearch.php 9805 2003-04-10 14:48:45Z br $
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

/*
    Searches for companies.
*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezuser/classes/ezpermission.php" );

$ini =& INIFIle::globalINI();
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezcontact/user/intl/" . $Language . "/companysearch.php.ini", false );

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl/", $Language, "companysearch.php" );

$t->set_file( "search", "companysearch.tpl" );

$t->set_block( "search", "current_type_tpl", "current_type" );
$t->set_block( "search", "list_tpl", "list" );
$t->set_block( "search", "not_root_tpl", "not_root" );

$t->set_block( "search", "category_list_tpl", "category_list" );

$t->set_block( "search", "no_type_item_tpl", "no_type_item" );
$t->set_block( "search", "no_category_item_tpl", "no_category_item" );
$t->set_block( "search", "company_item_tpl", "company_item" );
$t->set_block( "company_item_tpl", "image_view_tpl", "image_view" );
$t->set_block( "search", "no_companies_tpl", "no_companies" );
$t->set_block( "search", "companies_table_tpl", "companies_table" );
$t->set_block( "companies_table_tpl", "company_stats_header_tpl", "company_stats_header" );

$t->set_block( "company_item_tpl", "no_image_tpl", "no_image" );
$t->set_block( "company_item_tpl", "company_view_button_tpl", "company_view_button" );
$t->set_block( "company_item_tpl", "no_company_view_button_tpl", "no_company_view_button" );
$t->set_block( "company_item_tpl", "company_consultation_button_tpl", "company_consultation_button" );
$t->set_block( "company_item_tpl", "company_edit_button_tpl", "company_edit_button" );
$t->set_block( "company_item_tpl", "company_delete_button_tpl", "company_delete_button" );
$t->set_block( "company_item_tpl", "company_stats_item_tpl", "company_stats_item" );
$t->set_block( "search", "company_new_button_tpl", "company_new_button" );

$t->set_var( "search_box", "" );
$t->set_var( "search_results", "" );
$t->set_var( "results", "" );
$t->set_var( "result_item", "" );
$t->set_var( "category_option", "" );
$t->set_var( "result_category", "" );
$t->set_var( "companies_table", "" );
$t->set_var( "command_type", "company" );

$t->set_var( "search_text", htmlspecialchars( $SearchText ) );
$t->set_var( "current_id", isset( $SearchCategory ) ? $SearchCategory : false );

$Action = "new";
$results = "false";

if ( isset( $SearchObject) && $SearchObject == "company" )
{
    $Action = "search";
}

if ( !empty( $SearchText ) )
{
    $Action = "search";
}

$company = new eZCompany();
$result = false;
$companyArray = array();
if ( $Action == "search" )
{
    $companyArray = $company->search( $SearchText );

    $count = count( $companyArray );
    if ( $count > 0 )
    {
        $results = true;
    }
}

if ( $results == true )
{
    $count = count( $companyArray );
    $t->set_var( "results", $count );
    $i = 0;

    $can_view_stats = eZPermission::checkPermission( $user, "eZContact", "CompanyStats" ) && isset( $ShowStats ) && $ShowStats;
    $t->set_var( "company_stats_header", "" );
    if ( $can_view_stats )
        $t->parse( "company_stats_header", "company_stats_header_tpl" );
    $t->set_var( "company_stats_item", "" );


    $t->set_var( "company_consultation_button", "" );
    $t->set_var( "company_edit_button", "" );
    $t->set_var( "company_delete_button", "" );
    $t->set_var( "company_view_button", "" );
    $t->set_var( "no_company_view_button", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
        $t->parse( "company_consultation_button", "company_consultation_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyModify" ) )
        $t->parse( "company_edit_button", "company_edit_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyDelete" ) )
        $t->parse( "company_delete_button", "company_delete_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyView" ) )
    {
        $t->parse( "company_view_button", "company_view_button_tpl" );
    }
    else
    {
        $t->parse( "no_company_view_button", "no_company_view_button_tpl" );
    }

    if ( !isset( $companyList ) || isset( $companyList ) && count( $companyList ) == 0 )
    {
        $t->set_var( "company_item", "" );
        $t->set_var( "companies_table", "" );
        $t->parse( "no_companies", "no_companies_tpl" );
    }
    else
    {
        $can_view_stats = eZPermission::checkPermission( $user, "eZContact", "CompanyStats" ) && $ShowStats;
        $t->set_var( "company_stats_header", "" );
        if ( $can_view_stats )
            $t->parse( "company_stats_header", "company_stats_header_tpl" );
        $t->set_var( "company_stats_item", "" );
        $t->set_var( "no_companies", "" );
        $t->parse( "companies_table", "companies_table_tpl" );
    }

    foreach ( $companyArray as $company )
    {
        if ( $can_view_stats )
        {
            $count = $companyList[$i]->totalViewCount();
            $t->set_var( "company_views", $count );
            $t->parse( "company_stats_item", "company_stats_item_tpl" );
        }

        unSet( $logoObj );
        $logoObj = $companyArray[$i]->logoImage();

        if ( is_a( $logoObj, "eZImage" ) )
        {
            $variationObj = $logoObj->requestImageVariation( 150, 150 );

            $t->set_var( "company_logo_src", "/" . $variationObj->imagePath() );
            $image = new eZImage( $variationObj->imageID() );
            $t->set_var( "image_alt", $image->caption() );
            $t->set_var( "no_image", "" );
            $t->parse( "image_view", "image_view_tpl" );
        }
        else
        {
            $t->set_var( "image_view", "" );
            $t->parse( "no_image", "no_image_tpl" );
        }

        $t->set_var( "no_companies", "" );

        if ( ( $i % 2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
        $i++;
        $t->set_var( "company_name", $company->name() );
        $t->set_var( "company_id", $company->id() );
        $t->set_var( "item_description", $company->comment() );
        $t->set_var( "item_view_path", "/contact/company/view" );
        $t->set_var( "item_delete_path", "/contact/company/delete" );
        $t->set_var( "item_edit_path", "/contact/company/edit" );

        $t->set_var( "result_category", "" );
        $t->parse( "company_item", "company_item_tpl", true );

    }

    if ( $count > 0 )
    {
        $t->parse( "companies_table", "companies_table_tpl" );
    }
}
$t->parse( "list", "list_tpl" );
$t->setAllStrings();

$t->pparse( "output", "search");

?>