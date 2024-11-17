<form action="{www_dir}{index}/contact/search/person/" method="get">
<table width="100%" border="0">
<tr>
	<td rowspan="2" valign="bottom">
	<h1>{intl-person_list_headline}</h1>
	</td>
	<td align="right">
	    <input type="text" name="SearchText" size="12" value="{search_form_text}" />
		<input class="stdbutton" type="submit" value="{intl-search}" />
	</td>
</tr>
</table>
</form>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/contact/person/list/" method="post">
	<span class="boxtext">{intl-show_persons}:</span>
    <select name="LimitType">
	    <option value="all" {is_all_selected}>{intl-show_all}</option>
	    <option value="standalone" {is_without_selected}>{intl-without_relation}</option>
	    <option value="connected" {is_with_selected}>{intl-with_relation}</option>
	</select>
    <input class="stdbutton" type="submit" value="{intl-reload}" />
</form>

<hr noshade="noshade" size="4" />
<br />

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


<!-- BEGIN no_persons_tpl -->
<h2>{intl-no_persons_found}</h2>
<!-- END no_persons_tpl -->

<form method="post" action="{www_dir}{index}/contact/person/edit/">
<!-- BEGIN person_table_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-person_name}:</th>
	<th>{intl-state}:</th>
	<th colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN person_item_tpl -->
<tr class="{bg_color}">
	<td>
	<!-- BEGIN person_view_button_tpl -->
        <a href="{www_dir}{index}/contact/person/view/{person_id}">{person_lastname}, {person_firstname}</a>
	<!-- END person_view_button_tpl -->
	<!-- BEGIN no_person_view_button_tpl -->
        {person_lastname}, {person_firstname}
	<!-- END no_person_view_button_tpl -->
	</td>

	<!-- BEGIN person_state_tpl -->
	<td>
        {person_state}
	</td>
	<!-- END person_state_tpl -->

	<!-- BEGIN no_person_state_tpl -->
	<td>
        {intl-no_state}
	</td>
	<!-- END no_person_state_tpl -->

        <!-- BEGIN image_item_tpl -->
        <td class="{td_class}">
        <img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
        </td>
        <!-- END image_item_tpl --> 

	<!-- BEGIN person_buy_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/person/buy/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezb{person_id}-red','','/design/admin/images/button-cart-ro.gif',1)"><img name="ezb{person_id}-red" border="0" src="{www_dir}/design/admin/images/button-cart.gif" width="16" height="16" align="top" alt="Buy" /></a>
	</td>
	<!-- END person_buy_button_tpl -->

	<!-- BEGIN person_folder_button_tpl -->
	<td class="{td_class}" width="1%">
	<a href="{www_dir}{index}/contact/person/folder/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezf{person_id}-red','','/design/admin/images/button-folder-ro.gif',1)"><img name="ezf{person_id}-red" border="0" src="{www_dir}/design/admin/images/button-folder.gif" width="16" height="16" align="top" alt="Folder" /></a>
	</td>
	<!-- END person_folder_button_tpl -->

	<!-- BEGIN person_consultation_button_tpl -->
	<td width="1%">
	<a href="{www_dir}{index}/contact/consultation/person/new/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezn{person_id}-red','','/design/admin/images/addminimrk.gif',1)"><img name="ezn{person_id}-red" border="0" src="{www_dir}/design/admin/images/addmini.gif" width="16" height="16" align="top"></a>
	</td>
	<!-- END person_consultation_button_tpl -->

	<!-- BEGIN person_edit_button_tpl -->
	<td width="1%">
	<a href="{www_dir}{index}/contact/person/edit/{person_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezc{person_id}-red','','/design/admin/images/redigerminimrk.gif',1)"><img name="ezc{person_id}-red" border="0" src="{www_dir}/design/admin/images/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<!-- END person_edit_button_tpl -->

	<!-- BEGIN person_delete_button_tpl -->
	<td width="1%">
	<input type="checkbox" name="ContactArrayID[]" value="{person_id}" />
	</td>	
	<!-- END person_delete_button_tpl -->

</tr>
<!-- END person_item_tpl -->
</table>

<!-- BEGIN person_list_tpl -->
<table>
<tr>
	<!-- BEGIN person_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/contact/person/{action}/{item_previous_index}/{search_text}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
	</td>
	<!-- END person_list_previous_tpl -->

	<!-- BEGIN person_list_previous_inactive_tpl -->
	<td>
	|
	</td>
	<!-- END person_list_previous_inactive_tpl -->

	<!-- BEGIN person_list_item_list_tpl -->

	<!-- BEGIN person_list_item_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/contact/person/{action}/{item_index}/{search_text}">{item_name}</a>&nbsp;|
	</td>
	<!-- END person_list_item_tpl -->

	<!-- BEGIN person_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;&nbsp;{item_name}&nbsp;&gt;&nbsp;|
	</td>
	<!-- END person_list_inactive_item_tpl -->

	<!-- END person_list_item_list_tpl -->

	<!-- BEGIN person_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/contact/person/{action}/{item_next_index}/{search_text}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END person_list_next_tpl -->

	<!-- BEGIN person_list_next_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END person_list_next_inactive_tpl -->

</tr>
</table>
<!-- END person_list_tpl -->

<!-- END person_table_tpl -->
<!-- BEGIN person_new_button_tpl -->
<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="NewPerson" value="{intl-new_person}">
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_person}" />
<input class="stdbutton" type="submit" name="SendMail" value="{intl-send_mail}" />
</form>
<!-- END person_new_button_tpl -->
