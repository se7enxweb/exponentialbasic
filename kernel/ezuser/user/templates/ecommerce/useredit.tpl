<div id="breadcrumbs">
&nbsp;
</div>

<div id="contentWrap">
<form method="post" action="{www_dir}{index}/{module}/{user_new}/{action_value}/{user_id}/" style="size: 10px;">

<h1 class="mainHeading" style="width: 70%;">{head_line}</h1>

<span>{user_alert_message}</span>

<div>{user_address_alert_message}</div>

<!--
<hr noshade="noshade" size="4" />
-->

<!-- BEGIN required_fields_error_tpl -->
<h3 class="error" >{intl-required_fields_error}</h3>
<!-- END required_fields_error_tpl -->

<!-- BEGIN user_exists_error_tpl -->
<h3 class="error" >{intl-user_exists_error}</h3>
<!-- END user_exists_error_tpl -->

<!-- BEGIN password_error_tpl -->
<h3 class="error" >{intl-password_error}</h3>
<!-- END password_error_tpl -->

<!-- BEGIN email_error_tpl -->
<h3 class="error" >{intl-email_error}</h3>
<!-- END email_error_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="25%">
	<p class="boxtext">{intl-firstname}:</p>
	</td>
        <td width="25%">
        <input tabindex="1" type="text" size="20" name="FirstName" value="{first_name_value}"/>
        </td>
</tr>
<tr>
        <td>
        <p class="boxtext">{intl-lastname}:</p>
        </td>
	<td>
	<input tabindex="2" type="text" size="20" name="LastName" value="{last_name_value}"/>
	</td>
</tr>
<tr>
	<td width="386">
        	<p class="boxtext">{intl-password}:</p>
        </td>
        <td>
	        <input tabindex="3" type="password" size="20" name="Password" value="{password_value}"/>
        </td>
	</tr>
	<tr>	
	<td>
	        <p class="boxtext">{intl-verifypassword}:</p>
	</td>
	<td>
		<input tabindex="4" type="password" size="20" name="VerifyPassword" value="{verify_password_value}"/>
	</td>
</tr>
<tr>
	<td>
        <p class="boxtext">{intl-email}:</p>
	</td>
	<td>
	<input tabindex="5" type="text" size="30" name="Email" value="{email_value}"/>
        </td>
</tr>
<tr>
        <td>
        <p class="boxtext">{intl-login}:</p>

        </td>
        <td>
        <!-- BEGIN login_edit_tpl -->
        <input tabindex="6" type="text" size="30" name="Login" value="{login_value}"/>
        <!-- END login_edit_tpl -->
        <!-- BEGIN login_view_tpl -->
        {login_value}
        <input type="hidden" name="Login" value="{login_value}"/>
        <!-- END login_view_tpl -->
        </td>
</tr>
<tr>
	<td colspan="2" style="padding-top: 4px;">
	 <div class="p" align="center" style="width: 64%;">
	 <div style="padding-bottom: 12px;">{intl-infosubscription}&nbsp;<input {info_subscription} type="checkbox" name="InfoSubscription" tabindex="7" style="position: relative; top: 3px;" />&nbsp;</div>
         </div>
	 <!-- <hr noshade="noshade" size="4" /> -->

</td>
</tr>
<tr>
	<td colspan="2">

	<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
	<tr>
	 <td valign="top">
	  <input type="hidden" name="UserID" value="{user_id}" />
	  <input class="okbutton" type="submit" value="{intl-btn_submit}" tabindex="8" />
	  <input type="hidden" name="RedirectURL" value="{redirect_url}" tabindex="9" />
	 </form>
	</td>
	<td>&nbsp;</td>
	 <td valign="top">
	  <!-- <form action="{www_dir}{index}/">
	   <input class="okbutton" type="submit" value="{intl-abort}">
	  </form> -->

	  <!-- BEGIN skip_link_tpl -->
          <div><a href="{skip_url}" style="font-size: 12px;">Skip >></a><br /></div>
	  <!-- END skip_link_tpl -->
	 </td>
	</tr>
	</table>

  </td> 
 </tr>
</table>
