<?
// 
// $Id: invoice.php,v 1.2 2001/02/20 16:12:48 bf Exp $
//
// B�rd Farstad <bf@ez.no>
// Created on: <02-Feb-2001 18:28:36 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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


include_once( "classes/ezhttptool.php" );
include_once( "classes/INIFile.php" );
include_once( "ezaddress/classes/ezaddress.php" );

// retrieve site.ini config variables
$indexFile = $ini->Index;
$wwwDir = $ini->WWWDir;
$PaypalEmail = $ini->variable( "Checkout", "PaypalEmail" );
$ShopName = $ini->variable( "site", "SiteTitle" );
$CurrencyCode = $ini->variable( "Checkout", "CurrencyCode" );
$SiteLogo = $ini->variable( "Checkout", "SiteLogo" );
$PageStyle = $ini->variable( "Checkout", "PageStyle" );
$LanguageCode = $ini->variable( "Checkout", "LanguageCode" );
$PaypalMode = $ini->variable( "Checkout", "PaypalMode" );

// get billing address
$billingAddress = new eZAddress( $session->variable( "BillingAddressID" ) );
$region = $billingAddress->region();
$country = $billingAddress->country();
			
// build POST URL

if ( $PaypalMode == 'Sandbox' )
	$URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
elseif ( $PaypalMode == 'Paypal' )
	$URL = 'https://www.paypal.com/cgi-bin/webscr?';
else
	{
		print('Error: PaypalMode variable in site.ini not set!');
		exit();
	}
	
$URL .= 'cmd=_ext-enter';
$URL .= '&redirect_cmd=_xclick';
$URL .= '&business='.$PaypalEmail;
$URL .= '&item_name='.urlencode($ShopName);

if ( $SiteLogo != "" )
	$URL .= $SiteLogo;
	
$URL .= '&currency_code='.$CurrencyCode;
$URL .= '&amount='.$ChargeTotal;
$URL .= '&email='.$user->Email();    	
$URL .= '&first_name='.$user->firstName();
$URL .= '&last_name='.$user->lastName();
$URL .= '&address1='.urlencode($billingAddress->street1());
$URL .= '&address2='.urlencode($billingAddress->street2());
$URL .= '&city='.urlencode($billingAddress->place());

if ( $region )
	$URL .= '&state='.$region->Abbreviation();

$URL .= '&state=TX';
$URL .= '&zip='.$billingAddress->zip();
$URL .= '&country='.$country->iso();
$URL .= '&lc='.$LanguageCode;
$URL .= '&return=http://'.$HTTP_HOST.$wwwDir.$indexFile.'/trade/ordersendt/'.$session->variable( "OrderID" );
$URL .= '&cancel_return=http://'.$HTTP_HOST.$wwwDir.$indexFile.'/trade/checkout/';
if ($PageStyle != "")
	$URL .= '&page_style='.$PageStyle;
$URL .= '&address_override=1';
$URL .= '&invoice='.$session->variable( "OrderID" );
$URL .= '&notify_url=http://'.$HTTP_HOST.$wwwDir.$indexFile.'/trade/paypal/'.$session->variable( "OrderID" ).'/'.$session->id().'/';
$URL .= '&no_note=1';
$URL .= '&no_shipping=1';

//include_once( "classes/ezlog.php" );
//$writeURL = print_r($URL, true);
//eZLog::writeNotice( $writeURL."\n" );	

eZHTTPTool::header("Location: $URL");

?>
