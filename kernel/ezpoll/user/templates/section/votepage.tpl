<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<form method="post" action="{www_dir}{index}/poll/userlogin/vote/{poll_id}/">

<h1>{head_line}</h1>

<p>{description}</p>

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
<!-- BEGIN no_items_tpl -->
<p class="error">{intl-no_items_found}</p>
<!-- END no_items_tpl -->

</table>
<br />

<!-- BEGIN vote_buttons_tpl -->
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input type="hidden" name="PollID" value="{poll_id}" />
	<input class="okbutton" type="submit" value="{intl-vote}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="{www_dir}{index}/poll/result/{poll_id}">
	<input class="okbutton" type="submit" value="{intl-result}">
	</form>
	</td>
</tr>
</table>
<!-- END vote_buttons_tpl -->
