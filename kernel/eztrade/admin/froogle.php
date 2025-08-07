<?php
// 
// $Id: froogle.php,v 1.3 2005/03/08 16:18:42 ghb Exp $
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

$ini =& eZINI::instance( 'site.ini' );

$SiteURL = $ini->variable( "site", "UserSiteURL" );
$wwwDir = $ini->variable( "site", "UserSiteURL" );
$PricesIncludeVAT = $ini->variable( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
$MainImageWidth = $ini->variable( "eZTradeMain", "MainImageWidth" );
$MainImageHeight = $ini->variable( "eZTradeMain", "MainImageHeight" );
$SitePath = $ini->variable( "site", "SitePath" );


//////////////////////////////////////////////////////
// FTP: Server, User, Password

// use file location + name
//$FroogleServer = $ini->variable( "site", "ServerFroogle" );
//$FroogleUser = $ini->variable( "site", "UserFroogle" );
//$froogle_pass = $ini->variable( "site", "PasswordFroogle" );

$FroogleServer="hedwig.google.com";
$FroogleUser="fullthrottle";
$FroogleKey="key";

$FroogleServer="fullthrottle.com";
$FroogleServerPort="21";
$FroogleUser="full";
$FroogleKey="key";

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

$feed = "product_url\t name\t description\t price\t image_url\t category\n"; 

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
  $line['product_url'] = "http://" . $wwwDir . "/trade/productview/" . $product->id() . "/";  
  $line['name'] = html_entity_decode( $product->name() ) ." - ". $product->productNumber(); 
  // print($line['name']);
  $line['description'] = html_entity_decode($product->brief());

  // this line causes "" in product name, so we are removing it
  $line['name'] = preg_replace($search, $replace, $line['name']);
  // $line['name'] = $line['name'];

  $line['description'] = preg_replace($search, $replace, $line['description']);
  // $line['description'] = $line['description'];

  // not sure why this is commented out? no tax in price?
  // $line['price'] = str_replace( "$&nbsp;", "", $product->localePrice( $PricesIncludeVAT ) );
  $line['price'] = $product->price();

  $mainImage = $product->mainImage();
  if ( $mainImage )
  {
    $variation =& $mainImage->requestImageVariation( $MainImageWidth, $MainImageHeight );
    $line['image_url'] = "http://" . $SiteURL . "/" . $variation->imagePath();
  }
  else
    $line['image_url'] = "";

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

  $feed .=  
    $line['product_url'] . "\t" .
    $line['name'] . "\t" .
    $line['description'] . "\t" . 
    $line['price'] . "\t" . 
    $line['image_url'] . "\t" . 
    $line['category'] . "\n";
} 

$file = new eZFile();
$file->dumpFroogleDataToFile( $feed, "fullthrottle.txt" );

$fileName = $file->tmpName();
$fileSize = filesize( $fileName );

chmod( $fileName, 0644 );

// unlink ( "/home/web/public_html/export_froogle/");

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
   
   $fileName = $SitePath . '/export_froogle/attQ98AJz';

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
   header( "Content-disposition: attachment; filename=fullthrottle.txt" );
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
   
       // echo "Run: $SitePath/bin/cron/froogle_upload.sh $SitePath/export_froogle/attQ98AJz   \n";
       echo "Run: $SitePath/bin/cron/froogle_upload.sh $fileName \n";

       /*
       $buffer =& ob_get_contents();
       ob_end_clean();

       // fetch the system printout
       ob_start();
       */

       // $SitePath 
       // system( "$SitePath/bin/cron/froogle_upload.sh $SitePath/export_froogle/attQ98AJz" );

       system( "$SitePath/bin/cron/froogle_upload.sh $fileName" );


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