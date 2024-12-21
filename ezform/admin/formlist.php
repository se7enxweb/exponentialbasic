<?php
// 
// $Id: formlist.php 6216 2001-07-19 13:03:50Z jakobn $
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
// include_once( "classes/ezlist.php" );

// include_once( "ezform/classes/ezform.php" );

$ActionValue = "list";
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );
$Limit = $ini->read_var( "eZFormMain", "AdminFormListLimit" );

if ( !$Offset )
    $Offset = 0;

if( isset( $DeleteSelected ) )
{
    foreach( $formDelete as $deleteMe )
    {
        $form = new eZForm( $deleteMe );
        $form->delete();
    }
    eZHTTPTool::header( "Location: /form/form/list" );
    exit();
}

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );
$t->setAllStrings();

$t->set_file( array(
    "form_list_page_tpl" => "formlist.tpl"
    ) );

$t->set_block( "form_list_page_tpl", "no_forms_item_tpl", "no_forms_item" );
$t->set_block( "form_list_page_tpl", "form_list_tpl", "form_list" );
$t->set_block( "form_list_tpl", "form_item_tpl", "form_item" );

$t->set_var( "form_item", "" );
$t->set_var( "form_list", "" );
$t->set_var( "no_forms_item", "" );

$totalCount =& eZForm::count();
$forms =& eZForm::getAll( $Offset, $Limit );


if( count( $forms ) == 0 )
{
    $t->parse( "no_forms_item", "no_forms_item_tpl" );
}
else
{
    $i = 0;
    foreach( $forms as $form )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->set_var( "form_id", $form->id() );
        $t->set_var( "form_name", $form->name() );
        $t->set_var( "form_receiver", $form->receiver() );
        $t->parse( "form_item", "form_item_tpl", true );
        
        $i++;
    }
    
    
    $t->parse( "form_list", "form_list_tpl" );
}

eZList::drawNavigator( $t, $totalCount, $Limit, $Offset, "form_list_page_tpl" );

$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteDesign );
$t->pparse( "output", "form_list_page_tpl" );

?>