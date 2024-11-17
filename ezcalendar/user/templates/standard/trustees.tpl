<h1>{intl-set_trustees}</h1>

{intl-access_message} {current_user_name}<br />
<form action="{www_dir}{index}/calendar/trustees/edit" method="post">
<input type="hidden" name="current_user_id" value="{current_user_id}" />
<p class="boxtext">{intl-users}</p>
<select multiple size="5" name="TrusteesList[]">
<!-- BEGIN user_item_tpl -->
<option value="{user_id}" {selected}>{user_name}</option>
<!-- END user_item_tpl -->
</select>
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" value="OK" />
</form>
