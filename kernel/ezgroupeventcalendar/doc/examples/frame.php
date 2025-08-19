<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title><?php
// set the site title

$SiteTitle = $ini->variable( "site", "SiteTitle" );

if ( isset( $SiteTitleAppend ) )
    print( $SiteTitle . " : " . $SiteTitleAppend );
else
    print( $SiteTitle );

?></title>


<?php

// check if we need a http-equiv refresh
if ( isset( $MetaRedirectLocation ) && isset( $MetaRedirectTimer ) )
{
    print( "<META HTTP-EQUIV=Refresh CONTENT=\"$MetaRedirectTimer; URL=$MetaRedirectLocation\" />" );
}

?>

<style type="text/css">
@import url(/design/<? print ($GlobalSiteDesign); ?>/main.css);

/* Add this line for eZ Group Event Calendar : Module Style CSS */
@import url(/ezgroupeventcalendar/user/templates/standard/style/style.css);
</style>
<?
// dom-drag / overlib js if-statement work around (See: doc/BUGS for description)
if ($url_array[1] == "groupeventcalendar"){
?>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/js/dom-drag.js"></script>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/overlib/overlib.js"></script>
<?
}
?>
<?

/* Previous I.E. PNG-Alpha & PNG-Alpha Background Image page (90% implimented, incomplete) */
/*
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/js/ypSlideOutMenusC.js"></script>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/js/main.js"></script>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/js/browserdetect_lite.js"></script>
<script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/js/opacity.js"></script>

<script type="text/javascript">
function init() {
  var layerObject = null;
  if (pngAlpha) {
    for (i=0;i<document.getElementsByTagName("td").length; i++) {
      if (document.getElementsByTagName("td").item(i).className == "bgdark"){
	document.getElementsByTagName("td").item(i).style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/kernel/ezgroupeventcalendar/user/templates/standard/images/gcalEventTransBg.png', sizingMethod='scale')";
	// alert("Message from resident coder: Sorry guys, almost done... " + document.getElementsByTagName("td").item(i).style.filter);
      }
    }
    //document.getElementById("bgdark").style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='../skin1/images/background/infobg40.png', sizingMethod='scale')";
  }
}
</script>
*/
?>

<?
/*
  These Javascript functions are stock from Exponential Basic 2 and are required for the eZ GroupEventCalendar's Editor Mode
*/
?>
<script language="JavaScript1.2" type="text/javascript">
<!--//

   function MM_swapImgRestore()
   {
      var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
   }

   function MM_preloadImages()
   {
      var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
      var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
      if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
   }

   function MM_findObj(n, d)
   {
      var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
      d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
      if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
      for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
   }

   function MM_swapImage()
   {
      var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
      if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
   }

//-->
</script>

<!-- set the content meta information -->

<meta name="author" content="<?php

    $SiteAuthor = $ini->variable( "site", "SiteAuthor" );
    print( $SiteAuthor );

?>" />
<meta name="copyright" content="<?php

    $SiteCopyright = $ini->variable( "site", "SiteCopyright" );
    print( $SiteCopyright );

?>" />
<meta name="description" content="<?php

if ( isset( $SiteDescriptionOverride ) )
{
    print( $SiteDescriptionOverride );
}
else
{
    $SiteDescription = $ini->variable( "site", "SiteDescription" );
    print( $SiteDescription );
}

?>" />
<meta name="keywords" content="<?php
if ( isset( $SiteKeywordsOverride ) )
{
    print( $SiteKeywordsOverride );
}
else
{
    $SiteKeywords = $ini->variable( "site", "SiteKeywords" );
    print( $SiteKeywords );
}

?>" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="Exponential Basic" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

<? /* part of ie alpha-png fix */ ?>
<!--[if gte IE 5.5000]>
<script type="text/javascript" src="/design/ezc_developer/pngfix.js"></script>
<![endif]-->

