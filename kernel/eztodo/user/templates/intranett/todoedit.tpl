<form method="post" action="{www_dir}{index}/todo/todoedit/{action_value}/{todo_id}/">
<h1>{intl-head_line}</h1>

<hr noshade size="4"/>

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_name_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_tpl -->

    <!-- BEGIN error_permission_tpl -->
    <li>{intl-error_permission}
    <!-- END error_permission_tpl -->

    <!-- BEGIN error_description_tpl -->
    <li>{intl-error_description}
    <!-- END error_description_tpl -->

    <!-- BEGIN error_user_tpl -->
    <li>{intl-error_user}
    <!-- END error_user_tpl -->

</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->


<br>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-name}</p>
	<input type="text" size="30" name="Name" value="{name}">
	<br><br>
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-owner}</p>
	{first_name} {last_name}
	<br><br>
	</td>
	<td>
	<p class="boxtext">{intl-user}</p>
	<select name="UserID">
	<!-- BEGIN user_item_tpl -->
	<option {user_is_selected} value="{user_id}">{user_firstname} {user_lastname}</option>
	<!-- END user_item_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-cat}</p>
	<select name="CategoryID">
	<!-- BEGIN category_select_tpl -->
	<option {is_selected} value="{category_id}">{category_name}</option>
	<!-- END category_select_tpl -->
	</select>
	<br><br>
	</td>
	<td>
	<p class="boxtext">{intl-pri}</p>
	<select name="PriorityID">
	<!-- BEGIN priority_select_tpl -->
	<option {is_selected} value="{priority_id}">{priority_name}</option>
	<!-- END priority_select_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-desc}</p>
	<textarea wrap="soft" cols="30" rows="10" name="Description">{description}</textarea>
	<br><br>
	</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%">
	<select name="StatusID">
	<!-- BEGIN status_select_tpl -->
	<option {is_selected} value="{status_id}">{status_name}</option>
	<!-- END status_select_tpl -->
	</select>
	<br><br>
	</td>
	<td width="25%">
	<p class="boxtext">{intl-view_others}:</p>
	<div class="check"><input type="checkbox" name="Permission" {permission}>&nbsp;</div>
	</td>
	<td width="25%">
	<p class="boxtext">{intl-send_mail}:</p>
	<div class="check"><input type="checkbox" name="SendMail">&nbsp;</div>
	</td>

</tr>
</table>

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" value="{intl-ok}">
	</td>
	<td>&nbsp;</td>
    <td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}">
	</td>
</tr>
</table>
</form>
