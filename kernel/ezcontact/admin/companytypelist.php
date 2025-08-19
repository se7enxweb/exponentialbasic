<?php
//
// $Id: companytypelist.php 9529 2002-05-14 11:17:05Z jhe $
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

/*
  Viser firma typer.
*/

// include_once( "classes/INIFile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZContactMain", "Language" );
$MaxCompanyList = $ini->variable( "eZContactMain", "MaxCompanyList" );
$CompanyOrder = $ini->variable( "eZContactMain", "CompanyOrder" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlist.php" );
// include_once( "ezcontact/classes/ezcompanytype.php" );
// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

if ( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCompanyType();
$type->get( $TypeID );

$company = new eZCompany();

$user =& eZUser::currentUser();
if ( !is_a( $user, "eZUser" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "CompanyList" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/company/list" );
    exit();
}

if ( !$type->id() && $TypeID != 0 )
{
    header( "HTTP/1.0 404 Not Found" );
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/company/list/" );
    exit();
}
else
{
    $t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->variable( "eZContactMain", "AdminTemplateDir" ),
                         "kernel/ezcontact/admin/intl/", $Language, "companytype.php" );
    $t->setAllStrings();

    $t->set_file( "type_page", "companytypelist.tpl" );

    $t->set_block( "type_page", "current_type_tpl", "current_type" );
    $t->set_block( "type_page", "view_tpl", "view" );
    $t->set_block( "type_page", "list_tpl", "list" );
    $t->set_block( "type_page", "not_root_tpl", "not_root" );
    $t->set_block( "type_page", "type_list_tpl", "type_list" );

    $t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
    $t->set_block( "type_item_tpl", "type_edit_button_tpl", "type_edit_button" );
    $t->set_block( "type_item_tpl", "type_delete_button_tpl", "type_delete_button" );

    $t->set_block( "type_page", "type_new_button_tpl", "type_new_button" );

    $t->set_block( "type_page", "category_list_tpl", "category_list" );

    $t->set_block( "type_page", "no_type_item_tpl", "no_type_item" );
    $t->set_block( "type_page", "no_category_item_tpl", "no_category_item" );
    $t->set_block( "type_page", "path_tpl", "path" );
    $t->set_block( "path_tpl", "path_item_tpl", "path_item" );
    $t->set_block( "current_type_tpl", "image_item_tpl", "image_item" );
    $t->set_block( "type_page", "company_item_tpl", "company_item" );
    $t->set_block( "company_item_tpl", "image_view_tpl", "image_view" );
    $t->set_block( "type_page", "no_companies_tpl", "no_companies" );
    $t->set_block( "type_page", "companies_table_tpl", "companies_table" );
    $t->set_block( "companies_table_tpl", "company_stats_header_tpl", "company_stats_header" );

    $t->set_block( "company_item_tpl", "no_image_tpl", "no_image" );
    $t->set_block( "company_item_tpl", "company_view_button_tpl", "company_view_button" );
    $t->set_block( "company_item_tpl", "no_company_view_button_tpl", "no_company_view_button" );
    $t->set_block( "company_item_tpl", "company_consultation_button_tpl", "company_consultation_button" );
    $t->set_block( "company_item_tpl", "company_buy_button_tpl", "company_buy_button" );
    $t->set_block( "company_item_tpl", "company_folder_button_tpl", "company_folder_button" );
    $t->set_block( "company_item_tpl", "company_edit_button_tpl", "company_edit_button" );
    $t->set_block( "company_item_tpl", "company_delete_button_tpl", "company_delete_button" );
    $t->set_block( "company_item_tpl", "company_stats_item_tpl", "company_stats_item" );
    $t->set_block( "type_page", "company_new_button_tpl", "company_new_button" );

    $t->set_var( "image_item", "" );
    $t->set_var( "command_type", "company" );

    $args = false;

    if ( empty( $OrderBy ) )
    {
        $OrderBy = "Name";
    }

    if ( !empty( $LimitBy ) || !empty( $LimitStart ) )
    {
        $type_array = $type->getByParentID( $TypeID, $OrderBy, $LimitBy, $LimitStart );

        if ( empty( $LimitStart ) )
        {
            $LimitStart = $LimitBy;
        }
        else
        {
            $LimitStart += $LimitStart;
        }
    }
    else
    {
        $type_array = $type->getByParentID( $TypeID, $OrderBy );
    }

    if ( !empty( $OrderBy ) )
    {
        if ( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "OrderBy=$OrderBy";
    }

    if ( !empty( $LimitStart ) )
    {
        if ( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "LimitStart=$LimitStart";
    }

    if ( !empty( $LimitBy ) )
    {
        if ( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "LimitBy=$LimitBy";
    }

    $type_count = count( $type_array );

    $t->set_var( "page_args", $args );

    $pathArray = $type->path( $TypeID );

    $t->set_var( "path_item", "" );
    foreach ( $pathArray as $path )
    {
        $t->set_var( "parent_id", $path[0] );
        $t->set_var( "parent_name", $path[1] );

        $t->parse( "path_item", "path_item_tpl", true );
    }
    $t->parse( "path", "path_tpl" );

    $id = $type->id();
    $name = $type->name();
    $desc = $type->description();

    $t->set_var( "current_id", is_numeric( $id ) ? $id : 0 );
    $t->set_var( "current_name", $name );
    $t->set_var( "current_description", $desc );

    $ImageID = $type->imageID();

    if ( is_numeric( $ImageID ) && $ImageID != 0 )
    {
        $imageWidth = $ini->variable( "eZContactMain", "CategoryImageWidth" );
        $imageHeight = $ini->variable( "eZContactMain", "CategoryImageHeight" );

        $image = new eZImage( $ImageID );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth = $variation->width();
        $imageHeight = $variation->height();
        $imageCaption = $image->caption();

        $t->set_var( "image_width", $imageWidth );
        $t->set_var( "image_height", $imageHeight );
        $t->set_var( "image_url", $imageURL );
        $t->set_var( "image_caption", $imageCaption );
        $t->parse( "image_item", "image_item_tpl" );
    }

    $t->parse( "current_type", "current_type_tpl" );
    if ( $TypeID != 0 && $Action == "view" )
    {
        $t->parse( "not_root", "not_root_tpl" );
    }
    else
    {
        $t->set_var( "not_root", "" );
    }
    if ( $Action == "view" )
    {
        $t->parse( "view", "view_tpl" );
        $t->set_var( "list", "" );
    }
    if ( $Action == "list" )
    {
        $t->set_var( "view", "" );
        $t->parse( "list", "list_tpl" );
    }

    $t->set_var( "type_edit_button", "" );
    $t->set_var( "type_delete_button", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CategoryModify" ) )
        $t->parse( "type_edit_button", "type_edit_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CategoryDelete" ) )
        $t->parse( "type_delete_button", "type_delete_button_tpl" );
    if ( $type_count != 0 )
    {
        for ( $i = 0; $i < $type_count; $i++ )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "theme-type_class", "bglight" );
            }
            else
            {
                $t->set_var( "theme-type_class", "bgdark" );
            }

            $id = $type_array[$i]->id();
            $name = $type_array[$i]->name();
            $desc = $type_array[$i]->description();

            $t->set_var( "type_id", $id );

            if ( empty( $name ) )
            {
                $t->set_var( "type_name", "&nbsp;" );
            }
            else
            {
                $t->set_var( "type_name", $name );
            }
            if ( empty( $desc ) )
            {
                $t->set_var( "type_description", "&nbsp;" );
            }
            else
            {
                $t->set_var( "type_description", $desc );
            }
            $t->parse( "type_item", "type_item_tpl", true );
            $typesDone = true;
        }
    }
    $t->set_var( "type_new_button", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CategoryAdd" ) )
        $t->parse( "type_new_button", "type_new_button_tpl" );

    if ( !is_numeric( $Offset ) )
        $Offset = 0;
    if ( !is_numeric( $MaxCompanyList ) )
        $MaxCompanyList = 10;

    // List all the companies.
    $companyList = $company->getByCategory( $TypeID, $Offset, $MaxCompanyList, $CompanyOrder );
    $total_companies = $company->countByCategory( $TypeID );

    $t->set_var( "company_consultation_button", "" );
    $t->set_var( "company_buy_button", "" );
    $t->set_var( "company_edit_button", "" );
    $t->set_var( "company_delete_button", "" );
    $t->set_var( "company_view_button", "" );
    $t->set_var( "no_company_view_button", "" );

    $t->parse( "company_folder_button", "company_folder_button_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "Buy" ) )
        $t->parse( "company_buy_button", "company_buy_button_tpl" );
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

    if ( count( $companyList ) == 0 )
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
        for ( $i = 0; $i < count( $companyList ); $i++ )
        {
            if ( ( $i % 2 ) == 0 )
                $t->set_var( "td_class", "bglight" );
            else
                $t->set_var( "td_class", "bgdark" );

            $t->set_var( "company_id", $companyList[$i]->id() );
            $t->set_var( "company_name", $companyList[$i]->name() );
            if ( $can_view_stats )
            {
                $count = $companyList[$i]->totalViewCount();
                $t->set_var( "company_views", $count );
                $t->parse( "company_stats_item", "company_stats_item_tpl" );
            }

            unSet( $logoObj );
            $logoObj = $companyList[$i]->logoImage();

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
            $t->parse( "company_item", "company_item_tpl", true );
        }

        $t->set_var( "no_companies", "" );
        $t->parse( "companies_table", "companies_table_tpl" );
    }
    $t->set_var( "company_new_button", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyAdd" ) )
        $t->parse( "company_new_button", "company_new_button_tpl" );

    if ( isset( $typesDone ) && $typesDone == true )
    {
        $t->set_var( "no_type_item", "" );
        $t->parse( "type_list", "type_list_tpl" );
    }
    else
    {
        $t->set_var( "type_list", "" );
        $t->parse( "no_type_item", "no_type_item_tpl" );
    }

    if ( isset( $categoriesDone ) && $categoriesDone == true )
    {
        $t->set_var( "no_category_item", "" );
        $t->parse( "category_list", "category_list_tpl" );
    }
    else
    {
        $t->set_var( "category_list", "" );
        $t->parse( "no_category_item", "no_category_item_tpl" );
    }

    eZList::drawNavigator( $t, $total_companies, $MaxCompanyList, $Offset, "type_page",
                           array( "type_list" => "company_list",
                                  "next" => "",
                                  "previous" => "",
                                  "next_inactive" => "",
                                  "previous_inactive" => "",
                                  "item" => "",
                                  "item_inactive" => "",
                                  "item_list" => "",
                                  "next_index" => "",
                                  "previous_index" => "",
                                  "type_item_name" => "",
                                  "item_index" => "",
                                  "item_name" => "" ) );

    $t->pparse( "output", "type_page" );
}

?>