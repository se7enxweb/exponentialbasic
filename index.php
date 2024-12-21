<?php

ini_set('display_errors','On');
ini_set('max_execution_time', '300');

//
// $Id: index.php 9864 2003-07-11 07:27:00Z br $
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header( "X-Powered-By: eZ publish 2" );

// Find out, where our files are.
if ( preg_match( "/(.*\/)([^\/]+\.php)$/", $_SERVER['SCRIPT_FILENAME'], $regs ) )
{
    $siteDir = $regs[1];
    $index = "/" . $regs[2];
}
elseif ( preg_match( "/(.*\/)([^\/]+\.php)/?/", $_SERVER['PHP_SELF'], $regs ) )
{
    // Some people using CGI have their $SCRIPT_FILENAME not right... so we are trying this.
    $siteDir = $_SERVER['DOCUMENT_ROOT'] . $regs[1];
    $index = "/" . $regs[2];
}
else
{
    // Fallback... doesn't work with virtual-hosts, but better than nothing
    $siteDir = "./";
    // kracker: changed from 2.2 to run on firebright vhost w/out index.php
    // $index = "/index.php";
    $index = "/";
}

// What OS-type are we using?
if ( substr( php_uname(), 0, 7) == "Windows" )
    $separator = ";";
else
    $separator = ":";

// Setting the right include_path
$includePath = ini_get( "include_path" );
if ( trim( $includePath ) != "" )
    $includePath .= $separator . $siteDir;
else
    $includePath = $siteDir;
ini_set( "include_path", $includePath );

$wwwDir='';
// Get the webdir.
if ( preg_match( "/(.*)\/([^\/]+\.php)$/", $_SERVER['SCRIPT_NAME'], $regs ) )
    $wwwDir = $regs[1];

// Fallback... Finding the paths above failed, so $PHP_SELF is not set right.
if ( $siteDir == "./" )
     $_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'];

// Trick: Rewrite setup doesn't have index.php in $PHP_SELF, so we don't want an $index
if ( ! preg_match( "/.*index\.php.*/", $_SERVER['PHP_SELF'] ) )
    $index = "";
else
{
    // Get the right $REQUEST_URI, when using nVH setup.
    if ( preg_match( '/^ ' . $wwwDir . str_replace('/', '\/', $index ) . '(.+)/', $_SERVER['PHP_SELF'], $req ) )
        $_SERVER['REQUEST_URI'] = $req[1];
}

// Remove url parameters
preg_match( "/([^?]+)/", $_SERVER['REQUEST_URI'], $regs );
$_SERVER['REQUEST_URI'] = $regs[1];


$GLOBALS["DEBUG"] = false;
$GLOBALS["DDD"] = '';
$UsePHPSessions = false;

ob_start();
// Turn on output buffering with gz compression
//ob_start("ob_gzhandler");


if ( $UsePHPSessions == true )
{
    // start session handling
    session_start();
}

// Include composer based autoloads (new in 2.4.0.0)
require __DIR__ . '/vendor/autoload.php';

// settings for sessions
// max timeout is set to 48 hours
ini_alter( "session.gc_maxlifetime", "172800" );
ini_alter( "session.entropy_file","/dev/urandom" );
ini_alter( "session.entropy_length", "512" );

// kracker: changed from 2.2 to run on firebright vhost w/out index.php in auto generated urls
$index = "";

/*
// include_once( "classes/INIFile.php" );
// include_once( "classes/ezdb.php" );
// include_once( "classes/ezhttptool.php" );
*/
$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;


// Set the global nVH variables.
$GlobalSiteIni->Index = $index;
$GlobalSiteIni->WWWDir = $wwwDir;
$GlobalSiteIni->SiteDir = $siteDir;
unset( $index );
unset( $wwwDir );

// Design
// // include_once( "ezsession/classes/ezsession.php" );
// // include_once( "ezuser/classes/ezuser.php" );

// File functions
// // include_once( "classes/ezfile.php" );

$session =& eZSession::globalSession();

//
unset( $siteDesign );
unset( $GlobalSiteDesign );
$siteDesign =& $ini->read_var( "site", "SiteDesign" );

// Store the site design in a global variable
$GlobalSiteDesign = $siteDesign;


$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" and $GlobalSiteIni->Index != "" )
{
    // do the statistics
    // // include_once( "ezstats/classes/ezpageview.php" );

    // if we are using nVH setup, we need to store our stats here
    if ( isSet( $GlobalSiteIni->SiteDir ) and $GlobalSiteIni->SiteDir != "" )
    {
        // create a global page view object for statistics
        // and store the stats
        $GlobalPageView = new eZPageView();
        $GlobalPageView->store();
    }
}

// parse the URI
$meta_page = "";
$content_page = "";

