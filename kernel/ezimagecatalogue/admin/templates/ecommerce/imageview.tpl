<h1>{intl-headline}</h1>
<hr noshade size="4"/>

<img src="{www_dir}/design/admin/images/layout/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/imagecatalogue/list/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/imagecatalogue/image/list/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade size="4"/>

<table align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<a href="{www_dir}{index}{referer_url}">
	<img src="{www_dir}{image_uri}" border="0" width="{image_width}" height="{image_height}" /></a>
	</td>
</tr>
<tr>
	<td class="pictext">
	{image_caption}
	</td>
</tr>
</table>

<hr noshade size="4"/>

<a href="{www_dir}{index}{referer_url}">{intl-back}</a>