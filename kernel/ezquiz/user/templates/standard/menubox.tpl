<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-title}</td>
</tr>
<!-- BEGIN no_game_item_tpl -->
<tr>
	<tr>
		<td colspan="2" class="menusubhead">{intl-no_game}</td>
	</tr>
</tr>
<!-- END no_game_item_tpl -->
<!-- BEGIN current_game_item_tpl -->
<tr>
	<tr>
		<td colspan="2" class="menusubhead">{intl-current_game}:</td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/play/{game_id}/">{game_name}</a></td>
	</tr>
</tr>
<!-- END current_game_item_tpl -->
<!-- BEGIN next_game_item_tpl -->
<tr>
	<tr>
		<td colspan="2" class="menusubhead">{intl-next_game}:</td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/play/{game_id}/">{game_name}</a><br /><span class="menu">starts at {game_start_date}</span></td>
	</tr>
</tr>
<!-- END next_game_item_tpl -->
<!-- BEGIN quiz_menu_item_tpl -->
<tr>
	<tr>
		<td colspan="2" class="menusubhead">{intl-game_menu}:</td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/list/">{intl-all_games}</a></td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/future/">{intl-all_future_games}</a></td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/past/">{intl-all_previous_games}</a></td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/game/scores/">{intl-all_scores}</a></td>
	</tr>
</tr>
<!-- END quiz_menu_item_tpl -->
<!-- BEGIN my_quiz_item_tpl -->
<tr>
	<tr>
		<td colspan="2" class="menusubhead">{intl-my_quiz}:</td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/my/open/">{intl-my_unfinished_games}</a></td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/my/closed/">{intl-my_finished_games}</a></td>
	</tr>
	<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/quiz/my/scores/">{intl-my_scores}</a></td>
	</tr>
</tr>
<!-- END my_quiz_item_tpl -->

<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
