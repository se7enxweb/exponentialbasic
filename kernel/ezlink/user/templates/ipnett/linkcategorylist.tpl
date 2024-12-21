<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
  	<td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="Søk etter linker" />
         </form>
     </td>
</tr>
</table>

<!-- BEGIN path_tpl -->
<!--
<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="/link/category/0/">{intl-top}</a>
-->
<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->
<!--
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="/link/category/{category_id}/">{category_name}</a>
-->
<!-- END path_item_tpl -->

<br />
<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!--
<tr>
	<td colspan="2">
	<h2>{categories}</h2>
	</td>
</tr>
-->
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}" valign="top" width="1%">
	<img src="/images/folder.gif" height="16" width="16" border="0" alt="" /><br />
	</td>
	<td class="{td_class}" valign="top" width="98%" colspan="2">
	<span class="p"><a href="/link/category/{linkcategory_id}/">{linkcategory_name}</a></span>
	</td>
</tr>
<tr>
    <td class="{td_class}" width="99%" colspan="2">
	<span class="p">{linkcategory_description}</span><br />
	</td>
    <td class="{td_class}" width="1%">
	<!-- BEGIN image_item_tpl -->
	<a href="/link/category/{linkcategory_id}/">
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" /></a>
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- END no_image_tpl -->
    </td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_list_tpl -->


<!-- BEGIN link_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td colspan="3">
<!--
	<h2>{links}</h2>
-->
	<div class="boxtext">({link_start}-{link_end}/{link_total})</div>
	</td>
</tr>
<!-- BEGIN link_item_tpl -->
<tr class="{td_class}">
	<td class="{td_class}" valign="top" width="1%">
	<img src="/images/link.gif" height="16" width="16" border="0" alt="" /><br />
	</td>
	<td valign="top" width="99%" colspan="2">
	<span class="p"><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}" target="_blank">{link_name}</a></span>
	</td>
</tr>
    <td class="{td_class}" width="99%" valign="top" colspan="2">
	<span class="p">{link_description}</span></br />
	</td>
    <td class="{td_class}" width="1%" valign="top">
	<!-- BEGIN link_image_item_tpl -->
	<a href="/link/gotolink/addhit/{link_id}/?Url={link_url}"  target="_blank"><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" align="right" /></a>
	<!-- END link_image_item_tpl -->
	</td>
</tr>

<!-- BEGIN attribute_list_tpl -->
<tr>
	<td colspan="3" class="{td_class}" align="center">
	<table width="50%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN attribute_tpl -->

<!-- END attribute_tpl -->

<!-- BEGIN attribute_value_tpl -->
	<tr> 
		<th class="small">{attribute_name}:</th>
		<td class="small" align="right">{attribute_value_var} {attribute_unit}</td>
	</tr>
<!-- END attribute_value_tpl -->
<!-- BEGIN attribute_header_tpl -->
	<tr> 
		<th colspan="2">{attribute_name}:</th>
	</tr>
<!-- END attribute_header_tpl -->

	</table>
	</td>
</tr>
<!-- END attribute_list_tpl -->
<tr>
	<td colspan="3" class="{td_class}"><span class="small">(Hits: {link_hits})</span></td>
</tr>
<!-- END link_item_tpl -->
</table>
<!-- END link_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/link/category/{category_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="/link/category/{category_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="/link/category/{category_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
