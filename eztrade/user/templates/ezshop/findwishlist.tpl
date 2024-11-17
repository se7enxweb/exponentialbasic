<form method="post" action="{www_dir}{index}/trade/findwishlist/">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-find_wishlist}</h1>
	</td>
	<td align="right">
	<input type="text" value="{search_text}" name="SearchText" /> &nbsp;
	<input class="okbutton" type="submit" value="{intl-search}" />
	</td>
</tr>
</table>

<hr noshade size="4" />




<table width="100%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN wishlist_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/viewwishlist/{user_id}/">{first_name} {last_name}</a>
	</td>
</tr>
<!-- END wishlist_tpl -->
</table>

</form>