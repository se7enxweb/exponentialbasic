<!-- BEGIN articletags_tpl -->
<!-- VAR br=<hr /> -->
<!-- END articletags_tpl -->

<!-- 1BEGIN articletags_tpl /n END articletags_tpl -->
<!-- \s+VAR\s+$var=(.*?)\s+ -->
<!-- articletags :  $this->BrOverride = $this->Template->get_user_variable( "articletags_tpl",  "br" ); -->

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

<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="0" />
<a class="path" href="{www_dir}{index}/trade/categorylist/parent/0/">{intl-top}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="0" />
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
	<img src="{www_dir}/design/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="48%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/">{category_name}</a>
	</td>
	<td width="49%" class="{td_class}">
	{category_description}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{category_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{category_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}"><br/>
</form>
<hr noshade="noshade" size="4" />
<form method="post" action="{www_dir}{index}/trade/productedit/edit/" enctype="multipart/form-data">
<input type="text" name="ImportCSVDir" size="20" value="{csv_dir}" />&nbsp;	
<input class="stdbutton" type="submit" name="CSVImport" value="{intl-csv_import}" />

</form>

<!-- END category_list_tpl -->

<!-- BEGIN product_ezxml_warning -->
<span style="text-align: right;">
<a target="_help_window" href="" onclick="return popup('/help/trade/trade/productedit/','_help_window')"><img src="/design/admin/images/white/icon-help.gif" align="right" border="0" height="32" width="32" alt="Help" title="reference guide" style="position: relative; top: -15px;"></a>
<div style="text-align: left; font-size: 10px; padding-top: 4px; padding-bottom: 2px;">Please remember that the product Introduction and Description fields should must contain only valid ezxml valid text, please check the ezxml <a style="font-size: 10px;" href="/help/trade/trade/productedit/">reference guide</a>.</div></span> 
<!-- END product_ezxml_warning -->

<!-- BEGIN product_list_tpl -->
<form method="post" action="{www_dir}{index}/trade/productedit/edit/" enctype="multipart/form-data">
<table class="list" width="1%" cellspacing="0" cellpadding="2" border="0" style="border-top: 2px dotted black; border-left: 2px dotted black; border-right: 2px dotted black">

<!-- BEGIN detail_view_tpl -->
<tr>
	<td width="1%" class="{td_class}" align="center">
	<!-- BEGIN detail_product_icon_tpl -->
	<img src="{www_dir}/design/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
	<!-- END detail_product_icon_tpl -->
	<!-- BEGIN detail_voucher_icon_tpl -->
	<img src="{www_dir}/design/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
	<!-- END detail_voucher_icon_tpl -->
	</td>
	<td class="{td_class}" width="1%">
	<span class="small">{intl-product}:<br/>
	<input type="text" name="Name[]" size="30" value="{product_name}" STYLE="font-size:11px; font-weight: bold; color: #000080"/>
	<input type="hidden" name="ProductEditArrayID[]" value="{product_id}"/></span>
	</td>

	<td class="{td_class}" align="left" width="1%">
	<span class="small">{intl-price}:<br/>
	{product_price}</span>
	</td>

	<td class="{td_class}" align="left" width="1%">
	<span class="small">{intl-new_price}:<br/></span>	
	<input type="text" name="Price[]" size="8" value="" STYLE="font-size:11px"/>
	</td>
	
	<td class="{td_class}" align="left" width="1%">
	<span class="small">{intl-vat}:<br/></span>	
	<select name="IncludesVAT[]" STYLE="font-size:11px" >
	<option value="{intl-yes}" {include_vat}>{intl-yes}</option>
	<option value="{intl-no}" {exclude_vat}>{intl-no}</option>
	</select>
	</td>
	
	<!-- BEGIN detail_absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveDown={product_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveUp={product_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END detail_absolute_placement_item_tpl -->
	
	<td width="1%" class="{td_class}" align="center"><span class="small">{intl-edit}</span><br/>
	<a href="{www_dir}{index}/trade/{url_action}/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center"><span class="small">{intl-delete_check}</span><br/>
	<input type="checkbox" name="ProductArrayID[]" value="{product_id}">
	</td>
</tr>
<tr>
   <td valign="top" class="{td_class}" colspan="3" width="1%">
   <span class="small">{intl-intro}:</span><br/>
     <TEXTAREA NAME="Brief[]" ROWS=3 COLS=50 STYLE="font-size:11px">{brief_value}</TEXTAREA>
   </td>
   <td valign="top" class="{td_class}" colspan="4" width="1%">
   <span class="small">{intl-description}:</span><br/>
     <TEXTAREA NAME="Description[]" ROWS=3 COLS=60 STYLE="font-size:11px">{description_value}</TEXTAREA>
   </td>
</tr>
<tr>
	<td valign="center" class="{td_class}" align="center">

	<!-- BEGIN product_image_tpl -->
	<span class="small">{intl-thumb_image}:</span><br/>
	<a href="{www_dir}{index}/trade/productedit/imagelist/{product_id}/"><img src="{www_dir}{thumbnail_image_uri}" alt="{intl-thumb_alt}" border="0"></a><br/>
	<!-- END product_image_tpl -->
	
	<!-- BEGIN no_product_image_tpl -->
	<a href="{www_dir}{index}/trade/productedit/imagelist/{product_id}/" class="small" title="{intl-thumb_alt}">{intl-thumb_image}</a><br/>
	<!-- END no_product_image_tpl -->
	&nbsp;<br/>
	<!-- BEGIN main_image_tpl -->
	<span class="small">{intl-main_image}:</span><br/>
	<a href="{www_dir}{index}/trade/productedit/imagelist/{product_id}/"><img src="{www_dir}{main_image_uri}" alt="{intl-main_alt}" border="0"></a><br/>
	<!-- END main_image_tpl -->

	<!-- BEGIN no_main_image_tpl -->
	<a href="{www_dir}{index}/trade/productedit/imagelist/{product_id}/" class="small" title="{intl-main_alt}">{intl-main_image}</a><br/>
	<!-- END no_main_image_tpl -->

	</td>
	<td class="{td_class}" width="1%">
	
	<span class="small">{intl-main_category}:<br/></span>	
	<select name="MainCategoryID[]" STYLE="font-size:11px">
	
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	
	</select>

	<span class="small">{intl-multiple_categories}:<br/></span>	
	<select multiple size="5" STYLE="font-size:11px" name="CategoryArray[{category_array_id}][]">
	
	<!-- BEGIN multiple_value_tpl -->
	<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
	<!-- END multiple_value_tpl -->
	
	</select>
	</td>

	<td valign="top" colspan="2" class="{td_class}" width="1%">
	
	<span class="small">{intl-product_number}:</span><br/>
	<input type="text" size="21" name="ProductNumber[]" value="{product_number}" STYLE="font-size:11px"/><br/>
	
	
        <div class="check">
        <input type="checkbox" name="IsHotDeal[{category_array_id}]" STYLE="height: 11px; width: 11px;" {is_hot_deal_checked} />&nbsp;<span class="small">{intl-is_hot_deal}</span><br/>
        <input type="checkbox" name="Discontinued[{category_array_id}]" STYLE="height: 11px; width: 11px;" {discontinued_checked} />&nbsp;<span class="small">{intl-discontinued}</span><br/>
        <input type="checkbox" name="Active[{category_array_id}]" STYLE="height: 11px; width: 11px;" {showproduct_checked} />&nbsp;<span class="small">{intl-active}</span><br/>
        <input type="checkbox" name="ShowPrice[{category_array_id}]" STYLE="height: 11px; width: 11px;" {showprice_checked} />&nbsp;<span class="small">{intl-has_price}</span>
        </div>

	
	<!-- BEGIN quantity_item_tpl -->
	<span class="small">{intl-availability}:</span>
	<input type="text" size="3" name="Quantity[]" value="{quantity_value}" STYLE="font-size:11px"/><br/>
	
	<span class="small">{intl-stock_date}:</span></br>
	<select name="StockDay[]" STYLE="font-size:11px">
	
	<!-- BEGIN day_item_tpl -->
	<option value="{day_id}" {selected}>{day_value}</option>
	<!-- END day_item_tpl -->
	</select>

	<select name="StockMonth[]" STYLE="font-size:11px">
	<option value="1" {select_january}>{intl-january}</option>
	<option value="2" {select_february}>{intl-february}</option>
	<option value="3" {select_march}>{intl-march}</option>
	<option value="4" {select_april}>{intl-april}</option>
	<option value="5" {select_may}>{intl-may}</option>
	<option value="6" {select_june}>{intl-june}</option>
	<option value="7" {select_july}>{intl-july}</option>
	<option value="8" {select_august}>{intl-august}</option>
	<option value="9" {select_september}>{intl-september}</option>
	<option value="10" {select_october}>{intl-october}</option>
	<option value="11" {select_november}>{intl-november}</option>
	<option value="12" {select_december}>{intl-december}</option>
	</select>

        <input type="text" size="4" name="StockYear[]" value="{stockyear}" STYLE="font-size:11px"/>
	<!-- END quantity_item_tpl -->

    </td>
	
	<td valign="top" class="{td_class}" width="1%">
	<span class="small">{intl-vat_type}:</span><br/>
	<select name="VATTypeID[]" STYLE="font-size:11px">

	<!-- BEGIN vat_select_tpl -->
	<option value="{vat_id}" {vat_selected}>{vat_name}</option>
	<!-- END vat_select_tpl -->

	</select><br/>

	<span class="small">{intl-shipping_group}:</span></br>
	<select name="ShippingGroupID[]" STYLE="font-size:11px">

	<!-- BEGIN shipping_select_tpl -->
	<option value="{shipping_group_id}" {selected}>{shipping_group_name}</option>
	<!-- END shipping_select_tpl -->
	</select></br>

	<span class="small">{intl-box_type}:</span><br/>
	<select name="BoxTypeID[]" STYLE="font-size:11px">

	<option value="0" {box_selected}>{intl-no_box}</option>
	<!-- BEGIN box_select_tpl -->
	<option value="{box_id}" {box_selected}>{box_name}</option>
	<!-- END box_select_tpl -->

	</select><br/>
	
	<span class="small">{intl-weight}:</span></br>
	<input type="text" size="5" name="Weight[]" value="{weight_value}" STYLE="font-size:11px"/><br/>

    </td>
	
	<td valign="top" class="{td_class}" colspan="2" width="1%">
	<span class="small">{intl-external_link}:</span><br/>
	<span class="small">http://<input type="text" size="20" name="ExternalLink[]" value="{external_link}" STYLE="font-size:11px"/></span><br/>
	<span class="small">{intl-keywords}:</span><br/>
	<input type="text" name="Keywords[]" size="20" value="{keywords_value}" STYLE="font-size:11px"/>
	<br/>
	<!-- BEGIN section_item_tpl -->
	<span class="small">{section_name}:</span><br/>
	<!-- BEGIN link_item_tpl -->
	&nbsp;<a href="{www_dir}{index}{link_url}" class="small">{link_name}</a><br/>
	<!-- END link_item_tpl -->
	<!-- END section_item_tpl -->
	<span class="small">[<a href="{www_dir}{index}/trade/productedit/link/list/{product_id}/" class="small">{intl-add_link}</a>]</span>
	</td>	

</tr>
<tr>
<td valign="bottom" class="{td_class}" colspan="7" width="1%" style="border-bottom: 2px dotted black">
<span class="small">{intl-images}:&nbsp;[<a href="{www_dir}{index}/trade/productedit/imagelist/{product_id}/" class="small">{intl-add_image}</a>]
</span><br/>
<!-- BEGIN image_tpl -->
<img src="{www_dir}{image_url}" align="middle" alt="{image_caption}" width="{image_width}" height="{image_height}">&nbsp;&nbsp;
<!-- END image_tpl -->
</td>

</tr>
<!-- END detail_view_tpl -->

<!-- BEGIN normal_list_tpl -->
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
	<img src="{www_dir}/design/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
	<!-- END product_icon_tpl -->
	<!-- BEGIN voucher_icon_tpl -->
	<img src="{www_dir}/design/admin/images/product.gif" height="16" width="16" border="0" alt="" align="top" />
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
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveDown={product_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/categorylist/parent/{category_id}/?MoveUp={product_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>
	<!-- END absolute_placement_item_tpl -->
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/{url_action}/edit/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezti{product_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezti{product_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ProductArrayID[]" value="{product_id}">
	</td>
</tr>
<!-- END product_item_tpl -->

<!-- END normal_list_tpl -->

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
<!-- BEGIN update_button_tpl -->
<input class="stdbutton" type="submit" Name="UpdateProducts" value="{intl-update_products}" />
<!-- END update_button_tpl -->
<input class="stdbutton" type="submit" Name="DeleteProducts" value="{intl-deleteproducts}" />
</form>
<!-- END product_list_tpl -->

<form method="post" action="{www_dir}{index}/trade/categorylist/parent/{category_id}/{offset}/" enctype="multipart/form-data">
<input type="hidden" name="Detail" value="{is_detail_view}">

<!-- BEGIN normal_view_button -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NormalView" value="{intl-normal_view}">

<!-- END normal_view_button -->

<!-- BEGIN detail_view_button -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DetailView" value="{intl-detail_view}">

<!-- END detail_view_button -->

</form>