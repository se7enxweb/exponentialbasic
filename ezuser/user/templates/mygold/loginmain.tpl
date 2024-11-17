<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <th align="center">{intl-head_line}</th>
  </tr>
  <tr>
    <td class="spacer2">&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
        <tr>
          <td align="center">
	    {intl-advantage}
	    <table>
	      <tr>
	        <td>
		  <form action="{www_dir}{index}/user/login/?RedirectURL={redirect_url}" method="post">
	            <input class="okbutton" type="submit" name="{intl-login}" value="{intl-login}">
	          </form>
		</td>
              </tr>
	    </table>
	    <a class="small" href="{www_dir}{index}/user/user/new/?RedirectURL={redirect_url}">{intl-register_new}</a>
            <!-- BEGIN standard_creation_tpl -->
	    <!-- END standard_creation_tpl -->
	    <!-- BEGIN extra_creation_tpl -->
	    {extra_userbox}
	    <!-- END extra_creation_tpl -->
	  </td>
	</tr>
      </table>
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
