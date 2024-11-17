<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-head_line}</h1>
     </td>
</tr>
</table>

<hr noshade size="4" />

<!-- BEGIN product_search_form_tpl -->
<form action="{www_dir}{index}/trade/extendedsearch/" method="post">
<table width="50%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <th>{intl-text}</th>
</tr></tr>
<tr>
        <td>
	<input type="text" name="Text" value="{text}" />
	</td>
</tr>
<tr>
        <th>{intl-price_lower}</th>
        <th>{intl-price_higher}</th>
</tr>
<tr>
        <td>
	<input type="text" name="PriceLower" value="{price_lower}" />
	</td>
        <td>
	<input type="text" name="PriceHigher" value="{price_higher}" />
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<tr>
        <th>{intl-search_in_category}</th>
</tr>
<tr>
	<!-- BEGIN category_list_tpl -->
        <td>
	{category_main_name}
	<select name="CategoryArrayID[]" multiple size="5">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {is_selected}>{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
	<!-- END category_list_tpl -->
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<tr>
        <td>
	<input type="submit" name="SearchButton" value="{intl-search}" />
	</td>
</tr>
</table>
</form>
<!-- END product_search_form_tpl -->

<!-- BEGIN product_search_list_tpl -->        
<br>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN product_tpl -->
<tr>
	<td>
	<a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>
    <!-- BEGIN image_tpl -->
    <table align="right">
    <tr>
        <td>
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
        </td>
    </tr>
    <tr>
        <td>
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
    <!-- END image_tpl -->

    {product_intro_text}

<br>
	<!-- BEGIN price_tpl -->
	{product_price}<br>
	<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->
<tr>
	<td>

<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/trade/extendedsearch/?Offset={prev_offset}">
{intl-prev}
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/trade/extendedsearch/?Offset={next_offset}">
{intl-next}
</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>
<!-- END product_search_list_tpl -->        
