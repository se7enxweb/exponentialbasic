<form action="{www_dir}{index}/user/authorlist" method="post">

<h1>{intl-author_edit}</h1>

<hr size="4" noshade="noshade" />

<!-- BEGIN author_list_tpl -->

<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>
	{intl-author_name}:
	</th>
	<th>
	{intl-author_email}:
	</th>
</tr>
<!-- BEGIN author_item_tpl -->
<tr>
	<td class="{td_class}">
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" class="halfbox" size="20" name="Name[]" value="{author_name}" />
	</td>
	<td class="{td_class}">
	<input type="text" class="halfbox" size="20" name="EMail[]" value="{author_email}" />
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END author_item_tpl -->
</tr>
</table>
<!-- END author_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewAuthor" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteAuthor" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
