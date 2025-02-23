<!-- BEGIN category_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-products}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top">
	<!-- BEGIN category_image_hot_deals_tpl -->
		<a class="menu" href="{www_dir}{index}/trade/hotdealsgallery/"><img src="{www_dir}/design/{sitedesign}/images/product_categories/hot_deals.gif" width="130" height="23" border="0" />
	<!-- END category_image_hot_deals_tpl -->
	
	<!-- BEGIN category_image_list_tpl -->
	<!-- BEGIN category_image_tpl -->
		<a class="menu" href="{www_dir}{index}/trade/productgallery/{category_id}/"><img src="{www_dir}/design/{sitedesign}/images/product_categories/{category_image_name}" width="130" height="23" border="0" />
	<!-- END category_image_tpl -->
	<!-- END category_image_list_tpl -->

	<!-- BEGIN category_text_hot_deals_tpl -->
	   <a class="menu" href="{www_dir}{index}/trade/hotdealsgallery/">{category_hot_deals}</a><br>
	<!-- END category_text_hot_deals_tpl -->
</td>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td colspan="2"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12">&nbsp;<a class="menu" href="{www_dir}{index}/trade/productlist/{category_id}/">{category_name}</a></td>
</tr>
<!-- END category_tpl -->
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>

<!-- END category_list_tpl -->

