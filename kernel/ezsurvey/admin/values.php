<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezquestion.php" );
    // include_once( "ezsurvey/classes/ezquestionchoice.php" );
    
    $ini =& eZINI::instance( 'site.ini' );
    $Language = $ini->variable( "eZSurveyMain", "Language" );
    
    $t = new eZTemplate( "kernel/ezsurvey/admin/" . $ini->variable( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/admin/intl", $Language, "values.php" );
                         
    
    $SurveyID = $url_array[3];
    $QuestionID = $url_array[4];
    
    $t->set_file( "values_tpl", "values.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "values_tpl", "value_list_tpl", "value_list" );
    $t->set_block( "value_list_tpl", "value_item_tpl", "value_item" );
    
    $t->set_var( "value_list", "" );
    $t->set_var( "survey_id", $SurveyID );
    $t->set_var( "question_id", $QuestionID );
    
    $question = new eZQuestion( $QuestionID );
    
    // altera��o das op��es
    $i = 0;
    if ( isset($ValueID) )
    {
        foreach ($ValueID as $valueItem )
        {
            $questionChoice = new eZQuestionChoice( $valueItem );
            // trata da elimina��o caso tenha sido pedida
            if ( isset($ValueDeleteID) && in_array($valueItem, $ValueDeleteID) )
            {
                $questionChoice->delete();
            }
            else
            {
                $questionChoice->setContent( $Value[$i] );
                $questionChoice->store();
            }
            $i++;
        }
    }
    
    if( isset( $OK ) )
    {
        eZHTTPTool::header( "Location: /survey/surveyedit/edit/$SurveyID" );
        exit();
    }
    
    // adiciona op��o
    if ( isset( $AddValue ) )
    {
        $questionChoice = new eZQuestionChoice();
        $questionChoice->setQuestionID( $QuestionID );
        $questionChoice->setContent( "Choice " . ($question->numberOfQuestionsChoices()+1) );
        $questionChoice->store();
    }
    
    // lista op��es
    $i = 0;
    $questionChoice_array = $question->questionChoices();
    foreach ( $questionChoice_array as $questionItem )
    {
        if ($i++ % 2)
            $t->set_var( "td_class", "bgdark" );
        else
            $t->set_var( "td_class", "bglight" );
                
        $t->set_var( "value", $questionItem->content() );
        $t->set_var( "value_id", $questionItem->id() );
        $t->parse( "value_item", "value_item_tpl", true );
    }
    
    if ( count($questionChoice_array) > 0 )
    {
        $t->parse( "value_list", "value_list_tpl" );
    }
    
    $t->pparse( "output", "values_tpl" );
    
?>