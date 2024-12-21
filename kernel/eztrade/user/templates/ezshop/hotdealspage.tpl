<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td bgcolor="#c0c0c0">
	<div class="listheadline"><a class="listheadline">{intl-hot_deals}</a></div>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN product_list_tpl -->


<!-- BEGIN product_tpl -->
{begin_tr}
	<td valign="top" width="50%">
	<div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>

	<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
		<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
        </td>
    </tr>
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
	<!-- END product_image_tpl -->

	<div class="p">{product_intro_text}</div>

<!-- BEGIN price_tpl -->
	<div class="pris">{product_price}</div>
<!-- END price_tpl -->

	</td>
{end_tr}

<!-- END product_tpl -->

<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>

<!-- END product_list_tpl -->

