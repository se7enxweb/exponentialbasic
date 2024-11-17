<form method="post" action="{www_dir}{index}/trade/pricegroups/new">

<h1>{intl-price_edit}:</h1>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="4" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="40" name="Name" value="{name}"/>
	</td>

	<td rowspan="3" valign="top">
	<p class="boxtext">{intl-groups}:</p>
	<select size="6" name="GroupID[]" multiple >
	<!-- BEGIN value_tpl -->
	<option value="{group_id}" {selected}>{group_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>
<tr>
</tr>
	<td>
	<p class="boxtext">{intl-description}:</p>
	<input size="40" name="Description" value="{description}" />
	</td>
</tr>
</tr>
	<td>&nbsp;
	</td>
</tr>
</table>
	
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="PriceID" value="{price_id}" />
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />	
	</td>
	</form>
</tr>
</table>



