<h1>{intl-user_list}</h1>
<hr noshade="noshade" size="4">

<form action="{www_dir}{index}/bulkmail/userlist/" method="post">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td>
    <select name="ListID">
    <!-- BEGIN category_item_tpl -->
    <option value="{category_id}" {category_selected}>{category_name}</option>
    <!-- END category_item_tpl -->
    </select>
  </td>
  <td>&nbsp</td>
  <td><input type="submit" class="stdbutton" name="Ok" value="{intl-ok}" /></td>
</tr>
</table>

<!-- BEGIN address_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="100%">{intl-subscriber_normal}:</th>
</tr>
<!-- BEGIN address_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="mailto:{subscriber_address}">{subscriber_address}</a>
	</td>
</tr>
<!-- END address_item_tpl -->
</table>
<hr noshade="noshade" size="4">
<!-- END address_tpl -->

<!-- BEGIN user_address_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="100%">{intl-subscriber_user}:</th>
</tr>
<!-- BEGIN user_address_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="mailto:{subscriber_address}">{subscriber_address}</a>
	</td>
</tr>
<!-- END user_address_item_tpl -->
</table>
<hr noshade="noshade" size="4">
<!-- END user_address_tpl -->


<!-- BEGIN address_group_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="100%">{intl-subscriber_group}:</th>
</tr>
<!-- BEGIN address_item_group_tpl -->
<tr>
	<td class="{td_class}">
	<a href="mailto:{subscriber_address_group}">{subscriber_address_group}</a>
	</td>
</tr>
<!-- END address_item_group_tpl -->
</table>
<hr noshade="noshade" size="4">
<!-- END address_group_tpl -->




<!-- BEGIN no_subscribers_tpl -->
<br>
{intl-no_subscribers}
<hr noshade="noshade" size="4">
<!-- END no_subscribers_tpl -->

</form>