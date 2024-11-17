<h1>{intl-head_line}:</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN search_item_tpl -->
<form action="{www_dir}{index}/article/extendedsearch/" method="post">
<table width="100%" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-category}:</p>
	<select name="Category">
	<option value="-1">{intl-all}</option>
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}">{category_level}{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-text}:</p>
	<input type="text" name="SearchText" size="12" />
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</td>
</tr>
</table>

</form>	
<!-- END search_item_tpl -->
