<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
        <div style="text-align: center; font-size: 25px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">{intl-hot_deals}</div>
    </td>
</tr>
</table>


<table align="top" width="100%" cellspacing="5" cellpadding="5" border="0">
<!-- BEGIN product_list_tpl -->

<!-- BEGIN product_tpl -->
{begin_tr}
   <td valign="top">

       <table align="right" border="0">
        <tr>
         <td>
          <div class="listproducts">
	         <div align="left" style="vertical-align: top;"><a class="listproducts" href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/">{product_name}</a><br />
        </div>

    </td>
    </tr>
    <!-- BEGIN product_image_tpl -->
    <tr>
        <td align="right">
        <a href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/">
        <img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}" style=""/></a><br />
        </td>
    </tr>
    <tr>
        <td align="right" class="pictext">{thumbnail_image_caption}</td>
    </tr>
   <!-- END product_image_tpl -->
    <tr>
       <td>
        <div class="pictext" style="text-align: right;">{product_intro_text}</div>
       </td>
    </tr>
    <tr>
        <td align="right">
        <!-- BEGIN price_tpl -->
        <div class="spacer"><div class="pris">{product_price}</div>
        <a class="listproducts" href="{www_dir}{index}/{module}/{module_view}/{product_id}/{category_id}/" title="Product Number">{product_number}</a></div>
        <!-- END price_tpl -->
        <!-- BEGIN product_catalog_number_tpl -->
        <div class="spacer"><div class="pris"><a title="Catalog Number">{catalog_number}</a></div>
        <!-- END product_catalog_number_tpl -->
        </td>
    </tr>
    <tr>
        <td>
        <!-- BEGIN add_to_cart_tpl -->
        <form action="{www_dir}{index}/{module}/{action_url}/{product_id}/" method="post">
        <div align="right">
        <div class="spacer">
        <div class="pris">
        <input type="image" src="/design/trade/images/addtocart.gif" name="Cart" value="{intl-add_to_cart}" />
        </div>
        </div>
        </div>
        </form>
        <!-- END add_to_cart_tpl -->
        </td>
    </tr>
    </table>
        </td>

{end_tr}
<!-- END product_tpl -->

<!-- END product_list_tpl -->
      </td>
</tr>

<tr>
        <td class="menuspacer" colspan="{hotdeal_columns}">&nbsp;</td>
</tr>

</table>
