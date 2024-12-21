<h1>{intl-head_line}</h1>

<br />

<!-- BEGIN result_list_tpl -->

<h2>{poll_name}</h2>

<p>{description}</p>

<table width="100%" cellspacing="0" cellpadding="4" border="0">

<!-- BEGIN result_item_tpl -->
<tr>
	<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="50%">
		<!-- {choice_number}: --> <b>{choice_name}</b> {choice_percent}%
		</td>
		<td align="right">
		{choice_vote} {intl-votes}
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="{choice_percent}%" bgcolor="#ffee00">
		&nbsp;
		</td>
		<td width="{choice_inverted_percent}%"  bgcolor="#eeeeee">
		&nbsp;
		</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
<tr>
<!-- END result_item_tpl -->
<tr>
	<td>
	</td>
</tr>
<tr>
	<td>
	{intl-total_votes}: {total_votes}
	</td>
</tr>
</table>

<br />
<form method="post" action="{www_dir}{index}/poll/polls">

<input class="stdbutton" type="submit" value="{intl-more}">
</form>

<!-- END result_list_tpl -->
