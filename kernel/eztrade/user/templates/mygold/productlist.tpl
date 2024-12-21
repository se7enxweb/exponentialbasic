<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr> 
		<td> 
			<h1>{intl-productlist}</h1>
		</td>
		<td align="right"> 
			<form action="{www_dir}{index}/trade/search/" method="post">
				<input type="text" name="Query" size="8" />
				<input class="okbutton" type="submit" name="search" value="{intl-search_button}" />
			</form>
		</td>
	</tr>
	<tr> 
		<td colspan="2"> 
			<hr noshade size="1" />
			<img src="{www_dir}/sitedesign/mygold/images/path-arrow.gif" height="10" width="15" border="0" alt=""/> 
			<a href="{www_dir}{index}/trade/productlist/0/">{intl-top}</a> 
			<!-- BEGIN path_tpl -->
			<img src="{www_dir}/sitedesign/mygold/images/path-slash.gif" height="10" width="20" border="0" alt=""/> 
			<a href="{www_dir}{index}/trade/productlist/{category_id}/">{category_name}</a> 
			<!-- END path_tpl -->
			<hr noshade size="1" />
		</td>
	</tr>
	<!-- BEGIN category_list_tpl -->
	<tr> 
		<th align="left">{intl-category}:</th>
		<th align="left">{intl-description}:</th>
	</tr>
	<!-- BEGIN category_tpl -->
	<tr> 
		<td class="{td_class}"> <a href="{www_dir}{index}/trade/productlist/{category_id}/">{category_name}</a>&nbsp; 
		</td>
		<td class="{td_class}"> {category_description}&nbsp; </td>
	</tr>
	<!-- END category_tpl -->
	<tr> 
		<td colspan="2"> 
			<hr noshade size="1" />
		</td>
	</tr>
	<!-- END category_list_tpl -->
	<!-- BEGIN product_list_tpl -->
	<!-- BEGIN product_tpl -->
	<tr> 
		<td valign="top"> 
			<h2><a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/">{product_name}</a></h2>
			<p><br />
				{product_intro_text}</p>
		</td>
		<td rowspan="2" align="right"> 
			<!-- BEGIN product_image_tpl -->
			<table>
				<tr> 
					<td><a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"> 
						<img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" alt="{thumbnail_image_caption}"/></a> 
					</td>
				</tr>
				<tr> 
					<td class="pictext">{thumbnail_image_caption}</td>
				</tr>
			</table>
			<!-- END product_image_tpl -->
		</td>
	</tr>
	<tr> 
		<td valign="bottom"> 
			<!-- BEGIN price_tpl -->
			<p class="pris">{product_price}</p>
			<!-- END price_tpl -->
		</td>
	</tr>
	<tr> 
		<td colspan="2"> 
			<hr noshade size="1" />
		</td>
	</tr>
	<!-- END product_tpl -->
	<!-- END product_list_tpl -->
</table>
<table width="100%">
    <tr>
        <td class="spacer" width="1%">
	    <a class="nav" href="#"><img src="{www_dir}/sitedesign/mygold/images/top.gif" width="10" height="15" border="0" alt="" /></a>
	</td>
	<td>
	    <a class="nav" href="#">TOP</a>
	</td>        
	<td align="right">
	    <a class="nav" href="#">TOP</a>
	</td>
        <td class="spacer" width="1%">
	    <a class="nav" href="#"><img src="{www_dir}/sitedesign/mygold/images/top.gif" width="10" height="15" border="0" alt="" /></a>
	</td>	        
    </tr>
</table>    	
<br />
<!-- BEGIN type_list_tpl -->
<table cellpadding="0" cellspacing="0" border="0" align="center">
	<tr> 
		<!-- BEGIN type_list_previous_tpl -->
		<td> &lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_previous_index}">{intl-previous}</a>&nbsp;| 
		</td>
		<!-- END type_list_previous_tpl -->
		<!-- BEGIN type_list_previous_inactive_tpl -->
		<td> <span class="inactive">&lt;&lt; {intl-previous} </span>| </td>
		<!-- END type_list_previous_inactive_tpl -->
		<!-- BEGIN type_list_item_list_tpl -->
		<!-- BEGIN type_list_item_tpl -->
		<td> &nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_index}">{type_item_name}</a>&nbsp;| 
		</td>
		<!-- END type_list_item_tpl -->
		<!-- BEGIN type_list_inactive_item_tpl -->
		<td> <span class="inactive">&nbsp;{type_item_name}&nbsp;</span>| </td>
		<!-- END type_list_inactive_item_tpl -->
		<!-- END type_list_item_list_tpl -->
		<!-- BEGIN type_list_next_tpl -->
		<td> &nbsp;<a class="path" href="{www_dir}{index}/trade/productlist/{category_id}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt; 
		</td>
		<!-- END type_list_next_tpl -->
		<!-- BEGIN type_list_next_inactive_tpl -->
		<td> <span class="inactive">&nbsp;{intl-next}</span> &gt;&gt; </td>
		<!-- END type_list_next_inactive_tpl -->
	</tr>
</table>
<!-- END type_list_tpl -->
