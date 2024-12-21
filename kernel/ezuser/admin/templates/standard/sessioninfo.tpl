<form method="post" action="{www_dir}{index}/user/sessioninfo/delete" enctype="multipart/form-data">

<h1>{intl-logged_in_users} - ( {user_count} )</h1>

<hr noshade size="4">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-user_id}:
	</th>

	<th>
	{intl-name}:
	</th>

	<th>
	{intl-email}:
	</th>

	<th>
	{intl-idle}:
	</th>


	<th>
	{intl-session_ip}:
	</th>

	<th>
	&nbsp;
	</th>
</tr>
<!-- BEGIN user_item_tpl -->
<tr>
	<td class="{td_class}">
	{user_id}
	</td>

	<td class="{td_class}">
	{first_name} {last_name}
	</td>

	<td class="{td_class}">
	{email}
	</td>

	<td class="{td_class}">
	{idle}
	</td>

	<td class="{td_class}">
	{session_ip}
	</td>


	<td class="{td_class}" width="1%">
	  <input type="checkbox" name="SessionArrayID[]" value="{session_id}" />
	</td>	
</tr>
<!-- END user_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

</form>