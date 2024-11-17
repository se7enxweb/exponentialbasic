<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - {current_category_name}</h1>
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/ad/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0">

<a class="path" href="{www_dir}{index}/ad/archive/0/">{intl-topcategory}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0">

<a class="path" href="{www_dir}{index}/ad/archive/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</td>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<form method="post" action="{www_dir}{index}/ad/category/edit/" enctype="multipart/form-data">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/ad/archive/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/ad/category/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
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


<!-- BEGIN ad_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image}:</th>
	<th>{intl-ad}:</th>
	<th>{intl-active}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<form method="post" action="{www_dir}{index}/ad/ad/edit/" enctype="multipart/form-data">
<!-- BEGIN ad_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN image_item_tpl -->
	<!-- <p class="boxtext">{intl-th_type_current_image}:</p> -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
	<!-- END image_item_tpl -->
	<!-- BEGIN html_item_tpl -->
	{html_banner}
	<!-- END html_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	<p class="error">{intl-no_image}</p>
	<!-- END no_image_tpl -->
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/ad/statistics/{ad_id}/">
	{ad_name}
	</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN ad_is_active_tpl -->
	{intl-is_active}
	<!-- END ad_is_active_tpl -->
	<!-- BEGIN ad_not_active_tpl -->
	{intl-not_active}
	<!-- END ad_not_active_tpl -->
	&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/ad/ad/edit/{ad_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{ad_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{ad_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="AdArrayID[]" value="{ad_id}">
	</td>
</tr>
<!-- END ad_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" Name="DeleteAds" value="{intl-deleteads}">
</form>

<!-- END ad_list_tpl -->

