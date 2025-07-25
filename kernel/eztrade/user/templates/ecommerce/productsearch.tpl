<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp;<a href="{www_dir}{index}/search/?SearchText={query_string}">{intl-results}</a>
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">

<h1 class="mainHeading">{intl-head_line} - ({product_start}-{product_end}/{product_total})</h1>

<br />

<h2>Search for: "{query_string}"</h2>
<br>

<table width="90%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN product_tpl -->
<tr>
	<td class="productlist">

	<div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>
    <!-- BEGIN image_tpl -->
    <table width="1%" align="right" border="0">
    <tr>
        <td>
        <a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
        </td>
    </tr>
    
    <!--
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    -->
    
    </table>
    <!-- END image_tpl -->

<div class="p">{product_intro_text}</div>

	<!-- BEGIN price_tpl -->
	<div class="spacer"><div class="pris">{product_price}</div></div>
	<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->
</table>

<table width="90%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/trade/search/move/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/trade/search/move/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/trade/search/move/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
</div>