<?
// include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$UserSiteURL = $ini->read_var( "site", "UserSiteURL" );

$referer = $_SERVER["HTTP_REFERER"];
$referer = stristr ( $referer, '/user/withaddress' );
$shost = "https://"."$UserSiteURL";

if ($referer){
  $account_header = "Account Registration Complete - Thank You!";
}else {
  $account_header = "Full Throttle : Account";
}

// phpinfo();
// HTTP_REFERER http://dev.FullThrottle.com/user/userwithaddress

?>
<div id="breadcrumbs">
&nbsp; 
</div>

<div id="contentWrap">
  <h1 class="mainHeading"><? echo $account_header ?></h1>

  <table border="0" cellspacing="5" cellpadding="8" width="600" style="font-size:10;">
  <tr>
    <td align="right" width="250">
      <a href="<? print $shost ?>/user/edit"><< Edit Account</a>
    </td>
    <td align="left">
      <a href="/shop">Continue Shopping >></a>
    </td>
   </tr> 
   <tr>
    <td align="right">
      <a href="<? print $shost ?>/user/withaddress"><< Edit Addresses</a>
    </td>
    <td align="left">
      <a href="<? print $shost ?>/trade/checkout/">Proceed to Checkout >></a>
    </td>
   </tr>
  </table>

<br />

<table border="0" cellspacing="5" cellpadding="8" width="600" style="font-size:10;">

<tr>
<td align="right" width="250">
  <a href="/trade/wishlist">My Wish List</a>
</td>
<td align="left">
  <a href="/trade/findwishlist/">Find Wish List</a>
</td>
</tr>

<tr>
<td align="right">
  <a href="/contact">Contact Us</a>
</td>
<td align="left">
  <a href="/trade/orderlist/">Order Status</a>
</td>
</tr>

<? /* 
<!--
<tr>
<tr>
<td align="right">
  <a href="/policy/warrantees">Warrantees</a>  
</td>
<td align="left">
  <a href="/article/articlestatic/79/1/5/">Gift Certificates & Discounts</a>
</td>
</tr>

<tr>
<tr>
<td align="right">
  <a href="/policy/shipping">Shipping Policy</a>
</td>
<td align="left">
  <a href="/wishlists">Wish List & Gift Registry</a>
</td>
</tr>

<tr>
<tr>
<td align="right">
  <a href="/policy/security">Security Information</a>
</td>
<td align="left">
  <a href="/policy/privacy">Privacy Policy</a>
</td>
</tr>
-->

<!--
<tr>
<tr>
<td align="right">
  <a href="/policy/privacy">Privacy Policy</a>
</td>
<td align="left">
  <a href="/policy/security">Security Information</a>
</td>
</tr>

<tr>
<td align="center" colspan="2">
  <a href="/policy/return">Return Policy</a>
</td>
</tr>

-->

*/ ?>

<tr>
<td align="right">
  <a href="/policy/return">Return Policy</a>
</td>
<td align="left">
  <a href="/policy/shipping">Shipping Policy</a>
</td>
</tr>

</table>

</div>

<? /*
<td>
<input type="button" value="< Return Home&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" onClick="window.location='/';"></td>


<td><input type="button" value="<< Edit Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" onClick="window.location='/user/edit/';"></td>
<td><input type="button" value="&nbsp;&nbsp;&nbsp;&nbsp;Continue Shopping >" onClick="window.location='/trade/productlist/0';"></td>
</tr>
<tr>
<td><input type="button" value="<< Edit Addresses" onClick="window.location='/user/withaddress/';"></td>
<td><input type="button" value="Proceed to Checkout >>" onClick="window.location='/trade/checkout/';"></td>
</tr>

   */ ?>

