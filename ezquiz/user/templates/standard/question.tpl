<h1>{game_name}</h1>

<hr noshade="noshade" size="4" />
<!-- BEGIN error_item_tpl -->
<h2 class="error">{intl-error}</h2>
<p class="error">{error_message}</p>
<!-- END error_item_tpl -->
<!-- BEGIN high_score_item_tpl -->
<p>{players} {intl-earlier_played}.</p>
<p>{intl-high_score_item_1} {high_score} {intl-high_score_item_2} {scorer}<!-- {scorer_id} --></p>
<!-- END high_score_item_tpl -->
<!-- BEGIN your_score_item_tpl -->
<p>{intl-your_score_item_3}.</p>
<p>{intl-your_score_item_1} {your_name} {intl-your_score_item_2} {your_score}.<!-- {your_id} --></p>
<!-- END your_score_item_tpl -->

<!-- BEGIN start_item_tpl -->
<h2>{intl-instructions}</h2>
<p>{intl-several_alternatives}. {intl-select_your_choice}.</p>
<p>{intl-no_turning_back}. {intl-no_return}.</p>
<p>{intl-your_score}.</p>
<p>{intl-answer_before_end_date}.</p>
<p>{intl-save_and_return}. {intl-also_saved_games}.</p>
<img src="{www_dir}/design/base/images/icons/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/quiz/game/play/{game_id}/1">{intl-start_game}</a><br />
<img src="{www_dir}/design/base/images/icons/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/quiz/game/scores/{game_id}">{intl-view_high_score}</a><br />
<!-- END start_item_tpl -->

<!-- BEGIN question_item_tpl -->
<h2>Question {placement} of {questions}: <br />{question_name}</h2>

<form method="post" action="{www_dir}{index}/quiz/game/play/{game_id}/{next_question_num}/">
<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN alternative_item_tpl -->
<tr>
	<td width="1%"><input type="radio" value="{alternative_id}" name="AlternativeID"></td>
	<td class="menutext" width="99%">{alternative_name}</td>
</tr>
<!-- END alternative_item_tpl -->

<tr>
        <td>
        <input type="hidden" name="QuizID" value="{game_id}" />
        <input type="hidden" name="QuestionID" value="{question_id}" />
        <input type="hidden" name="UserID" value="{user_id}" />
        <input type="hidden" name="Placement" value="{placement}" />
        <input class="stdbutton" name="NextButton" type="submit" value="{intl-next}" />
        </td>
        <td>
        <input class="stdbutton" name="SaveButton" type="submit" value="{intl-save}" />
        </td>
</tr>
</table>
</form>
<!-- END question_item_tpl -->
