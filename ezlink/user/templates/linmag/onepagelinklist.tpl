<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<!-- BEGIN category_list_tpl -->
<!-- BEGIN category_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td valign="top" colspan="2">
	<h2>{linkcategory_name}</h2>
	</td>
</tr>
<tr>
    <td width="99%">
	<div class="p">{linkcategory_description}</div>
	</td>
	 <td width="1%">
	<!-- BEGIN image_item_tpl -->
	<a href="/link/category/{linkcategory_id}/">
	<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" /></a>
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	<img src="/images/1x1.gif" height="1" width="1" border="0" alt="" /><br />
	<!-- END no_image_tpl -->
    </td>
</tr>
</table>

<!-- BEGIN link_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN link_item_tpl -->
<tr>
	<td class="{td_class}" valign="top" width="99%" colspan="2">
	<span class="boxtext"><a href="/link/gotolink/addhit/{link_id}/?Url={link_url}"  target="_blank">{link_name}</a></span>
   	</td>
</tr>
<tr>
    <td class="{td_class}" width="99%" valign="top" colspan="2">
   	<span class="p">{link_description}</span>
	<!-- BEGIN link_image_item_tpl -->
	<a href="{www_dir}{index}/link/gotolink/addhit/{link_id}/?Url={link_url}"  target="_blank"><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" align="right" /></a>
	<!-- END link_image_item_tpl -->
	</td>
</tr>
<!-- END link_item_tpl -->

<!-- BEGIN attribute_list_tpl -->
<tr>
	<td colspan="2" class="{td_class}">
	<div align="left">
	<table cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN attribute_tpl -->

<!-- END attribute_tpl -->

<!-- BEGIN attribute_value_tpl -->

	<tr> 
		<th>{attribute_name}:</th>
		<td>{attribute_value_var} {attribute_unit}</td>
	</tr>

<!-- END attribute_value_tpl -->

<!-- BEGIN attribute_header_tpl -->

	<tr> 
		<th colspan="2">{attribute_name}:</th>
	</tr>

<!-- END attribute_header_tpl -->

	</table>
	</div>
	</td>
</tr>

<!-- END attribute_list_tpl -->

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

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->
