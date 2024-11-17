<form action="{www_dir}{index}/bug/category/list/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	  <input type="hidden" name="CategoryID[]" value="{category_id}" />
	  <input type="text" name="CategoryName[]" value="{category_name}" />
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="CategoryArrayID[]" value="{index_nr}" />
	</td>
</tr>
<!-- END category_item_tpl -->
</table>

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="AddCategory" value="{intl-newcategory}"></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="DeleteCategories" value="{intl-delete_categories}"></td>
</tr>
</table>

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" name="Ok" value="{intl-ok}"></td>
</tr>
</table>



</form>
