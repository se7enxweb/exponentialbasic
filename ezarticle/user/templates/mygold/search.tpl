<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<td>
	<h1>{intl-head_line} - ({article_start}-{article_end}/{article_total})</h1>
	</td>
	<td align="right">
	<form action="{www_dir}{index}/article/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="okbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="1" />

<h2>&quot;{search_text}&quot;</h2>

<!-- BEGIN article_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Article:</th>
	<th>
	<div align="right">
	{intl-publishing_date}:
	</div>
	</th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/articleview/{article_id}/">
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

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/article/search/move/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/article/search/move/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/article/search/move/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>


