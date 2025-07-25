<?
//
// Definition of eZEvent class
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZEventCalendar
//! eZEvent handles events.
/*!
  
*/

/*!TODO
*/

/*
include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztime.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );

include_once( "ezmail/classes/ezmail.php" );
*/

// take two date objects and return an array holding
// the difference in years, months, weeks, and days
// we should probably integrate this in a class later on...
function _date_diff($date1, $date2)
{
if (is_object($date1)) {
$y1 = $date1->year();
$m1 = $date1->month();
$d1 = $date1->day();
} else {
$d1 = explode("-", $date1);
$y1 = $d1[0];
$m1 = $d1[1];
$d1 = $d1[2];
}
if (is_object($date2)) {
$y2 = $date2->year();
$m2 = $date2->month();
$d2 = $date2->day();
} else {
$d2 = explode("-", $date2);
$y2 = $d2[0];
$m2 = $d2[1];
$d2 = $d2[2];
}
$years = $y2 - $y1;

if (($m2-$m1) && (!$years))
 $months = ($m2 - $m1);
else
 $months = ($m2 -$m1)+(12*$years);
 
$date1_set = mktime(0,0,0, $m1, $d1, $y1);
$date2_set = mktime(0,0,0, $m2, $d2, $y2);
$days = round(($date2_set-$date1_set)/(60*60*24));
$weeks = round($days / 7);
$retArr = array('years'=>$years, 'months'=>$months, 'weeks'=>$weeks, 'days'=>$days);
return $retArr;
}

