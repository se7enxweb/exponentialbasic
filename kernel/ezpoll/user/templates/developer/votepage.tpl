<form method="post" action="{www_dir}{index}/poll/userlogin/vote/{poll_id}/">
<h1>{head_line}</h1>

<hr noshade="noshade" size="4">

<br />
<table cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN vote_item_tpl -->
<tr>
	<td width="1%">
	<input type="radio" value="{choice_id}" name="ChoiceID">
	</td>
	<td width="99%">
	{choice_name}
	</td>
</tr>
<!-- END vote_item_tpl -->

</table>
<br />
<hr noshade="noshade" size="4">
<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td valign="top">
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input class="okbutton" type="submit" value="{intl-vote}" />
	</td>
	<td>&nbsp;</td>
	</form>

	<td>
	<form action="{www_dir}{index}/poll/result/{poll_id}">
	<input class="okbutton" type="submit" value="{intl-result}">
	</form>
	</td>
</tr>

</table>
