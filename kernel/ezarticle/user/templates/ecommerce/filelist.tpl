<form action="{www_dir}{index}/article/articleedit/fileedit/new/{article_id}/" method="post">

<h1>{intl-files}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN no_files_tpl -->
{intl-no_files}
<!-- END no_files_tpl -->

<!-- BEGIN file_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-file_id}:</th>
	<th>{intl-file_name}:</th>
	<th>{intl-file_description}:</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN file_tpl -->
<tr>
	<td width="1%" class="{td_class}">
	{file_number}
	</td>
	<td width="40%" class="{td_class}">
	{file_name}
	</td>
	<td width="57%" class="{td_class}">
	{file_description}
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="FileArrayID[]" value="{file_id}">
	</td>
</tr>
<!-- END file_tpl -->

</table>
<!-- END file_list_tpl -->

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="stdbutton" type="submit" name="NewFile" value="{intl-file_upload}" />
	</td>
	<td>&nbsp;</td>
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
