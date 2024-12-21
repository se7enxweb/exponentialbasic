<form action="{www_dir}{index}/forum/messageedit/insert/{forum_id}/" method="post">
<!-- <form action="{www_dir}{index}/forum/userlogin/insert/{forum_id}/" method="post"> -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
	<h1>{intl-headline}</h1>
    </td>
</tr>
</table>

<hr noshade size="4" />

	<img src="{www_dir}/images/path-arrow.gif" height="10" width="15" border="0">
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}">{forum_name}</a>

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

