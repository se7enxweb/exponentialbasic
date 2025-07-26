<?php
    
    switch ( $url_array[2] )
    {
        case "surveylist":
        {
            include ( "kernel/ezsurvey/admin/surveylist.php" );
        }
        break;
        
        case "surveyedit":
        {
            $Action = $url_array[3];
            
            if ( isset($Back) )
            {
                $url_array[4] = "";
                include ( "kernel/ezsurvey/admin/surveylist.php" );
            }
            else
            {
                include ( "kernel/ezsurvey/admin/surveyedit.php" );
            }
        }
        break;
        
        case "preview":
        {
            include( "kernel/ezsurvey/admin/preview.php" );
        }
        break;
        
        case "stats":
        {
            include( "kernel/ezsurvey/admin/stats.php" );
        }
        break;
        
        case "values":
        {
            include ( "kernel/ezsurvey/admin/values.php" );
        }
        break;
        
        case "default":
        {
            include ( "kernel/ezsurvey/admin/default.php" );
        }
        break;
    }
    
?>