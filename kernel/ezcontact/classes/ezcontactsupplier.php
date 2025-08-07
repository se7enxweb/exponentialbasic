<?php
// 
// $Id: ezcontactsupplier.php 6970 2001-09-05 11:57:07Z jhe $
//
// Definition of ezcontactsupplier class
//
// Created on: <19-Mar-2001 16:51:20 amos>
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

//!! eZContact
//! The class ezcontactsupplier does
/*!

*/

class eZContactSupplier
{
    function __construct()
    {
    }

    /*!
      Returns an array of available types.
    */
    function &urlTypes()
    {
        return $this->UrlTypes;
    }

    /*!
      Returns the name of the module.
    */
    function moduleName()
    {
        return "eZContact";
    }

    /*!
      Returns a list of categories and/or contacts.
    */
    function &urlList( $type, $category = 0, $offset = 0 )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $ret = false;
        switch ( $type )
        {
            case "company":
            {
                // include_once( "ezcontact/classes/ezcompany.php" );
                // include_once( "ezcontact/classes/ezcompanytype.php" );
                $limit = $ini->variable( "eZContactMain", "MaxCompanyList" );
                $categories = eZCompanyType::getByParentID( $category, "name" );
                $companies = eZCompany::getByCategory( $category, $offset, $limit );
                $num_companies = eZCompany::countByCategory( $category );
                $path = eZCompanyType::path( $category );
                $category_path = array();
                foreach ( $path as $path_item )
                {
                    $category_path[] = array( "id" => $path_item[0],
                                              "name" => $path_item[1] );
                }
                $category_array = array();
                $category_url = "/contact/company/list";
                foreach ( $categories as $category )
                {
                    $id = $category->id();
                    $url = "$category_url/$id";
                    $category_array[] = array( "name" => $category->name(),
                                               "id" => $id,
                                               "url" => $url );
                }
                $company_array = array();
                $company_url = "/contact/company/view";
                foreach ( $companies as $company )
                {
                    $id = $company->id();
                    $url = "$company_url/$id";
                    $company_array[] = array( "name" => $company->name(),
                                              "id" => $id,
                                              "url" => $url );
                }
                $ret = array();
                $ret["path"] = $category_path;
                $ret["categories"] = $category_array;
                $ret["items"] = $company_array;
                $ret["item_total_count"] = $num_companies;
                $ret["max_items_shown"] = $limit;
                break;
            }

//              case "person":
//              {
//                  break;
//              }
        }
        return $ret;
    }

    function &item( $type, $id, $is_category )
    {
        $ret = false;
        switch ( $type )
        {
            case "company":
            {
                if ( $is_category )
                {
                    // include_once( "ezcontact/classes/ezcompanytype.php" );
                    $category = new eZCompanyType( $id );
                    $category_url = "/contact/company/list";
                    $url = "$category_url/$id";
                    $ret = array( "name" => $category->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
                else
                {
                    // include_once( "ezcontact/classes/ezcompany.php" );
                    $company = new eZCompany( $id );
                    $company_url = "/contact/company/view";
                    $url = "$company_url/$id";
                    $ret = array( "name" => $company->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
            }

//              case "person":
//              {
//                  break;
//              }
        }
        return $ret;
    }

    var $UrlTypes = array( "company" => "{intl-contact_company}" /*,
                                                                   "person" => "{intl-contact_person}"*/ );
}

?>