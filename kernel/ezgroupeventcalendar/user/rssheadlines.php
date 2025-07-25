<?php
// 
// $Id: articlelistrss.php,v 1.6.2.2 2003/07/22 09:55:52 vl Exp $
//
// Created on: <11-Dec-2000 09:44:51 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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


include_once( "classes/INIFile.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/eztexttool.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupnoshow.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );

include_once( "classes/ezdatetime.php" );

include_once( "classes/ezvardump.php" );

// get ini variables
$ini =& INIFile::globalINI();
$Title = htmlspecialchars($ini->read_var( "eZGroupEventCalendarRSS", "Title" ));
$Link = $ini->read_var( "eZGroupEventCalendarRSS", "Link" );
$Description = htmlspecialchars($ini->read_var( "eZGroupEventCalendarRSS", "Description" ));
$Language = $ini->read_var( "eZGroupEventCalendarRSS", "Language" );
$Encoding = $ini->read_var( "eZGroupEventCalendarRSS", "Encoding" );

$Image = $ini->read_var( "eZGroupEventCalendarRSS", "Image" );
// $CategoryID = $ini->read_var( "eZGroupEventCalendarRSS", "CategoryID" );
$GroupID = $ini->read_var( "eZGroupEventCalendarRSS", "GroupID" );
$Limit = $ini->read_var( "eZGroupEventCalendarRSS", "Limit" );
$LimitDirectionForward = true;
$RssVersion = $ini->read_var( "eZGroupEventCalendarRSS", "RssVersion" );

$headerInfo = ( getallheaders() );
$Host =  $headerInfo["Host"] ;

//debug : disable rss output
$Debug = false;

// clear what might be in the output buffer
ob_end_clean();

if (!$Debug){
  if ( $RssVersion == "0.9" ){
  // xml header
  header( "Content-type: text/xml" );
  print( "<?xml version=\"1.0\" encoding=\"$Encoding\"?>\n\n" );

  // rss header
  //print( "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\" \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n" );
  print( "<rss version=\"0.92\">\n" );

  print( "<channel>\n" );
  print( "<title>$Title</title>\n" ); 
  print( "<link>$Link</link>\n" );
  print( "<description>$Description</description>\n" );
  print( "<language>$Language</language>\n" );
  //print( "<language/>");

  // Print Channel Image Tag
  print( "<image>\n" );
  print( "<url>http://".$Host.$Image."</url>\n" );
  print( "<link>http://".$Link."</link>\n" );
  print( "<title>".Title."</title>\n" );
  print( "</image>\n" );
 }elseif ( $RssVersion == "1.0" ) {

  // xml header
  header( "Content-type: text/xml" );
  print( "<?xml version=\"1.0\" encoding=\"$Encoding\"?>\n\n" );

  // rss header
  print( '<rdf:RDF 
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns="http://purl.org/rss/1.0/"
  xmlns:dc="http://purl.org/dc/elements/1.1/">'. "\n" );

  print( '<channel rdf:about="http://'. $Host .'/groupcalendar/rss">'. "\n" );
  print( "<title>$Title</title>\n" );
  print( "<link>$Link</link>\n" );
  print( "<description>$Description</description>\n" );

  // print( "<language>$Language</language>\n" );
  // Print Channel Image Tag
	// print( "<image>\n" );
	// print( "<url>http://".$Host.$Image."</url>\n" );
	//  print( "<link>http://".$Link."</link>\n" );
	// print( "<title>".Title."</title>\n" );
	// print( "</image>\n" );
 }
} //end if(!$Debug)


// get articles. Always sort by date/time (newest first) + $Limit (5|7|30 days)
for ($i=0; $i<=$Limit; $i++){
  if($Debug)
    print("<br /> it - $i || ");

  $currentDate = new eZDateTime();
  $currentDate = $currentDate->date();

  if($LimitDirectionForward){
    $currentDate->move(0,0,"+$i");
  }else{ 
    $currentDate->move(0,0,"-$i");
  }

  $dateLimit = $currentDate;

  $dateYear = $dateLimit->year();
  $dateMonth = addZero( $dateLimit->month() );
  $dateDay = addZero( $dateLimit->day() );
  $date = $dateYear ."-". $dateMonth ."-". $dateDay ; //." ".  $dateHour .":". $dateMinute;

  if($Debug)
    print "$date <br />";

  if ( $GroupID == 0)
  {
    $event = new eZGroupEvent();
    if($Limit){
      $eventList = $event->getAllByDate($dateLimit);
    }else {
      $eventList = $event->getAll();
    }
  } 
  else
  {
    $event = new eZGroupEvent();
    $eventList = $event->getByGroup($GroupID);
  }

  // build combined result set
  if($Debug){
    // Var_Dump::display($eventList);
    print("EventListIterationCount: ". sizeof($eventList)."<br />");
  }

  for ($e=0; $e<sizeof($eventList); $e++){
    $it = $eventList[$e];
    if( $it != false)
      $eventListLimited[] = $it;
    if($Debug)
      print "$e : EventListIterationObj: ". $eventList[$e] ."<br />";
  }
}

if($Debug)
   print("<br />|| ". sizeof($eventListLimited)." || <br />");

// Var_Dump::display($eventListLimited);

if ( $RssVersion != "0.9" ){
  print("<items>\n");
  print("<rdf:Seq>\n");
  foreach( $eventListLimited as $event )
  {
    $eventID = $event->id();
    print('<rdf:li resource="http://'. $Host .'/groupeventcalendar/eventview/'. $eventID .'/"/>'. "\n");    
  }
  print("</rdf:Seq>\n");
  print("</items>\n");
  print("</channel>\n");
}

 $locale = new eZLocale( $Language );

 foreach( $eventListLimited as $event )
 {
    $eventID = $event->id();
    $description = $event->description();

    /* 
     prefix relative Links in href and src attributes with the Hostname, 
     so the feed does not contain relative links and feedreaders can parse the links and show the images.
    */
    $description = str_replace("href=\"/", "href=\"http://".$Host."/", $description);
    $description = str_replace("src=\"/", "src=\"http://".$Host."/", $description);

    $date = $event->dateTime();

    /*
     $date = $date->month() ."/". $date->day() ."/". $date->year() ." ". $date->hour() 
     .":". $date->minute() .":". $date->second();
    */

    $dateYear = $date->year();
    $dateMonth = addZero( $date->month() );
    $dateDay = addZero( $date->day() );
    $dateHour = addZero( $date->hour() );
    $dateMinute = addZero( $date->minute() );
    $dateSecond = addZero( $date->second() );

    // $date = $dateMonth ."/". $dateDay ."/". $date->year() ." ". $dateHour .":". $dateMinute .":". $dateSecond;
    // $date = $date->year() ."-". $dateMonth ."-". $dateDay;
    //" ". $dateHour .":". $dateMinute .":". $dateSecond;
    //    $date = $dateYear ."-". $dateMonth ."-". $dateDay;
       $date = $dateYear ."-". $dateMonth ."-". $dateDay ." ".  $dateHour .":". $dateMinute;
    // $date = $dateYear ."-". $dateMonth ."-". $dateDay;

    $isRepeat = "";
    if($event->isRecurring())
      $isRepeat = "(Repeat Event)";
      //      $isRepeat = "(Repeat Event : $eventDates)";

    $description .= "\n". "Start: $date $isRepeat";
    $description = strip_tags( $description );

    $description = strip_tags( $description , "<a>");

    // $description = htmlspecialchars( $description );


    if ( $RssVersion == "0.9" ){
  
    print( "<item>\n" );
    print( "<title>" . htmlspecialchars($event->name()) . "</title>\n" );
    print( "<link>http://" . $Host . "/groupeventcalendar/eventview/$eventID/</link>\n" );
    
    //      $published = $event->published();
    //      print( $locale->format( $published ) );

    // encode HTML special character like < , > and " and print the tag   
     print( "<description>". $description ."</description>\n" );    
     print( "</item>\n" );
    }elseif ( $RssVersion == "1.0" ) {
      print('<item rdf:about="http://'. $Host .'/groupeventcalendar/eventview/'. $eventID .'/">'. "\n");
      print("<title>". htmlspecialchars($event->name()) . "</title>\n" );
      print( "<description>". $description ."</description>\n" );
      print( "<link>http://" . $Host . "/groupeventcalendar/eventview/$eventID/</link>\n" );
      print("<dc:date>".$date."</dc:date>\n");  
      print("</item>\n");
    }
 }

 if ( $RssVersion == "0.9" ){
   print( "\n</channel>\n" );
   print( "\n</rss>\n" );
 }elseif ( $RssVersion == "1.0" ) {
   print("</rdf:RDF>\n");
 }

//Adds a "0" in front of the value if it's below 10.
function addZero( $value )
{
  settype( $value, "integer" );
  $ret = $value;
  if ( $ret < 10 )
    {
      $ret = "0". $ret;
    }
  return $ret;
}

exit();

?>

