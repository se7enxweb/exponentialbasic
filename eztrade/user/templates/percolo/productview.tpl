        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><a href="{www_dir}{index}/tema/bildegalleri"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="Bygg mer enn hus..." width="140" height="100" border="0" /></a><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">{intl-head_line}</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		</table>

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="99%" valign="top" class="tdmini">
			
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
</table>

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
	{main_image_caption}	
	</td>
</tr>
</table>

<!-- END main_image_tpl -->

<p>{intro_text}</p>

<div class="p">{description_text}</div>

<br clear="all" />

<!-- BEGIN image_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
<!-- BEGIN image_tpl -->

	<td width="1%" valign="top" align="center">

	<table width="1%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td>
		<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/">
		<img src="{www_dir}{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
		<div class="pictext">
		{image_caption}
		</div>
		</td>
	</tr>
	</table>

	</td>

<!-- END image_tpl -->
</tr>
</table>

<br />
<!-- END image_list_tpl -->

<!-- BEGIN section_item_tpl -->
<!-- BEGIN link_item_tpl -->
<!-- END link_item_tpl -->
<!-- END section_item_tpl -->

<!-- BEGIN attribute_list_tpl -->
<table width="60%" cellspacing="0" cellpadding="2" border="0" align="center">
<!-- BEGIN attribute_tpl -->

<!-- END attribute_tpl -->

<!-- BEGIN attribute_value_tpl -->
<tr> 
	<th>{attribute_name}:</th>
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

<form action="{www_dir}{index}/{module}/cart/add/{product_id}/" method="post">

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

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<!-- BEGIN price_tpl -->
	<p class="boxtext"><span class="pris">{intl-price}:</span></p>
	<span class="pris">{product_price}</span>

	<!-- BEGIN alternative_currency_list_tpl -->
	<p class="boxtext">{intl-alternative_currency}:</p>
	<!-- BEGIN alternative_currency_tpl -->
	{alt_price}<br />
	<!-- END alternative_currency_tpl -->

	<!-- END alternative_currency_list_tpl -->

	<!-- END price_tpl -->	
	</td>
	<td align="right" valign="top">
	<!-- BEGIN external_link_tpl -->
	<p class="boxtext">{intl-external_link}:</p>
	<a href="{www_dir}{index}{external_link_url}" target="_blank">{external_link_url}</a>
	<!-- END external_link_tpl -->
	</td>
</tr>
</table>
<br />

<!-- BEGIN quantity_item_tpl -->
<p class="boxtext">{intl-availability}:</p>
<div class="p">{product_quantity}</div>
<!-- END quantity_item_tpl -->

<div class="p">{extra_product_info}</p>


<!-- BEGIN add_to_cart_tpl -->

<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />

<!-- END add_to_cart_tpl -->

<br /><br /><br />

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="{www_dir}{index}/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<div align="center"> <a class="path" href="{www_dir}{index}/{module}/{module_print}/{product_id}/{category_id}/">| {intl-print_page} |</a></div>
<!-- END print_page_link_tpl -->

</form>

</td>
</tr>
</table>
