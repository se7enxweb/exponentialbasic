{intl-message}<br />
<form method="post" action="{www_dir}{index}/mail/link/{mail_id}">

<table cellspacing="10" cellpadding="0" border="0">
<tr>
<!-- BEGIN company_list_tpl -->
<td>
<p class="boxtext">{intl-companies}:</p>
<select multiple size="10" name="CompanyID[]">
<!-- BEGIN company_select_tpl -->
<option value="{company_id}" {is_selected}>{company_level}{company_name}</option>
<!-- END company_select_tpl -->
</select>
</td>
<!-- END company_list_tpl -->
<!-- BEGIN person_list_tpl -->
<td>
<p class="boxtext">{intl-persons}:</p>
<select multiple size="10" name="PersonID[]">
<!-- BEGIN person_select_tpl -->
<option value="{person_id}" {person_is_selected}>{person_name}</option>
<!-- END person_select_tpl -->
</select>
</td>
<!-- END person_list_tpl -->
</tr>
</table>
<br />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Back" value="{intl-back}" />
</form>
