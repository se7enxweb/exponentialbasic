<form method="post" action="{www_dir}{index}/bug/edit/" enctype="multipart/form-data">

<h1>{intl-edit_bug}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-bug_title}:</p>
	{name_value}
	<br /><br />
	</td>
	<td width="50%">
	<p class="boxtext">{intl-bug_date}:</p>
	{bug_date}
	<br /><br />
	</td>
</tr>
<tr>
	<td width="50%">
	<p class="boxtext">{intl-bug_module}:</p>
	<select name="ModuleID">
	<!-- BEGIN module_item_tpl -->
	<option value="{module_id}" {selected}>{module_name}</option>
	<!-- END module_item_tpl -->
	</select>
	</td>
	<td width="50%">
	<p class="boxtext">{intl-bug_category}:</p>
	<select name="CategoryID">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {selected}>{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
</table>

<!-- BEGIN program_version_tpl -->
<p class="boxtext">{intl-version_number}:</p>
<div class="p">{version_value}</div>
<!-- END program_version_tpl -->

<p class="boxtext">{intl-bug_reporter}:</p>
<div class="p">{reporter_name_value}</div>

<p class="boxtext">{intl-bug_description}:</p>
<table cellspacing="0" cellpadding="4" border="0" width="100%">
<tr>
	<td class="bglight">
	{description_value}
	</td>
</tr>
</table>
<br />

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td width="50%">
	<p class="boxtext">{intl-assigned_priority}:</p>
	<select name="PriorityID">
	<!-- BEGIN priority_item_tpl -->
	<option value="{priority_id}" {selected}>{priority_name}</option>
	<!-- END priority_item_tpl -->
	</select>
	<br /><br />
	</td>
	<td width="50%">
	<p class="boxtext">{intl-assigned_status}:</p>
	<select name="StatusID">
	<!-- BEGIN status_item_tpl -->
	<option value="{status_id}" {selected}>{status_name}</option>
	<!-- END status_item_tpl -->
	</select>
	<br /><br />
	</td>
</tr>
<tr>
	<td>
	<input type="hidden" name="CurrentOwnerID" value="{current_owner_id}" />
	<p class="boxtext">{intl-owner}:</p>
	<select name="OwnerID">
	<option value="-1"> {intl-none}</option>
	<!-- BEGIN owner_item_tpl -->
	<option value="{owner_id}" {selected}>{owner_login}</option>
	<!-- END owner_item_tpl -->
	</select>
	<br />
	</td>
	<td>
	<input type="checkbox" name="IsPrivate" {is_private} />
	<span class="boxtext">{intl-is_private}</span><br />
	</td>
</tr>
</table>

<br />

<!-- BEGIN file_headers_tpl -->
<h2>{intl-avaliable_patches}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-file_id}:</th>
	<th>{intl-file_name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN file_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{file_number}
	</td>
	<td width="98%" class="{td_class}">
	{file_name}
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}" />
	</td>
</tr>
<!-- END file_tpl -->
</table>
<!-- END file_headers_tpl -->

<!-- inserted Images -->
<!-- BEGIN image_headers_tpl -->
<h2>{intl-avaliable_screenshots}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_name}:</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{image_number}
	</td>
	<td width="98%" class="{td_class}">
	{image_name}
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}" />
	</td>
</tr>
<!-- END image_tpl -->
</table>
<!-- END image_headers_tpl -->

<!-- BEGIN log_item_tpl -->
<p class="boxtext">{log_date}:</p>
<div class="p">{log_description}</div>
<!-- END log_item_tpl -->	

<br />

<p class="boxtext">{intl-log_message}:</p>
<textarea name="LogMessage" class="box" cols="40" rows="5" wrap="soft"></textarea>
<br>
<input type="checkbox" name="MailReporter" checked />
<span class="check">{intl-mail_bug_reporter}</span><br />

<br />

<input type="checkbox" name="IsClosed" {is_closed} />
<span class="boxtext">{intl-is_closed}</span><br />

<br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-del_selected}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input name="InsertImage" class="stdbutton" type="submit" value="{intl-add_screenshot}" /></td>
  <td>&nbsp; </td>
  <td><input name="InsertFile" class="stdbutton" type="submit" value="{intl-add_patch}" /></td>
</tr>
</table>

<hr noshade="noshade" size="4">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="Update" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="BugID" value="{bug_id}">

</form>


