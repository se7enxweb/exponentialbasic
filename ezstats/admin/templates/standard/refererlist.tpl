<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	    <h1>{intl-referer_list}</h1>
	</td>
	<td align="right">
	    <form action="{www_dir}{index}/stats/refererlist/{view_mode}/{view_limit}" method="post">
	        <span class="boxtext">{intl-exclude_domain}:</span>
	        <input type="text" size="12" value="" name="ExcludeDomain" />
	        <input class="stdbutton" type="submit" value="{intl-ok}" />
	    </form>
	</td>
</tr>
<tr>
	<td colspan="2"><span class="boxtext">({item_start}-{item_end}/{item_count})</span></td>
</tr>
</table>

<hr noshade size="4" />

<!-- BEGIN referer_list_tpl -->

<table class="list" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
	    <th valign="bottom">
            {intl-referer_domain}:
        </th>
	    <th valign="bottom">
            {intl-referer_uri}:
        </th>
	    <td align="right">
            <b>{intl-page_view_count}:</b>
        </td>
    </tr>
    <!-- BEGIN referer_tpl -->
    <tr class="{bg_color}">
	    <td>
	        <a target="_blank" href="http://{referer_domain}{referer_uri}">{referer_domain}</a>
	    </td>
	    <td>
	        {referer_uri}
	    </td>
	    <td align="right">
	        {page_view_count}
	    </td>
    </tr>
    <!-- END referer_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/stats/refererlist/top/{item_limit}/{item_previous_index}/{exclude_domain}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="{www_dir}{index}/stats/refererlist/top/{item_limit}/{item_index}/{exclude_domain}">{type_item_name}</a>
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	&lt;&nbsp;{type_item_name}&nbsp;&gt;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	| <a class="path" href="{www_dir}{index}/stats/refererlist/top/{item_limit}/{item_next_index}/{exclude_domain}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->



<!-- END referer_list_tpl -->

