<form action="{www_dir}{index}/poll/pollist/" method="post">

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<br />

<p class="error">{error_msg}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<th width="20%">{intl-poll}:</th>
	<th width="50%">{intl-description}:</th>
	<th width="10%">{intl-enabled}:</th>
	<th width="10%">{intl-closed}:</th>
	<th width="10%">{intl-main}:</th>
	<th colspan="2">&nbsp;</td>
</tr>
<tr>
	<td>
	{nopolls}
	</td>
	<!-- BEGIN poll_item_tpl -->
	<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/poll/polledit/edit/{poll_id}/">{poll_name}</a>
	</td>
	<td class="{td_class}">
	{poll_description}&nbsp;
	</td>

	<td class="{td_class}">
	{poll_is_enabled}
	</td>

	<td class="{td_class}">
	{poll_is_closed}
	</td>

	<td class="{td_class}">
	<input type="radio" name="MainPollID" value="{poll_id}" {is_checked} />
	</td>

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/poll/polledit/edit/{poll_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezp{poll_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezp{poll_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="PollArrayID[]" value="{poll_id}">
	</td>
	</tr>
	<!-- END poll_item_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
	  <input class="stdbutton" name="AddPoll" type="submit" value="{intl-addpoll}" />
	</td>  
	<td>&nbsp;</td>
	<td>
	  <input class="stdbutton" type="submit" name="DeletePolls" value="{intl-deletepoll}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<input type="hidden" name="Action" value="StoreMainPoll" />
<input class="okbutton" type="submit" value="{intl-save}" />

</form>
