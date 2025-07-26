<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-hetip_line} - {current_category_name}</h1>
	</td>
	<td rowspan="2" align="right">&nbsp;
	
	</td>
</tr>
</table>

<hr noshade size="4" />

<!-- BEGIN path_tpl -->


<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0">

<a class="path" href="{www_dir}{index}/tip/archive/0/">{intl-topcategory}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0">

<a class="path" href="{www_dir}{index}/tip/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<form method="post" action="{www_dir}{index}/tip/category/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/tip/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/tip/category/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{category_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>

<!-- END category_list_tpl -->


<!-- BEGIN tip_list_tpl -->
<form method="post" action="{www_dir}{index}/tip/tip/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image}:</th>
	<th>{intl-ad}:</th>
	<th>{intl-active}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN tip_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN image_item_tpl -->
	<!-- <p class="boxtext">{intl-th_type_current_image}:</p> -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="150" />
	<!-- END image_item_tpl -->
	<!-- BEGIN html_item_tpl -->
	{html_banner}
	<!-- END html_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	<p class="error">{intl-no_image}</p>
	<!-- END no_image_tpl -->
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/tip/statistics/{tip_id}/">
	{tip_name}
	</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN tip_is_active_tpl -->
	{intl-is_active}
	<!-- END tip_is_active_tpl -->
	<!-- BEGIN tip_not_active_tpl -->
	{intl-not_active}
	<!-- END tip_not_active_tpl -->
	&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/tip/tip/edit/{tip_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{tip_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{tip_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="TipArrayID[]" value="{tip_id}">
	</td>
</tr>
<!-- END tip_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" Name="DeleteTips" value="{intl-deletetips}">
</form>

<!-- END tip_list_tpl -->