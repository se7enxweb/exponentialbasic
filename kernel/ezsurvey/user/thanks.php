<?php

    // include_once( "classes/INIFile.php" );
    // include_once( "classes/eztemplate.php" );
    // include_once( "ezsurvey/classes/ezsurvey.php" );
    
    $ini =& eZINI::instance( 'site.ini' );
    $Language = $ini->variable( "eZSurveyMain", "Language" );
    
    $t = new eZTemplate( "kernel/ezsurvey/user/" . $ini->variable( "eZSurveyMain", "AdminTemplateDir" ),
                         "kernel/ezsurvey/user/intl", $Language, "thanks.php" );
                         
    $t->set_file( "thanks_tpl", "thanks.tpl" );
    $t->setAllStrings();
    
    $SurveyID = $url_array[4];
    
    $survey = new eZSurvey( $SurveyID );
    
    $t->set_var( "thanks_head", $survey->thankHead() );
    $t->set_var( "thanks_body", $survey->thankBody() );
    
    $t->pparse( "output", "thanks_tpl" );

?>