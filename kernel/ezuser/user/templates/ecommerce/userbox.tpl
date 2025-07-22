<form method="post" action="{www_dir}{index}/user/login/logout/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-userinfo}</td>
</tr>
<tr>
	<td colspan="2" class="menubold">
	{intl-userlogin}
	</td>
</tr>
<tr>
	<td colspan="2" class="menu">
	{user_first_name} {user_last_name}
	</td>
</tr>

<tr><td colspan="2">

<!-- BEGIN message_switch_tpl -->
<a href="{www_dir}{index}/message/list/">
<img src="{www_dir}/images/newpm.gif" alt="
<!-- BEGIN message_switch_item_tpl -->
{intl-date}: {message_date}
{intl-from}: {message_from_user}
{intl-subject}: {message_subject}
&nbsp;
<!-- END message_switch_item_tpl -->
" border="0"/></a>&nbsp;<a href="{www_dir}{index}/message/list/" class="menu" title="{new_message_count}&nbsp;{intl-new-message-count}">{intl-new-messages}</a></br>


</td></tr>
<!-- END message_switch_tpl -->	
<tr>
	<td colspan="2">
	<input class="stdbutton" type="submit" value="{intl-logout}" />
	</td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}{user_edit_url}{user_id}/">{intl-change_user_info}</a></td>
</tr><tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/message/list/" title="{message_count}&nbsp;{intl-message-count}&nbsp;({new_message_count}&nbsp;{intl-new-message-count})">{intl-view-private-messages}</a></td>
</tr>

<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="RedirectURL" value="{redirect_url}">
</form>

