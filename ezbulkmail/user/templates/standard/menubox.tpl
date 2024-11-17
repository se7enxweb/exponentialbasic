<!-- BEGIN normal_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-news_mail}</td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/bulkmail/login/">{intl-mail_subscriptions}</a></td>
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
<!-- END normal_list_tpl -->
<!-- BEGIN single_list_tpl -->
<form action="{www_dir}{index}/bulkmail/singlelist" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-news_mail}</td>
</tr>
<tr>
   <input type="text" size="15" name="Email" />
</tr>
<tr>
  <input class="stdbutton" type="submit" name="SubscribeButton" value="{intl-subscribe}" />
</tr>
<tr>
  <input class="stdbutton" type="submit" name="UnSubscribeButton" value="{intl-unsubscribe}" />
</tr>
<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>
</form>
<!-- END single_list_tpl -->
