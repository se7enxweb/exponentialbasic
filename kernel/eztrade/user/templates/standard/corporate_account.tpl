<h1>{intl-corporate_account_payment}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<p class="boxtext">{intl-account_number}:</p>

<input type="text" name="AccountNumber" value="{account_number}"/><br />

<br />

<!-- BEGIN error_tpl -->

<p class="error">{intl-error_message}</p>

<!-- END error_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />

<input type="hidden" name="Action" value="Verify" />


</form> 

