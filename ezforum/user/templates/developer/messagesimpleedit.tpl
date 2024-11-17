
<form action="{www_dir}{index}/forum/messagesimpleedit/insert/{forum_id}/" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<h1>{intl-new_comment} - {forum_name}</h1>
    </td>
</tr>
</table>


<hr noshade size="4" />

<br/ >

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" name="Topic" size="40">
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{user}
	</td>
</tr>
</table>

<p class="boxtext">{intl-text}:</p>
<textarea wrap="soft" name="Body" rows="15" cols="40" class="body"></textarea>

<br /><br />

<input type="checkbox" name="notice"> <span class="check">{intl-email-notice}</span><br />
<br />

<hr noshade size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" name="post" value="{intl-post}">
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-abort}">
	</td>
</tr>
</table>

<input type="hidden" name="RedirectURL" value="{redirect_url}" />

</form>
