<?php
//
// $Id: productedit.php 9904 2004-07-09 11:44:47Z br $
//
// Created on: <19-Sep-2000 10:56:05 bf>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezcachefile.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );
// include_once( "eztrade/classes/ezpricegroup.php" );
// include_once( "eztrade/classes/ezproductpermission.php" );
// include_once( "eztrade/classes/ezproductpricerange.php" );

// include_once( "ezxml/classes/ezxml.php" );

function deleteCache( $ProductID, $CategoryID, $CategoryArray, $Hotdeal )
{
    if ( is_a( $ProductID, "eZProduct" ) )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $Hotdeal = $ProductID->isHotDeal();
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "kernel/eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "kernel/eztrade/cache/", array( "productlist",
                                                          array_merge( array( $CategoryID ), $CategoryArray ) ),
                                 "cache", "," );

    foreach ( $files as $file )
    {
        $file->delete();
    }
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "kernel/eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }
    $files =& eZCacheFile::files( "kernel/ezarticle/cache/",
                                  array( "articlefrontpage",
                                         NULL,
                                         NULL),
                                  "cache", "," );
    foreach( $files as $file )
    {
        $file->delete();
    }

}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTradeMain", "Language" );
$ShowPriceGroups = $ini->read_var( "eZTradeMain", "PriceGroupsEnabled" ) == "true";
$ShowQuantity = $ini->read_var( "eZTradeMain", "ShowQuantity" ) == "true";
$ShowModuleLinker = $ini->read_var( "eZTradeMain", "ShowModuleLinker" ) == "true";

$CSVDelimiter = $ini->read_var( "eZTradeMain", "CSVDelimiter" );
$DefaultDealerPriceGroup = $ini->read_var( "eZTradeMain", "DefaultDealerPriceGroup" );

// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezproductcategory.php" );
// include_once( "eztrade/classes/ezvattype.php" );
// include_once( "eztrade/classes/ezshippinggroup.php" );

// must have to generate XML
// include_once( "ezarticle/classes/ezarticlegenerator.php" );

