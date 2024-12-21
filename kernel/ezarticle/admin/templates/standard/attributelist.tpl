<form action="{www_dir}{index}/article/articleedit/attributelist/{article_id}/" method="post">

<h1>{intl-types}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_types_item_tpl -->
{intl-no_types_for_article}
<!-- END no_types_item_tpl -->

<!-- BEGIN type_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-type_name}:</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr>
	<td width="98%" class="{td_class}">
	<!-- {type_id} -->{type_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/article/articleedit/attributeedit/edit/{type_id}?ArticleID={article_id}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{type_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{type_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="TypeArrayID[]" value="{type_id}">
	</td>
</tr>
<!-- END type_item_tpl -->

</table>
<!-- END type_list_tpl -->

<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
<!-- BEGIN no_types_select_item_tpl -->
    <td valign="top">
{intl-no_types}
    </td>
<!-- END no_types_select_item_tpl -->
<!-- BEGIN type_list_select_tpl -->
    <td valign="top">
        <select name="TypeID">
        <option value="-1">{intl-no_selected_type}</option>
<!-- BEGIN type_item_select_tpl -->
        <option value="{type_id}" {selected}>{type_name}</option>

<!-- END type_item_select_tpl -->
        </select>
    </td>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewType" value="{intl-add_type}" />
	</td>
<!-- END type_list_select_tpl -->
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

</form>

<form action="{www_dir}{index}/article/articleedit/edit/{article_id}/" method="post">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" value="{intl-cancel}" />
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>

</form>
