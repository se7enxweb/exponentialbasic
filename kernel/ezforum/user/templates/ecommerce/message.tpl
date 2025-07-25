<!-- start #breadcrumbs -->
<div id="breadcrumbs">
<!-- BEGIN header_list_tpl -->
&nbsp; <a href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a>
 &raquo; <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
<!-- END header_list_tpl -->
 &raquo; <a href="{www_dir}{index}/forum/messagelist/{forum_id}/">{forum_name}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-message_view}</h1>

<!-- BEGIN message_error_tpl -->
<p class="error">{intl-error_no_message}</p>
<!-- END message_error_tpl -->
<!-- BEGIN message_body_tpl -->

<br />

<h2>{topic}</h2>
<br />
<table border="0" cellspacing="0" cellpadding="0">
<tr>    
   	<td valign="top" width="50%">
	<p class="boxtext">{intl-author}:</p>
    {main-user}
<!-- BEGIN private_message_tpl -->    
	&nbsp;[<a href="{www_dir}{index}/message/edit/?Subject={intl-re}{PM_topic}&amp;Receiver={username}" 
	title="{intl-send-msg}&nbsp;{main-user}" class="small">{intl-PM}</a>]
<!-- END private_message_tpl -->
	</td>
	<td valign="top" width="50%">
	<p class="boxtext">{intl-time}:</p>
	<span class="small">{main-postingtime}</span>
	</td>
</tr>
</table>


<p class="boxtext">{intl-text}:</p>
<table border="0" cellspacing="0" cellpadding="4">
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
    <form method="post" action="{www_dir}{index}/forum/userlogin/reply/{reply_id}/">
    <input class="stdbutton" type="submit" value="{intl-answer}" />
    </form>
</td>
<!-- BEGIN edit_current_message_item_tpl -->
<td>
    &nbsp;
</td>
<td>
    <form method="post" action="{www_dir}{index}/forum/messageedit/edit/{message_id}/">
    <input class="stdbutton" type="submit" value="{intl-edit}" />
    </form>
</td>
<td>
    &nbsp;
</td>
<td>
    <form method="post" action="{www_dir}{index}/forum/messageedit/delete/{message_id}/">
    <input class="stdbutton" type="submit" value="{intl-delete}" />
    </form>
</td>
<!-- END edit_current_message_item_tpl -->
</tr>
</table>
<br />

<h2>{intl-message_thread}</h2>

<table class="list" border="0" cellspacing="0" cellpadding="4">
<tr>
        <th width="45%">{intl-reply-topic}:</th>
	<th width="25%">{intl-reply-author}:</th>
	<th width="29%"><div align="right">{intl-reply-time}:</div></th>
	<th width="1%"></th>
</tr>

    <!-- BEGIN message_item_tpl -->
<tr>
    	<td class="{td_class}">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>

		<td width="1%">
		
		<!-- BEGIN new_icon_tpl -->
                <img src="{www_dir}/images/message_new.gif" width="16" height="16" border="0" alt="New message" />&nbsp;
		<!-- END new_icon_tpl -->
		<!-- BEGIN old_icon_tpl -->
                <img src="{www_dir}/images/message.gif" width="16" height="16" border="0" alt="Message" />&nbsp;
		<!-- END old_icon_tpl -->	
		</td>
		<td width="99%">
		{spacer}{spacer}<a class="{link_color}" href="{www_dir}{index}/forum/message/{message_id}/">{reply_topic}</a>
		</td>
	</tr>
	</table>
	</td>
    	<td class="{td_class}">
	<span class="small">{user}
	
	<!-- BEGIN item_private_message_tpl -->    
	&nbsp;[<a href="{www_dir}{index}/message/edit/?Subject={intl-re}{PM_topic}&amp;Receiver={username}" 
	title="{intl-send-msg}&nbsp;{user}" class="small">{intl-PM}</a>]
	<!-- END item_private_message_tpl -->
	
	</span>
	</td>
    	<td class="{td_class}" align="right">
	<span class="small">{postingtime}</span>
	</td>
	<td class="{td_class}" align="right">
	&nbsp;
        <!-- BEGIN edit_message_item_tpl -->
        <nobr><a href="{www_dir}{index}/forum/messageedit/edit/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-red','','/images/redigerminimrk.gif',1)"><img name="ezfrm{message_id}-red" border="0" src="{www_dir}/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>&nbsp;<a href="{www_dir}{index}/forum/messageedit/delete/{message_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezfrm{message_id}-slett','','/images/slettminimrk.gif',1)"><img name="ezfrm{message_id}-slett" border="0" src="{www_dir}images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a></nobr>
        <!-- END edit_message_item_tpl -->
    </td>

</tr>
<!-- END message_item_tpl -->

</table>


<hr noshade="noshade" size="4" />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<form action="{www_dir}{index}/forum/userlogin/new/{forum_id}">
  	<input class="stdbutton" type="submit" value="{intl-new-posting}" />
        </form>
	</td>
</tr>
</table>
<!-- END message_body_tpl -->
        </div>
        <!-- end #contentWrap -->