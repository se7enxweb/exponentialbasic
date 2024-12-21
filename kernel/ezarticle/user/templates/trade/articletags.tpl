<!-- BEGIN header_1_tpl -->
<h2>{contents}</h2>
<!-- END header_1_tpl -->

<!-- BEGIN header_2_tpl -->
<h3>{contents}</h3>
<!-- END header_2_tpl -->

<!-- BEGIN header_3_tpl -->
<h3>{contents}</h3>
<!-- END header_3_tpl -->

<!-- BEGIN header_4_tpl -->
<h3>{contents}</h3>
<!-- END header_4_tpl -->

<!-- BEGIN header_5_tpl -->
<h3>{contents}</h3>
<!-- END header_5_tpl -->

<!-- BEGIN header_6_tpl -->
<h3>{contents}</h3>
<!-- END header_6_tpl -->

<!-- BEGIN image_tpl -->
{map_string}
<br clear="all"><table width="{image_width}" align="{image_alignment}" border="0" cellspacing="0" cellpadding="4">
<tr>
<td>
   <!-- BEGIN image_link_tpl -->
   <a target="{target}" href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL={referer_url}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END image_link_tpl -->
   <!-- BEGIN ext_link_tpl -->
   <a target="{target}" href="{image_href}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END ext_link_tpl -->
   <!-- BEGIN no_link_tpl -->
   <img src="{www_dir}{image_url}" {map_name} border="0" width="{image_width}" height="{image_height}" alt="" />
   <!-- END no_link_tpl -->
</td>
</tr>
<!-- BEGIN image_text_tpl -->
<tr>
   <td class="pictext">
    {caption}
   </td>
</tr>
<!-- END image_text_tpl -->
</table>
<!-- END image_tpl -->

<!-- BEGIN rollover_tpl -->
<!-- variables which are not used: {image_one_width} and {image_one_height} -->
<a href="{url}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezrollover_{rollover_id}','','{image_one_url}',1)"><img name="ezrollover_{rollover_id}" border="0" src="{image_two_url}" width="{image_two_width}" height="{image_two_height}" align="top" border="0" alt="{link_text}" /></a>
<!-- END rollover_tpl -->

<!-- BEGIN image_float_tpl -->
   <!-- BEGIN image_link_float_tpl -->
   <a target="{target}" href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL={referer_url}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" /></a>   
   <!-- END image_link_float_tpl -->
   <!-- BEGIN ext_link_float_tpl -->
   <a href="{www_dir}{index}{image_href}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" /></a>   
   <!-- END ext_link_float_tpl -->
   <!-- BEGIN no_link_float_tpl -->  
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   <!-- END no_link_float_tpl -->
<!-- END image_float_tpl -->

<!-- BEGIN link_tpl -->
<a href="{href}" target="{target}" >{link_text}</a>
<!-- END link_tpl -->

<!-- BEGIN popuplink_tpl -->
<a href="{href}" target="_new" >{link_text}</a>
<!-- END popuplink_tpl -->


<!-- BEGIN bold_tpl -->
<b>{contents}</b>
<!-- END bold_tpl -->

<!-- BEGIN italic_tpl -->
<i>{contents}</i>
<!-- END italic_tpl -->

<!-- BEGIN underline_tpl -->
<u>{contents}</u>
<!-- END underline_tpl -->

<!-- BEGIN strike_tpl -->
<s>{contents}</s>
<!-- END strike_tpl -->

<!-- BEGIN strong_tpl -->
<font color="885522" ><strong>{contents}</strong></font>
<!-- END strong_tpl -->

<!-- BEGIN factbox_tpl -->
<table width="250" align="right" cellspacing="4" cellpadding="4" >
<tr>
	<td class="bglight">
	{contents}
	</td>
</tr>
</table>
<!-- END factbox_tpl -->

<!-- BEGIN bullet_tpl -->
<ul>
	<!-- BEGIN bullet_item_tpl -->
	<li>
	{contents}
	</li>
	<!-- END bullet_item_tpl -->
</ul>
<!-- END bullet_tpl -->

<!-- BEGIN list_tpl -->
<ol>
	<!-- BEGIN list_item_tpl -->
	<li>
	{contents}
	</li>
	<!-- END list_item_tpl -->
</ol>
<!-- END list_tpl -->

<!-- BEGIN quote_tpl -->
<blockquote>
{contents}
</blockquote>
<!-- END quote_tpl -->

<!-- BEGIN pre_tpl -->
<table width="100%" bgcolor="#eeeeee" >
<tr>
	<td>
	<pre>{contents}</pre>
	</td>
</tr>
</table>
<!-- END pre_tpl -->

<!-- BEGIN html_tpl -->
{contents}
<!-- END html_tpl -->

<!-- BEGIN media_tpl -->
<embed src="{www_dir}{media_uri}" {attribute_string} />
<!-- END media_tpl -->

<!-- BEGIN file_tpl -->
<a href="{www_dir}{file_uri}">{text}</a>
<!-- END file_tpl -->


<!-- BEGIN table_tpl -->
<br clear="all" />
<table width="{table_width}" cellpadding="0" cellspacing="0">
<tr>
<td class="bgdark" valign="top">
<table width="100%" cellpadding="2" cellspacing="{table_border}">
<!-- BEGIN tr_tpl -->
<tr>
<!-- BEGIN td_tpl -->
    <td width="{td_width}" colspan="{td_colspan}" rowspan="{td_rowspan}" valign="top"  bgcolor="#ffffff">
    {contents}
    </td>
<!-- END td_tpl -->
</tr>
<!-- END tr_tpl -->
</table>
</td>
</tr>
</table>
<!-- END table_tpl -->

<!-- BEGIN logo_tpl -->
<a href="developer.ez.no">eZ publish</a>{contents}
<!-- END logo_tpl -->


<!-- BEGIN cleft_tpl -->
<table width="100%"> 
<tr>
<td valign="top">
{contents}
<!-- END cleft_tpl -->

<!-- BEGIN cbreak_tpl -->
</td>
<td valign="top">
<!-- END cbreak_tpl -->

<!-- BEGIN cright_tpl -->
{contents}
</td>
</tr>
</table>
<!-- END cright_tpl -->
