<?php
//
// $Id: userwithaddress.php 9662 2002-07-08 15:16:41Z bf $
//
// Created on: <10-ct-2000 12:52:42 bf>
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


require( "kernel/ezuser/user/usercheck.php" );

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "ezsession/classes/ezsession.php" );
// include_once( "classes/eztexttool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$SelectCountry = $ini->read_var( "eZUserMain", "SelectCountry" );
$SelectRegion = $ini->read_var( "eZUserMain", "SelectRegion" );
$AnonymousUserGroup = $ini->read_var( "eZUserMain", "AnonymousUserGroup" );
$ForceSSL = $ini->read_var( "eZUserMain", "ForceSSL" );
$AutoCookieLogin = eZHTTPTool::getVar( "AutoCookieLogin" );

$session =& eZSession::globalSession();

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezaddress/classes/ezcountry.php" );
// include_once( "ezmail/classes/ezmail.php" );

$user =& eZUser::currentUser();

// set SSL mode and redirect if not already in SSL mode.
if ( ( $ForceSSL == "enabled" ) )
{
    // force SSL if supposed to
    if ( $SERVER_PORT != '443' )
    {
   		$session->setVariable( "SSLMode", "enabled" );
        eZHTTPTool::header("Location: https://" . $HTTP_HOST . $REQUEST_URI );
 //       eZHTTPTool::header("Location: https://" . $HTTP_HOST . "/user/userwithaddress/edit/" . $user->id() );
//        print( "<font color=\"#333333\">Start: Location: https://" . $HTTP_HOST . $REQUEST_URI . "</font>" );
        exit();
    }
}
elseif ( $ForceSSL == "disabled" )
{
	if ( $SERVER_PORT == '443' )
    {
    	$session->setVariable( "SSLMode", "disabled" );
		eZHTTPTool::header("Location: http://" . $HTTP_HOST . "/user/userwithaddress/edit/" . $user->id() );
		exit();
	}
}

$t = new eZTemplate( "kernel/ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "kernel/ezuser/user/intl/", $Language, "userwithaddress.php" );

$t->setAllStrings();

$t->set_file( "user_edit_tpl", "userwithaddress.tpl" );

$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );
$t->set_block( "user_edit_tpl", "missing_address_error_tpl", "missing_address_error" );
$t->set_block( "user_edit_tpl", "address_actions_tpl", "address_actions" );

$t->set_block( "user_edit_tpl", "address_tpl", "address" );
$t->set_block( "address_tpl", "main_address_tpl", "main_address" );
$t->set_block( "address_tpl", "delete_address_tpl", "delete_address" );
$t->set_block( "address_tpl", "country_tpl", "country" );
$t->set_block( "country_tpl", "country_option_tpl", "country_option" );

$t->set_block( "address_tpl", "region_tpl", "region" );
$t->set_block( "region_tpl", "region_option_tpl", "region_option" );

$t->set_block( "user_edit_tpl", "errors_item_tpl", "errors_item" );
$t->set_var( "errors_item", "" );

$t->set_block( "user_edit_tpl", "login_item_tpl", "login_item" );
$t->set_block( "user_edit_tpl", "disabled_login_item_tpl", "disabled_login_item" );

// Info templates
$t->set_block( "user_edit_tpl", "info_item_tpl", "info_item" );
$t->set_block( "info_item_tpl", "info_updated_tpl", "info_updated" );

// Error templates
$t->set_block( "errors_item_tpl", "error_login_tpl", "error_login" );
$t->set_block( "errors_item_tpl", "error_login_exists_tpl", "error_login_exists" );
$t->set_block( "errors_item_tpl", "error_first_name_tpl", "error_first_name" );
$t->set_block( "errors_item_tpl", "error_last_name_tpl", "error_last_name" );
$t->set_block( "errors_item_tpl", "error_email_tpl", "error_email" );
$t->set_block( "errors_item_tpl", "error_email_not_valid_tpl", "error_email_not_valid" );
$t->set_block( "errors_item_tpl", "error_password_match_tpl", "error_password_match" );
$t->set_block( "errors_item_tpl", "error_password_too_short_tpl", "error_password_too_short" );
$t->set_block( "errors_item_tpl", "error_password_not_entered_tpl", "error_password_not_entered" );

