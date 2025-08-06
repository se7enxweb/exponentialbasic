<?php
// 
// $Id: export_yahoo.php,v 1.3 2005/03/08 16:18:42 ghb Exp $
//
// Created on: <22-Jun-2001 13:16:55 br>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2005 eZ Systems.  All rights reserved.
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


//////////////////////////////////////////////////////
// Includes : include the classes.
ob_end_clean();

// include_once( "classes/template.inc" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "classes/INIFile.php" );

//
//////////////////////////////////////////////////////

//////////////////////////////////////////////////////
// increse php execution time to allow for long running scripts (re: ftp connections)
ini_set("max_execution_time", 300);
ini_set('upload_max_filesize', 5242880); // 5MB

//////////////////////////////////////////////////////
// Settings

$ini =& INIFile::globalINI();

$SiteURL = $ini->read_var( "site", "UserSiteURL" );
$wwwDir = $ini->read_var( "site", "UserSiteURL" );
$PricesIncludeVAT = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$MainImageWidth = $ini->read_var( "eZTradeMain", "MainImageWidth" );
$MainImageHeight = $ini->read_var( "eZTradeMain", "MainImageHeight" );
$SitePath = $ini->read_var( "site", "SitePath" );


//////////////////////////////////////////////////////
// FTP: Server, User, Password

// use file location + name
//$YahooServer = $ini->read_var( "site", "ServerYahoo" );
//$YahooUser = $ini->read_var( "site", "UserYahoo" );
//$froogle_pass = $ini->read_var( "site", "PasswordYahoo" );

$YahooServer="hedwig.google.com";
$YahooUser="fullthrottle";
$YahooKey="key";

$YahooServer="fullthrottle.com";
$YahooServerPort="21";
$YahooUser="full";
$YahooKey="key";

if( $Action == "export" ){
  $exportCSVFile = true;
} elseif( $Action == "export-cron" ) {
  // export : csv file to user agent
  $exportCSVFile = false;
} else {
  $exportCSVFile = false;
}

// if( $Action == "export-cron" ) {
//  export : csv file to user agent
//  $exportCSVFile = false;
// }


//////////////////////////////////////////////////////
// Build Product Export Data

// if ($exportCSVFile){

$product = new eZProduct();
$productList = $product->activeProducts( "time", 0, "" );


  // Build Feed Header (Yahoo)
  // $feed_froogle_header = "product_url\t name\t description\t price\t image_url\t category\n"; 

  $feed = "code\t product-url\t name\t price\t merchant-category\t description\t image-url\n";

  // $feed_yahoo_header_extended = "code\t product-url\t name\t price\t merchant-category\t description\t image-url\t isbn\t medium\t condition\t classification\t availability\t ean\t weight\t upc\t manufacturer\t manufacturer-part-no\t model-no\n";

/*  Example of original rules (problems-inside)

	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
			 "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
			 "'([\r\n])[\s]+'",                // Strip out white space
			 "'[\r\n\s]+'",
			 "'&(quot|#34);'i",                // Replace HTML entities
			 "'&(amp|#38);'i",
			 "'&(lt|#60);'i",
			 "'&(gt|#62);'i",
			 "'&(nbsp|#160);'i",
			 "'&(iexcl|#161);'i",
			 "'&(cent|#162);'i",
			 "'&(pound|#163);'i",
			 "'&(copy|#169);'i",
			 "'&#(\d+);'e");                    // evaluate as php
	
	$replace = array ("",
			  "",
			  "\\1",
			  "\"",
			  "&",
			  "<",
			  ">",
			  " ",
			  chr(161),
			  chr(162),
			  chr(163),
			  chr(169),
			  "chr(\\1)");
*/

 // these string patters might cause space to " problems

 //		 "'([\r\n])[\s]+'",                // Strip out white space
 //		 "'[\r\n\s]+'",

 //		  "\\1",
 //		  "\"",

// replace the previous string patterns with these values (ordered list)
$replace = array ("",
		  "",
		  "&",
		  "<",
		  ">",
		  " ",
		  chr(161),
		  chr(162),
		  chr(163),
		  chr(169),
		  "chr(\\1)");

