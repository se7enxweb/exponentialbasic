<?php
///////////////////////////////////////////////////////////////////
// include page header : (metakeywords,css,js,ico,etc)
 include_once("design/$GlobalSiteDesign/frame_head.php"); 

// begin page body : (toplevel container)
///////////////////////////////////////////////////////////////////
?>
<body>
<div class="body" id="container">
  <div class="all">
    <div id="header">

<? /*

	<!-- start #container -->
*/ ?>
<? /*


   <!-- start #banner -->

<map name="ezmap1">
<area shape="poly" alt="FullThrottle.com" coords="155,48, 152,44, 149,44, 145,44, 145,48, 149,53" href="/">
<area shape="poly" alt="FullThrottle.com" coords="135,44, 127,49, 127,53, 131,53, 140,48, 140,44" href="/">
<area shape="poly" alt="FullThrottle.com" coords="118,59, 122,59, 124,61, 122,64, 118,66, 113,63" href="/">
<area shape="poly" alt="FullThrottle.com" coords="65,51, 66,46, 69,45, 70,45, 71,31, 77,24, 75,19, 75,14, 78,6, 84,3, 91,3, 96,8, 98,10, 98,17, 108,16, 121,27, 127,34, 128,37, 125,39, 127,41, 133,39, 141,40, 139,42, 132,42, 123,49, 113,59, 110,65, 107,65, 104,70, 108,71, 113,75, 117,80, 119,82, 123,81, 124,75, 127,73,
132,71, 133,70, 130,62, 146,55, 147,56, 149,59, 152,64, 134,71, 142,71, 146,73, 150,75, 151,78, 152,76, 156,65, 159,60, 165,59, 168,59, 171,63, 171,67, 170,69, 160,89, 164,96, 164,101, 164,110, 130,110, 127,106, 125,102, 123,96, 122,94, 122,89, 121,84, 123,82, 120,83, 122,99, 121,106, 119,107, 118,107, 111,107, 106,103,
100,98, 94,86, 85,85, 83,83, 82,78, 83,73, 86,68, 91,62, 93,58, 90,54, 84,50, 82,47, 81,40, 80,41, 80,46, 81,50, 80,53, 80,56, 83,57, 85,57, 85,60, 85,62, 84,63, 82,64, 74,64, 71,61, 70,58, 70,56, 71,52" href="/">
</map>

*/ ?>
     <div id="banner"><a href="/"><img class="logoImage" src="/design/ecommerce/images/bannerBg.png" usemap="#ezmap1" border="0" style="position: relative; width: 24rem; height: 98%;" /></a></div>
