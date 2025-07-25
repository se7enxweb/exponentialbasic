<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; 
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{intl-find_wishlist}</h1>

<form method="post" action="{www_dir}{index}/trade/findwishlist/">
<p>Search by first and/or last name:</p>
	<input type="text" value="{search_text}" name="SearchText" /> &nbsp;
	<input class="stdbutton" type="submit" value="{intl-search}" />

<hr noshade size="4" />

<table width="90%" cellspacing="0" cellpadding="2" border="0">
<!-- BEGIN wishlist_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/viewwishlist/{user_id}/">{first_name} {last_name}</a>
	</td>
</tr>
<!-- END wishlist_tpl -->
</table>

</form>
</div>