// patterns
$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
		 "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
		 "'([\r\n])[\s]+'",                // Strip out white space
		 "'\n'i",
                 "'\r\n'i",
                 "'\r'i",
		 "'&(quot|#34);'i",                // Replace HTML entities
		 "'&(amp|#38);'i",
		 "'&(lt|#60);'i",
		 "'&(gt|#62);'i",
		 "'&(nbsp|#160);'i",
		 "'&(iexcl|#161);'i",
		 "'&(cent|#162);'i",
		 "'&(pound|#163);'i",
		 "'&(copy|#169);'i",
		 "'&#(\d+);'e");                    // evaluate as php

$replace = array ("",
		  "",
		  "\\1",
                  "",
                  "",
                  "",
		  "&",
		  "<",
		  ">",
		  " ",
		  chr(161),
		  chr(162),
		  chr(163),
		  chr(169),
		  "chr(\\1)");

// prepare each product information
foreach ($productList as $product) 
{
  // product exclusion (by product id, _not_ category)
  if ( $product->id() != 1126 ) {

  //  $line['product_idl'] = $product->productNumber();  
  $line['product_id'] = $product->id();  

  $line['product_url'] = "http://" . $wwwDir . "/trade/productview/" . $product->id() . "/";  

  $line['name'] = html_entity_decode( $product->name() ) ." - ". $product->productNumber(); 
  // print($line['name']);

  // this line causes "" in product name, so we are removing it
  $line['name'] = preg_replace($search, $replace, $line['name']);
  // $line['name'] = $line['name'];

  // not sure why this is commented out? no tax in price?
  // $line['price'] = str_replace( "$&nbsp;", "", $product->localePrice( $PricesIncludeVAT ) );
  $line['price'] = $product->price();

 $category = $product->categoryDefinition();
  $pathArray =& $category->path();
  $line['category'] = "";
  $i = 1;

  foreach ( $pathArray as $path )
  {
    $line['category'] .= $path[1];
    if ( $i != count($pathArray) )
    $line['category'] .= " > ";
    $i++;
  }

  $line['description'] = html_entity_decode($product->brief());

  $line['description'] = preg_replace($search, $replace, $line['description']);
  // $line['description'] = $line['description'];

  $mainImage = $product->mainImage();
  if ( $mainImage )
  {
    $variation =& $mainImage->requestImageVariation( $MainImageWidth, $MainImageHeight );
    $line['image_url'] = "http://" . $SiteURL . "/" . $variation->imagePath();
  }
  else
    $line['image_url'] = "";

  // Yahoo Extended Product Fields
  // isbn\t medium\t condition\t classification\t availability\t ean\t weight\t upc\t manufacturer\t manufacturer-part-no\t model-no\n";

  // Product IBSN (Books)
  $line['ibsn'] = "";

  // Product Medium
  $line['medium'] = "";

  // Product Condition
  $line['condition'] = "";

  // Product Classification
  $line['classification'] = "";

  // Product Availablity
  $line['availablity'] = "";

  // Product " ean "
  $line['ean'] = "";

  // Product Weight
  $line['weight'] = "";

  // Product UPC
  $line['upc'] = "";

  // Product Brand
  $line['brand'] = "";

  // Product Manufacturer
  $line['manufacturer'] = "";

  // Product Manufacturer Part No
  $line['manufacturer-part-no'] = "";

  // Product Model No
  $line['model-no'] = "";

  // Product Size
  $line['size'] = "";

  // Product Color
  $line['color'] = "";

  // Product Sale Price
  $line['sale_price'] = "";

  // Product msrp (The manufacturer's suggested retail price)
  $line['msrp'] = "";

  // Product 'In-Stock'
  $line['in-stock'] = "";

  // Product Promotion Text
  $line['promo-text'] = "";

  // Product Shipping Price
  $line['shipping-price'] = "";

  // Product Shipping Weight
  $line['shipping-weight'] = "";

  // Product shipping-surcharge
  $line['shipping-surcharge'] = "";

  // Product shipping-class
  $line['shipping-class'] = "";

  // Product Size
  $line['size'] = "";

  // Product Size
  $line['size'] = "";

  // Product Size
  $line['size'] = "";



  // Build Feed (Minimal)
  // $feed_header = "code\t product-url\t name\t price\t merchant-category\t description\t image-url\n";

  $feed .=  
    $line['product_id'] . "\t" .
    $line['product_url'] . "\t" .
    $line['name'] . "\t" .
    $line['price'] . "\t" .
    $line['category'] . "\t" .
    $line['description'] . "\t" . 
    $line['image_url'] . "\n" ;

      //    $line['medium'] . "\n";
} 

}