<? /*


    <!-- start #cart -->
*/ ?>
        <div id="cart">
          <div class="row">
            <span class="cartTopRowLeft">
			<?
			include_once( "kernel/ezuser/classes/ezuser.php" );
			$user =& eZUser::currentUser();
			if ($user) {
				echo "Welcome back ".$user->firstName()."</span>";
				echo "<span class=\"cartTopRowRight\"><a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/account/logout\">Logout</a></span>";
				
		//	else	{
			/* echo "<form action=\"post\" method=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/login/login/\">
            <input type=\"text\" value=\"Username\"/><input type=\"text\" value=\"Password\"/>
          </form>";  
		  		echo "<a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/account/login\">Login</a></span>";
				echo "<span class=\"cartTopRowRight\"><a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/signup\">Signup</a></span>";
		  		}    */
		  echo "</div>
            <div class=\"row\">
            <div id=\"myStuff\">
              <a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/account/\">My Account</a><br />
              <a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/wishlist/\">My Wish List</a><br />
              <a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/orderlist/\">Order Status</a><br />
            </div>
			
            <div id=\"cartButtons\">
              <form method=\"post\" action=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/cart/\">
                <input id=\"viewcart\" type=\"submit\" value=\"VIEW CART\" /></form><br />
			  <form method=\"post\" action=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/cart/\">
               <input id=\"checkout\" type=\"submit\" name=\"DoCheckOut\" value=\"CHECKOUT\" />
              </form>
            </div>
          </div>
        </div>";
           }
		else
		{
	   /* echo "<form action=\"post\" method=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/login/login/\">
            <input type=\"text\" value=\"Username\"/><input type=\"text\" value=\"Password\"/>
          </form>";  */
		  		echo "<a style=\"padding-right: 3px;\" href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/forgot/\">Forgot password?</a></span>";

				//				echo "<span class=\"cartTopRowRight\"><a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/userwithaddress/new/\">Signup</a></span>";
	echo "<span class=\"cartTopRowRight\"><a href=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/new/\">Signup</a></span>";
   
		  echo "</div>
          <div class=\"row\">
            <div id=\"myStuff\">
				<form method=\"post\" action=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/user/login/login/\">
            Username:<input type=\"text\" name=\"Username\" value=\"Username\" onfocus=\"clearText(this)\" /><br />Password:<input value=\"Password\" type=\"password\" name=\"Password\" onfocus=\"clearText(this)\" />
			<input type=\"hidden\" name=\"RedirectURL\" value=\"".$REQUEST_URI."\" />
			  <input id=\"login\" type=\"submit\" value=\"OK\" />
			  </form>
            </div>
			
			<div id=\"cartButtons\">
              <form method=\"post\" action=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/cart/\">
                <input id=\"viewcart\" type=\"submit\" value=\"VIEW CART\" /></form><br />
			  <form method=\"post\" action=\"".$GlobalSiteIni->WWWDir.$GlobalSiteIni->Index."/trade/cart/\">
                <input id=\"checkout\"type=\"submit\" name=\"DoCheckOut\" value=\"CHECKOUT\" />
				</form>
            </div>
          </div>
        </div>";
				
	}
	?>
<? /*

        <!-- end #cart -->
*/ ?>
     </div>
<? /*

      <!-- end #banner -->
      <!-- <li id="m-home"><a onmouseover="document.getElementById('home').src='/design/ecommerce/images/home2.gif';" onmouseout="document.getElementById('home').src='/design/ecommerce/images/home.gif';" id="homeHack" href="/<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>"><img id="home" src="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/images/home.gif" alt="Full Throttle home" /> </a></li> -->
*/ ?>
   <div id="menuContainer">
     <div id="navcontainer">
       <ul id="navlist">
         <li id="m-home"><a href="/">Home</a></li>
         <li id="m-reviews"><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/shop">Shop</a></li>
         <li id="m-reports"><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/groupeventcalendar/monthview">Calendar</a></li>
         
         <li id="m-gallery"><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/gallery">Photo Gallery</a></li>
         <li id="m-install"><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/contact">Contact Us</a></li>
        </ul>
      </div>
     </div>
<? /*
     <!-- end #menuContainer -->
     <!-- start #left -->
 */ ?>
</div>
<div class="main-body">

      <div class="menu-left" id="left">
        <div id="wrapper">
          <div class="searchBox">
          <form action="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/search/" method="get">
       	    <input type="hidden" name="SectionIDOverride" value="3" />
            Site Search <br />
            <input type="text" name="SearchText"/> <input type="submit" value="GO" id="searchBut" />
          </form>
          </div>
	  <dl><dt id="dtNone"></dt>
          <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/news">News and Updates</a></dd>
          <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/reviews">In-Depth Reviews</a></dd>
          <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/reports">Trip Reports</a></dd>


<? /*
          <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/wishlists/find">Find A Wishlist</a></dd>
     
           <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/trade/sitemap">Products Sitemap</a></dd>
           <dd><a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/article/sitemap">Sitemap:Articles</a></dd>
   */ ?>
           <?  
            $CategoryID = 0;
            include( "kernel/eztrade/user/categorylist.php" ); 	
            include( "kernel/ezforum/user/menubox.php" );
            /* include( "kernel/ezforum/user/latestmessages.php" ); */
            include( "kernel/ezlink/user/menubox.php" );

            // include the static pages for category 5
            $CategoryID = 5;
            include( "kernel/ezarticle/user/articlelinks.php" );
           ?>
	  </dl>
	
        </div>
      </div>

      <? /*
         <!-- start #content -->
	 <!-- Main content view start -->
*/ ?>
	
         <div id="content">
         <? /*      <!-- start #menuContainer -->       */ ?>
         <? /*      <!-- end #left -->   */ ?>
	 <?
              print( $MainContents );
         ?>
	        </div>
	 <? /*
	    <!-- Main content view end -->	
            <!-- end #content -->
	    */ ?>

    </div>

	 <? /*      <!-- start #footer -->    
           <!-- <div id="footer-b1" style="width: 100%; z-index: 2; left: -10px; background-color: #e0e7e9;"> -->
          */ ?>
   </span>

