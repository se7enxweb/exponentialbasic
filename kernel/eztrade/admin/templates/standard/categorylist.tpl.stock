<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-productlist}</h1>
	</td>
     <td align="right">
	 <form action="{www_dir}{index}/trade/search/" method="post">
	       <input type="text" name="Query">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search_button}">
         </form>
     </td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<img src="{www_dir}/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/categorylist/parent/0/">{intl-top}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/categorylist/parent/{category_id}/">{category_name}</a>

<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/categoryedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{intl-category}:</th>
	<th>{intl-description}:</th>


	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN category_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="48%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/">{category_name}</a>
	</td>
	<td width="49%" class="{td_class}">
	{category_description}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{category_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/productedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="2">{intl-product}:</th>
	<th class="right">{intl-active}:</th>
	<th class="right">{intl-price}:</th>
	<th class="right">&nbsp;</th>
	<th class="right">{intl-new_price}:</th>
	<!-- BEGIN absolute_placement_header_tpl -->
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<!-- END absolute_placement_header_tpl -->

	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN product_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<!-- BEGIN product_icon_tpl -->
	<img src="{www_dir}/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
	<!-- END product_icon_tpl -->
	<!-- BEGIN voucher_icon_tpl -->
	<img src="{www_dir}/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
	<!-- END voucher_icon_tpl -->
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/productedit/productpreview/{product_id}/">{product_name}</a>
	<input type="hidden" name="ProductEditArrayID[]" value="{product_id}" />
	</td>
	<!-- BEGIN product_active_item_tpl -->
	<td class="{td_class}" align="right">
	{intl-product_active}
	</td>
	<!-- END product_active_item_tpl -->
	<!-- BEGIN product_inactive_item_tpl -->
	<td class="{td_class}" align="right">
	{intl-product_inactive}
	</td>
	<!-- END product_inactive_item_tpl -->
	<td class="{td_class}" align="right">
	{product_price}&nbsp;
	</td>
    <td class="{td_class}" align="center">
    <!-- BEGIN inc_vat_item_tpl -->
    {intl-inc_vat}
    <!-- END inc_vat_item_tpl -->
    <!-- BEGIN ex_vat_item_tpl -->
    {intl-ex_vat}
    <!-- END ex_vat_item_tpl -->
    </td>
	<td class="{td_class}" align="right">
	<input type="text" name="Price[]" size="8" value="" />
	</td>
	<!-- BEGIN absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveDown={product_id}"><img src="{www_dir}/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveUp={product_id}"><img src="{www_dir}/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/{url_action}/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ProductArrayID[]" value="{product_id}">
	</td>
</tr>
<!-- END product_item_tpl -->
</table>

<p>{intl-price_note}</p>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/categorylist/parent/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/categorylist/parent/{category_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/categorylist/parent/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
<hr noshade="noshade" size="4" />
<input type="hidden" name="CategoryID" value="{category_id}" />
<input type="hidden" name="Offset" value="{offset}" />
<input class="stdbutton" type="submit" Name="SubmitPrice" value="{intl-submit_price}" />
<input class="stdbutton" type="submit" Name="DeleteProducts" value="{intl-deleteproducts}" />
</form>
<!-- END product_list_tpl -->




