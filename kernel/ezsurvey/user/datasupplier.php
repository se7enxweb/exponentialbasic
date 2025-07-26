<?php

    // include_once( "ezsitemanager/classes/ezsection.php" );
    // include_once( "ezuser/classes/ezpermission.php" ); 
    
    $ini = INIFile::globalINI();
    $user = eZUser::currentUser();

    $GlobalSectionID = $ini->read_var( "eZSurveyMain", "DefaultSection" );
    $currentSiteDesign = (new eZSection())->siteDesign( $GlobalSectionID );
    $hasPermission = ( eZPermission::checkPermission( $user, "eZSurvey", "ModuleAnswer" ) );
    
    switch ( $url_array[2] )
    {
        case "surveylist":
        {
            if ( $hasPermission )
            {
                include ( "kernel/ezsurvey/user/surveylist.php" );
            }
            else
            {
                //eZHTTPTool::header( "Location: /novisagent/login/" );
                //exit();
                include ( "kernel/ezsurvey/user/surveylist.php" );
            }
        }
        break;
        
        case "thanks":
        {
            include ( "kernel/ezsurvey/user/thanks.php" );
        }
        break;
    }
    
?>