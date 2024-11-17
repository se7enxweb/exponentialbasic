<form method="post" action="{www_dir}{index}/poll/userlogin/vote/{poll_id}/">

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	<img src="{www_dir}/sitedesign/{sitedesign}/images/poll.gif" width="122" height="20"><br />
	</td>
</tr>
<tr>
	<td>
	<div class="rightmenutext">
	{head_line}
	</div>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN vote_item_tpl -->
<tr>
	<td width="1%" align="right">
	<div class="menuspacer">
	<input type="radio" value="{choice_id}" name="ChoiceID">
	</div>
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
	<div class="rightmenu">
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input class="stdbutton" type="submit" value="{intl-vote}" />
	</div>
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2">
	<div class="rightmenu">
	<a href="{www_dir}{index}/poll/result/{poll_id}">{intl-result}</a>
	</div>
	</td>
</tr>
<tr>
	<td colspan="2">
	<div class="rightmenu">
	<a href="{www_dir}{index}/poll/polls">{intl-polls}</a>
	</div>
	</td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>

</form>

