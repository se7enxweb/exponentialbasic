<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
	 <form action="/forum/search/" method="post">
	       <input class="searchbox" type="text" name="QueryString" size="10" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

	<img src="/images/path-arrow.gif" height="10" width="12" border="0">
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a> 
	<img src="/images/path-slash.gif" height="10" width="16" border="0">
    <a class="path" href="/forum/forumlist/{category_id}/">{category_name}</a>

<!-- BEGIN view_forums_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <th colspan="2">{intl-forum}:</th>
        <th>{intl-forum_description}:</th>
        <th class="right">{intl-threads}/<br />{intl-messages}:</th>
    </tr>
    <!-- BEGIN forum_item_tpl -->
    <tr>
	<td class="{td_class}" width="1%">
	    <img src="/images/forum.gif" width="16" height="16" border="0" alt="Forum" />
	</td>
	<td class="{td_class}" width="50%">
	    <a href="/forum/messagelist/{forum_id}/">{name}</a>
        </td>
	<td class="{td_class}" width="48%">
	    <span class="small">{description}</span>
        </td>
	<td class="{td_class}" align="right" width="1%">
	    {threads} / {messages}
        </td>
    </tr>
    <!-- END forum_item_tpl -->

</table>
<!-- END view_forums_tpl -->
<br />

