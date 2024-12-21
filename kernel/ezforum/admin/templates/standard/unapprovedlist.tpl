<form metdod="post" action="{www_dir}{index}/forum/unapprovededit/">

<h1>{intl-messages_awaiting_approval}</h1>

<!-- BEGIN message_item_tpl -->
<hr noshade="noshade" size="4" />
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
</tr>
<tr>
    <td width="50%">
        <p class="boxtext">{intl-subject}:</p>
        {message_topic}
    </td>
	<td>
	<p class="boxtext">{intl-username}:</p>
	{message_user}
	</td>
    <td>
        <p class="boxtext">{intl-postingtime}:</p>
        {message_postingtime}
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td>
        <p class="boxtext">{intl-path}:</p>
	<a class="path" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="" />
        <a class="path" href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
	<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="" />
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}">{forum_name}</a>
    </td>
</tr>
</table>

<p class="boxtext">{intl-original_message}:</p>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <td class="bglight">
        {message_body}
    </td>
</tr>
</table>
 
<p class="boxtext">{intl-action}:</p>
<span class="p">{intl-defer}:</span> <input value="Defer" type="radio" name="ActionValueArray[{i}]" checked /><br />
<span class="p">{intl-approve}:</span> <input value="Approve" type="radio" name="ActionValueArray[{i}]" /><br />
<span class="p">{intl-discard}:</span> <input value="Discard" type="radio" name="ActionValueArray[{i}]" /><br />
<span class="p">{intl-reject}:</span> <input value="Reject" type="radio" name="ActionValueArray[{i}]" />

<p class="boxtext">{intl-reject_reason}:</p>
<input type="hidden" name="MessageID[]" value="{message_id}" />
<textarea wrap="soft" rows="3" cols="40" name="RejectReason[]">{reject_message}</textarea>


<!-- END message_item_tpl -->
<br /><br />

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" value="{intl-update}" />

</form>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/forum/unapprovedlist/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/unapprovedlist/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/unapprovedlist/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

