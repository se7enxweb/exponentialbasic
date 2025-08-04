<? 
   // eZ calendar : Client Side ( DHTML/CSS, Client Side Script )
   // #############################################################################

   // dom-drag / overlib js if-statement work around (See: doc/BUGS for description)
   if ($url_array[1] == "groupeventcalendar"){
?>
   <style type="text/css">
    /* Add this line for eZ Group Event Calendar : Module Style CSS */
    @import url(/kernel/ezgroupeventcalendar/user/templates/standard/style/style.css);
   </style>
   <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/js/dom-drag.js"></script>
   <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/overlib/overlib.js"></script>
<?
   }

   // dom-drag / overlib js if-statement work around (See: doc/BUGS for description)
   if ($url_array[1] == "groupeventcalendar" && $url_array[2] == "eventedit" ){
?>
    <link rel="alternate stylesheet" type="text/css" media="all" href="/kernel/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-system.css" title="system" />
    <? /* !-- eZGroupEventCalendar:jscalendar script dependancies -- */ ?>

    <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar.js"></script>
    <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup.js"></script>
    <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/jscalendar/lang/calendar-en.js"></script
>
    <script type="text/javascript" src="/kernel/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup-instance.js"></script>
<?
   }
?>
