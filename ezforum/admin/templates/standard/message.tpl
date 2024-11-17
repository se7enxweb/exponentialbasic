<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-message_view}</h1>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString">
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

	<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
	<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />
    <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/">{forum_name}</a>
<!--
	<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />	
    <a class="path" href="{www_dir}{index}/forum/message/{message_id}/">{message_topic}</a>
-->
<hr noshade="noshade" size="4" />

<br />

<h2>{topic}</h2>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>        
    <td valign="top">
	<p class="boxtext">{intl-author}:</p>
    {user}
	</td>
	<td valign="top">
	<p class="boxtext">{intl-time}:</p>
	<span class="small">{postingtime}</span>
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

<hr noshade="noshade" size="4" />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<form class="method="post" action="{www_dir}{index}/forum/messageedit/edit/{message_id}">
	<input class="okbutton" type="submit" value="{intl-edit}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/forum/messageedit/delete/{message_id}">
	<input class="okbutton" type="submit" value="{intl-delete}">
	</form>
	</td>
</tr>

</table>

<br />

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
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>

		<td width="1%" valign="top">
		{spacer}{spacer}
        <img src="{www_dir}/images/message.gif" width="16" height="16" border="0" alt="Message" />&nbsp;
		</td>
		<td width="99%" valign="top">
		<a class="{link_color}" href="{www_dir}{index}/forum/message/{message_id}/">{reply_topic}</a>
		</td>
	</tr>
	</table>
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