$t->set_block( "errors_item_tpl", "error_address_street1_tpl", "error_address_street1" );
$t->set_block( "errors_item_tpl", "error_address_street2_tpl", "error_address_street2" );
$t->set_block( "errors_item_tpl", "error_address_zip_tpl", "error_address_zip" );
$t->set_block( "errors_item_tpl", "error_address_place_tpl", "error_address_place" );

$t->set_block( "errors_item_tpl", "error_missing_address_tpl", "error_missing_address" );
$t->set_block( "errors_item_tpl", "error_missing_country_tpl", "error_missing_country" );

$t->set_block( "errors_item_tpl", "error_missing_region_tpl", "error_missing_region" );

$t->set_block( "user_edit_tpl", "new_user_tpl", "new_user" );
$t->set_block( "user_edit_tpl", "edit_user_tpl", "edit_user" );
$t->set_block( "user_edit_tpl", "edit_user_info_tpl", "edit_user_info" );

$t->set_block( "user_edit_tpl", "ok_button_tpl", "ok_button" );
$t->set_block( "user_edit_tpl", "submit_button_tpl", "submit_button" );

$t->set_var( "error_login", "" );
$t->set_var( "error_login_exists", "" );
$t->set_var( "error_first_name", "" );
$t->set_var( "error_last_name", "" );
$t->set_var( "error_email", "" );
$t->set_var( "error_email_not_valid", "" );
$t->set_var( "error_password_match", "" );
$t->set_var( "error_password_too_short", "" );
$t->set_var( "error_password_not_entered", "" ); 
$t->set_var( "error_missing_country", "" );

$t->set_var( "error_address_place", "" );
$t->set_var( "error_address_zip", "" );
$t->set_var( "error_address_street1", "" );
$t->set_var( "error_address_street2", "" );

$t->set_var( "first_name_value", "$FirstName" );
$t->set_var( "last_name_value", "$LastName" );
$t->set_var( "login_value", "$Login" );
$t->set_var( "email_value", "$Email" );
$t->set_var( "password_value", "$Password" );
$t->set_var( "verify_password_value", "$VerifyPassword" );
$t->set_var( "address_actions", "" );
if ( $AutoCookieLogin == "on" )
{
    $t->set_var( "is_cookie_selected", "checked" );
}

if( isset($NoAddress) && $NoAddress == true )
{
    $t->set_var( "no_address", "no" );
}
else
{
    $t->set_var( "no_address", "" );
}

$t->set_var( "ok_button", "" );
$t->set_var( "submit_button", "" );
$t->set_var( "new_user", "" );
$t->set_var( "edit_user", "" );
$t->set_var( "edit_user_info", "" );
if ( is_a( $user, "eZUser" ) )
{
    $t->parse( "submit_button", "submit_button_tpl" );
    $t->parse( "edit_user", "edit_user_tpl" );
    $t->parse( "edit_user_info", "edit_user_info_tpl" );
}
else
{
    $t->parse( "ok_button", "ok_button_tpl" );
    $t->parse( "new_user", "new_user_tpl" );
}

// Defaults
$user_insert = false;
$Signature = '';
// Set error checking.
$error = false;
$nameCheck = true;
$emailCheck = true;
$firstNameCheck = true;
$lastNameCheck = true;
$loginCheck = true;
$passwordCheck = true;
$street1Check = true;
$street2Check = false;
$zipCheck = true;
$placeCheck = true;
$addressCheck = true;

