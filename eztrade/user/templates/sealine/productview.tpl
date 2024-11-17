<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<h1 class="small">{title_text}</h1>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-venstre.gif" width="8" height="4" border="0" /><br /></td>
    <td class="tdmini" width="98%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/1x1.gif" width="1" height="1" border="0" /><br /></td>
    <td class="tdmini" width="1%" background="{www_dir}/images/gyldenlinje-strekk.gif"><img src="{www_dir}/images/gyldenlinje-hoyre.gif" width="8" height="4" border="0" /><br /></td>
</tr>
</table>

<br />

<!-- BEGIN main_image_tpl -->

<table width="{main_image_width}" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="{www_dir}{index}/imagecatalogue/imageview/{main_image_id}/?RefererURL=/trade/productview/{product_id}/">
	<img src="{www_dir}{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a>
	</td>
</tr>
<tr>
	<td class="pictext">
<!--	{main_image_caption} -->
	</td>
</tr>
</table>

<!-- END main_image_tpl -->

<br />

<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="10%">&nbsp;</td>
	<td width="80%" align="center">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN attribute_tpl -->
{begin_tr}
	<td width="1%">&nbsp;</td>
	<th>
	{attribute_name}: 
	</th>
	<td align="right">
	{attribute_value}
	</td>
	<td width="1%">&nbsp;</td>
{end_tr}
<!-- END attribute_tpl -->
</table>

	</td>
	<td width="10%">&nbsp;</td>
</tr>
</table>
<!-- END attribute_list_tpl -->


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td colspan="2">

<br />

<!-- <p>{intro_text}</p> -->

<p>{description_text}</p>


<table width="100%" border="0">
<tr>
	<td>
	<!-- BEGIN price_tpl -->
	<p class="boxtext">{intl-price}: {product_price}</p>

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

<br clear="all" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN image_tpl -->
<tr>
	<td valign="top">
	<div class="feature">{image_name}</div>
	</td>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td width="1%">
	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/trade/productview/{product_id}/">
	<img src="{www_dir}{image_url}" border="0" alt="{image_name}" width="{image_width}" height="{image_height}" /></a>
	</td>
	<td width="1%"><img src="{www_dir}/images/1x1.gif" height="1" width="12" border="0" alt="" /></td>
	<td width="98%" valign="top">
	{image_caption}
	</td>
</tr>
<!-- END image_tpl -->

</table>
<br />
<form action="{www_dir}{index}/trade/cart/add/{product_id}/" method="post">

<!-- BEGIN option_tpl -->


<table width="100%" cellpadding="6" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	<br />
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

	</td>
</tr>
</table>


<!-- BEGIN add_to_cart_tpl -->

<!-- END add_to_cart_tpl -->
 
</form>

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="{www_dir}{index}/trade/productview/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->
<div align="center"> <a class="path" href="{www_dir}{index}/trade/productprint/{product_id}/">| {intl-print_page} |</a></div>
<!-- END print_page_link_tpl -->
</p> 
