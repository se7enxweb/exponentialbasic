<?php
//
// $Id: index_xmlrpc.php 9682 2002-07-31 11:25:38Z gl $
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


// Find out, where our files are.
if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_FILENAME, $regs ) )
{
    $siteDir = $regs[1];
    $index = "/" . $regs[2];
}
elseif ( ereg( "(.*/)([^\/]+\.php)/?", $PHP_SELF, $regs ) )
{
	// Some people using CGI have their $SCRIPT_FILENAME not right... so we are trying this.
    $siteDir = $DOCUMENT_ROOT . $regs[1];
    $index = "/" . $regs[2];
}
else
{
	// Fallback... doesn't work with virtual-hosts, but better than nothing
	$siteDir = "./";
	$index = "/index_xmlrpc.php";
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

// Get the webdir.
if ( ereg( "(.*)/([^\/]+\.php)$", $SCRIPT_NAME, $regs ) )
    $wwwDir = $regs[1];

// Fallback... Finding the paths above failed, so $PHP_SELF is not set right.
if ( $siteDir == "./" )
	$PHP_SELF = $REQUEST_URI;

// Trick: Rewrite setup doesn't have index.php in $PHP_SELF, so we don't want an $index
if ( ! ereg( ".*index_xmlrpc\.php.*", $REQUEST_URI ) )
    $index = "";
else
{
	// Get the right $REQUEST_URI, when using nVH setup.
    if ( ereg( "^$wwwDir$index(.+)", $REQUEST_URI, $req ) )
        $REQUEST_URI = $req[1];
    else
        $REQUEST_URI = "/";
}

// Remove url parameters
ereg( "([^?]+)", $REQUEST_URI, $regs );
$REQUEST_URI = $regs[1];

ob_end_clean();
ob_start();

// server information
// include_once( "classes/ezpublish.php" );
$version_string = eZPublish::version();
if ( ereg( "([0-9]+.[0-9]+).([0-9]+)", $version_string, $regs ) )
{
    define( "EZPUBLISH_SERVER_VERSION", $regs[1] );
    define( "EZPUBLISH_SERVER_RELEASE", $regs[2] );
}
else
{
    define( "EZPUBLISH_SERVER_VERSION", 0.0 );
    define( "EZPUBLISH_SERVER_RELEASE", 0 );
}


// Error codes
define( "EZERROR_BAD_LOGIN", 1 );
define( "EZERROR_INVALID_FUNCTION", 2 );
define( "EZERROR_CUSTOM", 3 );
define( "EZERROR_NO_RETURN_DATA", 4 );
define( "EZERROR_NONEXISTING_OBJECT", 5 );
define( "EZERROR_PHP_ERROR", 6 );
define( "EZERROR_BAD_REQUEST_DATA", 7 );
define( "EZERROR_NO_LOGIN", 8 );
define( "EZERROR_NO_PERMISSION", 9 );
define( "EZERROR_WRONG_VERSION", 10 );

// include the server
// include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
// include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
// include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

// site information
// include_once( "classes/INIFile.php" );
$ini =& eZINI::instance('site.ini');
$GlobalSiteIni =& $ini;

// Set the global nVH variables.
$GlobalSiteIni->Index = $index;
$GlobalSiteIni->UserIndex = "";
if ( $index != "" )
{
    if ( $GlobalSiteIni->hasVariable( "eZXMLRPC", "UserIndex" ) )
    {
        $GlobalSiteIni->UserIndex = $GlobalSiteIni->variable( "eZXMLRPC", "UserIndex" );
    }
    else
    {
        $GlobalSiteIni->UserIndex = "/index.php";
    }
}
$GlobalSiteIni->WWWDir = $wwwDir;
$GlobalSiteIni->SiteDir = $siteDir;
unset($index);
unset($wwwDir);

// File functions
// include_once( "classes/ezfile.php" );

// include_once( "classes/ezlog.php" );
error_reporting(0);
$old_error_handler = set_error_handler("xmlrpcErrorHandler");

// include_once( "classes/ezlocale.php" );


// eZ user
// include_once( "ezuser/classes/ezuser.php" );

// include_once( "ezuser/classes/ezpermission.php" );


// eZLog::writeNotice( "XML-RPC connect." );

$server = new eZXMLRPCServer( );

// Register server function
$server->registerFunction( "Call", array( new eZXMLRPCStruct( ) ) );

// process the server requests
$server->processRequest();

/*!
  Function to handle requests.
*/
function Call( $args )
{
    $call = $args[0]->value();
    if ( isset( $call["Version"] ) )
    {
        $version = $call["Version"]->value();
    }
    if ( isset( $call["Release"] ) )
    {
        $release = $call["Release"]->value();
    }
    $GLOBALS["version"] =& $version;
    $GLOBALS["release"] =& $release;
    if ( $version < EZPUBLISH_SERVER_VERSION and $release < EZPUBLISH_SERVER_RELEASE )
    {
        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["Module"] =& $Module;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        if( isset( $REQUEST_URI["ID"] ) and is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        $GLOBALS["ID"] =& $ID;

        $RefID = false;
        if ( isset( $call["RefID" ] ) and is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];
        $GLOBALS["RefID"] =& $RefID;

        // create the return struct...
        return createErrorMessage( EZERROR_WRONG_VERSION );
    }

    if ( !isset( $call["Session"] ) )
    {
        $RefID = false;
        if ( isset( $call["RefID" ] ) and is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];
        $GLOBALS["RefID"] =& $RefID;

        $Command = $call["Command"]->value();
        $GLOBALS["Command"] =& $Command;

        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["Module"] =& $Module;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        if( isset( $REQUEST_URI["ID"] ) and is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        $GLOBALS["ID"] =& $ID;

        if ( $Command != "login" )
        {
            return createErrorMessage( EZERROR_NO_LOGIN );
        }

        // we need to login first
        $login = $call["User"]->value();
        $GLOBALS["login"] =& $login;
        $password = $call["Password"]->value();
        $GLOBALS["password"] =& $password;
        $User = eZUser::validateUser( $login, $password );
        if ( !is_a( $user, "eZUser" ) )
            return createErrorMessage( EZERROR_BAD_LOGIN );
        if ( !eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
            return createErrorMessage( EZERROR_BAD_LOGIN );

        $session =& $GLOBALS["eZSessionObject"];
        $session = new eZSession();
//         $session =& eZSession::globalSession();
//         if ( isset( $call["LastSession"] ) and !empty( $call["LastSession"]->value() ) )
        if ( isset( $call["LastSession"] )  )
        {
            $hash = $call["LastSession"]->value();
        }
        else
        {
            $hash = md5( microtime() );
        }
        $GLOBALS["eZSessionCookie"] = $hash;

        if ( !$session->fetch() )
        {
            $session->store();
        }

        if ( !eZUser::loginUser( $User ) )
            return createErrorMessage( EZERROR_BAD_LOGIN );
        $hash = $session->hash();

    // Charset and language
        // include_once( "classes/ezlocale.php" );
        $ini =& eZINI::instance('site.ini');
        if ( $ini->hasVariable( "site", "Language" ) )
        {
            $Language = $ini->variable( "site", "Language" );
        }
        else
            $Language = $ini->variable( "eZCalendarMain", "Language" );
        $Locale = new eZLocale( $Language );
        $iso =& $Locale->languageISO();
        $ret_arr = array( "Session" => new eZXMLRPCString( $hash ),
                          "Language" => new eZXMLRPCString( $Language ) );
        if ( $iso != false )
            $ret_arr["Charset"] = new eZXMLRPCString( $iso );

        $ReturnData = new eZXMLRPCStruct( $ret_arr );

//         exit();

        // create the return struct...
        $ret_arr = array( "Version" => new eZXMLRPCDouble( EZPUBLISH_SERVER_VERSION ),
                          "Release" => new eZXMLRPCInt( EZPUBLISH_SERVER_RELEASE ),
                          "URL" => createURLStruct( $Module, $RequestType, $ID ),
                          "Command" => new eZXMLRPCString( $Command ),
                          "RefID" => $RefID,
                          "Data" => $ReturnData
                          );
        $ret = new eZXMLRPCStruct( $ret_arr );
        return $ret;
    }

    $hash = $call["Session"]->value();
    $GLOBALS["hash"] =& $hash;
    $GLOBALS["eZSessionCookie"] = $hash;
    $session =& $GLOBALS["eZSessionObject"];
    $session = new eZSession();
//     $session =& eZSession::globalSession();
    if ( !$session->fetch() )
    {
        $session->store();
    }

//      $login = $call["User"]->value();
//      $GLOBALS["login"] =& $login;
//      $password = $call["Password"]->value();
//      $GLOBALS["password"] =& $password;

//      $User = eZUser::validateUser( $login, $password );
    $User = eZUser::currentUser();

//      if ( is_a( $user, "eZUser" ) )
//      {
//          $logged_in = eZUser::loginUser( $User );
//          $cur = eZUser::currentUser();
//          $session =& eZSession::globalSession();
//  //          ob_start();
//  //          print( $session->variable( "AuthenticatedUser" ) . "\n" );
//  //          print_r( $cur );
//  //          eZLog::writeNotice( "user: " . ob_get_contents() );
//  //          ob_end_flush();
//      }

    $GLOBALS["User"] =& $User;

    if ( ( is_a( $user, "eZUser" ) ) and eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
    {
//          eZLog::writeNotice( "XML-RPC logged in." );

        // Get caller ID if any
        $caller = false;
        if ( isset( $call["Caller" ] ) and is_object( $call["Caller"] ) )
            $caller = $call["Caller"];
        $GLOBALS["caller"] =& $caller;

        $RefID = false;
        if ( isset( $call["RefID" ] ) and is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];
        $GLOBALS["RefID"] =& $RefID;

        // decode URL
        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["Module"] =& $Module;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        if( isset( $REQUEST_URI["ID"] ) and is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        $GLOBALS["ID"] =& $ID;

        $Data = $call["Data"]->value();
        $GLOBALS["Data"] =& $Data;
        $Command = $call["Command"]->value();
        $GLOBALS["Command"] =& $Command;

        $ReturnData = array();
        $GLOBALS["ReturnData"] =& $ReturnData;

        $Error = false;
        $GLOBALS["XMLRPC_Error"] =& $Error;
        $ret = "";
        $GLOBALS["ret"] =& $ret;
        $datasupplier = $Module . "/xmlrpc/datasupplier.php";
        if ( ( $Command == "search" && $Module == "" && $RequestType == "" ) ||
             file_exists( $datasupplier )  ||
             ( $Module == "ezpublish" && $RequestType == "modules" ) ||
             $Command == "logout" )
        {
            // check for module implementation
            if ( $Command == "search" && $Module == "" && $RequestType == "" )
            {
                // We handle global search ourselves
                $modules = array( "ezarticle", "ezimagecatalogue" );
                foreach( $modules as $module )
                {
                    $search_file = $module . "/xmlrpc/search.php";
                    if ( file_exists( $search_file ) )
                    {
                        include( $search_file );
                    }
                }
            }
            else if ( $Command == "logout" )
            {
                eZUser::logout();
                $ReturnData = new eZXMLRPCString( "Logged out" );
            }
            else if ( $Module == "ezpublish" && $RequestType == "modules" )
            {
                // return the modules in the system
                $dir = eZFile::dir( "." );
                $modules = array();
                while ( $entry = $dir->read() )
                {
                    if ( preg_match( "/ez.*/", $entry ) )
                    {
                        if ( file_exists( $entry . "/xmlrpc/datasupplier.php" ) )
                        {
                            $ReturnCatalogues = true;
                            include( $entry . "/xmlrpc/datasupplier.php" );

                            $modules[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $entry ),
                                                                    "Catalogues" => new eZXMLRPCArray( $Catalogues )
                                                                    ) );

                        }
                    }
                }

//                  eZLog::writeNotice( "XML-RPC returning modules." );
                $ReturnData = $modules;

            }
            else
            {
//                  eZLog::writeNotice( "XML-RPC returning standard data." );
                include( $datasupplier );

            }

            if ( !is_object( $RefID ) )
            {
                $RefID = new eZXMLRPCString( md5( microtime() ) );
            }

            if ( $Error and !is_object( $Error ) )
            {
                $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
            }
            else if ( $Error and is_object( $Error ) )
            {
            }
            else if ( isset( $ReturnData ) and is_object( $ReturnData ) )
            {
                // create the return struct...
                $ret_arr = array( "Version" => new eZXMLRPCDouble( EZPUBLISH_SERVER_VERSION ),
                                  "Release" => new eZXMLRPCInt( EZPUBLISH_SERVER_RELEASE ),
                                  "URL" => createURLStruct( $Module, $RequestType, $ID ),
                                  "Command" => new eZXMLRPCString( $Command ),
                                  "RefID" => $RefID,
                                  "Data" => $ReturnData
                                  );
                if ( is_object( $caller ) )
                    $ret_arr["Caller"] = $caller;
                $ret = new eZXMLRPCStruct( $ret_arr );
            }
            else
            {
                $ret =& createErrorMessage( EZERROR_NO_RETURN_DATA );
            }

            if ( is_a( $Error, "eZXMLRPCResponse" ) )
            {
                $ret = $Error;
            }
        }
        else
        {
            $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
        }

//          eZLog::writeNotice( "XML-RPC returning  data." );
        return $ret;
    }
    else
    {
        $ret =& createErrorMessage( EZERROR_BAD_LOGIN );
        return $ret;
    }
}

/*!
  Added the urls $urls to the search structure, if not search
  structure exists a new is created.
*/

function appendSearchURLS( $urls )
{
    global $ReturnData;
    global $Data;
    if ( is_object( $ReturnData ) )
        $ret =& $ReturnData->value();
    else
        $ret = array( "Elements" => new eZXMLRPCArray( array() ) );
    if ( isset( $ret["NextSearch"] ) )
        $next =& $ret["NextSearch"]->value();
    else
        $next = array();
    foreach( $urls as $url )
    {
        $next[] = $url;
    }
    if ( !isset( $ret["NextSearch"] ) )
        $ret["NextSearch"] = new eZXMLRPCArray( $next );
    if ( !isset( $ret["Keywords"] ) )
        $ret["Keywords"] = $Data["Keywords"];
    if ( isset( $Data["Parameters"] ) && !isset( $ret["Parameters"] ) )
        $ret["Parameters"] = $Data["Parameters"];
    if ( !is_object( $ReturnData ) )
        $ReturnData = new eZXMLRPCStruct( $ret );
}

/*!
  Makes the $ret variable contain the proper data taken from the current search.
*/

function handleSearchData( &$ret )
{
    global $Data;
    if ( isset( $Data["NextSearch"] ) )
    {
        $ret["NextSearch"] = $Data["NextSearch"];
        $ret["Keywords"] = $Data["Keywords"];
    }
    if ( isset( $Data["Parameters"] ) )
        $ret["Parameters"] = $Data["Parameters"];
}

/*!
  Catches PHP errors and sends the result as an XMLRPC response to the client.
*/

function xmlrpcErrorHandler ($errno, $errmsg, $filename, $linenum, $vars)
{
    global $XMLRPC_Error;
// timestamp for the error entry
    $dt = date("Y-m-d H:i:s (T)");

    // define an assoc array of error string
    // in reality the only entries we should
    // consider are 2,8,256,512 and 1024
    $errortype = array (
        1   =>  "Error",
        2   =>  "Warning",
        4   =>  "Parsing Error",
        8   =>  "Notice",
        16  =>  "Core Error",
        32  =>  "Core Warning",
        64  =>  "Compile Error",
        128 =>  "Compile Warning",
        256 =>  "User Error",
        512 =>  "User Warning",
        1024=>  "User Notice"
        );
    // set of errors for which a var trace will be saved
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

    $err = "<errorentry>\n";
    $err .= "\t<datetime>".$dt."</datetime>\n";
    $err .= "\t<errornum>".$errno."</errnumber>\n";
    $err .= "\t<errortype>".$errortype[$errno]."</errortype>\n";
    $err .= "\t<errormsg>".$errmsg."</errormsg>\n";
    $err .= "\t<scriptname>".$filename."</scriptname>\n";
    $err .= "\t<scriptlinenum>".$linenum."</scriptlinenum>\n";

    if (in_array($errno, $user_errors))
        $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
    $err .= "</errorentry>\n\n";

    // for testing
    // echo $err;

    // save to the error log, and e-mail me if there is a critical user error
//      error_log($err, 3, "/usr/local/php4/error.log");
    if ( $errno != 8 and $errno != 2 )
    {
        eZLog::writeError( $err );
//          $XMLRPC_Error = createErrorMessage( EZ_ERROR_PHP_ERROR, $err );
    }
//      if ($errno == E_USER_ERROR)
//          mail("phpdev@mydomain.com","Critical User Error",$err);
}

function &createErrorMessage( $error_id, $error_msg = false, $error_sub_id = false )
{
    global $ID;
    global $Command;
    global $Module;
    global $RequestType;
    global $version;
    $ret = new eZXMLRPCResponse( );
    switch( $error_id )
    {
        case EZERROR_BAD_LOGIN:
        {
            $error_text = "Login denied, please try again.";
            break;
        }
        case EZERROR_INVALID_FUNCTION:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Server function \"$Command\" for URL \"$Module:/$RequestType/$id_text\" not found.";
            break;
        }
        case EZERROR_NO_RETURN_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "No return data while processing \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_NONEXISTING_OBJECT:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Object does not exist, used command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_PHP_ERROR:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "PHP error for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            $error_text .= "Error was: $error_msg";
            break;
        }
        case EZERROR_BAD_REQUEST_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Bad request data for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            break;
        }
        case EZERROR_NO_LOGIN:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Client not logged in and no login data available\n";
            break;
        }
        case EZERROR_NO_PERMISSION:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "User has no permission to access URL \"$Module:/$RequestType/$id_text\"\n";
            break;
        }
        case EZERROR_WRONG_VERSION:
        {
            $error_text = "Wrong version,\nthis server requires you to have a client higher or equal to " . EZPUBLISH_SERVER_VERSION . "." . EZPUBLISH_SERVER_RELEASE . ",\nyour version was $version";
            break;
        }
        case EZERROR_CUSTOM:
        {
            $error_text = $error_msg;
            break;
        }
        default:
        {
            $error_text = "Unknown error";
        }
    }
    $ret->setVersion( EZPUBLISH_SERVER_VERSION, EZPUBLISH_SERVER_RELEASE );
    $ret->setError( $error_id, $error_text, $error_sub_id );
