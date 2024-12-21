<form action="{www_dir}{index}/trade/productedit/imageedit/storedef/{product_id}/" method="post">

<h1>{intl-image} {product_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_images_tpl -->
{intl-no_images}
<!-- END no_images_tpl -->

<!-- BEGIN image_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-nr}:</th>
	<th>{intl-imagetext}:</th>
	<th>{intl-preview}:</th>
	<th>{intl-mainimage}:</th>
	<th>{intl-miniimage}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN image_tpl -->
<tr>
	<td class="{td_class}">
	{image_number}
	</td>
	<td class="{td_class}">
	{image_name}&nbsp;
	</td>
	<td class="{td_class}">
	<img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td class="{td_class}">
        <input type="radio" {main_image_checked} name="MainImageID" value="{image_id}" />
        </td>
	<td class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/productedit/imageedit/edit/{image_id}/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{image_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{image_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}">
	</td>
</tr>
<!-- END image_tpl -->

</table>
<!-- END image_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewImage"value="{intl-newimage}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NoMainImage" value="{intl-image_no_main}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="NoMiniImage" value="{intl-image_no_mini}" />
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
	<form action="{www_dir}{index}/trade/productedit/edit/{product_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

