<form action="{www_dir}{index}/{module}/cart/add/{product_id}/" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{title_text}</div>
	</td>
</tr>
</table>
<br />

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN main_image_tpl -->

<table align="right" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="{www_dir}{index}/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
	<img src="{www_dir}{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a>
	</td>
</tr>
<tr>
	<td class="pictext">
	{main_image_caption}
	</td>
</tr>
</table>

<!-- END main_image_tpl -->

<p>{intro_text}</p>

<p>{description_text}</p>

<br clear="all" />

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>

<!-- BEGIN image_tpl -->
<td class="bglight">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
	<img src="{www_dir}{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
	</td>
</tr>
<tr>
	<td valign="top">
	<p class="pictext">
	{image_caption}
	</p>
	</td>
</tr>
</table>
&nbsp;

</td>

<!-- END image_tpl -->

</tr>
</table>

<!-- BEGIN option_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	{option_name}
	</th>
</tr>
<tr>
	<td width="20%">

	<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
	<select name="OptionValueArray[]">
	
	<!-- BEGIN value_tpl -->
	<option value="{value_id}">{value_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td width="80%">
	{option_description}
	</td>
</tr>
</table>

<!-- END option_tpl -->

<!-- BEGIN attribute_list_tpl -->
<table class="list" width="80%" cellspacing="0" cellpadding="2" border="0" align="center">
<!-- BEGIN attribute_tpl -->
<tr>
	<th>
	{attribute_name}: 
	</th>
	<td>
	{attribute_value}
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

{extra_product_info}

<table class="list" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>
	<!-- BEGIN price_tpl -->
	<p class="boxtext">{intl-price}:</p>
	{product_price}
	<!-- END price_tpl -->	
	</td>
	<td align="right">
	<!-- BEGIN external_link_tpl -->
	<p class="boxtext">{intl-external_link}:</p>
	<a href="{www_dir}{index}{external_link_url}" target="_blank">{external_link_url}</a>
	<!-- END external_link_tpl -->
	</td>
</tr>
</table>

<!-- BEGIN add_to_cart_tpl -->
<br />
<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />

<!-- END add_to_cart_tpl -->

<br /><br /><br />

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="{www_dir}{index}/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<div align="center"> <a class="path" href="{www_dir}{index}/{module}/{module_print}/{product_id}/">| {intl-print_page} |</a></div>
<!-- END print_page_link_tpl -->

</form>
