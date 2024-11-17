<h1>{intl-product_report}</h1>

<hr noshade size="4" />
{month}

<h2>{intl-most_viewed_products}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<td align="right">
	<b>{intl-view_count}:</b>
	</td>
</tr>
<!-- BEGIN most_viewed_product_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td align="right">
	{view_count}
	</td>
</tr>
<!-- END most_viewed_product_tpl -->

</table>

<h2>{intl-most_added_to_cart_products}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<td align="right">
	<b>{intl-add_to_cart_count}:</b>
	</td>
</tr>
<!-- BEGIN most_added_to_cart_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td align="right">
	{add_count}
	</td>
</tr>
<!-- END most_added_to_cart_products_tpl -->

</table>

<!--
<h2>{intl-most_added_to_wishlist_products}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{intl-product_name}:
	</th>
	<td align="right">
	<b>{intl-add_to_wishlist_count}:</b>
	</td>
</tr>
<!-- BEGIN most_added_to_wishlist_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td align="right">
	{add_count}
	</td>
</tr>
<!-- END most_added_to_wishlist_products_tpl -->

</table>
-->
<h2>{intl-most_bought_products}</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">
	{intl-product_name}:
	</th>
	<th width="25%">
	{intl-times_bought}:
	</th>
	<td width="25%" align="right">
	<b>{intl-total_number_bought}:</b>
	</td>
</tr>
<!-- BEGIN most_bought_products_tpl -->
<tr class="{bg_color}">
	<td>
	{product_name}
	</td>
	<td>
	{buy_count}
	</td>
	<td align="right">
	{total_buy_count}
	</td>
</tr>
<!-- END most_bought_products_tpl -->
</table>

<!-- BEGIN month_tpl -->
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN month_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/stats/productreport/{previous_year}/{previous_month}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END month_previous_tpl -->
	
	<!-- BEGIN month_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END month_previous_inactive_tpl -->
	<!-- BEGIN month_next_tpl -->
	<td align="right">
	<a class="path" href="{www_dir}{index}/stats/productreport/{next_year}/{next_month}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END month_next_tpl -->

	<!-- BEGIN month_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END month_next_inactive_tpl -->

</tr>
</table>

<br />

<!-- END month_tpl -->

