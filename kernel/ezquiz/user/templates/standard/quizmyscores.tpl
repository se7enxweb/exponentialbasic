<!-- BEGIN logged_in_user_item_tpl -->
<h1>{intl-my_score_list}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td>{intl-logged_in_as}</td>
    <td>{user_login}</td>
</tr>
<tr>
    <td>{intl-name}</td>
    <td>{user_first} {user_last}</td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<!-- END logged_in_user_item_tpl -->



<!-- BEGIN error_item_tpl -->
<h2 class="error">{intl-error}</h2>
<hr noshade="noshade" size="4" />
<p class="error">{error_message}</p>
<!-- END error_item_tpl -->

<!-- BEGIN score_list_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th width="1%">{intl-game}</td>
	<th>{intl-score}</td>
	<th>{intl-questions}</td>
	<th>{intl-players}</td>
</tr>

<!-- BEGIN score_item_tpl -->
<tr class="{td_class}">
	<td>{game_name} <!-- {game_id} --></td>
	<td>{game_score}</td>
	<td>{game_questions}</td>
	<td>{game_players}</td>
</tr>
<!-- END score_item_tpl -->

</table>
<!-- END score_list_item_tpl -->

<!-- BEGIN no_scores_item_tpl -->
<h2>{intl-you_have_no_scores}</h2>
{intl-you_must_play}. 
<!-- BEGIN game_item_tpl -->
<!-- {intl-click_on_game_in_menu} --> {intl-play_the_current}: <a href="{www_dir}{index}/quiz/game/play/{game_id}/">{game_name}</a>
<!-- END game_item_tpl -->
<!-- END no_scores_item_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/quiz/game/score/{user_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/score/{user_id}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/score/{user_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
