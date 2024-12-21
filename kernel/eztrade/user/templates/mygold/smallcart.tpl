<!-- BEGIN empty_cart_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr align="center"> 
    <th>{intl-cart}</th>
  </tr>
  <tr> 
    <td class="spacer5">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">{intl-empty_cart}</td>
  </tr>
  <tr>
    <td class="spacer5">&nbsp;</td>
  </tr>
  <tr> 
    <td class="bgspacer"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="2" /></td>
  </tr>
  <tr>
    <td class="spacer5">&nbsp;</td>
  </tr>

</table>
<!-- END empty_cart_tpl -->


<!-- BEGIN cart_item_list_tpl -->
<form action="{www_dir}{index}/trade/cart/" method="post">
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr align="center"> 
      <th colspan="2">{intl-cart}</th>
    </tr>
    <tr> 
      <td class="spacer2" colspan="2">&nbsp;</td>
    </tr>
    <!-- BEGIN cart_item_tpl -->
    <tr> 
      <td colspan="2">&nbsp;<a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a> 
	<div align="right">{product_price}&nbsp;</div>
      </td>
    </tr>
    <!-- END cart_item_tpl -->
    <tr>
      <td class="spacer" colspan="2" bgcolor="#999999"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr>
      <td>&nbsp;{intl-shipping}:</td>
      <td align="right">&nbsp;
{shipping_sum}&nbsp;
</td>
    </tr>
    <tr>
      <td >&nbsp;{intl-vat}:</td>
      <td align="right">
{cart_vat_sum}&nbsp;
</td>
    </tr>
    
<tr>
      <td class="spacer" colspan="2" bgcolor="#999999"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr> 
      <td>&nbsp;{intl-total}:</td>
      <td align="right">{cart_sum}&nbsp;</td>
    </tr>
    <tr>
      <td class="spacer" colspan="2" bgcolor="#999999"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr>
      <td class="spacer" colspan="2"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr>
      <td class="spacer" colspan="2" bgcolor="#999999"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr>
      <td class="spacer5" colspan="2">
<input type="hidden" name="Action" value="Refresh" />
</td>
    </tr>
    <tr align="center"> 
      <td class="small" colspan="2"> 
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
	    <td class="spacer5">&nbsp;</td>
	  </tr>
	  <tr> 
	    <!-- BEGIN cart_checkout_tpl -->
	    <td align="center"><input type="submit" class="okbutton" name="DoCheckOut" value="{intl-checkout}" />
</td>
	  </tr>
  	  <tr>
	    <td class="spacer2">&nbsp;</td>
	  </tr>

	  <tr> 
	    <td align="center">
<a class="small" href="{www_dir}{index}/trade/cart/">{intl-allcart}</a>
</td>
	    <!-- END cart_checkout_tpl -->
          </tr>
	</table>
      </td>
    </tr>
    <tr> 
      <td class="spacer2" colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td class="bgspacer" colspan="2"><img src="{www_dir}/sitedesign/mygold/images/shim.gif" alt="" width="1" height="2" /></td>
    </tr>
    <tr>
	<td class="spacer5" colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>

<!-- END cart_item_list_tpl -->