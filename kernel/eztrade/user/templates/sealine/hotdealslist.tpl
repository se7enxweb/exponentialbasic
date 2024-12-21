<h2>{intl-hot_deals}:</h2>

<hr noshade="noshade" size="4" />

<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
{begin_tr}
	<td>

	<a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><h3>{product_name}</h3></a>

	<!-- BEGIN product_image_tpl -->
	<a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/">
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/></a>
	<!-- END product_image_tpl -->&nbsp;

<p>{product_intro_text}</p>


<!-- BEGIN price_tpl -->
<p class="pris">{product_price}</p>
<!-- END price_tpl -->

	</td>
{end_tr}
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