//     eZLog::writeError( "ID: $error_id, SubID: $error_sub_id, Text: $error_text" );
    return $ret;
}

/*!
  Creates an url XMLRPC struct from the $module, $type and $id.
  If $id is equal to 0 it is not included in the struct.
  The resulting struct is returned.
*/

function createURLStruct( $module, $type , $id = 0 )
{
    if( $id != 0 )
    {
        $ret = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $id ),
                                          "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) ) );
    }
    else
    {
        $ret = new eZXMLRPCStruct( array( "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) ) );
    }
    return $ret;
}

/*!
  Creates a size XMLRPC struct of the $width and $height params and returns it.
*/

function createSizeStruct( $width, $height )
{
    $ret = new eZXMLRPCStruct( array( "Width" => new eZXMLRPCInt( $width ),
                                      "Height" => new eZXMLRPCInt( $height ) ) );
    return $ret;
}

/*!
  Creates a Date XMLRPC struct of an eZDate or eZDateTime object and returns it.
*/

function createDateStruct( $date )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $date->year() ),
                                      "Month" => new eZXMLRPCInt( $date->month() ),
                                      "Day" => new eZXMLRPCInt( $date->day() ) ) );
    return $ret;
}

/*!
  Creates a DateTime XMLRPC struct of an eZDateTime object and returns it.
*/