$file = new eZFile();
$file->dumpYahooDataToFile( $feed, "data.txt" );

$fileName = $file->tmpName();
$fileSize = filesize( $fileName );

chmod( $fileName, 0644 );

// unlink ( "/home/full/public_html/export_froogle/");
// unlink ( "/home/full/public_html/export_yahoo/");

$fp = fopen( $fileName, "r" );
$content =& fread( $fp, $fileSize );




/*
/////////////////////////////////////////////
}
else {
   // fake file
   // $file = new eZFile();
   // $file->dumpDataToFile( $feed, "fullthrottle.txt" );

   // $fileName = $file->tmpName();
   // $fileSize = filesize( $fileName );
   
   //$fileName = $SitePath . '/export_froogle/attQ98AJz';
   $fileName = $SitePath . '/export_yahoo/attQ98AJz';

   $fileSize = filesize( $fileName );
   //chmod( $fileName, 0777 );

   //   $fp = fopen( $fileName, "r" );
   //   $content =& fread( $fp, $fileSize );
}
/////////////////////////////////////////////
*/




//////////////////////////////////////////////////////
// export to a file instead.... else, export to a file and ftp instead....
if( $exportCSVFile ){
   header( "Cache-Control:" );
   header( "Content-Length: $fileSize" );
   header( "Content-disposition: attachment; filename=data.txt" );
   header( "Content-Transfer-Encoding: binary" );

   echo($content);
   unlink( $file->tmpName() );
} else {
   $source_file_name = $fileName;


  // $destination_file = "/trash/fullthrottle.txt"; 

   /*
   $source_fp = fopen( $source_file_name , 'r');
   $source_c =& fread( $source_fp, $fileSize );
   */

   // die($source_c);


   // begin debug / log output
   echo "\n ############################################################### \n";
   
   include_once("classes/ezdatetime.php");

   $date = new eZDateTime();
   $month = $date->month();
   $day = $date->day();
   $year = $date->year();
   $hour = $date->addZero($date->hour());
   $minute = $date->addZero($date->minute());

   $date = "$month".'/'."$day".'/'."$year".' | '."$hour".' : '."$minute";
   echo "Date : $date \n";
   echo "CSV : eZ publish (trade) Product : Export File : ($fileSize kb) : $fileName \n";


   //////////////////////////////////////////////
   //   if ( isset( $dfile ) )
   //   {
       // save the buffer contents
   
       // echo "Run: $SitePath/bin/cron/yahoo_upload.sh $SitePath/export_yahoo/attQ98AJz   \n";
       echo "Run: $SitePath/bin/cron/yahoo_upload.sh $fileName \n";

       /*
       $buffer =& ob_get_contents();
       ob_end_clean();

       // fetch the system printout
       ob_start();
       */

       // $SitePath 
       // system( "$SitePath/bin/cron/yahoo_upload.sh $SitePath/export_yahoo/attQ98AJz" );

       system( "$SitePath/bin/cron/yahoo_upload.sh $fileName" );


/*
       $ret = ob_get_contents();
       ob_end_clean();

       // fill the buffer with the old values
       ob_start();
       print( $buffer );
       //   }

*/


// echo " \n";
// print("end of file\n\n");
////////////////////////////

// unlink export file
unlink( $file->tmpName() );

}
exit();
?>