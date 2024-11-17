<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="{www_dir}{index}/link/category/0/">{intl-top}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="{www_dir}{index}/link/category/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade size="4" />
<!-- BEGIN category_list_tpl -->
<form method="post" action="{www_dir}{index}/link/categoryedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="6">
	<h2>{categories}</h2>
	</td>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td width="1%" class="{bg_color}">
	<img src="{www_dir}/design/admin/images/folder.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td class="{bg_color}">
	<a href="{www_dir}{index}/link/category/{linkcategory_id}/">{linkcategory_name}</a>
	</td>
	<td class="{bg_color}">
	{category_description}&nbsp;
	</td>
        <td class="{bg_color}">
	<!-- BEGIN image_item_tpl -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td width="1%" class="{bg_color}">
	<a href="{www_dir}{index}/link/categoryedit/edit/{linkcategory_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ela{linkcategory_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ela{linkcategory_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{bg_color}" width="1%" align="center">
	<input type="checkbox" name="CategoryArrayID[]" value="{linkcategory_id}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteCategories" value="{intl-deletecategories}">
</form>
<!-- END category_list_tpl -->


<!-- BEGIN link_list_tpl -->
<form method="post" action="{www_dir}{index}/link/linkedit/edit/" enctype="multipart/form-data">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="6">
	<h2>{links}</h2>
	<div class="boxtext">({link_start}-{link_end}/{link_total})</div>
	</td>
</tr>
<!-- BEGIN link_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}/design/admin/images/link.gif" height="16" width="16" border="0" alt="" align="top" />
	</td>
	<td width="97%" class="{td_class}">
	<b><a href="{www_dir}{index}/link/gotolink/addhit/{link_id}/?Url={link_url}" target="_blank">{link_name}</a></b><br />
	{link_description}
	</td>
        <td class="{td_class}">
	<!-- BEGIN image_item_tpl -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
        </td>
	<td class="{td_class}" width="80" align="right">
	(Hits:&nbsp;{link_hits})
	</td>
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/link/linkedit/edit/{link_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('el{link_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="el{link_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="LinkArrayID[]" value="{link_id}">
	</td>
</tr>
<!-- END link_item_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="DeleteLinks" value="{intl-delete_links}">
<input type="hidden" Name="LinkCategoryID" value="{category_id}">
</form>
<!-- END link_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/link/category/{category_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/link/category/{category_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/link/category/{category_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
