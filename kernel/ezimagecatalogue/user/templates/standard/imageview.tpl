<h1>{intl-headline}: {image_name}</h1>

<hr noshade="noshade" size="4" />

<img src="{www_dir}/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="{www_dir}{index}/imagecatalogue/image/list/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="{www_dir}/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="{www_dir}{index}/imagecatalogue/image/list/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade size="4"/>

<br />

<table width="1" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right">
[<a href=# onClick="document.sflyc4p.returl.value=document.location; document.sflyc4p.submit();return false">{intl-order}</a>]

  <form name="sflyc4p" action="http://www.shutterfly.com/c4p/UpdateCart.jsp" method="post">
  <input type=hidden name=addim value="1">
  <input type=hidden name=protocol value="SFP,100">
  <input type=hidden name=pid value="C4PP">
  <input type=hidden name=psid value="GALL">
  <input type=hidden name=referid value="gallery">
  <input type=hidden name=returl value="this-gets-set-by-javascript-in-onClick">
  <input type=hidden name=imraw-1 value="http://{site_url}/{image_path}">
  <input type=hidden name=imrawheight-1 value="{orig_height}">
  <input type=hidden name=imrawwidth-1 value="{orig_width}">
  <input type=hidden name=imthumb-1 value="http://{site_url}{image_src}">
  <input type=hidden name=imbkprntb-1 value="Hi">
</form>

</td></tr>

<tr><td>
	<a href="{www_dir}{index}{referer_url}"><img src="{image_uri}" border="0" width="{image_width}" height="{image_height}" alt="{image_description}" /></a>
	</td>
</tr>
<tr>
        <td class="pictext">{image_caption}</td>
</tr>
<tr><td>
<center>[<a href="{www_dir}{index}/imagecatalogue/download/{image_id}/{original_image_name}/">{intl-download}</a>]</center>
</td></tr>
<tr><td class="small">
<center>
{image_size}&nbsp;{image_unit}&nbsp;--&nbsp;{orig_height}x{orig_width}&nbsp;px</center>
</td></tr>

<tr>
<th>
<br>
      <!-- BEGIN related_articles_tpl -->
      {intl-articles}:</th></tr>

      <!-- BEGIN article_item_tpl -->
      <tr><td class="{td_class}" >
      <a href="{www_dir}{index}/article/articleview/{article_id}/">{article_name}</a>
      </td></tr>
      <!-- END article_item_tpl -->
      <!-- END related_articles_tpl -->
</td>
<tr><th>
<br>
      <!-- BEGIN related_products_tpl -->
      {intl-products}:</th></tr>
      <!-- BEGIN product_item_tpl -->
      <tr><td class="{td_class}" >
      <a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a>
      </td></tr>
      <!-- END product_item_tpl -->
      <!-- END related_products_tpl -->
</td>

</tr>




</table>
<p>{image_description}</p>

<br />

<a class="path" href="{www_dir}{index}{referer_url}">&lt;&lt;&nbsp;{intl-back}</a>
