<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{topic}</h1>
    </td>
    <td align="right">
    <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="{www_dir}/images/path-arrow.gif" height="10" width="15" border="0">
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">
    <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/">{forum_name}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="20" border="0">	
    <a class="path" href="{www_dir}{index}/forum/message/{message_id}/">{message_topic}</a>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>        
   	<td>
	<p class="boxtext">{intl-topic}:</p>
    {topic}
	</td>
    <td>
	<p class="boxtext">{intl-author}:</p>
    {main-user}
	</td>
	<td>
	<p class="boxtext">{intl-time}:</p>
	<span class="small">{main-postingtime}</span>
	</td>
</tr>
</table>


<p class="boxtext">{intl-text}:</p>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>        
   	<td class="bglight">
	{body}
	</td>
</tr>
</table>

<br />

<form method="post" action="{www_dir}{index}/forum/userlogin/reply/{reply_id}/">

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-answer}" />
<!-- <a href="{www_dir}{index}/forum/userlogin/reply/{reply_id}/">[{intl-reply}]</a> -->
</form>

<br />

<h2>{intl-message_thread}</h2>

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th>{intl-reply-topic}:</th>
    <th>{intl-reply-author}:</th>
    <th>{intl-reply-time}:</th>
</tr>

    <!-- BEGIN message_item_tpl -->
<tr>
    	<td class="{td_class}">
	   {spacer}
	<a class="{link_color}" href="{www_dir}{index}/forum/message/{message_id}/">
	{reply_topic}
	</a>
	</td>
    	<td class="{td_class}">
	{user}
	</td>
    	<td class="{td_class}">
	<span class="small">{postingtime}</span>
	</td>
</tr>
    <!-- END message_item_tpl -->

</table>
</form>

<hr noshade="noshade" size="4" />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<form action="{www_dir}{index}/forum/userlogin/new/{forum_id}">
  	<input class="okbutton" type="submit" value="{intl-new-posting}" />
	</td>
</tr>
</table>