if ( isset($CSVImport) )
{
	// include_once( "eztrade/classes/ezcsvimport.php" );
	//get default target category ID
	$CategoryID = $ini->read_var( "eZTradeMain", "CSVImportCat" );
	//read CSV file
	$ProductImport = new eZCSVImport();
	$csvArray =  $ProductImport->csvFileToArray($ImportCSVDir, $CSVDelimiter, 'none', TRUE, TRUE, FALSE);
//	print_r($csvArray); exit();
	foreach ( $csvArray as $row )
	{
		// make a new product
		// build the product
		/*
			$row[0] = Name
			$row[1] = Product_Number
			$row[2] = Keywords
			$row[3] = Lead_In
			$row[4] = Description
			$row[5] = External_Link
			$row[6] = Quantity
			$row[7] = StockDate
			$row[8] = Shipping_group
			$row[9] = Weight
			$row[10] = BoxType
			$row[11] = Duration
			$row[12] = IsHotDeal
			$row[13] = Discontinued
			$row[14] = Tax_Amount
			$row[15] = IncludesVAT
			$row[16] = Price
			$row[17] = ListPrice
			$row[18] = DealerPrice
			$row[19] = Show_Product
			$row[20] = Show_Price
			$row[21] = FlatUPS
			$row[22] = FlatUSPS
			$row[23] = FlatCombine
		*/
		
		$product = new eZProduct();
		$generator = new eZArticleGenerator();		
		
		// check to see if product already exists
		$item = $product->findProductNumber($row[1]);
		// update fields if true
		if ( $item )
		{
		    if ( eZXML::domTree( $contents ) )
    		{
    			if ( $item->name() != $row[0] )
					$item->setName( $row[0] );
				
				$contentsArray =& $generator->decodeXML( $item->contents() );

				if ( $contentsArray[0] != $row[3] )
					$item->setBrief( $row[3] );
				if ( $contentsArray[1] != $row[4] )
					$item->setDescription( $row[4] );
				
				if ( ($contentsArray[0] != $row[3]) || ($contentsArray[1] != $row[4]) )
				{
					$Contents = array( $row[3], $row[4] );
				    $contents =& $generator->generateXML( $Contents );
					$item->setContents( $contents );
				}
				
    	    	if ( $item->keywords() != $row[2] )
					$item->setKeywords( $row[2]  );
				if ( $item->productNumber != $row[1] )
				   	$item->setProductNumber( $row[1] );
				if ( $item->externalLink != $row[5] )
	        		$item->setExternalLink( $row[5] );
				//set delivery date if quantity = 0
				if ( is_numeric($row[6]) && $row[6] == 0 && $row[7] != "" )
					{
						$dateArray = explode('/', $row[7]);
			            $DateInStock = new eZDate( $dateArray[2], $dateArray[1], $dateArray[0] );
						$date = $DateInStock->timeStamp();
						if ( $item->stockDate != $date )
							$item->setStockDate( $date );
					}
				
				if ( $row[6] > 0 || $row[7] == "" )
					$item->setStockDate('');
				
		        $vattype = $item->vatType();
				if ( $vattype->id() != $row[14] && $row[14] > 0 )
					{
						$newVATType = new eZVATType( $row[14] );
	    	    		$item->setVATType( $newVATType );
					}
			
				$boxtype = $item->boxType();
				if ($boxtype && $boxtype->id() != $row[10] && $row[10] > 0 )
				{
		        	$newBoxType = new eZBoxType( $row[10] );
    	    		$item->setBoxType( $newBoxType );
				}
				
				if ($boxtype && $row[10]=='' )
				{
		        	$newBoxType = new eZBoxType();
    	    		$item->setBoxType( $newBoxType );
				}
				
				if ( !$item->shippingGroup() )
					$item->setShippingGroup( $row[8] );
				
				$shippingGroup = $item->shippingGroup();

				if ( $shippingGroup && $shippingGroup->id() != $row[8] )
	    	    {
					$newShippingGroup = new eZShippingGroup( $row[8] );
    			    $item->setShippingGroup( $newShippingGroup );
				}
    
    		    if ( $row[20] == "on" )
    	    	   	$item->setShowPrice( true );
	       		else if ( $row[20] == "off" )
    	    	    $item->setShowPrice( false );
        		if ( $item->weight() != $row[9] )
					$item->setWeight( $row[9] );
				//product is not a voucher
				$item->setProductType( 1 );
    	        $item->setShowProduct( $row[19] == "on" );
	        	$item->setDiscontinued( $row[13] == "on" );

	    	    if ( $row[12] == "on" )
    		    	$item->setIsHotDeal( true );
	        	else if ( $row[12] == "off" )
     				$item->setIsHotDeal( false );
           		$item->setPrice( $row[16] );
				$item->setListPrice( $row[17] );
	    	    if ( $row[15] == "true" )
    		  	    $item->setIncludesVAT( true );
        	    else if ( $row[15] == "false" )
        			$item->setIncludesVAT( false );
				if ( $row[11] > 0 )
	            	$item->setExpiryTime( $row[11] );
	            // Flat Rate 	
				  if ( is_numeric( $row[21] ) )
				   $item->setFlatUPS( $row[21] );
				  else
				   $item->setFlatUPS( 'off' );
				   
				  if ( is_numeric( $row[22] ) )
				   $item->setFlatUSPS( $row[22] );
				  else
				   $item->setFlatUSPS( 'off' );
				
				$item->setFlatCombine( $row[23] );

				//store product
    		    $item->store();
   		     	$itemID = $item->id();
				//set dealer price
				if ( $row[18] != '' )
				{
		            eZPriceGroup::removePrices( $itemID, -1, -1, $DefaultDealerPriceGroup );
					eZPriceGroup::addPrice( $itemID, $DefaultDealerPriceGroup, $row[18] );
				}
				
				if ( $row[18] == '' )
				    eZPriceGroup::removePrices( $itemID, -1, -1, $DefaultDealerPriceGroup );
										
				//set quantity data
				$item->setTotalQuantity( is_numeric( $row[6] ) ? $row[6] : false );
				// setup the category
			//	$category = new eZProductCategory( $CategoryID );
			//	$item->setCategoryDefinition( $category );
			//	eZProductCategory::addProduct( $item, $CategoryID );
				// set write permissions
    		/*    eZObjectPermission::removePermissions( $itemID, "trade_product", 'w' );
	        	eZObjectPermission::setPermission( 1, $itemID, "trade_product", 'w' );
            	// set read permissions
    		    eZObjectPermission::removePermissions( $itemID, "trade_product", 'r' );
    	    	eZObjectPermission::setPermission( -1, $itemID, "trade_product", 'r' );  */
			}
		
		}

	
		if (!$item)
		{	
			$Contents = array( $row[3], $row[4] );
//    	$generator = new eZArticleGenerator();
    		$contents =& $generator->generateXML( $Contents );

	   		if ( eZXML::domTree( $contents ) )
    		{
    			$product->setName( $row[0] );	
				$product->setContents( $contents );
				$product->setBrief( $row[3] );
				$product->setDescription( $row[4] );
        		$product->setKeywords( $row[2]  );
        		$product->setProductNumber( $row[1] );
        		$product->setExternalLink( $row[5] );
				//set delivery date if quantity = 0
				if ( is_numeric($row[6]) && $row[6] == 0 )
					{
						$dateArray = explode('/', $row[7]);
		            	$DateInStock = new eZDate( $dateArray[2], $dateArray[1], $dateArray[0] );
						$date = $DateInStock->timeStamp();
						$product->setStockDate( $date );
					}
				
	    	    $vattype = new eZVATType( $row[14] );
    	    	$product->setVATType( $vattype );
			
				if ($row[10])
				{
	        		$boxtype = new eZBoxType( $row[10] );
    	    		$product->setBoxType( $boxtype );
				}			
	        	$shippingGroup = new eZShippingGroup( $row[8] );
    	    	$product->setShippingGroup( $shippingGroup );
    
    		    if ( $row[20] == "on" )
        		   	$product->setShowPrice( true );
       			else if ( $row[20] == "off" )
    	        	$product->setShowPrice( false );
        		if ( $row[9] )
					$product->setWeight( $row[9] );
				//product is not a voucher
				$product->setProductType( 1 );
            	$product->setShowProduct( $row[19] == "on" );
        		$product->setDiscontinued( $row[13] == "on" );

		        if ( $row[12] == "on" )
    		    	$product->setIsHotDeal( true );
        		else if ( $row[12] == "off" )
     				$product->setIsHotDeal( false );
           		$product->setPrice( $row[16] );
				$product->setListPrice( $row[17] );
	        	if ( $row[15] == "true" )
    	  		    $product->setIncludesVAT( true );
            	else if ( $row[15] == "false" )
        			$product->setIncludesVAT( false );
				if ( $row[11] > 0 )
            		$product->setExpiryTime( $row[11] );

				  if ( is_numeric( $row[21] ) )
				   $product->setFlatUPS( $row[21] );
				  else
				   $product->setFlatUPS( 'off' );
				   
				  if ( is_numeric( $row[22] ) )
				   $product->setFlatUSPS( $row[22] );
				  else
				   $product->setFlatUSPS( 'off' );
				
				$product->setFlatCombine( $row[23] );  
				//store product
    	    	$product->store();
        		$productID = $product->id();
				if ( $row[18] != '' )
				{
		            eZPriceGroup::removePrices( $productID, -1, -1, $DefaultDealerPriceGroup );
					eZPriceGroup::addPrice( $productID, $DefaultDealerPriceGroup, $row[18] );
				}
				
				if ( $row[18] == '' )
				    eZPriceGroup::removePrices( $productID, -1, -1, $DefaultDealerPriceGroup );
				//set quantity data
				$product->setTotalQuantity( is_numeric( $row[6] ) ? $row[6] : false );
				// setup the category
				$category = new eZProductCategory( $CategoryID );
				$product->setCategoryDefinition( $category );
				eZProductCategory::addProduct( $product, $CategoryID );
				// set write permissions
    	    	eZObjectPermission::removePermissions( $productID, "trade_product", 'w' );
        		eZObjectPermission::setPermission( 1, $productID, "trade_product", 'w' );
            	// set read permissions
    	    	eZObjectPermission::removePermissions( $productID, "trade_product", 'r' );
        		eZObjectPermission::setPermission( -1, $productID, "trade_product", 'r' );
			}
		}
	}
	eZHTTPTool::header( "Location: /trade/categorylist/parent/$CategoryID/" );	
	exit();
}

