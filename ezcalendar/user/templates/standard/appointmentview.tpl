<h1>{intl-appointment_view}</h1>

<!-- BEGIN error_tpl -->
<p class="error">{intl-error}</p>
<!-- END error_tpl -->

<hr noshade size="4" />
<!-- BEGIN view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	<h2>{appointment_title}</h2>
	</th>
	<td width="10%" align="right">
	<!-- BEGIN private_tpl -->
	<b><i>{intl-private}</i></b>
	<!-- END private_tpl -->
	<!-- BEGIN public_tpl -->
	<b><i>{intl-public}</i></b>
	<!-- END public_tpl -->
	</td>
</tr>
</table>
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="50%">
	{intl-type}:
	</th>
	<th width="50%">
	{intl-priority}:
	</th>
</tr>
<tr>
	<td>
	{appointment_type}
	</td>
	<td>
	<!-- BEGIN low_tpl -->
	{intl-low}
	<!-- END low_tpl -->
	<!-- BEGIN normal_tpl -->
	{intl-normal}
	<!-- END normal_tpl -->
	<!-- BEGIN high_tpl -->
	{intl-high}
	<!-- END high_tpl -->
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<th>
	{intl-date}:
	</th>
	<th>
	{intl-time}:
	</th>
</tr>
<tr>
	<td>
	{appointment_date}
	</td>
	<td>
	{appointment_starttime} - {appointment_stoptime}
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<th colspan="2">
	{intl-description}:
	</th>
</tr>
<tr>
	<td colspan="3" class="bglight">

	<table width="100%" cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td>
		{appointment_description}
		</td>
	</tr>
	</table>
	
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

<tr>
	<th colspan="2">
	{intl-created_by}:
	</th>
</tr>
<tr>
	<td colspan="2">
	{appointment_owner}
	</td>
</tr>

<tr><td colspan="2"><br /></td></tr>

</table>

<hr noshade size="4" />
<form action="{www_dir}{index}/calendar/appointmentedit/edit/{appointment_id}">
<input class="stdbutton" type="submit" value="{intl-edit_appointment}">
</form>
<hr noshade size="4" />
<!-- END view_tpl -->

<form action="{www_dir}{index}/calendar/appointmentedit/edit/">
<input class="stdbutton" type="submit" name="GoDay" value="{intl-day}">
<input class="stdbutton" type="submit" name="GoMonth" value="{intl-month}">
<input class="stdbutton" type="submit" name="GoYear" value="{intl-year}">
<input class="stdbutton" type="submit" name="GoToday" value="{intl-today}">
</form>

