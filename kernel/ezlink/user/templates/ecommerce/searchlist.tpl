<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp;<a href="{www_dir}{index}/search/?SearchText={query_string}">{intl-results}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">

<h1 class="mainHeading">{intl-search}</h1>

<br />

<h2>Search for: "{query_string}"</h2>

<!-- BEGIN empty_result_tpl -->
<h2>{intl-empty_result}</h2>
<!-- END empty_result_tpl -->

<br />

<table width="90%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN search_item_tpl -->
<tr>
     <td bgcolor="{bg_color}">
     <b><a href="{www_dir}{index}/link/gotolink/addhit/{link_id}/?Url={link_url}">{link_title}</a></b> ( {intl-max} {link_hits} )<br>
     {link_description}<br><br>
     </td>
</tr>
<!-- END search_item_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<br />
<table width="90%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/link/search/parent/{query_string}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/link/search/parent/{query_string}/{item_index}">{type_item_name}</a>&nbsp;
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
	|&nbsp;<a class="path" href="{www_dir}{index}/link/search/parent/{query_string}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
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
</div>