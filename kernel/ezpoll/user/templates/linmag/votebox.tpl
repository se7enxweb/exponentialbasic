<form method="post" action="{www_dir}{index}/poll/userlogin/vote/{poll_id}/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-headline}</td>
</tr>
<tr>
	<td colspan="2" class="menutext">
	{head_line}
	</td>
</tr>
<!-- BEGIN vote_item_tpl -->
<tr>
	<td width="1%">
	<input type="radio" value="{choice_id}" name="ChoiceID">
	</td>
	<td class="menutext" width="99%">
	{choice_name}
	</td>
</tr>
<!-- END vote_item_tpl -->

<!-- BEGIN novote_item_tpl -->
<tr>
	<td colspan="2" class="menutext">
	{novote_item}
	</td>
</tr>
<!-- END novote_item_tpl -->

<tr>
	<td colspan="2">
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input class="stdbutton" type="submit" value="{intl-vote}" />
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/poll/result/{poll_id}">{intl-result}</a></td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/sitedesign/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/poll/polls">{intl-polls}</a></td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>

</form>