if ( isSet( $SubmitPrice ) )
{
    for ( $i = 0; $i < count( $ProductEditArrayID ); $i++ )
    {
        if ( $Price[$i] != "" and is_numeric( $Price[$i] ) )
        {
            $product = new eZProduct( $ProductEditArrayID[$i] );
            $product->setPrice( $Price[$i] );
            $product->store();
            deleteCache( $product, false, false, false );
        }
    }
    if ( isset( $Query ) )
        eZHTTPTool::header( "Location: /trade/search/$Offset/$Query" );
    else
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$CategoryID/$Offset" );

    exit();
}


if ( isSet( $UpdateProducts ) )
{	
/*	echo "<pre>";
	print_r( $Discontinued );
	print_r($ShowPrice);
	echo "</pre>";
	exit ();  */

    for ( $i = 0; $i < count( $ProductEditArrayID ); $i++ )
    {
        $product = new eZProduct( $ProductEditArrayID[$i] );
        
		if ( $Name[$i] != $product->name() )
			$product->setName($Name[$i]);
			
		if ( $Price[$i] != "" and is_numeric( $Price[$i] ) )
            $product->setPrice( $Price[$i] );
			
		if ( $ListPrice[$i] != "" and is_numeric( $ListPrice[$i] ) )
            $product->setListPrice( $ListPrice[$i] );
		
//		if ( $Brief[$i] != $product->brief() || $Description[$i] != $product->description() )
//		{
			$Contents = array( $Brief[$i], $Description[$i] );
	    	$generator = new eZArticleGenerator();
    		$contents =& $generator->generateXML( $Contents );
//		}
		
	    if ( $contents && eZXML::domTree( $contents ) )
		{
			$product->setContents( $contents );
			$was_hotdeal = $product->isHotDeal();
		
			if ( $Keywords[$i] != "" )
				$product->setKeywords( $Keywords[$i]  );
		
			if ( $ProductNumber[$i] != $product->productNumber() )
        		$product->setProductNumber( $ProductNumber[$i] );
		
			if ( $ExternalLink[$i] != $product->externalLink() )
        		$product->setExternalLink( $ExternalLink[$i] );
		
			$VatType =& $product->vatType();
		
			if ( $VATTypeID[$i] and $VATTypeID[$i] != $VatType->id() )
			{
        		$vattype = new eZVATType( $VATTypeID[$i] );
        		$product->setVATType( $vattype );
			}
			
			/* Flat and Free Shipping section */
			if ( $FlatFeeUPS[$i] ) {
			  if (  is_numeric( $FlatFeeUPS[$i] ) )
			   $product->setFlatUPS( $FlatFeeUPS[$i] );
			  else
			   $product->setFlatUPS( 'off' );
			} else {
				$product->setFlatUPS('off' );	
			}
			if ( $FlatFeeUSPS[$i] ) {
			  if ( is_numeric( $FlatFeeUSPS[$i] ) )
			   $product->setFlatUSPS( $FlatFeeUSPS[$i] );
			  else
			   $product->setFlatUSPS( 'off' );
			} else {
				$product->setFlatUSPS('off' );	
			}

			$product->setFlatCombine( $FlatCombine[$i] == "on" );
//			$BoxType =& $product->boxType();
		
//			if ( $BoxTypeID[$i] > 0 && $BoxTypeID[$i] != $BoxType->id() )
			if ( $BoxTypeID[$i] > 0 )
			{
        		$boxtype = new eZBoxType( $BoxTypeID[$i] );
        		$product->setBoxType( $boxtype );
			}
			elseif ( $BoxTypeID[$i] == 0 )
        		$product->setBoxType( "" );
		
			$ShippingGroup =& $product->shippingGroup();
		
			if ( $ShippingGroupID[$i] and $ShippingGroupID[$i] != $ShippingGroup->id() )
			{
        		$shippingGroup = new eZShippingGroup( $ShippingGroupID[$i] );
        		$product->setShippingGroup( $shippingGroup );
    		}
		
        	if ( $ShowPrice[$i] == "on" )
            	$product->setShowPrice( true );
        	else
            	$product->setShowPrice( false );

			$product->setShowProduct( $Active[$i] == "on" );
			$product->setDiscontinued( $Discontinued[$i] == "on" );
		
/*			if ( $Discontinued[$i] == "on" )
	        	$product->setDiscontinued( true );
			else
				$product->setDiscontinued( false );
			
			if ( $Active[$i] == "on" )
    	        $product->setShowProduct( true );
        	else
            	$product->setShowProduct( false );
*/		
        	if ( $IsHotDeal[$i] == "on" )
            	$product->setIsHotDeal( true );
        	else
            	$product->setIsHotDeal( false );
   
			if ( $ShowQuantity && is_numeric($Quantity[$i]) && $Quantity[$i] != $product->totalQuantity() )
				$product->setTotalQuantity( is_numeric( $Quantity[$i] ) ? $Quantity[$i] : false );
				
			if ( is_numeric($Quantity[$i]) && $Quantity[$i] == 0 ) {
            	$DateInStock = new eZDate( $StockYear[$i], $StockMonth[$i], $StockDay[$i] );
				$date = $DateInStock->timeStamp();
				$product->setStockDate( $date );
			}	
			
			if ( is_numeric($Quantity[$i]) && $Quantity[$i] > 0 )
				$product->setStockDate( "" );
			
			if ( $Weight[$i]>0 && is_numeric($Weight[$i]) && $Weight[$i] != $product->weight() )
            	$product->setWeight( $Weight[$i] );
		
			if ( $IncludesVAT[$i] == "true" )
            	$product->setIncludesVAT( true );
        	else
            	$product->setIncludesVAT( false );
			$product->store();

			//update categories
		
    	    $old_maincategory = $product->categoryDefinition();
        	$old_categories = array_merge( $old_maincategory->id(), $product->categories( false ) );
        	$old_categories = array_unique( $old_categories );

	        $new_categories = array_unique( array_merge( $MainCategoryID[$i], $CategoryArray[$i] ) );
       
    	    $remove_categories = array_diff( $old_categories, $new_categories );
        	$add_categories = array_diff( $new_categories, $old_categories );
		
	        foreach ( $remove_categories as $categoryItem )
    	    {
        	  eZProductCategory::removeProduct( $product, $categoryItem );
         	}
		 	// add a product to the categories
			$category = new eZProductCategory( $MainCategoryID[$i] );
        	$product->setCategoryDefinition( $category );

	        foreach ( $add_categories as $categoryItem )
    	    {
        	    eZProductCategory::addProduct( $product, $categoryItem );
        	}

		
	        // clear the cache files.
    	    deleteCache( $ProductID, $CategoryID, $old_categories, $was_hotdeal or $product->isHotDeal() );

	    }
	}
// for array debugging use
//	echo "<pre>";
//	            print_r($CategoryArray);
//				print_r($MainCategoryID);
//				echo "<br>"."specified element:"."<br>";
//				print_r($CategoryArray[1]);
//	echo "</pre>";

	if ( isSet( $Query ) )
        eZHTTPTool::header( "Location: /trade/search/$Offset/$Query" );
    else
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$CategoryID/$Offset" );
	
    exit();


}