function createDateTimeStruct( $datetime )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $datetime->year() ),
                                      "Month" => new eZXMLRPCInt( $datetime->month() ),
                                      "Day" => new eZXMLRPCInt( $datetime->day() ),
                                      "Hour" => new eZXMLRPCInt( $datetime->hour() ),
                                      "Minute" => new eZXMLRPCInt( $datetime->minute() ),
                                      "Second" => new eZXMLRPCInt( $datetime->second() ) ) );
    return $ret;
}

/*!
  Creates a XMLRPC structure of a tree. Each entry in the tree will have an url struct
  and a name. The url struct consists of $module, $type and each respective $id from the tree.
  The $tree input must be an array with "ID" being the id of the top level,
  "Name" being the name of the top level and "Children" being an array of the children
  where each children is the same structure as the toplevel.
  The resulting tree structure is returned.
*/

function &createTreeStruct( $tree, $module, $type )
{
    $id = $tree["ID"];
    $name = $tree["Name"];
    $children = $tree["Children"];
    $child_arr = array();
    foreach( $children as $child )
    {
        $child_arr[] =& createTreeStruct( $child, $module, $type );
    }
    $item_arr = array( "Location" => createURLStruct( $module, $type, $id ),
                       "Name" => new eZXMLRPCString( $name ) );
    if ( isset( $tree["Section"] ) )
        $item_arr["Section"] = $tree["Section"];
    if ( count( $child_arr ) > 0 )
        $item_arr["Children"] = $child_arr;
    $item = new eZXMLRPCStruct( $item_arr );
    return $item;
}

