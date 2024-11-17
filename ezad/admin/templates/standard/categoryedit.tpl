<form method="post" action="{www_dir}{index}/ad/category/{action_value}/{category_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" class="box" size="40" name="Name" value="{name_value}"/>
	</td>	
</tr>
</table>

<br />

<p class="boxtext">{intl-place}:</p>
<select name="ParentID">
<option value="0">{intl-categoryroot}</option>

<!-- BEGIN value_tpl -->
<option {selected} value="{option_value}">{option_level}{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-description}:</p>
<textarea class="box" rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<hr noshade="noshade" size="4" />

    <input type="hidden" name="CategoryID" value="{category_id}" />
    <input class="okbutton" type="submit" value="OK" />
    <input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