// If the user is trying to buy without having a address
if ( isset( $MissingAddress ) && $MissingAddress == true )
{
    $t->parse( "error_missing_address", "error_missing_address_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_address", "" );
    $t->set_var( "action_value", "update" );
}

// If the user is trying to buy without having a address
if ( isset( $MissingCountry ) && $MissingCountry == true )
{
    $t->parse( "error_missing_country", "error_missing_country_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_country", "" );
    $t->set_var( "action_value", "update" );
}
// If the user is trying to buy without having a address
if ( $MissingRegion == true )
{
    $t->parse( "error_missing_region", "error_missing_region_tpl" );

    $t->parse( "errors_item", "errors_item_tpl" );

    $t->set_var( "action_value", "update" );
}
else
{
    $t->set_var( "error_missing_region", "" );
    $t->set_var( "action_value", "update" );
}
// Check for errors when inserting, updating and inserting a new address
if ( isset( $OK ) or isset( $OK_x ) )
{
    if ( $loginCheck )
    {
        if ( !is_a( $user, "eZUser" ) and $Login == "" )
        {
            $t->parse( "error_login", "error_login_tpl" );
            $error = true;
        }
        else if ( !is_a( $user, "eZUser" ) )
        {
            if ( eZUser::exists( $Login ) == true )
            {
                $t->parse( "error_login_exists", "error_login_exists_tpl" );
                $error = true;
            }
        }
    }

    if ( $firstNameCheck and $FirstName == "" )
    {
        $t->parse( "error_first_name", "error_first_name_tpl" );
        $error = true;
    }

    if ( $lastNameCheck and $LastName == "" )
    {
        $t->parse( "error_last_name", "error_last_name_tpl" );
        $error = true;
    }

    if ( $emailCheck )
    {
        if( $Email == "" )
        {
            $t->parse( "error_email", "error_email_tpl" );
            $error = true;
        }
        else
        {
            if( eZMail::validate( $Email ) == false )
            {
                $t->parse( "error_email_not_valid", "error_email_not_valid_tpl" );
                $error = true;
            }
        }
    }

    if ( $passwordCheck )
    {
        if ( $Password != $VerifyPassword )
        {
            $t->parse( "error_password_match", "error_password_match_tpl" );
            $error = true;

        }
        if ( strlen( $VerifyPassword ) < 2 )
        {
            $t->parse( "error_password_too_short", "error_password_too_short_tpl" );
            $error = true;
        }
		//if ( $Password == "dummy" or $VerifyPassword == "dummy" )
		//{
			// $t->parse( "error_password_not_entered", "error_password_not_entered_tpl" );
         //   $error = true;
		//}
    }
    if ( $addressCheck )
    {
        if( !isset( $AddressID ) )
            $AddressID = array();

        for( $i=0; $i < count ( $AddressID ); $i++ )
        {
            if ( $ini->read_var( "eZUserMain", "RequireAddress" ) == "enabled" )
            {
                if ( count( $AddressID ) == 0 )
                {
                    $t->parse( "error_missing_address", "error_missing_address_tpl" );
                    $error = true;
                }
            }

            if ( $street1Check )
            {
                if ( $Street1[$i] == "" )
                {
                    $t->parse( "error_address_street1", "error_address_street1_tpl" );
                    $error = true;
                }
            }

            if ( $street2Check )
            {
                if ( $Street2[$i] == "" )
                {
                    $t->parse( "error_address_street2", "error_address_street2_tpl" );
                    $error = true;
                }
            }

            if ( $zipCheck )
            {
                if ( $Zip[$i] == "" )
                {
                    $t->parse( "error_address_zip", "error_address_zip_tpl" );
                    $error = true;
                }
            }

            if ( $placeCheck )
            {
                if ( $Place[$i] == "" )
                {
                    $t->parse( "error_address_place", "error_address_place_tpl" );
                    $error = true;
                }
            }
        }
    }

    if( $error == true )
    {
        $t->parse( "errors_item", "errors_item_tpl" );
    }
}
else
{
    $Password = false;
    $VerifyPassword = false;
}

