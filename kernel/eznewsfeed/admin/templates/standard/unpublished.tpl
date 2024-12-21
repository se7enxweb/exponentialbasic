<table width="100%" border="0">
<tr>
	<td valign="bottom">
	<h1>{intl-head_line}<!-- - {current_category_name} --></h1>
	</td>
	<td rowspan="2" align="right">
	<form action="{www_dir}{index}/newsfeed/search/" method="post">
	<input type="text" name="SearchText" size="12" />	
	<input class="stdbutton" type="submit" value="{intl-search}" />
	</form>	
	</td>
</tr>
<tr>
	<td>{current_category_description}</td>
</tr>
</table>

<form method="post" action="{www_dir}{index}/newsfeed/unpublished/{current_category_id}">

<hr noshade="noshade" size="4" />

<!-- BEGIN path_tpl -->


<img src="{www_dir}/design/admin/images/{site_style}/path-arrow.gif" height="10" width="12" border="0" alt="" /> 

<a class="path" href="{www_dir}{index}/newsfeed/unpublished/0/">{intl-top_category}</a>

<!-- END path_tpl -->

<!-- BEGIN path_item_tpl -->

<img src="{www_dir}/design/admin/images/{site_style}/path-slash.gif" height="10" width="16" border="0" alt="" />

<a class="path" href="{www_dir}{index}/newsfeed/unpublished/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</td>
	<th>{intl-description}:</th>
	<th colspan="2">&nbsp;</th>
</tr>
	
<!-- BEGIN category_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/newsfeed/unpublished/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	  {category_description}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	  <a href="{www_dir}{index}/newsfeed/categoryedit/edit/{category_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{category_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{category_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	  <input type="checkbox" name="CategoryArrayID[]" value="{category_nr}">
	</td>
</tr>
<!-- END category_item_tpl -->
</table>

<!-- END category_list_tpl -->

<!-- BEGIN news_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th width="50%">{intl-news}:</th>
	<th width="47%">{intl-news_origin}:</th>
	<th width="1%">{intl-news_date}:</th>
	<th width="1%">{intl-publish}:</th>
	<th width="1%" colspan="2">&nbsp;</th>
</tr>

<!-- BEGIN news_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/newsfeed/news/edit/{news_id}/">
	{news_name}
	</a>
	</td>

	<td class="{td_class}">
	{news_origin}&nbsp;
	</td>
	<td width="1%" class="{td_class}">
	<span class="small">{news_date}&nbsp;</span>
	</td>

	<td width="1%" class="{td_class}">
	<input type="checkbox" name="NewsPublishIDArray[]" value="{news_id}" />
	</td>

	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/newsfeed/news/edit/{news_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{news_id}-red','','{www_dir}/design/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezaa{news_id}-red" border="0" src="{www_dir}/design/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="NewsDeleteIDArray[]" value="{news_id}" />
	</td>
</tr>
<!-- END news_item_tpl -->

</table>
<!-- END news_list_tpl -->


<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td width="50%">
<!-- BEGIN previous_tpl -->
<a href="{www_dir}{index}/newsfeed/unpublished/{current_category_id}/?Offset={prev_offset}">&lt;&lt;&nbsp;{intl-prev}</a>
<!-- END previous_tpl -->

     </td>
     <td width="50%" align="right">

<!-- BEGIN next_tpl -->
<a href="{www_dir}{index}/newsfeed/unpublished/{current_category_id}/?Offset={next_offset}">{intl-next}&nbsp;&gt;&gt;</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td>
  <input class="stdbutton" type="submit" name="Delete" value="{intl-delete_marked}" />
  </td>
  <td>&nbsp;</td>
  <td>
  <input class="stdbutton" type="submit" name="DeleteCat" value="{intl-delete_marked_categories}" />
  </td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Publish" value="{intl-publish_marked}" />

<input type="hidden" name="CategoryID" value="{current_category_id}" />

</form>


