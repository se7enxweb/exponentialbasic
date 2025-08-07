<?php
// 
// $Id: seti.php,v 0.1 2004/01/10 
//
// Created on: <10-Jan-2004 Bob Sims>
//
// This source file integrates Stone Edge Order Manager
// with eZ publish v2.2 publishing software.
//
// Copyright (C) 2004 Bob Sims.  All rights reserved.
include_once( "eztrade/classes/ezorder.php" );
echo"test";
include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "eztrade/classes/ezcheckout.php" );
//include_once( "eztrade/classes/ezorder.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "classes/ezcurrency.php" );

$ini =& eZINI::instance( 'site.ini' );
$wwwDir = $ini->WWWDir;
$indexFile = $ini->Index;

$UserName = $ini->variable( "eZTradeMain", "SetiUser" );
$Password = $ini->variable( "eZTradeMain", "SetiPassword" );
$Code = $ini->variable( "eZTradeMain", "Code" );
$Version = $ini->variable( "eZTradeMain", "Version" );
$AdminSite = $ini->variable( "site", "AdminSiteURL" );

//print_r($_SERVER); exit();
/*
// check for secure connection
if( ($_SERVER['SERVER_PORT'] != 443) || ($_SERVER['HTTPS'] != 'on') ) 
{ 	//header( "Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	echo "SETIError: Connetion not secure!";
	exit();
} 
*/
//send version number
/*
if ($setifunction=="sendversion")
{
	echo "SetiResponse: version=".$Version;
	exit();
}

//check for proper username/password
if ($setiuser!=$UserName)
{
	echo "SETIError: Incorrect username-> ".$setiuser;
	exit();
}

if ($password!=$Password)
{
	echo "SETIError: Incorrect password-> ".$password;
	exit();
}

//check for correct cart code
if ($code!=$Code)
{
	echo "SETIError: Incorrect code-> ".$code;
	exit();
}

//check for $setifunction variable
if ( !isset($setifunction) )
{
	echo "SETIError: No setifunction variable provided";
	exit();
}


switch ( $setifunction )
{
	case "ordercount":
	{
	// return number of order
	$order=new eZOrder();
	$allOrders=$order->getAll( 0, $order->getTotalCount, "Date" );
	if ($lastorder)
		$lastorder=0;
	foreach ( $allOrders as $eachOrder ) {
		if ( $eachOrder->id() > $lastorder )
			$newOrders[]=$eachOrder;
		}
	echo "SetiResponse: ordercount=".count( $newOrders );
//	echo "<br>".strtotime($lastdate);
	break;
	}

	case "downloadorders":
	{
	// return orders
	$order=new eZOrder();
	$allOrders=$order->getAll( 0, $order->getTotalCount, "Date" );
	if (!$lastorder)
		$lastorder=0;
	if (!$startnum)
		$starnum=0;
	if (!$batchsize)
		$batchsize=count($allOrders);
	$locale = new eZLocale( $Language );
	//print header record
	echo "SETI_OH_START"."\t"."SETIOrders"."\t"."SETI_OM_IMP_VER=2.0"."\t"."eZTrade"."\t".$Code."\t".
			date("n/j/Y").
			"\t".date("H:i:s").
			"Approved\t".
			"\t"."=".
			"\t"."SETI_OH_END".
			"\n";
	$i=1;
	foreach ( $allOrders as $eachOrder ) {
		if ( ($eachOrder->id() > $lastorder) && ($i >= $startnum) && ($i <= $batchsize) )
		{
			// print order start field
			echo "SETI_ORD_START"."\t".
				$eachOrder->id()."\t";
			// print date/time
			$status = $eachOrder->initialStatus();
			$dateTime = $status->altered();
			$orderDate=$locale->format( $dateTime );
			$orderDate=str_replace(".", "/", $orderDate);
			$orderDate=str_replace(" ", "\t", $orderDate);
			echo $orderDate."\t";

			//print bill to names
			$user = $eachOrder->user();

		    if ( $eachOrder->personID() == 0 && $eachOrder->companyID() == 0 ) {
    		   		$billtoFullName = $user->firstName()." ".$user->lastName();
					$emailAddress = $user->email();
					}
       		else
    			{
        		if ( $order->personID() > 0 )
        			{
            		$customer = new eZPerson( $eachOrder->personID() );
            		$billtoFullName=$customer->firstName()." ".$customer->lastName();
        			}
        		else
        			{
            		$customer = new eZCompany( $eachOrder->companyID() );
            		$billtoFullName=$customer->name();
        			}
				$emailList = $customer->emailAddress();
		        $emailAddress = $emailList[0];
				}
						
			//print bill to first name (optional)
			if ($user->firstName())
				echo $user->firstName()."\t";
			else echo "\t";
			//print bill to last name (optional)
			if ($user->lastName())
				echo $user->lastName()."\t";
			else echo "\t";
			//print bill to full name
			echo $billtoFullName."\t";
			// print bill to company (?)
			if ($eachOrder->companyID() > 0)
				echo $customer->name()."\t";
			else echo "\t";
			// print email address
			echo $emailAddress."\t";
			// print bill to address1
			$billingAddress =& $eachOrder->billingAddress();
			echo $billingAddress->street1()."\t";
			// print bill to address2
			if ( $billingAddress->street2() )
				echo $billingAddress->street2()."\t";
			else echo "\t";
			// print bill to city
			echo $billingAddress->place()."\t";
			// print bill to state
			$region = $billingAddress->region();
			if ( is_object($region))
				echo $region->Abbreviation()."\t";
			else
				echo "\t";
			// print bill to ZIP
			echo $billingAddress->zip()."\t";
			// print bill to country
			$country = $billingAddress->country();
			if ( is_object($country))
				echo $country->ISO()."\t";
			else
				echo "\t";
			//	echo "no country\t";
			
			//print bill to phone
			// echo $eachOrder->phone;
			echo "\t";
			//print ship to full name
			echo $billtoFullName."\t";			
			//print ship to company
			if ($eachOrder->companyID() > 0)
				echo $customer->name()."\t";
			else echo "\t";		
			//print ship to address1
		    $shippingAddress =& $eachOrder->shippingAddress();			
			echo $shippingAddress->street1()."\t";
			// print ship to address2
			if ($shippingAddress->street2())
				echo $shippingAddress->street2()."\t";
			else echo "\t";
			// print ship to city
			echo $shippingAddress->place()."\t";
			// print ship to state
			$region = $shippingAddress->region();
			if ( is_object($region))
				echo $region->Abbreviation()."\t";
			else
				echo "\t";
			// print ship to zip
			echo $shippingAddress->zip()."\t";
			// print ship to country
			$country = $shippingAddress->country();
			if ( is_object($country))
				echo $country->ISO()."\t";
			else 
				echo "\t";
				//	echo "no country\t";

			// print ship to phone
			// echo $eachOrder->phone;
			echo "\t";
			// print special instructions
			echo "\t";
			// print comments
			if ($eachOrder->comment())
				echo preg_replace('/\r\n?|\n/', ' ', $eachOrder->comment())."\t";
			else echo "\t";
			// print payment type
			$checkout = new eZCheckout();
			$instance =& $checkout->instance();
			echo $instance->paymentName( $eachOrder->paymentMethod() )."\t";
			// print credit card acct number
			echo "\t";
			// print credit card expiration date
			echo "\t";
			// print credit card bank name (optional)
			echo "\t";
			// print credit card auth code
			echo "\t";
			// print credit card AVS code
			echo "\t";
			// print credit card transaction ID
			echo "\t";
			// print CVV2 code
			echo "\t";
			// print product total
			$currency = new eZCurrency();
		    $eachOrder->orderTotals( $tax, $total );
			$currency->setValue( $total["subextax"] );
			echo str_replace( "�&nbsp;", "", $locale->format( $currency ) )."\t";
			// print small order fee
			echo "\t";
			// print shipping charges
		    $currency->setValue( $total["shipextax"] );
			echo str_replace( "�&nbsp;", "", $locale->format( $currency ) )."\t";
			// print shipping insurance
			echo "\t";
			// print sales tax
		    $currency->setValue( $total["inctax"]-$total["shipextax"]-$total["subextax"] );
			echo str_replace( "�&nbsp;", "", $locale->format( $currency ) )."\t";
			// 	print discount message
			echo "\t";
			// print discount percentage
			echo "\t";
			// print COD charge
			echo "\t";
			// print coupon code
			echo "\t";
			// print coupon discount amount
			echo "\t";
			// print surcharge reason
			echo "\t";
			// print surchage amount
			echo "\t";
			// print grand total
		    $currency->setValue( $total["inctax"] );
			echo str_replace( "�&nbsp;", "", $locale->format( $currency ) )."\t";
			// print shipping method
			$shippingType = $eachOrder->shippingType();
			echo $shippingType->name()."\t";
			// print total weight
			echo "\t";
			// print affilliate name
			echo "\t";
			// print gift message
			echo "\t";
			// print name or ID of checkout form (Americart only?)
			echo "\t";
			// print number of line items
			$items = $eachOrder->items( $OrderType );
			echo count($items)."\t";
			//print payment string
			echo "\t";
			//print current order status
		    $status = $eachOrder->lastStatus();
		    $statusType = $status->type();
			echo preg_replace( "#intl-#", "", $statusType->name() )."\t";						
			// print optional field 57 (for future expansion)
			echo "\t";
			// print optional field 58 (for future expansion)
			echo "\t";						
			// print optional field 59 (for future expansion)
			echo "\t";
			// print optional field 60 (for future expansion)
			echo "\t";
			// print optional field 61 (for future expansion)
			echo "\t";
			// print optional field 62 (for future expansion)
			echo "\t";
			// print optional field 63 (for future expansion)
			echo "\t";
			// print optional field 64 (for future expansion)
			echo "\t";
			// print optional field 65 (for future expansion)
			echo "\t";
			// print optional field 66 (for future expansion)
			echo "\t";

			// print line items
			foreach ($items as $item)
				{
				// print out each line item
				$product =& $item->product();
				// print line item header
				echo "BEGIN_ITEM"."\t";
				// print item SKU
				echo $product->productNumber()."\t";
				//print item name
				echo html_entity_decode($product->name())."\t";
				// print unit price
				echo str_replace( "$&nbsp;", "", $item->localePrice( true, true, false ) )."\t";
				// print quantity ordered
				echo $item->count()."\t";
				// print options
				$numberOfOptions = 0;
    			$optionValues =& $item->optionValues();
				foreach ( $optionValues as $optionValue )
					echo $optionValue->optionName()."=".$optionValue->valueName()."\t";
				// print end of line item
				echo "END_ITEM\t";
				}						
		echo "SETI_ORD_END\n";
		$i++;			
		}
	}

	break;
	}

	case "downloadcustomers":
	{
	// return customers	
	$customers =& eZOrder::customers();
	echo "SETICustomers\n";
	foreach ($customers as $customer)
	{
		echo $customer->id()."\t";
		echo $customer->login()."\t";
		echo $customer->firstName()."\t";
		echo $customer->lastName()."\t";
		echo $customer->email()."\t";
		//company name
		echo "\t";
		//phone number
		echo "\t";
		$mainAddress = eZAddress::mainAddress( $customer );
		echo $mainAddress->street1()."\t";
		echo $mainAddress->street2()."\t";
		echo $mainAddress->place()."\t";
		
		$region = $mainAddress->region();
		if ($region)
			echo $region->Abbreviation()."\t";
		else
			echo "\t";
		
		echo $mainAddress->zip()."\t";
		
		$country = $mainAddress->country();
		if ($country)
			echo $country->ISO()."\n";
		else
			echo "\n";
	}
	echo "SETIEndOfData\n";
	break;
	}

	case "downloadprods":
	{
	// return product data	
	$product = new eZProduct();
	$productList = $product->activeProducts( "time", 0, "" );
	echo "SETIProducts"."\n";
	foreach ($productList as $product)
		{
		echo $product->id()."\t";
		echo $product->productNumber()."\t";
		$prodName=html_entity_decode( $product->name() );
		echo $prodName."\t";
		echo $product->price()."\t";
		// Cost (not currently supported)
		echo "\t";
		$prodDesc=html_entity_decode( $product->brief() );
		echo $prodDesc."\t";
		echo $product->weight()."\t";
		// taxable (0=false, 1=true)
		echo "1\t";
		if ($product->discontinued() == true )
			echo "1\t";
		else
			echo "0\t";
		if ($product->totalQuantity())
			echo $product->totalQuantity()."\n";
		else
			echo "0\n";
		}
	echo "SETIEndOfData\n";	
	break;
	}

	case "downloadqoh":
	{
	// return current product quantities
	$product = new eZProduct();
	$productList = $product->activeProducts( "time", 0, "" );
	echo "SETIProducts"."\n";
	foreach ($productList as $product)
		{
		echo $product->id()."\t";
		echo $product->productNumber()."\t";
		if ($product->totalQuantity())
			echo $product->totalQuantity()."\n";
		else
			echo "0\n";		
		}
	echo "SETIEndOfData\n";		
	break;
	}

	case "qohreplace":
	{
	// upload bulk inventory data
	$skuArray = explode("|", $update);
	echo "SETIResponse\n";
	foreach ($skuArray as $item)
		{
		$product=new eZProduct();
		$itemArray=explode("~", $item);
		$item=$product->findProductNumber($itemArray[0]);
		if ($item)
			{
			$item->setTotalQuantity($itemArray[1]);
			$item->store();
			echo $itemArray[0]."=OK\n";
			}
		else echo $itemArray[0]."=NF\n";
		}
	echo "SETIEndOfData\n";
	break;
	}

	case "invupdate":
	{
	// upload individual inventory data	
	if ($update)
		$skuArray = explode("~", $update);
	else echo "SETIRESPONSE=False;SKU=".$skuArray[0].";QOH=NA;NOTE=SKU data missing or incorrectly formatted.";
	$product=new eZProduct();
	$item=$product->findProductNumber($skuArray[0]);
	if ($item)
	{
		$item->setTotalQuantity($skuArray[1]);
		$item->store();
		echo "SETIRESPONSE=OK;"."SKU=".$skuArray[0].";QOH=".$skuArray[1].";Note=".$item->name();
	}
	else echo "SETIRESPONSE=False;SKU=".$skuArray[0].";QOH=NA;NOTE=SKU missing or formatted differently.";
	break;
	}
}

exit();
*/
?>