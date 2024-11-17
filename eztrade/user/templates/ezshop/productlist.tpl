<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="#f08c00">
	<div class="headline">{category_name}</div>
	</td>
</tr>
</table>
<br />

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_tpl -->

<!-- END category_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
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
<div class="spacer"><div class="pris">{product_price}</div></div>
<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

