<?php

    // include_once( "ezsitemanager/classes/ezsection.php" );
    // include_once( "ezuser/classes/ezpermission.php" ); 
    
    $ini = eZINI::instance( 'site.ini' );
    $user = eZUser::currentUser();

    $GlobalSectionID = $ini->variable( "eZSurveyMain", "DefaultSection" );
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