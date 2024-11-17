

<form method="post" action="{www_dir}{index}/article/articleedit/mediaedit/{article_id}/{media_id}" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-mediaupload}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<br />

<p class="boxtext">{intl-mediatitle}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-mediacaption}:</p>
<input class="box" type="text" size="40" name="Caption" value="{caption_value}"/>

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

<p class="boxtext">{intl-mediafile}:</p>
<input class="box" size="40" name="userfile" type="file" />

<br>

<!-- BEGIN no_file_name_tpl -->
{intl-no_file_name}:
<!-- END no_file_name_tpl -->
<!-- BEGIN file_name_tpl -->
{media_file}:
<!-- END file_name_tpl -->

{media_size}
{media_unit}


<br /><br />

<select name="TypeID">
<option value="-1">{intl-no_attributes}</option>
<!-- BEGIN type_tpl -->
<option value="{type_id}" {selected}>{type_name}</option>
<!-- END type_tpl -->
</select>&nbsp;<input class="stdbutton" type="submit" name="Update" value="{intl-update}" />

<br /><br />

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
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>

	<form method="post" action="{www_dir}{index}/article/articleedit/medialist/{article_id}/">
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</form>

	</td>

</tr>
</table>



