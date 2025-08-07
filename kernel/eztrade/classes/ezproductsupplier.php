<?php
// 
// $Id: ezarticlesupplier.php,v 1.4 2001/07/19 12:19:21 jakobn Exp $
//
// Definition of eZArticleSupplier class
//
// Created on: <04-May-2001 17:14:30 amos>
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

//!! eZTrade
//! The class eZProductSupplier supples product categories and products for other modules.
/*!

*/

class eZProductSupplier
{
    function eZProductSupplier()
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
        return "eZTrade";
    }

    /*!
      Returns a list of categories and/or products.
    */
    function &urlList( $type, $category = 0, $offset = 0 )
    {
        $ini =& eZINI::instance( 'site.ini' );
        $ret = false;
        switch( $type )
        {
            case "product":
            {
                include_once( "eztrade/classes/ezproduct.php" );
                include_once( "eztrade/classes/ezproductcategory.php" );
                $limit = $ini->variable( "eZTradeMain", "ProductLimit" );
                $cat = new eZProductCategory( $category );
                $categories = $cat->getByParent( $cat );
                $products = $cat->products( "alpha", false, $offset, $limit );
                $num_products = $cat->productCount( $cat->sortMode(), true );
                $path = $cat->path( $category );
                $category_path = array();
                foreach( $path as $path_item )
                {
                    $category_path[] = array( "id" => $path_item[0],
                                              "name" => $path_item[1] );
                }
                $category_array = array();
                $category_url = "/trade/categorylist";
                foreach( $categories as $category )
                {
                    $id = $category->id();
                    $url = "$category_url/$id";
                    $category_array[] = array( "name" => $category->name(),
                                               "id" => $id,
                                               "url" => $url );
                }
                $product_array = array();
                $product_url = "/trade/productview";
                foreach( $products as $product )
                {
                    $id = $product->id();
                    $cat = $product->categoryDefinition();
                    $cat = $cat->id();
		    $url = "$product_url/$id";
                    $product_array[] = array( "name" => $product->name(),
                                              "id" => $id,
                                              "url" => $url );
                }
                $ret = array();
                $ret["path"] = $category_path;
                $ret["categories"] = $category_array;
                $ret["items"] = $product_array;
                $ret["item_total_count"] = $num_products;
                $ret["max_items_shown"] = $limit;
                break;
            }
        }
        return $ret;
    }

    function &item( $type, $id, $is_category )
    {
        $ret = false;
        switch( $type )
        {
            case "product":
            {
                if ( $is_category )
                {
                    include_once( "eztrade/classes/ezproductcategory.php" );
                    $category = new eZProductCategory( $id );
                    $category_url = "/trade/productlist";
                    $url = "$category_url/$id";
                    $ret = array( "name" => $category->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
                else
                {
                    include_once( "eztrade/classes/ezproduct.php" );
                    $product = new eZProduct( $id );
                    $product_url = "/trade/productview";
                    $cat = $product->categoryDefinition();
                    $cat = $cat->id();
		    $url = "$product_url/$id";
                    $ret = array( "name" => $product->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
            }
        }
        return $ret;
    }

    var $UrlTypes = array( "product" => "{intl-product}" );
}

?>
