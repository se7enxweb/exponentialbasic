<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{intl-cart}</div>
	</td>
</tr>
</table>
<br />

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
	<div class="small">{option_name}: {option_value}<div>
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
	<a href="{www_dir}{index}/trade/cart/remove/{cart_item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztrade{cart_item_id}-slett','','/images/slettminimrk.gif',1)"><img name="eztrade{cart_item_id}-slett" border="0" src="{www_dir}/images/slettmini.gif" width="16" height="16" align="top"></a>
	</td>
</tr>
<!-- END cart_item_tpl -->

<!--
<tr>
	<td colspan="2">&nbsp;</td>
	<th colspan="2">{intl-shipping}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
-->
<tr>
	<td colspan="2">&nbsp;</td>
	<th colspan="2">{intl-total}:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>

<p>
Fraktkostnader kommer i tillegg: Kr. 50,- for programvarepakker.
Fraktkostnadene for maskinvare kan variere; postens takster vil gjelde.
For postoppkravsleveranser kommer postens oppkravsgebyr i tillegg.
Brukerstøtte leveres fraktfritt.
</p>

<!-- END cart_item_list_tpl -->

<!-- BEGIN cart_checkout_tpl -->

<table border="0">
<tr>

	<td>
	<input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>

	<td>
	<input class="okbutton" type="submit" value="{intl-update}" />
	
	</td>
</td>
</table>
<!-- END cart_checkout_tpl -->


<input type="hidden" name="Action" value="Refresh" />

</form>
