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
                         "kernel/ezsurvey/admin/intl", $Language, "stats.php" );
                         
    $t->set_file( "stats_tpl", "stats.tpl" );
    $t->setAllStrings();
    
    $t->set_block( "stats_tpl", "value_list_tpl", "value_list" );

    $t->set_block( "value_list_tpl", "question_tpl", "question" );
    $t->set_block( "question_tpl", "question_item_tpl", "question_item" );

    $t->set_block( "stats_tpl", "next_page_button_tpl", "next_page_button" );
    $t->set_block( "stats_tpl", "previous_page_button_tpl", "previous_page_button" );
    
    $t->set_var( "survey_id", $SurveyID );
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
    
    $totalPages = $survey->numberOfPages();
    $t->set_var( "page_number", $Page );
    $t->set_var( "page_total", $totalPages );
    
    $question_array = $survey->surveyQuestions( "Position", $Page );
    
    $position = 0;
    foreach ( $question_array as $questionItem )
    {
        if  ( $questionItem->hasStats() )
        {
            // limpar as vari�veis
            $t->set_var( "question", "" );
            
            $t->set_var( "id", $position++ );
            $t->set_var( "question_name", $questionItem->content() );
        
            if ( $questionItem->hasChoices() )
            {
                $questionChoice_array = $questionItem->questionChoices();
            }
            
            $i = 0;
            
            $stats = $questionItem->stats();
            
            $clear = false;
            for ( $i = 0; $i < count($stats[0]); $i++ )
            {
                 $t->set_var( "choice_name", $stats[1][$i] );
                 $perc = $stats[3][$i];
                 $t->set_var( "choice_perc", round($perc, 2) );
                 $t->set_var( "choice_left", (100 - $perc) );
                 $t->set_var( "choice_total", round($stats[2][$i], 2) );
                 $t->parse( "question_item", "question_item_tpl", $clear );
                 $clear = true;
            }
            
            $t->parse( "question", "question_tpl", true );
            $t->parse( "value_list", "value_list_tpl", true );
        }
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
  
    $t->pparse( "output", "stats_tpl" );
    
?>