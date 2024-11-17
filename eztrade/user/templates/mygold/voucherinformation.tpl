<h1>{product_name} - {product_price}</h1>
<hr noshade="noshade" size="1" />

<p>{intl-description}</p>
<br />

<form action="{www_dir}{index}/trade/voucherinformation/{product_id}" method="post">

<!-- BEGIN email_tpl -->
<table>
  <tr>
    <td>
      <b>{intl-to_name}:</b><br />
      <input type="text" name="ToName" value="{to_name}" />
    </td>
    <td>
      <b>{intl-to_email}:</b><br />
      <input type="text" name="Email" value="{email_var}" />
    </td>
  </tr>
  <tr>
    <td>
      <b>{intl-from_name}:</b><br />
      <input type="text" name="FromName" value="{from_name}" />
    </td>
    <td>
      <b>{intl-from_email}:</b><br />
      <input type="text" name="FromEmail" value="{from_email}" />    
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>{intl-text}:</b><br />
      <textarea cols="60" name="Description" rows="8">{email_text}</textarea>
    </td>
  </tr>
</table>
<!-- END email_tpl -->


<!-- BEGIN smail_tpl -->
<b>{intl-name}:</b><br />
<input type="text" name="Name" value="{name_value}" />
<br /><br />

<b>{intl-street}:</b><br />
<input type="text" size="20" name="Street1" value="{street1_value}"/>
<br /><br />

<b>{intl-street}:</b><br />
<input type="text" size="20" name="Street2" value="{street2_value}"/>
<br /><br />

<b>{intl-zip}:</b><br />
<input type="text" size="20" name="Zip" value="{zip_value}"/>
<br /><br />

<b>{intl-place}:</b><br />
<input type="text" size="20" name="Place" value="{place_value}"/>
<br /><br />

<!-- BEGIN country_tpl -->
<b>{intl-country}:</b><br />
<select name="CountryID[]" size="5">
  <!-- BEGIN country_option_tpl -->
  <option {is_selected} value="{country_id}">{country_name}</option>
  <!-- END country_option_tpl -->
</select>
<br /><br />
<!-- END country_tpl -->

<b>{intl-text}:</b><br />
<textarea name="Description" cols="40" rows="8">{email_text}</textarea>
<br /><br />
<!-- END smail_tpl -->

<hr noshade="noshade" size="1" />

<input type="hidden" name="Mail" value="{mail_method}" />
<input type="hidden" name="ProductID" value="{product_id}" />
<input type="hidden" name="Price" value="{price_range}" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />


</form>