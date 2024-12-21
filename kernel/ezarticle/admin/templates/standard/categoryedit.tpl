<form method="post" action="{www_dir}{index}/article/categoryedit/{action_value}/{category_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN error_permission_tpl -->
<p class="error">{intl-permission_error}</p>
<!-- END error_permission_tpl -->

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}" />
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

<input type="checkbox" name="ExcludeFromSearch" {exclude_checked} />
<span class="boxtext">{intl-exclude_from_search}</span><br />
	</td>
	<td>
<p class="boxtext">{intl-list_limit}:</p>
<input type="text" size="2" name="ListLimit" value="{list_limit_value}" /><br />
	</td>
</tr>
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

	<p class="boxtext">{intl-place}:</p>
	<select name="ParentID">
	<option value="0">{intl-categoryroot}</option>

	<!-- BEGIN value_tpl -->
	<option {selected} value="{option_value}">{option_level}{option_name}</option>
	<!-- END value_tpl -->

	</select>

	</td>
  	<td>
	<p class="boxtext">{intl-sort_mode}:</p>
	<select name="SortMode">

	<option {1_selected} value="1">{intl-publishing_date}</option>
	<option {5_selected} value="5">{intl-modification_date}</option>
	<option {2_selected} value="2">{intl-alphabetic_asc}</option>
	<option {3_selected} value="3">{intl-alphabetic_desc}</option>
	<option {4_selected} value="4">{intl-absolute_placement}</option>

	</select>

	</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr> 
<tr>
       <td>
       <p class="boxtext">{intl-bulkmail_category_select}:</p>
       <select name="BulkMailID">
       <option value="-1" {no_bulkmail_selected}>{intl-no_bulkmail}</option>
       <!-- BEGIN bulkmail_category_item_tpl -->
       <option value="{bulkmail_category_id}" {bulkmail_selected}>{bulkmail_category_name}</option>
       <!-- END bulkmail_category_item_tpl -->
       </select>
       </td>
       <td>
	<p class="boxtext">{intl-section_select}:</p>
	<select name="SectionID">
	<!-- BEGIN section_item_tpl -->
	<option value="{section_id}" {section_is_selected}>{section_name}</option>
	<!-- END section_item_tpl -->
	</select>
	</td>
</tr>
</table>

<br />



<p class="boxtext">{intl-description}:</p>
<textarea class="box" rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-groups}:</p>
	<select name="GroupArray[]" multiple size="7">
	<option value="0" {all_selected}>{intl-all}</option>
	<!-- BEGIN group_item_tpl -->
	<option value="{group_id}" {selected}>{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	</td>
	<td>
	<!-- {intl-owner_group -->
	<p class="boxtext">{intl-write_groups}:</p>
	<!-- <th class "boxtext" width="50%">{intl-recursive}:</th> -->
	    <select name="WriteGroupArray[]" multiple size="7">
	    <option value="0" {all_write_selected}>{intl-all}</option>
	    <!-- BEGIN category_owner_tpl -->
	    <option value="{module_owner_id}" {is_selected}>{module_owner_name}</option>
	    <!-- END category_owner_tpl -->
	    </select>
	<!--    <input type="checkbox" name="Recursive" /> -->
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<tr>

	<td>
	<p class="boxtext">{intl-editor_group}:</p>
	<select name="EditorGroupID" size="7">
	<option value="0" {no_selected}>{intl-do_not_use_editor}</option>
        <!-- BEGIN editor_group_item_tpl -->
	<option value="{editor_group_id}" {editor_selected}>{editor_group_name}</option>
	<!-- END editor_group_item_tpl -->
	</select>
	</td>
</tr>
</table>

<br />

<p class="boxtext">{intl-th_type_current_image}:</p>

<!-- BEGIN image_item_tpl -->
<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
<div><input type="checkbox" name="DeleteImage"><span class="p">{intl-delete_image}</span><div /><br />
<!-- END image_item_tpl -->

<input class="box" size="40" name="ImageFile" type="file" />
<br /><br />
<input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
<br /><br />



<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
	</td>
	<td>&nbsp;</td>
	<td>
       <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
	
