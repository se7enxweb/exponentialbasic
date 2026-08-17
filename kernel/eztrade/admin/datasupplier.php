<?php
//
// $id: datasupplier.php 9275 2002-02-26 14:28:02Z ce $
//
// Created on: <21-Sep-2000 10:32:36 bf>
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

// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezpermission.php" );
// include_once( "eztrade/classes/ezproducttool.php" );

// include_once( "ezuser/classes/ezobjectpermission.php" );


$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZTrade", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = explode( "/", $_SERVER['REQUEST_URI'] );
$abort                  = eZHTTPTool::getVar( 'Abort' );
$action                 = eZHTTPTool::getVar( 'Action' );
$actionValue            = eZHTTPTool::getVar( 'ActionValue' );
$active                 = eZHTTPTool::getVar( 'Active' );
$addCurrency            = eZHTTPTool::getVar( 'AddCurrency' );
$addFiles               = eZHTTPTool::getVar( 'AddFiles' );
$addGroup               = eZHTTPTool::getVar( 'AddGroup' );
$addImages              = eZHTTPTool::getVar( 'AddImages' );
$addItem                = eZHTTPTool::getVar( 'AddItem' );
$addType                = eZHTTPTool::getVar( 'AddType' );
$addValue               = eZHTTPTool::getVar( 'AddValue' );
$attributeID            = eZHTTPTool::getVar( 'AttributeID' );
$attributeName          = eZHTTPTool::getVar( 'AttributeName' );
$attributeType          = eZHTTPTool::getVar( 'AttributeType' );
$attributeValue         = eZHTTPTool::getVar( 'AttributeValue' );
$available              = eZHTTPTool::getVar( 'Available' );
$boxArrayID             = eZHTTPTool::getVar( 'BoxArrayID' ) ?? [];
$boxID                  = eZHTTPTool::getVar( 'BoxID' );
$boxName                = eZHTTPTool::getVar( 'BoxName' );
$boxType                = eZHTTPTool::getVar( 'BoxType' );
$boxTypeID              = eZHTTPTool::getVar( 'BoxTypeID' );
$brief                  = eZHTTPTool::getVar( 'Brief' );
$browse                 = eZHTTPTool::getVar( 'Browse' );
$csvDelimiter           = eZHTTPTool::getVar( 'CSVDelimiter' );
$csvImport              = eZHTTPTool::getVar( 'CSVImport' );
$cancel                 = eZHTTPTool::getVar( 'Cancel' );
$capitalizeHeadlines    = eZHTTPTool::getVar( 'CapitalizeHeadlines' );
$caption                = eZHTTPTool::getVar( 'Caption' );
$catalogNumber          = eZHTTPTool::getVar( 'CatalogNumber' );
$categoryArray          = eZHTTPTool::getVar( 'CategoryArray' ) ?? [];
$categoryArrayID        = eZHTTPTool::getVar( 'CategoryArrayID' ) ?? [];
$categoryID             = eZHTTPTool::getVar( 'CategoryID' );
$clientModuleName       = eZHTTPTool::getVar( 'ClientModuleName' );
$clientModuleType       = eZHTTPTool::getVar( 'ClientModuleType' );
$code                   = eZHTTPTool::getVar( 'Code' );
$colSpanSizeTotals      = eZHTTPTool::getVar( 'ColSpanSizeTotals' );
$contents               = eZHTTPTool::getVar( 'Contents' );
$contentsOverride       = eZHTTPTool::getVar( 'Contents_Override' );
$currencyID             = eZHTTPTool::getVar( 'CurrencyID' );
$currencyName           = eZHTTPTool::getVar( 'CurrencyName' );
$currencySign           = eZHTTPTool::getVar( 'CurrencySign' );
$currencyValue          = eZHTTPTool::getVar( 'CurrencyValue' );
$customerEmail          = eZHTTPTool::getVar( 'CustomerEmail' );
$customerID             = eZHTTPTool::getVar( 'CustomerID' );
$dateInStock            = eZHTTPTool::getVar( 'DateInStock' );
$defaultDealerPriceGroup = eZHTTPTool::getVar( 'DefaultDealerPriceGroup' );
$defaultSectionsName    = eZHTTPTool::getVar( 'DefaultSectionsName' );
$defaultTypeID          = eZHTTPTool::getVar( 'DefaultTypeID' );
$delete                 = eZHTTPTool::getVar( 'Delete' );
$deleteArrayID          = eZHTTPTool::getVar( 'DeleteArrayID' ) ?? [];
$deleteAttributes       = eZHTTPTool::getVar( 'DeleteAttributes' );
$deleteCategories       = eZHTTPTool::getVar( 'DeleteCategories' );
$deleteGroup            = eZHTTPTool::getVar( 'DeleteGroup' );
$deleteID               = eZHTTPTool::getVar( 'DeleteID' );
$deleteImage            = eZHTTPTool::getVar( 'DeleteImage' );
$deleteOption           = eZHTTPTool::getVar( 'DeleteOption' );
$deleteOptionID         = eZHTTPTool::getVar( 'DeleteOptionID' );
$deleteProducts         = eZHTTPTool::getVar( 'DeleteProducts' );
$deleteSelected         = eZHTTPTool::getVar( 'DeleteSelected' );
$deleteType             = eZHTTPTool::getVar( 'DeleteType' );
$description            = eZHTTPTool::getVar( 'Description' );
$descriptionArray       = eZHTTPTool::getVar( 'DescriptionArray' ) ?? [];
$detailView             = eZHTTPTool::getVar( 'DetailView' );
$discontinued           = eZHTTPTool::getVar( 'Discontinued' );
$expiry                 = eZHTTPTool::getVar( 'Expiry' );
$externalLink           = eZHTTPTool::getVar( 'ExternalLink' );
$fileArrayID            = eZHTTPTool::getVar( 'FileArrayID' ) ?? [];
$fileID                 = eZHTTPTool::getVar( 'FileID' );
$flatCombine            = eZHTTPTool::getVar( 'FlatCombine' );
$flatFeeUPS             = eZHTTPTool::getVar( 'FlatFeeUPS' );
$flatFeeUSPS            = eZHTTPTool::getVar( 'FlatFeeUSPS' );
$froogleKey             = eZHTTPTool::getVar( 'FroogleKey' );
$froogleServer          = eZHTTPTool::getVar( 'FroogleServer' );
$froogleServerPort      = eZHTTPTool::getVar( 'FroogleServerPort' );
$froogleUser            = eZHTTPTool::getVar( 'FroogleUser' );
$funcs                  = eZHTTPTool::getVar( 'Funcs' );
$groupID                = eZHTTPTool::getVar( 'GroupID' );
$groupName              = eZHTTPTool::getVar( 'GroupName' );
$height                 = eZHTTPTool::getVar( 'Height' );
$hide                   = eZHTTPTool::getVar( 'Hide' );
$hideFollowup           = eZHTTPTool::getVar( 'HideFollowup' );
$hotdeal                = eZHTTPTool::getVar( 'Hotdeal' );
$id                     = eZHTTPTool::getVar( 'ID' );
$iniGroup               = eZHTTPTool::getVar( 'INIGroup' );
$imageArrayID           = eZHTTPTool::getVar( 'ImageArrayID' ) ?? [];
$imageID                = eZHTTPTool::getVar( 'ImageID' );
$imageUpdateArrayID     = eZHTTPTool::getVar( 'ImageUpdateArrayID' ) ?? [];
$importCSVDir           = eZHTTPTool::getVar( 'ImportCSVDir' );
$includesVAT            = eZHTTPTool::getVar( 'IncludesVAT' );
$isHotDeal              = eZHTTPTool::getVar( 'IsHotDeal' );
$itemArrayID            = eZHTTPTool::getVar( 'ItemArrayID' ) ?? [];
$itemID                 = eZHTTPTool::getVar( 'ItemID' );
$itemToAdd              = eZHTTPTool::getVar( 'ItemToAdd' );
$keywords               = eZHTTPTool::getVar( 'Keywords' );
$language               = eZHTTPTool::getVar( 'Language' );
$length                 = eZHTTPTool::getVar( 'Length' );
$limit                  = eZHTTPTool::getVar( 'Limit' );
$listPrice              = eZHTTPTool::getVar( 'ListPrice' );
$listType               = eZHTTPTool::getVar( 'ListType' );
$mailBody               = eZHTTPTool::getVar( 'MailBody' );
$mailNotice             = eZHTTPTool::getVar( 'MailNotice' );
$mainCategoryID         = eZHTTPTool::getVar( 'MainCategoryID' );
$mainImageHeight        = eZHTTPTool::getVar( 'MainImageHeight' );
$mainImageID            = eZHTTPTool::getVar( 'MainImageID' );
$mainImageWidth         = eZHTTPTool::getVar( 'MainImageWidth' );
$maxPrice               = eZHTTPTool::getVar( 'MaxPrice' );
$minHeaders             = eZHTTPTool::getVar( 'MinHeaders' );
$minPrice               = eZHTTPTool::getVar( 'MinPrice' );
$minValues              = eZHTTPTool::getVar( 'MinValues' );
$moduleList             = eZHTTPTool::getVar( 'ModuleList' );
$moduleName             = eZHTTPTool::getVar( 'ModuleName' );
$modulePrint            = eZHTTPTool::getVar( 'ModulePrint' );
$moduleView             = eZHTTPTool::getVar( 'ModuleView' );
$moveDown               = eZHTTPTool::getVar( 'MoveDown' );
$moveUp                 = eZHTTPTool::getVar( 'MoveUp' );
$name                   = eZHTTPTool::getVar( 'Name' );
$namedQuantity          = eZHTTPTool::getVar( 'NamedQuantity' );
$new                    = eZHTTPTool::getVar( 'New' );
$newAttribute           = eZHTTPTool::getVar( 'NewAttribute' );
$newCaption             = eZHTTPTool::getVar( 'NewCaption' );
$newDescription         = eZHTTPTool::getVar( 'NewDescription' );
$newImage               = eZHTTPTool::getVar( 'NewImage' );
$newPriceGroup          = eZHTTPTool::getVar( 'NewPriceGroup' );
$newValue               = eZHTTPTool::getVar( 'NewValue' );
$noMainImage            = eZHTTPTool::getVar( 'NoMainImage' );
$noMiniImage            = eZHTTPTool::getVar( 'NoMiniImage' );
$normalView             = eZHTTPTool::getVar( 'NormalView' );
$ok                     = eZHTTPTool::getVar( 'OK' ) ?? eZHTTPTool::getVar( 'Ok' );
$offset                 = eZHTTPTool::getVar( 'Offset' );
$oldCaption             = eZHTTPTool::getVar( 'OldCaption' );
$optionDelete           = eZHTTPTool::getVar( 'OptionDelete' );
$optionDescriptionDelete = eZHTTPTool::getVar( 'OptionDescriptionDelete' );
$optionID               = eZHTTPTool::getVar( 'OptionID' );
$optionMainPrice        = eZHTTPTool::getVar( 'OptionMainPrice' );
$optionName             = eZHTTPTool::getVar( 'OptionName' );
$optionPrice            = eZHTTPTool::getVar( 'OptionPrice' );
$optionQuantity         = eZHTTPTool::getVar( 'OptionQuantity' );
$optionValue            = eZHTTPTool::getVar( 'OptionValue' );
$optionValueDescription = eZHTTPTool::getVar( 'OptionValueDescription' );
$optionValueID          = eZHTTPTool::getVar( 'OptionValueID' );
$orderArrayID           = eZHTTPTool::getVar( 'OrderArrayID' ) ?? [];
$orderBy                = eZHTTPTool::getVar( 'OrderBy' );
$orderID                = eZHTTPTool::getVar( 'OrderID' );
$orderSenderEmail       = eZHTTPTool::getVar( 'OrderSenderEmail' );
$orderType              = eZHTTPTool::getVar( 'OrderType' );
$parentID               = eZHTTPTool::getVar( 'ParentID' );
$password               = eZHTTPTool::getVar( 'Password' );
$preferencesSetting     = eZHTTPTool::getVar( 'PreferencesSetting' );
$preview                = eZHTTPTool::getVar( 'Preview' );
$price                  = eZHTTPTool::getVar( 'Price' );
$priceGroup             = eZHTTPTool::getVar( 'PriceGroup' );
$priceGroupID           = eZHTTPTool::getVar( 'PriceGroupID' );
$priceID                = eZHTTPTool::getVar( 'PriceID' );
$pricesIncludeVAT       = eZHTTPTool::getVar( 'PricesIncludeVAT' );
$printableVersion       = eZHTTPTool::getVar( 'PrintableVersion' );
$productArrayID         = eZHTTPTool::getVar( 'ProductArrayID' ) ?? [];
$productEditArrayID     = eZHTTPTool::getVar( 'ProductEditArrayID' ) ?? [];
$productID              = eZHTTPTool::getVar( 'ProductID' );
$productImport          = eZHTTPTool::getVar( 'ProductImport' );
$productNumber          = eZHTTPTool::getVar( 'ProductNumber' );
$quantity               = eZHTTPTool::getVar( 'Quantity' );
$query                  = eZHTTPTool::getVar( 'Query' );
$queryText              = eZHTTPTool::getVar( 'QueryText' );
$readGroupArray         = eZHTTPTool::getVar( 'ReadGroupArray' ) ?? [];
$requireQuantity        = eZHTTPTool::getVar( 'RequireQuantity' );
$requireUserLogin       = eZHTTPTool::getVar( 'RequireUserLogin' );
$search                 = eZHTTPTool::getVar( 'Search' );
$searchText             = eZHTTPTool::getVar( 'SearchText' );
$sectionID              = eZHTTPTool::getVar( 'SectionID' );
$shippingGroup          = eZHTTPTool::getVar( 'ShippingGroup' );
$shippingGroupID        = eZHTTPTool::getVar( 'ShippingGroupID' );
$show                   = eZHTTPTool::getVar( 'Show' );
$showCart               = eZHTTPTool::getVar( 'ShowCart' );
$showExTaxColumn        = eZHTTPTool::getVar( 'ShowExTaxColumn' );
$showExTaxTotal         = eZHTTPTool::getVar( 'ShowExTaxTotal' );
$showIncTaxColumn       = eZHTTPTool::getVar( 'ShowIncTaxColumn' );
$showModuleLinker       = eZHTTPTool::getVar( 'ShowModuleLinker' );
$showNamedQuantity      = eZHTTPTool::getVar( 'ShowNamedQuantity' );
$showOptionQuantity     = eZHTTPTool::getVar( 'ShowOptionQuantity' );
$showPrice              = eZHTTPTool::getVar( 'ShowPrice' );
$showPriceGroups        = eZHTTPTool::getVar( 'ShowPriceGroups' );
$showQuantity           = eZHTTPTool::getVar( 'ShowQuantity' );
$showSavingsColumn      = eZHTTPTool::getVar( 'ShowSavingsColumn' );
$simpleOptionHeaders    = eZHTTPTool::getVar( 'SimpleOptionHeaders' );
$siteDesign             = eZHTTPTool::getVar( 'SiteDesign' ) ?? $siteDesign;
$sitePath               = eZHTTPTool::getVar( 'SitePath' ) ?? $sitePath ?? '';
$siteStyle              = eZHTTPTool::getVar( 'SiteStyle' ) ?? $siteDesign;
$siteURL                = eZHTTPTool::getVar( 'SiteURL' ) ?? $siteURL ?? '';
$smallImageHeight       = eZHTTPTool::getVar( 'SmallImageHeight' );
$smallImageWidth        = eZHTTPTool::getVar( 'SmallImageWidth' );
$sortMode               = eZHTTPTool::getVar( 'SortMode' );
$startValue             = eZHTTPTool::getVar( 'StartValue' );
$statusComment          = eZHTTPTool::getVar( 'StatusComment' );
$statusID               = eZHTTPTool::getVar( 'StatusID' );
$stdHeaders             = eZHTTPTool::getVar( 'StdHeaders' );
$stock                  = eZHTTPTool::getVar( 'Stock' );
$stockDay               = eZHTTPTool::getVar( 'StockDay' );
$stockMonth             = eZHTTPTool::getVar( 'StockMonth' );
$stockYear              = eZHTTPTool::getVar( 'StockYear' );
$store                  = eZHTTPTool::getVar( 'Store' );
$subTotalsColumns       = eZHTTPTool::getVar( 'SubTotalsColumns' );
$submitPrice            = eZHTTPTool::getVar( 'SubmitPrice' );
$thumbnailImageID       = eZHTTPTool::getVar( 'ThumbnailImageID' );
$totalTypes             = eZHTTPTool::getVar( 'TotalTypes' );
$typeID                 = eZHTTPTool::getVar( 'TypeID' );
$typeName               = eZHTTPTool::getVar( 'TypeName' );
$urlQueryText           = eZHTTPTool::getVar( 'URLQueryText' );
$urls                   = eZHTTPTool::getVar( 'URLS' );
$unit                   = eZHTTPTool::getVar( 'Unit' );
$update                 = eZHTTPTool::getVar( 'Update' );
$updateImages           = eZHTTPTool::getVar( 'UpdateImages' );
$updateProducts         = eZHTTPTool::getVar( 'UpdateProducts' );
$useVoucher             = eZHTTPTool::getVar( 'UseVoucher' );
$userName               = eZHTTPTool::getVar( 'UserName' );
$vatTypeID              = eZHTTPTool::getVar( 'VATTypeID' );
$valueCount             = eZHTTPTool::getVar( 'ValueCount' );
$valueGroupID           = eZHTTPTool::getVar( 'ValueGroupID' );
$valueID                = eZHTTPTool::getVar( 'ValueID' );
$valueTypeID            = eZHTTPTool::getVar( 'ValueTypeID' );
$vatArrayID             = eZHTTPTool::getVar( 'VatArrayID' ) ?? [];
$vatID                  = eZHTTPTool::getVar( 'VatID' );
$vatName                = eZHTTPTool::getVar( 'VatName' );
$vatType                = eZHTTPTool::getVar( 'VatType' );
$vatValue               = eZHTTPTool::getVar( 'VatValue' );
$voucherID              = eZHTTPTool::getVar( 'VoucherID' );
$weight                 = eZHTTPTool::getVar( 'Weight' );
$width                  = eZHTTPTool::getVar( 'Width' );
$writeGroupArray        = eZHTTPTool::getVar( 'WriteGroupArray' ) ?? [];
$yahooKey               = eZHTTPTool::getVar( 'YahooKey' );
$yahooServer            = eZHTTPTool::getVar( 'YahooServer' );
$yahooServerPort        = eZHTTPTool::getVar( 'YahooServerPort' );
$yahooUser              = eZHTTPTool::getVar( 'YahooUser' );
switch ( $url_array[2] )
{
    case "funkyhomo" :
    {

        if ( isset( $addType ) )
            $action = "AddType";

        if ( isset( $addGroup ) )
            $action = "AddGroup";

        if ( isset( $store ) )
            $action = "Store";

        if ( isset( $delete ) )
            $action = "DeleteSelected";


        include( "kernel/eztrade/admin/shippingtypes.php" );
    }
    break;
    /*
    case "export" :
    {
      // automated ftp or manual ?
      if ( $url_array[3] == 'froogle' )
      {  
	if ( $url_array[4] == 'download' )
	{
	  $action = "export";
	} 
	else {
	  // $action = "export-cron";
	  $action = "display";
	}

	include( "kernel/eztrade/admin/export_froogle.php" );
      } 
      elseif ( $url_array[3] == 'yahoo' )
      {  
        if ( $url_array[4] == 'download' )
	{
	  $action = "export";
	}
        else {
          // $action = "export-cron";
          $action = "display";
        }

        include( "kernel/eztrade/admin/export_yahoo.php" );
      } 
      elseif ( $url_array[3] == 'download' )
      {  
	$action = "export";
        include( "kernel/eztrade/admin/export_froogle.php" );
      } 
      else {
	// $action = "export-cron";
	$action = "display";
	include( "kernel/eztrade/admin/export_froogle.php" );
      }

      // include( "kernel/eztrade/admin/froogle.php" );
    }
    break;
    */

    case "export" :
    {
      if ( $url_array[3] == 'froogle' )
      {
	if ( $url_array[4] == 'download' )
	{
	  $action = "export";
        }
	else
	{
	  $action = "export-cron";
        }
	include( "kernel/eztrade/admin/export_froogle.php" );
      }
      elseif ( $url_array[3] == 'yahoo' )
      {
	if ( $url_array[4] == 'download' )
	{
	  $action = "export";
        }
	else
	{
	  $action = "export-cron";
        }
	include( "kernel/eztrade/admin/export_yahoo.php" );
      }
      else {
	$action = "export-cron";
	include( "kernel/eztrade/admin/export_froogle.php" );
      }
    }
    break;

    case "orderlist" :
    {
        if ( $url_array[3] != "" )
            $offset = $url_array[3];
        else
            $offset = 0;
        
        if( isset( $_REQUEST['SortBy'] ) )
            $orderBy = $_REQUEST['SortBy'];
        else
            $orderBy = "Date";

        include( "kernel/eztrade/admin/orderlist.php" );
    }
    break;

    case "orderedit" :
        $orderID = $url_array[3];
        isset( $url_array[4] ) ? $action = $url_array[4] : $action = "Edit";
        include( "kernel/eztrade/admin/orderedit.php" );
    break;

    case "orderview" :
    {
        $orderID = $url_array[3];
        include( "kernel/eztrade/admin/orderview.php" );
    }
    break;

    case "customerlist" :
    {
        if ( $url_array[3] != "" )
            $offset = $url_array[3];
        else
            $offset = 0;

        include( "kernel/eztrade/admin/customerlist.php" );
    }
    break;

    case "customerview" :
    {
        $customerID = $url_array[3];

        include( "kernel/eztrade/admin/customerview.php" );
    }
    break;


    case "categorylist" :
    {
      if( isset( $detailView ) && $detailView ){
        if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
        {
            $parentID = $url_array[4];

            if( isset( $url_array[5] ) )
            $offset = $url_array[5];

            include( "kernel/eztrade/admin/categorylist.php" );
        }
        else
        {
            include( "kernel/eztrade/admin/detailcategorylist.php" );
        }
      }else{
        // else assume ( $normalView )

	if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
	{
            $parentID = $url_array[4];
            if( isset( $url_array[5] ) )
                $offset = $url_array[5];
            else
                $offset = 0;
            include( "kernel/eztrade/admin/simplecategorylist.php" );
	}
    else
	{
	  include( "kernel/eztrade/admin/simplecategorylist.php" );
    }
//             include( "kernel/eztrade/admin/categorylist.php" );

      }
    }
        break;

    case "typelist" :
    {
        include( "kernel/eztrade/admin/typelist.php" );
    }
    break;

    case "voucherlist" :
    {
        include( "kernel/eztrade/admin/voucherlist.php" );
    }
    break;

    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $typeID = $url_array[4];
            $action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $typeID = $url_array[4];
            $action = "Delete";
        }
        if ( $url_array[3] == "up" )
        {
            $typeID = $url_array[4];
            $attributeID = $url_array[5];
            $action = "up";
        }
        if ( $url_array[3] == "down" )
        {
            $typeID = $url_array[4];
            $attributeID = $url_array[5];
            $action = "down";
        }

        if ( !isset( $action ) )
        {
            $action = "New";
        }
        include( "kernel/eztrade/admin/typeedit.php" );
    }
    break;

    case "voucheredit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $voucherID = $url_array[4];
            $action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $voucherID = $url_array[4];
            $action = "Delete";
        }
        include( "kernel/eztrade/admin/voucheredit.php" );
    }
    break;

    case "categoryedit" :
        if ( ( $url_array[3] == "insert") )
        {
            $action = "Insert";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "edit") )
        {
            $action = "Edit";
            $categoryID = $url_array[4];
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "update") )
        {
            $action = "Update";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "delete") )
        {
            $action = "Delete";
            $categoryID = $url_array[4];
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        else
        {
            $action = "New";
            include( "kernel/eztrade/admin/categoryedit.php" );
        }
        break;

    case "voucher" :
        $useVoucher = true;
    case "productedit" :
    {
        switch ( $url_array[3] )
        {
		
			   // filelist
             case "filelist" :
                {
                    $productID = $url_array[4];
                    include( "kernel/eztrade/admin/filelist.php" );
                    break;
                }
				
            // preview
            case "productpreview" :
                $productID = $url_array[4];
                include( "kernel/eztrade/admin/productpreview.php" );
                break;

			//files	
				case "fileedit" :
            {
                if ( isSet( $browse ) )
                {
                    include( "kernel/ezfilemanager/admin/browse.php" );
                    break;
                }
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $action = "New";
                        $productID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $action = "Edit";
                        $productID = $url_array[6];
                        $fileID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

                    case "delete" :
                    {
                        $action = "Delete";
                        $productID = $url_array[6];
                        $fileID = $url_array[5];
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                    break;

		    default :
                    {
                        include( "kernel/eztrade/admin/fileedit.php" );
                    }
                }
            }
            break;
				

            // Images
            case "imagelist" :
                $productID = $url_array[4];
                include( "kernel/eztrade/admin/imagelist.php" );
                break;

            case "imageedit" :
                if ( isset ( $browse ) )
                {
                    include ( "kernel/ezimagecatalogue/admin/browse.php" );
                    break;
                }
                if ( $url_array[4] == "edit" )
                {
                    $action = "Edit";
                    $imageID = $url_array[5];
                    $productID = $url_array[6];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $action = "Delete";
                    $imageID = $url_array[5];
                    $productID = $url_array[6];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $productID = $url_array[5];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "storedef" )
                {
                    $action = "StoreDef";
                    if ( isset( $deleteSelected ) )
                        $action = "Delete";
		    if ( isset( $updateImages ) )
                        $action = "UpdateImages";
                    $productID = $url_array[5];
                    include( "kernel/eztrade/admin/imageedit.php" );
                }
                else
                {
                    include( "kernel/eztrade/admin/imageedit.php" );
                }

                break;

            // Options
            case "optionlist" :
                $productID = $url_array[4];
                include( "kernel/eztrade/admin/optionlist.php" );
                break;

            case "attributeedit" :
            {
                $productID = $url_array[4];
                include( "kernel/eztrade/admin/attributeedit.php" );
            }
            break;

            case "formlist":
            {
                $productID = $url_array[4];
                include( "kernel/eztrade/admin/formlist.php" );
            }
            break;


            case "link" :
            {
                $itemID = $url_array[5];
                // include_once( "kernel/eztrade/classes/ezproduct.php" );
                // include_once( "kernel/eztrade/classes/ezproducttool.php" );

                $iniGroup = "eZTradeMain";
                $defaultSectionsName = "ProductLinkSections";
                $preferencesSetting = "ProductLinkType";
                $clientModuleName = "eZTrade";
                $clientModuleType = "Product";
                $root = "/trade/productedit";
                $urls = array( "back" => "$root/edit/%s",
                               "linklist" => "$root/link/list/%s",
                               "linkmoveup" => "$root/link/moveup/link/%d/%d/%d",
                               "linkmovedown" => "$root/link/movedown/link/%d/%d/%d",
                               "sectionmoveup" => "$root/link/moveup/section/%d/%d",
                               "sectionmovedown" => "$root/link/movedown/section/%d/%d",
                               "linkselect" => "$root/link/select/%s/%s/%s/%s/%s/0/%s",
                               "linkselect_basic" => "$root/link/select/",
                               "linkselect_std" => "$root/link/select/%s/%s/%s/%s/%s",
                               "urledit" => "$root/link/select/%s/%s/%s/%s",
                               "linkedit" => "$root/link/select/%s/%s/%s/0/0/%s" );
                $funcs = array( "delete" => "deleteCacheHelper" );

                function deleteCacheHelper( $productID )
                    {
                        eZProductTool::deleteCache( $productID );
                    }

                switch( $url_array[4] )
                {
                    case "list":
                    {
                        include( "classes/admin/linklist.php" );
                        break;
                    }
                    case "select":
                    {
                        if ( isset( $url_array[6] ) )
                            $moduleName = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $type = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $sectionID = $url_array[8];
                        if ( isset( $url_array[9] ) )
                            $category = $url_array[9];
                        if ( isset( $url_array[10] ) )
                            $offset = $url_array[10];
                        if ( isset( $url_array[11] ) )
                            $linkID = $url_array[11];

                        include( "classes/admin/linkselect.php" );
                        break;
                    }
                    case "moveup":
                        $moveUp = true;
                    case "movedown":
                    {
                        if ( isset( $url_array[5] ) )
                            $objectType = $url_array[5];
                        if ( isset( $url_array[6] ) )
                            $itemID = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $objectID = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $linkID = $url_array[8];
                        include( "classes/admin/linkmove.php" );
                        break;
                    }
                    default:
                    {
                        eZHTTPTool::header( "Location: /error/404" );
                        break;
                    }
                }
                break;
            }

            case "optionedit" :
                if ( $url_array[4] == "edit" )
                {
                    $action = "Edit";
                    $optionID = $url_array[5];
                    $productID = $url_array[6];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $action = "Delete";
                    $optionID = $url_array[5];
                    $productID = $url_array[6];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $action = "New";
                    $productID = $url_array[5];
                    include( "kernel/eztrade/admin/optionedit.php" );
                }
                else
                {
                    include( "kernel/eztrade/admin/optionedit.php" );
                }

                break;

            case "insert" :
                $action = "Insert";
                include( "kernel/eztrade/admin/productedit.php" );
                break;
            case "edit" :
            {
                $action = "Edit";
                $productID = $url_array[4];
                if( eZObjectPermission::hasPermission( $productID, "trade_product", 'w' ) )
                {
                    include( "kernel/eztrade/admin/productedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;
            case "update" :
                $action = "Update";
                include( "kernel/eztrade/admin/productedit.php" );
                break;

            case "cancel" :
            {
                $action = "Cancel";
                include( "kernel/eztrade/admin/productedit.php" );
            }
            break;

            case "delete" :
                $action = "Delete";
                $productID = $url_array[4];
                if( eZObjectPermission::hasPermission( $productID, "trade_product", 'w' ) )
                {
                    include( "kernel/eztrade/admin/productedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
                break;

            case "voucheredit":
                include( "kernel/eztrade/admin/voucheredit.php" );
                break;
            default:
                include( "kernel/eztrade/admin/productedit.php" );
                break;
        }
    }
        break;

    case "vattypes" :
    {
        if ( isset( $Add ) )
            $action = "Add";

        if ( isset( $store ) )
            $action = "Store";

        if ( isset( $delete ) )
            $action = "Delete";

        include( "kernel/eztrade/admin/vattypes.php" );
    }
    break;

    case "boxtypes" :
    {
        if ( isset( $Add ) )
            $action = "Add";

        if ( isset( $store ) )
            $action = "Store";

        if ( isset( $delete ) )
            $action = "Delete";

        include( "kernel/eztrade/admin/boxtypes.php" );
    }
    break;

    case "shippingtypes" :
    {
        if ( isset( $addType ) )
            $action = "AddType";

        if ( isset( $addGroup ) )
            $action = "AddGroup";

        if ( isset( $store ) )
            $action = "Store";

        if ( isset( $delete ) )
            $action = "DeleteSelected";


        include( "kernel/eztrade/admin/shippingtypes.php" );
        break;
    }

    case "pricegroups":
    {
        $action = $url_array[3];
        switch( $action )
        {
            case "list":
            {
                include( "kernel/eztrade/admin/pricegroups.php" );
                break;
            }
            case "new":
            case "edit":
            {
                if ( !isset( $priceID ) )
                    $priceID = $url_array[4];
                include( "kernel/eztrade/admin/pricegroupedit.php" );
                break;
            }
        }
        break;
    }

    case "search":
    {
        $offset = $url_array[3];
        if ( isset( $query ) )
            $search = $query;
        else
            $search = $url_array[4];
        include( "kernel/eztrade/admin/productsearch.php" );
        break;
    }

    case "currency" :
    {
        if ( isset( $addCurrency ) )
            $action = "AddCurrency";

        if ( isset( $store ) )
            $action = "Store";

        if ( isset( $delete ) )
            $action = "DeleteSelected";


        include( "kernel/eztrade/admin/currency.php" );
        break;
    }

    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>