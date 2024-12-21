<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>


<h1>{intl-head_line}:</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN search_item_tpl -->
<form action="{www_dir}{index}/article/extendedsearch/" method="post">
<table width="100%" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-category}:</p>
	<select name="Category">
	<option value="">{intl-all}</option>
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {category_selected}>{category_level}{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-text}:</p>
	<input type="text" name="SearchText" size="12" value="{search_text}" />
	<input class="stdbutton" type="submit" name="Search" value="{intl-search}" />
	</td>
</tr>
</table>

</form>
<!-- END search_item_tpl -->

<!-- BEGIN article_list_tpl -->
<table width="100%">
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<a href="{www_dir}{index}/article/articleview/{article_id}/{article_page}/{article_category}">{article_name}</a>
	</td>
</tr>
<!-- END article_item_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/extendedsearch/{search_url_text}/{category}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/article/extendedsearch/{search_url_text}/{category}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/article/extendedsearch/{search_url_text}/{category}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

<!-- END article_list_tpl -->
