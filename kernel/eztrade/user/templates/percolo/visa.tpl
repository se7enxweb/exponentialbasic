        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="" width="140" height="100" /><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">{intl-visa_payment}</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		<tr>
		    <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td colspan="2">

<form action="{www_dir}{index}/trade/payment/{order_id}/{payment_type}/" method="post" >

<p class="boxtext">{intl-visa_code}:</p>
<input type="text" name="CCNumber" /> <br />

<p class="boxtext">{intl-expire_date}:</p>
<span class="small">{intl-month}:</span> <input type="text" size="2" name="ExpierMonth" /> <span class="small">{intl-year}:</span> <input type="text" size="2" name="ExpierYear" /><br />
<br />

<input class="okbutton" type="submit" value="{intl-ok}" />

<input type="hidden" name="Action" value="Verify" />

</form>

</td>
</tr>
</table>