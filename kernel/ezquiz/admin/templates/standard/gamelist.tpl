<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-games}</h1>
     </td>
</tr>
<tr>
	<td>
	<p class="boxtext">({game_start}-{game_end}/{game_total})</p>	
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/quiz/game/edit/" method="post">
<!-- BEGIN game_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
         <th>{intl-name}:</th>
         <th>{intl-description}:</th>
</tr>
<!-- BEGIN game_item_tpl -->
<tr class="{td_class}">
	<td width="47%">
	{game_name}
	</td>

	<td width="50%">
	{game_description}
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/quiz/game/edit/{game_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezquiz{game_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezquiz{game_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>

	<td width="1%">
	<input type="checkbox" name="GameArrayID[]" value="{game_id}">
	</td>
</tr>
<!-- END game_item_tpl -->
</table>
<!-- END game_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="New" value="{intl-new_game}" />&nbsp;
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_games}" />



</form>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/quiz/game/list/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/list/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/list/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
