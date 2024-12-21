<form method="post" action="{www_dir}{index}/contact/companyedit/{action_value}/{company_id}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<h3 class="error">{error}</h3>
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="20" name="Name" value="{name}"/>
	</td>
</tr>
</table>

<p class="boxtext">{intl-description}:</p>
<textarea cols="40" rows="8" name="Description">{description}</textarea>

<p class="boxtext">{intl-companytype}:</p>

<select multiple size="10" name="CompanyCategoryID[]">
<!-- BEGIN company_type_select_tpl -->
<option value="{company_type_id}" {is_selected}>{company_type_name}</option>
<!-- END company_type_select_tpl -->
</select>

<hr noshade size="4"/>

<input type="hidden" name="UserID" value="{user_id}" />
<input class="okbutton" type="submit" value="OK" />
</form>

<form method="post" action="{www_dir}{index}/contact/companylist/">
<input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

