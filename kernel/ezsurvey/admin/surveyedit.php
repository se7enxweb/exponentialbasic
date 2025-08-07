<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );
    // include_once( "ezsitemanager/classes/ezsection.php" );
    
    $ini =& eZINI::instance( 'site.ini' );
    $Language = $ini->variable( "eZSurveyMain", "Language" );
    
    $t = new eZTemplate( "kernel/ezsurvey/admin/" . $ini->variable( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/admin/intl", $Language, "surveyedit.php" );
    
    $t->set_file( "surveyedit_tpl", "surveyedit.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "surveyedit_tpl", "status_item_tpl", "status_item" );
    $t->set_block( "surveyedit_tpl", "section_item_tpl", "section_item" );
    
    $t->set_block( "surveyedit_tpl", "element_list_tpl", "element_list" );
    $t->set_block( "element_list_tpl", "element_item_tpl", "element_item" );
    $t->set_block( "element_item_tpl", "name_tpl", "name" );
    $t->set_block( "element_item_tpl", "no_name_tpl", "no_name" );
    $t->set_block( "element_item_tpl", "typelist_item_tpl", "typelist_item" );
    $t->set_block( "element_item_tpl", "fixed_values_tpl", "fixed_values" );
    $t->set_block( "element_item_tpl", "default_tpl", "default" );
    $t->set_block( "element_item_tpl", "size_tpl", "size" );
    $t->set_block( "element_item_tpl", "no_size_tpl", "no_size" );
    $t->set_block( "element_item_tpl", "required_tpl", "required" );
    
    $t->set_block( "surveyedit_tpl", "error_list_tpl", "error_list" );
    $t->set_block( "error_list_tpl", "error_item_tpl", "error_item" );
    
    $t->set_block( "surveyedit_tpl", "stats_btn_tpl", "stats_btn" );
    
    $t->set_var( "survey_count", "0" );
    $t->set_var( "total_survey_count", "0" );
    $t->set_var( "error_list", "" );
    $t->set_var( "element_list", "" );

    $errorMessages = array();
    
    $SurveyID = $url_array[4];
    
    if ( is_numeric($SurveyID) )
    {
        $survey = new eZSurvey($SurveyID);
    }
    else
    {
        $survey = new eZSurvey();
        $currentUser = eZUser::currentUser();
        if ( $currentUser != FALSE )
        {
            $survey->setUserID( $currentUser->id() );
        }
    }
    
    // copia o survey e edita-o
    if ( $Action == "copy" )
    {
        $survey =& $survey->copySurvey( "C�pia de " . $survey->title() );
        $currentUser = eZUser::currentUser();
        if ( $currentUser != FALSE )
        {
            $survey->setUserID( $currentUser->id() );
        }
        $Action = "edit";
    }
    
    // altera posi��es
    if ( $Action == "down" )
    {
        $question = new eZQuestion( $QuestionID );
        $question->moveDown();
        $Action = "edit";
    }
    
    if ( $Action == "up" )
    {
        $question = new eZQuestion( $QuestionID );
        $question->moveUp();
        $Action = "edit";
    }
    
    if ( $Action == "edit" )
    {
        // altera��es
        if ( isset( $OK ) || isset( $NewElement ) || isset( $Update ) || isset( $DeleteSelected ) || isset( $Preview ) )
        {
            $save = true;
            
            // grava��o do survey
            if ( $save )
            {
                $survey->setTitle( $Title );
                $survey->setSubTitle( $SubTitle );
                $survey->setInfo( $Info );
                $survey->setEMail( $EMail );
                $survey->setThankHead( $ThankHead );
                $survey->setThankBody( $ThankBody );
                $survey->setStatus( $Status );
                $survey->setSectionID( $Section );

                if ( isset( $Public ) && $Public == "N" )
                {
                    $Public = "N";
                }
                else
                {
                    $Public = "Y";
                }

                $survey->setPublic( $Public );
                
                $survey->store();
            }
            
            $t->set_var( "title_value", $Title );
            $t->set_var( "subtitle_value", $SubTitle );
            $t->set_var( "info_value", $Info );
            $t->set_var( "email_value", $EMail );
            $t->set_var( "thankhead_value", $ThankHead );
            $t->set_var( "thankbody_value", $ThankBody );
            
            foreach ( $survey->statusOptions() as $statusItem )
            {
                $t->set_var( "status_value", $statusItem );
                $t->parse( "status_item", "status_item_tpl", true );
            }
            
            $section_array =& eZSection::getAll();
            
            foreach ( $section_array as $sectionItem )
            {
                $t->set_var( "section_value", $sectionItem->id() );
                $t->set_var( "section_name", $sectionItem->name() );
                
                if ( $sectionItem->id() == $Section )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
                
                $t->parse( "section_item", "section_item_tpl", true );
            }
        }
        else
        {
            // edi��o de um survey (quando se entra na p�gina pela primeira vez)
            $t->set_var( "title_value", $survey->title() );
            $t->set_var( "subtitle_value", $survey->subTitle() );
            $t->set_var( "info_value", $survey->info() );
            $t->set_var( "email_value", $survey->email() );
            $t->set_var( "thankhead_value", $survey->thankHead() );
            $t->set_var( "thankbody_value", $survey->thankBody() );
            
            foreach ( $survey->statusOptions() as $statusItem )
            {
                $t->set_var( "status_value", $statusItem );
                $t->parse( "status_item", "status_item_tpl", true );
            }
            
            $section_array =& eZSection::getAll();
            
            foreach ( $section_array as $sectionItem )
            {
                $t->set_var( "section_value", $sectionItem->id() );
                $t->set_var( "section_name", $sectionItem->name() );
                
                if ( $sectionItem->id() == $survey->sectionID() )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
                
                $t->parse( "section_item", "section_item_tpl", true );
            }
        }
        
        // inser��o de uma nova pergunta.
        if ( isset( $NewElement ) && $survey->id() != "" )
        {
            $question = new eZQuestion();
            
            $count = $survey->numberOfQuestions();
            
            $question->setSurveyID( $survey->id() );
            $question->setContent( "New Question " . ($count+1) );
            $question->setPosition();

            $question->store();
        }
        
        // altera��o das perguntas
        $i = -1;
        if ( isset($elementID) )
        {
            foreach ($elementID as $elementItem )
            {
                $i++;
                $question = new eZQuestion( $elementItem );
                
                if ( $question->questionTypeID() != $elementTypeID[$i] && $survey->status() == "ACTIVE" )
                {
                    $errorMessages[] = "You can't change question types while in ACTIVE mode";
                    continue;
                }
                
                // trata da elimina��o caso tenha sido pedida
                if ( isset($elementDelete) && in_array($elementItem, $elementDelete) )
                {
                    $question->delete();
                }
                else
                {
                    $question->setContent( $elementName[$i] );
                    $question->setQuestionTypeID( $elementTypeID[$i] );
                    $question->setLength( $Size[$i] );
                    
                    // Is required?
                    if ( isset($elementRequired) && in_array($elementItem, $elementRequired) )
                        $question->setRequired( "Y" );
                    else
                        $question->setRequired( "N" );
                    
                    if ( isset( $Public ) && $Public == "N" )
                    {
                        $Public = "N";
                    }
                    else
                    {
                        $Public = "Y";
                    }

                    $question->setPublic( $Public );
                    $question->store();
                }
            }
        }
        
        if( isset( $OK ) )
        {
            eZHTTPTool::header( "Location: /survey/surveylist/list" );
            exit();
        }
        
        
        if( isset( $Preview ) )
        {
            eZHTTPTool::header( "Location: /survey/preview/$SurveyID" );
            exit();
        }
        
        if( isset( $Stats ) )
        {
            eZHTTPTool::header( "Location: /survey/stats/$SurveyID" );
            exit();
        }
        
        // lista perguntas
        $types =& eZQuestion::questionTypes();
        $question_array = $survey->surveyQuestions();
        
        $i = 0;
        foreach ($question_array as $questionItem )
        {
            if ($i++ % 2)
                $t->set_var( "td_class", "bgdark" );
            else
                $t->set_var( "td_class", "bglight" );
                
            $clear = false;
            
            // id
            $t->set_var( "element_id", $questionItem->id() );
            
            // Name
            if ( $questionItem->hasName() )
            {
                $t->set_var( "element_name", $questionItem->content() );
                $t->set_var( "no_name", "" );
                $t->parse( "name", "name_tpl" );
            }
            else
            {
                $t->set_var( "name", "" );
                $t->parse( "no_name", "no_name_tpl" );
            }
            
            // Type
            foreach ( $types as $typeItem )
            {
                $t->set_var( "element_type_id", $typeItem[0] );
                $t->set_var( "element_type_name", $typeItem[1] );
                
                if ($questionItem->questionTypeID() == $typeItem[0])
                    $t->set_var( "selected", "selected" );
                else
                    $t->set_var( "selected", "" );
                    
                $t->parse( "typelist_item", "typelist_item_tpl", $clear );
                $clear = true;
            }
            
            // Fixed values
            if ( $questionItem->hasChoices() )
                $t->parse( "fixed_values", "fixed_values_tpl" );
            else
                $t->set_var( "fixed_values", "" );
                
            // Default
            if ( $questionItem->hasDefault() )
                $t->parse( "default", "default_tpl" );
            else
                $t->set_var( "default", "" );
            
            // Size
            if ( $questionItem->hasSize() )
            {
                $t->set_var( "element_size", $questionItem->length() );
                $t->set_var( "no_size", "" );
                $t->parse( "size", "size_tpl" );
            }
            else
            {
                $t->set_var( "element_size", '0' );
                $t->set_var( "size", "" );
                $t->parse( "no_size", "no_size_tpl" );
            }
            
            // Required
            if ( $questionItem->hasRequired() )
            {
                if ( $questionItem->isRequired() )
                    $t->set_var( "element_required", "checked" );
                else
                    $t->set_var( "element_required", "" );
                    
                $t->parse( "required", "required_tpl" );
            }
            else
            {
                $t->set_var( "required", "" );
            }
            
            $t->parse( "element_item", "element_item_tpl", true );
        }
        if ( count( $question_array ) > 0 )
        {
            $t->parse( "element_list", "element_list_tpl" );
        }
    }
    
    if ( $Action == "new" )
    {
        $survey = new eZSurvey();
        $count = eZSurvey::numberOfSurveys() + 1;
        
        $t->set_var( "title_value", "Survey Title $count" );
        $t->set_var( "subtitle_value", "" );
        $t->set_var( "info_value", "" );
        $t->set_var( "email_value", "" );
        $t->set_var( "thankhead_value", "" );
        $t->set_var( "thankbody_value", "" );
        $t->set_var( "element_list", "" );
        
        foreach ( $survey->statusOptions() as $statusItem )
        {
            $t->set_var( "status_value", $statusItem );
            $t->parse( "status_item", "status_item_tpl", true );
        }
        
        $section_array =& eZSection::getAll();
        
        foreach ( $section_array as $sectionItem )
        {
            $t->set_var( "section_value", $sectionItem->id() );
            $t->set_var( "section_name", $sectionItem->name() );
            
            $t->parse( "section_item", "section_item_tpl", true );
        }
    }
    
    // tratamento de erros
    foreach ($errorMessages as $errorItem )
    {
        $t->set_var( "error_message", $errorItem );
        $t->parse( "error_item", "error_item_tpl", true );
    }
    if ( count($errorMessages) > 0 )
        $t->parse( "error_list", "error_list_tpl" );
    else
        $t->set_var( "error_list", "" );
    
    if ( $survey->status() == "ACTIVE" )
    {
        $t->parse( "stats_btn", "stats_btn_tpl" );
    }
    else
    {
        $t->set_var( "stats_btn", "" );
    }
    
    $t->set_var( "survey_id", $survey->id() );
    
    $t->pparse( "output", "surveyedit_tpl" );

?>