class eZGroupEvent
{
    /*!
      Constructs a new eZGroupEvent object.

      If $id is set the object's values are fetched from the
      database.
    */
    function __construct( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->EventCategoryID = 0;
        $this->IsRecurring = 0;
        $this->RepeatTimes = 0;
        $this->EMailNotice = 0;
        $this->EventTypeID = 0;
        $this->GroupID = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZEvent object to the database.
    */
    function store()
    {
    // commenting out all the recurring stuff until the UI is implemented
        $this->dbInit();

		$Name        = addslashes( $this->Name );
		$Description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
         
	$this->Database->query( "INSERT INTO eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',
                             IsRecurring='$this->IsRecurring',
                             RecurExceptions='$this->RecurExceptions',
                             RecurDay='$this->RecurDay',
                             RecurMonthlyType='$this->RecurMonthlyType',
                             RecurMonthlyTypeInfo='$this->RecurMonthlyTypeInfo',
                             RecurType='$this->RecurType',
                             RecurFreq='$this->RecurFreq',
                             RepeatForever='$this->RepeatForever',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate',
                             RecurFinishDate='$this->RecurFinishDate',                            
                             EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
 			                       GroupID='$this->GroupID'" );
            $this->ID = $this->Database->insertID();
        }
        else
        {
            $query = "UPDATE eZGroupEventCalendar_Event SET
                             Name='$Name',
                             Description='$Description',
                             Date='$this->Date',
                             Duration='$this->Duration',
                             Url='$this->Url',
                             Status='$this->Status',
                             Location='$this->Location',
                             EventAlarmNotice='$this->EventAlarmNotice',
                             EventCategoryID='$this->EventCategoryID',
                             IsRecurring='$this->IsRecurring',
                             RecurExceptions='$this->RecurExceptions',
                             RecurDay='$this->RecurDay',
                             RecurMonthlyType='$this->RecurMonthlyType',
                             RecurMonthlyTypeInfo='$this->RecurMonthlyTypeInfo',
                             RecurType='$this->RecurType',
                             RecurFreq='$this->RecurFreq',
                             RepeatForever='$this->RepeatForever',
                             RepeatTimes='$this->RepeatTimes',
                             RepeatUntilDate='$this->RepeatUntilDate',     
                             RecurFinishDate='$this->RecurFinishDate',                            
                             EMailNotice='$this->EMailNotice',
                             IsPrivate='$this->IsPrivate',
                             Priority='$this->Priority',
                             EventTypeID='$this->EventTypeID',
                             GroupID='$this->GroupID'
                             WHERE ID='$this->ID'";

	          $this->Database->query( $query );
        }
        return true;
    }

    /*!
      Deletes a eZGroupEvent object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZGroupEventCalendar_Event WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $event_array, "SELECT * FROM eZGroupEventCalendar_Event WHERE ID='$id'" );

            if ( count( $event_array ) > 1 )
            {
                die( "Error: Event's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $event_array ) == 1 )
            {

                $this->ID =& $event_array[0][ "ID" ];
                $this->Name =& $event_array[0][ "Name" ];
                $this->Description =& $event_array[0][ "Description" ];
                $this->EventTypeID =& $event_array[0][ "EventTypeID" ];
                $this->GroupID =& $event_array[0][ "GroupID" ];
                $this->Date =& $event_array[0][ "Date" ];
                $this->Duration =& $event_array[0][ "Duration" ];
                $this->IsPrivate =& $event_array[0][ "IsPrivate" ];
                $this->Priority =& $event_array[0][ "Priority" ];

                $this->Url =& $event_array[0][ "Url" ];
                $this->Location =& $event_array[0][ "Location" ];

                $this->Status =& $event_array[0][ "Status" ];
                $this->EventCategoryID =& $event_array[0][ "EventCategoryID" ];

                $this->EMailNotice =& $event_array[0][ "EventAlarmNotice" ];
                $this->EventAlarmNotice =& $event_array[0][ "EventAlarmNotice" ];

                $this->EventCategoryID =& $event_array[0][ "EventCategoryID" ];
                $this->IsRecurring =& $event_array[0][ "IsRecurring" ]; 
                $this->RecurDay =& $event_array[0][ "RecurDay" ];
                $this->RecurFreq =& $event_array[0][ "RecurFreq" ];
                $this->RecurForever =& $event_array[0][ "RecurForever" ];
                $this->RecurMonthlyType =& $event_array[0][ "RecurMonthlyType" ];
                $this->RecurMonthlyTypeInfo =& $event_array[0][ "RecurMonthlyTypeInfo" ];
                $this->RecurType =& $event_array[0] [ "RecurType" ];
                $this->RecurExceptions =& $event_array[0][ "RecurExceptions" ];
                $this->RepeatForever =& $event_array[0][ "RepeatForever" ];
                $this->RepeatTimes =& $event_array[0][ "RepeatTimes" ];
                $this->RepeatUntilDate =& $event_array[0][ "RepeatUntilDate" ];
                $this->RecurFinishDate =& $event_array[0][ "RecurFinishDate" ];
                $this->State_ = "Coherent";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the events found in the database.

      The events are returned as an array of eZEvent objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $event_array = array();
        
        $this->Database->array_query( $event_array,
        "SELECT ID FROM eZGroupEventCalendar_Event" );
        
        for ( $i=0; $i<count($event_array); $i++ )
        {
            $return_array[$i] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the events thats belongs to another group found in the database.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByOthers()
    {
        $this->dbInit();
        
        $return_array = array();
        $event_array = array();
        
        $this->Database->array_query( $event_array,
        "SELECT ID FROM eZGroupEventCalendar_Event WHERE ID='$id' AND IsPrivate='0'" );
        
        for ( $i=0; $i<count($event_array); $i++ )
        {
            $return_array[$i] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }


    /*!
      Returns all the events for the given user on the given date.

      The events are returned as an array of eZEvent objects.
    */
      function &getByDate( $date, $group, $showPrivate=false )
    {
        $ret = array();

        if ( ( get_class( $date ) == "eZDate" ) && ( get_class( $group ) == "eZUserGroup" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );

            $groupID = $group->id();
            $stamp   = $date->year() . $month . $day;
            $longstamp = $stamp . '235959';
			//include_once( "classes/INIFile.php" );
			$ini =& $GLOBALS["GlobalSiteIni"];
			
			if( $ini->read_var( "eZGroupEventCalendarMain", "SubGroupSelect" ) == "enabled" )
			{
				$i = 0;
				$groupTree = $group->getTree( $groupID );

				$selectGroups = "GroupId='$groupID'";

				foreach( $groupTree as $tree )
				{
					$i++;
					$groupID = $tree[0]->id();
					$selectGroups .= " OR GroupID='$groupID'";
				}

				$selectGroups = "( $selectGroups )";
			}
			else
			{
			        // Add Support for Events in All Groups
			        $selectGroups = "GroupID='$groupID'";
			        // $selectGroups = "GroupID='$groupID' OR GroupID=0";
			}

			if ( $showPrivate == false )
			{
				$this->Database->array_query( $event_array,
				"SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
				 WHERE ( Date LIKE '$stamp%' AND IsPrivate='0' AND $selectGroups )
                 OR ( IsRecurring='1' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp' AND IsPrivate='0' AND $selectGroups )
                 ORDER BY Date ASC", true );
			}
			else
			{
        $query = "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
				WHERE ( Date LIKE '$stamp%' AND $selectGroups )
                OR ( IsRecurring='1' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp' AND $selectGroups)
                ORDER BY Date ASC";
                echo $query;
				$this->Database->array_query( $event_array,
				$query );
			}
			//			print(" SELECT ID FROM eZGroupEventCalendar_Event WHERE ( Date LIKE '$stamp%' AND GroupID='$groupID' ) ORDER BY Date ASC <br />");
			for ( $i=0; $i<count($event_array); $i++ )
			{
				$recurCheck = filterRecurring($event_array[$i], $date);
                if ($recurCheck)
				 $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
			}

            $ret =& $return_array;
        }
        return $ret;
    }
    
    /*!
      Returns all the events for the given group on the given date and type.

      The events are returned as an array of eZEvent objects.
    */
    function &getByGroupType( $date, $group, $type, $showPrivate=false )
    {
        $ret = array();

        if ( ( get_class( $date ) == "eZDate" ) && ( get_class( $group ) == "eZUserGroup" ) && ( get_class( $type ) == "eZGroupEventType" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month   = $date->month();
            $month   = eZDateTime::addZero( $month );

            $day     = $date->day();
            $day     = eZDateTime::addZero( $day );

            $groupID = $group->id();

			//include_once( "classes/INIFile.php" );
			$ini =& $GLOBALS["GlobalSiteIni"];
			
			if( $ini->read_var( "eZGroupEventCalendarMain", "SubGroupSelect" ) == "enabled" )
			{
				$i = 0;
				$groupTree = $group->getTree( $groupID );
				
				$selectGroups = "GroupId='$groupID'";

				foreach( $groupTree as $tree )
				{
					$i++;
					$groupID = $tree[0]->id();
					$selectGroups .= " OR GroupID='$groupID'";
				}

				$selectGroups = "( $selectGroups )";
			}
			else
			{
			        // Add Support for Events in All Groups
			         $selectGroups = "GroupID='$groupID'";
			     //   $selectGroups = "GroupID='$groupID' OR GroupID=0";
			}

			$typeID  = $type->id(); 
            
            $stamp = $date->year() . $month . $day;
            $longstamp = $stamp . '235959';
            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0' AND $selectGroups AND EventTypeID='$typeID' 
                 OR ( IsRecurring='1' AND $selectGroups AND EventTypeID='$typeID' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp' AND IsPrivate='0' )
                 ORDER BY Date ASC", true );
            }
            else
            {
                $query = "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND $selectGroups AND EventTypeID='$typeID' 
                 OR ( IsRecurring='1' AND $selectGroups AND EventTypeID='$typeID' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp')
                 ORDER BY Date ASC";
                 echo $query;
                $this->Database->array_query( $event_array,
                $query );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
            	$recurCheck = filterRecurring($event_array[$i], $date);
                if (!$recurCheck) echo 'filtering out ' . $event->name() . ': '. $event->GroupID() . '<br>'; 
                if ($recurCheck) {
                 $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 ); }
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for all groups on the given date.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByDate( $date, $showPrivate=false, $groupNoShow=true )
    {
        $ret = array();

        if ( ( is_a( $date, "eZDate" ) ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );
            
            $stamp = $date->year() . $month . $day;
	          $longstamp = $stamp . '235959';
            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date
		 FROM eZGroupEventCalendar_Event
                 WHERE ( Date LIKE '$stamp%' AND IsPrivate='0' )
		 OR ( IsRecurring='1' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp' AND IsPrivate='0' )
		 ORDER BY Date ASC", true );

            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date
		 FROM eZGroupEventCalendar_Event
                 WHERE ( Date LIKE '$stamp%')
		 OR ( IsRecurring='1' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp')
		 ORDER BY Date ASC" );
            }

			// groups not to include
			if( $groupNoShow == true )
			{
				$noShowGroup = new eZGroupNoShow();
				$noshow_array = $noShowGroup->getAll();
			}
      for ( $i=0; $i<=count($event_array); $i++ )
	    {
        if( !isset($event_array[$i]) )
          continue;

        // : implementing recurring event sorting
				$recurCheck = filterRecurring($event_array[$i], $date);
        if( $groupNoShow == true )
				{
					$displayGroup = true;

					foreach( $noshow_array as $noshow )
					{
						if( $noshow->GroupID == $event_array[$i]["GroupID"] )
							$displayGroup = false;
					}
				}
				else
				{
					$displayGroup = true;
				}
				if( $displayGroup == true && isset($event_array[$i]["ID"]) && $recurCheck)
					$return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }

    /*!
      Returns all the events for all groups by a given type on the given date.

      The events are returned as an array of eZEvent objects.
    */
    function &getAllByType( $date, $type, $showPrivate=false, $groupNoShow=true )
    {
        $ret = array();
var_dump($date, $type, $showPrivate, $groupNoShow);
        if ( ( is_a( $date, "eZDate" ) ) && ( is_a( $type, "eZGroupEventType" ) ) )
        {
            $this->dbInit();

            $return_array = array();
            $event_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );

			      $typeID = $type->id();

            $stamp = $date->year() . $month . $day;
            $longstamp = $stamp . '235959';
            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0' AND EventTypeID='$typeID'
                OR ( IsRecurring='1' AND IsPrivate='0' AND EventTypeID='$typeID' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp' )
                 ORDER BY Date ASC", true );
            }
            else
            {
                $query = "SELECT ID, GroupID, IsRecurring, RecurType, RecurFreq, RepeatTimes, RecurDay, RecurFinishDate, RecurMonthlyType, RecurMonthlyTypeInfo, RecurExceptions, Date FROM eZGroupEventCalendar_Event
                 WHERE Date LIKE '$stamp%' AND EventTypeID='$typeID'
                 OR ( IsRecurring='1' AND EventTypeID='$typeID' AND RecurFinishDate>='$longstamp' AND Date<='$longstamp')
                 ORDER BY Date ASC";
                echo $query; die();
                $this->Database->array_query( $event_array,
                $query );
            }

			// groups not to include
			if( $groupNoShow == true )
			{
				$noShowGroup = new eZGroupNoShow();
				$noshow_array = $noShowGroup->getAll();
			}

            for ( $i=0; $i<count($event_array); $i++ )
            {
                if( $groupNoShow == true )
				{
					$displayGroup = true;

					foreach( $noshow_array as $noshow )
					{
						if( $noshow->GroupID == $event_array[$i]["GroupID"] )
							$displayGroup = false;
					}
				}
				else
				{
					$displayGroup = true;
				}
                $recurCheck = filterRecurring($event_array[$i], $date);
				if( $displayGroup == true && $recurCheck)
					$return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for the given group.

      The events are returned as an array of eZEvent objects.
    */
    function &getByType( $type, $showPrivate=false )
    {
        $ret = array();
        if ( ( is_a( $type, "eZGroupEventType" ) ) )
        {
            $this->dbInit();

            $return_array = array();
            $event_array = array();

            $typeID = $type->id();

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE IsPrivate='0' AND EventTypeID='$typeID'
                 ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE EventTypeID='$typeID' ORDER BY Date ASC" );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
                $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns all the events for the given type.

      The events are returned as an array of eZEvent objects.
    */
    function &getByGroup( $group, $showPrivate=false )
    {
        $ret = array();

        if ( ( is_a( $group, "eZUserGroup" ) ) )
        {
            $this->dbInit();

            $return_array = array();
            $event_array = array();

            $groupID = $group->id();

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE IsPrivate='0' AND GroupID='$groupID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $event_array,
                "SELECT ID FROM eZGroupEventCalendar_Event
                 WHERE GroupID='$groupID' ORDER BY Date ASC" );
            }

            for ( $i=0; $i<count($event_array); $i++ )
            {
                $return_array[] = new eZGroupEvent( $event_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;
        }
        return $ret;
    }


    /*!
      Returns the object ID to the event. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Returns the name of the event.
    */
    function name( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the type description.
    */
    function description( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }


    /*!
      Returns the type location.
    */
    function location( $htmlchars=true )
      {
        if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        if ( $htmlchars == true )
	  {
            return htmlspecialchars( $this->Location );
	  }
        else
	  {
            return $this->Location;
	  }
      }

    /*!
      Returns the type url.
    */
    function url( $htmlchars=true )
      {
        if ( $this->State_ == "Dirty" )
          $this->get( $this->ID );

        if ( $htmlchars == true )
          {
            return htmlspecialchars( $this->Url );
          }
        else
          {
            return $this->Url;
          }
      }


    /*!
      Returns the date and time of the event.
    */
    function dateTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       return $date;
    }
    
    /*!
      Returns the date of the event.
    */
    function &getDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = $this->Date;

       return $date;
    }

    /*!
      Returns the duration of the event as an eZTime object.
    */
    function duration()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $time = new eZTime();
       $time->setMySQLTime( $this->Duration );

       return $time;
    }

    /*!
      Returns the start time of the event.
    */
    function &startTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLDateTime( date("Y-m-d H:i:s", $this->Date ) );

       $time = new eZTime();
       $time->setHour( $date->hour() );
       $time->setMinute( $date->minute() );
       $time->setSecond( 0 );

       return $time;
    }

    /*!
      Returns the end time of the event.
    */
    function &stopTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       if ( !is_null( $this->Date ) || $this->Date != "" )
       {
           $date->setMySQLDateTime( date("Y-m-d H:i:s", $this->Date ) );;
       }
       //$date->setMySQLTimeStamp( $this->Date );

       $start = $this->startTime();
       $time = $start->add( $this->duration() );

       return $time;
    }

    /*!
      Returns the priority, can be 0,1,2.
    */
    function priority()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Priority;
    }

    /*!
      Returns the status, can be 0,1,2.
    */
    function status()
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	return $this->Status;
      }

    /*!
      Sets the event type.
    */
    function type( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $type = new eZGroupEventType( $this->EventTypeID );
       return $type;
    }

    /*!
      Sets the event category.
    */
    function category( )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	$category = new eZGroupEventCategory( $this->EventCategoryID );
	return $category;
      }

    /*!
      Returns true if the event is private.
    */
    function &isPrivate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsPrivate == 0 )
           $ret = false;
       else
           $ret = true;
       
       return $ret;
    }    

    /*!
      Returns true if the event notification is enabled.
    */
    function &isEventAlarmNotice()
    {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

	if ( $this->EventAlarmNotice == 0 )
	  $ret = false;
	else
	  $ret = true;

	return $ret;
    }

    /*!
      Sets the name of the event.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the event.
    */
    function setDescription( $value )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        $this->Description = $value;
      }

    /*!
      Sets the location of the event.
    */
    function setLocation( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Location = $value;
    }

    /*!
      Sets the url of the event.
    */
    function setUrl( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Url = $value;
    }


    /*!
      Sets the priority of the event.
    */
    function setPriority( $value )
      {
	if ( $this->State_ == "Dirty" )
	  $this->get( $this->ID );

        $this->Priority = $value;
      }

    /*!
      Sets the status of the event.
    */
    function setStatus( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Status = $value;
    }
    
    /*!
      Sets the event date and time. Takes an eZDateTime object
      as argument.
    */
    function setDateTime( $dateTime )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( is_a( $dateTime, "eZDateTime" ) )
       {
           $this->Date = $dateTime->mysqlTimeStamp();
       }
    }

    /*!
      Sets the event type.
    */
    function setType( $type )
      {
          if ( $this->State_ == "Dirty" )
              $this->get( $this->ID );

          if ( get_class( $type ) == "eZGroupEventType" )
          {
              $this->EventTypeID = $type->id();
          }
      }

    /*!
      Sets the event category.
    */
    function setCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $category ) == "eZGroupEventCategory" )
       {
           $this->EventCategoryID = $category->id();
       }
    }

    /*!
      Sets the event Group.
    */
    function setGroup( $group )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $group ) == "eZUserGroup" )
       {
           $this->GroupID = $group->id();
       }
    }
    
    /*!
      Returns the group ID of the event group.
    */
    function groupID()
    {
        return $this->GroupID;
    }
    
    /*!
     Sets the event to private or not. 
    */
    function setIsPrivate( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsPrivate = 1;
       }
       else
       {
           $this->IsPrivate = 0;
       }
    }    
    


    /*!
     Sets the event notification property.
    */
    function setEventAlarmNotice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->EventAlarmNotice = 1;
       }
       else
       {
           $this->EventAlarmNotice = 0;
       }
    }

    /*!
      Sets the event duration.
    */
    function setDuration( $time )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( is_a( $time, "eZTime" ) )
       {
           $this->Duration = $time->mysqlTime();
       }
    }
    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    /*!  
      Public function.  	 
      Email Notice to all Members of an Group the Event is Acociated with (including all) 	 
    */ 	 
    function notification( $debug = false ) 	 
    {
  
     /* 	 
        if ( $this->IsConnected == false ) 	 
      { 	 
            $this->Database = eZDB::globalDatabase(); 	 
            $this->IsConnected = true; 	 
      } 	 
     */ 	 
  	 
    $ret = false;  	 
    // include_once( "classes/INIFile.php" );
    $ini =& $GLOBALS["GlobalSiteIni"];
    // site variables
    $siteName = $ini->read_var( "site", "SiteURL" ); 
    $siteAdministrator = $ini->read_var( "eZUserMain", "ReminderMailFromAddress" );

    $db =& eZDB::globalDatabase(); 	 
    $event_array = new eZGroupEvent(); 	 

    $eventIteration = 0;
    $eventName = $this->name();

    if ( $debug )
    print("\n## Begin Event: $eventName  ##############################\n\n");

     	 
    $current_date = new eZDate(); 	 
    $current_date_stamp = $current_date->timeStamp(); 	 
    $current_date_human = laddZero($current_date->month()) ." / ". laddZero($current_date->day()) ." / ". laddZero($current_date->year());

    if ( $debug )
      print( "current date: ". $current_date_human ."\n" ); 


    $future_date = new eZDate(); 	 
    $future_date->move(0,0,-7); 	 
    $future_date_stamp = $future_date->timeStamp(); 	 
    $future_date_human = laddZero($future_date->month()) ." / ". laddZero($future_date->day()) ." / ". laddZero($future_date->year());
    // print( "future date: ". $future_date_human ."\n"); 	 

    /*
    $event_date = new eZDateTime();
    $event_date->setMySQLTimeStamp( $this->Date ); echo("\n\n");
    $event_date_human = $event_date->month() ." / ". $event_date->day() ." / ". $event_date->year();
    */

    /*
    $zDate = new eZDateTime();
    $zDate->setMySQLTimeStamp( $this->Date );
    */

    $zDate = $this->dateTime();
    $event_date = $zDate;
    $event_date_human = laddZero($zDate->month()) ." / ". laddZero($zDate->day()) ." / ". laddZero($zDate->year());

    if ( $debug )
      print( "event date: ". $event_date_human ."\n");

    /*
    $event_date = new eZDate();
    $event_date_time = $event_date->setMySQLDate($event_date_time);

    $zDate = new eZDateTime();
    $zDate->setMySQLTimeStamp( $this->Date );
    $event_date_human = $zDate->month() ." / ". $zDate->day() ." / ". $zDate->year();

    */


    $event_date_time = $this->startTime();
    $event_date_time_human = laddZero($event_date_time->hour()) .":". laddZero($event_date_time->minute()) .":". laddZero($event_date_time->second());

    if ( $debug )
      print("event Date: $event_date_human\nevent Time: $event_date_time_human \n\n");

    if ( $debug )
      print("Iteration: ". $eventIteration ."\n\n");

    // $date_gt = $current_date->isGreater($future_date); 	
    // $date_gt = $event_date_time->isGreater($current_date); 	 
    // $date_gt = $current_date->isGreater($event_date_time); 	 
    $date_gt = $current_date->equals($event_date);

    /* 
    $date_gt = count(event_array); 	 
    print($date_gt."\n"); 	 
    */
    // if ($future_date_stamp < $current_date_stamp ) { 	 
    // if (  $current_date->isGreater($event_date_time)  ) {

    //equals  	 
    //    if (  $date_gt  ) { 	 
    // print("Current Date Matches Event Date \n\n"); 	 

      // if the event date is greater than current date
      // if it is greater (meaning active event), match event_date with current date, 
      // if they match then the message should be sent.

        $eventGroupID = $this->GroupID;

        // get users by group
	if ( $eventGroupID != 0 ) {
            $eventGroup = new eZUserGroup($eventGroupID); 
            $eventGroupID = $eventGroup->id();

	    // get all users per group
	    $eventGroupUserList = $eventGroup->users($eventGroupID);

	    if ( $debug )
	      print("group (static) : $this->GroupID  --> $eventGroupID\n");


            foreach( $eventGroupUserList as $user ) {
	      $userNotificationPreference = $user->infoSubscription();
	      if ($userNotificationPreference){
		$userEmail = $user->email();        
		$userName = $user->name();
		// build email	      
		$mailBody = "Greetings $userName,\n\nThe $siteName Calendar event $eventName is occuring today at: ". $event_date_human ." ". $event_date_time_human ."\n\n". "You have opted to recive this email notification via your user account settings. This email notication is provided by the $siteName's Calendar System and is not spam.";
		$mailSubject = $siteName ." : Calendar : Event Notification ";
	      
		$mail = new eZMail();
		$mail->to($userEmail);
		$mail->from($siteAdministrator);
		$mail->subject($mailSubject3);
		$mail->body($mailBody);
	      
		// send mail
		if (!$debug )
		  $mail->send();

		if ( $debug )
		  print("$userEmail\n$mailSubject\n\n$mailBody");
		  //print("Virtual Email: Sent To: $userEmail \n\n####################################### \n");
	      }
	    }
	}else {
          $eventGroup = new eZUserGroup();
          $eventGroupList = $eventGroup->getAll();

             
          foreach ($eventGroupList as $group) {
	    $cGroupID = $group->id();
	    $eventGroupUserList = $group->users($cGroupID);

	    if ( $debug )
	      print("group (all): $cGroupID  --> $eventGroupID \n");
	    
	    // foreach user print notice
	    foreach( $eventGroupUserList as $user ) {
	      $userID = $user->ID();
	      $userList[] = $userID;
	    }

	  }

	  
	  // ensure that per group loop we only mail to
	  $uniqueUserList = array_unique( $userList );

	  foreach( $uniqueUserList as $user_id ) {
	    $userObj = new eZUser($user_id);
	    $userObjectList[] = $userObj;
	  }



	  // foreach user print notice      
	  // foreach( $eventGroupUserList as $user ) {
	  foreach( $userObjectList as $user ) {
	    $userNotificationPreference = $user->infoSubscription();
	    if ($userNotificationPreference){
	      $userEmail = $user->email();        
	      $userName = $user->name();

	      // build mail
	      $mailBody = "Greetings $userName,\n\nThe $siteName Calendar event \"$eventName\" is occuring today at ". $event_date_human ." ". $event_date_time_human ."\n\n". "You have opted to recive this email notification via your user account settings.\nThis email notication is provided by the $siteName's Calendar System and is not spam.";
	      $mailSubject = $siteName ." : Calendar : Event Notification : $eventName";
	    
	      $mail = new eZMail();
	      $mail->to($userEmail);
	      $mail->from($siteAdministrator);
	      $mail->subject($mailSubject3);
	      $mail->body($mailBody);
	    
	      // send mail
	      if (!$debug )
		$mail->send();

	      if ( $debug )
		print("$siteAdministrator\n\n$userEmail\n$mailSubject\n\n$mailBody");
	        //print("\nVirtual Email: Sent To: $userEmail \n############################## \n");
	    }
	  }
	}
    

	if ( $debug )
	  print("\n## End $eventName ############################################ \n");
        $eventIteration++;

    /*
    } else { 	 
      print("Current Date Does Not Match Event Date \n\n"); 	 
    } 	 
    */
  	 
    $eventResponceDateMysql = $future_date_stamp; 	 


    // if (x ) 
    // consider: $ret = true;


    // $eventResponceDateMysql = "bob"; 	 
  	 
    //      $date = new eZDate( 2000, 9, 2 ); 	 
    //       $eventResponceDeadline = $event->responceDueDate(true); 	 
  	 
    // responce due date & printable 	 
    //        $eventResponceDeadline = $event->responceDueDate(); 	 
    // 	 
  	 
    //         $eventResponceDeadlineStamp = $eventResponceDeadline->timeStamp(); 	 
  	 
    //        $eventResponceDateMysql = $db->escapeString( $current_date_stamp ); 	 
    //   $eventResponceDateCheck = $db->escapeString( $eventResponseDueDate ); 	 
  	 
    // the rest of the script is bogus! 	 
 
      return $ret; 
    } 	 
  	 
    /*! 	 
      Returns every file to a event as an array of eZFile objects. 	 
    */ 	 
    function files( $as_object = true ) 	 
    { 	 
        $db =& eZDB::globalDatabase(); 	 
  	 
        $return_array = array(); 	 
        $file_array = array(); 	 
  	 
        $db->array_query( $file_array, "SELECT FileID, Created FROM eZGroupEventCalendar_EventFileLink WHERE EventID='$this->ID' ORDER BY Created" ); 	 
  	 
        for ( $i=0; $i < count($file_array); $i++ ) 	 
        { 	 
            $id = $file_array[$i][$db->fieldName("FileID")]; 	 
            $return_array[$i] = $as_object ? new eZVirtualFile( $id, false ) : $id; 	 
        } 	 
  	 
        return $return_array; 	 
    } 	 
  	 
    /*! 	 
      Deletes an file from the event. 	 
      $value can either be a eZVirtualFile or an ID 	 
  	 
      NOTE: the file does not get deleted from the file catalogue. 	 
    */ 	 
    function deleteFile( $value ) 	 
    { 	 
        if ( get_class( $value ) == "eZVirtualFile" ) 	 
        { 	 
            $fileID = $value->id(); 	 
  	 
        } 	 
        else 	 
            $fileID = $value; 	 
  	 
        $db =& eZDB::globalDatabase(); 	 
        $db->query( "DELETE FROM eZGroupEventCalendar_EventFileLink WHERE EventID='$this->ID' AND FileID='$fileID'" ); 	 
    } 	 
  	 
  	 
  	 
    /*! 	 
      Adds an file to the event. 	 
      $value can either be a eZVirtualFile or an ID 	 
    */ 	 
    function addFile( $value ) 	 
      { 	 
        if ( get_class( $value ) == "eZVirtualFile" ) 	 
        { 	 
              $fileID = $value->id(); 	 
        } 	 
        else 	 
            $fileID = $value; 	 
  	 
        $db =& eZDB::globalDatabase(); 	 
  	 
        $db->begin( ); 	 
  	 
        $db->lock( "eZGroupEventCalendar_EventFileLink" ); 	 
  	 
        $nextID = $db->nextID( "eZGroupEventCalendar_EventFileLink", "ID" ); 	 
  	 
        $timeStamp = ( (new eZDateTime)->timestamp( true ) ); 	 
  	 
    //print("INSERT INTO eZGroupEventCalendar_EventFileLink 	 
  	 
        $res = $db->query( "INSERT INTO eZGroupEventCalendar_EventFileLink 	 
                         ( ID, EventID, FileID, Created ) VALUES ( '$nextID', '$this->ID', '$fileID', '$timeStamp' )" ); 	 
  	 
        $db->unlock(); 	 
  	 
        if ( $res == false ) 	 
          $db->rollback( ); 	 
        else 	 
          $db->commit(); 	 
      } 	 
  	 
  	 
    /*!
      Returns the comments for the event.
    */
    function forum( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT ForumID FROM
                                            eZGroupEventCalendar_EventForumLink
                                            WHERE EventID='$this->ID'" );
        $forum = false;
        if ( count( $res ) == 1 )
	{
          if ( $as_object )
	    $forum = new eZForum( $res[0][$db->fieldName( "ForumID" )] );
          else
	    $forum = $res[0][$db->fieldName( "ForumID" )];
	}
        else
	  { 
            $forum = new eZForum();
            $forum->setName( $db->escapeString( $this->Name ) );

	    // NOTE:  Comment out the below line if you want to disable anonymous (not logged in) comments!!!
	    $forum->setIsAnonymous(true);

	    // NOTE:  Comment out the below line if you want moderated comments!!!
	    $forum->setIsModerated(false);

	    // NOTE:  Uncomment the below lines if you want moderated comments!!!
	    //$forum->setIsModerated(true);
	    //      $moderatorgroup = new eZUserGroup( 1 ); //should be a variable - administrator groupid
	    //            $forum->setModerator( $moderatorgroup );

            $forum->store();
	    $ini =& INIFile::globalINI();
	    $linkModules = $ini->read_var( "eZGroupEventCalendarMain", "LinkModules" );
	    $module_array = explode(',', $linkModules );
	    unset ($linkModules);
	    foreach ( $module_array as $module)
	    {
	      $moduleSubArray = explode( ':', $module );
	      list($module_name, $forum_id) = $moduleSubArray;
	      $linkModules[$module_name] = $forum_id;
	    }
            $category = new eZForumCategory( $linkModules['eZGroupEventCalendar'] );  
	    $category->addForum( $forum );
            $forumID = $forum->id();
            $db->begin( );
            $db->lock( "eZGroupEventCalendar_EventForumLink" );
            $nextID = $db->nextID( "eZGroupEventCalendar_EventForumLink", "ID" );
            $res = $db->query( "INSERT INTO eZGroupEventCalendar_EventForumLink
                                ( ID, EventID, ForumID )
                                VALUES
                                ( '$nextID', '$this->ID', '$forumID' )" );
            $db->unlock();
            if ( $res == false )
	      $db->rollback( );
            else
	      $db->commit();
            if ( $as_object )
	      $forum = new eZForum( $forumID );
            else
	      $forum = $forumID;
	}
      return $forum;
    }

    /*!
      Returns the event which a review is connected to.
    */
    function eventIDFromForum( $ForumID )
    {
        $db =& eZDB::globalDatabase();
        $EventID = 0;
        $db->array_query( $result, "SELECT EventID FROM
                                    eZGroupEventCalendar_EventForumLink
                                    WHERE ForumID='$ForumID' GROUP BY EventID" );
        if( count( $result ) > 0 )
	{
          $EventID = $result[0][$db->fieldName("EventID")];
	}
      return $EventID;
    }
// methods for processing recurring information
    /*!
      Returns true if the event is recurring.
    */
    function &isRecurring()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsRecurring == 0 )
           $ret = false;
       else
           $ret = true;
       
       return $ret;
    }    
    /*!
      Returns value of RecurType
    */
    function &recurType()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       $ret = $this->RecurType;
       
       return $ret;
    }     
    /*!
      Returns value of RecurFreq
    */
    function &recurFreq()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       $ret = $this->RecurFreq;
       
       return $ret;
    }
    /*!
      Returns an array form of RecurDay
    */
    function &recurDay()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       $ret = explode (":", $this->RecurDay);
       
       return $ret;
    }
        /*!
      Returns an array form of RecurDay
    */
    function &recurMonthlyType()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       $ret = $this->RecurMonthlyType;
       
       return $ret;
    }
    /*!
    Returns RecurMonthlyTypeInfo
    */
    function RecurMonthlyTypeInfo()
    {
    	if ( $this->State_ == "Dirty" )
        $this->get( $this->ID );
       $ret = $this->RecurMonthlyTypeInfo;

       return $ret;
    }
    /*!
      Returns RepeatUntilDate or false
    */
    function &repeatUntilDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ($this->RepeatUntilDate != '00000000000000') 
       {       
         $year = substr($this->RepeatUntilDate, 0, 4);
	 $month = substr($this->RepeatUntilDate, 4, 2);
	 $day = substr($this->RepeatUntilDate, 6, 2);
	 
	 $ret = "$year-$month-$day";
       }
       else
         $ret = false;
       
       return $ret;
    }
     
    /*!
      Returns RepeatTimes or false
    */
    function &repeatTimes()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ($this->RepeatTimes)
         $ret = $this->RepeatTimes;
       else
         $ret = 0;
           
       return $ret;
    }   
    /*!
      Returns array form of RecurExceptions
    */
    function &recurExceptions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
	 
       $ret = explode(":", $this->RecurExceptions);
       
       return $ret;
    }     
    
     /*!
      Returns repeatForever
    */
    function &repeatForever()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
	    
       $ret = $this->RepeatForever;
       
       return $ret;
    }    
    
    /*!
      Sets the IsRecurring 
    */
    function setIsRecurring( $IsRecurring )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // check if the checkbox was turned "on"
       if ($IsRecurring == 'on')
         $this->IsRecurring = 1;
       else
         $this->IsRecurring = 0;
    }
    
    /*!
      Sets the recurrance frequency.
    */
    function setRecurFreq( $RecurFreq )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // straight property set
       $this->RecurFreq = $RecurFreq;
    }
    
     /*!
      Sets the recurrance type.
    */
    function setRecurType( $RecurType )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // straight property set
       $this->RecurType = $RecurType;
    }
     /*!
      Sets the recurrance monthly type AND the type info. $date must be an eZDate or eZDateTime object.
    */
    function setRecurMonthlyType( $RecurMonthlyType = '', $date = false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
	    // strdaynum will currently always be the last of the month, because there is no other option.
       if ($RecurMonthlyType == 'strdayname') {
           // this should always be taken care of by 
          $this->RecurMonthlyTypeInfo = 'last';
       } 
       elseif ($RecurMonthlyType == 'numdayname') {
          // figure out what week we're in (this should be made a function of eZDate)
	  if ($date->day() < 8) $week = 'first';
	  elseif ($date->day() < 15) $week = 'second';
	  elseif ($date->day() < 22) $week = 'third';
	  elseif ($date->day() < 29) $week = 'fourth';
	  // we should never get to this point because of the javascript validation
	  // but if we do, we are going to turn it into 'last' rather than break stuff or throw errors.
	  else
	  $week = 'last';
	  
	  $this->RecurMonthlyTypeInfo = $week;
	}
	
       $this->RecurMonthlyType = $RecurMonthlyType;
    }
    
        
    /*!
      Sets the recurrance days in a string like 'day1:day2:day3'.
    */
    function setRecurDay( $RecurDay = false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
	// check to see if we aren't using a recur day
	if (!$RecurDay) {
	// if it isn't, set $RecurString to blank
	$RecurString = '';
	} else {
       foreach ($RecurDay as $day) {
        // build the RecurDay string (will look something like mon:thu:fri:sun)
	// if we haven't started building it yet, don't prefix it with a colon
	if (!isset($RecurString))
	 $RecurString = $day;
	else
	// if we have started building it, do prefix it with a colon
	 $RecurString .= ":$day";
       }
      }
      $this->RecurDay = $RecurString;
    }    
    /*!
      Sets the finish date to the maximum
    */
    function setFinishDateForever( $stamp = '20371230235959' )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // straight property set
       $this->RepeatForever = true;
       $this->RecurFinishDate = $stamp;
    }
    /*!
      Calculates the finish date based on the number of times, recurrance frequency,
      the recurrance type, and the date the event started.
    */
    function setFinishDateNot( $not, $freq, $type, $datetime )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // this is a somewhat complex calc (for those of us 
       // who didn't graduate algebra or prefer the mayan calendar).
       // first we must turn the ezdatetime object into a ezdate object
       // because we need the move method
       
       if ( is_a( $datetime, "eZDateTime" )) {
	// BUG in eZDate2.2 -> For some reason the day is not preserved when calling &date.
	// we have to set it manually
	// the below line should be the corrent one.
	// $date =& $date->date();
	// instead we do this stuff
	$hackYear =& $datetime->year();
	$hackMonth =& $datetime->month();
	$hackDay =& $datetime->day();
	$date = new eZDate($hackYear, $hackMonth, $hackDay);
	} //end hack
       // second we switch out the type and do 4 different calculations
       switch($type) {
       case 'day': // the easy one #uno
       // this moves the date 0 years, 0 months, and the correct amount of days
       // (recurrance frequency * the number of times to repeat) forward
	   if ($freq == 1)
        $date->move(0,0, $not);
	   else
        $date->move(0, 0, (($freq * $not)-1));
       // format this for mysql
        $datet = new eZDateTime($date->year(), $date->month(), $date->day(), $datetime->hour(), $datetime->minute(), $datetime->second());
	$stamp = $datet->mysqlTimeStamp();
       break;
       case 'week': // the hard one #uno
       /* I better note the functionality of this.
        this will find the proper week, then
        find the last day in that week
        that the user has selected for the recurring
	event. So if the user selected monday,
	tuesday, and friday the block will end on friday.
      */ 
       $date->move( 0, 0, ( $freq * 7 * $not ) );
       $lastDay = substr($this->RecurDay, -3);
       // now we use the similar logic to the month code
         if (strtolower($date->dayName(true)) != $lastDay) 
	 {
	  // this loop will move ahead a max 6 days to find the last day
	  for ($i=1; $i<7; $i++)
	  {
	   if (strtolower($date->dayName(true)) == $lastDay)
	   {
	    $i=7; //break outta the loop
	   } // end second last day check
	  } //end for loop
	 } // end first last day check
	 $datet = new eZDateTime($date->year(), $date->month(), $date->day(), $datetime->hour(), $datetime->minute(), $datetime->second());
        $stamp = $datet->mysqlTimeStamp();
       break;
       case 'month': // the hard one #dos
       // if the type is daily it's easy
       if ('daily' == $this->RecurMonthlyType) 
       {
         $date->move( 0, ($freq * $not), 0 );
	 $datet = new eZDateTime($date->year(), $date->month(), $date->day(), $datetime->hour(), $datetime->minute(), $datetime->second());
	 $stamp = $datet->mysqlTimeStamp();
       } else {
       /*
            now, this is a weird calc because eZDate just isn't built to handle
	    stuff like this. So it seems to me the easiest way to do it is to
	    kick the month forward until we are at the last month, and then
	    move the day to the correct dayName in the week. But eZDate can
	    only do the first part. Shucks.
	*/ 
	 // record the date name for later use...
	   $oldDayName = $date->dayName(true);
	   $date->move( 0, ($freq * $not) ,0 );
	   // now we are in the last month
	   // we check to see if the dayName is already the same...
	   if ( $date->dayName(true) != $oldDayName ) 
	   {
	   // it's not the same so we are going to build an associative
	   // array that we will use to build a loop based
	   // on the week we are in. Then while in that loop
	   // we will find the day that matches.
	   $dateNums = array('firsts'=>1, 
	                     'firste'=>7,
			     'seconds'=>8,
			     'seconde'=>14,
			     'thirds'=>15,
			     'thirde'=>21,
			     'fourths'=>22,
			     'fourthe'=>28,
			     'lasts'=>($date->daysInMonth() - 6), // this is the start of the last week in the month
			     'laste'=>$date->daysInMonth()); 
	     // this looks pretty funky, but it should be faster and cleaner than looping through each type
	          $startDay = $this->RecurMonthlyTypeInfo.'s';
		  $endDay = $this->RecurMonthlyTypeInfo.'e';
		//  die ($i=$dateNums[$startDay] .' and ' . $dateNums[$endDay] . ' and oldDayName is ' . $oldDayName);
	     for ($i=$dateNums[$startDay]; $i<$dateNums[$endDay];$i++) {
	     $date->setDay($i);
	      if ($date->dayName(true) == $oldDayName) {
	      $i = 32; // this will finish the loop, cuz we are on the day we want.
	     }
	    } 
	   }
	   $datet = new eZDateTime($date->year(), $date->month(), $date->day(), $datetime->hour(), $datetime->minute(), $datetime->second());
	   $stamp = $datet->mysqlTimeStamp();
       } //this ends the switch($type)
       break;
       case 'year': // the easy one #dos
       $date->move(($freq * $not), 0, 0);
       // format this for mysql
       $datet = new eZDateTime($date->year(), $date->month(), $date->day(), $datetime->hour(), $datetime->minute(), $datetime->second());
	$stamp = $datet->mysqlTimeStamp();
       break;
       }
       $this->RepeatTimes = $not;
       $this->RecurFinishDate = $stamp;
    }
    /*!
     Returns a bool toggle to add in filtering events.
    */
    function noDisplay()
    {
     if (!isset($this->noDisplay)) return false;
     else return $this->noDisplay;
    }
    /*!
     Sets a noDisplay toggle.
    */
    function setNoDisplay($bool)
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
            $this->noDisplay = $bool;
    }
    /*!
      Sets the FinishDate to the UntilDate. UntilDate comes in format yyyy-mm-dd.
    */
    function setFinishDateUntil( $UntilDate )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // prepare the until date to be turned into
       // a timestamp
       $stamp = str_replace('-', '', $UntilDate);
       $stamp .= '235959';
       $this->RepeatUntilDate = $stamp; //not necessary?
       $this->RecurFinishDate = $stamp;
    }
    
    /*!
      Sets the recur exceptions string for entry into db.
    */    
    function setRecurExceptions( $ex )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       // check to see if there are any exceptions
       if (is_array($ex)) {
       // loop through the exceptions and turn them into a string
        foreach ($ex as $x) {
	if ($x != '')	
        if (!isset($exStr)) // if the string hasn't been started, don't include a colon
          $exStr = $x;
        else // if it has then do
          $exStr .= ":$x";
        }
       } else { $exStr = ''; }
       $this->RecurExceptions = $exStr;
    }

    var $ID;
    var $Name;
    var $Description;

    var $URL;
    var $Location;
    var $EventCategoryID;
    
    var $GroupID;
    var $Date;
    var $Duration;
    var $EventTypeID;
    var $EMailNotice;

    var $EventAlarmNotice;

    /// boolean stored as an int
    var $IsPrivate;          

    /// boolean stored as an int
    var $IsRecurring;
    var $RecurExceptions;
    var $RecurDay;
    var $RecurMonthlyType;
    var $RecurMonthlyTypeInfo;
    var $RecurType;
    var $RecurFreq;
    var $RepeatForever;
    var $RepeatTimes;
    var $RepeatUntilDate;   
    var $RecurFinishDate;

    var $noDisplay;
    /// The priority of the event, values can be 0, 1, 2 where 1 is normal.
    var $Priority;
    
    /// The status of the event, values can be 0, 1, 2 where 1 is normal.
    var $Status;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
    var $Url;
    var $Time;
    var $RecurForever;
}
function filterRecurring($event_array, &$date)
{
if ($event_array["IsRecurring"])
{
                   /*
				  there are four things we must check to
				  decide if a recurring event should be included
				  the bool value IsRecurring (above)
				  With any recurring events using RepeatUntilDate or RepeatTimes,
				  we store the last date this event can occur in the column RecurFinishDate.
				  The sql statement checks to see if the current date object is past RecurFinishDate,
				  and also if the current date is in the Exceptions table for each recurring event.
				  Then finally after we have filtered these, we check the RecurType.
				  If the RecurType is day, we check to see if the day matches, 
				  then we check the RecurFreq and calculate from the day started.
				  If the RecurType is week, we check RecurFreq to see if it will be this week, 
				  then we check which days have been selected, and if that day is today, include it. 
				  If the RecurType is month, we check RecurFreq to see if this month is 
				  skipped, then we check RecurMonthType 
				  If RecurMonthType is daily(26th of the month), we simply check to see 
				  if this day matches RecurMonthTypeInfo
				  If RecurMonthType is weekdaynum( Last Tuesday of the month), we first check 
				  to see if it is the day in question in RecurMonthTypeInfo, 
				  then if it is, we check to see if the week matches.
				  If RecurMonthType numdayname(Second Friday of the month), we check to see if 
				  the day matches, then check to see if what number day it is and see if they match.
				  Finally, if RecurType is yearly, check RecurFreq first of course, and then 
				  find out if the month matches, then move to day.
				  */
				  // filter out recur exceptions if todays date matches
				  if ($event_array['RecurExceptions']) {
				     $recurExArr = explode(':', $event_array['RecurExceptions']);
				    $strMonth = eZDateTime::addZero( $date->month() );
				     $strDay = eZDateTime::addZero( $date->day());
				     $fullDateStr = $date->year().'-'.$strMonth.'-'.$strDay;
				     foreach ($recurExArr as $exKey) 
				     {
				      if ($exKey == $fullDateStr)
				      return false;
				     }
				   }
				  // for each of these cases we will be needing dates to compare.
				  // The first has already been created at the beginning of this method 
				  // as an object, $date. The second is a set of strings made from the
				  // timestamp $event_array['Date'];
				  // we will get the year, month, and day substr function
				  $rYear = substr($event_array['Date'], 0, 4);
				  $rMonth = substr($event_array['Date'], 4, 2);
				  $rDay = substr($event_array['Date'], 6, 2);
				  // now lets make the eZDate object
				  $rDate = new eZDate( $rYear, $rMonth, $rDay );
				  $rType = $event_array['RecurType'];
				  // for readability and ease of use, define extra variables
				  $rFreq = $event_array['RecurFreq'];
				  // This switch statement switches out the recurType, because
				  // we need to know what type of recurring event it is to
				  // do proper
				  // first we do a check to see if recurFreq is more than one, because if
				  // it is, we will need to run the dateDiff function which will help decide
				  // if we need to do further checks or just remove the key from the array
				    if ($rFreq > 1) {
				     // so let's create a couple arrays that the dateDiff function can read
				     $firstDate = array('year' => $rDate->year(), 'month' => $rDate->month(), 'day' => $rDate->day());
				     $secondDate = array('year' => $date->year(), 'month' => $date->month(), 'day' => $date->day());
				     // This dateDiff function returns an array that holds
				     // the date differences in year, month, and day 
				     $arrDiff = _date_diff($rDate, $date);
				    }
				  switch($rType) {
				    // if the type is day...
				    case 'day':
				  //  echo($event_array['ID'].' has a datediff of '.$arrDiff["days"].'<br>');
				    // if $arrDiff['days'] is true, we know that $rFreq is more than 1
				    // and therefore should check to see if $rFreq renders this date
				    // invalid.
				    if ($arrDiff['days']) {
				    // set $diffDiv var as the difference of days divided by the
				    // recurrance frequency
				     $diffDiv = ($arrDiff['days']) / $rFreq;
				    // this checks to see if the number has a decimal in it
				    // (ie check to see if it is a whole number)
				    // if this does NOT return false, it is NOT a whole number
				     if ( strstr( $diffDiv, '.' ) || (!$diffDiv) ) {
				     // it's not a whole number or it's 0, so this date shouldn't be considered
				     // let's remove it from the array
				      return false;
				     }
				    }
				    // if the recur freq is 1, then we will be repeating every day
				    // so we leave this event in the array 
				    break;
				    case 'week':
				    // to understand most of this section, see the day section calculations
				    if ($arrDiff['weeks']) {
				     $diffDiv = $arrDiff['weeks'] / $rFreq;
				     if ( strstr( $diffDiv, '.' ) ) return false;
				    } else {
				    }
				    if (isset($event_array)) { //if it's still there, we do the next set of checks
				    // this next section we will be doing week specific checks
				    // involving the RecurDay string.
				    // this line is fairly simple, it does a case insentive check
				    // to see if the name of the current day is in the RecurDay string
				    $RecurDay = $event_array['RecurDay'];
				    if ( !stristr( $RecurDay, $date->dayName(true) ) ) {
				      // if it's not, unset the array
				      return false;
				     }
				     }
				    break;
				    case 'month':
				    // to understand most of this section, see the day section calculations
				    if (  $arrDiff['months'] ) {
				    $diffDiv = $arrDiff['months'] / $rFreq;
				    if ( strstr( $diffDiv, '.' ) ) return false;
				    }
				    // this next section isn't pretty, and it's rather complex
				    // and was generally just a pain in the neck
				    $RecurMonthlyType = $event_array['RecurMonthlyType'];
				    if ( $RecurMonthlyType == 'daily') {
				    // daily is the easy part. if it is not the same day, unset the array
				     if ( $date->day() != $rDate->day() ) return false;
				     // we may want to add some extra logic to get the amount
				     // of days in the month and if a day is over the 28th check
				     // and see if we need to put the event on the closest
				     // day to the end of the month
				    }
				    if ( $RecurMonthlyType == 'numdayname' ) {
				     // in this section we assign the $weekNum var
				     // with first, second, third, fourth, or last
				     if ( $date->day() < 8) { $weekNum = 'first'; }
				     elseif ( $date->day() < 15) { $weekNum = 'second'; } 
				     elseif ( $date->day() < 22) { $weekNum = 'third'; }
				     elseif ( $date->day() < 29) { $weekNum = 'fourth'; }
				     // we only offer options up to the fourth week, otherwise they
				     // must check last, so that's why we don't need to worry
				     // about anything over the 28th in this section
				     else { return false; }
				      // we see if these match. If they don't, unset array
				     if ($event_array['RecurMonthlyTypeInfo'] != $weekNum) return false;
				      // finally we have to check
				      if ( $date->dayName(true) != $rDate->dayName(true) ) return false;
				      }
				   if ( $RecurMonthlyType == 'strdayname' ) {
				    // the only value this will ever be is 'last'
				    // we have to find the amount of days in this month
				    // then calculate when the last one will happen
				    // the first thing to do is check if the day is before the 22nd
				    // because if it is, it's not going to be in the last week
				    // next if the day plus seven is less than or equal to
				    // the number of days in the month, it is not the last day.
				     if ( $date->day() < 22 || ( $date->day() + 7 ) <= $date->daysInMonth() || $date->dayName(true) != $rDate->dayName(true) ) 
				       return false;

				
				    }
				    break;
				    case 'year':
				    if ($arrDiff['years']) {
				     $diffDiv = $arrDiff['years'] / $rFreq;
				     if ( strstr( $diffDiv, '.' ) ) return false;
				     }
				     // if at this point the month and day match, we are golden
				     // note the dash inbetween, its needed to seperate the two otherwise 11-5 and 1-15 are the same  :)
				     if ( ( $date->month() . '-' . $date->day() ) != ( $rDate->month() . '-' . $rDate->day() ) )
				       return false;
				    break;
				  }
				}
				return true;
       }
?>

