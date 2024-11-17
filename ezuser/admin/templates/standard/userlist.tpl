<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line} - ( {user_count} / {total_user_count} )</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/user/userlist/" method="post">
	<input type="text" name="SearchText" size="12" />
	<input class="stdbutton" Name="Search" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>


<hr noshade size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<form method="post" action="{www_dir}{index}/user/userlist/0/{sort_order}">
	<select name="GroupID">
	<option value="0">{intl-all}</option>
	<!-- BEGIN group_item_tpl -->
	<option {is_selected} value="{group_id}">{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	<input class="stdbutton" type="submit" value="{intl-show}">
	</form>
	</td>
</tr>

<tr>
	<th>
	<a href="{www_dir}{index}/user/userlist/0/name/{current_group_id}">{intl-name}:</a>
	</th>

	<th>
	<a href="{www_dir}{index}/user/userlist/0/email/{current_group_id}">{intl-email}:</a>
	</th>

	<th>
	<a href="{www_dir}{index}/user/userlist/0/login/{current_group_id}">{intl-login}:</a>
	</th>

	<th>
	&nbsp;
	</th>

	<th>
	&nbsp;
	</th>

</tr>
<form method="post" action="{www_dir}{index}/user/useredit/edit/" enctype="multipart/form-data">
<!-- BEGIN user_item_tpl -->
<tr>
	<td class="{td_class}">
	{first_name} {last_name}
	</td>

	<!-- BEGIN user_email_item_tpl -->
	<td class="{td_class}">
	{email}
	</td>
	<!-- END user_email_item_tpl -->
	<!-- BEGIN user_empty_email_item_tpl -->
	<td class="{td_class}">
	&nbsp;
	</td>
	<!-- END user_empty_email_item_tpl -->

	<td class="{td_class}">
	{login_name}
	</td>

	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/user/useredit/edit/{user_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{user_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezuser{user_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="UserArrayID[]" value="{user_id}">
	</td>
</tr>
<!-- END user_item_tpl -->

<tr>
	<td colspan="5">
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/user/userlist/{item_previous_index}/{sort_order}/{current_group_id}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/user/userlist/{item_index}/{sort_order}/{current_group_id}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/user/userlist/{item_next_index}/{sort_order}/{current_group_id}">{intl-next}&nbsp;&gt;&gt;</a>
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
	</td>
</tr>

<tr>
    <td colspan="5">
	<hr noshade="noshade" size="4" />
	<input class="stdbutton" type="submit" Name="DeleteUsers" value="{intl-delete_users}">
	</td>
</tr>
</form>
</table>
