<h2>{intl-headline}</h2>

<!-- BEGIN message_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th width="45%">{intl-topic}:</th>
    <th width="25%">{intl-author}:</th>
    <th width="30%"><div align="right">{intl-time}:</div></th>
</tr>

    <!-- BEGIN message_item_tpl -->
    <tr>
    	<td class="{td_class}">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="1%">{spacer}{spacer}</td>
		<td width="99%">{topic}</td>
	</tr>
	</table>
		</td>
    	<td class="{td_class}">
	    {user}
	    </td>
    	<td class="{td_class}" align="right">
	   <span class="small">{postingtime}</span>
	   </td>
    </tr>
    <tr>
    <td colspan="3">
    <div class="p">
    {body}
    </div>
	<div class="spacer">
	<form action="{www_dir}{index}/forum/userlogin/replysimple/{forum_id}/{message_id}/?RedirectURL={redirect_url}">
	<input class="stdbutton" type="submit" value="{intl-reply}" />
	</form>
	</div>
    </td>
    </tr>
    <!-- END message_item_tpl -->

</table>

<!-- END message_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}{url}parent/{item_previous_index}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}{url}parent/{item_index}/">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}{url}parent/{item_next_index}/">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<form action="{www_dir}{index}/forum/userlogin/newsimple/{forum_id}">
<input class="stdbutton" type="submit" value="{intl-new-posting}" />
<input type="hidden" name="RedirectURL" value="{redirect_url}" />
</form>


