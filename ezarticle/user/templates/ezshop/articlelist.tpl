<!-- BEGIN header_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <td bgcolor="#f08c00"  width="100%">
        <strong class="h1"><img src="{www_dir}/images/1x1.gif" width="3" height="1" border="0">eZ systems Web Shop</strong>
        </td>
</tr>
</table>
<!-- END header_item_tpl -->
<br />
<p>
Vi vil tilby produkter og tjenester av høy kvalitet. Kontak oss hvis du har noen synspunkter om
hvordan vi kan gjøre denne tjenesten bedre for deg.
</p>

<p>
Du kan begynne å handle ved å trykke på "Produkter". Vårt utvalg vil vokse i tiden framover.
</p>



<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->


<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<tr>
	<td>
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
	        <td bgcolor="#c0c0c0" width="100%">
		<a href="{www_dir}{index}/article/articleview/{article_id}/">
                <strong class="h2"><img src="{www_dir}/images/1x1.gif" width="3" height="1" border="0">&nbsp;{article_name}
                </strong>
		</a>
                </td>
        </tr>
        </table>

	<!-- BEGIN article_image_tpl -->
	    <table align="right" width="{thumbnail_image_width}">
	        <tr>
			<td>
			<a href="{www_dir}{index}/article/articleview/{article_id}/">
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
        <!-- END article_image_tpl -->


	<p>
	{article_intro}
	</p>
	<img src="{www_dir}/ezarticle/user/{image_dir}/path-arrow.gif" height="10" width="10" border="0" alt="">
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/">
	{article_link_text}
	</a>
	<br />
	<br />
	<br />
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->




