<SCRIPT LANGUAGE="JavaScript1.2">
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
</SCRIPT> 

<!-- wishlist.tpl -->

<!-- BEGIN public_wishlist_tpl -->
<!-- END public_wishlist_tpl -->
<!-- BEGIN non_public_wishlist_tpl -->
<!-- END non_public_wishlist_tpl -->

<body onLoad="MM_preloadImages('{www_dir}/eztrade/user/images/slettminimrk.gif')">

<form action="{www_dir}{index}/trade/wishlist/" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
      <h1>{intl-wishlist}</h1>
      <hr noshade size="4" />
      <!-- BEGIN empty_wishlist_tpl -->
      <h2>{intl-empty_wishlist}</h2>
      <!-- END empty_wishlist_tpl --> <!-- BEGIN wishlist_item_list_tpl -->
      <table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr> 
		  <th>{intl-product_image}:</th>
		  <th>{intl-product_name}:</th>
		  <th>{intl-product_options}:</th>
		  <th>{intl-product_qty}:</th>
		  <th>{intl-move_to_cart}:</th>
		  <td align="right"><b>{intl-product_price}:</b></td>
		  <td align="right">&nbsp;</td>
		</tr>
		<!-- BEGIN wishlist_item_tpl --> 
		<tr> 
		  <td class="{td_class}"> <!-- BEGIN wishlist_image_tpl --> <img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
			<!-- END wishlist_image_tpl --> </td>
		  <td class="{td_class}"> <a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a> 
		  </td>
		  <td class="{td_class}"> <!-- BEGIN wishlist_item_option_tpl --> {option_name}: 
			{option_value}<br>
			<!-- END wishlist_item_option_tpl --> &nbsp;</td>
		  <td class="{td_class}"> <a href="{www_dir}{index}/trade/wishlist/movetocart/{wishlist_item_id}/"> 
			{intl-move_to_cart} </a> </td>
  		  <td class="{td_class}">
		  	<input type="hidden" name="WishlistIDArray[]" value="{wishlist_item_id}" />
			<input size="3" type="text" name="WishlistCountArray[]" value="{wishlist_item_count}" />
   		  </td>
		  <td class="{td_class}" align="right"> {product_price} </td>
		  <td class="{td_class}" align="right"><a href="{www_dir}{index}/trade/wishlist/remove/{wishlist_item_id}/"  
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{wishlist_item_id}-slett','','/eztrade/user/images/slettminimrk.gif',1)"><img name="ezuser{wishlist_item_id}-slett" border="0" src="{www_dir}/eztrade/user/images/slettmini.gif" width="16" height="16" align="top"></a></td>
		</tr>
		<!-- END wishlist_item_tpl --> 
		<tr> 
		  <td colspan="3">&nbsp;</td>
		  <th>{intl-shipping}:</th>
		  <td align="right"> {shipping_cost} </td>
		  <td align="right">&nbsp;</td>
		</tr>
		<tr> 
		  <td colspan="3">&nbsp;</td>
		  <th>{intl-total}:</th>
		  <td align="right"> {wishlist_sum} </td>
		  <td align="right">&nbsp;</td>
		</tr>
	  </table>
      <!-- END wishlist_item_list_tpl -->
      <hr noshade size="4" />
    </td>
  </tr>
</table>

<table border="0">
<tr>
	<td>
	<input type="hidden" name="Action" value="Refresh" />
	<input class="okbutton" type="submit" value="{intl-update}" />
	</td>
</td>
</table>


</form>
