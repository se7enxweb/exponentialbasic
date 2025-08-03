<!-- start #breadcrumbs -->
<div id="breadcrumbs">
&nbsp; <a href="{www_dir}{index}/{module}/{module_list}/0/">{intl-top}</a>
<!-- BEGIN path_tpl -->
 &raquo; <a href="{www_dir}{index}/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->
</div>
<!-- end #breadcrumbs -->
<!-- start #contentWrap -->
<div id="contentWrap">
   <h1 class="mainHeading">{title_text}</h1>

<!-- BEGIN price_to_high_tpl -->
<p class="error">{intl-price_to_high}</p>
<!-- END price_to_high_tpl -->
<!-- BEGIN price_to_low_tpl -->
<p class="error">{intl-price_to_low}</p>
<!-- END price_to_low_tpl -->

<!-- BEGIN user_login_tpl -->
<p class="byline"> As the admin, you may <a class="byline" href="{admin_site}/trade/productedit/edit/{product_id}/" target="_blank">edit</a> this product.</p>
<!-- END user_login_tpl -->

         <!-- start #productWrap -->
          <div id="productWrap">
            <div id="productFloat">
	      <!-- BEGIN product_number_item_tpl -->
              <strong>{intl-nr}:</strong> {product_number}<br />
	      <!-- END product_number_item_tpl -->
              <!-- start #orderBox -->

              <div id="orderBox">
                <h3>{intl-orderbox_head}</h3>

                <div class="orderBoxWrap">
                  <!-- BEGIN list_price_tpl -->
			<span id="lPrice">{intl-list_price}: <strike>{product_list_price}</strike></span><br />
			<!-- END list_price_tpl -->
                  <span id="oPrice">
					<!-- BEGIN price_tpl -->
					{intl-price}: {product_price}<br />
					<!-- BEGIN alternative_currency_list_tpl -->
					{intl-alternative_currency}: 
					<!-- BEGIN alternative_currency_tpl -->
					{alt_price}<br />
					<!-- END alternative_currency_tpl -->
					<!-- END alternative_currency_list_tpl -->
					<!-- END price_tpl -->	
					<!-- BEGIN price_range_tpl -->
					{intl-price_range}:<br />
					<!-- BEGIN price_range_min_unlimited_tpl -->
					{intl-min}: {intl-unlimited}<br />
					<!-- END price_range_min_unlimited_tpl -->
					<!-- BEGIN price_range_min_limited_tpl -->
					{intl-min}: {price_min}<br />
					<!-- END price_range_min_limited_tpl -->
					<!-- BEGIN price_range_max_unlimited_tpl -->
					{intl-max}: {intl-unlimited}<br />
					<!-- END price_range_max_unlimited_tpl -->
					<!-- BEGIN price_range_max_limited_tpl -->
					{intl-max}: {price_max}<br />
					<!-- END price_range_max_limited_tpl -->
					<input type="text" name="PriceRange" size="8" />
					<!-- END price_range_tpl -->
		  		  </span><br />

		<form action="{www_dir}{index}/{module}/{action_url}/{product_id}/{category_id}">
			<!-- BEGIN option_tpl -->
                  	<small>{option_name}: {option_description}</small><br />
			
			<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
			<!-- BEGIN value_price_header_tpl -->
			<!-- BEGIN value_description_header_tpl -->
			<!-- END value_description_header_tpl -->
			<!-- BEGIN value_price_header_item_tpl -->
			<!-- END value_price_header_item_tpl -->
			<!-- BEGIN value_currency_header_item_tpl -->
			<!-- END value_currency_header_item_tpl -->
			<!-- END value_price_header_tpl -->

                    <select name="OptionValueArray[]">
			<!-- BEGIN value_tpl -->
			<!-- BEGIN value_description_tpl -->
			<option value="{value_id}">{value_name}
			<!-- END value_description_tpl -->
			<!-- BEGIN value_price_item_tpl -->
			{value_price}
			<!-- END value_price_item_tpl -->
			<!-- BEGIN value_availability_item_tpl -->
			({value_availability})
			<!-- END value_availability_item_tpl -->
			</option>
			<!-- BEGIN value_price_currency_list_tpl -->
			<!-- BEGIN value_price_currency_item_tpl -->
			<!-- END value_price_currency_item_tpl -->
			<!-- END value_price_currency_list_tpl -->
			<!-- END value_tpl -->
                    </select><br />
			<!-- END option_tpl -->
			<!-- BEGIN add_to_cart_tpl -->
			<input type="submit" name="Cart" id="addCart" value="{intl-add_to_cart}" /><br />
			<input type="submit" name="WishList" id="addWish" value="{intl-wishlist}" />
			<!-- END add_to_cart_tpl -->
			<!-- BEGIN voucher_buttons_tpl -->
			<input type="submit" name="Voucher" id="addCart" value="{intl-next}" />
			<!-- END voucher_buttons_tpl -->
                  </form>
			<!-- BEGIN quantity_item_tpl -->
			<p>{intl-availability}: {product_quantity}</p>
			<!-- END quantity_item_tpl -->
			<!-- BEGIN date_available_tpl -->
			<p>{intl-date_available}: {date_available}</p>
			<!-- END date_available_tpl -->
			<!-- BEGIN shipping_message_tpl -->
			<table width="100%" cellpadding="2" cellspacing="2" align="center"><tr><td align="center">
			<strong>Important Shipping Info</strong></td></tr>
			<!-- BEGIN free_ups_tpl -->
			<tr><td align="center">
			<img src="/images/free-shipping-upsground.jpg" />
			</td></tr>
			<!-- END free_ups_tpl -->
			<!-- BEGIN free_usps_tpl -->
			<tr><td align="center">
			Free Shipping via...<br />
			<img src="/images/free-shipping-usps.jpg" />
			</td ></tr>
			<!-- END free_usps_tpl -->
			<!-- BEGIN flat_usps_tpl -->
			<tr><td align="center">
			This product ships for a flat rate of {flat_usps_price} when shipped via USPS Parcel.
			</td></tr>
			<!-- END flat_usps_tpl -->
			<!-- BEGIN flat_usps_combine_tpl -->
			<tr><td align="center">
			This product ships for a flat rate of {flat_usps_price} when ordered by itself and shipped via USPS Parcel.
			</td></tr>
			<!-- END flat_usps_combine_tpl -->
			<!-- BEGIN flat_ups_tpl -->
			<tr><td align="center">
			This product ships for a flat rate of {flat_ups_price} when shipped via UPS Ground.
			</td></tr>
			<!-- END flat_ups_tpl -->
			<!-- BEGIN flat_ups_combine_tpl -->
			<tr><td align="center">
			This product ships for a flat rate of {flat_ups_price} when ordered by itself and shipped via UPS Ground.
			</td></tr>
			<!-- END flat_ups_combine_tpl -->
			</table>
			<!-- END shipping_message_tpl -->
                </div>
              </div>
              <!-- end #orderBox -->
              <!-- start #related -->
	      <div id="related">
	<!-- BEGIN section_item_tpl -->
              <h3>{section_name}</h3>

                <div class="relatedBoxWrap">
		  <!-- BEGIN link_item_tpl -->
                  <a href="{www_dir}{index}{link_url}">{link_name}</a><br />
                  <!-- END link_item_tpl -->
                </div>
        <!-- END section_item_tpl -->
	      </div>
              <!-- end #related -->

            <!-- start #itemBox -->

            <div id="itemBox">
	      <!-- BEGIN external_link_tpl -->
              <strong>{intl-external_link}:</strong> <a href="{external_link_url}" target="_blank">{external_link_url}</a><br />
	      <!-- END external_link_tpl -->
		<!-- BEGIN main_image_tpl -->
		<table cellpadding="0" cellspacing="0" border="0"><tr><td>
		<a href="{www_dir}{index}/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/"><img src="{www_dir}{main_image_uri}" alt="{main_image_caption}" width="{main_image_width}" height="{main_image_height}" /></a></td>
		</tr>
		<tr><td>
              <div class="row prodRow">
	     <a href="{www_dir}{index}/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/"> {main_image_caption}</a>
              </div></td></tr></table>
		<!-- END main_image_tpl -->
            </div>
            <!-- end #itemBox -->
	      <!-- BEGIN attribute_list_tpl -->
	      <!-- BEGIN attribute_tpl -->
	      <!-- END attribute_tpl -->
	      <!-- BEGIN attribute_value_tpl -->
              <strong>{attribute_name}:</strong> {attribute_value_var} {attribute_unit}<br />
	      <!-- END attribute_value_tpl -->
	      <!-- BEGIN attribute_header_tpl -->
	      {attribute_name}:<br />
	      <!-- END attribute_header_tpl -->
	      <!-- END attribute_list_tpl -->
          </div>
          <!-- end #productWrap -->

          <div class="clearRight">
            &nbsp;
          </div><table cellpadding="0" cellspacing="0" border="0"><tr><td>
          <!-- start #featuresWrap -->
          <div id="featuresWrap">
	    <!-- BEGIN image_list_tpl -->
            <!-- start #featuresRight -->

            <div id="featuresRight">
	    <!-- BEGIN image_list_tpl -->
	    <!-- BEGIN image_tpl -->
              <div class="featuresRightBoxes">
                <p><a href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/{category_id}/"><img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" alt="{image_caption}" /></a>{image_caption}</p>
	          <div class="clearRight">
		  &nbsp;
                  </div>
              </div>
	    <!-- END image_tpl -->
	    </div>
            <!-- end #featuresRight -->
            <!-- start #featuresLeft -->
	    <!-- END image_list_tpl -->
	
    <div id="featuresLeft">
			<p>{intro_text}</p>
			<p>{description_text}</p>
			<!-- BEGIN numbered_page_link_tpl -->
			<div align="center"><a class="path" href="{www_dir}{index}/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
			<!-- END numbered_page_link_tpl -->
    </div>
    <!-- end #featuresLeft -->

          </div>
          <!-- end #featuresWrap -->
        </div></td></tr></table>
        <!-- end #contentWrap -->
        
		<!-- BEGIN print_page_link_tpl -->
		<div align="center">
		<a class="path" href="{www_dir}{index}/{module}/{module_print}/{product_id}/{category_id}/">{intl-print_page}</a><br></div>
		<!-- END print_page_link_tpl 
		<a class="path" href="{www_dir}{index}/{module}/mailtofriend/{product_id}/1/{category_id}/">{intl-send_mailtofriend}</a></div> -->
		</div>
        
<!-- BEGIN attached_file_list_tpl -->
<table class="list" cellspacing="0" cellpadding="4" border="0" width="77%%">
<tr><th><font size="2">{intl-attached_files}:</font></th></tr>

<!-- BEGIN attached_file_tpl -->
<tr>
     <td width="50%" class="{td_class}">
     <font size="2"><a href="{www_dir}{index}/filemanager/download/{file_id}/">{file_name}</a></font>
     </td>
     <td width="50%" class="{td_class}" align="right">
     <div class="p"><font size="2"><a href="{www_dir}{index}/filemanager/download/{file_id}/">( {file_size}�{file_unit} )</a></font></div>
     </td>
</tr>
<tr>
     <td colspan="2" valign="top" class="{td_class}"> 
	<font size="2">{file_description}</font>
     </td>
</tr>
<!-- END attached_file_tpl -->
</table>
<!-- END attached_file_list_tpl -->
