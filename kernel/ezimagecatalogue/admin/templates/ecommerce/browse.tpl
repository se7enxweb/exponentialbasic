
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-add_images_for} {name}</h1>
	</td>
	
	</td>
	<td align="right">
	<form action="{www_dir}{index}/imagecatalogue/browsesearch/" method="post">
	<input class="searchbox" type="text" name="SearchText" size="10" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>


<form method="post" action="{www_dir}{index}{action_url}" enctype="multipart/form-data">

<input type="hidden" name="CategoryID" value="{main_category_id}">


<!-- BEGIN current_category_tpl -->

<!-- END current_category_tpl -->

<hr noshade="noshade" size="4" />

<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="">
<a class="path" href="{www_dir}{index}/imagecatalogue/browse/0/">{intl-image_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="">
<a class="path" href="{www_dir}{index}/imagecatalogue/browse/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">

<!-- BEGIN category_tpl -->
<tr>
        <!-- BEGIN category_read_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/imagecatalogue/browse/{category_id}/"><img src="{www_dir}/images/folder.gif" alt="" width="16" height="16" border="0" /></a>
	</td>
	<td class="{td_class}" width="38%">
	<a href="{www_dir}{index}/imagecatalogue/browse/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}" width="59%">
	{category_description}
	</td>
        <!-- END category_read_tpl -->
</tr>
<!-- END category_tpl -->

</table>
<!-- END category_list_tpl -->

<!-- BEGIN image_list_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/browse/{main_category_id}/"><img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a>
	</td>
	<td class="{td_class}" width="77%">
	{image_caption}
	</td>
	<td class="{td_class}" width="20%">
	{image_size}&nbsp;{image_unit}
	</td>
	<!-- BEGIN multi_images_tpl -->
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}">
	</td>
	<!-- END multi_images_tpl -->
	<!-- BEGIN single_images_tpl -->
	<td class="{td_class}" width="1%">
	<input type="radio" name="ImageID" value="{image_id}">
	</td>
	<!-- END single_images_tpl -->
	<!-- END detail_read_tpl -->

</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END image_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="AddImages" value="{intl-add_images}">&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}">
</form>


