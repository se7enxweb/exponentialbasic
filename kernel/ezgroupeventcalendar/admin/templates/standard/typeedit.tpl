<form method="post" action="/groupeventcalendar/typeedit/">

<h1>{header}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-parent}:</p>
<select name="ParentID">
<!-- BEGIN parent_item_tpl -->
<option {parent_is_selected} value="{parent_id}">{parent_name}</option>
<!-- END parent_item_tpl -->
</select>

<p class="boxtext">{intl-name}:</p>
<input type="text" size="40" name="Name" value="{name_value}"/>


<p class="boxtext">{intl-description}:</p>
<textarea rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />


<hr noshade="noshade" size="4" />

<input type="hidden" name="TypeID" value="{type_id}" />
<input type="hidden" name="Action" value="{action_value}" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>