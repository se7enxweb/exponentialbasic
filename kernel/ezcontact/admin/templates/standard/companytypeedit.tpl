<h1>{intl-headline_edit}</h1>
<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="{www_dir}/design/admin/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="{www_dir}{index}/contact/{command_type}/list/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="{www_dir}{index}/contact/companycategory/view/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<!-- BEGIN current_path_item_tpl -->
<img src="{www_dir}/design/admin/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="{www_dir}{index}/contact/companycategory/view/{parent_id}">{intl-current_edit}</a>
<!-- END current_path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<form method="post" action="{www_dir}{index}/contact/companycategory/{action_value}/{current_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">
<p class="boxtext">{intl-th_type_name}:</p>
<input type="text" size="40" name="TypeName" value="{current_name}">

<p class="boxtext">{intl-th_type_parent_name}:</p>
<select size="10" name="SelectParentID">

<option {root_selected} value="0">{intl-root_category}</option>
<!-- BEGIN parent_item_tpl -->
<option {selected} value="{select_parent_id}">{parent_level}{select_parent_name}</option>
<!-- END parent_item_tpl -->

</select>

<p class="boxtext">{intl-th_type_description}:</p>
<textarea rows="5" cols="40" name="TypeDescription" wrap="soft">{current_description}</textarea>
<input type="hidden" name="TypeID" value="{current_id}">
<input type="hidden" name="OldParentID" value="{parent_id}">

<!-- BEGIN image_item_tpl -->
<p class="boxtext">{intl-th_type_current_image}:</p>
<p><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
</p>
<p>{intl-th_image_uploading_new_replaces_old}</p>
<!-- END image_item_tpl -->
<p class="boxtext">{intl-th_type_image}:</p>
<!-- BEGIN no_image_item_tpl -->

<!-- END no_image_item_tpl -->
<input size="40" name="ImageFile" type="file" /><br /><br />
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="submit" value="{intl-button_ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/contact/{command_type}/list/{parent_id}/">
	<input class="okbutton" type="submit" name="back" value="{intl-button_back}" />
	</form>
	</td>
</tr>
</table>
<!-- END current_type_tpl -->