// Add a new address
if ( isset( $NewAddress ) )
{
    if ( !isset( $AddressID ) )
        $AddressID = array();

    if ( !isset( $CountryID ) )
        $CountryID = array();

    if ( count( $AddressID ) > 0 )
        $AddressID[] = $AddressID[count( $AddressID ) - 1] + 1;
    else
        $AddressID = array( 1 );
    $Street1[] = "";
    $Street2[] = "";
    $Zip[] = "";
    $Place[] = "";
    $country_id = $ini->read_var( "eZUserMain", "DefaultCountry" );
    if ( count( $CountryID ) > 0 and is_numeric( $CountryID[count( $CountryID ) - 1] ) )
        $CountryID[] = $CountryID[count( $CountryID ) - 1];
    else
        $CountryID[] = $country_id;

    $region_id = $ini->read_var( "eZUserMain", "DefaultRegion" );

    if ( count( $RegionID ) > 0 and is_numeric( $RegionID[count($RegionID)-1] ) )
        $RegionID[] = $RegionID[count($RegionID)-1];
    else
        $RegionID[] = $region_id;
}

// Insert a user with address
if ( ( isset( $OK ) or isset( $OK_x ) ) and $error == false )
{
    $new_user = false;
    if ( !is_a( $user, "eZUser" ) )
        $new_user = true;

    if ( $new_user )
    {
        $user_insert = new eZUser();
        $user_insert->setLogin( $Login );
    }
    else
    {
        $user_insert = $user;
    }

    if ( $new_user or $Password != "dummy" )
        $user_insert->setPassword( $Password );

    $user_insert->setEmail( $Email );
    $user_insert->setFirstName( $FirstName );
    $user_insert->setLastName( $LastName );
    $user_insert->setSignature( $Signature );

    if ( $InfoSubscription == "on" )
        $user_insert->setInfoSubscription( true );
    else
        $user_insert->setInfoSubscription( false );

    if ( $AutoCookieLogin == "on" )
        $user_insert->setCookieLogin( true );
    else
        $user_insert->setCookieLogin( false );

    $user_insert->store();

    // Does only need to add the user to the anonymous group if
    // it is a new user
    if ( $new_user )
    {
        setType( $AnonymousUserGroup, "integer" );
        $group = new eZUserGroup( $AnonymousUserGroup );
        $group->addUser( $user_insert );
        $user_insert->setGroupDefinition( $group );
    }

    if ( !isset( $MainAddressID ) )
    {
        $mainAddress = eZAddress::mainAddress( $user );

        if ( $mainAddress )
            $MainAddressID = $mainAddress->id();
    }

    if ( !isset( $MainAddressID ) && count( $AddressID ) > 0 )
        $MainAddressID = $AddressID[0];

//    if ( !$new_user )
//        $user_insert->removeAddresses();

    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $realAddressID = $RealAddressID[$i];

        $address = new eZAddress();
        if ( !$address->get( $realAddressID ) )
        {
            $address = new eZAddress();
        }

        $address->setStreet1( $Street1[$i] );
        $address->setStreet2( $Street2[$i] );
        $address->setZip( $Zip[$i] );
        $address->setPlace( $Place[$i] );

        if ( $SelectCountry == "enabled" and isset( $CountryID[$i] ) )
        {
            $address->setCountry( $CountryID[$i] );
        }
        else
        {
            $CountryID = $ini->read_var( "eZUserMain", "DefaultCountry" );
            $address->setCountry( $CountryID );
        }

        if ( $SelectRegion == "enabled" and isset( $RegionID[$i] ) )
        {
            $address->setRegion( $RegionID[$i] );
        }
        else
        {
            $RegionID = $ini->read_var( "eZUserMain", "DefaultRegion" );
            $address->setRegion( $RegionID );
        }

        $address->store();

        // set correct ID
        if ( !is_numeric( $realAddressID ) )
            $RealAddressID[$i] = $address->id();

        if ( $MainAddressID == $RealAddressID[$i] )
            $main_id = $MainAddressID;

        if ( count ( $AddressID ) == 1 )
            $main_id = $address->id();

        // add address if new
        if ( !is_numeric( $realAddressID ) )
            $user_insert->addAddress( $address );
    }

    if ( count( $AddressID ) > 0 )
        eZAddress::setMainAddress( $main_id, $user_insert );

    $user_insert->loginUser( $user_insert );

    if ( $user_insert->cookieLogin() == true )
    {
        $user_insert->setCookieValues();
    }

    if ( !$new_user )
        $Updated = true;

	// if( $RedirectURL == "" )
		// $RedirectURL = $REQUEST_URI;
    if( $RedirectURL == "" )
    {
        $RedirectURL = $session->variable( "RedirectURL" );
    }
    if ( isset( $RedirectURL )  && ( $RedirectURL != "" ) )
    {
        $session->setVariable( "RedirectURL", "$RedirectURL" );
        eZHTTPTool::header( "Location: $RedirectURL" );
        exit();
    }
    if ( !is_a( $user, "eZUser" ) )
    {
        eZHTTPTool::header( "Location: /" );
        exit();
    }
}

