<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-search}</h1>
		<div class="boxtext">({forum_start}-{forum_end}/{forum_total})</div>
     </td>
     <td align="right">
	 <form action="{www_dir}{index}/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>


<h2>{intl-searchfor} "{url_text}"</h2>
<br />

<!-- BEGIN empty_result_tpl -->
<h3 class="error">{intl-empty_result}</h3>
<!-- END empty_result_tpl -->


<!-- BEGIN search_result_tpl -->
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th colspan="2">{intl-topic}:</th>
    <th>{intl-author}:</th>
    <th>{intl-time}:</th>
</tr>
    <!-- BEGIN message_tpl -->
<tr>
    	<td class="{td_class}" width="1%">
	<!-- BEGIN new_icon_tpl -->
        <img src="{www_dir}/images/message_new.gif" width="16" height="16" border="0" />&nbsp;
	<!-- END new_icon_tpl -->
	<!-- BEGIN old_icon_tpl -->
        <img src="{www_dir}/images/message.gif" width="16" height="16" border="0" />&nbsp;
	<!-- END old_icon_tpl -->	
	<td class="{td_class}">
	<a href="{www_dir}{index}/forum/message/{message_id}/">
	{message_topic}
	</a>
	</td>
    	<td class="{td_class}">
	<span class="small">{user}</span>
	</td>
   	<td class="{td_class}">
	<span class="small">{postingtime}</span>
	</td>
</tr>
    <!-- END message_tpl -->

</table>
<!-- END search_result_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/forum/search/parent/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
