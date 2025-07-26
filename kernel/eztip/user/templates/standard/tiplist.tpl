<table class="list" width="100%" cellspacing="0" cellpadding="2" border="0">
  <!-- BEGIN tip_title_tpl -->
  <tr>
	<td align="center">
	<h3>{title_text}</h3>
	</td>
</tr>
  <!-- END tip_title_tpl -->
  <!-- BEGIN tip_image_tpl -->
  <tr>
	<td align="center">{link_start}<img src="/{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{tip_name}" />{link_end}</td>
</tr>
  <!-- END tip_image_tpl -->
  <!-- BEGIN tip_html_tpl -->
  <tr>
	<td align="center" valign="top">{html}<br>
	</td>
</tr>
  <!-- END tip_html_tpl -->
  <!-- BEGIN tip_link_tpl -->
  <tr>
	<td align="left">
	<a target="{tip_target}" href="{www_dir}{index}/tip/goto/{tip_id}/" class="menu">Read more...</a>
	</td>
</tr>
  <!-- END tip_link_tpl -->
</table>
