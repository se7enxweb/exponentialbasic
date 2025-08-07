<?php
//
// $Id: companyview.php 9529 2002-05-14 11:17:05Z jhe $
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
  Edit company.
*/

// include_once( "classes/INIFile.php" );

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZContactMain", "Language" );
$CompanyViewLogin = $ini->variable( "eZContactMain", "CompanyViewLogin" ) == "true";
$CompanyEditLogin = $ini->variable( "eZContactMain", "CompanyEditLogin" ) == "true";
$ShowCompanyContact = $ini->variable( "eZContactMain", "ShowCompanyContact" ) == "true";
$ShowCompanyStatus = $ini->variable( "eZContactMain", "ShowCompanyStatus" ) == "true";
$SiteURL = $ini->variable( "Site", "SiteURL" );
$AdminSiteURL = $ini->variable( "Site", "AdminSiteURL" );

// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezlocale.php" );
// include_once( "classes/ezdate.php" );
// include_once( "classes/ezlist.php" );
// include_once( "classes/eztexttool.php" );
// include_once( "classes/ezimagefile.php" );
// include_once( "classes/ezcurrency.php" );

// include_once( "ezaddress/classes/ezaddress.php" );
// include_once( "ezaddress/classes/ezaddresstype.php" );
// include_once( "ezaddress/classes/ezphone.php" );
// include_once( "ezaddress/classes/ezphonetype.php" );
// include_once( "ezaddress/classes/ezonline.php" );
// include_once( "ezaddress/classes/ezonlinetype.php" );

// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezcontact/classes/ezcompanytype.php" );
// include_once( "ezcontact/classes/ezprojecttype.php" );
// include_once( "ezcontact/classes/ezconsultation.php" );

// include_once( "ezimagecatalogue/classes/ezimage.php" );

// include_once( "ezmail/classes/ezmail.php" );

// include_once( "eztrade/classes/ezorder.php" );

// include_once( "ezuser/classes/ezusergroup.php" );
// include_once( "ezuser/classes/ezpermission.php" );

$user =& eZUser::currentUser();

