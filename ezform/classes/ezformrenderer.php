<?php
//
// $Id: ezformrenderer.php 9785 2003-02-28 08:31:05Z br $
//
// eZFormRenderer class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! eZForm
//! eZFormRenderer documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );


class eZFormRenderer
{

    /*!
      Constructs a new eZFormRenderer object.
    */
    function __construct( $form = "" )
    {
        $ini =& INIFile::globalINI();
        if ( is_a( $form, "eZForm" ) )
        {
            $this->Form = $form;
        }
        $Language = $ini->read_var( "eZFormMain", "Language" );

        $this->Template = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                                          "ezform/admin/intl/", $Language, "form.php" );

        $this->Template->setAllStrings();

        $this->Template->set_file( "form_renderer_page_tpl", "formrenderer.tpl" );

        $this->Template->set_block( "form_renderer_page_tpl", "text_field_item_tpl", "text_field_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_area_item_tpl", "text_area_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "multiple_select_item_tpl", "multiple_select_item" );
        $this->Template->set_block( "multiple_select_item_tpl", "multiple_select_item_sub_item_tpl", "multiple_select_item_sub_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "dropdown_item_tpl", "dropdown_item" );
        $this->Template->set_block( "dropdown_item_tpl", "dropdown_item_sub_item_tpl", "dropdown_item_sub_item" );

        $this->Template->set_block( "form_renderer_page_tpl", "radiobox_item_tpl", "radiobox_item" );
        $this->Template->set_block( "radiobox_item_tpl", "radiobox_item_sub_item_tpl", "radiobox_item_sub_item" );

        $this->Template->set_block( "form_renderer_page_tpl", "checkbox_item_tpl", "checkbox_item" );
        $this->Template->set_block( "checkbox_item_tpl", "checkbox_item_sub_item_tpl", "checkbox_item_sub_item" );

        $this->Template->set_block( "form_renderer_page_tpl", "form_list_tpl", "form_list" );
        $this->Template->set_block( "form_list_tpl", "form_item_tpl", "form_item" );
        $this->Template->set_block( "form_item_tpl", "break_tpl", "break" );
        $this->Template->set_block( "form_list_tpl", "form_start_tag_tpl", "form_start_tag" );
        $this->Template->set_block( "form_list_tpl", "form_end_tag_tpl", "form_end_tag" );
        $this->Template->set_block( "form_list_tpl", "form_buttons_tpl", "form_buttons" );
        $this->Template->set_block( "form_list_tpl", "form_sender_tpl", "form_sender" );
        $this->Template->set_block( "form_list_tpl", "form_instructions_tpl", "form_instructions" );
        $this->Template->set_block( "form_renderer_page_tpl", "error_list_tpl", "error_list" );
        $this->Template->set_block( "error_list_tpl", "error_item_tpl", "error_item" );

        $this->Template->set_var( "error_list", "" );
        $this->Template->set_var( "error_item", "" );
        $this->Template->set_var( "form_start_tag", "" );
        $this->Template->set_var( "form_sender", "" );
        $this->Template->set_var( "form_end_tag", "" );
        $this->Template->set_var( "form_buttons", "" );
        $this->Template->set_var( "form_list", "" );
        $this->Template->set_var( "form_item", "" );
        $this->Template->set_var( "text_field_item", "" );
        $this->Template->set_var( "text_area_item", "" );
        $this->Template->set_var( "form_instructions", "" );
        $this->Template->set_var( "form_sender_value", "" );


        global $GlobalSectionID, $SectionIDOverride;

        if ( isset( $SectionIDOverride ) )
        {
            $this->Template->set_var( "section_id", $SectionIDOverride );
        }
        else
        {
            $this->Template->set_var( "section_id", $GlobalSectionID );
        }

    }

    /*!
        Renders the element which are given as an argument based on its type.
     */
    function &renderElement( $element )
    {
        $output = "";
        $target = '';
        if ( is_a( $element, "eZFormElement" ) )
        {
            $subItems =& $element->fixedValues();
            $this->Template->set_var( "sub_item", "" );

            $type =& $element->elementType();

            $name = $type->name();

            $elementType = $element->elementType();
            $type = $elementType->name();
            $type = str_replace( " ", "_", $type );

            $elementName = "eZFormElement_" . $element->id();

            $elementValue = '';
            global $$elementName;
            if ( isSet( $$elementName ) )
                $elementValue = $$elementName;

            $this->Template->set_var( "field_name", $elementName );
            $this->Template->set_var( "field_value", $elementValue );

            if ( $element->size() == 0 )
            {
                $this->Template->set_var( "element_size", 40 );
            }
            else
            {
                $this->Template->set_var( "element_size", $element->size() );
            }

            if ( $element->isBreaking() )
            {
                $this->Template->set_var( "break", "<br>" );
            }
            else
            {
                $this->Template->set_var( "break", "" );
            }

            $this->Template->set_var( $name . "_sub_item", "" );

            foreach ( $subItems as $subItem )
            {
                $this->Template->set_var( "sub_value", $subItem->value() );
                $this->Template->parse( $name . "_sub_item", $name . "_sub_item_tpl", true );
            }

            if( !is_null( $$elementName ) )
            {
                $elementValue = str_replace( "eZFormElement_", "", $$elementName );
            }
            else
            {
                $elementValue = $elementName;
            }

            if ( trim( $type ) != "" )
                $output =& $this->Template->parse( $target, $type . "_tpl" );
        }

        return $output;
    }

    /*!
        Renders a form
     */
    function &renderForm( $form = "", $addFormTags = true, $addButtons = true )
    {
        $output = "";
        $render = false;
        $target = '';

        if ( is_a( $form, "eZForm" ) )
        {
            $this->Form =& $form;
            $render = true;
        }
        else
        {
            if ( is_a( $this->Form, "eZForm" ) )
            {
                $render = true;
            }
        }

        if ( $render == true )
        {
            $elements =& $this->Form->formElements();

            $elementCounter = 0;

            $this->Template->set_var( "form_id", $this->Form->id() );
            $this->Template->set_var( "form_name", $this->Form->name() );
            $this->Template->set_var( "form_completed_page", $this->Form->completedPage() );
            if ( $this->Form->instructionPage() != "" )
            {
                $this->Template->set_var( "form_instruction_page", $this->Form->instructionPage() );
                $this->Template->parse( "form_instructions", "form_instructions_tpl" );
            }

            $maxBreakCount = 1;
            $breakCount = 1;
            $lastBreaked = true;
            // count the max number of unbreaked elements
            foreach ( $elements as $element )
            {
                $eType = $element->elementType();

                if ( $element->isBreaking() or ( $eType->name() != "text_field_item" ) )
                {
                    $lastBreaked = true;
                    $breakCount = 1;
                }
                else
                {
                    $lastBreaked = false;
                }

                if ( $lastBreaked == false )
                {
                    $breakCount++;
                    $maxBreakCount = max( $maxBreakCount, $breakCount );
                }
            }

            foreach ( $elements as $element )
            {
                $elementCounter++;

                $eType = $element->elementType();

                $output = $this->renderElement( $element );

                $this->Template->set_var( "element", $output );
                $this->Template->set_var( "element_name", $element->name() );


                if ( $eType->name() != "text_field_item" )
                    $this->Template->set_var( "colspan", " colspan=\"$maxBreakCount\"" );
                else
                    $this->Template->set_var( "colspan", " colspan=\"1\"" );


                if ( ( $eType->name() != "text_field_item" ) or $element->isBreaking() )
                {
                    $this->Template->parse( "break", "break_tpl" );
                }
                else
                {
                    $this->Template->set_var( "break", "" );
                }
                $this->Template->parse( "form_item", "form_item_tpl", true );
            }


            if ( $form->isSendAsUser() )
            {
                global $formSender;
                if ( $formSender )
                {
                    $this->Template->set_var( "form_sender_value", $formSender );
                }
                else
                {
                    if ( $user =& eZUser::currentUser() )
                    {
                        $this->Template->set_var( "form_sender", $user->eMail() );
                    }
                    else
                    {
                        $this->Template->set_var( "form_sender", "" );
                    }
                }
                $this->Template->parse( "form_sender", "form_sender_tpl" );
            }

            if ( $elementCounter != 0 )
            {
                if ( $addFormTags == true )
                {
                    $this->Template->parse( "form_start_tag", "form_start_tag_tpl" );
                    $this->Template->parse( "form_end_tag", "form_end_tag_tpl" );
                }

                if ( $addButtons == true )
                {
                    $this->Template->parse( "form_buttons", "form_buttons_tpl" );
                }
                $output = $this->Template->parse( $target, "form_list_tpl" );
            }
        }

        return $output;
    }

    /*!
        This function verifies the data from a posted form
     */
    function verifyForm()
    {
        global $FormID;
        global $formName;
        global $formSender;
        global $redirectTo;

        $output = "";
        $errorMessages = array();
        $errorMessagesAdditionalInfo = array();
        $ini =& INIFile::globalINI();

        $this->Form = new eZForm( $FormID );

        $elements = $this->Form->formElements();

        if ( $this->Form->isSendAsUser() )
        {
            if ( isset( $formSender ) )
            {
                if ( eZMail::validate( $formSender ) == false )
                {
                    $errorMessages[] = "form_sender_not_valid";
                    $errorMessagesAdditionalInfo[] = "";
                }
            }
            else
            {
                $errorMessages[] = "form_sender_missing";
                $errorMessagesAdditionalInfo[] = "";
            }
        }

        foreach ( $elements as $element )
        {
            $elementName = "eZFormElement_" . $element->id();

            global $$elementName;

            $value = $$elementName;

            if ( $element->isRequired() == true )
            {
                if ( empty( $value ) )
                {
                     $errorMessages[] = "required_field";
                     $errorMessagesAdditionalInfo[] = "\"" .  $element->name() . "\"" ;
                }
            }
        }

        if ( count( $errorMessages ) > 0 )
        {
            $i = 0;
            foreach ( $errorMessages as $errorMessage )
            {
                $errorMessage = $this->Template->Ini->read_var( "strings", $errorMessage );
                $this->Template->set_var( "error_message", $errorMessage );
                $this->Template->set_var( "error_value", $errorMessagesAdditionalInfo[$i] );
                $this->Template->parse( "error_item", "error_item_tpl", true );
                $i++;
            }

            $output = $this->Template->parse( $target, "error_list_tpl" );
        }

        return $output;
    }

    /*!
        This function will send the form to the recipients.
     */
    function sendForm()
    {
        global $FormID;
        global $formName;
        global $formSender;
        global $redirectTo;

        $formSent = false;

        $form = new eZForm( $FormID );

        $ini =& INIFile::globalINI();
        $Language = $ini->read_var( "eZFormMain", "Language" );

        $emailDefaults = false;

        if ( $ini->read_var( "eZFormMain", "CreateEmailDefaults" ) == "enabled" )
        {
            $emailDefaults = true;
        }

        $elements = $form->formElements();

        $mail = new eZMail();

        $content = "";

        foreach ( $elements as $element )
        {
            $elementName = "eZFormElement_" . $element->id();

            global $$elementName;
            $value = $$elementName;


            // convert array to multiple textlines.
            $tmpValue = "";
            if ( is_array( $value ) )
            {
                foreach ( $value as $v )
                {
                    $tmpValue .=  $v . "\n";
                }
                $value = $tmpValue;
            }

            if ( $emailDefaults == true )
            {
                if ( $element->name() == $this->Template->Ini->read_var( "strings", "subject_label" ) )
                {
                    $mail->setSubject( $value );
                }
                else
                {
                    $content .= $element->name() . ":\n " . $value . "\n\n";
                }
            }
            else
            {
                $content .= $element->name() . ":\n " . $value . "\n\n";
            }
        }

        if ( $emailDefaults == false )
        {
            $mail->setSubject( $form->name() );
        }

        $t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "form.php" );

        $t->set_file( array(
            "form_mail_tpl" => "emailtemplate.tpl"
            ) );


        $t->set_var( "content", $content );

        $t->setAllStrings();

        $formatedContent = $t->parse( $target, "form_mail_tpl" );

        $mail->setBody( $formatedContent );

        if ( $form->isSendAsUser() )
        {
            $mail->setFrom( $formSender );
            $mail->setCC( $form->CC() . ", " . $formSender );
        }
        else
        {
            $mail->setFrom( $form->sender() );
            $mail->setCC( $form->CC() . ", " . $form->sender() );
        }

        $mail->setTo( $form->receiver() );

        $mail->send();
        $formSent = true;

        if ( $formSent )
        {
            if ( $form->completedPage() == "" )
            {
                eZHTTPTool::header( "Location: /" );
            }
            else
            {
                $redirectTo = $form->completedPage();
                eZHTTPTool::header( "Location: $redirectTo" );
            }
            exit();
        }
    }

    var $Template;
    var $Form;
}

?>