<?
// dom-drag / overlib js if-statement work around (See: doc/BUGS for description)
?>
<?
if ($url_array[1] == "groupeventcalendar" && $url_array[2] == "eventedit" ){
?>
    <link rel="alternate stylesheet" type="text/css" media="all" href="http://web.archive.org/web/20060822010037/http://ezcommunity.net/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-system.css" title="system" />

    <!-- eZGroupEventCalendar:jscalendar script dependancies -->

    <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar.js"></script>
    <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup.js"></script>
    <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/lang/calendar-en.js"></script>

    <script type="text/javascript" src="/ezgroupeventcalendar/user/templates/standard/jscalendar/calendar-setup-instance.js"></script>
<?
}
?>
</head>

<body bgcolor="#000000" onload="MM_preloadImages('<? print $GlobalSiteIni->WWWDir; ?>/images/document/filemanager/redigerminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/document/slettminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/document/filemanager/downloadminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/admin_edit_images/addminimrk.gif')">

<div align="center">
<table width="91%" border="0" cellspacing="1" cellpadding="1" style="background-color: #000000; border: 0px dashed;">
<tr>
   <td>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   <td class="tdmini" width="99%" align="left">
<?php
/*
<!--
<img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/ez_systems_branding/ezpublish-yourcontentmadeeasy.gif" height="20" width="290" border="0" alt="" /><br />
-->

<!--
<span style=""><a href="/" style="font-size: 23px; text-decoration: none; ">eZ&nbsp;Community&nbsp;.&nbsp;net</a></span><br />
-->
*/
?>
<a href="/" style="font-size: 24px; color: #ffffff; text-decoration: none; padding-left: 3px; padding-botom: 4px;"><img src="/design/ezc_standard/images/logo-grey-mini.png" height="28" width="28" border="0" align="middle" alt="icon" />&nbsp;eZ&nbsp;Community</a>
<?php
/*
<!--
&nbsp;
<span>&nbsp;&nbsp; <a href="/" style="font-size: 17px; color: #ffffff; text-decoration: none; "><span style="color: #2372c9;">&nbsp;</span>eZ&nbsp;Community</a></span>
-->
*/
?>
   </td>
   <td class="tdmini" width="1%" valign="bottom" align="right">
    <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/document/1x1.gif" width="1" height="10" border="0" alt="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-left.gif" height="20" width="20" border="0" alt="" /><br />
   </td>

   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/community/">Community</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">
<img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />

<!--
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-mrk-left.gif" height="20" width="20" border="0" alt="" /><br />
-->
   </td>

   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/forums/">Forum</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />

<!--

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-left.gif" heigh\
t="20" width="20" border="0" alt="" /><br />

-->
   </td>

   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/community/developer/">Developer</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-mrk.gif" height="20" width="20" border="0" alt="" /><br />

<!--

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-left.gif" height="20" width="20" border="0" alt="" /><br />

-->
   </td>

   <td class="tab" bgcolor="#ffffff" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/calendar/">Calendar</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-mrk-right.gif" height="20" width="20" border="0" alt="" /><br />

<!--
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
-->
   </td>

<? /*

<!--
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-left.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
-->

   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-standard/">Standard</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-trade/">Trade</a>&nbsp;&nbsp;</td>


   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-intranet/">Intranet</a>&nbsp;&nbsp;</td>

   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-news/">News</a>&nbsp;&nbsp;</td>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-right.gif" height="20" width="20" border="0" alt="" /><br />
   </td>

   */ ?>
</tr>
</table>

   </td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">

   <td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/document/1x1.gif" width="2" height="1" border="0" alt="" /></td>
   <td width="96%" bgcolor="#ffffff" align="left">
    <?
   //    <!-- Banner start -->
   //    $CategoryID = 1;
     $CategoryID = 2;
    $Limit = 1;
    include( "ezad/user/adlist.php" );
    //    <!-- Banner end-->
    ?>

    <?
    // <!-- Main content view start -->
    print( $MainContents );
    ?>
    <!-- Main content view end -->
    <br />
    </td>
    <td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/document/1x1.gif" width="2" height="1" border="0" alt="" /></td>

    <td width="1%" bgcolor="#ffffff" align="left">
    <div class="bglight">
    <?
    // a short list of articles from the given category
    // shows $Limit number starting from offset $Offset
    //    $CategoryID=1;
    $CategoryID=8;
    $Offset=1;
    $Limit=0;
    include( "ezarticle/user/smallarticlelist.php" );
    ?>


