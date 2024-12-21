<form method="post" action="{www_dir}{index}/quiz/game/edit/{game_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN error_name_tpl -->
<p class="error">{intl-error_this_game_needs_a_name}.</p>
<!-- END error_name_tpl -->

<!-- BEGIN error_question_tpl -->
<p class="error">{intl-error_this_game_needs_a_question}.</p>
<!-- END error_question_tpl -->

<!-- BEGIN error_no_date_tpl -->
<p class="error">{intl-error_this_game_needs_dates}.</p>
<!-- END error_no_date_tpl -->

<!-- BEGIN error_date_tpl -->
<p class="error">{intl-this_game_crash_with} {error_game_name} - ({error_game_start_day}-{error_game_start_month}-{error_game_start_year} / {error_game_stop_day}-{error_game_stop_month}-{error_game_stop_year})</p>
<!-- END error_date_tpl -->

<!-- BEGIN error_stop_date_tpl -->
<p class="error">{intl-this_game_crash_with_stop} {error_game_name} - ({error_game_start_day}-{error_game_start_month}-{error_game_start_year} / {error_game_stop_day}-{error_game_stop_month}-{error_game_stop_year})</p>
<!-- END error_stop_date_tpl -->

<!-- BEGIN error_start_date_tpl -->
<p class="error">{intl-this_game_crash_with_start} {error_game_name} - ({error_game_start_day}-{error_game_start_month}-{error_game_start_year} / {error_game_stop_day}-{error_game_stop_month}-{error_game_stop_year})</p>
<!-- END error_start_date_tpl -->

<!-- BEGIN error_embracing_period_tpl -->
<p class="error">{intl-this_game_embraces_period} {error_game_name} - ({error_game_start_day}-{error_game_start_month}-{error_game_start_year} / {error_game_stop_day}-{error_game_stop_month}-{error_game_stop_year})</p>
<!-- END error_embracing_period_tpl -->

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{game_name}" />
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%" colspan="3">
	<p class="boxtext">{intl-start_date}:</p>
	</td>
	<td width="50%" colspan="3">
	<p class="boxtext">{intl-stop_date}:</p>
	</td>
</tr>
<tr>
	<td width="10%">
	<span class="small">{intl-day}:</span>
	</td>
	<td width="10%">
	<span class="small">{intl-month}:</span>
	</td>
	<td width="30%">
	<span class="small">{intl-year}:</span>
	</td>
	<td width="10%">
	<span class="small">{intl-day}:</span>
	</td>
	<td width="10%">
	<span class="small">{intl-month}:</span>
	</td>
	<td width="30%">
	<span class="small">{intl-year}:</span>
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="2" name="StartDay" value="{start_day}" />&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StartMonth" value="{start_month}" />&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StartYear" value="{start_year}" />
	</td>
	<td>
	<input type="text" size="2" name="StopDay" value="{stop_day}" />&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StopMonth" value="{stop_month}" />&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StopYear" value="{stop_year}" />
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<textarea class="box" name="Description" wrap="soft" cols="40" rows="10">{game_description}</textarea>

<br />

<!-- BEGIN question_list_tpl -->
<br />
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
         <th>{intl-questions}:</th>
</tr>
<!-- BEGIN question_item_tpl -->
<tr>
     <td class="{td_class}">
	 {question_name}
	 </td>
	 <td class="{td_class}" width="1%">
	 <a href="{www_dir}{index}/quiz/game/questionedit/{question_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_wapImage('ezquiz{question_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezquiz{question_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>&nbsp;
	 </td>
	<td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="DeleteQuestionArray[]" value="{question_id}">
	</td>
</tr>
<!-- END question_item_tpl -->

</table>
<!-- END question_list_tpl -->


<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewQuestion" value="{intl-new_question}" />
<input class="stdbutton" type="submit" name="DeleteQuestions" value="{intl-delete_questions}" />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
