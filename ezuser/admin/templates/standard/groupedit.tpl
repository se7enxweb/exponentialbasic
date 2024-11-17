<form method="post" action="{www_dir}{index}/user/groupedit/{action_value}/{group_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{name_value}" />


<p class="boxtext">{intl-description}:</p>
<textarea class="box" cols="40" rows="4" name="Description">{description_value}</textarea>

<br /><br />
<input type="checkbox" name="IsRoot"  value="HasRoot" {root_checked} /><span class="boxtext">&nbsp;{intl-root_permission}</span><br>

<p class="boxtext">{intl-session_timeout}:</p>
<input type="text" size="10" name="SessionTimeout" value="{session_timeout_value}" />

<p class="boxtext">{intl-group_url}:</p>
<input type="text" class="box" size="40" name="GroupURL" value="{group_url_value}" />

<br /><br />

        <h2>{intl-module_permissions}</h2>
        <hr noshade size="4"/>
	<!-- BEGIN module_list_header_tpl -->
	<p class="checkhead">{module_name}:</p>

	<input type="hidden" name="ModuleArray[]" value="{module_name}">

	<!-- BEGIN permission_list_tpl -->

	<input type="checkbox" name="PermissionArray[]" value="{permission_id}" {is_enabled}><span class="check"> {permission_name}</span><br>

	<!-- END permission_list_tpl -->

	<!-- END module_list_header_tpl -->

<br />


<hr noshade size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input type="hidden" name="GroupID" value="{group_id}" />
	<input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>
	</td>
</table>
</form>

