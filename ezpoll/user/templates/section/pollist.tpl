<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<h1>{intl-head_line}</h1>

<br />
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>
	{intl-poll}:
	</th>

	<th>
	{intl-description}:
	</th>
</tr>
<!-- BEGIN poll_item_tpl -->
<tr class="{td_class}">
	<td> 
	<a href="{www_dir}{index}/poll/{action}/{poll_id}/">{poll_name}</a>
	</td>
	<td>
	{poll_description}
	</td>
</tr>
<!-- END poll_item_tpl -->
</table>