// Check if userlogin is required
$user =& eZUser::currentUser();

$requireUserLogin =& $ini->read_var( "eZUserMain", "RequireUserLogin" );

// Cookie auto login.
if ( isSet( $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] ) and $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] != false )
{
    if ( ( !$user ) && ( $ini->read_var( "eZUserMain", "AutoCookieLogin" ) == "enabled" ) )
    {
        eZUser::autoCookieLogin( $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] );
    }
}

$url_array = explode( "/", $_SERVER['REQUEST_URI'] );
$url_array_count = count( $url_array );

for( $i = $url_array_count; $i <= 25; $i++ )
{
    $url_array[$i] = false;
}

if ( ( $requireUserLogin == "disabled" ) ||
     ( ( $requireUserLogin == "enabled" ) && ( is_a( $user, "eZUser" ) ) && ( $user->id() != 0 ) ) )
{

    // do url translation if needed
    $URLTranslationKeyword = $ini->read_var( "site", "URLTranslationKeyword" );

    $urlTranslatorArray = explode( ";", $URLTranslationKeyword );

    if ( in_array( $url_array[1], $urlTranslatorArray ) )
    {
        // // include_once( "ezurltranslator/classes/ezurltranslator.php" );
        $translatedURL = eZURLTranslator::translate( $_SERVER['REQUEST_URI'] );
        if ( $translatedURL )
        {
            $_SERVER['REQUEST_URI'] = $translatedURL;
            $url_array = explode( "/", $_SERVER['REQUEST_URI'] );
        }
    }

    // if uri == / show default page or article list
    if ( $_SERVER['REQUEST_URI'] == "/" )
    {
        if ( $ini->read_var( "site", "DefaultPage" ) == "disabled" )
        {
            $_SERVER['REQUEST_URI'] = "/article/archive/0/";
        }
        else
        {
            $_SERVER['REQUEST_URI'] = $ini->read_var( "site", "DefaultPage" );
        }

        if ( $user )
        {
            $mainGroup = $user->groupDefinition( true );
            if ( ( $mainGroup ) && $mainGroup->groupURL() )
            {
                $_SERVER['REQUEST_URI'] = $mainGroup->groupURL();
            }
        }
        $url_array = explode( "/", $_SERVER['REQUEST_URI'] );
    }

    // 7x Register Globals Replacement
    foreach($_COOKIE as $key => $value) {
        if(!is_array($value)){
            ${$key} = trim(rawurldecode($value));
            //echo "$key $value<br>";
        }
        else{
            ${$key} = $value;
        }
    }
    foreach($_GET as $key => $value) {
        if(!is_array($value)){
            ${$key} = trim(rawurldecode($value));
            //echo "$key $value<br>";
        }
        else{
            ${$key} = $value;
        }
    }
    foreach($_POST as $key => $value) {
        if(!is_array($value)){
            ${$key} = trim(rawurldecode($value));
            //echo "$key $value<br>";
        }
        else{
            ${$key} = $value;
        }
    }
    foreach($_REQUEST as $key => $value) {
        if(!is_array($value)){
            ${$key} = trim(rawurldecode($value));
            //echo "$key $value<br>";
        }
        else{
            ${$key} = $value;
        }
    }
    foreach($_SERVER as $key => $value) {
        if(!is_array($value)){
            ${$key} = trim(rawurldecode($value));
            //echo "$key $value<br>";
        }
        else{
            ${$key} = $value;
        }
    }

    // Load the main contents and store in a variable
    $content_page = "ez" . $url_array[1] . "/user/datasupplier.php";

    // site cache check
    $SiteCacheFile = "classes/cache/" . md5( $_SERVER['REQUEST_URI'] ) . ".php";
    $SiteCache = $ini->read_var( "site", "SiteCache" );

    //This is horrible!!
    if ( $_SERVER['REQUEST_METHOD'] == "POST" ||
         $url_array[1] == "forum" ||
         $url_array[1] == "user" ||
         $url_array[1] == "error" ||
         $url_array[1] == "poll" )
    {
        $SiteCache = "disabled";
    }

    // check to use site cache
    if ( ( $SiteCache == "enabled" ) and !eZFile::file_exists( $SiteCacheFile ) )
    {
        $StoreSiteCache = true;
    }
    else
    {
        $StoreSiteCache = false;

        if ( $SiteCache == "enabled" and eZFile::file_exists( $SiteCacheFile ) )
        {
            $timeout = $ini->read_var( "site", "SiteCacheTimeout" );
            $SiteCacheTime = eZFile::filemtime( $SiteCacheFile );
            if ( ( time() - $SiteCacheTime ) < ( $timeout * 60 ) )
            {
             // print( "valid cache" );
            }
            else
            {
                $StoreSiteCache = true;

                // delete cache file
                eZFile::unlink( $SiteCacheFile );
                //  print( "time out-clearing cache" );
            }
        }
    }

    if ( $StoreSiteCache || $SiteCache == "disabled" )
    {
        $buffer =& ob_get_contents();
        ob_end_clean();
        ob_start();

        // fetch the module printout
        if ( eZFile::file_exists( $content_page ) )
        {
            // the page with the real contents
            include( $content_page );
            // The following variables can be set from the contents page:
            // $PrintableVersion = "enabled | disabled";
            // $GlobalSectionID = integer value, reference to the selected section.
            // $SiteTitleAppend = string which will be appended to the site title
            // $SiteDescriptionOverride = string which will override the meta content information
        }
        else
        {
            // the default page to load
            if ( file_exists( $ini->read_var( "site", "DefaultPage" ) ) )
            {
                include( $ini->read_var( "site", "DefaultPage" ) );
            }
            else if ( $ini->read_var( "site", "DefaultPage" ) != "disabled" )
            {
                $_SERVER['REQUEST_URI'] = $ini->read_var( "site", "DefaultPage" );
                $url_array = explode( "/", $_SERVER['REQUEST_URI'] );
                $content_page = "ez" . $url_array[1] . "/user/datasupplier.php";
                include( $content_page );
            }
        }

        // set character set
        // // include_once( "classes/ezlocale.php" );
        if ( isset( $GLOBALS["eZLanguageOverride"] ) and $GLOBALS["eZLanguageOverride"] != "" )
        {
            $Language = $GLOBALS["eZLanguageOverride"];
        }
        else if ( $ini->has_var( "site", "Language" ) )
        {
            $Language = $ini->read_var( "site", "Language" );
        }
        else
        {
            $Language = $ini->read_var( "eZCalendarMain", "Language" );
        }
        $Locale = new eZLocale( $Language );
        $iso =& $Locale->languageISO();
        if ( $iso != false )
            header( "Content-type: text/html;charset=$iso" );


        $MainContents =& ob_get_contents();
        ob_end_clean();

        // fill the buffer with the old values
        ob_start();
        print( $buffer );

        // set the sitedesign from the section
        if ( $ini->read_var( "site", "Sections" ) == "enabled" )
        {
            if ( !is_numeric( $GlobalSectionID ) )
            {
                $GlobalSectionID = $ini->read_var( "site", "DefaultSection" );
            }

            // // include_once( "ezsitemanager/classes/ezsection.php" );

            if ( isset($_REQUEST['SectionIDOverride']) && is_numeric( $_REQUEST['SectionIDOverride'] ) )
            {
                $GlobalSectionID = $_REQUEST['SectionIDOverride'];
            }

            // init the section
            $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
            $sectionObject->setOverrideVariables();

            if ( $DEBUG == true )
            {
//                print( "Section Debug $GlobalSectionID: <br>" );
//                print( "sitedesign: " . $sectionObject->siteDesign() . " <br>" );
//                print( "template: " . $sectionObject->templateStyle() . " <br>" );
//                print( "language: " . $sectionObject->language() . " <br>" );
            }

            $sectionSiteDesign = eZSection::siteDesign( $GlobalSectionID );

            if ( $sectionSiteDesign != "" )
            {
                $siteDesign = $sectionSiteDesign;
                $GlobalSiteDesign = $sectionSiteDesign;
            }
        }

        // include some html
        $Title = $ini->read_var( "site", "SiteTitle" );

        // Main contents
        // handled by the sitedesign/$design/frame.php file now..
        // print( $MainContents );

        // include framework
        if ( isSet( $PrintableVersion ) and $PrintableVersion == "enabled" )
        {
            include( "design/$siteDesign/simpleframe.php" );
        }
        else
        {
            include( "design/$siteDesign/frame.php" );
        }

        // store site cache
        if ( $StoreSiteCache == true )
        {
            $fp = fopen( $SiteCacheFile, "w+");

            $SiteContents =& ob_get_contents();
            fwrite( $fp, $SiteContents );
            fclose( $fp );
        }
    }
    else
    {
        // load site cache
        $fp = fopen( $SiteCacheFile, r );
        fpassthru( $fp );
    }

}
else
{
    // parse the URI
    $page = "";

    // send the URI to the right decoder
    $page = "ezuser/user/datasupplier.php";
    if ( eZFile::file_exists( $page ) )
    {
        include( $page );
    }

    $MainContents =& ob_get_contents();
    ob_end_clean();
    ob_start();

    include( "design/$siteDesign/loginframe.php" );
}


// close the database connection.
$db =& eZDB::globalDatabase();
$db->close();

ob_end_flush();