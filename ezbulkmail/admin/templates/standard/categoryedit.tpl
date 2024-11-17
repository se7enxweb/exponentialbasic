<h1>{intl-category_edit}</h1>

<hr noshade="noshade" size="4">

<form action="{www_dir}{index}/bulkmail/categoryedit/{category_id}" method="post">

<p class="boxtext">{intl-name}:</p>
<input type="text" class="box" size="40" name="Name" value="{category_name}">
<br>
<p class="boxtext">{intl-description}:</p>
<textarea name="Description" class="box" cols="40" rows="3" wrap="soft">{description}</textarea>

<p class="boxtext">{intl-subscribed_usergroups}:</p>
<select multiple size="5" name="SubscriptionGroupsArrayID[]">
<!-- BEGIN subscribe_group_item_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END subscribe_group_item_tpl -->
</select>

<br /><br />
<input type="checkbox" name="PublicList" value="true" {checked} />&nbsp;<span class="boxtext">{intl-public_list}</span><br /><br />

<hr noshade="noshade" size="4">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Ok" value="{intl-ok}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>