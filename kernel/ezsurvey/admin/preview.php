<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );
    
    $ini =& eZINI::instance( 'site.ini' );
    $Language = $ini->variable( "eZSurveyMain", "Language" );
    
    $SurveyID = $url_array[3];
    $Page = $url_array[4];

    $t = new eZTemplate( "kernel/ezsurvey/admin/" . $ini->variable( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/admin/intl", $Language, "preview.php" );
                         
    $t->set_file( "preview_tpl", "preview.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "preview_tpl", "subtitle_section_tpl", "subtitle_section" );
    
    $t->set_block( "preview_tpl", "value_list_tpl", "value_list" );
    $t->set_block( "value_list_tpl", "yesno_tpl", "yesno" );
    
    $t->set_block( "value_list_tpl", "radio_tpl", "radio" );
    $t->set_block( "radio_tpl", "radio_item_tpl", "radio_item" );
    $t->set_block( "radio_tpl", "radio_other_tpl", "radio_other" );
    
    $t->set_block( "value_list_tpl", "checkbox_tpl", "checkbox" );
    $t->set_block( "checkbox_tpl", "checkbox_item_tpl", "checkbox_item" );
    $t->set_block( "checkbox_tpl", "checkbox_other_tpl", "checkbox_other" );
    
    $t->set_block( "value_list_tpl", "rate_tpl", "rate" );
    $t->set_block( "rate_tpl", "rate_item_tpl", "rate_item" );
    
    $t->set_block( "value_list_tpl", "text_tpl", "text" );
    $t->set_block( "value_list_tpl", "essay_tpl", "essay" );
    $t->set_block( "value_list_tpl", "date_tpl", "date" );
    $t->set_block( "value_list_tpl", "numeric_tpl", "numeric" );
    
    $t->set_block( "value_list_tpl", "dropdown_tpl", "dropdown" );
    $t->set_block( "dropdown_tpl", "dropdown_item_tpl", "dropdown_item" );
    
    $t->set_block( "preview_tpl", "next_page_button_tpl", "next_page_button" );
    $t->set_block( "preview_tpl", "previous_page_button_tpl", "previous_page_button" );
    
    $t->set_var( "survey_id", $SurveyID );
    $t->set_var( "subtitle_section", "" );
    $t->set_var( "value_list", "" );
    
    if ( $Page == "" )
    {
        $Page = 1;
    }
    
    if ( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /survey/surveyedit/edit/$SurveyID" );
        exit();
    }
    
    if ( isset( $NextPage ) )
    {
        $Page++;
    }
    if ( isset( $PreviousPage ) )
    {
        $Page--;
    }
    
    $survey = new eZSurvey( $SurveyID );
    
    $t->set_var( "title", $survey->title() );
    
    if ( $survey->subTitle() != "" )
    {
        $t->set_var( "subtitle", $survey->subTitle() );
        $t->parse( "subtitle_section", "subtitle_section_tpl" );
    }
    
    $t->set_var( "info", $survey->info() );
    
    $totalPages = $survey->numberOfPages();
    $t->set_var( "page_number", $Page );
    $t->set_var( "page_total", $totalPages );
    
    $question_array = $survey->surveyQuestions( "Position", $Page );
    
    $position = 0;
    foreach ( $question_array as $questionItem )
    {
        // limpar as vari�veis
        $t->set_var( "yesno", "" );
        $t->set_var( "checkbox", "" );
        $t->set_var( "radio", "" );
        $t->set_var( "dropdown", "" );
        $t->set_var( "rate", "" );
        $t->set_var( "text", "" );
        $t->set_var( "essay", "" );
        $t->set_var( "date", "" );
        $t->set_var( "numeric", "" );
        
        $t->set_var( "id", $position++ );
        
        $t->set_var( "question_name", $questionItem->content() );
    
        if ( $questionItem->hasChoices() )
        {
            $questionChoice_array = $questionItem->questionChoices();
        }
        
        // apresentar default
        switch ( $questionItem->questionTypeID() )
        {
            // Yes/No
            case "1":
            {
                if ( $questionItem->initial() == "Y" )
                {
                    $t->set_var( "selected_y", "checked" );
                    $t->set_var( "selected_n", "" );
                }
                else
                {
                    $t->set_var( "selected_y", "" );
                    $t->set_var( "selected_n", "checked" );
                }
                
                $t->parse( "yesno", "yesno_tpl" );
            }
            break;
            
            // Texto
            case "2":
            {
                $t->set_var( "size", $questionItem->length() );
                $t->set_var( "default", $questionItem->initial() );
                $t->parse( "text", "text_tpl" );
            }
            break;
            
            // Essay
            case "3":
            {
                $t->set_var( "default", $questionItem->initial() );
                $t->parse( "essay", "essay_tpl" );
            }
            break;
            
            // radio
            case "4":
            {
                $i = 0;
                foreach( $questionChoice_array as $questionChoiceItem )
                {
                    // default
                    if ( $questionItem->initial() == $questionChoiceItem->id() )
                        $t->set_var( "selected", "checked" );
                    else
                        $t->set_var( "selected", "" );
                        
                    $t->set_var( "value", $questionChoiceItem->id() );
                    $t->set_var( "value_name", $questionChoiceItem->content() );
                    
                    
                    // other
                    if ( $questionChoiceItem->isOther() )
                    {
                        $t->set_var( "radio_item", "" );
                        $t->parse( "radio_other", "radio_other_tpl" );
                    }
                    else
                    {
                        $t->set_var( "radio_other", "" );
                        $t->parse( "radio_item", "radio_item_tpl" );
                    }
                    
                    $t->parse( "radio", "radio_tpl", true );
                }
            }
            break;
            
            // checkbox
            case "5":
            {
                $i = 0;
                $default = explode( ";", $questionItem->initial() );
                foreach( $questionChoice_array as $questionChoiceItem )
                {
                    // default
                    if ( in_array( $questionChoiceItem->id(), $default ) )
                        $t->set_var( "selected", "checked" );
                    else
                        $t->set_var( "selected", "" );
                        
                    $t->set_var( "value", $questionChoiceItem->id() );
                    $t->set_var( "value_name", $questionChoiceItem->content() );
                    
                    // other
                    if ( $questionChoiceItem->isOther() )
                    {
                        $t->set_var( "checkbox_item", "" );
                        $t->parse( "checkbox_other", "checkbox_other_tpl" );
                    }
                    else
                    {
                        $t->set_var( "checkbox_other", "" );
                        $t->parse( "checkbox_item", "checkbox_item_tpl" );
                    }
                    
                    $t->parse( "checkbox", "checkbox_tpl", true );
                }
            }
            break;
            
            // dropdown
            case "6":
            {
                $i = 0;
                // � opcional
                if ( ! $questionItem->isRequired() )
                {
                    $t->set_var( "value", "" );
                    $t->set_var( "value_name", "" );
                    $t->parse( "dropdown_item", "dropdown_item_tpl", true );
                }
                
                foreach( $questionChoice_array as $questionChoiceItem )
                {
                    // default
                    if ( $questionItem->initial() == $questionChoiceItem->id() )
                        $t->set_var( "selected", "selected" );
                    else
                        $t->set_var( "selected", "" );
                        
                    $t->set_var( "value", $questionChoiceItem->id() );
                    $t->set_var( "value_name", $questionChoiceItem->content() );
                    $t->parse( "dropdown_item", "dropdown_item_tpl", true );
                }
                $t->parse( "dropdown", "dropdown_tpl" );
            }
            break;
            
            // rate
            case "8":
            {
                $i = 0;
		        $t->set_var( "rate_item", "" );
                foreach( $questionChoice_array as $questionChoiceItem )
                {
                    $t->set_var( "value_name", $questionChoiceItem->content() );
                    $t->set_var( "id2", $i++ );
                    $t->parse( "rate_item", "rate_item_tpl", true );
                }
                $t->parse( "rate", "rate_tpl" );
            }
            break;
            
            // Data
            case "9":
            {
                $t->set_var( "size", $questionItem->length() );
                $t->parse( "date", "date_tpl" );
            }
            break;
            
            // Num�rico
            case "10":
            {
                $t->set_var( "size", $questionItem->length() );
                $t->set_var( "default", $questionItem->initial() );
                $t->parse( "numeric", "numeric_tpl" );
            }
            break;
        }
        
        $t->parse( "value_list", "value_list_tpl", true );
    }
  
    // Next page button
    if ( $Page < $totalPages )
        $t->parse( "next_page_button", "next_page_button_tpl" );
    else
        $t->set_var( "next_page_button", "" );
        
    // Previous page button
    if ( $Page > 1 )
        $t->parse( "previous_page_button", "previous_page_button_tpl" );
    else
        $t->set_var( "previous_page_button", "" );
  
    $t->pparse( "output", "preview_tpl" );
    
?>