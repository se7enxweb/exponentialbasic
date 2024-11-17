<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-head_line} - ({article_start}-{article_end}/{article_total}) - {search_text}</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/article/search/" enctype="multipart/form-data">

<!--
<p>
{current_category_description}
</p>
-->

<hr noshade="noshade" size="4" />

<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-article}:</th>
	<th>{intl-published}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{www_dir}/design/admin/images/document.gif" height="16" width="16" border="0" alt="" />&nbsp;
	<a href="{www_dir}{index}/article/articlepreview/{article_id}/">
	{article_name}
	</a>
	</td>
	<td class="{td_class}">
	<!-- BEGIN article_is_published_tpl -->
	{intl-is_published}
	<!-- END article_is_published_tpl -->
	<!-- BEGIN article_not_published_tpl -->
	{intl-not_published}
	<!-- END article_not_published_tpl -->
	&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/articleedit/edit/{article_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{article_id}-red','','{www_dir}/ezarticle/design/admin/images/redigerminimrk.gif',1)"><img name="ezaa{article_id}-red" border="0" src="{www_dir}/ezarticle/design/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
          <input type="checkbox" name="ArticleArrayID[]" value="{article_id}" />
	</td>
</tr>
<!-- END article_item_tpl -->

</table>
<!-- END article_list_tpl -->

<hr noshade="noshade" size="4">

<!-- BEGIN article_delete_tpl -->
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete}" />
<!-- END article_delete_tpl -->
<input type="hidden" name="SearchText" value="{search_text}" />
<input type="hidden" name="StartStamp" value="{url_start_stamp}" />
<input type="hidden" name="StopStamp" value="{url_stop_stamp}" />
<input type="hidden" name="SeperatedCategoryArray" value="{url_category_array}" />
<input type="hidden" name="ContentsWriterID" value="{url_contentswriter_id}" />
<input type="hidden" name="PhotographerID" value="{url_photographer_id}" />
</form>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/article/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/article/search/parent/{url_text}/{url_start_stamp}/{url_stop_stamp}/{url_category_array}/{url_contentswriter_id}/{url_photographer_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
