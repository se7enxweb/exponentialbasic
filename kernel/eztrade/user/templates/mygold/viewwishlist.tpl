
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>
      <h1>{intl-wishlist}</h1>
    </td>
<tr>
    <td>

      <hr noshade="noshade" size="1" />
      <!-- BEGIN empty_wishlist_tpl -->
      <h2>{intl-empty_wishlist}</h2>
      <!-- END empty_wishlist_tpl --> <!-- BEGIN wishlist_item_list_tpl -->
      <table width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr align="left"> 
		  <th width="1%">{intl-product_image}:</th>
		  <th>{intl-product_name}:</th>
		  <th width="1%">{intl-product_options}:</th>
		  <th width="1%">{intl-action}:</th>
		  <th width="1%">{intl-someone_has_bought_this}:</th>
		  <!-- BEGIN product_available_header_tpl -->
	
		  <!-- END product_available_header_tpl -->
		  <th width="1%">{intl-product_qty}:</th>
		  <th align="right">{intl-product_price}:</th>
		</tr>
		<!-- BEGIN wishlist_item_tpl --> 
		<tr align="center"> 
		  <td class="{td_class}"> <!-- BEGIN wishlist_image_tpl --> <img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
			<!-- END wishlist_image_tpl --> </td>
		  <td align="left" class="{td_class}"> <a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a> 
		  </td>
		  <td class="{td_class}"> 
			<!-- BEGIN wishlist_item_option_tpl -->  
			{option_value}
			    <!-- BEGIN wishlist_item_option_availability_tpl -->
			    ({option_availability})
			    <!-- END wishlist_item_option_availability_tpl -->
			<!-- END wishlist_item_option_tpl --> &nbsp;</td>
		  <td class="{td_class}"> 
		  <!-- BEGIN move_to_cart_item_tpl -->
		  <a href="{www_dir}{index}/trade/viewwishlist/movetocart/{wishlist_item_id}/{wishlist_item_count}/"> 
			{intl-move_to_cart} </a> 
		  <!-- END move_to_cart_item_tpl -->
		  <!-- BEGIN no_move_to_cart_item_tpl -->
		  &nbsp;
		  <!-- END no_move_to_cart_item_tpl -->
                  </td>

  		  <td class="{td_class}">

		  <!-- BEGIN is_bought_tpl -->
		  {intl-is_bought}
		  <!-- END is_bought_tpl -->

		  <!-- BEGIN is_not_bought_tpl -->
		  {intl-is_not_bought}
		  <!-- END is_not_bought_tpl -->

   		  </td>

		  <!-- BEGIN product_available_item_tpl -->
		  
		  <!-- END product_available_item_tpl -->

  		  <td class="{td_class}">
		  {wishlist_item_count}
   		  </td>
		  <td class="{td_class}" align="right"> {product_price} </td>
		</tr>
		<!-- END wishlist_item_tpl --> 
	  </table>
      <!-- END wishlist_item_list_tpl -->
      
     <hr noshade="noshade" size="1" />
    </td>
  </tr>
</table>
