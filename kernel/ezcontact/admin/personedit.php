<?php
//
// $Id: personedit.php 9829 2003-06-03 05:56:24Z jhe $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

/*
  Edit a person
*/

// include_once( "classes/INIFile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZContactMain", "Language" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlog.php" );
// include_once( "classes/eztexttool.php" );

// include_once( "ezaddress/classes/ezcountry.php" );
// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezcontact/classes/ezprojecttype.php" );
// include_once( "ezmail/classes/ezmail.php" );
// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

// deletes the dayview cache file for a given day
function deleteCache( $SiteDesign )
{
    unlinkWild( "./kernel/ezcalendar/user/cache/", "monthview.tpl-$SiteDesign-*" );
}

function unlinkWild( $dir, $rege )
{
    $d = eZPBFile::dir( $dir );
    while ( $f = $d->read() )
    {
        if ( preg_match( $rege, $f ) )
        {
            eZPBFile::unlink( $dir . $f );
        }
    }
}

$user =& eZUser::currentUser();

if ( isset( $CompanyEdit ) && $CompanyEdit )
{
    $item_type = "company";
    $item_id = $CompanyID;
}
else
{
    $item_type = "person";
    $item_id = $PersonID;
}

if ( !is_a( $user, "eZUser" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( isset( $BuyButton ) )
{
    include( "kernel/ezcontact/admin/buy.php" );
}

if ( isset( $OK ) )
{
    if ( $CompanyEdit )
    {
        if ( isset( $Action ) && $Action == "edit" || isset( $Action ) && $Action == "update" )
        {
            if ( !eZPermission::checkPermission( $user, "eZContact", "CompanyModify" ) )
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/nopermission/company/edit" );
                exit();
            }
        }
        else if ( isset( $Action ) && $Action == "new" || isset( $Action ) && $Action == "insert" )
        {
            if ( !eZPermission::checkPermission( $user, "eZContact", "CompanyAdd" ) )
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/nopermission/company/new" );
                exit();
            }
        }
    }
    else
    {
        if ( isset( $Action ) && $Action == "edit" || isset( $Action ) && $Action == "update" )
        {
            if ( !eZPermission::checkPermission( $user, "eZContact", "PersonModify" ) )
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/nopermission/person/edit" );
                exit();
            }
        }
        else if ( isset( $Action ) && $Action == "new" || isset( $Action ) && $Action == "insert" )
        {
            if ( !eZPermission::checkPermission( $user, "eZContact", "PersonAdd" ) )
            {
                // include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/nopermission/person/new" );
                exit();
            }
        }
    }
}

