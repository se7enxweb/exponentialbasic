<form method="post" action="{www_dir}{index}/article/categoryedit/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}</p>
<input type="text" size="20" name="Name" value="{name_value}"/>


<!-- BEGIN value_tpl -->

<!-- END value_tpl -->

<p class="boxtext">{intl-description}</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/article/categoryedit/cancel/">
	<input type="hidden" name="CategoryID" value="{category_id}" />
	<input class="okbutton" type="submit" value="Avbryt" />
	</form>

	</td>
</tr>
</table>

