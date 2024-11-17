<form method="post" action="{www_dir}{index}/bug/module/{action_value}/{module_id}/">
<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Name" value="{module_name}">

<p class="boxtext">{intl-module}:</p>
<select name="ParentID">
<option value="0">{intl-topcat}</option>
<!-- BEGIN module_item_tpl -->
<option value="{module_parent_id}" {is_selected}>{module_parent_name}</option>
<!-- END module_item_tpl -->
</select>
<br />
<br />
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<th class "boxtext" width="20%">{intl-owner}:</th>
<tr>
  <td>
    <select multiple size="5" name="WriteGroupArrayID[]">
    <option value="0">{intl-none}</option>
    <!-- BEGIN write_group_item_tpl -->
    <option value="{group_id}" {is_write_selected1}>{group_name}</option>
    <!-- END write_group_item_tpl -->
    </select>
  </td>
</tr>
<tr>
    <td>
	<div class="check"><input type="checkbox" name="Recursive" />&nbsp;{intl-recursive}</div>
    </td>
</tr>
</table>
<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}">
</form>
