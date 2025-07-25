<?php
//
// $Id: datasupplier.php 9407 2002-04-10 11:49:02Z br $
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

$PageCaching = $ini->read_var( "eZTradeMain", "PageCaching");


// include_once( "ezuser/classes/ezuser.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );
// include_once( "classes/ezhttptool.php" );

$user =& eZUser::currentUser();
$SiteDesign = $ini->read_var( "site", "SiteDesign" );

$RequireUser = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "enabled" ? true : false;
$ShowPrice = $RequireUser ? is_a( $user, "eZUser" ) : true;
$UserReviews = $ini->read_var( "eZTradeMain", "UserReviews" );

$PriceGroup = 0;
if ( is_a( $user, "eZUser" ) )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( false ) );
}
if ( !$ShowPrice )
    $PriceGroup = -1;

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZTradeMain", "DefaultSection" );

if ( $user )
{
    $groupIDArray =& $user->groups( false );
    sort( $groupIDArray );
}
else
{
    $groupIDArray = null;
}


switch ( $url_array[2] )
{

    case "hotdealsgallery" :
    {
        // $RedirectURL = $RedirectURL;
        $session->setVariable( "RedirectURL", $REQUEST_URI );
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;
        if ( $PageCaching == "enabled" )
        {
            //include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                            array( "hotdealsgallery", $CategoryID, $groupIDArray, $Offset, $PriceGroup ),
                                            "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/hotdealsgallery.php" );
            }
        }
        else
        {
            include( "kernel/eztrade/user/hotdealsgallery.php" );
        }
        break;
    }

    case "hotdealslist" :
    {
        // $RedirectURL = $RedirectURL;
        $session->setVariable( "RedirectURL", $REQUEST_URI );
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;
        if ( $PageCaching == "enabled" )
        {
            //include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                            array( "hotdealslist", $CategoryID, $groupIDArray, $Offset, $PriceGroup ),
                                            "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/hotdealslist.php" );
            }
        }
        else
        {
            include( "kernel/eztrade/user/hotdealslist.php" );
        }
        break;
    }

    case "productgallery" :
    {
    // $RedirectURL = $RedirectURL;
    $session->setVariable( "RedirectURL", $REQUEST_URI );
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;
        if ( $PageCaching == "enabled" )
        {
            // include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                            array( "productgallery", $CategoryID, $groupIDArray, $Offset, $PriceGroup ),
                                            "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/productgallery.php" );
            }
        }
        else
        {
            include( "kernel/eztrade/user/productgallery.php" );
        }
        break;
    }
    
    case "productlist" :
    {
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;
        if ( $PageCaching == "enabled" )
        {
            if ( !isset( $groupIDArray ) || !$groupIDArray )
                $groupIDArray = 0;
  
              if ( !isset( $PriceGroup ) || !$PriceGroup )
                $PriceGroup = 0;

            // include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                          array( "productlist", $CategoryID, $Offset, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                if ( $CacheFile->filename( true ) != "" )
                {
                    include( $CacheFile->filename( true ) );
                    // print_r( $CacheFile->filename( true ) );
                }
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/productlist.php" );
            }
        }
        else
        {
            //$GenerateStaticPage = "false";
            $GenerateStaticPage = "false";
            include( "kernel/eztrade/user/productlist.php" );
        }
        break;
    }

    case "sitemap" :
    {
        include( "kernel/eztrade/user/sitemap.php" );
        break;
    }
    
    case "productview" :
        $PrintableVersion = "disabled";
        if ( $PageCaching == "enabled" )
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            // include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                          array( "productview", $ProductID, $groupIDArray, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/productview.php" );
            }
        }
        else
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];
            include( "kernel/eztrade/user/productview.php" );
        }
        if  ( ( $PrintableVersion != "enabled" ) && ( $UserReviews == "enabled" ) )
        {
            $RedirectURL = "/trade/productview/$ProductID/$CategoryID/";
            $product = new eZProduct( $ProductID );
            if ( ( $product->id() >= 1 ) )    //  && $product->discuss() )
            {
                for ( $i = 0; $i < count( $url_array ); $i++ )
                {
                    if ( ( $url_array[$i] ) == "parent" )
                    {
                        $next = $i + 1;
                        $Offset = $url_array[$next];
                    }
                }
                $forum = $product->forum();
                $ForumID = $forum->id();
                include( "kernel/ezforum/user/messagereviewlist.php" );
            }
        }

        break;

    case "print" :
    case "productprint" :
        if ( $PageCaching == "enabled" )
        {
            $PrintableVersion = "enabled";
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            // include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "kernel/eztrade/cache/",
                                          array( "productprint", $ProductID, $groupIDArray, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "kernel/eztrade/user/productview.php" );
            }
        }
        else
        {
            $PrintableVersion = "enabled";
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];
            include( "kernel/eztrade/user/productview.php" );
        }

        break;

    case "cart" :
    {
        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
        }

        if ( $url_array[3] == "remove" )
        {
            $Action = "RemoveFromBasket";
            $CartItemID = $url_array[4];
        }

        if ( isset( $WishList ) )
        {
            include( "kernel/eztrade/user/wishlist.php" );

//               eZHTTPTool::header( "Location: /trade/wishlist/add/$ProductID" );
//              exit();
        }
        else
        {
            include( "kernel/eztrade/user/cart.php" );
        }
    }
        break;

    case "wishlist" :
    {
        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
        }

        if ( $url_array[3] == "movetocart" )
        {
            $Action = "MoveToCart";
            $WishListItemID = $url_array[4];
        }

        if ( $url_array[3] == "remove" )
        {
            $Action = "RemoveFromWishlist";
            $WishListItemID = $url_array[4];
        }

        include( "kernel/eztrade/user/wishlist.php" );
    }
    break;

    case "viewwishlist" :
    {
        if ( $url_array[3] == "movetocart" )
        {
            $Action = "MoveToCart";
            $WishListItemID = $url_array[4];
        }

        include( "kernel/eztrade/user/viewwishlist.php" );
    }
    break;

    case "sendwishlist" :
    {
        include( "kernel/eztrade/user/sendwishlist.php" );
    }
    break;

    case "voucherview" :
    {
        include( "kernel/eztrade/user/voucherview.php" );
    }
    break;

    case "vouchermain" :
    {
        include( "kernel/eztrade/user/vouchermain.php" );
    }
    break;

    case "voucheremailsample" :
    {
        include( "kernel/eztrade/user/voucheremailsample.php" );
    }
    break;

    case "orderview" :
    {
        $OrderID = $url_array[3];
        include( "kernel/eztrade/user/orderview.php" );
    }
    break;

    case "findwishlist" :
    {
        include( "kernel/eztrade/user/findwishlist.php" );
    }
    break;

    case "customerlogin" :
        include( "kernel/eztrade/user/customerlogin.php" );
    break;

    case "precheckout" :
    {
        include( "kernel/eztrade/user/precheckout.php" );
    }
    break;

    case "checkout" :
    {
        include( "kernel/eztrade/user/checkout.php" );
    }
    break;

    case "payment" :
    {
        include( "kernel/eztrade/user/payment.php" );
    }
    break;

	case "paypal" :
    {
        $orderID = $url_array[3];
        $sessionID = $url_array[4];
        include( "kernel/eztrade/user/paypalnotify.php" );

    }
    break;

    case "confirmation" :
    {
        include( "kernel/eztrade/user/confirmation.php" );
    }
    break;

    case "voucherinformation" :
    {
        $ProductID = $url_array[3];
        $PriceRange = $url_array[4];
        $MailMethod = $url_array[5];

        include( "kernel/eztrade/user/voucherinformation.php" );
    }
    break;

    case "ordersendt" :
    {
        $OrderID = $url_array[3];
        include( "kernel/eztrade/user/ordersendt.php" );
    }
    break;

    case "search" :
    {
        if ( $url_array[3] == "move" )
        {
            $Query = urldecode( $url_array[4] );
            $Offset = urldecode ( $url_array[5] );
        }
        include( "kernel/eztrade/user/productsearch.php" );
    }
    break;

    case "orderlist" :
    {
        if ( $url_array[3] != "" )
            $Offset = $url_array[3];
        else
            $Offset = 0;

        include( "kernel/eztrade/user/orderlist.php" );
    }
    break;

    case "extendedsearch" :
    {
        $Limit = 10;
        if ( $url_array[3] == "move" )
        {
            $Text = urldecode( $url_array[4] );
            $PriceRange = urldecode( $url_array[5] );
            $MainCategories = urldecode ( $url_array[6] );
            $CategoryArray = urldecode ( $url_array[7] );
            $Offset = urldecode ( $url_array[8] );

            $Action = "SearchButton";
            $Next = true;
        }

        include( "kernel/eztrade/user/extendedsearch.php" );
    }
    break;
    
    case "export" :
    {
      if ( $url_array[3] == 'froogle' )
      {
        if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
	}
        else 
	{
          $Action = "export-cron";
        }
        include( "kernel/eztrade/admin/export_froogle.php" );
      }
      elseif ( $url_array[3] == 'yahoo' )
      {
        if ( $url_array[4] == 'download' )
	{
	  $Action = "export";
	}
        else 
	{
          $Action = "export-cron";
        }
        include( "kernel/eztrade/admin/export_yahoo.php" );
      }
      else {
	$Action = "export-cron";
	include( "kernel/eztrade/admin/export_froogle.php" );
      }
    }
    break;

    // XML rpc interface
    case "xmlrpc" :
    {
        include( "kernel/eztrade/xmlrpc/xmlrpcserver.php" );
    }
    break;

    // XML rpc interface
    case "xmlrpcimport" :
    {
        include( "kernel/eztrade/xmlrpc/xmlrpcserverimport.php" );
    }
    break;


    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>