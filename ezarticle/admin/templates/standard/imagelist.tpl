<form action="{www_dir}{index}/article/articleedit/imageedit/storedef/{article_id}/" method="post">

<h1>{intl-images}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_images_tpl -->
{intl-no_images}
<!-- END no_images_tpl -->

<!-- BEGIN image_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-image_id}:</th>
	<th>{intl-image_caption}:</th>
	<th>{intl-image_preview}:</th>
	<th>{intl-image_mini}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{image_number}
	</td>
	<td width="94%" class="{td_class}">
	{image_name}
	</td>
	<td width="1%" class="{td_class}">
	<img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td width="1%" class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/articleedit/imagelist/{article_id}/?MoveImageDown={image_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/articleedit/imagelist/{article_id}/?MoveImageUp={image_id}"><img src="{www_dir}/design/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a>
	</td>

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/articleedit/imageedit/edit/{image_id}/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{image_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}">
	</td>
</tr>
<!-- END image_tpl -->

</table>
<!-- END image_list_tpl -->

<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="NoFrontImage" value="{intl-image_no_front}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewImage" value="{intl-image_upload}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="{www_dir}{index}/article/articleedit/edit/{article_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>
