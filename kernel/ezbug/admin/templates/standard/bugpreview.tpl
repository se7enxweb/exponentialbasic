<h1>{intl-bug_view}</h1>

<hr noshade="noshade" size="4">
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
	{module_name}
	</td>
	<td width="50%">
	<p class="boxtext">{intl-bug_category}:</p>
	{category_name}
	</td>
</tr>
</table>

<p class="boxtext">{intl-bug_description}:</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
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
	{priority_name}
	</td>
	<td width="50%">
	<p class="boxtext">{intl-assigned_status}:</p>
	{status_name}
	</td>
</tr>
</table>

<p class="boxtext">{intl-is_closed}:</p>
<!-- BEGIN yes_tpl -->
<div class="p">{intl-yes}</div>
<!-- END yes_tpl -->
<!-- BEGIN no_tpl -->
<div class="p">{intl-no}</div>
<!-- END no_tpl -->

<!-- BEGIN log_item_tpl -->
<p class="boxtext>{log_date}</p>
<div class="p">{log_description}</div>
<!-- END log_item_tpl -->	

<br />






