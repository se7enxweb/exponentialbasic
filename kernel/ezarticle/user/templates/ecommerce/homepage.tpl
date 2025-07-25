        <!-- start #breadcrumbs -->
        <div id="breadcrumbs">
          &nbsp; <a href="{www_dir}{index}/article/archive/0/">{intl-top_level}</a>
        </div>
        <!-- end #breadcrumbs -->
        <!-- start #contentWrap -->
        <div id="contentWrap" style="position:relative; top: 7px;">
          <!-- start #productWrap -->
          <div id="frontpageWrap">

            <div id="frontpageFloat">
              <!-- start #orderBox -->

			  <!-- BEGIN distributor_image_box_tpl -->
              <div id="importBox">
                <h3>{distributor}:</h3>

                <div class="importBoxWrap" style="margin: auto;">
		<table cellpadding=0 cellspacing=0 border=0>
			  <!-- BEGIN distributor_image_item_tpl -->
			  {import_row_open}
			  <td>
			
                                       <span class="thumbnail">
					<!-- BEGIN distributor_image_tpl -->
                                        <a href="{www_dir}{index}/trade/productlist/{category_id}" title="{category_description}">
					<img src="{www_dir}/{thumbnail_image_uri}" width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{category_description}" border="1" />
					</a>
					<!-- END distributor_image_tpl -->
					<p><a href="{www_dir}{index}/trade/productlist/{category_id}" title="{category_description}">{category_name}</a></p>
			               </span> 
				       </td>
                         {import_row_close}
			  <!-- END distributor_image_item_tpl -->
			
			  </table>


                </div>
              </div>
			  <!-- END distributor_image_box_tpl -->
			  <!-- BEGIN dealer_image_box_tpl -->
		<div id="dealer">
                <h3>{dealer}:</h3>

                <div class="dealerBoxWrap">
		<table cellpadding=0 cellspacing=0 border=0>
			  <!-- BEGIN dealer_image_item_tpl -->
			  {dealer_row_open}
			  <td>
				<div class="thumbnail">
					<!-- BEGIN dealer_image_tpl -->
					<a href="{www_dir}{index}/trade/productlist/{category_id}" title="{category_description}">
					<img src="{www_dir}/{thumbnail_image_uri}" width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{category_description}" border="1" />
					</a>
					<!-- END dealer_image_tpl -->
					<p><a href="{www_dir}{index}/trade/productlist/{category_id}" title="{category_description}">{category_name}</a></p>
					</div>
					</td>
					{dealer_row_close}
			  <!-- END dealer_image_item_tpl -->
			</table>
                </div>
              </div>
			  <!-- END dealer_image_box_tpl -->
			  			  
              <!-- end #orderBox -->

            </div>
            <!-- start #itemBox -->

            <!-- end #itemBox -->
          </div>
          <!-- end #productWrap -->

          <div class="clearRight">
            &nbsp;
          </div>




          <!-- start #featuresWrap -->

          <div id="featuresWrap">
			<div class="frontRight">
			<!-- BEGIN product_list_tpl -->
			<h3>{intl-featured_products}</h3>
			
              <div class="frontRightBoxes">
	    
			    <!-- BEGIN product_list_item_tpl -->

                <h5><a href="{www_dir}{index}/trade/productview/{product_id}/" title="{product_intro_text}">{product_name}</a></h5><p><a href="{www_dir}{index}/trade/productview/{product_id}/"><img src="{www_dir}/{product_image_path}"
                width="{product_image_width}" height="{product_image_height}" alt="{image_caption}" border="0" /></a>
				{product_intro_text}</p><h6>
				<!-- BEGIN price_tpl -->
				{intl-base_price}:&nbsp;{product_price}&nbsp;
				<!-- END price_tpl -->
				<!-- BEGIN product_message_count_tpl -->
				[<a href="{www_dir}{index}/trade/productview/{product_id}/">{messages}</a>]
				<!-- END product_message_count_tpl -->
				</h6>
			    <!-- END product_list_item_tpl -->

			  </div>
                <div class="clearRight">
                  &nbsp;
                </div>
			<!-- END product_list_tpl -->
			
			<!-- BEGIN image_list_tpl -->			
			<h3>{intl-image_categories}</h3>			  
			  
              <div id="frontRightGallery"><table cellpadding="0" cellspacing="0" border="0">
			<!-- BEGIN image_list_item_tpl -->
			{image_row_open}
			<td>
				<span class="thumbnail" style="padding-left: 8px; float: left"><a href="{www_dir}{index}/imagecatalogue/image/list/{category_id}/" title="{category_description}"><img src="{www_dir}/{thumbnail_image_uri}" alt="{category_description}" width="{thumbnail_image_width}" height="{thumbnail_image_height}" border="0" />
				<p>{category_name}</p></a>
				</span>
			</td>
			{image_row_close}
			<!-- END image_list_item_tpl -->	
</table>
              </div>
                <div class="clearRight">
                  &nbsp;
                </div>
			<!-- END image_list_tpl -->												  
			
			<!-- BEGIN message_list_tpl -->
			<h3>{intl-latest}</h3>						  
          <div id="comments">
			<!-- BEGIN message_list_item_tpl -->			  


            <p><span class="commentHeadings"><a href="{www_dir}{index}/forum/message/{message_id}">{date_posted}&nbsp;
			{message_topic}&nbsp;({author})</a></span><br />
            {message_header} <span class="commentHeadings"><a href="{www_dir}{index}/forum/message/{message_id}">{intl-read_more}&nbsp;&raquo;</a></span></p>

			<!-- END message_list_item_tpl -->	
           </div>	 
			<!-- END message_list_tpl -->
            </div>
		
            <!-- end #frontRight -->
            <!-- start #featuresLeft -->
			
			<!-- BEGIN article_list_tpl -->
            <div class="frontpageLeft">
			  <h3>{intl-recent_articles}</h3>
			  
                <div class="frontLeftBoxes">
			    <!-- BEGIN article_item_tpl -->
                <h5><a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{title}</a></h5>
				<p>
				<!-- BEGIN article_image_tpl -->
				<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">
				<img src="{www_dir}/{thumbnail_image_uri}"
                width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{thumbnail_image_caption}" border="0" />
				</a>
				<!-- END article_image_tpl -->
				{article_intro}
                </p>
				<h6>{intl-posted} {article_published}&nbsp;
				<!-- BEGIN message_count_tpl -->
				[<a href="{www_dir}{index}/article/articleview/{article_id}/1/{category_id}/">{messages}</a>]
				<!-- END message_count_tpl -->
				</h6>
				<!-- END article_item_tpl -->
              </div>

			<!-- END article_list_tpl -->
			</div>            
                <div class="clearRight">
                  &nbsp;
                </div>
            <!-- end #featuresLeft -->
          </div>
          <!-- end #featuresWrap -->

        </div>
        <!-- end #contentWrap -->
