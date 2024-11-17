<form method="post" action="{www_dir}{index}/mediacatalogue/media/{action_value}/{media_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-mediaupload}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>
    <!-- BEGIN error_name_tpl -->
    <li>{intl-error_name}
    <!-- END error_name_tpl -->

    <!-- BEGIN error_caption_tpl -->
    <li>{intl-error_caption}
    <!-- END error_caption_tpl -->

    <!-- BEGIN error_description_tpl -->
    <li>{intl-error_description}
    <!-- END error_description_tpl -->

    <!-- BEGIN error_file_upload_tpl -->
    <li>{intl-error_file_upload}
    <!-- END error_file_upload_tpl -->

    <!-- BEGIN error_read_everybody_permission_tpl -->
    <li>{intl-error_read_check}
    <!-- END error_read_everybody_permission_tpl -->

    <!-- BEGIN error_write_everybody_permission_tpl -->
    <li>{intl-error_write_check}
    <!-- END error_write_everybody_permission_tpl -->

</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->

<br />

<p class="boxtext">{intl-mediatitle}:</p>
<input type="text" class="box" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-creator}:</p>
<select name="PhotoID">
<!-- BEGIN photographer_item_tpl -->
<option value="{photo_id}" {selected}>{photo_name}</option>
<!-- END photographer_item_tpl -->
</select>

<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-new_creator_name}:</p>
	<input class="halfbox" type="text" name="NewCreatorName" size="20" value="" />
	</td>
	<td>
	<p class="boxtext">{intl-new_creator_email}:</p>
	<input class="halfbox" type="text" name="NewCreatorEmail" size="20" value="" />
	</td>
</tr>
</table>

<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
	<p class="boxtext">{intl-category}:</p>
	<select name="CategoryID">
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
	<td valign="top">
	<p class="boxtext">{intl-additional_category}:</p>

	<select multiple size="{num_select_categories}" name="CategoryArray[]">
	
	<!-- BEGIN multiple_value_tpl -->
	<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
	<!-- END multiple_value_tpl -->
	
	</select>
	</td>
</tr>
</table>

<p class="boxtext">{intl-mediafile}:</p>
<input size="40" class="box" name="userfile" type="file" />

<p class="boxtext">{intl-mediacaption}:</p>
<input type="text" size="40" class="box" name="Caption" value="{caption_value}"/>

<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" cols="40" rows="5" wrap="soft">{media_description}</textarea>

<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td valign="top">
    <p class="boxtext">{intl-read_permissions}:</p>
    <select multiple size="5" name="ReadGroupArrayID[]">
    <option value="0" {read_everybody}>{intl-everybody}</option>
    <!-- BEGIN read_group_item_tpl -->
    <option value="{group_id}" {is_read_selected1}>{group_name}</option>
    <!-- END read_group_item_tpl -->
    </select>
    <br /><br />
    </td>
    <td valign="top">
    <p class="boxtext">{intl-write_permissions}:</p>
    <select multiple size="5" name="WriteGroupArrayID[]">
    <option value="0" {write_everybody}>{intl-everybody}</option>
    <!-- BEGIN write_group_item_tpl -->
    <option value="{group_id}" {is_write_selected1}>{group_name}</option>
    <!-- END write_group_item_tpl -->
    </select>
    <br /><br />
    </td>
</tr>
</table>

<select name="TypeID">
<option value="-1">{intl-no_attributes}</option>
<!-- BEGIN type_tpl -->
<option value="{type_id}" {selected}>{type_name}</option>
<!-- END type_tpl -->
</select>&nbsp;<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />

<br />

<!-- BEGIN attribute_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th>{intl-attribute_name}:</th>
	<th>{intl-attribute_value}:</th>
</tr>
<!-- BEGIN attribute_tpl -->
<tr>
	<td>
	{attribute_name}: 
	</td>
	<td>
	<input type="hidden" name="AttributeID[]" value="{attribute_id}" />
	<input type="text" name="AttributeValue[]" value="{attribute_value}" />
	</td>
</tr>

<!-- END attribute_tpl -->
</table>
<!-- END attribute_list_tpl -->

<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="MediaID" value="{media_id}" />
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
