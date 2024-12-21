
<!-- BEGIN mail_preview_tpl -->
<!-- END mail_preview_tpl -->

<h1>{intl-form_preview}</h1>

<hr noshade="noshade" size="4" />

<h2>{form_name}</h2>

{error}

<form action="{www_dir}{index}/form/form/{action_value}/{form_id}" method="post">
{form}

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Test" value="{intl-test}" />

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
</tr>
</table>

</form>
