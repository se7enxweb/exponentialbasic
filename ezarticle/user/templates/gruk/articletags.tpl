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
<br clear="all"><table width="{image_width}" align="{image_alignment}" border="0" cellspacing="0" cellpadding="4">
<tr>
<td>
   <!-- BEGIN image_link_tpl -->
   <a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/article/{view_mode}/{article_id}/">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END image_link_tpl -->
   <!-- BEGIN ext_link_tpl -->
   <a href="{www_dir}{index}{image_href}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END ext_link_tpl -->
   <!-- BEGIN no_link_tpl -->
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
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

<!-- BEGIN image_float_tpl -->
   <!-- BEGIN image_link_float_tpl -->
   <a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/article/{view_mode}/{article_id}/">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END image_link_float_tpl -->
   <!-- BEGIN ext_link_float_tpl -->
   <a href="{www_dir}{index}{image_href}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END ext_link_float_tpl -->
   <!-- BEGIN no_link_float_tpl -->  
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   <!-- END no_link_float_tpl -->
<!-- END image_float_tpl -->

<!-- BEGIN link_tpl -->
<a href="{www_dir}{index}{href}">{link_text}</a>
<!-- END link_tpl -->

<!-- BEGIN eksternlink_tpl -->
<a href="{www_dir}{index}{href}" target="_blank">{link_text}</a>
<!-- END eksternlink_tpl -->

<!-- BEGIN bold_tpl -->
<b>{contents}</b>
<!-- END bold_tpl -->

<!-- BEGIN italic_tpl -->
<i>{contents}</i>
<!-- END italic_tpl -->

<!-- BEGIN underline_tpl -->
<u>{contents}</u>
<!-- END underline_tpl -->

<!-- BEGIN strong_tpl -->
<font color="885522" ><strong>{contents}</strong></font>
<!-- END strong_tpl -->

<!-- BEGIN factbox_tpl -->
<table bgcolor="#555555" width="250" align="right" cellspacing="2" cellpadding="2" >
<tr>
	<td bgcolor="#eeeeee" >
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
	<pre>
	{contents}
	</pre>
	</td>
</tr>
</table>
<!-- END pre_tpl -->

<!-- BEGIN media_tpl -->
<embed src="{www_dir}{media_uri}" {attribute_string} />
<!-- END media_tpl -->


<!-- BEGIN table_tpl -->
<br clear="all" />
<table width="{table_width}" >
<tr>
<td bgcolor="#aaaaaa">
<table width="100%" border="{table_border}" cellpadding="2" cellspacing="2">
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
