<!-- BEGIN wishlist_sendt_tpl -->
<h1>{intl-wishlist_sendt}</h1>

<hr noshade size="4" />

<!-- END wishlist_sendt_tpl -->

<!-- BEGIN wishlist_empty_tpl -->
<h1>{intl-empty_wishlist}</h1>

<hr noshade="noshade" size="4" />

{intl-add_something_to_wishlist_first}

<!-- END wishlist_empty_tpl -->


<!-- BEGIN wishlist_private_tpl -->
<h1>{intl-wishlist_is_private}</h1>

<hr noshade size="4" />

{intl-wishlist_must_be_public_to_send_it_to_others}

<!-- END wishlist_private_tpl -->

<!-- BEGIN send_wishlist_tpl -->

<h1>{intl-send_wishlist_to_friend}</h1>


<hr noshade size="4" />

<form method="post" action="{www_dir}{index}/trade/sendwishlist/" >

<b>{intl-send_wishlist_to}</b>:<br />
<input type="text" name="SendTo" value="" />

<br />


<p class="boxtext">{intl-personal_message}:</p>
<textarea name="Message" cols="40" rows="5" wrap="soft">{intl-standard_message}</textarea>
<br /><br />

<input class="okbutton" type="submit" value="{intl-send_wishlist}" />
<input type="hidden" name="Action" value="SendWishlist" />
</form>

<!-- END send_wishlist_tpl -->