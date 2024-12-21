<form method="post" action="{www_dir}{index}/user/groupedit/{action_value}/{group_id}/">

<h1>{head_line}</h1>

<hr noshade size="4"/>

<p class="boxtext">{error_msg}</p>

<p class="boxtext">{intl-name}</p>
<input type="text" size="20" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-description}</p>
<input type="text" size="40" name="Description" value="{description_value}"/>

	<!-- BEGIN module_list_header_tpl -->
	<p class="checkhead">{module_name}:</p>

	<input type="hidden" name="ModuleArray[]" value="{module_name}">

	<!-- BEGIN permission_list_tpl -->

	<input type="checkbox" name="PermissionArray[]" value="{permission_id}" {is_enabled}><span class="check"> {permission_name}</span><br>

	<!-- END permission_list_tpl -->

	<!-- END module_list_header_tpl -->

<br />


<hr noshade size="4"/>

<input type="hidden" name="GroupID" value="{group_id}" />
<input class="okbutton" type="submit" value="OK" />
<form method="post" action="{www_dir}{index}/user/grouplist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>