$info_array = array();
$t->set_var( "info_item", "" );
if ( isset( $Updated ) )
{
    $info_array[] = "info_updated";
}

if ( count( $info_array ) > 0 )
{
    foreach( $info_array as $info )
    {
        $t->parse( $info, $info . "_tpl" );
    }
    $t->parse( "info_item", "info_item_tpl" );
}

$t->set_var( "readonly", "" );
$cookieCheck = "";

if ( is_a( $user_insert, "eZUser" ) )
    $user = $user_insert;

// Fill in variables which are not set for current user,
// this is done the first the page loads
if ( is_a( $user, "eZUser" ) )
{
    if ( !isset( $UserID ) )
        $UserID = $user->id();
    if ( !isset( $Login ) )
        $Login = $user->Login();
    if ( !isset( $Email ) )
        $Email = $user->Email();
    if ( !isset( $FirstName ) )
        $FirstName = $user->FirstName();
    if ( !isset( $LastName ) )
         $LastName = $user->LastName();

    $cookieCheck = "";
    if ( $user->cookieLogin() == true )
    {
        $cookieCheck = "checked";
    }
    else
    {
    }

    if ( !isset( $AddressID ) )
    {
        if ( !isset( $AddressID ) )
            $AddressID = array();
        if ( !isset( $Street1 ) )
            $Street1 = array();
        if ( !isset( $Street2 ) )
            $Street2 = array();
        if ( !isset( $Zip ) )
            $Zip = array();
        if ( !isset( $Place ) )
            $Place = array();
        if ( !isset( $CountryID ) )
            $CountryID = array();

        if ( !isset( $RegionID ) )
            $RegionID = array();
        $mainAddress = eZAddress::mainAddress( $user );

        $addressArray = $user->addresses();

        $i = 0;
        foreach ( $addressArray as $address )
        {
            if ( ( is_a( $mainAddress, "eZAddress" ) ) and ( $address->id() == $mainAddress->id()  ) and !isset( $MainAddressID ) )
            {
//                $MainAddressID = $i + 1;
                $MainAddressID = $address->id();
            }
            if ( !isset( $AddressID[$i] ) )
                $AddressID[$i] = $i + 1;
            if ( !isset( $RealAddressID[$i] ) )
                $RealAddressID[$i] = $address->id();
            if ( !isset( $Street1[$i] ) )
                $Street1[$i] = $address->street1();
            if ( !isset( $Street2[$i] ) )
                $Street2[$i] = $address->street2();
            if ( !isset( $Zip[$i] ) )
                $Zip[$i] = $address->zip();
            if ( !isset( $Place[$i] ) )
                $Place[$i] = $address->place();
            if ( !isset( $CountryID[$i] ) )
            {
                $country = $address->country();
                if ( $country )
                {
                    $CountryID[$i] = $country->id();
                }
            }

            if ( !isset( $RegionID[$i] ) )
            {
                $region = $address->region();
                if ( $region )
                {    // region object does not return region objects just id, feature addition, 
		     // but only cause i hacked it for testing. badd comment!.
                     $RegionID[$i] = $region->id();
		     // $RegionID[$i] = $region;
                }
            }
            ++$i;
        }
    }

    $t->set_var( "first_name_value", $FirstName );
    $t->set_var( "last_name_value", $LastName );
    $t->set_var( "login_value", $Login );
    $t->set_var( "email_value", $Email );
    $t->set_var( "password_value", $Password );
    $t->set_var( "verify_password_value", $VerifyPassword );
    $t->set_var( "address_actions", "" );
}
else
{
    $UserID = false;
    $LastName = false;
    $FirstName = false;
    $Login = false;
    $Email = false;
    if ( $ini->read_var( "eZUserMain", "RequireFirstAddress" ) == "enabled" )
    {
        if ( !isset( $AddressID ) )
            $AddressID = array( 1 );
        if ( !isset( $Street1 ) )
            $Street1 = array( "" );
        if ( !isset( $Street2 ) )
            $Street2 = array( "" );
        if ( !isset( $Zip ) )
            $Zip = array( "" );
        if ( !isset( $Place ) )
            $Place = array( "" );
        if ( !isset( $CountryID ) )
            $CountryID = array( $ini->read_var( "eZUserMain", "DefaultCountry" ) );
        if ( !isset( $RegionID ) )
            $RegionID = array( $ini->read_var( "eZUserMain", "DefaultRegion" ) );
        if ( !isset( $MainAddressID ) )
        {
            $MainAddressID = 1;
        }
    }
    else
    {
        if ( !isset( $AddressID ) )
            $AddressID = array();
    }
}

