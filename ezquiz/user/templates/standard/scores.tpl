<h1>{intl-high_score_list}: {game_name}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN error_item_tpl -->
<h2 class="error">{intl-error}</h2>
<p class="error">{error_message}</p>
<!-- END error_item_tpl -->

<!-- BEGIN score_list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="1%">{intl-position}:</td>
	<th>{intl-player}:</td>
	<th class="right">{intl-score}:</td>
</tr>

<!-- BEGIN score_item_tpl -->
<tr class="{td_class}">
	<td width="1%">{score_position}</td>
	<td>{player}<!-- {player_id} --></td>
	<td align="right">{score}</td>
</tr>
<!-- END score_item_tpl -->

</table>
<!-- END score_list_item_tpl -->

<!-- BEGIN no_scores_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-no_players}.
<!-- END no_scores_item_tpl -->

<!-- BEGIN not_closed_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-not_closed}. {intl-closes_at} {game_stop}.
<!-- END not_closed_item_tpl -->

<!-- BEGIN future_item_tpl -->
<h2>{intl-no_scores}</h2>
{intl-in_the_future}. {intl-opens_at} {game_start}.
<!-- END future_item_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/quiz/game/score/{game_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/score/{game_id}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/score/{game_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
