<?php
// 
// $Id: link.php 6478 2001-08-17 08:43:49Z jhe $
//
// Created on: <17-Aug-2001 09:06:39 jhe>
//
// This source file is part of Exponential Basic, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

// include_once( "classes/eztexttool.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "ezcontact/classes/ezperson.php" );
// include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezcontact/classes/ezcompanytype.php" );
// include_once( "ezmail/classes/ezmail.php" );

if ( isset( $Back ) )
{
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mail/view/$MailID" );
    exit();
}

if ( isset( $OK ) )
{
    eZMail::removeContacts( $MailID );
    foreach ( $CompanyID as $company )
    {
        if ( $company > -1 )
            eZMail::addContact( $MailID, $company, true );
    }
    foreach ( $PersonID as $person )
    {
        if ( $person > -1 )
            eZMail::addContact( $MailID, $person, false );
    }
    // include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /mail/view/$MailID" );
    exit();
}

$ini =& eZINI::instance( 'site.ini' );
$Language = $ini->variable( "eZMailMain", "Language" ); 

$t = new eZTemplate( "kernel/ezmail/user/" . $ini->variable( "eZMailMain", "TemplateDir" ),
                     "kernel/ezmail/user/intl/", $Language, "link.php" );
$t->setAllStrings();

$t->set_file( "mail_link_page_tpl", "link.tpl" );

$t->set_block( "mail_link_page_tpl", "company_list_tpl", "company_list" );
$t->set_block( "company_list_tpl", "company_select_tpl", "company_select" );
$t->set_block( "mail_link_page_tpl", "person_list_tpl", "person_list" );
$t->set_block( "person_list_tpl", "person_select_tpl", "person_select" );

$t->set_var( "mail_id", $MailID );

$top_name = $t->get_var( "intl-top_category" );
if ( !is_string( $top_name ) )
    $top_name = "";
$companyTypeList = eZCompanyType::getTree( 0, 0, true, $top_name );
$categoryList = array();

if ( !isset( $PersonID ) )
    $PersonID = false;


$categoryList = eZPerson::companies( $PersonID, false );
$category_values = (new eZMail())->getContacts( $MailID );
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
        $t->set_var( "is_selected", in_array( $companyItem->id(), $category_values["CompanyID"] )
                     ? "selected" : "" );
        $t->parse( "company_select", "company_select_tpl", true );
    }
}

if ( count( $companyTypeList ) > 0 )
{
    $t->parse( "company_list", "company_list_tpl" );
}
else
{
    $t->set_var( "company_list", "" );
}
$personList =& (new eZPerson())->getAll();

foreach ( $personList as $person )
{
    $t->set_var( "person_name", eZTextTool::htmlspecialchars( $person->name() ) );
    $t->set_var( "person_id", $person->id() );
    $t->set_var( "person_is_selected", in_array( $person->id(), $category_values["PersonID"] )
                 ? "selected" : "" );
    $t->parse( "person_select", "person_select_tpl", true );
}

if ( count( $personList ) > 0 )
{
    $t->parse( "person_list", "person_list_tpl" );
}
else
{
    $t->set_var( "person_list", "" );
}


$t->pparse( "output", "mail_link_page_tpl" );

?>