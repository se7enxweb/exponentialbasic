<!-- BEGIN header_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
   <td colspan="3">
	  <center><span class="h3">The World of Sailing World</span></center><br>
   </td>
</tr>
  <tr>
    <td bgcolor="#006699" width="9"><img src="{www_dir}/sitedesign/sailing/images/leftrounded.gif" width="9" height="20" hspace="0" vspace="0" border="0" align="left" alt=""></td>
    <td bgcolor="#006699" width="100%"><b class="white">{current_category_name}</b></td>
    <td width="70"><img src="{www_dir}/sitedesign/sailing/images/rightrounded.gif" width="70" height="20" hspace="0" vspace="0" border="0" align="right" alt=""></td>
  </tr>

</table>

<!-- END header_item_tpl -->

<!-- BEGIN path_item_tpl -->

<!-- END path_item_tpl -->

<div class="spacer"><div class="p">{current_category_description}</div></div>

<!-- BEGIN category_list_tpl -->

<!-- BEGIN category_item_tpl -->

<!-- END category_item_tpl -->

<!-- END category_list_tpl -->


<!-- BEGIN article_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	</td>
</tr>
<!-- BEGIN article_item_tpl -->
<tr>
	<td>
	<span class="listheadline"><a class="listheadline" href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a></span>
	<span class="small">( {article_published} )</span>

	<br />
	<b>By:</b> {author_text} <br />

	<!-- BEGIN article_image_tpl -->
	    <table align="right">
	        <tr>
			<td>
			<a href="{www_dir}{index}/article/articleview/{article_id}/"><img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" /></a>
            </td>
                </tr>
                <tr>
                         <td class="pictext">
                         {thumbnail_image_caption}
                         </td>
                </tr>
             </table>
        <!-- END article_image_tpl -->


	<div class="spacer"><div class="p">{article_intro}</div></div>
	<a class="path" href="{www_dir}{index}/article/articleview/{article_id}/">{article_link_text}</a>
	<br /><br />
	</td>
</tr>
<!-- END article_item_tpl -->
</table>
<!-- END article_list_tpl -->



<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>

<!-- BEGIN previous_tpl -->
<a class="path" href="{www_dir}{index}/article/archive/{category_id}/{prev_offset}/">
&lt;&lt; {intl-prev}
</a>
<!-- END previous_tpl -->
     </td>
     <td align="right">

<!-- BEGIN next_tpl -->
<a class="path" href="{www_dir}{index}/article/archive/{category_id}/{next_offset}/">
{intl-next} &gt;&gt;
</a>
<!-- END next_tpl -->
     </td>
</tr>
</table>    


