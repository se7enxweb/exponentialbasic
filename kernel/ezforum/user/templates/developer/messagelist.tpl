<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
        <form action="{www_dir}{index}/forum/search/" method="post">
           <input type="text" name="QueryString" size="12" />
           <input type="submit" name="search" value="{intl-search}" />
        </form>
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

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th>{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
</tr>

    <!-- BEGIN message_item_tpl -->
    <tr>
    	<td class="{td_class}">
	   {spacer}{spacer}
		<a href="{www_dir}{index}/forum/message/{message_id}/">
		{topic}
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

<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/forum/messagelist/{forum_id}/?Offset={prev_offset}&Limit={limit}">
{intl-previous}
</a>
<!-- END previous_tpl -->

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/forum/messagelist/{forum_id}/?Offset={next_offset}&Limit={limit}">
{intl-next}
</a>
<!-- END next_tpl -->

<form action="{www_dir}{index}/forum/userlogin/new/{forum_id}">

<hr noshade size="4" />

<input class="okbutton" type="submit" value="{intl-new-posting}" />
</form>

