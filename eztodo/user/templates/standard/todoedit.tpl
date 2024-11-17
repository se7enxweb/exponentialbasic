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

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="br" colspan="2">
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="box" size="40" name="Name" value="{name}">
	<br><br>
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-owner}:</p>
	{first_name} {last_name}
	<br><br>
	</td>
	<td>
	<p class="boxtext">{intl-user}:</p>
	<select name="UserID">
	<!-- BEGIN user_item_tpl -->
	<option value="{user_id}" {user_is_selected}>{user_firstname} {user_lastname}</option>
	<!-- END user_item_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-cat}:</p>
	<select name="CategoryID">
	<!-- BEGIN category_select_tpl -->
	<option {is_selected} value="{category_id}">{category_name}</option>
	<!-- END category_select_tpl -->
	</select>
	<br><br>
	</td>
	<td>
	<p class="boxtext">{intl-pri}:</p>
	<select name="PriorityID">
	<!-- BEGIN priority_select_tpl -->
	<option value="{priority_id}" {is_selected}>{priority_name}</option>
	<!-- END priority_select_tpl -->
	</select>
	<br><br>
	</td>
</tr>
<tr>
	<td colspan="2">
	<p class="boxtext">{intl-desc}:</p>
	<textarea wrap="soft" class="box" cols="40" rows="10" name="Description">{description}</textarea>
	<br><br>
	</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-status}:</p>
	<select name="StatusID">
	<!-- BEGIN status_select_tpl -->
	<option value="{status_id}" {is_selected}>{status_name}</option>
	<!-- END status_select_tpl -->
	</select>
	<br><br>
	</td>

	<td>
	<input type="checkbox" name="IsPublic" {todo_is_public}>&nbsp;<span class="boxtext">{intl-is_public}</span>
	</td>

<td>
<p class="boxtext">{intl-deadline_headline}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="1%" valign="bottom">
    <select name="DeadlineDay">
	<!-- BEGIN day_item_tpl -->
	<option value="{day_id}" {selected}>{day_value}</option>
	<!-- END day_item_tpl -->
	</select>
    </td>
    <td width="1%" valign="bottom">
    <select name="DeadlineMonth" >
	<option value="1" {select_january}>{intl-january}</option>
	<option value="2" {select_february}>{intl-february}</option>
	<option value="3" {select_march}>{intl-march}</option>
	<option value="4" {select_april}>{intl-april}</option>
	<option value="5" {select_may}>{intl-may}</option>
	<option value="6" {select_june}>{intl-june}</option>
	<option value="7" {select_july}>{intl-july}</option>
	<option value="8" {select_august}>{intl-august}</option>
	<option value="9" {select_september}>{intl-september}</option>
	<option value="10" {select_october}>{intl-october}</option>
	<option value="11" {select_november}>{intl-november}</option>
	<option value="12" {select_december}>{intl-december}</option>
	</select>
    </td>
    <td width="1%" valign="bottom">
        <input type="text" size="4" name="DeadlineYear" value="{deadlineyear}" />
    </td>
    <td width="97%" valign="bottom">
        &nbsp;
    </td>
</tr>
</table>
</td>


	<!-- BEGIN send_mail_tpl -->
	<td>
	<input type="checkbox" name="SendMail" {send_mail_checkbox}&nbsp;<span class="boxtext">{intl-send_mail}</span>
	</td>
	<!-- END send_mail_tpl -->
</tr>
</table>

<!-- BEGIN list_logs_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN log_item_tpl -->
<th>{log_created}</th>
<tr>
	<td>
	{log_view}
	</td>
</tr>
<!-- END log_item_tpl -->

<hr noshade size="4"/>
<br />
<tr>
        <td>
	<textarea wrap="soft" cols="40" rows="5" name="Log"></textarea>
	&nbsp;<input type="checkbox" name="MailLog">&nbsp;{intl-mail_log}
	</td>
</tr>
<tr>
        <td>
	<input type="submit" name="AddLog" value="{intl-add_log}">
	</td>
</tr>
</table>
<!-- END list_logs_tpl -->


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
