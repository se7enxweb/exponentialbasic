<!-- wishlist.tpl --> 
<!-- $Id: wishlist.tpl 6304 2001-07-29 23:31:17Z kaid $ -->

<h1>{intl-wishlist}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN empty_wishlist_tpl -->
<h2>{intl-empty_wishlist}</h2>
<!-- END empty_wishlist_tpl -->

<!-- BEGIN public_wishlist_tpl -->
<!-- END public_wishlist_tpl -->
<!-- BEGIN non_public_wishlist_tpl -->
<!-- END non_public_wishlist_tpl -->


<!-- BEGIN wishlist_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-product_image}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-product_options}:</th>
	<th>{intl-move_to_cart}:</th>
	<td class="path" align="right">{intl-product_price}:</td>
</tr>

<!-- BEGIN wishlist_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN wishlist_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END wishlist_image_tpl -->
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
	</td>
	<td class="{td_class}">
        <!-- BEGIN wishlist_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END wishlist_item_option_tpl -->
	&nbsp;</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/wishlist/movetocart/{wishlist_item_id}/">
	{intl-move_to_cart}
	</a>
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END wishlist_item_tpl -->

<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-shipping}:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<th>{intl-total}:</th>
	<td align="right">
	{wishlist_sum}
	</td>
</tr>
</table>
<!-- END wishlist_item_list_tpl -->

<hr noshade="noshade" size="4" />
