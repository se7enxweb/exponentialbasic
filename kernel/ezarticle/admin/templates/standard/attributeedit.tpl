<form action="{www_dir}{index}/article/articleedit/attributeedit/{action_value}/{this_type_id}" method="post">

<h1>{intl-attributes}: {article_name}</h1>

<input type="hidden" name="ArticleID" value="{article_id}" />

<br/>

<hr noshade="noshade" size="4" />
<!-- BEGIN no_attributes_item_tpl -->
{intl-no_attributes_exist}
<!-- END no_attributes_item_tpl -->

<!-- BEGIN no_selected_type_item_tpl -->
{intl-no_type_selected}
<!-- END no_selected_type_item_tpl -->

<!-- BEGIN attribute_list_tpl -->
<h2 class="boxtext">{type_name}:</h2>
<!-- {type_id} -->

<!-- BEGIN attribute_item_tpl -->
<p class="boxtext">{attribute_name}:</p>
<textarea class="box" name="AttributeValue[]" cols="40" rows="5" wrap="soft">{attribute_value}</textarea>
<input type="hidden" name="AttributeID[]" value="{attribute_id}">
<br /><br />
<!-- END attribute_item_tpl -->
<!-- END attribute_list_tpl -->
<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

</form>
