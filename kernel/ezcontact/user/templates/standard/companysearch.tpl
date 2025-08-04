<!-- BEGIN list_tpl -->
<form action="{www_dir}{index}/contact/search/company/" method="get">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td rowspan="2" valign="bottom">
	        <h1>{intl-headline_list} '{search_text}'</h1>
	</td>
  	<td align="right">
  	    	<input type="text" name="SearchText" size="12" />
  		<input class="stdbutton" type="submit" value="{intl-search}" />
  	    	<input type="hidden" name="SearchCategory" value="{current_id}" />
  	</td>
</tr>
</table>
</form>

<!-- END list_tpl -->
<hr noshade="noshade" size="4" />

<img src="{www_dir}/design/admin/images/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="{www_dir}{index}/contact/{command_type}/list/0">{intl-root_category}</a>

<hr noshade="noshade" size="4" />

<h2>{intl-companylist_headline}</h2>

<!-- BEGIN no_companies_tpl -->
	<p>{intl-no_companies_error}</p>
<!-- END no_companies_tpl -->

<!-- BEGIN companies_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
	<!-- BEGIN company_stats_header_tpl -->
	<th>{intl-views}:</th>
	<!-- END company_stats_header_tpl -->
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN company_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN company_view_button_tpl -->
	<a href="{www_dir}{index}/contact/company/view/{company_id}">{company_name}</a>
	<!-- END company_view_button_tpl -->
	<!-- BEGIN no_company_view_button_tpl -->
	{company_name}
	<!-- END no_company_view_button_tpl -->
	</td>
	<td class="{td_class}">
	<!-- BEGIN image_view_tpl -->
        <img src="{www_dir}{company_logo_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	<!-- END image_view_tpl -->
	<!-- BEGIN no_image_tpl -->
	&nbsp;
	<!-- <p>{intl-no_image}</p> -->
	<!-- END no_image_tpl -->	
	</td>

	<!-- BEGIN company_stats_item_tpl -->
	<td class="{td_class}">
	<a href="{www_dir}{index}/contact/company/stats/year/{company_id}/">{company_views}</a>
	</td>
	<!-- END company_stats_item_tpl -->

	<!-- BEGIN company_consultation_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/consultation/company/new/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{company_id}-red','','/design/admin/images/addminimrk.gif',1)"><img name="ezn{company_id}-red" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top" alt="Add consultation" /></a>
	</td>
	<!-- END company_consultation_button_tpl -->

	<!-- BEGIN company_edit_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/edit/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{company_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{company_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<!-- END company_edit_button_tpl -->

	<!-- BEGIN company_delete_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/delete/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{company_id}-slett','','{www_dir}/design/base/images/icons/slettminimrk.gif',1)"><img name="ezc{company_id}-slett" border="0" src="{www_dir}design/base/images/icons/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	
	<!-- END company_delete_button_tpl -->

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END companies_table_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td with="50%" align="left">{intl-results}</td>
<td width="50%" align="right">{results}</td></tr>
</table>