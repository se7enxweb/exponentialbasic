<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">
	<!-- BEGIN path_tpl -->
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/bug/archive/0/">{intl-top_level}</a>
	<!-- END path_tpl -->
	<!-- BEGIN path_item_tpl -->	
	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/bug/archive/{module_id}/">{module_name}</a>
	<!-- END path_item_tpl -->
	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-bug_view}</h1>

<br /> 

<table cellspacing="0" cellpadding="0" border="0" width="100%">
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

<!-- BEGIN version_number_tpl -->
<p class="boxtext">{intl-version_number}:</p>
<div class="p">{version_number_value}</div>
<!-- END version_number_tpl -->

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
	<td>
	<p class="boxtext">{intl-assigned_priority}:</p>
	{priority_name}
	</td>

	<td>
	<p class="boxtext">{intl-assigned_status}:</p>
	{status_name}
	</td>
</tr>
</table>

<br />
<p class="boxtext">{intl-is_closed}:</p>
<!-- BEGIN yes_tpl -->
<div class="p">{intl-yes}</div>
<!-- END yes_tpl -->
<!-- BEGIN no_tpl -->
<div class="p">{intl-no}</div>
<!-- END no_tpl -->

<!-- BEGIN screenshots_tpl -->
<h2>{intl-screenshots}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_name}:</th>
</tr>
<!-- BEGIN screenshot_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{image_number}
	</td>
	<td width="99%" class="{td_class}">
	{image_name}
	</td>
</tr>
<!-- END screenshot_item_tpl -->
</table>
<!-- END screenshots_tpl -->

<!-- BEGIN patches_tpl -->
<h2>{intl-patches}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-file_id}:</th>
	<th>{intl-file_name}:</th>
</tr>
<!-- BEGIN patch_item_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{file_number}
	</td>
	<td width="99%" class="{td_class}">
	{file_name}
	</td>
</tr>
<!-- END patch_item_tpl -->
</table>
<!-- END patches_tpl -->

<br />
<!-- BEGIN log_item_tpl -->
<p class="boxtext">{log_date}:</p>
<div class="p">{log_description}</div>
<!-- END log_item_tpl -->	

<br />
