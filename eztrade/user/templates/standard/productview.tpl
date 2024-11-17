<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/trade/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade size="4"/>

<img src="{www_dir}/design/admin/images/layout/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/{module}/{module_list}/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="{www_dir}/design/admin/images/layout/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade size="4"/>

<!-- BEGIN price_to_high_tpl -->
<p class="error">{intl-price_to_high}</p>
<!-- END price_to_high_tpl -->
<!-- BEGIN price_to_low_tpl -->
<p class="error">{intl-price_to_low}</p>
<!-- END price_to_low_tpl -->


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h2>{title_text}</h2>
	</td>
	<td align="right">
	<br />
	<!-- BEGIN product_number_item_tpl -->
	<span class="boxtext">{intl-nr}:</span> {product_number}
	<!-- END product_number_item_tpl -->
	</td>
</tr>
<tr>
	<td colspan="2">

<br />
<!-- BEGIN main_image_tpl -->

<table width="1%" align="right" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<a href="{www_dir}{index}/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/">
	<img src="{www_dir}{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a>
	</td>
</tr>
<tr>
	<td class="pictext">
	{main_image_caption}<br />
	<br />
	</td>
</tr>
</table>

<!-- END main_image_tpl -->

<p>{intro_text}</p>

<div class="p">{description_text}</div>

<br clear="all" />

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td width="70%">
<!-- BEGIN image_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN image_tpl -->
<tr>
<td width="1%" valign="top">

	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/">
	<img src="{www_dir}{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
</td>
</tr>
<tr>
<td valign="top">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<th valign="top">
	<!-- {image_title}: -->
	</th>
</tr>
<tr>
	<td valign="top" class="pictext">
	{image_caption}
	</td>
</tr>
</table>

</td>

</tr>

<!-- END image_tpl -->

</table>
<br />
<!-- END image_list_tpl -->
	</td>
	<td width="30%" valign="top">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN section_item_tpl -->
<tr>
	<th>
	{section_name}:
	</th>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td class="{td_class}">
	&nbsp;<a href="{www_dir}{index}{link_url}">{link_name}</a>
	</td>
</tr>
<!-- END link_item_tpl -->
<tr>
	<td>&nbsp;
	</td>
</tr>
<!-- END section_item_tpl -->
</table>
	</td>
</tr>
</table>

<!-- BEGIN attribute_list_tpl -->
<table width="60%" cellspacing="0" cellpadding="2" border="0" align="center">
<!-- BEGIN attribute_tpl -->

<!-- END attribute_tpl -->

<!-- BEGIN attribute_value_tpl -->
<tr> 
	<td>{attribute_name}:</td>
	<td align="right">{attribute_value_var} {attribute_unit}</td>
</tr>
<!-- END attribute_value_tpl -->
<!-- BEGIN attribute_header_tpl -->
<tr> 
	<th colspan="2">{attribute_name}:</th>
</tr>
<!-- END attribute_header_tpl -->

</table>
<!-- END attribute_list_tpl -->

<form action="{www_dir}{index}/{module}/{action_url}/{product_id}/" method="post">

<!-- BEGIN option_tpl -->


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="3">
	<br />
	{option_name}:
	</th>
</tr>
<tr>
	<td width="20%">

	<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
	<!-- BEGIN value_price_header_tpl -->

	<!-- BEGIN value_description_header_tpl -->

	<!-- END value_description_header_tpl -->

	<!-- BEGIN value_price_header_item_tpl -->

	<!-- END value_price_header_item_tpl -->

	<!-- BEGIN value_currency_header_item_tpl -->

	<!-- END value_currency_header_item_tpl -->

	<!-- END value_price_header_tpl -->
	<select name="OptionValueArray[]">

	<!-- BEGIN value_tpl -->
	<!-- BEGIN value_description_tpl -->
	<option value="{value_id}">{value_name}
	<!-- END value_description_tpl -->
	<!-- BEGIN value_price_item_tpl -->
	{value_price}
	<!-- END value_price_item_tpl -->
	<!-- BEGIN value_availability_item_tpl -->
	({value_availability})
	<!-- END value_availability_item_tpl -->
	 </option>

	<!-- BEGIN value_price_currency_list_tpl -->

	<!-- BEGIN value_price_currency_item_tpl -->

	<!-- END value_price_currency_item_tpl -->

	<!-- END value_price_currency_list_tpl -->

	<!-- END value_tpl -->
	</select>
	</td>
	<td width="1%">
	&nbsp;&nbsp;
	</td>
	<td width="79%">
	{option_description}
	</td>
</tr>
</table>

<!-- END option_tpl -->

	</td>
</tr>
</table>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<!-- BEGIN price_tpl -->
	<p class="boxtext">{intl-price}:</p>
	{product_price}

	<!-- BEGIN alternative_currency_list_tpl -->
	<p class="boxtext">{intl-alternative_currency}:</p>
	<!-- BEGIN alternative_currency_tpl -->
	{alt_price}<br />
	<!-- END alternative_currency_tpl -->

	<!-- END alternative_currency_list_tpl -->

	<!-- END price_tpl -->	
	<!-- BEGIN price_range_tpl -->
	<p class="boxtext">{intl-price_range}:</p>
	<!-- BEGIN price_range_min_unlimited_tpl -->
	{intl-min}: {intl-unlimited}
	<!-- END price_range_min_unlimited_tpl -->
	<!-- BEGIN price_range_min_limited_tpl -->
	{intl-min}: {price_min}
	<!-- END price_range_min_limited_tpl -->
	<!-- BEGIN price_range_max_unlimited_tpl -->
	{intl-max}: {intl-unlimited}
	<!-- END price_range_max_unlimited_tpl -->
	<!-- BEGIN price_range_max_limited_tpl -->
	{intl-max}: {price_max}
	<!-- END price_range_max_limited_tpl -->

	</td>
</tr>
<tr>
	<td>
	<input type="text" name="PriceRange" size="8" />
	<!-- END price_range_tpl -->
	</td>
	<td align="right" valign="top">
	<!-- BEGIN external_link_tpl -->
	<p class="boxtext">{intl-external_link}:</p>
	<a href="{www_dir}{index}{external_link_url}" target="_blank">{external_link_url}</a>
	<!-- END external_link_tpl -->
	</td>

</tr>
<tr>
       <td>&nbsp;</td>
</tr>

<tr>
	<!-- BEGIN mail_method_tpl -->
	<td>
<!--	<p class="boxtext">{intl-mail_method}:</p>
	{intl-email}: <input type="radio" value="1" name="MailMethod" checked />&nbsp;
	{intl-smail}: <input type="radio" value="2" name="MailMethod" />
	</td> -->
<input type="hidden" value="1" name="MailMethod" />
	<!-- END mail_method_tpl -->
</tr>
</table>
<br />

<!-- BEGIN quantity_item_tpl -->
<p class="boxtext">{intl-availability}:</p>
<div class="p">{product_quantity}</div>
<!-- END quantity_item_tpl -->

<div class="p">{extra_product_info}</p>


<!-- BEGIN add_to_cart_tpl -->
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />

<input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" />
<!-- END add_to_cart_tpl -->

<!-- BEGIN voucher_buttons_tpl -->
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Voucher" value="{intl-next}" />

<!-- END voucher_buttons_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="{www_dir}{index}/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<div align="center"> <a class="path" href="{www_dir}{index}/{module}/{module_print}/{product_id}/{category_id}/">| {intl-print_page} |</a></div>
<!-- END print_page_link_tpl -->

</form>

