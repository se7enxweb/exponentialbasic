<!-- BEGIN list_tpl -->
<form action="{www_dir}{index}/contact/search/company/" method="get">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td rowspan="2" valign="bottom">
	    <h1>{intl-headline_list}</h1>
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
<!-- BEGIN view_tpl -->
<h1>{intl-headline_view}</h1>
<!-- END view_tpl -->

<!-- BEGIN path_tpl -->

<hr noshade="noshade" size="4" />

<img src="{www_dir}/design/admin/images/path-arrow.gif" height="10" width="12" border="0" alt="" />

<a class="path" href="{www_dir}{index}/contact/{command_type}/list/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="{www_dir}/design/admin/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/contact/{command_type}/list/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />
<!--
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="right">
<table>
<tr>
	<td align="right">
	{intl-new_consultation}
	</td>
	<td>
	<img src="{www_dir}/design/admin/images/addmini.gif">
	</td>
</tr>
<tr>
	<td align="right">
	{intl-edit_person}
	</td>
	<td>
	<img src="{www_dir}/design/admin/images/redigermini.gif">
	</td>
</tr>
<tr>
	<td align="right">
	{intl-delete_person}
	</td>
	<td>
	<img src="{www_dir}/design/admin/images/slettmini.gif">
	</td>
</tr>
</table>
	</td>
</tr>
</table>
-->
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->
<!-- <h2>{current_name}</h2>
<p>{current_description}</p> -->
<!-- BEGIN image_item_tpl -->
<!-- <p class="boxtext">{intl-th_type_current_image}:</p> -->
<p><img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" /></p>
<!-- END image_item_tpl -->
<!-- END current_type_tpl -->

<!-- BEGIN not_root_tpl -->
<!-- <p><a href="{www_dir}{index}/contact/{command_type}/edit/{current_id}">{intl-button_edit}</a></p> -->
<!-- END not_root_tpl -->

<!-- BEGIN category_list_tpl -->

<h2>{intl-headline_categories}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th><a href="{www_dir}{index}/contact/{command_type}/list/{current_id}?OrderBy=Name">{intl-th_type_name}:</a></th>
    <th><a href="{www_dir}{index}/contact/{command_type}/list/{current_id}?OrderBy=Description">{intl-th_type_description}:</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

</table>
<!-- END category_list_tpl -->

<!-- BEGIN no_category_item_tpl -->
<!-- <h2>{intl-headline_no_categories}</h2>
{intl-error_no_categories} -->
<!-- END no_category_item_tpl -->


<!-- BEGIN type_list_tpl -->
<h2>{intl-headline_types}</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th><a href="{www_dir}{index}/contact/{command_type}/list/{current_id}?OrderBy=Name">{intl-th_type_name}:</a></th>
    <th><a href="{www_dir}{index}/contact/{command_type}/list/{current_id}?OrderBy=Description">{intl-th_type_description}:</a></th>
    <th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN type_item_tpl -->
<tr class="{theme-type_class}">
    <td><a href="{www_dir}{index}/contact/{command_type}/list/{type_id}/">{type_name}</a></td>
    <td>{type_description}</td>

    <!-- BEGIN type_edit_button_tpl -->
    <td width="1%"><a href="{www_dir}{index}/contact/companycategory/edit/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezuser{type_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a></td>
    <!-- END type_edit_button_tpl -->

    <!-- BEGIN type_delete_button_tpl -->
    <td width="1%"><a href="{www_dir}{index}/contact/companycategory/delete/{type_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{type_id}-slett','','{www_dir}/design/base/images/icons/slettminimrk.gif',1)"><img name="ezuser{type_id}-slett" border="0" src="{www_dir}/design/admin/images/slettmini.gif" width="16" height="16" align="top" alt="Delete" /></a></td>
</tr>
    <!-- END type_delete_button_tpl -->

<!-- END type_item_tpl -->

</table>
<!-- END type_list_tpl -->

<!-- BEGIN no_type_item_tpl -->
<h2>{intl-headline_no_types}</h2>
{intl-error_no_types}
<!-- END no_type_item_tpl -->


<!-- BEGIN type_new_button_tpl -->
<form method="post" action="{www_dir}{index}/contact/companycategory/new/{current_id}">

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-new_company_category}">
</form>
<!-- END type_new_button_tpl -->

<h2>{intl-companylist_headline}</h2>

<!-- BEGIN no_companies_tpl -->
	<p>{intl-no_companies_error}</p>
<!-- END no_companies_tpl -->

<form method="post" action="{www_dir}{index}/contact/company/edit/{current_id}">
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

	<!-- BEGIN company_buy_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/buy/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{company_id}-red','','/design/admin/images/button-cart-ro.gif',1)"><img name="ezb{company_id}-red" border="0" src="{www_dir}/design/admin/images/button-cart.gif" width="16" height="16" align="top" alt="Buy" /></a>
	</td>
	<!-- END company_buy_button_tpl -->

	<!-- BEGIN company_folder_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/company/folder/{company_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{company_id}-red','','/design/admin/images/button-folder-ro.gif',1)"><img name="ezf{company_id}-red" border="0" src="{www_dir}/design/admin/images/button-folder.gif" width="16" height="16" align="top" alt="Folder" /></a>
	</td>
	<!-- END company_folder_button_tpl -->

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
	<input type="checkbox" name="ContactArrayID[]" value="{company_id}" />
	</td>	
	<!-- END company_delete_button_tpl -->

</tr>
<!-- END company_item_tpl -->
</table>
<!-- END companies_table_tpl -->

<!-- BEGIN company_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/contact/company/list/{current_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/contact/company/list/{current_id}/{item_index}">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/contact/company/list/{current_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END company_list_tpl -->

<!-- BEGIN company_new_button_tpl -->
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewCompany" value="{intl-new_company}" />
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_company}" />
<input class="stdbutton" type="submit" name="SendMail" value="{intl-send_mail}" />

</form>
<!-- END company_new_button_tpl -->