if ( !isset( $DeleteAddressArrayID ) )
    $DeleteAddressArrayID = array();

$t->set_var( "login_value", $Login );
$t->set_var( "disabled_login_item", "" );
$t->set_var( "login_item", "" );
if ( is_a( $user, "eZUser" ) )
{
    $t->parse( "disabled_login_item", "disabled_login_item_tpl" );
}
else
{
    $t->parse( "login_item", "login_item_tpl" );
}

if ( is_a( $user, "eZUser" ) and $Password == "" )
    $Password = "dummy";
if ( is_a( $user, "eZUser" ) and $VerifyPassword == "" )
    $VerifyPassword = "dummy";
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );
$t->set_var( "is_cookie_selected", "$cookieCheck" );

if( ( is_a( $user, "eZUser" ) ) and $user->infoSubscription() == true )
    $InfoSubscription = "checked";
else
    $InfoSubscription = "";

$t->set_var( "info_subscription", $InfoSubscription );

if ( is_a( $user, "eZUser" ) )
    $t->set_var( "readonly", "disabled" );

$t->set_var( "address", "" );

if ( $SelectCountry == "enabled" )
    $countryList =& eZCountry::getAllArray();

if ( $SelectRegion == "enabled" )
    $regionList =& eZRegion::getAllArray();

// Make sure the MainAddressID is set to something sensible
$deleted = false;
for ( $i = 0; $i < count( $AddressID ); ++$i )
{
    $address_id = $AddressID[$i];
    $variable = "DeleteAddressButton$address_id";
    if ( in_array( $AddressID[$i], $DeleteAddressArrayID ) or isset( $$variable ) )
    {
        if ( $RealAddressID[$i] == $MainAddressID )
        {
            $deleted = true;
        }
    }
}

