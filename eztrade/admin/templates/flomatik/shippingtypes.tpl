<h1>{intl-shipping_types}</h1>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/trade/shippingtypes/" method="post">

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	&nbsp;
	</td>

 	<!-- BEGIN type_item_tpl -->
	<td colspan="2">
	<p class="boxtext">{intl-shipping_type}:</p>
	 <input type="hidden" name="TypeID[]" value="{type_id}" />
	 <input type="radio" name="DefaultTypeID" {default_checked} value="{type_id}" />

	 <input type="text" size="15" name="TypeName[]" value="{shipping_type_name}" />

	 <input type="checkbox" name="DeleteType[]" value="{type_id}" />
	 <br />
	<p class="boxtext">{intl-vat_type}:</p>
	<select name="VATTypeID[]">

	<!-- BEGIN vat_item_tpl -->
	<option value="{vat_id}" {vat_selected}>{vat_name}</option>
	<!-- END vat_item_tpl -->

	</select>
	<br /><br />

	</td>
 	<!-- END type_item_tpl -->
<tr>
<tr>
    <td>
    &nbsp;
    </td>
    <!-- BEGIN header_item_tpl -->
    <th>
        {intl-first}:
    </th>
    <th>
        {intl-additional}:
    </th>
    <!-- END header_item_tpl -->
</tr>
<!-- BEGIN group_item_tpl -->
<tr>
	<th class="{td_class}">
	 <input type="hidden" name="GroupID[]" value="{group_id}" />
	 <input type="text" size="15" name="GroupName[]" value="{shipping_group_name}" />
	 <input type="checkbox" name="DeleteGroup[]" value="{group_id}" />

	</th>
       <!-- BEGIN type_group_item_tpl -->
	<td class="{td_class}">
	  <input type="hidden" name="ValueGroupID[]" value="{value_group_id}" />
	  <input type="hidden" name="ValueTypeID[]" value="{value_type_id}" />

	  <input type="text" size="6" name="StartValue[]" value="{start_value}" />
	</td>
	<td class="{td_class}">
	  <input type="text" size="6" name="AddValue[]" value="{add_value}" />
	</td>
       <!-- END type_group_item_tpl -->
</tr>
<!-- END group_item_tpl -->
</table>

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />

<hr noshade size="4" />

<input class="stdbutton" type="submit" name="AddType" value="{intl-add_shipping_type}" />
<input class="stdbutton" type="submit" name="AddGroup" value="{intl-add_shipping_group}" />

<hr noshade size="4" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />

</form>
