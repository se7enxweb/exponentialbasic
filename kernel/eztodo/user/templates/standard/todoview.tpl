<form method="post" action="{www_dir}{index}/todo/todoedit/edit/{todo_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td  colspan="2">
	<h2>{todo_name}</h2><br />
	</td>
</tr>
<tr>
	<td >
	<p class="boxtext">{intl-owner}:</p>
	{first_name} {last_name}
	<br><br>
	</td>
	<td >
	<p class="boxtext">{intl-user}:</p>
	{user_firstname} {user_lastname}
	<br><br>
	</td>
</tr>
<tr>
	<td >
	<p class="boxtext">{intl-cat}:</p>
	<!-- BEGIN category_select_tpl -->
	{todo_category}
	<!-- END category_select_tpl -->
	<br><br>
	</td>

	<td >
	<p class="boxtext">{intl-pri}:</p>
	<!-- BEGIN priority_select_tpl -->
	{todo_priority}
	<!-- END priority_select_tpl -->
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-desc}:</p>
	</td>
</tr>
<tr>
	<td colspan="2" class="bglight">
	{todo_description}
	</td>
</tr>
<!-- BEGIN deadline_tpl -->
<tr>
        <td>&nbsp;</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-deadline}:</p>
	</td>
</tr>
<tr>
	<td>
	{todo_deadline}
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<!-- END deadline_tpl -->
<tr>

	<td >
	<p class="boxtext">{intl-status}:</p>
	<!-- BEGIN status_select_tpl -->
	{todo_status}
	<!-- END status_select_tpl -->
	</td>

	<!-- BEGIN todo_is_public_tpl -->	
	<td >
	{intl-todo_is_public}
	</td>
	<!-- END todo_is_public_tpl -->	
	<!-- BEGIN todo_is_private_tpl -->	
	<td >
	{intl-todo_is_private}
	</td>
	<!-- END todo_is_private_tpl -->	

</tr>
</table>

<br />

<!-- BEGIN list_logs_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
        <th>{intl-log_header}</th>
</tr>
<!-- BEGIN log_item_tpl -->
<th>{log_created}</th>
<tr>
	<td>
	{log_view}
	</td>
</tr>
<!-- END log_item_tpl -->
</table>
<!-- END list_logs_tpl -->

<!-- BEGIN mark_as_done -->
<!--<input class="stdbutton" type="submit" Name="Done" value="{intl-mark_as_done}">-->
<!-- END mark_as_done -->

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" Name="List" value="{intl-ok}">
	</td>
	<td>&nbsp;</td>
        <td>
	<input class="okbutton" type="submit" Name="Edit" value="{intl-edit}">
	</td>
</tr>
</table>
</form>

