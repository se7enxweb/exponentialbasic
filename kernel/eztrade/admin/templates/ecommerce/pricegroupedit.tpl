<form method="post" action="{www_dir}{index}/trade/pricegroups/new">

<h1>{intl-price_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name}"/>
<br />

<p class="boxtext">{intl-description}:</p>
<input class="box" size="40" name="Description" value="{description}" />
<br />

<p class="boxtext">{intl-groups}:</p>
<select size="6" name="GroupID[]" multiple >
<!-- BEGIN value_tpl -->
<option value="{group_id}" {selected}>{group_name}</option>
<!-- END value_tpl -->
</select>
<br /><br />
	
<hr noshade="noshade" size="4" />

<input type="hidden" name="PriceID" value="{price_id}" />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />	
</form>



