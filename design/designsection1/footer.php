	<br />
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="468" height="1" border="0" alt="" /><br />
	</td>
	<td background="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/designsection1/images/menuedge-right.gif" valign="top"><img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/designsection1/images/menuedge-top-right.gif" width="20" height="60" border="0" alt="" /></td>
	<td bgcolor="#f08c00">
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="128" height="51" border="0" alt="" />

	     <?
          include( "ezuser/user/userbox.php" );
         ?>    

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/designsection1/images/articles-dummy.gif" width="122" height="17"><br />
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="1" height="5"><br />
	</td>
</tr>
<tr>
	<td width="100%">
	<div class="rightmenu">
	<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/article/articleedit/new/">Dette er en lengre tekst!</a>
	</div>
	</td>
</tr>
</table>

	</td>
</tr>
<tr>
	<td background="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/designsection1/images/menuedge-right.gif"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="20" height="1" border="0" alt="" /></td>
	<td bgcolor="#f08c00">
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="128" height="1" border="0" alt="" />

<?
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // callback for storing the stats
    $imgSrc = "$GlobalSiteIni->WWWDir$GlobalSiteIni->Index/stats/store" . $REQUEST_URI . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"0\" width=\"0\" border=\"0\" alt=\"\" />" );    
}

?><br />

	</td>
</tr>
<tr>
	<td colspan="5" bgcolor="#000000"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="1" height="38" border="0" alt="" /></td>
</tr>
</table>




</body>
</html>
