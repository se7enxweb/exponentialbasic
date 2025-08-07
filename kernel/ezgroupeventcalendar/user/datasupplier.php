<?
include_once( "kernel/ezgroupeventcalendar/classes/ezgroupevent.php" );

$ini =& eZINI::instance( 'site.ini' );
$GlobalSectionID = $ini->variable( "eZGroupEventCalendarMain", "DefaultSection" );
$UserComments = $ini->variable( "eZGroupEventCalendarMain", "UserComments" );

$Title = "Calendar";

/*
if( $GetByTypeID != 0 )
	$Type = $GetByTypeID;
else
	$Type = 0;
*/

switch ( $url_array[2] )
{
    case "rssheadlines":
    case "listrss":
    case "rss":
    {
      include( "kernel/ezgroupeventcalendar/user/rssheadlines.php" );
    }
    break;

    case "yearview" :
    {
        $Year = $url_array[3];

        include( "kernel/ezgroupeventcalendar/user/yearview.php" );
    }
    break;

    case "monthview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "kernel/ezgroupeventcalendar/user/monthview.php" );
    }
    break;

    case "dayview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "kernel/ezgroupeventcalendar/user/dayview.php" );
    }
    break;
    case "weekview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "kernel/ezgroupeventcalendar/user/weekview.php" );
    }
    break;
    
    case "eventedit" :
    {
        switch ( $url_array[3] )
        {
	    // filelist
	    case "filelist" :
	    {
	        $EventID = $url_array[4];
		include( "kernel/ezgroupeventcalendar/user/filelist.php" );
		break;
	    }

	    //files
	    case "fileedit" :
	    {
	      if ( isSet( $Browse ) )
	      {
		include( "kernel/ezfilemanager/admin/browse.php" );
		break;
	      }

 	      switch ( $url_array[4] )
	      {

	      case "new" :
	      {
		  $Action = "New";
		  $EventID = $url_array[5];
		  include( "kernel/ezgroupeventcalendar/user/fileedit.php" );
	      }
	      break;

	      case "edit" :
	      {
		  $Action = "Edit";
		  $EventID = $url_array[6];
		  $FileID = $url_array[5];
		  include( "kernel/ezgroupeventcalendar/user/fileedit.php" );
	      }
	      break;

	      case "delete" :
	      {
		  $Action = "Delete";
		  $EventID = $url_array[6];
		  $FileID = $url_array[5];
		  include( "kernel/ezgroupeventcalendar/user/fileedit.php" );
	      }
	      break;

	      default :
	      {
	          include( "kernel/ezgroupeventcalendar/user/fileedit.php" );
	      }
	      }
	  }
	  break;


            case "new" :
            {
                $Action = "New";
                $Year = $url_array[4];
                $Month = $url_array[5];
                $Day = $url_array[6];
                $StartTime = $url_array[7];

                include( "kernel/ezgroupeventcalendar/user/eventedit.php" );
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $EventID = $url_array[4];

                include( "kernel/ezgroupeventcalendar/user/eventedit.php" );
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $EventID = $url_array[4];

                include( "kernel/ezgroupeventcalendar/user/eventedit.php" );
            }
            break;

            case "insert" :
            {
                $Action = "Insert";
                $EventID = $url_array[4];

                include( "kernel/ezgroupeventcalendar/user/eventedit.php" );
            }
            break;

            default :
            {
                $Action = $url_array[3];
		include( "kernel/ezgroupeventcalendar/user/eventedit.php" );
            }
        }
    }
    break;

    case "eventview" :
    {
        $EventID = $url_array[3];
        include( "kernel/ezgroupeventcalendar/user/eventview.php" );

	if  ( isset( $PrintableVersion ) && ( $PrintableVersion != "enabled" ) &&  ( $UserComments == "enabled" ) )
	{
	    $RedirectURL = "/groupeventcalendar/eventview/$EventID/";
	    $event = new eZGroupEvent( $EventID );
	    if ( ( $event->id() >= 1 ) )
	    {
		for ( $i = 0; $i < count( $url_array ); $i++ )
		{
		  if ( ( $url_array[$i] ) == "parent" )
		  {
		     $next = $i + 1;
		     $Offset = $url_array[$next];
		   }
		}

	      $forum = $event->forum();
	      $ForumID = $forum->id();
       	      include( "kernel/ezforum/user/messagesimplelist.php" );
	    }
	}
    }
    break;
	
    default;
    {
       	eZHTTPTool::header( "Location: /error/404" );
         exit();
    }
}

?>
