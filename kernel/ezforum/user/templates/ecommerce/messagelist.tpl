<!-- start #breadcrumbs -->
<div id="breadcrumbs">
<!-- BEGIN header_list_tpl -->
 &nbsp; <a href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
 &raquo; <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
<!-- END header_list_tpl -->
 &raquo; <a href="{www_dir}{index}/forum/messagelist/{forum_id}">{forum_name}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-headline}</h1>

<!-- BEGIN no_access_tpl -->
{intl-no_access}
<!-- END no_access_tpl -->

<!-- BEGIN read_access_tpl -->

<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td  width="20%"><p class="boxtext">({forum_start}-{forum_end}/{forum_total})</p></td>
    <td align="right" valign="middle" width="60%">
	<form action="{www_dir}{index}/forum/messagelist/{forum_id}/parent/{offset}" method="post">
	<span class="boxtext">{intl-show}:&nbsp;</span>
        <select name="ForumMessages">
	<!-- BEGIN messages_element_tpl -->
	<option {is_selected} value="{messages_number}">{messages_number}</option>
	<!-- END messages_element_tpl -->
	</select>
	<input class="stdbutton" type="submit" value="{intl-update}" />
	</form>
    </td>
    <td align="right" width="20%">
    <form action="{www_dir}{index}/forum/messagelist/{forum_id}/" method="post">
        <!-- BEGIN hide_threads_tpl -->
        <input class="stdbutton" type="submit" name="HideThreads" value="{intl-hide_threads}" />
        <!-- END hide_threads_tpl -->
        <!-- BEGIN show_threads_tpl -->
        <input class="stdbutton" type="submit" name="ShowThreads" value="{intl-show_threads}" />
        <!-- END show_threads_tpl -->
    </form>
    </td>
  </tr>
</table>

<form action="{www_dir}{index}/forum/userlogin/new/{forum_id}">

<table class="list" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th width="55%">{intl-topic}:</th>
    <th width="24%">{intl-author}:</th>
    <th class="right" width="20%">{intl-time}:</th>
    <th width="1%"></th>
</tr>

<!-- BEGIN message_item_tpl -->
<tr>
    <td class="{td_class}">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="1%">
		<!-- BEGIN new_icon_tpl -->
                <img src="{www_dir}/images/message_new.gif" width="16" height="16" border="0" alt="New message" />
		<!-- END new_icon_tpl -->
		<!-- BEGIN old_icon_tpl -->
                <img src="{www_dir}/images/message.gif" width="16" height="16" border="0" alt="Message" />
		<!-- END old_icon_tpl -->	
		</td>
		<td width="99%">
        &nbsp;{spacer}{spacer}<a href="{www_dir}{index}/forum/message/{message_id}/">{topic}</a>&nbsp;&nbsp;<span class="small">{count_replies}</span>
        </td>
	</tr>
	</table>
    </td>
    <td class="{td_class}">
        <span class="small">{author}

	<!-- BEGIN private_message_tpl -->    
	&nbsp;[<a href="{www_dir}{index}/message/edit/?Subject={intl-re}{PM_topic}&Receiver={username}" 
	title="{intl-send-msg}&nbsp;{author}" class="small">{intl-PM}</a>]
	<!-- END private_message_tpl -->

	</span>
    </td>
    <td class="{td_class}" align="right">
        <span class="small">{postingtime}</span>
    </td>
    <td class="{td_class}" align="right">
		&nbsp;
        <!-- BEGIN edit_message_item_tpl -->
        <a href="{www_dir}{index}/forum/messageedit/edit/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezfrm{message_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>&nbsp;<a href="{www_dir}{index}/forum/messageedit/delete/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezfrm{message_id}-slett" border="0" src="{www_dir}images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
        <!-- END edit_message_item_tpl -->
    </td>
</tr>
<!-- END message_item_tpl -->

</table>

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/parent/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/parent/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/forum/messagelist/{forum_id}/parent/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-new-posting}" />
</form>
<!-- END read_access_tpl -->

        </div>
        <!-- end #contentWrap -->