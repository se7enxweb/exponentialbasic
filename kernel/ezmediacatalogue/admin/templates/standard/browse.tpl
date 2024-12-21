<form method="post" action="{www_dir}{index}{action_url}" enctype="multipart/form-data">

<input type="hidden" name="CategoryID" value="{main_category_id}">

<h1>{intl-add_media_for} {name}</h1>

<!-- BEGIN current_category_tpl -->

<!-- END current_category_tpl -->

<hr noshade="noshade" size="4" />

<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="{www_dir}{index}/mediacatalogue/browse/0/">{intl-media_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="{www_dir}{index}/mediacatalogue/browse/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">

<!-- BEGIN category_tpl -->
<tr>
        <!-- BEGIN category_read_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/mediacatalogue/browse/{category_id}/"><img src="{www_dir}/images/folder.gif" alt="" width="16" height="16" border="0" /></a>
	</td>
	<td class="{td_class}" width="38%">
	<a href="{www_dir}{index}/mediacatalogue/browse/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}" width="59%">
	{category_description}
	</td>
        <!-- END category_read_tpl -->
</tr>
<!-- END category_tpl -->

</table>
<!-- END category_list_tpl -->

<!-- BEGIN media_list_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td class="{td_class}" width="1%">
	<img src="{www_dir}/images/file.gif" border="0" alt="" width="16" height="16" border="0" />
	</td>
	<td class="{td_class}" width="40%">
	<a href="{www_dir}{index}/mediacatalogue/mediaview/{media_id}/?RefererURL=/mediacatalogue/browse/{main_category_id}/">{media_name}</a>
	</td>
	<td class="{td_class}" width="56%">
	<span class="small">{media_description}</span>
	</td>
	<!-- BEGIN multi_media_tpl -->
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="MediaArrayID[]" value="{media_id}">
	</td>
	<!-- END multi_media_tpl -->
	<!-- BEGIN single_media_tpl -->
	<td class="{td_class}" width="1%">
	<input type="radio" name="MediaID" value="{media_id}">
	</td>
	<!-- END single_media_tpl -->
	<!-- END detail_read_tpl -->

</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END media_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="AddMedia" value="{intl-add_media}">&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}">
</form>


