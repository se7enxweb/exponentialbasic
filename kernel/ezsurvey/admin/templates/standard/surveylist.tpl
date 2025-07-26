<form action="{www_dir}{index}/survey/surveylist/list/{survey_id}" method="post">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}</h1>
	</td>
</tr>
</table>

<!-- BEGIN survey_list_tpl -->
<table width="100%" class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th>{intl-survey_title}:</th>
	<th>&nbsp;</th>
  <th>{intl-survey_status}:</th>
  <th>{intl-survey_copy}:</th>
  <th>{intl-survey_stats}:</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN survey_item_tpl -->
<tr>
    <td class="{td_class}">
        <a href="{www_dir}{index}/survey/surveyedit/edit/{survey_id}/">{survey_title}</a>
    </td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}">
        {survey_status}
    </td>
    <td class="{td_class}">
        <a href="{www_dir}{index}/survey/surveyedit/copy/{survey_id}/">copia-me</a>
    </td>
    <td class="{td_class}">
      <!-- BEGIN survey_item_stats_tpl -->
      <a href="{www_dir}{index}/survey/stats/{survey_id}/">{intl-view}</a>
      <!-- END survey_item_stats_tpl -->
    </td>
    <td width="1%" class="{td_class}" align="center">
    <input type="checkbox" name="formDelete[]" value="{survey_id}">
    </td>
</tr>
<!-- END survey_item_tpl -->
</table>
<!-- END survey_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="DeleteSelected" value="{intl-delete_selected_surveys}" />

</form>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/survey/surveylist/list/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/survey/surveylist/list/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/survey/surveylist/list/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<hr noshade size="4">
