<form method="post" action="{www_dir}{index}/imagecatalogue/image/{action_value}/{image_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="30000000">

<h1>{intl-imageupload}</h1>

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
</ul>

<hr noshade size="4"/>

<br />
<!-- END errors_tpl -->

<br />

	<!-- BEGIN image_tpl -->
	<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_tpl -->

<p class="boxtext">{intl-imagetitle}:</p>
<input type="text" class="box" size="40" name="Name" value="{name_value}"/>

<p class="boxtext">{intl-photographer}:</p>
<select name="PhotoID">
<!-- BEGIN photographer_item_tpl -->
<option value="{photo_id}" {selected}>{photo_name}</option>
<!-- END photographer_item_tpl -->
</select>
<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-new_photographer_name}:</p>
	<input class="halfbox" type="text" name="NewPhotographerName" size="20" value="" />
	</td>
	<td>
	<p class="boxtext">{intl-new_photographer_email}:</p>
	<input class="halfbox" type="text" name="NewPhotographerEmail" size="20" value="" />
	</td>
</tr>
</table>


<p class="boxtext">{intl-category}:</p>
<select name="CategoryID">
<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_level}{option_name}</option>
<!-- END value_tpl -->
</select>

<p class="boxtext">{intl-additional_category}:</p>

<select multiple size="{num_select_categories}" name="CategoryArray[]">

<!-- BEGIN multiple_value_tpl -->
<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
<!-- END multiple_value_tpl -->

</select>


<p class="boxtext">{intl-imagefile}:</p>
<input size="40" class="box" name="userfile" type="file" />

<p class="boxtext">{intl-imagecaption}:</p>
<input type="text" size="40" class="box" name="Caption" value="{caption_value}"/>

<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" cols="40" rows="5" wrap="soft">{image_description}</textarea>

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

<!-- BEGIN image_info_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
      <p class="boxtext">{intl-variations}:</p>
      <!-- BEGIN image_variation_tpl -->
      <tr><td class="{td_class}" >
      <a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/{variation_id}/?RefererURL=/imagecatalogue/image/edit/{image_id}/">{variation_width}x{variation_height}</a>
      </td></tr>
      <!-- END image_variation_tpl -->
</td>
<td>
      <p class="boxtext">{intl-articles}:</p>
      <!-- BEGIN article_item_tpl -->
      <tr><td class="{td_class}" >
      <a href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
      </td></tr>
      <!-- END article_item_tpl -->
</td>
<td>
      <p class="boxtext">{intl-products}:</p>
      <!-- BEGIN product_item_tpl -->
      <tr><td class="{td_class}" >
      <a href="{www_dir}{index}/trade/productedit/productpreview/{product_id}/">{product_name}</a>
      </td></tr>
      <!-- END product_item_tpl -->
</td>

</tr>
</table>
<!-- END image_info_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="ImageID" value="{image_id}" />
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