if ( $CompanyViewLogin and !is_a( $user, "eZUser" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( $CompanyViewLogin and !eZPermission::checkPermission( $user, "eZContact", "CompanyView" ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/company/view" );
    exit();
}

$t = new eZTemplate( "kernel/ezcontact/admin/" . $ini->variable( "eZContactMain", "AdminTemplateDir" ),
                     "kernel/ezcontact/admin/intl", $Language, "companyview.php" );
$intl = new eZINI( "kernel/ezcontact/admin/intl/$Language/companyview.php.ini", false );
$t->setAllStrings();

$t->set_file( "company_edit", "companyview.tpl" );

$t->set_block( "company_edit", "company_information_tpl", "company_information" );
$t->set_block( "company_edit", "no_company_tpl", "no_company" );

$t->set_block( "company_information_tpl", "contact_item_tpl", "contact_item" );
$t->set_block( "contact_item_tpl", "contact_person_tpl", "contact_person" );
$t->set_block( "contact_item_tpl", "no_contact_person_tpl", "no_contact_person" );
$t->set_block( "company_information_tpl", "status_item_tpl", "status_item" );
$t->set_block( "status_item_tpl", "project_status_tpl", "project_status" );
$t->set_block( "status_item_tpl", "no_project_status_tpl", "no_project_status" );

$t->set_block( "company_information_tpl", "consultation_buttons_tpl", "consultation_buttons" );
$t->set_block( "company_information_tpl", "buy_button_tpl", "buy_button" );

$t->set_block( "company_information_tpl", "person_table_item_tpl", "person_table_item" );
$t->set_block( "person_table_item_tpl", "person_item_tpl", "person_item" );
$t->set_block( "person_item_tpl", "person_consultation_button_tpl", "person_consultation_button" );

$t->set_block( "company_information_tpl", "consultation_table_item_tpl", "consultation_table_item" );
$t->set_block( "company_information_tpl", "order_table_item_tpl", "order_table_item" );
$t->set_block( "company_information_tpl", "mail_table_item_tpl", "mail_table_item" );
$t->set_block( "consultation_table_item_tpl", "consultation_item_tpl", "consultation_item" );
$t->set_block( "order_table_item_tpl", "order_item_tpl", "order_item" );
$t->set_block( "mail_table_item_tpl", "mail_item_tpl", "mail_item" );

$t->set_block( "company_information_tpl", "address_item_tpl", "address_item" );
$t->set_var( "address_item", "" );
$t->set_block( "company_information_tpl", "no_address_item_tpl", "no_address_item" );
$t->set_var( "no_address_item", "" );
$t->set_block( "company_information_tpl", "image_view_tpl", "image_view" );
$t->set_var( "image_view", "&nbsp;" );
$t->set_block( "company_information_tpl", "logo_view_tpl", "logo_view" );
$t->set_var( "logo_view", "&nbsp;" );
$t->set_block( "company_information_tpl", "no_image_tpl", "no_image" );
$t->set_var( "no_image", "&nbsp;" );

$t->set_block( "company_information_tpl", "online_item_tpl", "online_item" );
$t->set_var( "online_item", "&nbsp;" );
$t->set_block( "online_item_tpl", "online_line_tpl", "online_line" );
$t->set_var( "online_line", "&nbsp;" );
$t->set_block( "online_line_tpl", "email_line_tpl", "email_line" );
$t->set_var( "email_line", "" );
$t->set_block( "online_line_tpl", "url_line_tpl", "url_line" );
$t->set_var( "url_line", "" );
$t->set_block( "company_information_tpl", "no_online_item_tpl", "no_online_item" );
$t->set_var( "no_online_item", "&nbsp;" );
$t->set_block( "company_information_tpl", "phone_item_tpl", "phone_item" );
$t->set_var( "phone_item", "&nbsp;" );
$t->set_block( "phone_item_tpl", "phone_line_tpl", "phone_line" );
$t->set_var( "phone_line", "&nbsp;" );
$t->set_block( "company_information_tpl", "no_phone_item_tpl", "no_phone_item" );
$t->set_var( "no_phone_item", "&nbsp;" );
$t->set_block( "company_information_tpl", "company_edit_button_tpl", "company_edit_button" );
$t->set_var( "company_edit_button", "" );

$t->set_var( "company_information", "" );
$t->set_var( "no_company", "" );

$t->set_var( "company_id", $CompanyID );

if ( !eZCompany::exists( $CompanyID ) )
{
    $t->parse( "no_company", "no_company_tpl" );
}
else
{
    $company = new eZCompany();
    $company->get( $CompanyID );

    $t->set_var( "name", eZTextTool::htmlspecialchars( $company->name() ) );
    $t->set_var( "description", eZTextTool::htmlspecialchars( $company->comment() ) );
    $t->set_var( "company_no", eZTextTool::htmlspecialchars( $company->companyNo() ) );


// View logo.
    $logoImage = $company->logoImage();

    $no_image = true;
    if ( ( is_a( $logoImage, "eZImage" ) ) && ( $logoImage->id() != 0 ) )
    {
        $variation = $logoImage->requestImageVariation( 150, 150 );
        if ( is_a( $variation, "eZImageVariation" ) )
        {
            $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
            $t->set_var( "logo_name", eZTextTool::htmlspecialchars( $logoImage->name() ) );
            $t->set_var( "logo_id", $logoImage->id() );
            $t->set_var( "logo_width", $variation->width() );
            $t->set_var( "logo_height", $variation->height() );
            $t->set_var( "logo_alt", eZTextTool::htmlspecialchars( $logoImage->caption() ) );

            $t->parse( "logo_view", "logo_view_tpl" );
            $no_image = false;
        }
    }


// View company image.
    $companyImage = $company->companyImage();

    $no_image = true;
    if ( ( is_a( $companyImage, "eZImage" ) ) && ( $companyImage->id() != 0 ) )
    {
        $variation = $companyImage->requestImageVariation( 150, 150 );
        if ( is_a( $variation, "eZImageVariation" ) )
        {
            $t->set_var( "image_src", "/" . $variation->imagePath() );
            $t->set_var( "image_name", eZTextTool::htmlspecialchars( $companyImage->name() ) );
            $t->set_var( "image_id", $companyImage->id() );
            $t->set_var( "image_width", $variation->width() );
            $t->set_var( "image_height", $variation->height() );

            $t->set_var( "image_alt", eZTextTool::htmlspecialchars( $companyImage->caption() ) );

            $t->parse( "image_view", "image_view_tpl" );
            $no_image = false;
        }
    }
    if ( $no_image )
        $t->parse( "no_image", "no_image_tpl" );


// Address list
    $addressList = $company->addresses( $company->id() );
    if ( count( $addressList ) != 0 )
    {
        foreach ( $addressList as $addressItem )
        {
            $t->set_var( "address_id", $addressItem->id() );
            $t->set_var( "street1", eZTextTool::htmlspecialchars( $addressItem->street1() ) );
            $t->set_var( "street2", eZTextTool::htmlspecialchars( $addressItem->street2() ) );
            $t->set_var( "zip", eZTextTool::htmlspecialchars( $addressItem->zip() ) );
            $t->set_var( "place", eZTextTool::htmlspecialchars( $addressItem->place() ) );
            $addressType = $addressItem->addressType();
            $t->set_var( "address_type_name", eZTextTool::htmlspecialchars( $addressType->name() ) );
            $country = $addressItem->country();
            if ( is_a( $country, "eZCountry" ) )
                $t->set_var( "country", eZTextTool::htmlspecialchars( $country->name() ) );
            else
                $t->set_var( "country", "" );

            $t->set_var( "script_name", "companyedit.php" );

            $t->parse( "address_item", "address_item_tpl", true );

        }
    }
    else
    {
        $t->parse( "no_address_item", "no_address_item_tpl" );
    }


// Telephone list
    $phoneList = $company->phones();

    $count = count( $phoneList );

    if ( $count != 0 )
    {
        for ( $i = 0; $i < $count; $i++ )
        {
            $t->set_var( "phone_id", $phoneList[$i]->id() );
            $t->set_var( "phone", eZTextTool::htmlspecialchars( $phoneList[$i]->number() ) );

            $phoneType = $phoneList[$i]->phoneType();

            $t->set_var( "phone_type_id", $phoneType->id() );
            $t->set_var( "phone_type_name", eZTextTool::htmlspecialchars( $phoneType->name() ) );

            $t->set_var( "phone_width", 100/$count );
            $t->parse( "phone_line", "phone_line_tpl", true );
        }
        $t->parse( "phone_item", "phone_item_tpl" );
    }
    else
    {
        $t->parse( "no_phone_item", "no_phone_item_tpl" );
    }

// Online list
    $OnlineList = $company->onlines( $company->id() );
    $count = count( $OnlineList );
    if ( $count != 0)
    {
        for ( $i = 0; $i < $count; $i++ )
        {
            $t->set_var( "online_id", $OnlineList[$i]->id() );
            $onlineType = $OnlineList[$i]->onlineType();
            $prefix = $onlineType->urlPrefix();
            $vis_prefix = $prefix;
            $url = $OnlineList[$i]->URL();
            if ( $onlineType->prefixLink() )
            {
                if ( strncasecmp( $url, $prefix, strlen( $prefix ) ) == 0 )
                {
                    $prefix = "";
                }
            }
            else
            {
                $prefix = "";
            }
            if ( $onlineType->prefixVisual() )
            {
                if ( strncasecmp( $url, $vis_prefix, strlen( $vis_prefix ) ) == 0 )
                {
                    $vis_prefix = "";
                }
            }
            else
            {
                $vis_prefix = "";
            }

            $t->set_var( "online_prefix", $prefix );
            $t->set_var( "online_visual_prefix", $vis_prefix );
            $t->set_var( "online", eZTextTool::htmlspecialchars( $OnlineList[$i]->URL() ) );
            $t->set_var( "online_type_id", $onlineType->id() );
            $t->set_var( "online_type_name", eZTextTool::htmlspecialchars( $onlineType->name() ) );
            $t->set_var( "online_width", 100 / $count );

            $t->parse( "online_line", "online_line_tpl", true );
        }
        $t->parse( "online_item", "online_item_tpl" );
    }
    else
    {
        $t->parse( "no_online_item", "no_online_item_tpl" );
    }

    $t->set_var( "contact_person", "" );
    $t->set_var( "no_contact_person", "" );

    $t->set_var( "contact_item", "" );
    if ( $ShowCompanyContact )
    {
        $contact = $company->contact();
        if ( $contact )
        {
            if ( $company->contactType() == "ezperson" )
                $contactPerson = new eZPerson( $contact );
            else
                $contactPerson = new eZUser( $contact );
            $t->set_var( "contact_firstname", eZTextTool::htmlspecialchars( $contactPerson->firstName() ) );
            $t->set_var( "contact_lastname", eZTextTool::htmlspecialchars( $contactPerson->lastName() ) );
            $t->parse( "contact_person", "contact_person_tpl" );
        }
        else
        {
            $t->parse( "no_contact_person", "no_contact_person_tpl" );
        }
        $t->parse( "contact_item", "contact_item_tpl" );
    }

    $t->set_var( "status_item", "" );
    if ( $ShowCompanyStatus )
    {
        $t->set_var( "project_status", "" );
        $t->set_var( "no_project_status", "" );

        $statusid = $company->projectState();
        if ( $statusid )
        {
            $status = new eZProjectType( $statusid );
            $t->set_var( "project_status", eZTextTool::htmlspecialchars( $status->name() ) );
            $t->parse( "project_status", "project_status_tpl" );
        }
        else
        {
            $t->parse( "no_project_status", "no_project_status_tpl" );
        }
        $t->parse( "status_item", "status_item_tpl" );
    }

// Person list
    $user =& eZUser::currentUser();
    $t->set_var( "person_consultation_button", "" );
    $t->set_var( "buy_button", "" );
    if ( is_a( $user, "eZUser" ) && eZPermission::checkPermission( $user, "eZContact", "consultation" ) )
    {
        $t->parse( "person_consultation_button", "person_consultation_button_tpl" );
    }

    if ( is_a( $user, "eZUser" ) && eZPermission::checkPermission( $user, "eZContact", "Buy" ) )
    {
        $t->parse( "buy_button", "buy_button_tpl" );
    }

    if ( !isset( $PersonLimit ) or !is_numeric( $PersonLimit ) )
        $PersonLimit = 5;
    if ( !isset( $PersonOffset ) or !is_numeric( $PersonOffset ) )
        $PersonOffset = 0;
    $t->set_var( "person_table_item", "" );
    $persons = $company->persons( $CompanyID, true, $PersonLimit, $PersonOffset );
    if ( count( $persons ) > 0 )
    {
        $person_count = $company->personCount();
        $i = 0;
        $t->set_var( "person_max", $person_count );
        $t->set_var( "person_start", $PersonOffset + 1 );
        $t->set_var( "person_end", min( $PersonOffset + $PersonLimit, $person_count ) );
        foreach ( $persons as $person )
        {
            $t->set_var( "bg_color", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
            $t->set_var( "person_id", $person->id() );
            $t->set_var( "person_lastname", eZTextTool::htmlspecialchars( $person->lastName() ) );
            $t->set_var( "person_firstname", eZTextTool::htmlspecialchars( $person->firstName() ) );
            $t->parse( "person_item", "person_item_tpl", true );
            $i++;
        }
        eZList::drawNavigator( $t, $person_count, $PersonLimit, $PersonOffset, "person_table_item_tpl" );

        $t->parse( "person_table_item", "person_table_item_tpl" );
    }

// Consultation list
    $user =& eZUser::currentUser();
    if ( is_a( $user, "eZUser" ) && eZPermission::checkPermission( $user, "eZContact", "consultation" ) )
    {
        if ( !isset( $OrderBy ) )
            $OrderBy = "Date";

        $max = $ini->variable( "eZContactMain", "MaxCompanyConsultationList" );

        if ( $ini->variable( "eZContactMain", "ShowAllConsultations" ) == "enabled" )
        {
            if ( $ini->variable( "eZContactMain", "ShowRelatedConsultations" ) == "enabled" )
                $consultations = eZConsultation::findConsultationsByContact( $CompanyID, -1, $OrderBy, false, 0, $max, true );
            else
                $consultations = eZConsultation::findConsultationsByContact( $CompanyID, -1, $OrderBy, false, 0, $max );
        }
        else
        {
            if ( $ini->variable( "eZContactMain", "ShowRelatedConsultations" ) == "enabled" )
                $consultations = eZConsultation::findConsultationsByContact( $CompanyID, $user->id(), $OrderBy, false, 0, $max, true );
            else
                $consultations = eZConsultation::findConsultationsByContact( $CompanyID, $user->id(), $OrderBy, false, 0, $max );
        }

        $t->set_var( "consultation_type", "company" );
        $t->set_var( "company_id", $CompanyID  );

        $locale = new eZLocale( $Language );
        $i = 0;

        foreach ( $consultations as $consultation )
        {
            $t->set_var( "bg_color", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

            $t->set_var( "consultation_id", $consultation->id() );
            $t->set_var( "consultation_date", $locale->format( $consultation->date() ) );
            $t->set_var( "consultation_short_description", eZTextTool::htmlspecialchars( $consultation->shortDescription() ) );
            $t->set_var( "consultation_status_id", $consultation->state() );
            $t->set_var( "consultation_status", eZTextTool::htmlspecialchars( eZConsultation::stateName( $consultation->state() ) ) );
            $t->parse( "consultation_item", "consultation_item_tpl", true );
            $i++;
        }
    }

    if ( is_a( $user, "eZUser" ) and eZPermission::checkPermission( $user, "eZContact", "consultation" ) and count( $consultations ) > 0 )
    {
        $t->parse( "consultation_table_item", "consultation_table_item_tpl", true );
    }
    else
    {
        $t->set_var( "consultation_table_item", "" );
    }

    if ( is_a( $user, "eZUser" ) and eZPermission::checkPermission( $user, "eZContact", "consultation" ) )
    {
        $t->parse( "consultation_buttons", "consultation_buttons_tpl" );
    }
    else
    {
        $t->set_var( "consultation_buttons", "" );
    }

    if ( !$CompanyEditLogin or ($CompanyEditLogin and eZPermission::checkPermission( $user, "eZContact", "companyedit" ) ) )
    {
        $t->parse( "company_edit_button", "company_edit_button_tpl" );
    }

// Order list
    if ( is_a( $user, "eZUser" ) and eZPermission::checkPermission( $user, "eZContact", "buy" ) )
    {
        $max = $ini->variable( "eZContactMain", "MaxCompanyConsultationList" );
        $orders = eZOrder::getByContact( $CompanyID, false, 0, $max );

        $locale = new eZLocale( $Language );
        $i = 0;
        $currency = new eZCurrency();
        $languageINI = new eZINI( "kernel/eztrade/admin/intl/" . $Language . "/orderlist.php.ini", false );
        $t->set_var( "admin_dir", $AdminSiteURL );
        foreach ( $orders as $order )
        {
            $t->set_var( "bg_color", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

            $t->set_var( "order_id", $order->id() );
            $t->set_var( "order_date", $locale->format( $order->date() ) );

            $status = $order->initialStatus( );
            $dateTime = $status->altered();

            $status = $order->lastStatus( );

            $statusType = $status->type();
            $statusName = preg_replace( "#intl-#", "", $statusType->name() );
            $statusName =  $languageINI->variable( "strings", $statusName );

            $t->set_var( "order_status", $statusName );

            if ( $order->isVATInc() == true )
                $currency->setValue( $order->totalPriceIncVAT() + $order->shippingCharge());
            else
                $currency->setValue( $order->totalPrice() + $order->shippingCharge() );
            $t->set_var( "order_price", $locale->format( $currency ) );

            $t->parse( "order_item", "order_item_tpl", true );
            $i++;
        }
    }

    if ( is_a( $user, "eZUser" ) and eZPermission::checkPermission( $user, "eZContact", "buy" ) and count( $orders ) > 0 )
    {
        $t->parse( "order_table_item", "order_table_item_tpl", true );
    }
    else
    {
        $t->set_var( "order_table_item", "" );
    }

// e-mail list
    $emails = eZMail::getByContact( $CompanyID, true, 0, $max );

    $locale = new eZLocale( $Language );
    $i = 0;
    $currency = new eZCurrency();
    $t->set_var( "admin_dir", $AdminSiteURL );
    $date = new eZDateTime();
    foreach ( $emails as $email )
    {
        $t->set_var( "bg_color", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );

        $t->set_var( "mail_id", $email->id() );
        $date->setTimeStamp( $email->uDate() );
        $t->set_var( "mail_date", $locale->format( $date ) );

        $t->set_var( "mail_subject", $email->subject() );
        $t->set_var( "mail_email", $email->sender() );

        $t->parse( "mail_item", "mail_item_tpl", true );
        $i++;
    }

    if ( is_a( $user, "eZUser" ) and count( $emails ) > 0 )
    {
        $t->parse( "mail_table_item", "mail_table_item_tpl", true );
    }
    else
    {
        $t->set_var( "mail_table_item", "" );
    }



// Template variabler.
    $Action_value = "update";

    $t->parse( "company_information", "company_information_tpl" );
}

$t->pparse( "output", "company_edit"  );

?>