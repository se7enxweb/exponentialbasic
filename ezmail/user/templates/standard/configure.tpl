<form method="post" action="{www_dir}{index}/mail/config/" enctype="multipart/form-data" >

<h1>{intl-configure}</h1>

<hr noshade="noshade" size="4" />
<h2>{intl-options}:</h2>
<p class="boxtext">{intl-signature}:</p>
<textarea class="box" name="Signature" cols="40" rows="5" wrap="soft">{signature}</textarea>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
  <td>
    <p class="boxtext">{intl-auto_signature}:</p>
    <input type="checkbox" value="true" name="AutoSignature" {signature_checked} />
  </td>
  
  <td>
    <p class="boxtext">{intl-on_delete}:</p>
    <input type="radio" value="trash" name="OnDelete" {trash_checked} />
    <span>{intl-trash_on_del}</span><br />
    <input type="radio" value="del" name="OnDelete" {delete_checked} />
    <span>{intl-del_on_del}</span><br />
  </td>

</tr>
<tr>
  <td>
    <p class="boxtext">{intl-show_unread}:</p>
    <input type="checkbox" value="true" name="ShowUnread" {show_unread_checked} />
    <span>{intl-unread_text}</span><br />
  </td>

  <td>
    <p class="boxtext">{intl-auto_check_mail}:</p>
    <input type="checkbox" value="true" name="AutoCheckMail" {check_mail_checked} />
    <span>{intl-auto_text}</span><br />
  </td>
</tr>
</table>

<h2>{intl-account_setup}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="33%">{intl-name}:</th>
	<th width="32%">{intl-type}:</th>
	<th width="27%">{intl-folder}:</th>
	<th width="5%">{intl-active}:</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN account_item_tpl -->
<tr>
	<td class="{td_class}">
	{account_name}
	</td>

	<td class="{td_class}">
	{account_type}
	</td>
	<td class="{td_class}">
	{account_folder}
	</td>
	<td class="{td_class}">
	<input type="checkbox" name="AccountActiveArrayID[]" value="{account_id}" {account_active_checked} />
	</td>
	<td class="{td_class}">
	  <a href="{www_dir}{index}/mail/accountedit/{account_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{account_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{account_id}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	</td>	
	<td class="{td_class}">
	<input type="checkbox" name="AccountArrayID[]" value="{account_id}" />
	</td>
</tr>
<!-- END account_item_tpl -->
</table>
<br />
<br />

<h2>{intl-filters_setup}:</h2>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="98%">{intl-name}:</th>
	<th width="1%">&nbsp;</th>
	<th width="1%">&nbsp;</th>
</tr>
<!-- BEGIN filter_item_tpl -->
<tr>
	<td class="{td_class}">
	{filter_name}
	</td>
	<td class="{td_class}">
	  <a href="{www_dir}{index}/mail/filteredit/{filter_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{filter_id}-red','','/images/{site_style}/redigerminimrk.gif',1)">
           <img name="ezb{filter_id}-red" border="0" src="{www_dir}/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" />
          </a>
	</td>	
	<td class="{td_class}">
	<input type="checkbox" name="FilterArrayID[]" value="{filter_id}" />
	</td>
</tr>
<!-- END filter_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteAccounts" value="{intl-delete}" />

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" name="NewAccount" value="{intl-new}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" name="NewFilter" value="{intl-new_filter}" /></td>
</tr>
</table>
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Ok" value="{intl-ok}" />

</form>