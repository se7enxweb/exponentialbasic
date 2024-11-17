<h1>{intl-{header_of_page}}</h1>

<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN error_item_tpl -->
<h2 class="error">{intl-error}</h2>
<hr noshade="noshade" size="4" />
<p class="error">{error_message}</p>
<!-- END error_item_tpl -->


<!-- BEGIN game_list_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th>Name:</th>
    <th>Description:</th>
    <th>{intl-valid}:</th>
<!--    <th>{intl-questions}:</th> -->
<!--    <th>{intl-players}:</th> -->
	<th>&nbsp;</th>
<!--	<th>&nbsp;</th>  -->
</tr>

<!-- BEGIN game_item_tpl -->
<tr class="{td_class}">
    <td><a href="{www_dir}{index}/quiz/game/view/{game_id}">{game_name}</a></td>
    <td>{game_description}&nbsp;</td>
    <td class="small">{game_start}<br />{game_stop}</td>
<!--    <td>{game_questions}&nbsp;</td> -->
<!--    <td>{game_players}&nbsp;</td> -->
        <td>
<!-- BEGIN score_link_tpl -->
    <a href="{www_dir}{index}/quiz/game/scores/{game_id}">{intl-scores}</a>
<!-- END score_link_tpl -->
        </td>
<!--	<td><a href="{www_dir}{index}/quiz/game/play/{game_id}">{intl-play}</a></td> -->
</tr>
<!-- END game_item_tpl -->
</table>
<!-- END game_list_item_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/quiz/game/list/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/list/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/quiz/game/list/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
