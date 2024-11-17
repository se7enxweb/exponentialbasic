<form method="post" action="{www_dir}{index}/quiz/game/questionedit/{question_id}/">

<h1>{intl-headline}</h1>

<!-- BEGIN error_list_tpl -->
<hr noshade="noshade" size="4" />
<br />
<h2 class="error">{intl-error_headline}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_message}</div>
<!-- END error_item_tpl -->

<!-- END error_list_tpl -->

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input class="box" type="text" size="40" name="Name" value="{question_name}" />
	</td>
</table>

<br />

<!-- BEGIN alternative_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
         <th>{intl-alternatives}:</th>
         <th>{intl-is_correct}:</th>
</tr>
<!-- BEGIN alternative_item_tpl -->
<tr>
	 <td>
	 <input type="text" name="AlternativeArrayName[]" value="{alternative_name}" />
	 </td>
	 <td width="50%">
	 <input type="radio" name="IsCorrect" value="{alternative_id}" {is_selected} />
	 <input type="hidden" name="AlternativeArrayID[]" value="{alternative_id}" />
	 </td>
         <td width="1%" align="center">
	 <input type="checkbox" name="AlternativeDeleteArray[]" value="{alternative_id}">
	 </td>
</tr>
<!-- END alternative_item_tpl -->
</table>
<!-- END alternative_list_tpl -->

<br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewAlternative" value="{intl-new_alternative}" />&nbsp;<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_alternatives}" />&nbsp;

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
