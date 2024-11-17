<h1>{intl-voucher_payment}</h1>

<hr noshade="noshade" size="1" />

<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<b>{intl-key_number}:</b><br />

<input type="text" name="KeyNumber" /><br />

<!-- BEGIN error_tpl -->

<p class="error">{intl-error_message}</p>

<!-- END error_tpl -->

<hr noshade="noshade" size="1" />

<input class="okbutton" type="submit" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

<input type="hidden" name="Action" value="Verify" />


</form> 

