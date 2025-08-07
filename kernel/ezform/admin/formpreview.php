<?php
// 
// $Id: formpreview.php 6216 2001-07-19 13:03:50Z jakobn $
//
// Created on: <12-Jun-2001 13:07:24 pkej>
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

// include_once( "classes/INIFile.php" );
// include_once( "classes/eztemplate.php" );
// include_once( "classes/ezhttptool.php" );

// include_once( "ezform/classes/ezform.php" );
// include_once( "ezform/classes/ezformelement.php" );
// include_once( "ezform/classes/ezformelementtype.php" );
// include_once( "ezform/classes/ezformrenderer.php" );
// include_once( "ezmail/classes/ezmail.php" );


$ini =& eZINI::instance( 'site.ini' );

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if( isset( $OK ) )
{
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}


$ActionValue="preview";

$form = new eZForm( $FormID );

$errorMessages = array();

$Language = $ini->variable( "eZFormMain", "Language" );

$t = new eZTemplate( "kernel/ezform/admin/" . $ini->variable( "eZFormMain", "AdminTemplateDir" ),
                     "kernel/ezform/admin/intl/", $Language, "form.php" );

$t->setAllStrings();

$t->set_file( array(
    "form_preview_page_tpl" => "formpreview.tpl"
    ) );

$t->set_block( "form_preview_page_tpl", "mail_preview_tpl", "mail_preview" );

$t->set_var( "mail_preview", "" );
$t->set_var( "error", "" );

$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );
$t->set_var( "form_completed_page", $form->completedPage() );
$t->set_var( "form_instruction_page", $form->instructionPage() );

$renderer = new eZFormRenderer( $form );
$output =& $renderer->renderForm( $form, false, false );
$t->set_var( "form", $output );

if( isset( $Test ) )
{
    $output =& $renderer->verifyForm();
    $t->set_var( "error", $output );
}


if( count( $errorMessages ) > 0 )
{
    foreach( $errorMessages as $errorMessage )
    {
        $errorMessage =& $t->Ini->variable( "strings", $errorMessage );
        $t->set_var( "error_message", $errorMessage );
        $t->parse( "error_item", "error_item_tpl", true );
    }
    
    $t->set_var( "form_id", $formID );
    $t->set_var( "form_name", $formName );
    $t->set_var( "form_completed_page", $formCompletedPage );
    $t->set_var( "form_instruction_page", $formInstructionPage );

    $t->parse( "error_list", "error_list_tpl" );
}

$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteDesign );
$t->pparse( "output", "form_preview_page_tpl" );

?>