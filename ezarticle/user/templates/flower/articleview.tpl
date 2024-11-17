<!-- BEGIN article_url_item_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<center><h3>{intl-found}: http://{article_url}</h3></center>
	</td>
</tr>
</table>
<!-- END article_url_item_tpl -->
<h2>{article_name}</h2>
{intl-article_date}: {article_created} <br />

{intl-article_author}: {author_text} <br />

<p>
{article_body}
</p>



<!-- BEGIN prev_page_link_tpl -->
<a href="{www_dir}{index}/article/articleview/{article_id}/{prev_page_number}/">{intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a href="{www_dir}{index}/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a href="{www_dir}{index}/article/articleview/{article_id}/{next_page_number}/">{intl-next_page}</a>
<!-- END next_page_link_tpl -->