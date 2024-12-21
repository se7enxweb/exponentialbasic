<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
    	<td align="left" valign="bottom">
     	   <h1>{intl-head_line}</h1>
     	</td>
   		<td align="right">
			<form action="{www_dir}{index}/trade/search/" method="post">
	      		<input type="text" name="Query" />
	      		<input class="okbutton" type="submit" name="search" value="{intl-search_button}" />
	        </form>
	    </td>
	</tr>
</table>
<hr noshade="noshade" size="1" />

<h2>Ihre Suche nach "{query_string}" ergab:</h2>

<!-- BEGIN error_max_search_for_products_tpl -->
<p class="error">{intl-max_search}</p>
<!-- END error_max_search_for_products_tpl -->

<table width="60%">
  <tr>
    <td style="font-size: 10px; width="50%">
      Ist Ihnen das Ergebnis zu umfangreich oder wollen Sie weitere Suchoptionen nutzen Sie bitte unsere 
      <a href="{www_dir}{index}/trade/extendedsearch/">erweitert Suche</a>.
    </td>
  </tr>
</table>


<hr noshade="noshade" size="1" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <!-- BEGIN product_tpl -->
  <tr>
    <td colspan="2">
      <a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br />
    </td>
  </tr>
  <tr>
    <td colspan="2" class="spacer2">&nbsp;</td>
  </tr>
  <tr>
    <td width="99%" valign="top">
      {product_intro_text}
    </td>
    <td>
      <!-- BEGIN image_tpl -->
      <table align="right">
        <tr>
          <td>
	    <img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	  </td>
	</tr>
	<tr>
          <td>
	    {thumbnail_image_caption}
          </td>
	</tr>
      </table>
      <!-- END image_tpl -->
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <!-- BEGIN price_tpl -->
      <p class "pris">
	{product_price}
      </p>
      <!-- END price_tpl -->
    </td>
  </tr>
  <tr>
    <td colspan="2"><hr noshade="noshade" size="1" /></td>
  </tr>
  <!-- END product_tpl -->
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
	<td>
	    <!-- BEGIN type_list_tpl -->
	    <br />
	    <table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <!-- BEGIN type_list_previous_tpl -->
		    <td>
			&lt;&lt;&nbsp;<a class="path" href="/trade/search/move/{url_text}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/trade/search/move/{url_text}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="/trade/search/move/{url_text}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
