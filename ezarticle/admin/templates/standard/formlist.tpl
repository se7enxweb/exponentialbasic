<form action="{www_dir}{index}/article/articleedit/formlist/{article_id}/" method="post">

<h1>{intl-form_list}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_forms_item_tpl -->
<div>{intl-no_forms_exist}</div>
<!-- END no_forms_item_tpl -->

<!-- BEGIN form_list_tpl -->
<select name="selectedFormID">
<option value="0">{intl-no_form_selected}</option>
<!-- BEGIN form_item_tpl -->
<option value="{form_id}" {selected}>{form_name}</option>
<!-- END form_item_tpl -->
</select>
<!-- END form_list_tpl -->

<br/>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
</tr>
</table>

</form>
