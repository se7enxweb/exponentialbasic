<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );
    // include_once( "ezsurvey/classes/ezresponse.php" );
    // include_once( "ezsurvey/classes/ezresponsequestion.php" );
    
    $ini = eZINI::instance( 'site.ini' );
    $Language = $ini->variable( "eZSurveyMain", "Language" );
    
    $SurveyID = $url_array[3];
    $Page = $url_array[4];
     
    if ( $Page == "" )
    {
        $Page = 1;
    }

    if( !isset( $ResponseID ) )
    {
        $ResponseID = false;
    }

    $t = new eZTemplate( "kernel/ezsurvey/user/" . $ini->variable( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/user/intl", $Language, "surveylist.php" );
                         
    $t->set_file( "surveylist_tpl", "surveylist.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "surveylist_tpl", "step_tpl", "step" );
    $t->set_block( "step_tpl", "step_on_tpl", "step_on" );
    $t->set_block( "step_tpl", "step_off_tpl", "step_off" );

    $t->set_block( "surveylist_tpl", "already_responded_tpl", "already_responded" );
    
    $t->set_block( "surveylist_tpl", "subtitle_section_tpl", "subtitle_section" );

    $t->set_block( "surveylist_tpl", "check_value_tpl", "check_value" );
    
    $t->set_block( "surveylist_tpl", "error_list_tpl", "error_list" );
    $t->set_block( "error_list_tpl", "error_item_tpl", "error_item" );
    
    $t->set_block( "surveylist_tpl", "value_list_tpl", "value_list" );
    $t->set_block( "value_list_tpl", "required_tpl", "required" );
    $t->set_block( "value_list_tpl", "qnumber_tpl", "qnumber" );
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
    
    $t->set_block( "surveylist_tpl", "next_page_button_tpl", "next_page_button" );
    $t->set_block( "surveylist_tpl", "previous_page_button_tpl", "previous_page_button" );
    $t->set_block( "surveylist_tpl", "finish_button_tpl", "finish_button" );
    
    $t->set_var( "survey_id", $SurveyID );
    $t->set_var( "step", "" );
    $t->set_var( "subtitle_section", "" );
    $t->set_var( "value_list", "" );
    
    $t->set_var( "next_page_button", "" );
    $t->set_var( "previous_page_button", "" );
    $t->set_var( "finish_button", "" );
    
    $t->set_var( "error_list", "" );
        
    $currentUser = eZUser::currentUser();
    $errorMessages = array();
    
    if ( eZSurvey::existSurvey( $SurveyID ) )
    {
        if ( isset( $NextPage ) || isset( $PreviousPage ) || isset( $Finish ) )
        {
            // response
            if ( $ResponseID == "" )
            {
                $response = new eZResponse();
                $response->setSurveyID( $SurveyID );
                
                if ( $currentUser != FALSE )
                {
                    $response->setUserID( $currentUser->id() );
                }
            }
            else
            {
                $response = new eZResponse( $ResponseID );
            }
            
            $response->store();
            $ResponseID = $response->id();
            
            $i = 0;
            foreach( $QuestionID as $questionIDItem )
            {
                $question = new eZQuestion( $questionIDItem );
                
                switch ( $question->questionTypeID() )
                {
                    case $question->TYPE_YESNO:
                    case $question->TYPE_TEXTBOX:
                    case $question->TYPE_TEXTAREA:
                    case $question->TYPE_DROPDOWN:
                    {
                        if ( $question->isRequired() && $Value[$i] == "" )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        else
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            $responseQuestion->setChoiceID( $Value[$i] );
                            $responseQuestion->store();
                        }
                    }
                    break;
                    
                    case $question->TYPE_NUMERIC:
                    {
                        if ( $question->isRequired() && $Value[$i] == "" )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        elseif ( !is_numeric( $Value[$i] ) )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "not_a_number" );
                        }
                        else
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            $responseQuestion->setChoiceID( $Value[$i] );
                            $responseQuestion->store();
                        }
                    }
                    break;
                    
                    case $question->TYPE_DATE:
                    {
                        $data_array = explode( "-", $Value[$i] );
                        
                        if ( $question->isRequired() && $Value[$i] == "" )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        elseif ( $Value[$i] != "" && ( count($data_array) != "3" || !checkdate( $data_array[1], $data_array[0], $data_array[2] ) ) )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "not_a_date" );
                        }
                        else
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            $responseQuestion->setChoiceID( $Value[$i] );
                            $responseQuestion->store();
                        }
                    }
                    break;
                    
                    case $question->TYPE_RADIO:
                    {
                        if ( $question->isRequired() && count($Value[$i][0]) == 0 )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        else
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            $responseQuestion->setChoiceID( $Value[$i][0] );
                            $responseQuestion->setOther( $Value[$i][1] );
                            $responseQuestion->store();
                        }
                    }
                    break;
                    
                    case $question->TYPE_CHECKBOX:
                    {
                        if ( $question->isRequired() && count($Value[$i][0]) == 0 )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        else
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            
                            $k = 0;
                            for ( $j = 0; $j < count($Value[$i][0]); $j++ )
                            {
                                $questionChoice = new eZQuestionChoice( $Value[$i][0][$j] );
                                
                                $choice_array[] = $Value[$i][0][$j];
                                $other_array[] = ( $questionChoice->isOther() ? $Value[$i][1][$k++] : "" );
                            }
                            
                            $responseQuestion->setChoiceID( $choice_array );
                            $responseQuestion->setOther( $other_array );
                            
                            $responseQuestion->store();
                        }
                    }
                    break;
                    
                    case $question->TYPE_RATE:
                    {
                        if ( $question->isRequired() && count($Value[$i]) != count($Rank[$i]) )
                        {
                            $errorMessages[] = $question->content() . " " . $t->Ini->variable( "strings", "is_required" );
                        }
                        elseif ( count($Value[$i]) > 0 )
                        {
                            $responseQuestion = new eZResponseQuestion();
                            $responseQuestion->setResponseID( $ResponseID );
                            $responseQuestion->setQuestionID( $questionIDItem );
                            $responseQuestion->setChoiceID( $Rank[$i] );
                            $responseQuestion->setOther( $Value[$i] );
                            $responseQuestion->store();
                        }
                    }
                    break;
                }
                $i++;
            }
            
            if ( isset( $Finish ) && count($errorMessages) == 0 )
            {
                $response->setComplete( 'Y' );
                $response->store();
                $response->sendMail();
                eZHTTPTool::header( "Location: /survey/thanks/show/$SurveyID" );
                exit();
            }
        }
        
        // page count
        if ( count( $errorMessages ) == 0 )
        {
            if ( isset( $NextPage ) )
            {
                $Page++;
            }
            if ( isset( $PreviousPage ) )
            {
                $Page--;
            }
        }
        
        // lista perguntas
        $survey = new eZSurvey( $SurveyID );
        
        if ( is_object( $currentUser ) && $survey->hasCompleted( $currentUser->id() ) )
        {
            $t->set_var( "title", "" );
            $t->set_var( "info", "" );
            $t->parse( "already_responded", "already_responded_tpl" );
        }
        else
        {
            $t->set_var( "already_responded", "" );
            $t->set_var( "page_number", $Page );
            $t->set_var( "check_value", "" );
            // Title
            $t->set_var( "title", $survey->title() );
            
            // SubTitle
            if ( $survey->subTitle() != "" )
            {
                $t->set_var( "subtitle", $survey->subTitle() );
                $t->parse( "subtitle_section", "subtitle_section_tpl" );
            }
            
            // Informa��o adicional
            $t->set_var( "info", $survey->info() );
            
            // Pagina��o
            $totalPages = $survey->numberOfPages();
            
            for ( $i = 1; $i <= $totalPages; $i++ )
            {
                $t->set_var( "page", $i );
                if ( $Page == $i )
                {
                    $t->parse( "step_on", "step_on_tpl" );
                    $t->set_var( "step_off", "" );
                }
                else
                {
                    $t->parse( "step_off", "step_off_tpl" );
                    $t->set_var( "step_on", "" );
                }
                $t->parse( "step", "step_tpl", true );
            }
            
            $question_array = $survey->surveyQuestions( "Position", $Page );
            
            $position = 0;
            
            foreach ( $question_array as $questionItem )
            {
                // limpar as vari�veis
                $t->set_var( "qnumber", "" );
                $t->set_var( "required", "" );
                $t->set_var( "yesno", "" );
                $t->set_var( "checkbox", "" );
                $t->set_var( "radio", "" );
                $t->set_var( "dropdown", "" );
                $t->set_var( "rate", "" );
                $t->set_var( "text", "" );
                $t->set_var( "essay", "" );
                $t->set_var( "date", "" );
                $t->set_var( "numeric", "" );
                
                // question id
                $t->set_var( "question_id", $questionItem->id() );
                
                $t->set_var( "id", $position );
                
                $t->set_var( "question_name", $questionItem->content() );
                $t->set_var( "question_num", $questionItem->questionNumber() );
                
                // required
                if ( $questionItem->isRequired() )
                {
                    $t->parse( "required", "required_tpl" );
                }
                
                if ( $questionItem->hasChoices() )
                {
                    $questionChoice_array = $questionItem->questionChoices();
                }
                
                $responseQuestion = new eZResponseQuestion( $ResponseID, $questionItem->id() );
                $responded = $responseQuestion->choiceID();
                $respondedOther = $responseQuestion->other();
                
                // listar perguntas
                switch ( $questionItem->questionTypeID() )
                {
                    // Yes/No
                    case $questionItem->TYPE_YESNO:
                    {
                        $t->set_var( "selected_n", "" );
                        $t->set_var( "selected_y", "" );
                        
                        if ( $questionItem->isRequired() )
                        {
                            $t->set_var( "element_item", "Value[$position]" );
                            $t->parse( "check_value", "check_value_tpl", true );
                        }
                        
                        // valor anteriormente respondido
                        if ( $responded == "Y" )
                        {
                            $t->set_var( "selected_y", "checked" );
                        }
                        elseif ( $responded == "N" )
                        {
                            $t->set_var( "selected_n", "checked" );
                        }
                        // default
                        elseif ( $questionItem->initial() == "Y" )
                        {
                            $t->set_var( "selected_y", "checked" );
                        }
                        elseif ( $questionItem->initial() == "N" )
                        {
                            $t->set_var( "selected_n", "checked" );
                        }
                        
                        $t->parse( "yesno", "yesno_tpl" );
                    }
                    break;
                    
                    // Texto
                    case $questionItem->TYPE_TEXTBOX:
                    {
                        if ( $questionItem->isRequired() )
                        {
                            $t->set_var( "element_item", "Value[$position]" );
                            $t->parse( "check_value", "check_value_tpl", true );
                        }
                        
                        $t->set_var( "size", $questionItem->length() );
                        
                        if ( isset( $responded ) )
                        {
                            $t->set_var( "default", $responded );
                        }
                        else
                        {
                            $t->set_var( "default", $questionItem->initial() );
                        }
                        
                        $t->parse( "text", "text_tpl" );
                    }
                    break;
                    
                    // Essay
                    case $questionItem->TYPE_TEXTAREA:
                    {
                        $t->set_var( "size", $questionItem->length() );
                        
                        if ( $questionItem->isRequired() )
                        {
                            $t->set_var( "element_item", "Value[$position]" );
                            $t->parse( "check_value", "check_value_tpl", true );
                        }
                        
                        if ( isset( $responded ) )
                        {
                            $t->set_var( "default", $responded );
                        }
                        else
                        {
                            $t->set_var( "default", $questionItem->initial() );
                        }
                        $t->parse( "essay", "essay_tpl" );
                    }
                    break;
                    
                    // radio
                    case $questionItem->TYPE_RADIO:
                    {
                        $i = 0;
                        
                        $t->set_var( "size", $questionItem->length() );
                        
                        if ( $questionItem->isRequired() )
                        {
                            $t->set_var( "element_item", "Value[$position][0]" );
                            $t->parse( "check_value", "check_value_tpl", true );
                        }
                        
                        foreach( $questionChoice_array as $questionChoiceItem )
                        {
                            $t->set_var( "selected", "" );
                            
                            // default
                            if ( $questionItem->initial() == $questionChoiceItem->id() && $responded == "" )
                            {
                                $t->set_var( "selected", "checked" );
                                $t->set_var( "radio_other", "" );
                            }
                            // valor anteriormente respondido
                            elseif ( $responded == $questionChoiceItem->id() )
                            {
                                $t->set_var( "selected", "checked" );
                                $t->set_var( "radio_other", $respondedOther );
                            }
                            
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
                    case $questionItem->TYPE_CHECKBOX:
                    {
                        if ( $questionItem->isRequired() )
                        {
                            $t->set_var( "element_item", "Value[$position][0][]" );
                            $t->parse( "check_value", "check_value_tpl", true );
                        }
                        
                        $i = 0;
                        $default = explode( ";", $questionItem->initial() );
                        foreach( $questionChoice_array as $questionChoiceItem )
                        {
                            $t->set_var( "selected", "" );
                            
                            // valores anteriormente respondidos
                            if ( isset($responded) )
                            {
                                $j = array_search( $questionChoiceItem->id(), $responded );
                                if ( isset( $j ) )
                                {
                                    $t->set_var( "selected", "checked" );
                                    $t->set_var( "checkbox_other", $respondedOther[$j] );
                                }
                            }
                            // default
                            elseif ( in_array( $questionChoiceItem->id(), $default ) )
                            {
                                $t->set_var( "selected", "checked" );
                            }
                            
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
                    case $questionItem->TYPE_DROPDOWN:
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
                            $t->set_var( "selected", "" );
                            
                            // default
                            if ( $questionItem->initial() == $questionChoiceItem->id() && $responded == "" )
                            {
                                $t->set_var( "selected", "selected" );
                            }
                            // valor anteriormente respondido
                            elseif ( $responded == $questionChoiceItem->id() )
                            {
                                $t->set_var( "selected", "selected" );
                            }
                            
                            $t->set_var( "value", $questionChoiceItem->id() );
                            $t->set_var( "value_name", $questionChoiceItem->content() );
                            $t->parse( "dropdown_item", "dropdown_item_tpl", true );
                        }
                        $t->parse( "dropdown", "dropdown_tpl" );
                    }
                    break;
                    
                    // rate
                    case $questionItem->TYPE_RATE:
                    {
                        $i = 0;
                        
                        foreach( $questionChoice_array as $questionChoiceItem )
                        {
                            $t->set_var( "value_name", $questionChoiceItem->content() );
                            $t->set_var( "choice_id", $questionChoiceItem->id() );
                            $t->set_var( "id2", $i++ );
                            
                            $t->set_var( "selected_rank0", "" );
                            $t->set_var( "selected_rank1", "" );
                            $t->set_var( "selected_rank2", "" );
                            $t->set_var( "selected_rank3", "" );
                            $t->set_var( "selected_rank4", "" );
                            
                            // valores anteriormente respondidos
                            
                            if ( isset($responded) )
                            {
                                $j = array_search( $questionChoiceItem->id(), $responded );
                                if ( isset( $j ) )
                                {
                                    //$t->set_var( "selected", "checked" );
                                    $t->set_var( "selected_rank" . $respondedOther[$j], "checked" );
                                }
                            }
                            
                            $t->parse( "rate_item", "rate_item_tpl", true );
                        }
                        $t->parse( "rate", "rate_tpl" );
                    }
                    break;
                    
                    // Data
                    case $questionItem->TYPE_DATE:
                    {
                        $t->set_var( "size", $questionItem->length() );
                        $t->set_var( "default_date", $responded );
                        $t->parse( "date", "date_tpl" );
                    }
                    break;
                    
                    // Num�rico
                    case $questionItem->TYPE_NUMERIC:
                    {
                        $t->set_var( "size", $questionItem->length() );
                        if ( isset( $responded ) )
                        {
                            $t->set_var( "default", $responded );
                        }
                        else
                        {
                            $t->set_var( "default", $questionItem->initial() );
                        }
                        $t->parse( "numeric", "numeric_tpl" );
                    }
                    break;
                }
                
                if ( $questionItem->isQuestion() )
                {
                    $position++;
                    $t->parse( "qnumber", "qnumber_tpl" );
                }
                
                $t->parse( "value_list", "value_list_tpl", true );
            }
            
            // set the response id
            $t->set_var( "response_id", $ResponseID );
            
            // Next page button
            if ( $Page < $totalPages )
            {
                $t->parse( "next_page_button", "next_page_button_tpl" );
            }
            else
            {
                $t->parse( "finish_button", "finish_button_tpl" );
            }
                
            // Previous page button
            if ( $Page > 1 )
            {
                $t->parse( "previous_page_button", "previous_page_button_tpl" );
            }
            
            if ( count( $errorMessages ) > 0 )
            {
                foreach ( $errorMessages as $errorItem )
                {
                    $t->set_var( "error_message", $errorItem );
                    $t->parse( "error_item", "error_item_tpl", true );
                }
                $t->parse( "error_list", "error_list_tpl" );
            }
        }
    }
    else
    {
        $t->set_var( "already_responded", "" );
        $t->set_var( "title", "" );
        $t->set_var( "info", "" );
        
        $t->set_var( "error_message", $t->Ini->variable( "strings", "no_such_survey" ) );
        $t->parse( "error_item", "error_item_tpl", true );
        $t->parse( "error_list", "error_list_tpl" );
    }
    
    $t->pparse( "output", "surveylist_tpl" );
    
?>