<h1>{intl-subscription_list}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/bulkmail/subscriptionlist" method="post">

<!-- BEGIN category_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="20%">{intl-category_name}:</th>
	<th width="73%">{intl-category_description}:</th>
	<th width="73%">{intl-delay}:</th>
	<th width="1%">{intl-subscribe}</th>
</tr>
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/bulkmail/bulklist/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
	<td class="{td_class}">
	<select name="SendDelay[]">
	<option {delay_0} value="0">{intl-no_delay}</option>
	<option {delay_1} value="1">{intl-day}</option>
	<option {delay_2} value="2">{intl-week}</option>
	<option {delay_3} value="3">{intl-month}</option>
	</select>
	</td>
	<td class="{td_class}"><input type="checkbox" name="CategoryArrayID[]" value="{category_id}" {is_checked} /></td>
	<td class="{td_class}"><input type="hidden" name="CategoryAll[]" value="{category_id}" {is_checked} /></td>
	<td class="{td_class}"><a href="{www_dir}{index}/bulkmail/categoryedit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{category_id}-red','','/user/images/{site_style}/redigerminimrk.gif',1)"><img name="ezb{category_id}-red" border="0" src="{www_dir}/user/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a></td>
</tr>
<!-- END category_item_tpl -->
</table>
<!-- END category_tpl -->

<!-- BEGIN no_categories_tpl -->
<p class="error">{intl-no_categories_error}</p>
<!-- END no_categories_tpl -->



<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><input type="submit" class="okbutton" name="Ok" value="{intl-ok}" /></td>
</tr>
</table>
</form>