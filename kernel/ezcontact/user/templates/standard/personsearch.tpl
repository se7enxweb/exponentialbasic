<!-- BEGIN list_tpl -->
<form action="{www_dir}{index}/contact/search/person/" method="get">
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

<h2>{intl-personlist_headline}</h2>

<!-- BEGIN no_companies_tpl -->
	<p>{intl-no_companies_error}</p>
<!-- END no_companies_tpl -->

<!-- BEGIN companies_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-name}:</th>
	<th>&nbsp;</th>
	<!-- BEGIN person_stats_header_tpl -->
	<th>{intl-views}:</th>
	<!-- END person_stats_header_tpl -->
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr>
	<td class="{td_class}">
	<!-- BEGIN person_view_button_tpl -->
	<a href="{www_dir}{index}/contact/person/view/{person_id}">{person_name}</a>
	<!-- END person_view_button_tpl -->
	<!-- BEGIN no_person_view_button_tpl -->
	{person_name}
	<!-- END no_person_view_button_tpl -->
	</td>
	<td class="{td_class}">
	</td>

	<!-- BEGIN person_stats_item_tpl -->
	<td class="{td_class}">
	<a href="{www_dir}{index}/contact/person/stats/year/{person_id}/">{person_views}</a>
	</td>
	<!-- END person_stats_item_tpl -->

	<!-- BEGIN person_consultation_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/design/admin/images/addminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top" alt="Add consultation" /></a>
	</td>
	<!-- END person_consultation_button_tpl -->

	<!-- BEGIN person_edit_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/person/edit/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{person_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<!-- END person_edit_button_tpl -->

	<!-- BEGIN person_delete_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/person/delete/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-slett','','{www_dir}/design/base/images/icons/slettminimrk.gif',1)"><img name="ezc{person_id}-slett" border="0" src="{www_dir}design/base/images/icons/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a>
	</td>	
	<!-- END person_delete_button_tpl -->

</tr>
<!-- END person_item_tpl -->
</table>
<!-- END companies_table_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td with="50%" align="left">{intl-results}</td>
<td width="50%" align="right">{results}</td></tr>
</table>