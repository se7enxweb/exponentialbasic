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

<h1>{intl-cart}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->


<form action="{www_dir}{index}/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-product_options}:</th>
	<th>{intl-product_qty}:</th>

	<td class="path" align="right">{intl-product_price}:</td>
	<td class="path" align="right">&nbsp;</td>
</tr>

<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN cart_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END cart_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END cart_item_option_tpl -->
	&nbsp;</td>
	<td class="{td_class}">
	<input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
	<input size="3" type="text" name="CartCountArray[]" value="{cart_item_count}" />
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td class="{td_class}" align="right">
	<a href="{www_dir}{index}/trade/cart/remove/{cart_item_id}/"  
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{cart_item_id}-slett','','/eztrade/user/images/slettminimrk.gif',1)"><img name="ezuser{cart_item_id}-slett" border="0" src="{www_dir}/eztrade/user/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-shipping}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<th>{intl-total}:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<hr noshade="noshade" size="4" />
<table border="0">
<tr>
	<!-- BEGIN cart_checkout_tpl -->
	<td>
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
	<!-- END cart_checkout_tpl -->

	<td>
	<input class="okbutton" type="submit" value="{intl-update}" />
	
	</td>
</td>
</table>

<input type="hidden" name="Action" value="Refresh" />

</form>
