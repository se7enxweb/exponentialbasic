<?php
// 
// $Id: addressedit.php 9518 2002-05-08 11:51:36Z vl $
//
// Created on: <06-Nov-2000 16:55:56 bf>
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

require( "kernel/ezuser/user/usercheck.php" );

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/ezhttptool.php" );
// include_once( "classes/eztexttool.php" );


$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZUserMain", "Language" );
$SelectCountry = $ini->variable( "eZUserMain", "SelectCountry" );
$AnonymousUserGroup = $ini->variable( "eZUserMain", "AnonymousUserGroup" );

// include_once( "ezuser/classes/ezuser.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezaddress/classes/ezcountry.php" );

if ( $Action == "Insert" )
{
    // check for valid data
    if ( 
    $Street1 != "" &&
    $Zip != "" &&
    $Place != "" )
    {
        $user =& eZUser::currentUser();

        if ( $user )
        {
            $address = new eZAddress();
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );
            $address->setPlace( $Place );
            $address->setRegion( $Region );
            
            if ( isset( $CountryID ) )
            {
                $country = new eZCountry( $CountryID );
                $address->setCountry( $country );
            }
            
            $address->store();
            
            // add the address to the user.
            $user->addAddress( $address );

            eZPBLog::writeNotice( "Anonyous user added address:  (" . $user->login() . ") from IP: $REMOTE_ADDR" );                    
            
            if ( ( isset( $RedirectURL ) ) && ( $RedirectURL != "" ) )
            {
                eZHTTPTool::header( "Location: $RedirectURL" );
                exit();
            }
            
            eZHTTPTool::header( "Location: /" );
            exit();
        }
        else
        {
            $UserExistsError = true;
        }
    }
    else
    {
        $Error = true;
    }
}


if ( $Action == "Update" )
{
    // check for valid data
    if ( $Login != "" &&
    $Email != "" &&
    $FirstName != "" &&
    $LastName != "" )
        {
            $user = new eZUser();
            $user->get( $UserID );

            if ( $Password )
            {
                if ( ( $Password == $VerifyPassword ) && ( strlen( $VerifyPassword ) > 2 ) )
                {
                    $user->setPassword( $Password );
                }
                else
                {
                    $PasswordError = true;
                }
            }
    
            $user->setEmail( $Email );
            $user->setFirstName( $FirstName );
            $user->setLastName( $LastName );

            $address = new eZAddress();
            $address->get( $AddressID );
            $address->setStreet1( $Street1 );
            $address->setStreet2( $Street2 );
            $address->setZip( $Zip );
            $address->setPlace( $Place );
            $address->setRegion( $Region );
            if ( isset( $CountryID ) )
            {
                $country = new eZCountry( $CountryID );
                $address->setCountry( $country );
            }
                
            $address->store();
            
            if ( !$PasswordError )
                $user->store();


            if ( isset( $RedirectURL )  && ( $RedirectURL != "" ) )
            {
                eZHTTPTool::header( "Location: $RedirectURL" );
                exit();
            }
            eZHTTPTool::header( "Location: /" );
            exit();
        }
    else
    {
        $Error = true;
    }
}



$t = new eZTemplate( "kernel/ezuser/user/" . $ini->variable( "eZTradeMain", "TemplateDir" ),
                     "kernel/ezuser/user/intl/", $Language, "addressedit.php" );

$t->setAllStrings();

$t->set_file( array(        
    "user_edit_tpl" => "addressedit.tpl"
    ) );

$t->set_var( "readonly", "" );
$action_value = "insert";

if ( $Action == "Update" )
    $action_value = "update";

if ( $Action == "Edit" )
{
    $user =& eZUser::currentUser();
    if ( !$user )
        eZHTTPTool::header( "Location: /" );
    $UserID = $user->id();
    $user->get( $user->id() );
    $Login = $user->Login( );
    $Email = $user->Email(  );
    $FirstName = $user->FirstName(  );
    $LastName = $user->LastName(  );

    $t->set_var( "readonly", "readonly" );
    

// print out the addresses
    // Dosent work with multiplie addresses.

    $addressArray = $user->addresses();

    foreach ( $addressArray as $address )
    {
        $Street1 =  $address->street1();
        $Street2 = $address->street2();
        $Zip = $address->zip();
        $Place = $address->place();
        $Region = $address->region();

       $t->set_var( "address_id", $address->id() );

//        $country = $address->country();
//        $t->set_var( "country", $country->name() );
    }

    $action_value = "update";

}


$t->set_block( "user_edit_tpl", "required_fields_error_tpl", "required_fields_error" );
$t->set_block( "user_edit_tpl", "user_exists_error_tpl", "user_exists_error" );
$t->set_block( "user_edit_tpl", "password_error_tpl", "password_error" );

$t->set_block( "user_edit_tpl", "country_tpl", "country" );
$t->set_block( "country_tpl", "country_option_tpl", "country_option" );

if ( $Error == true )
{
    $t->parse( "required_fields_error", "required_fields_error_tpl" );
}
else
{
   $t->set_var( "required_fields_error", "" );
}

if ( $UserExistsError == true )
{
    $t->parse( "user_exists_error", "user_exists_error_tpl" );
}
else
{
   $t->set_var( "user_exists_error", "" );
}

if ( $PasswordError == true )
{
    $t->parse( "password_error", "password_error_tpl" );
}
else
{
   $t->set_var( "password_error", "" );
}

$t->set_var( "login_value", $Login );
$t->set_var( "password_value", $Password );
$t->set_var( "verify_password_value", $VerifyPassword );
$t->set_var( "email_value", $Email );

$t->set_var( "first_name_value", $FirstName );
$t->set_var( "last_name_value", $LastName );

$t->set_var( "street1_value", $Street1 );
$t->set_var( "street2_value", $Street2 );

$t->set_var( "zip_value", $Zip );

$t->set_var( "place_value", $Place );

$t->set_var( "region_value", $Region );

if ( $SelectCountry == "enabled" )
{
    $ezcountry = new eZCountry();
    $countryList =& $ezcountry->getAllArray();
    
    foreach ( $countryList as $country )
    {
        if ( $Action == "Edit" )
        {
            $countryID = $address->country();
            
            if ( $country["ID"] == $countryID->id() )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
                $t->set_var( "is_selected", "" );
        }
        
        $t->set_var( "country_id", $country["ID"] );
        $t->set_var( "country_name", $country["Name"] );
        $t->parse( "country_option", "country_option_tpl", true );
    }
    $t->parse( "country", "country_tpl" );
}
else
{
    $t->set_var( "country", "" );
}
    


$t->set_var( "action_value", $action_value );
$t->set_var( "user_id", $UserID );

$t->set_var( "redirect_url", eZTextTool::htmlspecialchars( $RedirectURL ) );

$t->pparse( "output", "user_edit_tpl" );

?>