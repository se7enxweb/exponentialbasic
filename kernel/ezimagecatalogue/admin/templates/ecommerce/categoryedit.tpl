<form method="post" action="{www_dir}{index}/imagecatalogue/category/{action_value}/{category_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-category_edit}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN errors_tpl -->
<div class="error">{intl-error_headline}</div>
<ul>
    <!-- BEGIN error_write_permission -->
    <li>{intl-error_write_permission}
    <!-- END error_write_permission -->

    <!-- BEGIN error_name_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_tpl -->

    <!-- BEGIN error_description_tpl -->
    <li>{intl-error_description}
    <!-- END error_description_tpl -->

    <!-- BEGIN error_parent_check_tpl -->
    <li>{intl-error_parent_check}
    <!-- END error_parent_check_tpl -->

    <!-- BEGIN error_read_everybody_permission_tpl -->
    <li>{intl-error_read_check}
    <!-- END error_read_everybody_permission_tpl -->

    <!-- BEGIN error_write_everybody_permission_tpl -->
    <li>{intl-error_write_check}
    <!-- END error_write_everybody_permission_tpl -->
</ul>

<hr noshade size="4"/>


<!-- END errors_tpl -->
<p class="boxtext">{intl-category_name}:</p>
<input type="text" size="40" class="box" name="Name" value="{category_name}"/>

<table with="100%" border="0">
<tr>
    <td><p class="boxtext">{intl-category}:</p>

    <select name="ParentID">
    <option value="0" {selected}>{intl-root_level}</option>
    <!-- BEGIN value_tpl -->
    <option value="{option_value}" {is_selected}>{option_level}{option_name}</option>
    <!-- END value_tpl -->

    </select></td>
    <td><p class="boxtext">{intl-section_select}:</p>
    <select name="SectionID">
    <!-- BEGIN section_item_tpl -->
    <option value="{section_id}" {section_is_selected}>{section_name}</option>
    <!-- END section_item_tpl -->
    </select></td>
</tr>
</table>
<br />

<p class="boxtext">{intl-category_description}:</p>

<textarea name="Description" class="box" cols="40" rows="5" wrap="soft">{category_description}</textarea>
<br />
<br />
	
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-read_permissions}</p>
    <select multiple size="5" name="ReadGroupArrayID[]">
    <option value="0" {read_everybody}>{intl-everybody}</option>
    <!-- BEGIN read_group_item_tpl -->
    <option value="{group_id}" {is_read_selected1}>{group_name}</option>
    <!-- END read_group_item_tpl -->
    </select>
    <br /><br />
    </td>
   <td>
    <p class="boxtext">{intl-write_permissions}</p>
    <select multiple size="5" name="WriteGroupArrayID[]">
    <option value="0" {write_everybody}>{intl-everybody}</option>
    <!-- BEGIN write_group_item_tpl -->
    <option value="{group_id}" {is_write_selected1}>{group_name}</option>
    <!-- END write_group_item_tpl -->
    </select>
    <br /><br />
    </td>
    <td>
    <p class="boxtext">{intl-upload_permissions}</p>
    <select multiple size="5" name="UploadGroupArrayID[]">
    <option value="0" {upload_everybody}>{intl-everybody}</option>
    <!-- BEGIN upload_group_item_tpl -->
    <option value="{group_id}" {is_upload_selected1}>{group_name}</option>
    <!-- END upload_group_item_tpl -->
    </select>
    </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="{intl-ok}" />

	</td>
	<td>&nbsp;</td>
	<td>

	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

	</td>

</tr>
</table>

</form>


