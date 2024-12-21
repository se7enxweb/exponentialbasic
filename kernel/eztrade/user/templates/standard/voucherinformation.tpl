<form action="{www_dir}{index}/trade/voucherinformation/{product_id}" method="post">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">

<h2>{product_name} - {product_price}</h2>

<hr noshade="noshade" size="4" />

<p>{intl-description}</p>

<!-- BEGIN email_tpl -->
<tr>
<th>{intl-to_name}</th>
</tr>
<tr>
      <td>
      <input type="text" name="ToName" value="{to_name}" />
      </td>
</tr>
<tr>
<th>{intl-to_email}</th>
</tr>
<tr>
      <td>
      <input type="text" name="Email" value="{email_var}" />
      </td>
</tr>
<tr>
<th>{intl-from_name}</th>
</tr>
<tr>
      <td>
      <input type="text" name="FromName" value="{from_name}" />
      </td>
</tr>
<th>{intl-from_email}</th>
<tr>

      <td>
      <input type="text" name="FromEmail" value="{from_email}" />
      </td>
</tr>
<th>{intl-text}</th>
<tr>
      <td>
      <textarea cols="60" name="Description" rows="8">{email_text}</textarea>
      </td>
</tr>
<!-- END email_tpl -->

<!-- BEGIN smail_tpl -->

<tr>
<td>
    <table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td>
        <h2>{intl-to-address}</h2>
        </td>
    </tr>
     <tr>
         <td>
         <input type="text" name="ToName" value="{to_name_value}" />
         </td>
     </tr>
     <tr>
	 <td>
	 <p class="boxtext">{intl-street}:</p>
	 <input type="text" size="20" name="ToStreet1" value="{to_street1_value}"/><br />
	 <input type="text" size="20" name="ToStreet2" value="{to_street2_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <p class="boxtext">{intl-zip}:</p>
	 <input type="text" size="20" name="ToZip" value="{to_zip_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <p class="boxtext">{intl-place}:</p>
	 <input type="text" size="20" name="ToPlace" value="{to_place_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <!-- BEGIN country_tpl -->
	 <p class="boxtext">{intl-country}:</p>
	 <select name="ToCountryID[]" size="5">
	 <!-- BEGIN country_option_tpl -->
	 <option {is_selected} value="{to_country_id}">{country_name}</option>
	 <!-- END country_option_tpl -->
	 </select>
	 <!-- END country_tpl -->
	 </td>
    </tr>
    </table>
</td>
<td>
    <table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
    <tr>
        <td>
        <h2>{intl-from-address}</h2>
        </td>
    </tr>
     <tr>
         <td>
         <input type="text" name="FromName" value="{to_name_value}" />
         </td>
     </tr>
     <tr>
	 <td>
	 <p class="boxtext">{intl-street}:</p>
	 <input type="text" size="20" name="FromStreet1" value="{to_street1_value}"/><br />
	 <input type="text" size="20" name="FromStreet2" value="{to_street2_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <p class="boxtext">{intl-zip}:</p>
	 <input type="text" size="20" name="FromZip" value="{to_zip_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <p class="boxtext">{intl-place}:</p>
	 <input type="text" size="20" name="FromPlace" value="{to_place_value}"/>
	 </td>
     </tr>
     <tr>
         <td>
	 <!-- BEGIN country_tpl -->
	 <p class="boxtext">{intl-country}:</p>
	 <select name="FromCountryID[]" size="5">
	 <!-- BEGIN country_option_tpl -->
	 <option {is_selected} value="{to_country_id}">{country_name}</option>
	 <!-- END country_option_tpl -->
	 </select>
	 <!-- END country_tpl -->
	 </td>
    </tr>
    </table>
</td>

</tr>

<th>{intl-text}</th>
<tr>
      <td>
      <textarea name="Description" cols="60" rows="8">{email_text}</textarea>
      </td>
</tr>


<!-- END smail_tpl -->
</table>

<hr noshade="noshade" size="4" />

<input type="hidden" name="Mail" value="{mail_method}" />
<input type="hidden" name="ProductID" value="{product_id}" />
<input type="hidden" name="Price" value="{price_range}" />

<input type="submit" name="OK" value="{intl-ok}" />&nbsp;

<input type="submit" name="Cancel" value="{intl-cancel}" />


</form>