/*!
  Creates a eZDateTime object from a DateTime struct and returns it.
  \sa createDateTimeStruct()
*/

function createDateTime( $struct )
{
    $datetime = new eZDateTime();
    $datetime->setYear( $struct["Year"]->value() );
    $datetime->setMonth( $struct["Month"]->value() );
    $datetime->setDay( $struct["Day"]->value() );
    $datetime->setHour( $struct["Hour"]->value() );
    $datetime->setMinute( $struct["Minute"]->value() );
    $datetime->setSecond( $struct["Second"]->value() );
    return $datetime;
}

/*!
  Creates an array of urls which represents the total path of a certain object.
  The object is require to have a function path() which returns an array of category ids,
  the object must also have a function id() which returns the id of the object.
  Each url will have the $module, $type and corresponding id from the array.
  If include_self is false the category id which has the same id as the object is
  not included.
  The resulting url array is returned.
*/

function &createPath( &$obj, $module, $type, $include_self = true )
{
    $par = array();
    if ( is_object( $obj ) )
    {
        $path =& $obj->path();
        if ( $obj->id() != 0 )
            $par[] = createURLStruct( $module, $type, 0 );
        else
            $par[] = createURLStruct( $module, "" );
        foreach( $path as $item )
        {
            if ( $include_self or $item[0] != $obj->id() )
                $par[] = createURLStruct( $module, $type, $item[0] );
        }
    }
    return $par;
}

