<form method="post" action="{www_dir}{index}/trade/productedit/optionedit/">


<h1>{intl-option_edit}: {product_name}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-title}:</p>
<input type="text" size="40" class="box" name="OptionName" value="{name_value}"/>
	
<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" class="box" name="Description">{description_value}</textarea>
<br /><br />
	
<h2>{intl-options}</h2>
<table table cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>&nbsp;</th>
	<th colspan="{value_count}">{intl-value_description}:</th>
	<th>{intl-main_price}:</th>
	<!-- BEGIN group_item_tpl -->
	<th>{price_group_name}:</th>
	<!-- END group_item_tpl -->
	<!-- BEGIN option_quantity_header_tpl -->
	<th>{intl-availability}:</th>
	<!-- END option_quantity_header_tpl -->
	<th>&nbsp;</th>
</tr>
<!-- BEGIN value_headers_tpl -->
<tr>
	<th valign="bottom">{intl-value_header}:</th>
	<!-- BEGIN value_description_item_tpl -->
	<td>
	<!-- BEGIN value_description_item_checkbox_tpl -->
	<div class="check"><input type="checkbox" name="OptionDescriptionDelete[]" value="{value_description_index}" />{intl-delete}</div>
	<!-- END value_description_item_checkbox_tpl -->
	<input type="text" size="8" name="OptionValueDescription[{value_description_index}]" value="{option_description_value}" />
	</td>
	<!-- END value_description_item_tpl -->
	<td>&nbsp;</td>
	<td colspan="{group_count}">&nbsp;</td>
</tr>
<!-- END value_headers_tpl -->
	<!-- BEGIN option_item_tpl -->
<tr>
	<th>
	{intl-value} {value_pos}:<!-- {option_value_id} -->
	<input type="hidden" name="OptionValueID[{number_run}]" value="{option_value_id}" />
	</th>
	<!-- BEGIN value_item_tpl -->
	<td>
	<input type="text" size="20" class="halfbox" name="OptionValue[{number_run}][]" value="{option_value}" />
	</td>
	<!-- END value_item_tpl -->
	<td>
	<input type="text" size="6" name="OptionMainPrice[]" value="{main_price_value}" />
	</td>
	<!-- BEGIN option_price_item_tpl -->
	<td>
	<input type="text" size="6" name="OptionPrice[{number_run}][{price_group}]" value="{price_value}" />
	</td>
	<!-- END option_price_item_tpl -->
	<!-- BEGIN option_quantity_item_tpl -->
	<td>
	<input type="text" size="6" name="OptionQuantity[{number_run}]" value="{quantity_value}" />
	</td>
	<!-- END option_quantity_item_tpl -->
	<td>
	<div class="check"><input type="checkbox" name="OptionDelete[]" value="{number_run}" />{intl-delete}</div>
	</td>
</tr>
	<!-- END option_item_tpl -->
</table>
<br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_value}" />	

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="stdbutton" type="submit" name="NewValue" value="{intl-new_value}" />	
	</td>
	<td>&nbsp;</td>
	<!-- BEGIN new_description_tpl -->
	<td>
	<input class="stdbutton" type="submit" name="NewDescription" value="{intl-new_description}" />	
	</td>
	<!-- END new_description_tpl -->
</tr>
</table>

<input type="hidden" name="ValueCount" value="{value_count}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ProductID" value="{product_id}" />
	<input type="hidden" name="OptionID" value="{option_id}" />
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Abort" value="{intl-abort}" />	
	</td>
</tr>
</table>
</form>