if ( isSet( $DeleteProducts ) )
{
    $Action = "DeleteProducts";
}

if ( isset( $Action ) && $Action == "Update"  or isset( $Action ) && $Action == "Insert" )
{
    $parentCategory = new eZProductCategory();
    $parentCategory->get( $CategoryID );

    if ( isset( $Action ) && $Action == "Insert" )
    {
        $product = new eZProduct();
    }
    else
    {
        $product = new eZProduct();
        $product->get( $ProductID );
        $was_hotdeal = $product->isHotDeal();

    }

    $product->setName( $Name );

    $generator = new eZArticleGenerator();
    $contents =& $generator->generateXML( $Contents );

    if ( eZXML::domTree( $contents ) )
    {
        $product->setContents( $contents );

        $product->setKeywords( $Keywords  );
        $product->setProductNumber( $ProductNumber );
        $product->setCatalogNumber( $CatalogNumber );
        $product->setExternalLink( $ExternalLink );

        $vattype = new eZVATType( $VatTypeID );
        $product->setVATType( $vattype );

		$boxtype = new eZBoxType( $BoxTypeID );
        $product->setBoxType( $boxtype );

        $shippingGroup = new eZShippingGroup( $ShippingGroupID );
        $product->setShippingGroup( $shippingGroup );

        if ( $ShowPrice == "on" )
        {
            $product->setShowPrice( true );
        }
        else
        {
            $product->setShowPrice( false );
        }

        if ( $UseVoucher == true )
        {
            $product->setProductType( 2 );
        }
        else
        {
            $product->setProductType( 1 );
        }

        $product->setShowProduct( $Active == "on" );
        $product->setDiscontinued( $Discontinued == "on" );

        if ( $IsHotDeal == "on" )
        {
            $product->setIsHotDeal( true );
        }
        else
        {
            $product->setIsHotDeal( false );
        }

        $product->setPrice( $Price );
		$product->setListPrice( $ListPrice );

		if ( $Weight>0 && is_numeric($Weight) )
	        $product->setWeight( $Weight );

        if ( $IncludesVAT == "true" )
        {
            $product->setIncludesVAT( true );
        }
        else
        {
            $product->setIncludesVAT( false );
        }
			/* Flat and Free Shipping section */
		if ( $FlatFeeUPS ) {
			if ( is_numeric( $FlatFeeUPS ) )
			$product->setFlatUPS( $FlatFeeUPS );
			else {
			 $product->setFlatUPS( 'off' );
			}
			} else {
				$product->setFlatUPS('off' );	
			}
			if ( $FlatFeeUSPS ) {
			  if ( is_numeric( $FlatFeeUSPS ) )
			   $product->setFlatUSPS( $FlatFeeUSPS );
			  else
			   $product->setFlatUSPS( 'off' );
			} else {
				$product->setFlatUSPS('off' );	
			}
			$product->setFlatCombine( $FlatCombine == "on" );

        if ( $Expiry > 0 )
            $product->setExpiryTime( $Expiry );
		
		if ( is_numeric($Quantity) && $Quantity == 0 ) {
                $DateInStock = new eZDate( $StockYear, $StockMonth, $StockDay );
				$date = $DateInStock->timeStamp();

				$product->setStockDate( $date );
				}	
		
		if ( is_numeric($Quantity) && $Quantity > 0 )
			$product->setStockDate( "" );

        $product->store();
//				print_r($product); exit();
        $productID = $product->id();

        if ( $product->productType() == 2 )
        {
            $range =& $product->priceRange();
            if ( !$range )
                $range = new eZProductPriceRange();
            $range->setMin( $MinPrice );
            $range->setMax( $MaxPrice );
            $range->setProduct( $product );
            $range->store();
        }

        if ( $ShowQuantity )
        {
            $product->setTotalQuantity( is_numeric( $Quantity ) ? $Quantity : false );
        }

        if ( $ProductID )
            eZPriceGroup::removePrices( $ProductID, -1 );

        if( isset( $PriceGroup ) && isset( $PriceGroupID ) )
        {
            $count = max( count( $PriceGroup ), count( $PriceGroupID ) );
        }
        else
        {
            $count = false;
        }
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( is_numeric( $PriceGroupID[$i] ) and $PriceGroup[$i] != "" )
            {
                eZPriceGroup::addPrice( $productID, $PriceGroupID[$i], $PriceGroup[$i] );
            }
        }


        eZObjectPermission::removePermissions( $productID, "trade_product", 'w' );
        if( isset( $WriteGroupArray ) )
        {
            if( $WriteGroupArray[0] == 0 )
            {
                eZObjectPermission::setPermission( -1, $productID, "trade_product", 'w' );
            }
            else
            {
                foreach ( $WriteGroupArray as $groupID )
                {
                    eZObjectPermission::setPermission( $groupID, $productID, "trade_product", 'w' );
                }
            }
        }
        else
        {
            eZObjectPermission::removePermissions( $productID, "trade_product", 'w' );
        }

        /* read access thingy */
        eZObjectPermission::removePermissions( $productID, "trade_product", 'r' );
        if ( isset( $ReadGroupArray ) )
        {
            if( $ReadGroupArray[0] == 0 )
            {
                eZObjectPermission::setPermission( -1, $productID, "trade_product", 'r' );
            }
            else // some groups are selected.
            {
                foreach ( $ReadGroupArray as $groupID )
                {
                    eZObjectPermission::setPermission( $groupID, $productID, "trade_product", 'r' );
                }
            }
        }
        else
        {
            eZObjectPermission::removePermissions( $productID, "trade_product", 'r' );
        }

        // Calculate which categories are new and which are unused

        if ( isset( $Action ) && $Action == "Update" )
        {
            $old_maincategory = $product->categoryDefinition();

            $old_categories = array_merge( array( $old_maincategory->id() ), $product->categories( false ) );
            $old_categories = array_unique( $old_categories );

            if( isset( $CategoryArray ) )
            {
                $new_categories = array_unique( array_merge( array( $CategoryID ), $CategoryArray ) );
            }
            else
            {
                $new_categories = array( $CategoryID );
            }
            $remove_categories = array_diff( $old_categories, $new_categories );
            $add_categories = array_diff( $new_categories, $old_categories );

            foreach ( $remove_categories as $categoryItem )
            {
                eZProductCategory::removeProduct( $product, $categoryItem );
            }
        }
        else
        {
            $add_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );
        }

        // add a product to the categories
        $category = new eZProductCategory( $CategoryID );
        $product->setCategoryDefinition( $category );

        if( empty( $add_categories ) )
        {
             $add_categories = array( $old_maincategory->id() );
        }

        foreach ( $add_categories as $categoryItem )
        {
            eZProductCategory::addProduct( $product, $categoryItem );
        }

        // clear the cache files.
        deleteCache( $ProductID, $CategoryID, $old_categories, $was_hotdeal or $product->isHotDeal() );

        // preview
        if ( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /trade/productedit/productpreview/$productID/" );
            exit();
        }

        if( isset( $AddItem ) )
        {
            switch ( $ItemToAdd )
            {
                // add options
                case "Option":
                {
                    eZHTTPTool::header( "Location: /trade/productedit/optionlist/$productID/" );
                    exit();
                }
                break;
				
			    // add files
                case "File":
                    {
                        // add files
                        eZHTTPTool::header( "Location: /trade/productedit/filelist/$productID/" );
                    exit();
                }
                break;

                // add images
                case "Image":
                {
                    eZHTTPTool::header( "Location: /trade/productedit/imagelist/$productID/" );
                    exit();
                }
                break;

                // attribute
                case "Attribute":
                {
                    eZHTTPTool::header( "Location: /trade/productedit/attributeedit/$productID/" );
                    exit();
                }
                break;

                // attribute
                case "ModuleLinker":
                {
                    eZHTTPTool::header( "Location: /trade/productedit/link/list/$productID/" );
                    exit();
                }
                break;

                case "Form":
                {
                    // add form
                    eZHTTPTool::header( "Location: /trade/productedit/formlist/$productID/" );
                    exit();
                }
                break;
            }
        }

        // get the category to redirect to
        $category = $product->categoryDefinition();
        $categoryID = $category->id();
    
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
        exit();
    }
    else
    {
        $Contents_Override = $Contents;
        if ( isset( $Action ) && $Action == "Update" )
        {
            $Action = "Edit";
        }
        else
        {
            $Action = "Insert";
        }
    }
}

