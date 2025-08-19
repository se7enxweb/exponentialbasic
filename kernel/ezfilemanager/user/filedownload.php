<?php
//
// $Id: filedownload.php 9808 2003-04-11 13:48:48Z br $
//
// Created on: <10-Dec-2000 16:39:10 bf>
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

// include_once( "ezfilemanager/classes/ezvirtualfile.php" );
// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezobjectpermission.php" );

$ini =& eZINI::instance( 'site.ini' );

$user =& eZUser::currentUser();

$file = new eZVirtualFile( $FileID );

if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) == false )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$fileName = $file->name();

if ( $ini->variable( "eZFileManagerMain", "DownloadOriginalFilename" ) == "true" && $file->originalFileName() != "" )
    $originalFileName = $file->originalFileName();
else
    $originalFileName = $file->name();

$filePath = $file->filePath( true );
// echo "<hr>"; svar_dump( $filePath ); echo "<hr>";
$userID = $user->ID;

$file_debug = true;
$download_style_inline = true;

//###################################################
// nsb non-requred nsb authentication

/*
if( $userID == '')
     die("sucka!!");
*/

/*
if ( $userID < 2 ) {
if ( $userID != '' ) {
// print ("USER ID : '". $userID ."'");

 if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) == false )
{
  print ("USER With Permission : '". $userID ."'<br>");
}

}

  // print ("USER ID : is Null"); 
  //exit();
}
*/

//###################################################
// nsb non-requred nsb authentication

/*
if ( ( $userID != '' ) && ( $userID != 0 ) )
{
*/
//###################################################

//include_once( "ezstats/classes/ezpageview.php" );

if ( !isset( $GlobalPageView ) )
{
    $GlobalPageView = new eZPageView();
    $GlobalPageView->store();
}

$editedFileName = str_replace( " ", "%20", $originalFileName );

// store the statistics
$file->addPageView( $GlobalPageView );

$filePath = preg_replace( "#.*/(.*)#", "\\1", $filePath );

if($file_debug){
  print( $filePath );

  print( "Location: /filemanager/filedownload/$filePath/$editedFileName"  );
  exit();
}


/*
}
*/

//###################################################
// nsb required user authentication download
//###################################################

/* 
// $host = $SERVER_NAME;
// $location = "Location: http://" . $SERVER_NAME . ":" . $SERVER_PORT . "/" . $wwwDir . $index . "filemanager/filedownload/$filePath/$editedFileName";

//if ( eZObjectPermission::hasPermission( $file->id(), "filemanager_file", "r", $user ) == false )

if ( ( $userID == '' ) && ($userID == 0 ) )
{
 // include( "ezuser/user/login.php" );
 $fileID = $file->id();

 // $RedirectURL = "/filemanager/download/$fileID";

 // Create a new session, store it to the database and set a cookie.
 //  $session =& eZSession::globalSession( );
 //  $session->store();

  // get the session from the client
  // The page must reload before the session cookie is accessable.
  // $session->fetch();

  // set a session variable
  // $session->setVariable( "RedirectURL", "$RedirectURL" );

  // fetch the CartID session variable
  // $cartID = $session->variable( "CartID" );


  //  eZHTTPTool::header( "Location: $RedirectURL" );
  //  die($RedirectURL);

  //  eZHTTPTool::header( "Location: /error/nouser/" );
  //  exit();
}


// print( $location );
// Header( $location );
*/

//###################################################

// Rewrote to be compatible with virtualhost-less install
$size = eZPBFile::filesize( "var/site/storage/ezfilemanager/$filePath" );

$nameParts = explode( ".", $editedFileName );
$suffix = $nameParts[count( $nameParts ) - 1];

$suffix = strtolower( $suffix );

 if($file_debug) {
  print("your file is: " . $suffix ."<br />");
  print("your file is: " . $editedFileName ."<br />");
  die("your file is: " . $originalFileName );
 }

//----------------------------------------- 
// clear what might be in the output buffer and stop the buffer.
ob_end_clean();
switch ( $suffix )
{
   case "gif" :
   case "GIF" :
        header( "Content-Type: image/gif" );
        break;
   case "jpeg" :
   case "JPEG" :
        header( "Content-Type: image/jpeg" );
        break;
   case "png" :
   case "PNG" :
        header( "Content-Type: image/jpeg" );
        break;
   case "JPG" :
   case "jpg" :
        header( "Content-Type: image/jpg" );
        break;
   case "txt" :
   case "TXT" :
        header( "Content-Type: text/plain" );
        break;
    case "zip" :
    case "ZIP" :
        header( "Content-Type: application/zip" );
        break;
    case "doc" :
    case "DOC" :
        header( "Content-Type: application/msword" );
        break;
    case "ppt" :
    case "PPT" :
        header( "Content-Type: application/vnd.ms-powerpoint" );
        break;
    case "xls" :
    case "XLS" :
        header( "Content-Type: application/vnd.ms-excel" );
        break;
    case "PDF" :
    case "pdf" :
        header( "Content-Type: application/pdf" );
        break;
    default :
        header( "Content-Type: application/octet-stream" );
        break;
}

// xxx
if($file_debug){
  die("PDF: " . $suffix);
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header( "Accept-Ranges: bytes" );

// header( "Cache-Control:" );
header( "Content-Length: $size" );

if(!$download_style_inline){
  // can you make them download outside the main window ... not like this
  header( "Content-disposition: attachment; filename=$editedFileName" );
  // header( "Content-disposition: filename=$editedFileName" );
} else {
  // inline download (for pdfs)
  header( "Content-disposition: inline; filename=$editedFileName" );
  header( "Content-Transfer-Encoding: binary" );
}

// include the file's contents to browser ... 
$fh = eZPBFile::fopen( "var/site/storage/ezfilemanager/$filePath", "rb" );
fpassthru( $fh );
exit();

// original exit method
// break();

?>