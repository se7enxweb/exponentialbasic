<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2" class="menuhead">{intl-mail}</td>
</tr>

<!-- BEGIN mail_check_tpl -->
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/check/">{intl-check_mail}</a></td>
</tr>
<!-- END mail_check_tpl -->

<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/folderlist">{intl-folder_list}</a></td>
</tr>
<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/mailedit/">{intl-new_mail}</a></td>
</tr>

<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/folderedit/">{intl-new_folder}</a></td>
</tr>

<tr>
	<td width="1%" valign="top"><img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/config/">{intl-configure}</a></td>
</tr>
<tr>
	<td colspan="2" class="menusubhead">{intl-folders}:</td>
</tr>
<!-- BEGIN mail_folder_tpl -->
</table>
<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
<!--	<td width="1%" valign="top"><img src="{www_dir}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>-->
	<td width="1%" valign="top">{indent}<img src="{www_dir}/design/{sitedesign}/images/dot.gif" width="10" height="12" border="0" alt="" /><br /></td>
	<td width="99%"><a class="menu" href="{www_dir}{index}/mail/folder/{folder_id}">{folder_name} {unread}</a></td>
</tr>
<!-- END mail_folder_tpl -->


<tr>
	<td colspan="2" class="menuspacer">&nbsp;</td>
</tr>
</table>