</div>
<div>
<?

    ///////////////////////////////////////////////////////////////////
    /* !-- <span id="footer-b1" style="width: 170px; z-index: -1; left: -10px; background-color: #ffffff;">&nbsp;</span><span id="footer-b1" style="position: relative; left: 160px; background-color: #ffffff;"> --> 
    */
    ///////////////////////////////////////////////////////////////////
?>
<? /* !-- begin #lignt-footer -- */ ?>
<? 
   // Begin : Footer : (Hard Break From Above) 
   ///////////////////////////////////////////////////////////////////
?>

<form action="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/bulkmail/singlelist" method="post">
<div id="footerWrap" style="position: relative;">
        <div align="center" style="width: 100%; margin-top: 0px; z-index: 1; padding-top: 0px; padding-bottom: -1px; background-color: #e0e7e9;">
          <span style="padding-bottom: 4px;"><strong>Receive email updates on sales and special offers:</strong></span> 
          <br />
          <div align="center" id="footer-a1" style="padding-top: 2px; background-color: #e0e7e9; font-size: .8em;">
           <input type="text" name="Email" value="Enter Email Address" onfocus="clearText(this)" /> &nbsp;
           <input type="submit" name="SubscribeButton" value="Subscribe" id="emailGo" />&nbsp;
           <input type="submit" name="UnSubscribeButton" value="Unsubscribe" id="emailNo" />
          </div>
        </div>
</form>

        <div align="center" style="width: 100%; padding-top: 10px; padding-bottom: 13px; font-size: 13px; background-color: #e0e7e9;">
          <a href="/">Home</a> |
          <a href="/sitemap/article">Article Sitemap</a> |
          <a href="/sitemap/product">Product Sitemap</a> |
          <a href="/gallery">Photo Gallery</a> |
          <a href="/forums">Forums</a> |
          <a href="/links">Links</a>
        </div>
       <? /* !-- end #lignt-footer -- */ ?>

        <div align="center" style="width: 100%; vertical-align: bottom; margin-top: 0px; padding-top: 4px; padding-bottom: 8px; font-size: 11px; background-color: #c2cfe5;">
          <div>fullthrottle.com is a <a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/policy/security">secure</a> site that respects your <a href="<? echo $GlobalSiteIni->WWWDir.$GlobalSiteIni->Index; ?>/policy/privacy">privacy</a></div>

	  <div class="poweredByLogo">
	      <div align="center"><a target="" href="/about"><img src="/design/base/images/logo/powered-by-ezpublish-100x35-trans-lgrey.gif" width="90" height="35" border="0" alt="Powered by eZ publish"></a></div>
              <div class="poweredBy">Powered by <a href="https://basic.ezpublish.one">eZ Publish Basic</a> version <a href="/about"><? echo eZPublish::version(); ?></a></div>
	  </div>
          <? $SiteCopyright = $ini->read_var( "site", "SiteCopyright" ); ?>
          <div class="copyright"><? echo $SiteCopyright; ?></div>

	</div>
 </div></div>
	  <? /* <!-- end #footerWrap -- */ ?>
    </form>
</div>
</div>
<?

//
// Store Stats
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
///////////////////////////////////////////////////////////////////
//

$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // create a random string to prevent browser caching.
    $seed = md5( microtime() );
    // callback for storing the stats
    $imgSrc = $GlobalSiteIni->WWWDir . "/stats/store/rx$seed-" . $REQUEST_URI . "/1x1.gif";
    print( "\r<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );
}

// begin close body and page tags  : (re: toplevel container)
///////////////////////////////////////////////////////////////////
?>
  </body>
</html>