if ( isset( $Action ) && $Action == "Cancel" )
{
    if ( is_numeric( $ProductID ) )
    {
        $product = new eZProduct( $ProductID );
        $category = $product->categoryDefinition();
        $categoryID = $category->id();
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID" );
        exit();
    }
    else
    {
        eZHTTPTool::header( "Location: /trade/categorylist/parent/" );
        exit();
    }
}

if ( isset( $Action ) && $Action == "DeleteProducts" )
{
    if ( count ( $ProductArrayID ) != 0 )
    {
        foreach ( $ProductArrayID as $ProductID )
        {
            $product = new eZProduct();
            $product->get( $ProductID );

            $categories = $product->categories();

            $categoryArray = $product->categories();
            $categoryIDArray = array();
            foreach ( $categoryArray as $cat )
            {
                $categoryIDArray[] = $cat->id();
            }


            // clear the cache files.
            deleteCache( $ProductID, $CategoryID, $categoryIDArray, $product->isHotDeal() );

            $category = $product->categoryDefinition( );
            $categoryID = $category->id();

            $product->delete();

            eZPriceGroup::removePrices( $ProductID, -1 );
        }
    }

    if ( isset( $Query ) )
        eZHTTPTool::header( "Location: /trade/search/$Offset/$Query" );
    else
        eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID/$Offset" );
    exit();
}

