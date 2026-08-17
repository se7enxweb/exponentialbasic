	<!-- Main content view end -->

	<br />
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="<?php print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="" /></td>

	<td width="1%" bgcolor="#f0f0f0">

   	<!-- Right menu start -->
    
    <?php
    include( "ezuser/user/userbox.php" );
	?>

    <?php
     $categoryID = 1;
//    include( "ezarticle/user/smallarticlelist.php" );
    include( "ezarticle/user/headlines.php" );
    ?>


    <?php
    include( "ezpoll/user/votebox.php" );
    ?>

    <?php
    include( "ezquiz/user/menubox.php" );
    ?>
        
	<hr noshade="noshade" size="4" />
	
    <?php
    $session = eZSession::globalSession();


if ( $session->fetch() == false )
{
    $session = eZSession::globalSession();
    $session->store();    
}

if ( $Design == 1 )
{
    $session->setVariable( "SiteDesign", "intranet" );
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $_SERVER['REQUEST_URI']" );
    exit();
}

if ( $Design == 2 )
{
    $session->setVariable( "SiteDesign", "trade" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $_SERVER['REQUEST_URI'] ) && ( $_SERVER['REQUEST_URI'] != "" ) )
    {
        $redir = $_SERVER['REQUEST_URI'];
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}

if ( $Design == 3 )
{
    $session->setVariable( "SiteDesign", "news" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $_SERVER['REQUEST_URI'] ) && ( $_SERVER['REQUEST_URI'] != "" ) )
    {
        $redir = $_SERVER['REQUEST_URI'];
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}


    ?>
	
	<h2>Alternative sitedesigns:</h2>
    <a href="<?php print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $_SERVER['REQUEST_URI'] . "?Design=1"); ?>"><b>Intranet</b></a><br />
    <a href="<?php print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $_SERVER['REQUEST_URI'] . "?Design=2"); ?>"><b>Trade</b></a><br />
    <a href="<?php print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $_SERVER['REQUEST_URI'] . "?Design=3"); ?>"><b>News</b></a><br />

   	<!-- Right menu end -->
	
	<img src="<?php print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="20" border="0" alt="" /><br />
	
	<div align="center">
	<a target="_blank" href="http://developer.ez.no"><img src="<?php print $GlobalSiteIni->WWWDir; ?>/design/base/images/logo/exponential-basic-100x35-trans-lgrey.png" width="100" height="35" border="0" alt="Powered by Exponential Basic" /></a>
	</div>
	
	<img src="<?php print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />
	 
	</td>
  </tr>
</table>

<?php
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->variable( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // callback for storing the stats
    $imgSrc = "/stats/store" . $_SERVER['REQUEST_URI'] . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );    
}

?>

</body>
</html>
