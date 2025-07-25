<?

switch ( $url_array[2] )
{
    case "typelist":
    {
        include( "kernel/ezgroupeventcalendar/admin/typelist.php" );
    }
    break;

    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $TypeID = $url_array[4];
        }
        else if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $TypeID = $url_array[4];
        }
        else if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }
        
        include( "kernel/ezgroupeventcalendar/admin/typeedit.php" );
    }
    break;

 case "categorylist":
   {
     include( "kernel/ezgroupeventcalendar/admin/categorylist.php" );
   }
   break;

 case "categoryedit" :
   {
     if ( $url_array[3] == "edit" )
       {
	 $Action = "Edit";
	 $CategoryID = $url_array[4];
       }
     else if ( $url_array[3] == "delete" )
       {
	 $Action = "Delete";
	 $CategoryID = $url_array[4];
       }
     else if ( $url_array[3] == "new" )
       {
	 $Action = "New";
       }

     include( "kernel/ezgroupeventcalendar/admin/categoryedit.php" );
   }
   break;

	case "grpdspl" :
	{
		include( "kernel/ezgroupeventcalendar/admin/groupdisplay.php" );
	}
	break;

	case "editor" :
	{
		switch ( $url_array[3] )
		{
			case "edit" :
			{
				$Action  = "Edit";
				$GroupID = $url_array[4];
				include( "kernel/ezgroupeventcalendar/admin/groupeditor.php" );
			}
			break;

			case "" :
			{
				$Action = "Display";
				include( "kernel/ezgroupeventcalendar/admin/groupeditor.php" );
			}
			break;
		}
	}
	break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
