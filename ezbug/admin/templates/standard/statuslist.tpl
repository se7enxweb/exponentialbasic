<form action="{www_dir}{index}/bug/status/list/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN status_item_tpl -->
<tr>
	<td class="{td_class}">
	  <input type="hidden" name="StatusID[]" value="{status_id}" />
	  <input type="text" name="StatusName[]" value="{status_name}" />
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="StatusArrayID[]" value="{index_nr}">
	</td>
</tr>
<!-- END status_item_tpl -->
</table>

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="AddStatus" value="{intl-newstatus}"></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="DeleteStatus" value="{intl-delete_status}"></td>
</tr>
</table>

<hr noshade size="4"/>

<input class="okbutton" type="submit" name="Ok" value="{intl-ok}">

</form>