if ( $deleted )
{
    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $variable = "DeleteAddressButton$address_id";
        if ( !in_array( $AddressID[$i], $DeleteAddressArrayID ) and !isset( $$variable ) )
        {
            $MainAddressID = $RealAddressID[$i];
            break;
        }
    }
}

// Check if we will add delete buttons
$checkArray = array_diff( $AddressID, $DeleteAddressArrayID );
if ( count ( $checkArray ) == 1 )
{
    $t->set_var( "delete_address", "" );
	$t->set_var( "main_address", "" );
}
else
{
    $t->parse( "main_address", "main_address_tpl" );
    $t->parse( "delete_address", "delete_address_tpl" );
}


if ( count( $DeleteAddressArrayID ) )
{
    // delete addresses
    foreach ( $DeleteAddressArrayID as $aid )
    {
        $delete_address = new eZAddress( $RealAddressID[$aid-1] );
        if ( $user )
            $user->removeAddress( $delete_address );
    }
}

// Render addresses
if ( $ini->read_var( "eZUserMain", "UserWithAddress" ) == "enabled" )
{
    for ( $i = 0; $i < count( $AddressID ); ++$i )
    {
        $address_id = $AddressID[$i];
        $variable = "DeleteAddressButton$address_id";
        if ( !in_array( $AddressID[$i], $DeleteAddressArrayID ) and !isset( $$variable ) )
        {
            if ( isset( $MainAddressID ) && is_numeric( $MainAddressID ) )
            {
                $t->set_var( "is_checked", $RealAddressID[$i] == $MainAddressID ? "checked" : "" );
            }

            if ( count ( $checkArray ) == 1 )
            {
                $t->set_var( "delete_address", "" );
                $t->set_var( "main_address", "" );
            }
            else
            {
                $t->set_var( "address_id", $RealAddressID[$i] );
                $t->parse( "main_address", "main_address_tpl" );
                $t->set_var( "address_id", $AddressID[$i] );
                $t->parse( "delete_address", "delete_address_tpl" );
            }
            $t->set_var( "address_id", $AddressID[$i] );

            $t->set_var( "real_address_id", $RealAddressID[$i] );

            $t->set_var( "street1_value", $Street1[$i] );
            $t->set_var( "street2_value", $Street2[$i] );


            $t->set_var( "zip_value", $Zip[$i] );
            $t->set_var( "place_value", $Place[$i] );

            $t->set_var( "country", "" );
            if ( $SelectCountry == "enabled" )
            {
                $t->set_var( "country_option", "" );
                foreach ( $countryList as $country )
                {
                    $t->set_var( "is_selected", $country["ID"] == $CountryID[$i] ? "selected" : "" );

                    $t->set_var( "country_id", $country["ID"] );
                    $t->set_var( "country_name", $country["Name"] );
                    $t->parse( "country_option", "country_option_tpl", true );
                }
                $t->parse( "country", "country_tpl" );
            }

            $t->set_var( "region", "" );
            if ( $SelectRegion == "enabled" )
            {
                    $t->set_var( "region_option", "" );
                    $t->set_var( "specialFormId",$i);
                    foreach ( $regionList as $region )
                    {
                        $t->set_var( "is_selected", $region["ID"] == $RegionID[$i] ? "selected" : "" );
                        $t->set_var( "region_id", $region["ID"] );
                        $t->set_var( "region_name", $region["Name"] );
                        $t->parse( "region_option", "region_option_tpl", true );
                    }
                    $t->parse( "region", "region_tpl" );
            }
            $t->set_var( "address_number", $i + 1 );

            $t->parse( "address", "address_tpl", true );
        }
    }
    $t->parse( "address_actions", "address_actions_tpl" );
}


$t->set_var( "global_section_id", $GlobalSectionID );

$t->set_var( "user_id", $UserID );

if( isset( $RedirectURL ) )
{
    $t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );
}
else
{
    $t->set_var( "redirect_url", '' );
}

$t->pparse( "output", "user_edit_tpl" );

?>