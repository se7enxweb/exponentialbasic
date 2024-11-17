        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="" width="140" height="100" /><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">{intl-confirming-order}</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		<tr>
		    <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td colspan="2">

<h2>{intl-thanks_for_shopping}!</h2>

<p>{intl-email_notice}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<!-- BEGIN billing_address_tpl -->
	<h2>{intl-billing_address}:</h2>
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
	<h2>{intl-shipping_address}:</h2>
	{shipping_first_name} {shipping_last_name} <br />
	{shipping_street1}<br />
	{shipping_street2}<br />
	{shipping_zip} {shipping_place}<br />
	{shipping_country}<br />
	<!-- END shipping_address_tpl -->
	<br />
	</td>
</tr>
</table>
<br />

<h2>{intl-goods_list}:</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>{intl-picture}:</th>
	<th>{intl-product_name}:</th>
	<td align="right"><b>{intl-qty}:</td>
	<td align="right"><b>{intl-price}:</b></td>
</tr>
<!-- BEGIN order_item_tpl -->

<tr>
	<td class="{td_class}">
	<!-- BEGIN order_image_tpl -->
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END order_image_tpl -->&nbsp;
	</td>
	<td class="{td_class}">
	{product_name}&nbsp;
	</td>

        <!-- BEGIN order_item_option_tpl -->

        <!-- END order_item_option_tpl -->

	<td align="right" class="{td_class}">
	{order_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END order_item_tpl -->
<tr>
	<td>&nbsp;</td>
	<td align="right" colspan="2" class="boxtext">{intl-shipping_and_handling}:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right" colspan="2" class="boxtext"><i>Herav {intl-vat}:</i></td>
	<td align="right"><i>{order_vat_sum}</i></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align="right" colspan="2" class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

</td>
</tr>
</table>
