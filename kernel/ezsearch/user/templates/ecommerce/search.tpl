<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp;
</div>
<!-- end #breadcrumbs -->

<!-- start #contentWrap -->
<div id="contentWrap">


<h1 class="mainHeading">{intl-site_search}</h1>

<h2>{intl-search_for}: "{search_text}"</h2>

<!-- BEGIN search_type_tpl -->
<table class="list" cellpadding="4" cellspacing="0" border="0">
<tr>
	<th colspan="2">
	{intl-search_in_module}:&nbsp;{module_name}
	</th>
</tr>
<!-- BEGIN search_sub_module_tpl -->
<tr>
        <th colspan="2">
<!-- BEGIN search_sub_module_name_tpl -->
        {sub_module_name}<br />
<!-- END search_sub_module_name_tpl -->
	{intl-search_count}:&nbsp;{search_count}
	</th>
</tr>
<!-- BEGIN search_item_tpl -->
<tr>
	<td class="{td_class}" width="1%">
	<img src="{www_dir}{icon_src}" width="16" height="16" alt="" border="0" />
	</td>
	<td class="{td_class}" width="99%">
	<a href="{www_dir}{index}{search_link}">{search_name}</a>
	</td>
</tr>
<!-- END search_item_tpl -->
<tr>
	<td colspan="2">
	{intl-full_search}:&nbsp;<a href="{www_dir}{index}{search_more_link}">{intl-click_here}</a>
	</td>
</tr>
<!-- END search_sub_module_tpl -->
</table>
<!-- END search_type_tpl -->

</div>
<!-- end #contentWrap -->
