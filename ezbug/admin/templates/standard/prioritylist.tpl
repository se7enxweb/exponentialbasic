<form action="{www_dir}{index}/bug/priority/list/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN priority_item_tpl -->
<tr>
	<td class="{td_class}">
	  <input type="hidden" name="PriorityID[]" value="{priority_id}" />
	  <input type="text" name="PriorityName[]" value="{priority_name}" />
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="PriorityArrayID[]" value="{index_nr}">
	</td>
</tr>
<!-- END priority_item_tpl -->
</table>

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="AddPriority" value="{intl-new_priority}"></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="DeletePriorities" value="{intl-delete_priorities}"></td>
</tr>
</table>

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" name="Ok" value="{intl-ok}"></td>
</tr>
</table>

</form>
