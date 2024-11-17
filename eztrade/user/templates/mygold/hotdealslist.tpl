<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <th align="center">{intl-hot_deals}</th>
  </tr>
  <tr> 
    <td class="spacer2">&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top"> 
    <!-- BEGIN product_list_tpl -->
      <table width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
        <!-- BEGIN product_tpl -->
	{begin_tr}
	  <td>
	    <h3 align="center"><a class="hotdeal" href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/">{product_name}</a></h3> 
	    <!-- BEGIN product_image_tpl -->
	    <div align="center">
	      <a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/">
<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/></a>
	    </div>
	    <!-- END product_image_tpl -->
	    <p class="small">{product_intro_text}</p>
	    <!-- BEGIN price_tpl -->
	    <p class="small" align="right">{product_price}</p>
	    <!-- END price_tpl -->
	  </td>
	{end_tr} 
	<!-- END product_tpl -->
      </table>
    <!-- END product_list_tpl -->
    </td>
  </tr>
  <tr> 
    <td class="spacer2">&nbsp;</td>
  </tr>
  <tr> 
    <td class="bgspacer"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="2" /></td>
  </tr>
  <tr> 
    <td class="spacer5">&nbsp;</td>
  </tr>
</table>