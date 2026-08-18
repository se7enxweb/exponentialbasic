<!-- Menubox: Start --->

<div class="menubox">
<div class="menubox-header">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%">
	<!-- Ikon: Start -->
	<a href="{www_dir}{index}{request_uri}"><img src="{www_dir}{module_icon}" width="32" height="32" border="0" align="absmiddle" alt="{intl-module_name}" /></a>
	<!-- Ikon: Slutt -->		
	</td>
	<td width="96%" style="padding-left: 6px;">
	<span class="modulename">		
	{intl-module_name}
	</span>
	</td>
	<td width="1%">
	<a href="{www_dir}{index}{move_down_uri}"><img src="{www_dir}/design/admin/images/{site_style}/move-down.gif" width="12" height="12" border="0" alt="" /></a>
	</td>
	<td width="1%" style="padding-left: 4px;">
	<a href="{www_dir}{index}{move_up_uri}"><img src="{www_dir}/design/admin/images/{site_style}/move-up.gif" width="12" height="12" border="0" alt="" /></a>
	</td>	
</tr>
</table>
</div>
<div class="menubox-content">
<table width="100%" cellpadding="1" cellspacing="0" border="0">

<!-- BEGIN menu_item_tpl -->
<!-- BEGIN menu_item_link_tpl -->
<tr>
<td class="menu"><a class="menu" href="{www_dir}{index}{target_url}">{name}</a></td>
</tr>
<!-- END menu_item_link_tpl -->
<!-- BEGIN menu_item_break_tpl -->
<tr>
	<td>
	<br />
	</td>
</tr>
<!-- END menu_item_break_tpl -->
<!-- END menu_item_tpl -->

</table>
</div>
</div>

<!-- Menubox: End -->
