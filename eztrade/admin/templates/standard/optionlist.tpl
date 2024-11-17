<form action="{www_dir}{index}/trade/productedit/optionedit/new/{product_id}/" method="post">

<h1>{intl-optionlist}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-option}: </th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN option_tpl -->
<tr>
	<td class="{td_class}">
	{option_name}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/productedit/optionedit/edit/{option_id}/{product_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezto{option_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezto{option_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="DeleteOptionID[]" value="{option_id}">
	</td>
</tr>
<!-- END option_tpl -->

</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" value="{intl-newoption}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="stdbutton" type="submit" name="DeleteOption" value="{intl-delete_selected}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

</form>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<form action="{www_dir}{index}/trade/productedit/edit/{product_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-back}" />
	</form>
	</td>
</tr>
</table>