if ( isset( $ListConsultation ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/consultation/$item_type/list/$item_id" );
    exit;
}

if ( isset( $NewConsultation ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/consultation/$item_type/new/$item_id" );
    exit;
}

if ( isset( $FileButton ) )
{
    include( "kernel/ezcontact/admin/folder.php" );
}

if ( isset( $Back ) )
{
    if ( $CompanyEdit )
    {
        $company = new eZCompany( $CompanyID );
        $categories = $company->categories( false, false );
        $id = $categories[0];
    }
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/$item_type/list/$id" );
    exit;
}

if ( isset( $Delete ) )
{
    $Action = "delete";
}

if ( isset( $Action ) && $Action == "delete" )
{
    if ( $CompanyEdit )
    {
        if ( !eZPermission::checkPermission( $user, "eZContact", "CompanyDelete" ) )
        {
            // include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /contact/nopermission/company/delete" );
            exit();
        }
    }
    else
    {
        if ( !eZPermission::checkPermission( $user, "eZContact", "PersonDelete" ) )
        {
            // include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /contact/nopermission/person/delete" );
            exit();
        }
    }

    if ( $CompanyEdit )
    {
        $categories =& (new eZCompany())->categories( $CompanyID, false, 1 );
        $id =& $categories[0];
        $item_type = "company";
        foreach ( $ContactArrayID as $contactItem )
        {
            eZCompany::delete( $contactItem );
        }
    }
    else
    {
        $item_type = "person";
        foreach ( $ContactArrayID as $contactItem )
        {
            eZPerson::delete( $contactItem );
        }
    }

    deleteCache( "default" );
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/$item_type/list/$id" );
    exit;
}

if ( isset( $OK ) )
{
    if ( $Action == "new" )
        $Action = "insert";
    else if ( $Action == "edit" )
        $Action = "update";
}

$error = false;

if ( $CompanyEdit )
{
    $template_file = "companyedit.tpl";
    $language_file = "companyedit.php";
}
else
{
    $template_file = "personedit.tpl";
    $language_file = "personedit.php";
}

$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->variable( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl", $Language, $language_file );
$t->setAllStrings();

$t->set_file( "person_edit", $template_file );

$t->set_block( "person_edit", "edit_tpl", "edit_item" );
$t->set_block( "person_edit", "confirm_tpl", "confirm_item" );
$t->set_block( "person_edit", "image_item_tpl", "image_item" );

if ( $CompanyEdit )
{
    $t->set_block( "edit_tpl", "company_item_tpl", "company_item" );
    $t->set_block( "company_item_tpl", "company_type_select_tpl", "company_type_select" );
    $t->set_block( "edit_tpl", "logo_item_tpl", "logo_item" );
    $t->set_block( "edit_tpl", "image_item_tpl", "image_item" );
}
else
{
    $t->set_block( "edit_tpl", "person_item_tpl", "person_item" );
    $t->set_block( "person_item_tpl", "day_item_tpl", "day_item" );
    $t->set_block( "person_item_tpl", "company_select_tpl", "company_select" );
}

$t->set_block( "edit_tpl", "address_table_item_tpl", "address_table_item" );
$t->set_block( "address_table_item_tpl", "address_item_tpl", "address_item" );
$t->set_block( "address_item_tpl", "address_item_select_tpl", "address_item_select" );

$t->set_block( "address_item_tpl", "country_item_select_tpl", "country_item_select" );

$t->set_block( "edit_tpl", "phone_table_item_tpl", "phone_table_item" );
$t->set_block( "phone_table_item_tpl", "phone_item_tpl", "phone_item" );
$t->set_block( "phone_item_tpl", "phone_item_select_tpl", "phone_item_select" );

$t->set_block( "edit_tpl", "online_table_item_tpl", "online_table_item" );
$t->set_block( "online_table_item_tpl", "online_item_tpl", "online_item" );
$t->set_block( "online_item_tpl", "online_item_select_tpl", "online_item_select" );

$t->set_block( "edit_tpl", "project_item_tpl", "project_item" );
$t->set_block( "project_item_tpl", "project_contact_item_tpl", "project_contact_item" );
$t->set_block( "project_contact_item_tpl", "contact_group_item_select_tpl", "contact_group_item_select" );
$t->set_block( "project_contact_item_tpl", "contact_item_select_tpl", "contact_item_select" );
$t->set_block( "project_item_tpl", "project_item_select_tpl", "project_item_select" );

$t->set_block( "person_edit", "delete_item_tpl", "delete_item" );

$t->set_block( "edit_tpl", "errors_tpl", "errors_item" );

if ( $CompanyEdit )
{
    $t->set_block( "errors_tpl", "error_name_item_tpl", "error_name_item" );
}
else
{
    $t->set_block( "errors_tpl", "error_firstname_item_tpl", "error_firstname_item" );
    $t->set_block( "errors_tpl", "error_lastname_item_tpl", "error_lastname_item" );
    $t->set_block( "errors_tpl", "error_birthdate_item_tpl", "error_birthdate_item" );
}

$t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );
$t->set_block( "errors_tpl", "error_phone_item_tpl", "error_phone_item" );
$t->set_block( "errors_tpl", "error_online_item_tpl", "error_online_item" );
$t->set_block( "errors_tpl", "error_logo_item_tpl", "error_logo_item" );
$t->set_block( "errors_tpl", "error_image_item_tpl", "error_image_item" );

$confirm = false;

if( isset( $Action ) && $Action == "new" )
{
    if( !isset( $BirthDay ) )
        $BirthDay = false;
    if( !isset( $BirthYear ) )
        $BirthYear = false;
    if( !isset( $BirthMonth ) )
        $BirthMonth = false;
    if( !isset( $Comment ) )
        $Comment = false;

    $t->set_var( "image_caption", '' );
}

if ( isset( $Action ) && $Action == "delete" )
{
    if ( !isset( $Confirm ) )
    {
        $confirm = true;

        if ( $CompanyEdit )
        {
            $t->set_var( "company_id", $CompanyID );
            $company = new eZCompany( $CompanyID );
            $t->set_var( "name", $company->name() );
        }
        else
        {
            $t->set_var( "person_id", $PersonID );
            $person = new eZPerson( $PersonID );
            $t->set_var( "firstname", $person->firstName() );
            $t->set_var( "lastname", $person->lastName() );
        }
        $t->set_var( "edit_item", "" );
        $t->set_var( "action_value", $Action );
        $t->set_var( "delete_item", "" );
        $t->parse( "confirm_item", "confirm_tpl" );
    }
}

if ( !$confirm )
{
    $t->set_var( "confirm_item", "" );

    if ( $CompanyEdit )
    {
        $t->set_var( "name", "" );
        $t->set_var( "companyno", "" );
    }
    else
    {
        $t->set_var( "firstname", "" );
        $t->set_var( "lastname", "" );
        $t->set_var( "birthdate", "" );
        $t->set_var( "comment", "" );
        $t->set_var( "person_id", "" );
    }

    $t->set_var( "user_id", isset( $UserID ) ? $UserID : false );

    $t->set_var( "contact_group_item_select", "" );
    $t->set_var( "contact_item_select", "" );

/* End of the pre-defined values */
    if ( ( isset( $Action ) && $Action == "insert" || isset( $Action ) && $Action == "update" ) )
    {
        if ( $Action == "update" )
        {
            deleteCache( "default" );
        }

        if ( $CompanyEdit )
        {
            $t->set_var( "error_name_item", "" );
        }
        else
        {
            $t->set_var( "error_firstname_item", "" );
            $t->set_var( "error_lastname_item", "" );
            $t->set_var( "error_birthdate_item", "" );
        }

        $t->set_var( "error_address_item", "" );
        $t->set_var( "error_phone_item", "" );
        $t->set_var( "error_online_item", "" );
        $t->set_var( "error_logo_item", "" );
        $t->set_var( "error_image_item", "" );

        if ( $CompanyEdit )
        {
            if ( $Name == "" )
            {
                $t->parse( "error_name_item", "error_name_item_tpl" );
                $error = true;
            }
        }
        else
        {
            if ( $FirstName == "" )
            {
                $t->parse( "error_firstname_item", "error_firstname_item_tpl" );
                $error = true;
            }

            if ( $LastName == "" )
            {
                $t->parse( "error_lastname_item", "error_lastname_item_tpl" );
                $error = true;
            }

            if ( $BirthYear != "" )
            {
                $birth = new eZDate( $BirthYear, $BirthMonth, $BirthDay );
                if ( !$birth->isValid() )
                {
                    $t->parse( "error_birthdate_item", "error_birthdate_item_tpl" );
                    $error = true;
                }
            }
        }

        if( !isset( $AddressID ) )
            $AddressID = array();

        $count = max( count( $AddressTypeID ), count( $AddressID ),
                      count( $Street1 ), count( $Street2 ),
                      count( $Zip ), count( $Place ), 1 );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $AddressTypeID[$i] != -1 )
            {
                if ( $Street1[$i] == "" || $Place[$i] == "" || $Country[$i] == "" )
                {
                    $t->set_var( "error_address_position", $i + 1 );
                    $t->parse( "error_address_item", "error_address_item_tpl", true );
                    $error = true;
                }
            }
            else
            {
                if ( $Street1[$i] != "" || $Street2[$i] != "" || $Place[$i] != "" ||
                   ( $Country[$i] != -1 and $Country[$i] != "" ) )
                {
                    $t->set_var( "error_address_position", $i + 1 );
                    $t->parse( "error_address_item", "error_address_item_tpl", true );
                    $error = true;
                }
            }
        }

        $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $PhoneTypeID[$i] != -1 )
            {
                if ( $Phone[$i] == "" )
                {
                    $t->set_var( "error_phone_position", $i + 1 );
                    $t->parse( "error_phone_item", "error_phone_item_tpl", true );
                    $error = true;
                }
            }
            else
            {
                if ( $Phone[$i] != "" )
                {
                    $t->set_var( "error_phone_position", $i + 1 );
                    $t->parse( "error_phone_item", "error_phone_item_tpl", true );
                    $error = true;
                }
            }
        }

        $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $OnlineTypeID[$i] != -1 )
            {
                if ( $Online[$i] == "" )
                {
                    $t->set_var( "error_online_position", $i + 1 );
                    $t->parse( "error_online_item", "error_online_item_tpl", true );
                    $error = true;
                }
            }
            else
            {
                if ( $Online[$i] != "" )
                {
                    $t->set_var( "error_online_position", $i + 1 );
                    $t->parse( "error_online_item", "error_online_item_tpl", true );
                    $error = true;
                }
            }
        }

        // Check uploaded logo image
        $file = new eZImageFile();
        if ( $file->getUploadedFile( "logo" ) )
        {
            $logo = new eZImage();
            if ( !$logo->checkImage( $file ) )
            {
                $t->parse( "error_logo_item", "error_logo_item_tpl", true );
                $error = true;
            }
        }

        // Check uploaded image
        $file = new eZImageFile();
        if ( $file->getUploadedFile( "image" ) )
        {
            $image = new eZImage();
            if ( !$image->checkImage( $file ) )
            {
                $t->parse( "error_image_item", "error_image_item_tpl", true );
                $error = true;
            }
        }

        if ( is_numeric( $CompanyID ) )
        {
            if ( isset( $DeleteImage ) )
            {
                print( "deleteimage $CompanyID" );
                eZCompany::deleteImage( $CompanyID );
            }

            if ( isset( $DeleteLogo ) )
            {
                print( "deletelogo $CompanyID" );
                eZCompany::deleteLogo( $CompanyID );
            }
        }

        if ( $error && isset( $OK ) )
        {
            $t->set_var( "action_value", $Action );
            $t->parse( "errors_item", "errors_tpl" );
        }
    }

    if ( $error == false || isset( $RefreshUsers ) )
    {
        $t->set_var( "errors_item", "" );
    }
    else
    {
        $Action = "formdata";
    }

    if ( ( $Action == "insert" || $Action == "update" ) && !$error && isset( $OK ) )
    {
        if ( $CompanyEdit )
        {
            if ( $Action == "insert" )
                $company = new eZCompany();
            else
                $company = new eZCompany( $CompanyID );

            $company->setName( $Name );

            $company->setCompanyNo( $CompanyNo );
            if ( $ContactPersonType == "ezperson" )
                $company->setPersonContact( $ContactID );
            else
                $company->setContact( $ContactID );
            $company->setComment( $Comment );
            $company->store();

            $item_id = $company->id();
            $CompanyID = $item_id;

            // Update categories
            $company->removeCategories();
            $category = new eZCompanyType();
            if ( count( $CompanyCategoryID ) > 0 )
            {
                for ( $i = 0; $i < count( $CompanyCategoryID ); $i++ )
                {
                    $category->get( $CompanyCategoryID[$i] );
                    $category->addCompany( $company );
                }
            }
            else
            {
                $category->get( 0 );
                $category->addCompany( $company );
            }
            $item_cat_id = $CompanyCategoryID[0];

            // Upload images
            $file = new eZImageFile();
            if ( $file->getUploadedFile( "logo" ) )
            {
                $logo = new eZImage();
                $logo->setName( "Logo" );
                if ( $logo->checkImage( $file ) and $logo->setImage( $file ) )
                {
                    $logo->store();
                    $company->setLogoImage( $logo );
                }
                else
                {
                    $company->deleteLogo();
                }
            }
            else
            {
                print( $file->name() . " not uploaded successfully" );
            }

            // Upload images
            $file = new eZImageFile();
            if ( $file->getUploadedFile( "image" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                if ( $image->checkImage( $file ) and $image->setImage( $file ) )
                {
                    $image->store();
                    $company->setCompanyImage( $image );
                }
                else
                {
                    $company->deleteImage();
                }
            }
            else
            {
                print( $file->name() . " not uploaded successfully" );
            }
            $item =& $company;
        }
        else
        {
            $person = new eZPerson( $PersonID, true );
            $person->setFirstName( $FirstName );
            $person->setLastName( $LastName );

            if ( $BirthYear != "" )
            {
                $Birth = new eZDate( $BirthYear, $BirthMonth, $BirthDay );
                $person->setBirthDay( $Birth->timeStamp() );
            }
            else
            {
                $person->setNoBirthDay();
            }
//              $person->setContact( $ContactID );
            $person->setComment( $Comment );

            if ( $DeleteImage == "on" )
                $person->setImage(0);

            $person->store();

            // Upload images
            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                if ( $image->checkImage( $file ) and $image->setImage( $file ) )
                {
                    $image->store();
                    $person->setImage( $image, $person->id() );
                }
            }
            $person->store();

            $person->removeCompanies();

            if( !isset( $CompanyID ) )
                $CompanyID = array();
            for ( $i = 0; $i < count( $CompanyID ); $i++ )
            {
                (new eZCompany())->addPerson( $person->id(), $CompanyID[$i] );
            }

            $item_id = $person->id();
            $PersonID = $item_id;
            $item_cat_id = "";

            $item =& $person;
            //var_dump($item);
        }

        $item->setProjectState( $ProjectID, $item->id() );

        // address
        $item->removeAddresses();
        $count = max( count( $AddressTypeID ), count( $AddressID ),
                      count( $Street1 ), count( $Street2 ),
                      count( $Zip ), count( $Place ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( $Street1[$i] != "" && $Place[$i] != "" &&
                 $Country[$i] != "" && $AddressTypeID != -1 )
            {
                if ( isset( $AddressDelete ) && !in_array( $i + 1, $AddressDelete ) && $AddressTypeID[$i] != -1 )
                {
                    $address = new eZAddress();
                    $address->setStreet1( $Street1[$i] );
                    $address->setStreet2( $Street2[$i] );
                    $address->setZip( $Zip[$i] );
                    $address->setPlace( $Place[$i] );
                    $address->setAddressType( $AddressTypeID[$i] );
                    $address->setCountry( $Country[$i] );
                    $address->store();

                    $item->addAddress( $address );
                }
            }
        }

        $item->removePhones();
        $count = max( count( $PhoneID ), count( $Phone ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( isset( $PhoneDelete ) && !in_array( $i + 1, $PhoneDelete ) && $Phone[$i] != "" )
            {
                $phone = new eZPhone( false, true );
                $phone->setNumber( $Phone[$i] );
                $phone->setPhoneTypeID( $PhoneTypeID[$i] );
                $phone->store();

                $item->addPhone( $phone );
            }
        }

        $item->removeOnlines();
        $count = max( count( $OnlineID ), count( $Online ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( isset( $OnlineDelete ) && !in_array( $i + 1, $OnlineDelete ) && $Online[$i] != "" )
            {
                $online = new eZOnline( false, true );
                $online->setURL( $Online[$i] );
                $online->setOnlineTypeID( $OnlineTypeID[$i] );
                $online->store();

                $item->addOnline( $online );
            }
        }

        if ( $CompanyEdit )
        {
            $CompanyID = $company->id();
            $item_cat_id = $CompanyID;
        }
        else
        {
            $PersonID = $person->id();
            $item_cat_id = $PersonID;
        }

        $t->set_var( "user_id", $UserID );
        $t->set_var( "person_id", $PersonID );
        $t->set_var( "company_id", $CompanyID );

        // include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/$item_type/view/$item_cat_id" );
    }

/*
    The user wants to edit an existing person.

    We fetch the appropriate variables.
*/

    if ( $Action == "edit" )
    {
        if ( $CompanyEdit )
        {
            $company = new eZCompany( $CompanyID, true );
            $item =& $company;

            $Name = $company->name();
            $Comment = $company->comment();
            $CompanyNo = $company->companyNo();
        }
        else
        {
            $person = new eZPerson( $PersonID, true );
            $item =& $person;

            $FirstName = $person->firstName();
            $LastName = $person->lastName();
            if ( $person->hasBirthDate() )
            {
                $Birth = new eZDate();
                $Birth->setTimeStamp( $person->birthDate() );
                $BirthYear = $Birth->year();
                $BirthMonth = $Birth->month();
                $BirthDay = $Birth->day();
            }
            else
            {
                $BirthYear = "";
                $BirthMonth = 1;
                $BirthDay = 1;
            }
            $Comment = $person->comment();
            $image =& $person->image( $person->id() );
            if ( get_class( $image ) == "ezimage" && $image->id() != 0 )
            {
                $imageWidth =& $ini->variable( "eZContactMain", "PersonImageWidth" );
     	        $imageHeight =& $ini->variable( "eZContactMain", "PersonImageHeight" );
                $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
                $imageURL = "/" . $variation->imagePath();
                $imageWidth = $variation->width();
                $imageHeight = $variation->height();
                $imageCaption = $image->caption();
                $t->set_var( "image_width", $imageWidth );
                $t->set_var( "image_height", $imageHeight );
                $t->set_var( "image_url", $imageURL );
                $t->set_var( "image_caption", $imageCaption );
                $t->parse( "image_item", "image_item_tpl" );
            }
            else
            {
                $t->set_var( "image_caption", '' );
                $t->parse( "image_item", "image_item_tpl" );
            }
        }

        $addresses = $item->addresses();
        $i = 1;
        foreach ( $addresses as $address )
        {
            $AddressTypeID[$i - 1] = $address->addressTypeID();
            $AddressID[$i - 1] = $i;
            $Street1[$i - 1] = $address->street1();
            $Street2[$i - 1] = $address->street2();
            $Zip[$i - 1] = $address->zip();
            $Place[$i - 1] = $address->place();
            $country = $address->country();
            if ( $country )
                $Country[$i - 1] = $country->id();
            else
                $Country[$i - 1] = -1;
            $i++;
        }

        $phones = $item->phones();
        $i = 1;
        foreach ( $phones as $phone )
        {
            $PhoneTypeID[$i - 1] = $phone->phoneTypeID();
            $PhoneID[$i - 1] = $i;
            $Phone[$i - 1] = $phone->number();
            $i++;
        }

        $onlines = $item->onlines();
        $i = 1;
        foreach ( $onlines as $online )
        {
            $OnlineTypeID[$i - 1] = $online->onlineTypeID();
            $OnlineID[$i - 1] = $i;
            $Online[$i - 1] = $online->url();
            $i++;
        }

        $ContactID = $item->contact();
        if ( is_a( $item, "eZCompany" ) )
            $ContactType = $item->contactType();
        else
            $ContactType = "eZUser";
        $ProjectID = $item->projectState();
    }

/*
    The user wants to create a new person/company.

    We present an empty form.
 */
    if ( ( $Action == "new" || $Action == "formdata" || $Action == "edit" || isset( $RefreshUsers ) ) )
    {
        if ( isset( $OK ) )
        {
            if ( $Action == "edit" )
                $Action = "update";
            else if ( $Action == "new" )
                $Action = "insert";
        }

        if ( $CompanyEdit )
        {
            $t->set_var( "company_id", $CompanyID );
            $t->set_var( "user_id", $user->id() );

            if( isset( $Name ) && isset( $CompanyNo ) && isset( $Comment ) )
            {
                $t->set_var( "name", eZTextTool::htmlspecialchars( $Name ) );
                $t->set_var( "comment", eZTextTool::htmlspecialchars( $Comment ) );
                $t->set_var( "companyno", eZTextTool::htmlspecialchars( $CompanyNo ) );
            }
            else
            {
                $t->set_var( "name", '' );
                $t->set_var( "comment", '' );
                $t->set_var( "companyno", '' );
            }

            // Company type selector
            $companyTypeList = eZCompanyType::getTree();
            $categoryList = array();

            if ( $Action != "new" )
            {
                if ( !isset( $CompanyCategoryID ) )
                    $categoryList =& (new eZCompany())->categories( $CompanyID, false );
                else
                    $categoryList = array( $CompanyCategoryID );
            }
            if ( isset( $NewCompanyCategory ) and !is_numeric( $NewCompanyCategory ) )
                $NewCompanyCategory = 0;
            if ( isset( $NewCompanyCategory ) and is_numeric( $NewCompanyCategory ) )
                $categoryList =& array_unique( array_merge( array( $NewCompanyCategory ), $categoryList ) );
            if ( isset( $CompanyCategoryID ) )
                $categoryList =& array_unique( array_merge( array( $CompanyCategoryID ), $categoryList ) );
            if ( isset( $categoryList ) && count( $categoryList ) > 0 )
                $category_values = array_values( $categoryList );
            else
                $category_values = array();

            $t->set_var( "is_top_selected", in_array( 0, $category_values ) ? "selected" : "" );
            foreach ( $companyTypeList as $companyTypeItem )
            {
                $t->set_var( "company_type_name", eZTextTool::htmlspecialchars( $companyTypeItem[0]->name() ) );
                $t->set_var( "company_type_id", $companyTypeItem[0]->id() );

                if ( $companyTypeItem[1] > 0 )
                    $t->set_var( "company_type_level", str_repeat( "&nbsp;", $companyTypeItem[1] ) );
                else
                    $t->set_var( "company_type_level", "" );

                $t->set_var( "is_selected", in_array( $companyTypeItem[0]->id(), $category_values )
                                            ? "selected" : "" );

                $t->parse( "company_type_select", "company_type_select_tpl", true );
            }

            $t->parse( "company_item", "company_item_tpl" );
        }
        else
        {
            $t->set_var( "person_id", $PersonID );

            $t->set_var( "user_id", $user->id() );
            if ( isset( $FirstName ) )
                $t->set_var( "firstname", eZTextTool::htmlspecialchars( $FirstName ) );
            if ( isset( $LastName ) )
                $t->set_var( "lastname", eZTextTool::htmlspecialchars( $LastName ) );

            $top_name = $t->get_var( "intl-top_category" );
            if ( !is_string( $top_name ) )
                $top_name = "";
            $companyTypeList = eZCompanyType::getTree( 0, 0, true, $top_name );
            $categoryList = array();
            $categoryList = eZPerson::companies( $PersonID, false );
            $category_values = array_values( $categoryList );
            $t->set_var( "is_top_selected", in_array( 0, $category_values ) ? "selected" : "" );
            foreach ( $companyTypeList as $companyTypeItem )
            {
                $t->set_var( "company_name", "[" . eZTextTool::htmlspecialchars( $companyTypeItem[0]->name() ) . "]" );
                $t->set_var( "company_id", "-1" );

                $level = $companyTypeItem[1] > 0 ? str_repeat( "&nbsp;", $companyTypeItem[1] ) : "";
                $t->set_var( "company_level", $level );
                $t->set_var( "is_selected", "" );
                $t->parse( "company_select", "company_select_tpl", true );

                $level = str_repeat( "&nbsp;", $companyTypeItem[1] + 1 );
                $t->set_var( "company_level", $level );

                $companies = eZCompany::getByCategory( $companyTypeItem[0]->id() );
                foreach ( $companies as $companyItem )
                {
                    $t->set_var( "company_name", eZTextTool::htmlspecialchars( $companyItem->name() ) );
                    $t->set_var( "company_id", $companyItem->id() );
                    $t->set_var( "is_selected", in_array( $companyItem->id(), $category_values )
                                 ? "selected" : "" );
                    $t->parse( "company_select", "company_select_tpl", true );
                }
            }

            for ( $i = 1; $i <= 31; $i++ )
            {
                $t->set_var( "day_id", $i );
                $t->set_var( "day_value", $i );
                $t->set_var( "selected", "" );
                if ( ( $BirthDay == "" and $i == 1 ) or $BirthDay == $i )
                    $t->set_var( "selected", "selected" );
                $t->parse( "day_item", "day_item_tpl", true );
            }

            $birth_array = array( 1 => "select_january",
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

            foreach ( $birth_array as $month )
            {
                $t->set_var( $month, "" );
            }

            $var_name =& $birth_array[$BirthMonth];
            if ( $var_name == "" )
                $var_name =& $birth_array[1];

            $t->set_var( $var_name, "selected" );
            $t->set_var( "birthyear", $BirthYear );
            $t->set_var( "comment", $Comment );

            $t->parse( "person_item", "person_item_tpl" );
        }

        $phone_types =& eZPhoneType::getAll();
        $online_types =& eZOnlineType::getAll();
        $address_types =& eZAddressType::getAll();
        $countries =& eZCountry::getAllArray();
        if ( !isset( $PhoneDelete ) )
        {
            $PhoneDelete = array();
        }
        if ( !isset( $OnlineDelete ) )
        {
            $OnlineDelete = array();
        }
        if ( !isset( $AddressDelete ) )
        {
            $AddressDelete = array();
        }

        $AddressMinimum = $ini->variable( "eZContactMain", "AddressMinimum" );
        $PhoneMinimum = $ini->variable( "eZContactMain", "PhoneMinimum" );
        $OnlineMinimum = $ini->variable( "eZContactMain", "OnlineMinimum" );
        $AddressWidth = $ini->variable( "eZContactMain", "AddressWidth" );
        $PhoneWidth = $ini->variable( "eZContactMain", "PhoneWidth" );
        $OnlineWidth = $ini->variable( "eZContactMain", "OnlineWidth" );

        if ( isset( $NewAddress ) )
        {
            $AddressTypeID[] = "";
            $AddressID[] = count( $AddressID ) > 0 ? $AddressID[count( $AddressID ) - 1] + 1 : 1;
            $Street1[] = "";
            $Street2[] = "";
            $Zip[] = "";
            $Place[] = "";
            $Country[] = count( $Country ) > 0 ? $Country[count( $Country ) - 1] : "";

            $count = max( count( $AddressTypeID ), count( $AddressID ),
                count( $Street1 ), count( $Street2 ),
                count( $Zip ), count( $Place ) );
        }
        else
        {
            $count = false;
            $Zip = array();
        }
        $item = 0;
        $AddressDeleteValues =& array_values( $AddressDelete );
        $last_id = 0;
        for ( $i = 0; $i < $count || $item < $AddressMinimum; $i++ )
        {
            if ( ( $item % $AddressWidth == 0 ) && $item > 0 )
            {
                $t->parse( "address_table_item", "address_table_item_tpl", true );
                $t->set_var( "address_item" );
            }
            if ( !isset( $AddressID[$i] ) or !is_numeric( $AddressID[$i] ) )
                 $AddressID[$i] = ++$last_id;
            if ( !in_array( $AddressID[$i], $AddressDeleteValues ) )
            {
                $last_id = $AddressID[$i];
                if( isset( $Street1[$i] ) )
                    $t->set_var( "street1", eZTextTool::htmlspecialchars( $Street1[$i] ) );
                else
                    $t->set_var( "street1", '' );

                if( isset( $Street2[$i] ) )
                    $t->set_var( "street2", eZTextTool::htmlspecialchars( $Street2[$i] ) );
                else
                    $t->set_var( "street2", '' );
                if( isset( $Zip[$i] ) )
                    $t->set_var( "zip", eZTextTool::htmlspecialchars( $Zip[$i] ) );
                else
                    $t->set_var( "zip", '' );
                if( isset( $Place[$i] ) )
                    $t->set_var( "place", eZTextTool::htmlspecialchars( $Place[$i] ) );
                else
                    $t->set_var( "place", '' );
                $t->set_var( "address_id", $AddressID[$i] );
                $t->set_var( "address_index", $AddressID[$i] );
                $t->set_var( "address_position", $i + 1 );

                $t->set_var( "address_item_select", "" );

                foreach ( $address_types as $address_type )
                {
                    $t->set_var( "type_id", $address_type->id() );
                    $t->set_var( "type_name", eZTextTool::htmlspecialchars( $address_type->name() ) );
                    $t->set_var( "selected", "" );
                    if ( isset( $AddressTypeID ) && $address_type->id() == $AddressTypeID[$i] )
                        $t->set_var( "selected", "selected" );
                    $t->parse( "address_item_select", "address_item_select_tpl", true );
                }
                $t->set_var( "country_item_select", "" );
                $t->set_var( "no_country_selected", "" );
                foreach ( $countries as $country )
                {
                    $t->set_var( "type_id", $country["ID"] );
                    $t->set_var( "type_name", eZTextTool::htmlspecialchars( $country["Name"] ) );
                    $t->set_var( "selected", "" );
                    if ( isset( $Country ) && $Country[$i] == -1 )
                        $t->set_var( "no_country_selected", "selected" );
                    else if ( isset( $Country ) && $country["ID"] == $Country[$i] )
                        $t->set_var( "selected", "selected" );
                    $t->parse( "country_item_select", "country_item_select_tpl", true );
                }

                $t->parse( "address_item", "address_item_tpl", true );
                $item++;
            }
            else
                $AddressDeleteValues = array_diff( $AddressDeleteValues, array( $AddressID[$i] ) );
        }
        $t->parse( "address_table_item", "address_table_item_tpl", true );

//          $t->parse( "address_item", "address_item_tpl" );

        if ( isset( $NewPhone ) )
        {
            $PhoneTypeID[] = "";
            $PhoneID[] = count( $PhoneID ) > 0 ? $PhoneID[count( $PhoneID ) - 1] + 1 : 1;
            $Phone[] = "";
            $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
        }
        else
        {
            $count = false;
        }
        $item = 0;
        $last_id = 0;
        $PhoneDeleteValues =& array_values( $PhoneDelete );
        for ( $i = 0; $i < $count || $item < $PhoneMinimum; $i++ )
        {
            if ( ( $item % $PhoneWidth == 0 ) && $item > 0 )
            {
                $t->parse( "phone_table_item", "phone_table_item_tpl", true );
                $t->set_var( "phone_item" );
            }
            if ( !isset( $PhoneID[$i] ) or !is_numeric( $PhoneID[$i] ) )
                 $PhoneID[$i] = ++$last_id;
            if ( !in_array( $PhoneID[$i], $PhoneDeleteValues ) )
            {
                $last_id = $PhoneID[$i];
                if( isset( $Phone[$i] ) )
                {
                    $t->set_var( "phone_number", eZTextTool::htmlspecialchars( $Phone[$i] ) );
                    $t->set_var( "phone_id", $PhoneID[$i] );
                    $t->set_var( "phone_index", $PhoneID[$i] );
                }
                else
                {
                    $t->set_var( "phone_number", '' );
                    $t->set_var( "phone_id", '' );
                    $t->set_var( "phone_index", '' );
                }
                $t->set_var( "phone_position", $i + 1 );

                $t->set_var( "phone_item_select", "" );

                foreach ( $phone_types as $phone_type )
                {
                    $t->set_var( "type_id", $phone_type->id() );
                    $t->set_var( "type_name", eZTextTool::htmlspecialchars( $phone_type->name() ) );
                    $t->set_var( "selected", "" );
                    if ( isset( $PhoneTypeID ) && $phone_type->id() == $PhoneTypeID[$i] )
                        $t->set_var( "selected", "selected" );
                    $t->parse( "phone_item_select", "phone_item_select_tpl", true );
                }

                $t->parse( "phone_item", "phone_item_tpl", true );
                $item++;
            }
            else
                $PhoneDeleteValues = array_diff( $PhoneDeleteValues, array( $PhoneID[$i] ) );
        }
        $t->parse( "phone_table_item", "phone_table_item_tpl", true );

        if ( isset( $NewOnline ) )
        {
            $OnlineTypeID[] = "";
            $OnlineID[] = count( $OnlineID ) > 0 ? $OnlineID[count( $OnlineID ) - 1] + 1 : 1;
            $Online[] = "";
            $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
        }
        else
        {
            $count = false;
        }
        $item = 0;
        $last_id = 0;
        $OnlineDeleteValues =& array_values( $OnlineDelete );
        for ( $i = 0; $i < $count || $item < $OnlineMinimum; $i++ )
        {
            if ( ( $item % $OnlineWidth == 0 ) && $item > 0 )
            {
                $t->parse( "online_table_item", "online_table_item_tpl", true );
                $t->set_var( "online_item" );
            }
            if ( !isset( $OnlineID[$i] ) or !is_numeric( $OnlineID[$i] ) )
                 $OnlineID[$i] = ++$last_id;
            if ( !in_array( $OnlineID[$i], $OnlineDeleteValues ) )
            {
                $last_id = $OnlineID[$i];
                if( isset( $Online[$i] ) )
                {
                    $t->set_var( "online_value", eZTextTool::htmlspecialchars( $Online[$i] ) );
                    $t->set_var( "online_id", $OnlineID[$i] );
                    $t->set_var( "online_index", $OnlineID[$i] );
                }
                else
                {
                    $t->set_var( "online_value", '' );
                    $t->set_var( "online_id", '' );
                    $t->set_var( "online_index", '' );
                }
                $t->set_var( "online_position", $i + 1 );

                $t->set_var( "online_item_select", "" );

                foreach ( $online_types as $online_type )
                {
                    $t->set_var( "type_id", $online_type->id() );
                    $t->set_var( "type_name", eZTextTool::htmlspecialchars( $online_type->name() ) );
                    $t->set_var( "selected", "" );
                    if ( isset( $OnlineTypeID ) && $online_type->id() == $OnlineTypeID[$i] )
                        $t->set_var( "selected", "selected" );
                    $t->parse( "online_item_select", "online_item_select_tpl", true );
                }

                $t->parse( "online_item", "online_item_tpl", true );
                $item++;
            }
            else
                $OnlineDeleteValues = array_diff( $OnlineDeleteValues, array( $OnlineID[$i] ) );
        }
        $t->parse( "online_table_item", "online_table_item_tpl", true );

        $groups =& eZUserGroup::getAll();
        foreach ( $groups as $group )
        {
            $t->set_var( "type_id", $group->id() );
            $t->set_var( "type_name", eZTextTool::htmlspecialchars( $group->name() ) );
            $t->set_var( "selected", "" );
            if ( isset( $ContactGroupID ) && $ContactGroupID == $group->id() )
                $t->set_var( "selected", "selected" );
            $t->parse( "contact_group_item_select", "contact_group_item_select_tpl", true );
        }

        $t->set_var( "project_contact_item", "" );
        if ( $CompanyEdit )
        {
            if( isset( $UserSearch ) )
                $t->set_var( "user_search", eZTextTool::htmlspecialchars( $UserSearch ) );
            else
                $t->set_var( "user_search", '' );

            $users = array();
            if ( isset( $ContactGroupID ) && $ContactGroupID == -1 )
            {
                $users =& eZUser::getAll( "name", true, $UserSearch );
            }
            else if ( isset( $ContactGroupID ) && $ContactGroupID == -3 )
            {
                $users =& eZPerson::getAll( $UserSearch, 0, -1 );
            }
            else if ( isset( $ContactGroupID ) && $ContactGroupID < 1 )
            {
                if ( is_numeric( $ContactID ) and $ContactID > 0 )
                {
                    if ( $ContactType == "eZPerson" )
                        $contact = new eZPerson( $ContactID );
                    else
                        $contact = new eZUser( $ContactID );
                    $users[] = $contact;
                }
            }
            else
            {
                $group = new eZUserGroup();
                $users =& $group->users( isset( $ContactGroupID ) ? $ContactGroupID : false, "name", isset( $UserSearch ) ? $UserSearch : false );
            }
            foreach ( $users as $contact )
            {
                if ( is_a( $contact, "eZUser" ) ||
                     is_a( $contact, "eZPerson" ) )
                {
                    $t->set_var( "type_id", $contact->id() );
                    $t->set_var( "type_firstname", eZTextTool::htmlspecialchars( $contact->firstName() ) );
                    $t->set_var( "type_lastname", eZTextTool::htmlspecialchars( $contact->lastName() ) );
                    $t->set_var( "selected", "" );
                    if ( $ContactID == $contact->id() )
                        $t->set_var( "selected", "selected" );
                }
                $t->parse( "contact_item_select", "contact_item_select_tpl", true );
            }
            if ( count( $users ) > 0 )
                $t->set_var( "contact_person_type", is_a( $users[0], "eZUser" )? "eZUser" : "eZPerson" );
            else
                $t->set_var( "contact_person_type", "" );

            $t->set_var( "none_selected", "" );
            $t->set_var( "all_selected", "" );
            $t->set_var( "persons_selected", "" );
            if ( isset( $ContactGroupID ) && $ContactGroupID == -1 )
            {
                $t->set_var( "all_selected", "selected" );
            }
            else if ( isset( $ContactGroupID ) && $ContactGroupID == -3 )
            {
                $t->set_var( "persons_selected", "selected" );
            }
            else if ( isset( $ContactGroupID ) && $ContactGroupID < 1 )
            {
                $t->set_var( "none_selected", "selected" );
            }

            $t->parse( "project_contact_item", "project_contact_item_tpl" );
        }

        $t->set_var( "project_item_select", "" );
        $project_types =& eZProjectType::findTypes();
        foreach ( $project_types as $project_type )
        {
            $t->set_var( "type_id", $project_type->id() );
            $t->set_var( "type_name", eZTextTool::htmlspecialchars( $project_type->name() ) );
            $t->set_var( "selected", "" );
            if ( isset( $ProjectID ) && $ProjectID == $project_type->id() )
                $t->set_var( "selected", "selected" );
            $t->parse( "project_item_select", "project_item_select_tpl", true );
        }

        $t->parse( "project_item", "project_item_tpl", true );

        if ( $CompanyEdit )
        {
            // View logo.
            $logoImage = eZCompany::logoImage( $CompanyID );
            if ( isset( $LogoImageID ) && is_numeric( $LogoImageID ) )
            {
                $logoImage = new eZImage( $LogoImageID );
            }

            $t->set_var( "logo_item", "&nbsp;" );
            if ( ( is_a( $logoImage, "eZImage" ) ) && ( $logoImage->id() != 0 ) )
            {
                $variation = $logoImage->requestImageVariation( 150, 150 );
                if ( is_a( $variation, "eZImageVariation" ) )
                {
                    $t->set_var( "logo_image_src", "/" . $variation->imagePath() );

                    $t->set_var( "logo_image_width", $variation->width() );
                    $t->set_var( "logo_image_height", $variation->height() );
                    $t->set_var( "logo_image_alt", eZTextTool::htmlspecialchars( $logoImage->caption() ) );
                    $t->set_var( "logo_name", eZTextTool::htmlspecialchars( $logoImage->name() ) );
                    $t->set_var( "logo_id", $logoImage->id() );

                    $t->parse( "logo_item", "logo_item_tpl" );
                }
            }
            else
            {
                $t->set_var( "logo_id", "" );
                $t->set_var( "image_id", "" );
            }

            // View company image.
            $companyImage = eZCompany::companyImage( $CompanyID );
            if ( isset( $CompanyImageID ) && is_numeric( $CompanyImageID ) )
            {
                $companyImage = new eZImage( $CompanyImageID );
            }

            $t->set_var( "image_item", "&nbsp;" );
            if ( ( is_a( $companyImage, "eZImage" ) ) && ( $companyImage->id() != 0 ) )
            {
                $variation = $companyImage->requestImageVariation( 150, 150 );
                if ( is_a( $variation, "eZImageVariation" ) )
                {
                    $t->set_var( "image_src", "/" . $variation->imagePath() );
                    $t->set_var( "image_width", $variation->width() );
                    $t->set_var( "image_height", $variation->height() );
                    $t->set_var( "image_alt", eZTextTool::htmlspecialchars( $companyImage->caption() ) );
                    $t->set_var( "image_name", eZTextTool::htmlspecialchars( $companyImage->name() ) );
                    $t->set_var( "image_id", $companyImage->id() );
                    $t->set_var( "image_caption", '' );

                    $t->parse( "image_item", "image_item_tpl" );
                }
            }
        }
    }

// Template variables.

    if ( isset( $CompanyID ) && is_numeric( $CompanyID ) || isset( $PersonID ) && is_numeric( $PersonID ) )
        $t->parse( "delete_item", "delete_item_tpl" );
    else
        $t->set_var( "delete_item", "" );

    if ( !$error )
        $t->set_var( "action_value", $Action );

    $t->parse( "edit_item", "edit_tpl" );
}

$t->pparse( "output", "person_edit" );

?>