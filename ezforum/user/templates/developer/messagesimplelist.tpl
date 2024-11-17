<hr noshade size="4" />

<h2>{intl-headline}</h2>

<!-- BEGIN message_list_tpl -->
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
		{topic}
	</td>
    	<td class="{td_class}">
	    {user}
	    </td>
    	<td class="{td_class}">
	   <span class="small">{postingtime}</span>
	   </td>
    </tr>
    <tr>
    <td colspan="3">
    <p>
    {body}
    </p>
    <a class="path" href="{www_dir}{index}/forum/userlogin/replysimple/{forum_id}/{message_id}/?RedirectURL={redirect_url}">[ {intl-reply} ]</a>
    </td>
    </tr>
    <!-- END message_item_tpl -->

</table>

<!-- END message_list_tpl -->

<a href="{www_dir}{index}/forum/category/forum/{forum_id}/?Offset={prev_offset}&Limit={limit}">
{previous}
</a>

<a href="{www_dir}{index}/forum/category/forum/{forum_id}/?Offset={next_offset}&Limit={limit}">
{next}
</a>

<form action="{www_dir}{index}/forum/userlogin/newsimple/{forum_id}">

<hr noshade size="4" />

<input class="okbutton" type="submit" value="{intl-new-posting}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>

