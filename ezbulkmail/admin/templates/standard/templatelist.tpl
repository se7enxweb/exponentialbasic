<h1>{intl-template_list}</h1>

<hr noshade="noshade" size="4">
<form action="{www_dir}{index}/bulkmail/templatelist" method="post">
<!-- BEGIN template_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-template_name}:</th>
	<th width="78%">{intl-template_description}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN template_item_tpl -->
<tr>
	<td class="{td_class}">
	{template_name}
	</td>
	<td class="{td_class}">
	{template_description}
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/bulkmail/templateedit/{template_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{template_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{template_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td class="{td_class}"><input type="checkbox" name="TemplateArrayID[]" value="{template_id}" /></td>
</tr>
<!-- END template_item_tpl -->
</table>
<!-- END template_tpl -->

<hr noshade="noshade" size="4">

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="stdbutton" name="New" value="{intl-new}" /></td>
  <td>&nbsp</td>
  <td><input type="submit" class="stdbutton" name="Delete" value="{intl-delete_selected}" /></td>
</tr>
</table>
</form>
