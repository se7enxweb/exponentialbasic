        <table width="100%" height="100" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1%" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td width="98%" valign="top" class="tdmini"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="1" height="14" hspace="0" vspace="0" /><br /></td>
            <td rowspan="3" valign="top" width="1%" align="right"><a href="{www_dir}{index}/tema/bildegalleri"><img src="{www_dir}/sitedesign/percolo/images/tittelbilde.gif" alt="Bygg mer enn hus..." width="140" height="100" border="0" /></a><br /></td>
        </tr>
        <tr>
            <td bgcolor="#009ebf" valign="top"><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="32" hspace="0" vspace="0" /><br /></td>
            <td bgcolor="#009ebf" valign="top"><h1 class="sidetittel">Butikk</h1></td>
        </tr>
        <tr>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
            <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="50" height="60" hspace="0" vspace="0"></td>
        </tr>
		</table>
        <table width="100%" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0">
		<tr>
		    <td><img src="{www_dir}/sitedesign/percolo/images/onepix.gif" alt="luft" width="14" height="14" hspace="0" vspace="0" /><br /></td>
			<td colspan="2">
	
<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_tpl -->

<!-- END category_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<div class="listproducts"><a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">{product_name}</a></div>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
        </td>
    </tr>
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
<!-- END product_image_tpl -->

<div class="p">{product_intro_text}</div>

<table cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN price_tpl -->
<tr>
	<td>
	<div class="pris">{product_price}</div>
	</td>
</tr>
<!-- END price_tpl -->
<tr>
	<td>
	<a class="path" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">Les mer om dette produktet</a>
	</td>
</tr>
</table>
<br />
	</td>
</tr>
<!-- END product_tpl -->
</table>

<!-- END product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<div class="listheadline"><a class="listheadline" href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">Kjøpsinformasjon</a></div>

	<div class="p">Her kan du få mer informasjon om hvordan du handler fra vår nettbutikk.</div>
	<img src="{www_dir}/images/1x1.gif" height="8" width="1" border="0" alt="" /><br />
	<a class="path" href="{www_dir}{index}/article/articleview/14/1/{category_id}/">Les mer om dine rettigheter</a>
	<br /><br />
	</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
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
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>

</table>


</td>
</tr>
</table>
