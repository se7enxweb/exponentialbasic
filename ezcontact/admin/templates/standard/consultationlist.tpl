<h1>{intl-consultation_list_headline}</h1>
<hr noshade="noshade" size="4" />
<br />

<!-- BEGIN no_companies_item_tpl -->
<p>{intl-consultation_no_companies}</p>
<!-- END no_companies_item_tpl -->


<!-- BEGIN company_table_item_tpl -->
<form action="{www_dir}{index}/contact/consultation/company/delete/" method="post">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-company_name}:</th>
	<th>{intl-consultation_count}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN company_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/contact/consultation/company/list/{company_id}">{company_name}&nbsp;</a>
	</td>

	<td width="1%">
        {consultation_count}
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/company/new/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezcc{company_id}-slett','','/design/admin/images/addminimrk.gif',1)"><img name="ezcc{company_id}-slett" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>	

	<td width="1%">
	<input type="checkbox" name="ConsultationList[]" value="{company_id}" />
	</td>	

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END company_table_item_tpl -->

<!-- BEGIN no_persons_item_tpl -->
<p>{intl-consultation_no_persons}</p>
<!-- END no_persons_item_tpl -->

<form action="{www_dir}{index}/contact/consultation/person/delete/" method="post">
<!-- BEGIN person_table_item_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th>{intl-consultation_count}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
        <a href="{www_dir}{index}/contact/consultation/person/list/{person_id}">{person_lastname}, {person_firstname}&nbsp;</a>
	</td>

	<td width="1%">
        {consultation_count}
	</td>

	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezpc{person_id}-slett','','/design/admin/images/addminimrk.gif',1)"><img name="ezpc{person_id}-slett" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>	

	<td width="1%">
	<input type="checkbox" name="ConsultationList[]" value="{person_id}" />
	</td>	

</tr>
<!-- END person_item_tpl -->
</table>
<!-- END person_table_item_tpl -->
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="new_consultation" value="{intl-new_consultation}" />
<input class="stdbutton" type="submit" value="{intl-delete_selected}" />
</form>
