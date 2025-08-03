<?php  
  // eZ publish : Full Throttle : Design : Page : Header 
  // #############################################################################

  // eZ publish : Application : Doc Type (Headers)
  // #############################################################################
  //echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\">";
?>
<!DOCTYPE html>

<html lang="en-US">
<head>
<?php

   // eZ publish : Application : Doc Type / Content Type && Primary Application StyleSheet
   // #############################################################################
   // <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
?>	
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?php

    // eZ publish : Application : Page Title + Breadcrumb
    // #############################################################################

    // print($SiteTitleAppend);
    if ( isset( $Title ) && !isset($SiteTitleAppend ) )
       print( "$Title");
    elseif ( isset($SiteTitleAppend ) )
       print( "$Title : $SiteTitleAppend");
    else
       print( $ini->read_var( "site", "SiteTitle" ) );
?></title>
<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/css/mainStyle.css" />
<?php 

   // eZ publish : Application : Page Meta Keywords
   // ############################################################################# 
   include_once("design/$GlobalSiteDesign/frame_head_keywords.append.php");


   // eZ publish : application : refreesh | Check if we need a http-equiv refresh
   // #############################################################################

   if ( isset( $MetaRedirectLocation ) && isset( $MetaRedirectTimer ) )
   {
       print( "<META HTTP-EQUIV=Refresh CONTENT=\"$MetaRedirectTimer; URL=$MetaRedirectLocation\" />" );
   }


   // eZ calendar : Client Side ( DHTML/CSS, Client Side Script )
   // #############################################################################
   include_once("design/$GlobalSiteDesign/frame_head_calendar.append.php");
 

   // Standard Javascript , Favorite Icon, SmartTags Settings
   // #############################################################################
?>
   <script type="text/javascript" src="/design/ecommerce/js/ezimage.js"></script>
   <script type="text/javascript" src="/design/ecommerce/js/main.js"></script>
<?php /*
   <script type="text/javascript">
     window.onload = init; 
     // window.onload = alert("fnrt");
   </script>
*/ ?>
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
   <meta name="MSSmartTagsPreventParsing" content="TRUE" />
</head>
