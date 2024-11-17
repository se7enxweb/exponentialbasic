<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<form method="post" action="{www_dir}{index}/bug/report/{action_value}/{bug_id}">

<h1>{intl-report_a_bug}</h1>

<!-- BEGIN all_fields_error_tpl -->
<span class="error">{intl-all_fields_error}</span>
<!-- END all_fields_error_tpl -->

<!-- BEGIN email_error_tpl -->
<span class="error">{intl-email_error}</span>
<!-- END email_error_tpl -->

<p class="boxtext">{intl-bug_title}:</p>
<input class="box" type="text" size="40" name="Name" value="{title_value}"/>
<br /><br />

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td width="33%">
	<p class="boxtext">{intl-bug_module}:</p>
	<select name="ModuleID">
	<!-- BEGIN module_item_tpl -->
	<option value="{module_id}" {selected}>{module_name}</option>
	<!-- END module_item_tpl -->
	</select>
	</td>

	<td width="33%">
	<p class="boxtext">{intl-bug_category}:</p>
	<select name="BugCategoryID">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {selected}>{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
</table>

<p class="boxtext">{intl-program_version}:</p>
<input class="halfbox" type="text" size="20" name="Version" value="{version_value}" />
<br /><br />


<!-- BEGIN email_address_tpl -->
<p class="boxtext">{intl-email_address} ({intl-if_you_are_a registered_user_please_log_in}):</p>
<input class="halfbox" type="text" size="20" name="Email" value="{usr_email}"/>
<!-- END email_address_tpl -->

<p class="boxtext">{intl-bug_description}:</p>
<textarea class="box" name="Description" cols="40" rows="8" wrap="soft">{description_value}</textarea>
<br /><br />
  <input type="checkbox" name="IsPrivate" value="true" {private_checked} />&nbsp;<span class="boxtext">{intl-private}</span>
<br />


<!-- BEGIN inserted_files_tpl -->
<h2>{intl-avaliable_patches}:</h2>
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
	<td width="97%" class="{td_class}">
	{file_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/bug/report/fileedit/edit/{file_id}/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}" />
	</td>
</tr>
<!-- END file_tpl -->

</table>
<!-- END inserted_files_tpl -->



<!-- BEGIN inserted_images_tpl -->
<h2>{intl-avaliable_screenshots}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{image_number}
	</td>
	<td width="96%" class="{td_class}">
	{image_name}
	</td>
	<td width="1%" class="{td_class}">
	  <img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>	
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/bug/report/imageedit/edit/{image_id}/{bug_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}" />
	</td>
</tr>
<!-- END image_tpl -->

</table>
<!-- END inserted_images_tpl -->

<br />
<!-- BEGIN delete_items_tpl -->

<input name="DeleteSelected" class="stdbutton" type="submit" value="{intl-del_selected}" />
<!-- END delete_items_tpl -->


<table cellspacing="0" cellpadding="0" border="0">
<tr>
  	<td><input name="InsertImage" class="stdbutton" type="submit" value="{intl-add_screenshot}" /></td>
  	<td>&nbsp; </td>
  	<td><input name="InsertFile" class="stdbutton" type="submit" value="{intl-add_patch}" /></td>
</tr>
</table>

<br />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="Ok" value="{intl-send_bug_report}">
	</td>
</tr>
</table>

</form>
