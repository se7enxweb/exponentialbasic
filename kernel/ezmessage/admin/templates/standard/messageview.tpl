<form action="{www_dir}{index}/message/list/" method="post">

<h1>{intl-message_from} {from_user_first_name} {from_user_last_name} </h1>

<hr size="4" noshade="noshade" />
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-message_subject}:
	</th>

	<th class="right">
	{intl-message_date}:
	</th>
</tr>
<tr>
	<td valign="top">
	{message_subject}
	</td>

	<td valign="top" align="right">
	{message_date}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2">
	{intl-message_message}:
	</th>

</tr>
<tr>
	<td valign="top" colspan="2">
	{message_message}
	</td>
</tr>
</tr>
</table>
<br />

<hr size="4" noshade="noshade" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="1%">
       <input class="okbutton" type="submit" name="Back" value="{intl-back}" />&nbsp;
       </form>
</td>
<td width="99%">
       <form action="{www_dir}{index}/message/edit/" method="post">
       <input type="hidden" name="Subject" value="{message_subject}" />
       <input type="hidden" name="Message" value="{message_message}" />
       <input type="hidden" name="FromUserID" value="{message_user_id}" />
       <input class="okbutton" type="submit" name="Reply" value="{intl-reply}" />
       </form>
</td>
</tr>
</table>

