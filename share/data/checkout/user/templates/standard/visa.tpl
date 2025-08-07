<h1>{intl-visa_payment}</h1>

<form action="/trade/payment/{order_id}/{payment_type}/" method="post" >

<h2>{intl-visa_code}</h2>
<input type="text" name="CCNumber" /> <br />
<h2>{intl-expire_date}</h2>
{intl-month} <input type="text" size="2" name="ExpierMonth" /> {intl-year} <input type="text" size="2" name="ExpierYear" /><br />

<input type="submit" value="{intl-ok}" />

<input type="hidden" name="Action" value="Verify" />

</form>