<?
          include_once( "classes/ezdate.php" );
          include_once( "ezuser/classes/ezuser.php" );
          $bdate = new ezdate();
          $now_date = ucfirst($bdate->monthName()) . " " . $bdate->day() . ", " .  $bdate->year();
         ?>
         <style type="text/css">
         .LayerMenuDate {
         position:relative;
         left:0px; right:0px;
         color : rgb(0, 0, 0);
         font-family : Arial, Helvetica, sans-serif;
         font-size : 12px;
         font-weight: bold;

         padding-top: 3px;
         padding-bottom: 6px;
         }
         </style>
         <div class="LayerMenuDate" style="text-align: center;"><span class=navitem onclick='rdrctMain(""); return true;'><? print($now_date); ?></span></div>


    <?
    //  include( "ezsearch/user/menubox.php" );
    ?>

<!--
    <div align="center" style="border: 4px;"><a class="path" href="?PrintableVersion=enabled">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/button-printpage.gif" border="0"/></a><br /><br /></div>
-->


    <?
    $NoAddress = true;
    include( "ezuser/user/userbox.php" );

//    include("ezgroupeventcalendar/user/menubox.php" );
    ?>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f0f0f0" align="left">

   <? /*Left menu start */ ?>
   <?
    // include the static pages for category 2
//    $CategoryID = 1;
    $CategoryID = 8;
//    include( "ezarticle/user/articlelinks.php" );
   ?>
   <?
    $CategoryID = 7;
   include( "ezarticle/user/menubox.php" );
   ?>

    <?

    //    $CategoryID = 1;
    $CategoryID = 8;
//    include( "ezarticle/user/headlines.php" );

    ?>

</td>
</tr>

<tr rowspan="2" valign="bottom">
    <td width="1%" bgcolor="#fffff" align="left">

  <br /><br />
  <div align="center">

   <a target="_blank" style="text-decoration: none;" href="http://developer.berlios.de/projects/ezcommunity" title="eZ Community is a BerliOS Developer Project">
<img src="http://developer.berlios.de/bslogo.php?group_id=2154" width="100px" height="32px" border="0" alt="BerliOS Developer Logo">
    <br /><a href="/article/articleview/161" style="text-decoration: none;">eZ Community's BerliOS Project</a>

    <br /><br />

    <a target="_blank" href="http://firebright.com/" style="text-decoration: none;" title="Sponsored By FireBright">
    <img src="/images/firebright/firebright_sponsor.png" border="0" width="85" height="35" alt= "Sponsored By FireBright" />
    <br />
    <a href="/article/articleview/153/1/13/" style="text-decoration: none;">Sponsored By FireBright</a>

    </div>

    <br /><br />

    <?
//    include( "ezlink/user/menubox.php" );
    ?>

</td>
</tr>
</table>

    <?
//    include( "ezpoll/user/votebox.php" );
    ?>

<? /*
   <!-- Right menu end -->
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/document/1x1.gif" width="80" height="20" border="0" alt="" /><br />

*/ ?>
   <div align="center">

    <?
//    include( "ezlink/user/menubox.php" );
    ?>

<? /*
   <!--
   <br /><br />
   <span>ezcommunity : ezcommunity v1 in development</span>
   <span>ezcommunity : gpublish v1 in development</span>
   -->
*/
?>

     <?
     // include( "eznewsfeed/user/menubox.php" );
     ?>
     <?
     $CategoryID = 6;
     // include( "eznewsfeed/user/headlines.php" );
     ?>

   </div>

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/document/1x1.gif" width="100" height="8" border="0" alt="" /><br />

   </td>
</tr>
</table>

   </td>
</tr>
</table>


</div>

<?
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->variable( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // create a random string to prevent browser caching.
    $seed = md5( microtime() );
    // callback for storing the stats
    $imgSrc = $GlobalSiteIni->WWWDir . "/stats/store/rx$seed-" . $REQUEST_URI . "1x1.gif";
    print( "       ". "<span><img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" /></span>" );
}

?>

</body>
</html>

<?php 
// phpinfo(); 
?>
