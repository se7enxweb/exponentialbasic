<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{topic}</h1>
     </td>
     <td align="right">
        <form action="{www_dir}{index}/forum/search/" method="post">
           <input class="searchbox" type="text" name="QueryString" size="10" />
           <input type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>



	<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0">
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0">
    <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0">
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/">{forum_name}</a>
  
<br />

<form action="{www_dir}{index}/forum/reply/insert/{msg_id}/" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-topic}:</p>
	<input type="text" class="box" name="Topic" size="40" value="{topic}">
	</td>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{user}
	</td>
</tr>
</table>

<p class="boxtext">{intl-text}:</p>
<textarea wrap="soft" class="box" name="Body" rows="15" cols="40" rows="10">{body}</textarea>
<br /><br />
    
<input type="checkbox" name="notice"> {intl-email_notice}
<br /><br />


<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="reply" value="{intl-answer}">
	<input type="hidden" name="Action" value="Insert" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/forum/messagelist/{forum_id}">
	<input class="okbutton" type="submit" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>
</form>