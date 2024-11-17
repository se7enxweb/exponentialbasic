<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-productlist} - ({product_start}-{product_end}/{product_total})</h1>
	</td>
     <td align="right">
 	 <form action="{www_dir}{index}/trade/search/" method="post">
	       <input type="text" name="Query" value="{search_text}">
	       <input type="submit" name="search" value="{intl-search_button}">
         </form>
     </td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<!-- BEGIN product_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/productedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-product}:</th>
	<th>{intl-category}:</th>
	<th>{intl-active}:</th>
	<td class="path" align="right">{intl-price}:</td>
	<td class="path" align="right">{intl-new_price}:</td>

	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN product_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/productedit/productpreview/{product_id}/">{product_name}&nbsp;</a>
	<input type="hidden" name="ProductEditArrayID[]" value="{product_id}" />
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{product_category_id}/">{product_category}</a>
	</td>
	<!-- BEGIN product_active_item_tpl -->
	<td class="{td_class}">
	{intl-product_active}
	</td>
	<!-- END product_active_item_tpl -->
	<!-- BEGIN product_inactive_item_tpl -->
	<td class="{td_class}">
	{intl-product_inactive}
	</td>
	<!-- END product_inactive_item_tpl -->
	<td class="{td_class}" align="right">
	{product_price}
	</td>
	<td class="{td_class}" align="right">
	<input type="text" name="Price[]" size="8" value="" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/productedit/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ProductArrayID[]" value="{product_id}">
	</td>
</tr>
<!-- END product_item_tpl -->
<tr>
	<td>
	{intl-price_note}
	</td>
</tr>
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/search/{item_previous_index}/{search_text}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/search/{item_index}/{search_text}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/search/{item_next_index}/{search_text}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>
</table>
<hr noshade="noshade" size="4" />
<input type="hidden" name="Offset" value="{offset}" />
<input type="hidden" name="Query" value="{search_text}">
<input class="stdbutton" type="submit" Name="SubmitPrice" value="{intl-submit_price}" />
<input class="stdbutton" type="submit" Name="DeleteProducts" value="{intl-deleteproducts}" />
</form>
<!-- END product_list_tpl -->




