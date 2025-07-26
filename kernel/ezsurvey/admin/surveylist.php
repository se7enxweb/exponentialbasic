<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    // include_once( "classes/ezlist.php" );
    
    $ini =& INIFile::globalINI();
    $Language = $ini->read_var( "eZSurveyMain", "Language" );
    
    $t = new eZTemplate( "kernel/ezsurvey/admin/" . $ini->read_var( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/admin/intl", $Language, "surveylist.php" );
    $t->setAllStrings();
    
    $t->set_file( "surveylist_tpl", "surveylist.tpl" );
    $t->set_block( "surveylist_tpl", "survey_list_tpl", "survey_list" );
    $t->set_block( "survey_list_tpl", "survey_item_tpl", "survey_item" );
    $t->set_block( "survey_item_tpl", "survey_item_stats_tpl", "survey_item_stats" );
    
    $t->set_var( "survey_list", "" );
    
    $surveyListLimit = $ini->read_var( "eZSurveyMain", "SurveyListLimit" );

    $offset = $url_array[4];
    
    // Eliminar surveys
    if ( isset( $DeleteSelected ) && isset( $formDelete ) )
    {
        foreach( $formDelete as $formDelete )
        {
            $survey = new eZSurvey( $formDelete );
            $survey->delete();
        }
        $offset = 0;
    }
    
    $surveyCount = eZSurvey::numberOfSurveys();
    
    if ( ! is_numeric($offset) )
    {
        $offset = 0;
    }
    
    $surveyList = eZSurvey::getAll( "ID", $offset, $surveyListLimit );

    if ( count($surveyList) == 0 )
    {
        $t->set_var( "survey_list", "" );
    }
    else
    {
        $i = 0;
        foreach ( $surveyList as $surveyItem )
        {
            if ($i++ % 2)
                $t->set_var( "td_class", "bgdark" );
            else
                $t->set_var( "td_class", "bglight" );
            
            $t->set_var( "survey_id", $surveyItem->id() );
            $t->set_var( "survey_title", $surveyItem->title() );
            $t->set_var( "survey_status", $surveyItem->status() );
            
            if ( $surveyItem->status() == "ACTIVE" )
                $t->parse( "survey_item_stats", "survey_item_stats_tpl" );
            else
                $t->set_var( "survey_item_stats", "" );
            
            $t->parse( "survey_item", "survey_item_tpl", true );
        }
        $t->parse( "survey_list", "survey_list_tpl" );
    }
    
    eZList::drawNavigator( $t, $surveyCount, $surveyListLimit, $offset, "surveylist_tpl" );
    
    $t->pparse( "output", "surveylist_tpl" );

?>