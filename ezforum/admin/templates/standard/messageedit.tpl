<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input size="12" type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/forum/messageedit/{action_value}/{message_id}">

<p class="error">{error_msg}</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" size="40" name="Topic" value="{message_topic}">
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{message_user}
	</td>
</tr>
</table>
	
<p class="boxtext">{intl-time}:</p>
<span class="p">{message_postingtime}</span>

<p class="boxtext">{intl-body}:</p>
<textarea rows="10" class="box" cols="40" name="Body">{message_body}</textarea>

<br /><br />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/forum/messagelist/{forum_id}">
	<input class="okbutton" type="submit" value="{intl-cancel}">
	</form>
	</td>
</tr>
</table>
