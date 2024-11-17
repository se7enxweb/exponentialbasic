<!-- orderlist.tpl --> 
<!-- $Id: orderlist.tpl 8699 2001-12-07 14:40:05Z kaid $ -->

<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-head_line}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/trade/orderlist/" method="post">
	<input type="text" name="QueryText" />
	<input class="stdbutton" type="submit" value="{intl-search}">
	</form>
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/trade/orderlist/?Offset={current_offset}">
<hr noshade="noshade" size="4" />

<!-- BEGIN order_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="/trade/orderlist/?OrderBy=No">{intl-nr}:</a></th>
	<th><a href="/trade/orderlist/?OrderBy=Created">{intl-created}:</a></th>
	<th><a href="/trade/orderlist/?OrderBy=Modified">{intl-modified}:</a></th>
	<th><a href="/trade/orderlist/?OrderBy=Status">{intl-status}:</a></th>
	<td align="right"><b>{intl-price}:</b></td>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">
	{order_id}
	</td>
	<td class="{td_class}">
	<span class="small">{order_date}</span>
	</td>
	<td class="{td_class}">
	<span class="small">{altered_date}</span>
	</td>
	<td class="{td_class}">
	{order_status}
	</td>
	<td class="{td_class}" align="right">
	{order_price}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/orderedit/{order_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{order_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezto{order_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
<!--	<a href="#" onClick="verify( '{intl-delete}', '/trade/orderedit/{order_id}/delete/'); return false;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{order_id}-slett','','{www_dir}/design/admin/images/{site_style}/slettminimrk.gif',1)"><img name="ezto{order_id}-slett" border="0" src="{www_dir}/design/admin/images/{site_style}/slettmini.gif" width="16" height="16" align="top"></a> -->
        <input type="checkbox" name="OrderArrayID[]" value="{order_id}" />
     	</td>
</tr>
<!-- END order_item_tpl -->

</table>
<!-- END order_item_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/orderlist/{item_previous_index}/?URLQueryString={url_query_string}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_index}/?URLQueryString={url_query_string}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_next_index}/?URLQueryString={url_query_string}">{intl-next}&nbsp;&gt;&gt;</a>
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
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>	


