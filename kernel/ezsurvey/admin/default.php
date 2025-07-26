<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );
    
    $ini =& INIFile::globalINI();
    $Language = $ini->read_var( "eZSurveyMain", "Language" );
    
    $t = new eZTemplate( "kernel/ezsurvey/admin/" . $ini->read_var( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/admin/intl", $Language, "default.php" );
                         
    $t->set_file( "default_tpl", "default.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "default_tpl", "value_list_tpl", "value_list" );
    $t->set_block( "value_list_tpl", "yesno_tpl", "yesno" );
    $t->set_block( "value_list_tpl", "radio_tpl", "radio" );
    $t->set_block( "value_list_tpl", "checkbox_tpl", "checkbox" );
    $t->set_block( "value_list_tpl", "text_tpl", "text" );
    $t->set_block( "value_list_tpl", "essay_tpl", "essay" );
    $t->set_block( "value_list_tpl", "numeric_tpl", "numeric" );
    
    $t->set_block( "value_list_tpl", "dropdown_tpl", "dropdown" );
    $t->set_block( "dropdown_tpl", "dropdown_item_tpl", "dropdown_item" );
    
    $SurveyID = $url_array[3];
    $QuestionID = $url_array[4];
    
    $t->set_var( "survey_id", $SurveyID );
    $t->set_var( "question_id", $QuestionID );
    
    $t->set_var( "yesno", "" );
    $t->set_var( "checkbox", "" );
    $t->set_var( "radio", "" );
    $t->set_var( "dropdown", "" );
    $t->set_var( "text", "" );
    $t->set_var( "essay", "" );
    $t->set_var( "numeric", "" );
    
    $question = new eZQuestion( $QuestionID );
    
    // retira default
    if ( isset( $Clear ) )
    {
        $question->setInitial( "" );
        $question->store();
    }
    
    // gravar altera��es
    if ( (isset( $Store ) || isset( $OK )) && isset( $Value ) )
    {
        switch ( $question->questionTypeID() )
        {
            case $question->TYPE_YESNO:
            case $question->TYPE_TEXTBOX:
            case $question->TYPE_TEXTAREA:
            case $question->TYPE_RADIO:
            case $question->TYPE_DROPDOWN:
            case $question->TYPE_NUMERIC:
            {
                $question->setInitial( $Value[0] );
                $question->store();
            }
            break;
            
            case $question->TYPE_CHECKBOX:
            {
                $question->setInitial( implode( ";", $Value ) );
                $question->store();
            }
            break;
        }
    }
    
    if( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /survey/surveyedit/edit/$SurveyID" );
        exit();
    }
    
    if ( $question->hasChoices() )
    {
        $questionChoice_array = $question->questionChoices();
    }
    
    // apresentar default
    switch ( $question->questionTypeID() )
    {
        // Yes/No
        case $question->TYPE_YESNO:
        {
            $t->set_var( "selected_n", "" );
            $t->set_var( "selected_y", "" );
            
            if ( $question->initial() == "Y" )
            {
                $t->set_var( "selected_y", "checked" );
            }
            elseif ( $question->initial() == "N" )
            {
                $t->set_var( "selected_n", "checked" );
            }
            
            $t->parse( "yesno", "yesno_tpl" );
        }
        break;
        
        // Texto
        case $question->TYPE_TEXTBOX:
        {
            $t->set_var( "size", $question->length() );
            $t->set_var( "default", $question->initial() );
            $t->parse( "text", "text_tpl" );
        }
        break;
        
        // Essay
        case $question->TYPE_TEXTAREA:
        {
            $t->set_var( "default", $question->initial() );
            $t->parse( "essay", "essay_tpl" );
        }
        break;
        
        // radio
        case $question->TYPE_RADIO:
        {
            $i = 0;
            foreach( $questionChoice_array as $questionItem )
            {
                if ($i++ % 2)
                    $t->set_var( "td_class", "bgdark" );
                else
                    $t->set_var( "td_class", "bglight" );
                
                // default
                if ( $question->initial() == $questionItem->id() )
                    $t->set_var( "selected", "checked" );
                else
                    $t->set_var( "selected", "" );
                    
                $t->set_var( "value", $questionItem->id() );
                $t->set_var( "value_name", $questionItem->content() );
                $t->parse( "radio", "radio_tpl", true );
            }
        }
        break;
        
        // checkbox
        case $question->TYPE_CHECKBOX:
        {
            $i = 0;
            $default = explode( ";", $question->initial() );
            foreach( $questionChoice_array as $questionItem )
            {
                if ($i++ % 2)
                    $t->set_var( "td_class", "bgdark" );
                else
                    $t->set_var( "td_class", "bglight" );
                    
                // default
                if ( in_array( $questionItem->id(), $default ) )
                    $t->set_var( "selected", "checked" );
                else
                    $t->set_var( "selected", "" );
                    
                $t->set_var( "value", $questionItem->id() );
                $t->set_var( "value_name", $questionItem->content() );
                $t->parse( "checkbox", "checkbox_tpl", true );
            }
        }
        break;
        
        // dropdown
        case $question->TYPE_DROPDOWN:
        {
            $i = 0;
            // � opcional
            if ( ! $question->isRequired() )
            {
                $t->set_var( "value", "" );
                $t->set_var( "value_name", "" );
                $t->parse( "dropdown_item", "dropdown_item_tpl", true );
            }
            
            foreach( $questionChoice_array as $questionItem )
            {
                if ($i++ % 2)
                    $t->set_var( "td_class", "bgdark" );
                else
                    $t->set_var( "td_class", "bglight" );
                    
                // default
                if ( $question->initial() == $questionItem->id() )
                    $t->set_var( "selected", "selected" );
                else
                    $t->set_var( "selected", "" );
                    
                $t->set_var( "value", $questionItem->id() );
                $t->set_var( "value_name", $questionItem->content() );
                $t->parse( "dropdown_item", "dropdown_item_tpl", true );
            }
            $t->parse( "dropdown", "dropdown_tpl" );
        }
        break;
        
        // Num�rico
        case $question->TYPE_NUMERIC:
        {
            $t->set_var( "size", $question->length() );
            $t->set_var( "value", $question->initial() );
            $t->parse( "numeric", "numeric_tpl" );
        }
        break;
    }
    
    $t->parse( "value_list", "value_list_tpl" );
    
    $t->pparse( "output", "default_tpl" );
    
?>