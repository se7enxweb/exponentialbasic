<!-- wishlist.tpl -->

<form action="{www_dir}{index}/trade/wishlist/" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>
      <h1>{intl-wishlist}</h1>
    </td>
    <td align="right">
      <!-- BEGIN public_wishlist_tpl -->
      <input type="checkbox" name="IsPublic" checked />&nbsp;<span class="boxtext">{intl-is_public}</span>
      <!-- END public_wishlist_tpl -->
      <!-- BEGIN non_public_wishlist_tpl -->
      <input type="checkbox" name="IsPublic" />&nbsp;<span class="boxtext">{intl-is_public}</span>
      <!-- END non_public_wishlist_tpl -->
    </td>
<tr>
    <td colspan="2">

      <hr noshade size="4" />
      <!-- BEGIN empty_wishlist_tpl -->
      <h2>{intl-empty_wishlist}</h2>
      <!-- END empty_wishlist_tpl --> 
      <!-- BEGIN wishlist_item_list_tpl -->
      <table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
		<tr> 
		  <th>&nbsp;</th>
		  <th>{intl-product_name}:</th>
		  <th>{intl-product_options}:</th>
		  <th>{intl-move_to_cart}:</th>
		  <th>{intl-someone_has_bought_this}:</th>
		  <!-- BEGIN product_available_header_tpl -->
		  <th>{intl-product_availability}:</th>
		  <!-- END product_available_header_tpl -->
		  <th>{intl-product_qty}:</th>
		  <td align="right"><b>{intl-product_price}:</b></td>
		  <td align="right">&nbsp;</td>
		</tr>
		<!-- BEGIN wishlist_item_tpl --> 
		<tr> 
		  <td class="{td_class}"> <!-- BEGIN wishlist_image_tpl --> <img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
			<!-- END wishlist_image_tpl --> </td>
		  <td class="{td_class}"> <a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a> 
		  </td>
		  <td class="{td_class}"> <!-- BEGIN wishlist_item_option_tpl --> <div class="small">{option_name}: {option_value}<!-- BEGIN wishlist_item_option_availability_tpl -->({option_availability})
<!-- END wishlist_item_option_availability_tpl --></div>
			<!-- END wishlist_item_option_tpl --> &nbsp;</td>
		  <td class="{td_class}">
		  <!-- BEGIN move_to_cart_item_tpl -->
		   <a href="{www_dir}{index}/trade/wishlist/movetocart/{wishlist_item_id}/"> 
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
		  <td class="{td_class}">
		  {product_availability}
		  </td>
		  <!-- END product_available_item_tpl -->
  		  <td class="{td_class}">
		  	<input type="hidden" name="WishlistIDArray[]" value="{wishlist_item_id}" />
			<input size="3" type="text" name="WishlistCountArray[]" value="{wishlist_item_count}" />
   		  </td>
		  <td class="{td_class}" align="right"><nobr>{product_price}</nobr></td>
		  <td class="{td_class}" align="right"><a href="{www_dir}{index}/trade/wishlist/remove/{wishlist_item_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztrade{wishlist_item_id}-slett','','/images/slettminimrk.gif',1)"><img name="eztrade{wishlist_item_id}-slett" border="0" src="{www_dir}/images/slettmini.gif" width="16" height="16" align="top"></a></td>
		</tr>
		<!-- END wishlist_item_tpl --> 
		<tr> 
		  <td colspan="6">&nbsp;</td>
		  <th colspan="1">{intl-total}:</th>
		  <td align="right"><nobr>{wishlist_sum}</nobr></td>
		  <td align="right">&nbsp;</td>
		</tr>
	  </table>
      <!-- END wishlist_item_list_tpl -->
      <hr noshade size="4" />
    </td>
  </tr>
</table>

	<input type="hidden" name="Action" value="Refresh" />
	<input class="stdbutton" type="submit" value="{intl-update}" />
</form>

<hr noshade="noshade" size="4" />

<!-- BEGIN wishlist_checkout_tpl -->
<form action="{www_dir}{index}/trade/sendwishlist/" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
        <td>
        <input class="okbutton" type="submit" value="{intl-send_wishlist}" />
	</td>
</tr>
</table>
<!-- END wishlist_checkout_tpl -->
</form>