/*!
  Creates an array of urls structs from an array of ids(int),
  each url will consist of the $module $type and $id of each entry in the list.
  The resulting url array is returned.
*/

function &createURLArray( &$ids, $module, $type )
{
    $arr = array();
    foreach( $ids as $id )
    {
        $arr[] = createURLStruct( $module, $type, $id );
    }
    $ret = new eZXMLRPCArray( $arr );
    return $ret;
}

/*!
  Rewrites the URL $url to work with non virtualhost setups.
  Prepends WWWDir and UserIndex and returns it.
  On virtualhost setups the url is simply returned.
  Use this for ezpublish style urls ie urls which need the index.php set.
*/
function rewriteWebURL( $url )
{
    global $GlobalSiteIni;
    if ( $GlobalSiteIni->UserIndex != "" )
    {
        $url = $GlobalSiteIni->WWWDir . $GlobalSiteIni->UserIndex . $url;
    }
    return $url;
}

/*!
  Rewrites the URL $url to work with non virtualhost setups.
  Prepends WWWDir and returns it.
  On virtualhost setups the url is simply returned.
  Use this for normal http urls ie image and file download
*/
function rewriteHTTPURL( $url )
{
    global $GlobalSiteIni;
    $url = $GlobalSiteIni->WWWDir . $url;
    return $url;
}

ob_end_flush();
exit();

?>