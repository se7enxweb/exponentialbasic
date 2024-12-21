<h1>{intl-confirming-order}</h1>

<hr noshade="noshade" size="1" />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<!-- BEGIN billing_address_tpl -->
	<p><b>{intl-billing_address}:</b></p>
	{customer_first_name} {customer_last_name} <br />
	{billing_street1}<br />
	{billing_street2}<br />
	{billing_zip} {billing_place}<br />
	{billing_country}<br />
	<!-- END billing_address_tpl -->
	<br />
	</td>
	<td>
	<!-- BEGIN shipping_address_tpl -->
	<p><b>{intl-shipping_address}:</b></p>
	{shipping_first_name} {shipping_last_name} <br />
	{shipping_street1}<br />
	{shipping_street2}<br />
	{shipping_zip} {shipping_place}<br />
	{shipping_country}<br />
	<!-- END shipping_address_tpl -->
	<br />
	</td>
</tr>
<tr>
	<td>
	<p><b>{intl-payment_method}:</b></p>
	{payment_method}
	</td>
	<td>
	<p><b>{intl-shipping_type}:</b></p>
	{shipping_type}
	</td>
</tr>
</table>
<br />

<h2>{intl-goods_list}:</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr align="left">
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<th>{intl-options}:</th>
	<th>{intl-qty}:</th>
	<th align="right">{intl-price}:</th>
</tr>
<!-- BEGIN order_item_tpl -->

<tr align="left">
	<td class="{td_class}">
	<!-- BEGIN order_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END order_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}&nbsp;
	</td>
	<td class="{td_class}">
        <!-- BEGIN order_item_option_tpl -->
	<span class="small">{option_name}: {option_value}</span><br />
        <!-- END order_item_option_tpl -->
	&nbsp;
	</td>
	<td class="{td_class}">
	{order_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END order_item_tpl -->
<tr>
	<td colspan="3">&nbsp;</td>
	<td class="boxtext">{intl-shipping_and_handling}:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<td class="boxtext">{intl-vat}:</td>
	<td align="right">{order_vat_sum}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
	<td class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->
<br />
<table width="1%" border="0" cellspacing="0" cellpadding="3">
    <tr>
	<th colspan="2">Wir garantieren f&uuml;r Ihre Sicherheit</th>
    </tr>
    <tr bgcolor="#DDDDDD">
        <td>
            <form name="formSiegel" method="post" action="https://www.trustedshops.de/tshops/siegel.php3" target="_blank">
                <input type="image" border="0" src="{www_dir}/sitedesign/mygold/images/trusted_shop.gif" height="69" width="69" alt="Trusted Shops G&uuml;tesiegel - Bitte hier klicken." />
	        <input name="shop_id" type="hidden" value="XD7D38F69FDE28952D48AC3056C5D449C" />
	    </form>
	</td>
	<td>
            <form method="post" action="https://www.trustedshops.de/tshops/protect.php3" target="_blank">
                <input name=shop_id type=hidden value="XD7D38F69FDE28952D48AC3056C5D449C" />
                <input name=email type=hidden value="{order_email}" />
	        <input name=phone type=hidden value="n/a" />
                <input name=first_name type=hidden value="{customer_first_name}" />
		<input name=last_name type=hidden value="{customer_last_name}" />
		<input name=street type=hidden value="{billing_street1}" />
		<input name=zip type=hidden value="{billing_zip}" />
		<input name=city type=hidden value="{billing_place}" />
		<input name=country type=hidden value="{billing_country}" />
		<input name=amount type=hidden value="{order_sum_wo_cncy}" />
		<input name=curr type=hidden value="DEM" />
		Als Trusted Shops Mitglied bieten wir Ihnen als zus&auml;tzlichen
	        Service die Geld-Zur&uuml;ck-Garantie von Gerling an. Wir &uuml;bernehmen
		alle Kosten dieser Garantie, Sie m&uuml;ssen sich lediglich anmelden.
		<input type="submit" class="okbutton" name="btnProtect" value="Anmeldung Geld-Zur&uuml;ck-Garantie..." />
	    </form>
	</td>
    </tr>
</table>																																						
