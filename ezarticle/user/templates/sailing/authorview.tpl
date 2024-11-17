<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
   <td colspan="3">
	  <center><span class="h3">The World of Sailing World</span></center><br>
   </td>
</tr>
  <tr>
    <td bgcolor="#006699" width="9"><img src="{www_dir}/sitedesign/sailing/images/leftrounded.gif" width="9" height="20" hspace="0" vspace="0" border="0" align="left" alt=""></td>
    <td bgcolor="#006699" width="100%"><b class="white">{intl-author_info}</b></td>
    <td width="70"><img src="{www_dir}/sitedesign/sailing/images/rightrounded.gif" width="70" height="20" hspace="0" vspace="0" border="0" align="right" alt=""></td>
  </tr>

</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	{intl-author_name}:
	</td>
	<td>
	<a href="mailto:{author_mail}">{author_firstname} {author_lastname}</a>
	</td>
</tr>
</table>

<p>{intl-article_info}</p>

<br>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td bgcolor="#006699" width="9"><img src="{www_dir}/sitedesign/sailing/images/leftrounded.gif" width="9" height="20" hspace="0" vspace="0" border="0" align="left" alt=""></td>
    <td bgcolor="#006699" width="100%"><b class="white">{intl-head_line}{author_firstname} {author_lastname} ({article_start}-{article_end}/{article_count})</b></td>
    <td width="70"><img src="{www_dir}/sitedesign/sailing/images/rightrounded.gif" width="70" height="20" hspace="0" vspace="0" border="0" align="right" alt=""></td>
  </tr>

</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/name">{intl-name}</a>:</th>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/category">{intl-category}</a>:</th>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/author">{intl-author}</a>:</th>
	<th><a href="{www_dir}{index}/article/author/view/{author_id}/published">{intl-published}</a>:</th>
</tr>

<!-- BEGIN article_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
	</td>
	<td class="{td_class}">
	<a href="{www_dir}{index}/article/archive/{category_id}/">{article_category}</a>
	</td>
	<td class="{td_class}">
	{author_name}
	</td>
	<td class="{td_class}">
	{article_published}
	</td>
</tr>
<!-- END article_item_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>&nbsp;|&nbsp;</td>
	<td>
	<a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_index}">{type_item_name}</a>
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
	| <a class="path" href="{www_dir}{index}/article/author/view/{author_id}/{sort}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>&nbsp;</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
