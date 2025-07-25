<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp;
</div>
<!-- end #breadcrumbs -->

<!-- start #contentWrap -->
<div id="contentWrap">

<div>Due to a recent server upgrade, status information for orders placed before May 30, 2005 (all order numbers below 2500) is not viewable online. Please call us via telephone during business hours for a status update, as these orders will not be shown in our website's order status system.</div>

<!-- orderlist.tpl --> 
<!-- $Id: orderlist.tpl,v 1.1 2001/09/24 10:20:18 ce Exp $ -->

<h1 class="mainHeading">{intl-head_line} {full_name}</h1>

<!-- BEGIN order_item_list_tpl -->
<table width="90%" class="list" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="/trade/orderlist/?OrderBy=No">{intl-nr}:</a></th>
	<th><a href="/trade/orderlist/?OrderBy=Created">{intl-created}:</a></th>
	<!-- BEGIN order_status_header_tpl -->
	<th><a href="/trade/orderlist/?OrderBy=Status">{intl-status}:</a></th>
	<!-- END order_status_header_tpl -->
	<td ><b>{intl-price}:</b></td>
	<td ><b>{intl-view}:</b></td>
</tr>

<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">
	{order_id}
	</td>
	<td class="{td_class}">
	<span class="small">{order_date}</span>
	</td>
	<!-- BEGIN order_status_tpl -->
	<td class="{td_class}">
	{order_status}
	</td>
	<!-- END order_status_tpl -->
	<td class="{td_class}" >
	{order_price}
	</td>
	<td class="{td_class}" >
	<a href="{www_dir}{index}/trade/orderview/{order_id}/">{intl-view}</a>
	</td>
</tr>
<!-- END order_item_tpl -->

</table>
<!-- END order_item_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table width="90%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/orderlist/{item_previous_index}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_index}/">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

</div>
