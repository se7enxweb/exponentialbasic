<h1>{intl-consultation_list_headline} {contact_name}</h1>
<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_consultations_item_tpl -->
<p>{intl-consultation_no_consultations}:</p>
<!-- END no_consultations_item_tpl -->


<!-- BEGIN consultation_table_item_tpl -->

<!-- BEGIN new_person_consultation_item_tpl -->
<form method="post" action="{www_dir}{index}/contact/consultation/person/new/{person_id}">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="30%"><a href="{www_dir}{index}/contact/consultation/person/list/{person_id}/?OrderBy=Date">
                        {intl-consultation_date}:</a></th>
	<th><a href="{www_dir}{index}/contact/consultation/person/list/{person_id}/?OrderBy=Description">
                        {intl-consultation_short_description}:</a></th>
	<th><a href="{www_dir}{index}/contact/consultation/person/list/{person_id}/?OrderBy=Status">
                        {intl-consultation_status}:</a></th>
	<th colspan="2">&nbsp;</th>
</tr>
<!-- END new_person_consultation_item_tpl -->
<!-- BEGIN new_company_consultation_item_tpl -->
<form method="post" action="{www_dir}{index}/contact/consultation/company/new/{company_id}">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="30%"><a href="{www_dir}{index}/contact/consultation/company/list/{company_id}/?OrderBy=Date">
                        {intl-consultation_date}:</a></th>
	<th><a href="{www_dir}{index}/contact/consultation/company/list/{company_id}/?OrderBy=Description">
                        {intl-consultation_short_description}:</a></th>
	<th><a href="{www_dir}{index}/contact/consultation/company/list/{company_id}/?OrderBy=Status">
                        {intl-consultation_status}:</a></th>
	<th colspan="2">&nbsp;</th>
</tr>
<!-- END new_company_consultation_item_tpl -->

<!-- BEGIN consultation_item_tpl -->
<tr class="{bg_color}">
	<td class="small">
        {consultation_date}
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/view/{consultation_id}">{consultation_short_description}</a>
	</td>
	<td>
        <a href="{www_dir}{index}/contact/consultation/view/{consultation_status_id}">{consultation_status}</a>
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/edit/{consultation_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{consultation_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{consultation_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>

	<td width="1%">
	<input type="checkbox" name="ConsultationList[]" value="{consultation_id}">
	</td>	

</tr>
<!-- END consultation_item_tpl -->
</table>

<hr noshade="noshade" size="4" />

<table>
<tr>
<td>
<input class="stdbutton" type="submit" name="New" value="{intl-new_consultation}">
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_consultations}">
</form>
</td>
</tr>
</table>

<!-- END consultation_table_item_tpl -->
<table>
<tr>
<td>
<form method="post" action="{www_dir}{index}/contact/consultation/list">
<input class="stdbutton" type="submit" value="{intl-back}">
</form>
</td>
</tr>
</table>