if ( isset( $Action ) && $Action == "Delete" )
{
    $product = new eZProduct();
    $product->get( $ProductID );

    $categories = $product->categories();

    $categoryArray = $product->categories();
    $categoryIDArray = array();
    foreach ( $categoryArray as $cat )
    {
        $categoryIDArray[] = $cat->id();
    }

    // clear the cache files.
    deleteCache( $ProductID, $CategoryID, $categoryIDArray, $product->isHotDeal() );

    $category = $product->categoryDefinition( );
    $categoryID = $category->id();

    $product->delete();

    eZPriceGroup::removePrices( $ProductID, -1 );

    eZHTTPTool::header( "Location: /trade/categorylist/parent/$categoryID/" );
    exit();
}

$t = new eZTemplate( "kernel/eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "kernel/eztrade/admin/intl/", $Language, "productedit.php" );

$t->set_file( "product_edit_tpl", "productedit.tpl" );

$t->set_block( "product_edit_tpl", "value_tpl", "value" );
$t->set_block( "product_edit_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "product_edit_tpl", "module_linker_button_tpl", "module_linker_button" );
$t->set_block( "product_edit_tpl", "group_item_tpl", "group_item" );

$t->set_block( "product_edit_tpl", "vat_select_tpl", "vat_select" );
$t->set_block( "product_edit_tpl", "box_select_tpl", "box_select" );
$t->set_block( "product_edit_tpl", "shipping_select_tpl", "shipping_select" );
$t->set_block( "product_edit_tpl", "quantity_item_tpl", "quantity_item" );
$t->set_block( "quantity_item_tpl", "day_item_tpl", "day_item" );

$t->set_block( "product_edit_tpl", "read_group_item_tpl", "read_group_item" );
$t->set_block( "product_edit_tpl", "write_group_item_tpl", "write_group_item" );

$t->set_block( "product_edit_tpl", "price_range_tpl", "price_range" );
$t->set_block( "product_edit_tpl", "normal_price_tpl", "normal_price" );
$t->set_block( "product_edit_tpl", "list_price_tpl", "list_price" );

$t->set_block( "product_edit_tpl", "price_group_list_tpl", "price_group_list" );
$t->set_block( "price_group_list_tpl", "price_groups_item_tpl", "price_groups_item" );
$t->set_block( "price_groups_item_tpl", "price_group_header_item_tpl", "price_group_header_item" );
$t->set_block( "price_groups_item_tpl", "price_group_item_tpl", "price_group_item" );
$t->set_block( "price_group_list_tpl", "price_groups_no_item_tpl", "price_groups_no_item" );

$t->setAllStrings();

$t->set_var( "brief_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "name_value", "" );
$t->set_var( "keywords_value", "" );
$t->set_var( "product_nr_value", "" );
$t->set_var( "product_catalog_number", "" );
$t->set_var( "price_value", "" );
$t->set_var( "list_price", "" );
$t->set_var( "expiry_value", "" );

$t->set_var( "weight_value", "");
$t->set_var( "flat_fee_ups", "");
$t->set_var( "flat_fee_usps", "");

$t->set_var( "showprice_checked", "" );
$t->set_var( "showproduct_checked", "" );
$t->set_var( "discontinued_checked", "" );
$t->set_var( "is_hot_deal_checked", "" );

$t->set_var( "price_min", "0" );
$t->set_var( "price_max", "0" );

$t->set_var( "external_link", "" );

$t->set_var( "action_value", "insert" );
$t->set_var( "flat_combine_checked", "");
$writeGroupsID = array();
$readGroupsID = array();

$PriceGroup = array();
$PriceGroupID = array();

$VatType = false;
$BoxType = false;
// edit
if ( isset( $Action ) && $Action == "Edit" )
{
    $product = new eZProduct();
    $product->get( $ProductID );
	
    $t->set_var( "name_value", htmlspecialchars($product->name()) );
    $t->set_var( "keywords_value", $product->keywords() );
    $t->set_var( "product_nr_value", $product->productNumber() );
    $t->set_var( "product_catalog_number", $product->catalogNumber() );
    $t->set_var( "price_value", $product->price() );
    $t->set_var( "list_price", $product->listPrice() );
    $t->set_var( "expiry_value", $product->expiryTime() ? $product->expiryTime() : "" );
    $t->set_var( "external_link", $product->externalLink() );

    $generator = new eZArticleGenerator();
    $contentsArray =& $generator->decodeXML( $product->contents() );

    if ( isset( $Contents_Override ) && count( $Contents_Override ) == 2 )
    {
        $t->set_var( "brief_value", $Contents_Override[0] );
        $t->set_var( "description_value", $Contents_Override[1] );
    }
    else
    {
        $t->set_var( "brief_value", $contentsArray[0] );
        $t->set_var( "description_value", $contentsArray[1] );
    }


    $t->set_var( "action_value", "update" );
    $t->set_var( "product_id", $product->id() );

    if ( $product->showPrice() == true )
        $t->set_var( "showprice_checked", "checked" );

    if ( $product->showProduct() == true )
        $t->set_var( "showproduct_checked", "checked" );

    if ( $product->discontinued() == true )
        $t->set_var( "discontinued_checked", "checked" );

    if ( $product->isHotDeal() == true )
        $t->set_var( "is_hot_deal_checked", "checked" );
		
    if ( $product->weight() > 0 )
        $t->set_var( "weight_value", $product->weight() );
	else
        $t->set_var( "weight_value", "" );	

    if ( $product->productType() == 2 )
        $t->set_var( "mark_as_voucher", "checked" );

    if ( $product->includesVAT() == true )
        $t->set_var( "include_vat", "checked" );

    if ( $product->excludedVAT() == true )
        $t->set_var( "exclude_vat", "checked" );
	
	if ($product->FlatUPS() == 'off')
	 $t->set_var( "flat_fee_ups", '' );
	else
	 $t->set_var( "flat_fee_ups", $product->FlatUPS ); 
	 
	if ($product->FlatUSPS() == 'off')
	 $t->set_var( "flat_fee_usps", '' );
	else
	 $t->set_var( "flat_fee_usps", $product->FlatUSPS ); 
	 
	if ($product->FlatCombine())
		$t->set_var( "flat_combine_checked", 'checked' );	

    $VatType =& $product->vatType();
	
    $BoxType =& $product->BoxType();

    $Quantity = $product->totalQuantity();

            if ( $product->stockDate() )
            {
                $Stock = new eZDate();
                $Stock->setTimeStamp( $product->stockDate() );	
                $StockYear = $Stock->year();
                $StockMonth = $Stock->month();
                $StockDay = $Stock->day();
            }
            else
            {
                $StockYear = "";
                $StockMonth = 1;
                $StockDay = 1;
            }
	
     for ( $i = 1; $i <= 31; $i++ )
            {
                $t->set_var( "day_id", $i );
                $t->set_var( "day_value", $i );
                $t->set_var( "selected", "" );
//                if ( ( $StockDay == "" and $i == 1 ) or $StockDay == $i )
                if ( $StockDay == $i )
 	                 $t->set_var( "selected", "selected" );
                if ( $StockDay == "" and $i == date(j) )
 	                 $t->set_var( "selected", "selected" );
	                $t->parse( "day_item", "day_item_tpl", true );
            }

            $month_array = array( 1 => "select_january",
                                  2 => "select_february",
                                  3 => "select_march",
                                  4 => "select_april",
                                  5 => "select_may",
                                  6 => "select_june",
                                  7 => "select_july",
                                  8 => "select_august",
                                  9 => "select_september",
                                  10 => "select_october",
                                  11 => "select_november",
                                  12 => "select_december" );

            foreach ( $month_array as $month )
            {
                $t->set_var( $month, "" );
            }

            $var_name =& $month_array[$StockMonth];
            if ( $var_name == "" ) {
//				$dateMonth = date(n);
                $var_name =& $month_array[date(n)];
				}
				
            $t->set_var( $var_name, "selected" );
			
			if ( $StockYear )
	            $t->set_var( "stockyear", $StockYear );
			else
				$t->set_var( "stockyear", date("Y") );
			

    $prices = eZPriceGroup::prices( $ProductID );

    $PriceGroup = array();
    $PriceGroupID = array();

    foreach ( $prices as $price )
    {
        $PriceGroup[] = $price["Price"];
        $PriceGroupID[] = $price["PriceID"];
    }

    if ( isset( $UseVoucher ) && $UseVoucher )
    {
        $priceRange =& $product->priceRange();
    }

    $writeGroupsID = eZObjectPermission::getGroups( $ProductID, "trade_product", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $ProductID, "trade_product", 'r', false );

//    $VatType =& $product->vatType();    
    $ShippingGroup =& $product->shippingGroup();
}

if ( isset( $UseVoucher ) && $UseVoucher )
{
    if ( isset( $priceRange ) && $priceRange )
    {
        $t->set_var( "price_max", $priceRange->max() );
        $t->set_var( "price_min", $priceRange->min() );
    }
    else
    {
        $t->set_var( "price_max", "0" );
        $t->set_var( "price_min", "0" );
    }

    $t->set_var( "url_action", "voucher" );
    $t->set_var( "normal_price", "" );
    $t->set_var( "list_price", "" );
    $t->parse( "price_range", "price_range_tpl" );
}
else
{
    $t->set_var( "url_action", "productedit" );
    $t->set_var( "price_range", "" );
    $t->parse( "normal_price", "normal_price_tpl" );
	$t->parse( "list_price", "list_price_tpl" );
}

$category = new eZProductCategory();
$categoryArray = $category->getTree( );

foreach ( $categoryArray as $catItem )
{
    if ( isset( $Action ) && $Action == "Edit" )
    {
        $defCat = $product->categoryDefinition();
        if ( $product->existsInCategory( $catItem[0] ) &&
             ( $defCat->id() != $catItem[0]->id() ) )
        {
            $t->set_var( "multiple_selected", "selected" );
        }
        else
        {
            $t->set_var( "multiple_selected", "" );
        }

        if ( $defCat->id() == $catItem[0]->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
    }
    else
    {
        $t->set_var( "selected", "" );
        $t->set_var( "multiple_selected", "" );
    }

//      if ( isset( $Action ) && $Action == "Edit" )
//      {
//          if ( $product->existsInCategory( $catItem ) )
//              $t->set_var( "selected", "selected" );
//          else
//              $t->set_var( "selected", "" );
//      }
//      else
//      {
//              $t->set_var( "selected", "" );
//      }

    $t->set_var( "option_value", $catItem[0]->id() );
    $t->set_var( "option_name", $catItem[0]->name() );

    if ( $catItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
    else
        $t->set_var( "option_level", "" );

    $t->parse( "value", "value_tpl", true );
    $t->parse( "multiple_value", "multiple_value_tpl", true );
}

// show the VAT values

$vat = new eZVATType();
$vatTypes = $vat->getAll();

foreach ( $vatTypes as $type )
{
    if ( $VatType  and  ( $VatType->id() == $type->id() ) )
    {
        $t->set_var( "vat_selected", "selected" );
    }
    else
    {
        $t->set_var( "vat_selected", "" );
    }

    $t->set_var( "vat_id", $type->id() );
    $t->set_var( "vat_name", $type->name() . " (" . $type->value() . ")%" );

    $t->parse( "vat_select", "vat_select_tpl", true );
}

// show the box values

$box = new eZBoxType();
$boxTypes = $box->getAll();

foreach ( $boxTypes as $type )
{
    if ( $BoxType  and  ( $BoxType->id() == $type->id() ) )
    {
        $t->set_var( "box_selected", "selected" );
    }
    else
    {
        $t->set_var( "box_selected", "" );
    }
        
    $t->set_var( "box_id", $type->id() );
    $t->set_var( "box_name", $type->name()." (".$type->length()."x".$type->width()."x".$type->height()." in)" );

    $t->parse( "box_select", "box_select_tpl", true );
}

// show shipping groups

$group = new eZShippingGroup();

$groups =& $group->getAll();

foreach ( $groups as $group )
{
    if ( isset( $ShippingGroup ) && $ShippingGroup and $ShippingGroup->id() == $group->id() )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->set_var( "shipping_group_id", $group->id() );

    $t->set_var( "shipping_group_name", $group->name() );

    $t->parse( "shipping_select", "shipping_select_tpl", true );
}

// Show quantity
$t->set_var( "quantity_item", "" );
$t->set_var( "quantity_value", isset( $Quantity ) ? $Quantity : false );
if ( isset( $ShowQuantity ) && $ShowQuantity )
{
    $t->parse( "quantity_item", "quantity_item_tpl" );
}

// Show price groups

$t->set_var( "price_group_list", "" );
$t->set_var( "price_groups_item", "" );
$t->set_var( "price_groups_no_item", "" );

if ( $ShowPriceGroups )
{
    $price_groups = eZPriceGroup::getAll();
    $count = max( count( $PriceGroup ), count( $PriceGroupID ) );

    $NewPriceGroup = array();
    for ( $i = 0; $i < $count; $i++ )
    {
        $NewPriceGroup[$PriceGroupID[$i]] = $PriceGroup[$i];
    }

    $prices = array();
    $price_ids = array();
    $price_names = array();
    foreach ( $price_groups as $price_group )
    {
        $price_id = $price_group->id();

	if ( isset( $NewPriceGroup[$price_id] ) )
            $prices[] = $NewPriceGroup[$price_id];

        $price_ids[] = $price_id;
        $price_names[] = $price_group->name();
    }
    $PriceGroup = $prices;
    $PriceGroupID = $price_ids;
    $t->set_var( "price_group_header_item", "" );
    $t->set_var( "price_group_item", "" );

    for ( $i = 0; $i < count( $PriceGroup ); $i++ )
    {
        $t->set_var( "price_group_name", $price_names[$i] );
        $t->parse( "price_group_header_item", "price_group_header_item_tpl", true );
        $t->set_var( "price_group_value", $PriceGroup[$i] );
        $t->set_var( "price_group_id", $PriceGroupID[$i] );
        $t->parse( "price_group_item", "price_group_item_tpl", true );
    }

    if ( count( $price_groups ) > 0 )
    {
        $t->parse( "price_groups_item", "price_groups_item_tpl" );
        $t->parse( "price_group_list", "price_group_list_tpl" );
    }
//    else
//        $t->parse( "price_groups_no_item", "price_groups_no_item_tpl" );
}

    if ( isset( $ShippingGroup ) && $ShippingGroup and ( $ShippingGroup->id() == $group->id() ) )
    {
        $t->set_var( "selected", "selected" );
    }
    else
    {
        $t->set_var( "selected", "" );
    }

    $t->set_var( "shipping_group_id", $group->id() );

    $t->set_var( "shipping_group_name", $group->name() );

$t->set_var( "module_linker_button", "" );
if ( $ShowModuleLinker )
    $t->parse( "module_linker_button", "module_linker_button_tpl" );

// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );
foreach ( $groupList as $groupItem )
{
    // for the group owner selector
    $t->set_var( "read_id", $groupItem->id() );
    $t->set_var( "read_name", $groupItem->name() );
    
    if ( in_array( $groupItem->id(), $readGroupsID ) )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

	if ( in_array( "-1", $readGroupsID ) )
	    $t->set_var( "all_selected", "selected" );
	else
		$t->set_var( "all_selected", "" );
		
    $t->parse( "read_group_item", "read_group_item_tpl", true );
    
    // for the read access groups selector
        $t->set_var( "write_name", $groupItem->name() );
        $t->set_var( "write_id", $groupItem->id() );
        if ( in_array( $groupItem->id(), $writeGroupsID ) )
            $t->set_var( "is_selected", "selected" );
        else
            $t->set_var( "is_selected", "" );

	if ( in_array( "-1", $writeGroupsID ) )
	    $t->set_var( "all_write_selected", "selected" );
    else
		$t->set_var( "all_write_selected", "" );
		
    $t->parse( "write_group_item", "write_group_item_tpl", true );
}

$t->pparse( "output", "product_edit_tpl" );

?>