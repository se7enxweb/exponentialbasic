<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-customerlist}</h1>
	</td>
     <td align="right">
	 <form action="{www_dir}{index}/trade/customerlist/" method="post">
	       <input type="text" name="SearchText">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<!-- BEGIN customer_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN customer_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/customerview/{customer_id}">{customer_first_name} {customer_last_name}</a>
	</td>
</tr>
<!-- END customer_item_tpl -->
</table>
<!-- END customer_item_list_tpl -->

<hr noshade="noshade" size="4" />
