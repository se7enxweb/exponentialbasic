<?php
// 
// $Id: formedit.php 8176 2001-11-01 17:01:50Z bf $
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
// include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/list/" );
    exit();
}

$ActionValue="edit";

$form = new eZForm( $FormID );

if ( isset( $Action ) && $Action == "up" )
{
    $element = new eZFormElement( $ElementID );
    $form->moveUp( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( isset( $Action ) && $Action == "down" )
{
    $element = new eZFormElement( $ElementID );
    $form->moveDown( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}


if ( isSet( $DeleteSelected ) )
{
    foreach ( $elementDelete as $deleteMe )
    {
        $element = new eZFormElement( $deleteMe );
        $element->delete();
    }
}

$errorMessages = array();

if ( isSet( $OK ) || isSet( $Update ) || isSet( $Preview ) || isSet( $NewElement ) )
{
    if ( empty( $formSender ) )
    {
        if ( isSet( $formSendAsUser ) == false )
        {
            $errorMessages[] = "form_must_have_sender";
        }
    }
    else
    {
        if ( isSet( $formSendAsUser ) )
        {
            $errorMessages[] = "form_cant_have_both";
        }
        else
        {
            if ( eZMail::validate( $formReceiver ) == false )
            {
                $errorMessages[] = "form_sender_not_valid";
            }
        }
    }

    if ( empty( $formName ) )
    {
        $errorMessages[] = "form_name_not_set";
    }

    if ( empty( $formReceiver ) )
    {
        $errorMessages[] = "form_receiver_not_set";
    }
    else
    {
        if ( eZMail::validate( $formReceiver ) == false )
        {
            $errorMessages[] = "form_receiver_not_valid";
        }
    }

    if ( empty( $formCompletedPage ) )
    {
        $errorMessages[] = "form_completed_page_not_set";
    }

    if ( empty( $formCC ) == false )
    {
        if ( eZMail::validate( $formCC ) == false )
        {
            $errorMessages[] = "form_cc_not_valid";
        }
    }

    if ( count( $errorMessages ) == 0 || isSet( $NewElement ) || isSet( $Update ) )
    {
        $form->setName( $formName );
        $form->setReceiver( $formReceiver );
        $form->setCC( $formCC );
        $form->setCompletedPage( $formCompletedPage );
        $form->setInstructionPage( $formInstructionPage );
        $form->setSender( $formSender );
        
        if ( isSet( $formSendAsUser ) )
        {
            $form->setSendAsUser( true );
        }
        else
        {
            $form->setSendAsUser( false );
        }
        
        $form->store();
        $FormID = $form->id();
        
        $existingElementCount = $form->numberOfElements();
        $existingElementCount++;
        
        if ( isSet( $NewElement ) )
        {
            $newElementName =& $ini->read_var( "eZFormMain", "DefaultElementName" );
            $newElementName = $newElementName . " " . $existingElementCount;
            $element = new eZFormElement();
            $element->setName( $newElementName );
            $element->store();
        }
        
        if ( isSet( $element ) )
        {
            $form->addElement( $element );
        }
        if( isset( $elementID ) )
            $elementCount = count( $elementID );
        else
            $elementCount = false;

        $elementTypeError = false;
        
        for ( $i = 0; $i < $elementCount; $i++ )
        {
            $element = new eZFormElement( $elementID[$i] );
            $elementType = new eZFormElementType( $elementTypeID[$i] );
            $element->setElementType( $elementType );

            if ( $elementType->id() == 0 && $elementTypeError == false )
            {
                $errorMessages[] = "all_elements_must_have_type";
                $elementTypeError = true;
            }

            $element->setName( $elementName[$i] );

            if( isset( $Size[ $i ] ) )
                $element->setSize( $Size[$i] );
            else
                $element->setSize( 0 );


            $required = false;
            $break = false;
            if ( isset( $elementRequired ) && count( $elementRequired ) > 0 )
            {
                foreach ( $elementRequired as $requiredID )
                {
                    if ( $elementID[$i] == $requiredID )
                    {
                        $element->setRequired( true );
                        $required = true;
                    }
                }
            }
            if ( isset( $ElementBreak ) && count( $ElementBreak ) > 0 )
            {
                foreach ( $ElementBreak as $breakID )
                {
                    if ( $elementID[$i] == $breakID )
                    {
                        $element->setBreak( true );
                        $break = true;
                    }
                }
            }

            $element->setBreak( $break );
            $element->setRequired( $required );

            $element->store();
        }

        if ( isSet( $OK ) && count( $errorMessages ) == 0 )
        {
            eZHTTPTool::header( "Location: /form/form/list/" );
            exit();
        }

        if ( isSet( $Preview ) && count( $errorMessages ) == 0 )
        {
            eZHTTPTool::header( "Location: /form/form/preview/$FormID/" );
            exit();
        }
    }
}

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "kernel/ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "kernel/ezform/admin/intl/", $Language, "form.php" );

$t->set_file( "form_edit_page_tpl", "formedit.tpl" );

$t->set_block( "form_edit_page_tpl", "form_item_tpl", "form_item" );
$t->set_block( "form_edit_page_tpl", "error_list_tpl", "error_list" );
$t->set_block( "error_list_tpl", "error_item_tpl", "error_item" );
$t->set_block( "form_edit_page_tpl", "no_types_item_tpl", "no_types_item" );
$t->set_block( "form_edit_page_tpl", "no_elements_item_tpl", "no_elements_item" );
$t->set_block( "form_edit_page_tpl", "element_list_tpl", "element_list" );
$t->set_block( "element_list_tpl", "element_item_tpl", "element_item" );
$t->set_block( "element_item_tpl", "typelist_item_tpl", "typelist_item" );
$t->set_block( "element_item_tpl", "fixed_values_tpl", "fixed_values" );
$t->set_block( "element_item_tpl", "size_tpl", "size" );
$t->set_block( "element_item_tpl", "break_tpl", "break" );

$move_item = true;
$t->set_block( "element_item_tpl", "item_move_up_tpl", "item_move_up" );
$t->set_block( "element_item_tpl", "item_separator_tpl", "item_separator" );
$t->set_block( "element_item_tpl", "item_move_down_tpl", "item_move_down" );
$t->set_block( "element_item_tpl", "no_item_move_up_tpl", "no_item_move_up" );
$t->set_block( "element_item_tpl", "no_item_separator_tpl", "no_item_separator" );
$t->set_block( "element_item_tpl", "no_item_move_down_tpl", "no_item_move_down" );

$t->set_var( "no_types_item", "" );
$t->set_var( "no_elements_item", "" );
$t->set_var( "element_list", "" );
$t->set_var( "element_item", "" );
$t->set_var( "typelist_item", "" );
$t->set_var( "error_list", "" );
$t->set_var( "checked", "" );

$t->set_var( "form_completed_page", "" );
$t->set_var( "form_instruction_page", "" );

if ( $form->completedPage() )
{
    $t->set_var( "form_completed_page", $form->completedPage() );
}
else
{
    if ( $ini->read_var( "eZFormMain", "UseDefaultRedirectPage" ) == "enabled" )
    {
        $t->set_var( "form_completed_page", $ini->read_var( "eZFormMain", "DefaultRedirectPage" ) );
    }
}

if ( $form->instructionPage() )
{
    $t->set_var( "form_instruction_page", $form->instructionPage() );
}
else
{
    if ( $ini->read_var( "eZFormMain", "UseDefaultInstructionPage" ) == "enabled" )
    {
        $t->set_var( "form_instruction_page", $ini->read_var( "eZFormMain", "DefaultInstructionPage" ) );
    }
}

if ( isset( $Action ) && $Action != "new" && $form->numberOfTypes() == 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
}

if ( $form->numberOfElements() == 0 )
{
    if ( $ini->read_var( "eZFormMain", "CreateEmailDefaults" ) == "enabled" )
    {
        $form->store();
        $FormID = $form->id();
        $elementTypeA = new eZFormElementType( 1 );
        $elementTypeB = new eZFormElementType( 2 );
        $elementA = new eZFormElement();
        $elementB = new eZFormElement();
        $name = $t->Ini->read_var( "strings", "subject_label" );
        $name = $t->Ini->read_var( "strings", "content_label" );
        $elementA->setName( $name );
        $elementB->setName( $name );
        $elementA->setElementType( $elementTypeA );
        $elementB->setElementType( $elementTypeB );
        $elementA->setRequired( true );
        $elementB->setRequired( true );
        $elementA->store();
        $elementB->store();
        $form->addElement( $elementA );
        $form->addElement( $elementB );
    }
    else
    {
        if ( isset( $Action ) && $Action != "new" && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
            $t->parse( "no_elements_item", "no_elements_item_tpl" );
    }
}

$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );
$t->set_var( "form_receiver", $form->receiver() );
$t->set_var( "form_cc", $form->cc() );
$t->set_var( "form_sender", $form->sender() );

if ( $form->isSendAsUser() )
{
    $t->set_var( "form_send_as_user", "1" );
    $t->set_var( "checked", "checked" );
}
else
{
    $t->set_var( "form_send_as_user", "0" );
}

$elements = $form->formElements();
$count = $form->numberOfElements();

if ( $count > 0 )
{
    $i = 0;
    foreach ( $elements as $element )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "element_name", $element->name() );
        $t->set_var( "element_id", $element->id() );

        $t->set_var( "element_size", $element->size() );
        
        if ( $element->isRequired() )
        {
            $t->set_var( "element_required", "checked" );
        }
        else
        {
            $t->set_var( "element_required", "" );
        }

        if ( $element->isBreaking() )
        {
            $t->set_var( "element_is_breaking", "checked" );
        }
        else
        {
            $t->set_var( "element_is_breaking", "" );
        }

        $currentType = $element->elementType();
        if( !is_null( $currentType  ) )
            $types = $currentType->getAll();
        else
            $types = array();

        $t->set_var( "fixed_values", "" );
        $t->set_var( "size", "" );
        $t->set_var( "typelist_item", "" );
                    $t->set_var( "break", "" );

        foreach ( $types as $type )
        {
            $t->set_var( "selected", "" );

            if ( $type->id() == $currentType->id() )
            {
                $name = $currentType->name();
                if ( $name == "multiple_select_item" ||
                     $name == "dropdown_item" ||
                     $name == "radiobox_item" ||
                     $name == "checkbox_item" )
                {
                    $t->parse( "fixed_values", "fixed_values_tpl" );
                }
                else
                    $t->set_var( "fixed_values", "" );
                    
                $t->set_var( "selected", "selected" );

                $t->set_var( "element_nr", $i );
                if ( $name == "text_field_item" )
                {
                    $t->parse( "size", "size_tpl" );
                    $t->parse( "break", "break_tpl" );
                }
                else
                    $t->set_var( "break", "" );
            }
            
            $t->set_var( "element_type_id", $type->id() );
            $t->set_var( "element_type_name", $type->name() );
            $t->parse( "typelist_item", "typelist_item_tpl", true );
        }
        
        $t->set_var( "item_move_up", "" );
        $t->set_var( "no_item_move_up", "" );
        $t->set_var( "item_move_down", "" );
        $t->set_var( "no_item_move_down", "" );
        $t->set_var( "item_separator", "" );
        $t->set_var( "no_item_separator", "" );

        if ( isSet( $move_item ) )
        {
            $t->parse( "item_move_up", "item_move_up_tpl" );
        }
        
        if ( isSet( $move_item ) )
        {
            $t->parse( "item_separator", "item_separator_tpl" );
        }
        
        if ( isSet( $move_item ) )
        {
            $t->parse( "item_move_down", "item_move_down_tpl" );
        }

        $t->parse( "element_item", "element_item_tpl", true );
        $i++;
    }
    
    $t->parse( "element_list", "element_list_tpl" );
}

if ( count( $errorMessages ) > 0 && !isSet( $NewElement ) && !isSet( $DeleteSelected ) )
{
    foreach ( $errorMessages as $errorMessage )
    {
        $errorMessage =& $t->Ini->read_var( "strings", $errorMessage );
        $t->set_var( "error_message", $errorMessage );
        $t->parse( "error_item", "error_item_tpl", true );
    }
    
    $t->set_var( "form_name", $formName );
    $t->set_var( "form_receiver", $formReceiver );
    $t->set_var( "form_cc", $formCC );
    $t->set_var( "form_completed_page", $formCompletedPage );
    $t->set_var( "form_sender", $formSender );
    
    if ( isSet( $formSendAsUser ) )
    {
        $t->set_var( "checked", "checked" );
    }
    $t->set_var( "form_instruction_page", $formInstructionPage );

    $t->parse( "error_list", "error_list_tpl" );
}

$t->parse( "form_item", "form_item_tpl" );

$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteDesign );
$t->setAllStrings();
$t->pparse( "output", "form_edit_page_tpl" );

?>