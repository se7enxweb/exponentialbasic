<br />
<p class="boxtext">{intl-topic}:</p>
<input type="text" name="NewMessageTopic" class="box" size="40" value="{new_message_topic}" />
<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-author}:</p>
	{message_author}
	</td>
	<td align="right">
	<p class="boxtext">{intl-posted_at}:</p>
	<span class="small">{message_posted_at}</span>
	</td>
</tr>
</table>

<p class="boxtext">{intl-text}:</p>
<textarea wrap="soft" name="NewMessageBody" class="box" rows="15" cols="40" class="body">{new_message_body}</textarea>

<!-- BEGIN message_body_info_tpl -->
<p>{intl-tags_info} <b>{allowed_tags}</b>. </p>
<!-- END message_body_info_tpl -->

<!-- BEGIN message_reply_info_tpl -->
<p class="boxtext">{intl-reply_info_header}:</p>
<p>{intl-reply_info_1}.</p><p>{intl-reply_info_2}, {intl-reply_info_3}. {intl-reply_info_4} </p>
<!-- END message_reply_info_tpl -->

<br /><br />
<input type="checkbox" name="NewMessageNotice" {new_message_notice}> <span class="check">{intl-notice_requested}</span><br />

<br />

