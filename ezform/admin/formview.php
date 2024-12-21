<?php
// 
// $Id: formview.php 6216 2001-07-19 13:03:50Z jakobn $
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


$ini =& INIFile::globalINI();

if( isset( $Cancel ) )
{
    if( !empty( $redirectTo ) )
    {
        eZHTTPTool::header( "Location: $redirectTo" );
    }
    else
    {
        eZHTTPTool::header( "Location: /" );
    }
    exit();
}



$ActionValue="process";

$form = new eZForm( $FormID );

$errorMessages = array();

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );

$t->setAllStrings();

$t->set_file( array(
    "form_view_page_tpl" => "formview.tpl"
    ) );

$t->set_block( "form_view_page_tpl", "mail_preview_tpl", "mail_preview" );

$t->set_var( "error", "" );
$t->set_var( "form", "" );

$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );
$t->set_var( "form_completed_page", $form->completedPage() );
$t->set_var( "form_instruction_page", $form->instructionPage() );

$renderer = new eZFormRenderer( $form );
$output =& $renderer->renderForm( $form );
$t->set_var( "form", $output );

if( isset( $OK ) )
{
    $output =& $renderer->verifyForm();
    if( $output == "" )
    {
        $renderer->sendForm();
    }
    else
    {
        $t->set_var( "error", $output );
    }
}

$t->pparse( "output", "form_view_page_tpl" );

?>
