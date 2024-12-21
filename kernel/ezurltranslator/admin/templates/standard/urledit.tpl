<form action="{www_dir}{index}/urltranslator/urledit" method="post">

<h1>{intl-url_edit}</h1>

<hr size="4" noshade="noshade" />
<br />
<!-- BEGIN url_list_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-source_url}:
	</th>
	<th>
	{intl-dest_url}:
	</th>
</tr>
<!-- BEGIN url_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" size="30" name="SourceURL[]" value="{source_url}" />
	</td>
	<td>
	<input type="text" size="30" name="DestURL[]" value="{dest_url}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END url_item_tpl -->
</tr>
</table>
<br />
<!-- END url_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewURL" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteURL" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
