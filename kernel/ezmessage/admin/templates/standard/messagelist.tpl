<form action="{www_dir}{index}/message/list/" method="post">

<h1>{intl-messages_for} {user_first_name} {user_last_name} </h1>

<hr size="4" noshade="noshade" />

<!-- BEGIN message_list_tpl -->

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>
	{intl-message_is_read}:
	</th>
	<th>
	{intl-message_subject}:
	</th>
	<th>
	{intl-message_from_user}:
	</th>
	<th>
	{intl-message_date}:
	</th>
</tr>
<!-- BEGIN message_item_tpl -->
<tr>
        <!-- BEGIN message_read_tpl -->
	<td class="{td_class}">
	{intl-is_read}
	</td>
        <!-- END message_read_tpl -->
        <!-- BEGIN message_unread_tpl -->
	<td>
	{intl-is_unread}
	</td>
        <!-- END message_unread_tpl -->
	<td class="{td_class}" width="50%">
	<a href="{www_dir}{index}/message/view/{message_id}/">
	{message_subject}
	</a>
	</td>
	<td class="{td_class}">
	{message_from_user}
	</td>
	<td class="{td_class}">
	{message_date}
	</td>
</tr>

<!-- END message_item_tpl -->
</tr>
</table>

<!-- END message_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Refresh" value="{intl-refresh}" />

</form>
