<!-- start #commentsWrap -->
<div id="commentsWrap">

<div id="reviews">
   <h5 class="reviews">{intl-headline} ({total_threads})</h5>

<!-- BEGIN message_list_tpl -->
    <!-- BEGIN message_item_tpl -->
     <p>{spacer}{spacer}<span class="reviewHeadings"><a href="{www_dir}{index}/forum/message/{message_id}/">{postingtime} &raquo; {topic}</a>&nbsp;{user}
	 <!-- BEGIN private_message_tpl -->  
	&nbsp;[<a href="{www_dir}{index}/message/edit/?Subject={intl-re}{PM_topic}&amp;Receiver={username}" 
	title="{intl-send-msg}&nbsp;{user}" class="small">{intl-PM}</a>]
	<!-- END private_message_tpl -->
	 </span><br />{body}</p>
    <!-- END message_item_tpl -->
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
<p>
<!-- BEGIN view_list_tpl -->
  <img src="{www_dir}/images/arrow.gif" alt="" /> <a href=
  "{www_dir}{index}/forum/messagelist/{forum_id}/" title="{intl-all-posts}">{intl-all-posts}</a><br />
<!-- END view_list_tpl -->
  <img src="{www_dir}/images/arrow.gif" alt="" /> <a href=
  "{www_dir}{index}/forum/userlogin/newsimple/{forum_id}?RedirectURL={redirect_url}" title="{intl-new-posting}">{intl-new-posting}</a></p>
</div>
<!-- end #commentsWrap -->
</div>