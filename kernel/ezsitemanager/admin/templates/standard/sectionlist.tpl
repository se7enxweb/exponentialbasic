<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-sections}</h1>
     </td>
</tr>
<tr>
	<td>
	<p class="boxtext">({section_start}-{section_end}/{section_total})</p>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/sitemanager/section/edit/" method="post">
<!-- BEGIN section_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
         <th>{intl-id}</th>
         <th>{intl-name}</th>
         <th>{intl-description}</th>
</tr>
<!-- BEGIN section_item_tpl -->
<tr class="{td_class}">
	<td width="5%">
	{section_id}
	</td>

	<td width="42%">
	{section_name}&nbsp;
	</td>

	<td width="50%">
	{section_description}&nbsp;
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/sitemanager/section/edit/{section_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezsitemanager{section_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezsitemanager{section_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<input type="checkbox" name="SectionArrayID[]" value="{section_id}">
	</td>
</tr>
<!-- END section_item_tpl -->
</table>
<!-- END section_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="New" value="{intl-new_section}" />&nbsp;
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_sections}" />



</form>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/sitemanager/section/list/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/sitemanager/section/list/parent/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/sitemanager/section/list/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
