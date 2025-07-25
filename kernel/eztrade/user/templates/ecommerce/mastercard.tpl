<h1>{intl-mastercard_payment}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<p class="boxtext">{intl-mastercard_code}:</p>
<input type="text" name="CCNumber" /> <br />

<p class="boxtext">{intl-expire_date}:</p>
<span class="small">{intl-month}:</span> <input type="text" size="2" name="ExpierMonth" /> <span class="small">{intl-year}:</span> <input type="text" size="2" name="ExpierYear" /><br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}" />

<input type="hidden" name="Action" value="Verify" />

</form>