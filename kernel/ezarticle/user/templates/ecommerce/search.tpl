<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp;<a href="{www_dir}{index}/search/?SearchText={search_text}">{intl-results}</a></div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">

<h1 class="mainHeading">{intl-head_line}</h1>

<br />

<table class="list" width="90%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<h2>{intl-search_for} &quot;{search_text}&quot;</h2>
	</td>
	<td align="right" align="bottom">
	<div class="boxtext">({article_start}-{article_end}/{article_total})</div>
	</td>
</tr>
</table>

<!-- BEGIN article_list_tpl -->
<table class="list" width="90%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-article}:</th>
	<th>
	<div align="right">
	{intl-publishing_date}:
	</div>
	</th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">
	{article_name}
	</a>
	</td>
	<td align="right" class="{td_class}">
	<span class="small">{article_published}</span>
	</td>
</tr>
<!-- END article_item_tpl -->

</table>
<!-- END article_list_tpl -->

<table width="90%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
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
	</td>
    </tr>
</table>
</div>
