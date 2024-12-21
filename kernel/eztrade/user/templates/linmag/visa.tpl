<h1>{intl-visa_payment}</h1>

<hr noshade="noshade" size="4" />

<!-- 
<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<p class="boxtext">{intl-visa_code}:</p>
<input type="text" name="CCNumber" /> <br />

<p class="boxtext">{intl-expire_date}:</p>
<span class="small">{intl-month}:</span> <input type="text" size="2" name="ExpierMonth" /> <span class="small">{intl-year}:</span> <input type="text" size="2" name="ExpierYear" /><br />
<br />

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" value="{intl-ok}" />

<input type="hidden" name="Action" value="Verify" />

</form> -->


<form action="https://extcont.infogroup.it/cgi-bin/check-inn.cgi" method="post">
<input type="hidden" name="f" value="{f}">
<input type="hidden" name="l" value="{l}">
<input type="hidden" name="m" value="{m}">
<input type="hidden" name="o" value="{order_id}">
<input type="hidden" name="i" value="{i}">
<input type="hidden" name="d" value="{d}">
<input type="hidden" name="p" value="{p}">
<input type="hidden" name="c" value="{card_type}">
<input type="hidden" name="u" value="{referer_url}">
<input type="hidden" name="n" value="{first_name} {last_name}">
<input type="hidden" name="e" value="{email}"> 
 <input type="submit" value="{intl-redirect">

</form>