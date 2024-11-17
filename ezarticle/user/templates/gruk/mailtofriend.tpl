<h1>{intl-header}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN first_page_tpl -->
<form method="post">
<!-- BEGIN err_msg_tpl -->
<h3 class="error">{intl-errors_occured}:</h3>
<!-- END err_msg_tpl -->

<!-- BEGIN err_real_name_tpl -->
<h3 class="error" >{intl-error_real_name}</h3>
<!-- END err_real_name_tpl -->

<!-- BEGIN err_send_to_tpl -->
<h3 class="error" >{intl-error_send_to}</h3>
<!-- END err_send_to_tpl -->

<!-- BEGIN err_from_tpl -->
<h3 class="error" >{intl-error_from}</h3>
<!-- END err_from_tpl -->

<h2>{Topic}</h2>
<p>{Intro}</p>
<br />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<p class="boxtext">{intl-send_to}:*</p>
	<input type="text" class="box" size="40" name="SendTo" value="{send_to}" />
	<br /><br />
	</td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-from_mail}:*</p>
	<input type="text" class="box" size="40" name="From" value="{from}">
	<br />
	</td>
</tr>
</table>

<p class="boxtext">{intl-comment}:</p>
<textarea name="Textarea" class="box" cols="40" rows="5">{textarea}</textarea>
<br /><br />

<p><span class="boxtext">*</span> <span class="small">{intl-required}</span></p>

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="Submit" value="{intl-send}">
<input class="okbutton" type="reset" name="Reset" value="{intl-reset}">
</form>

<!-- END first_page_tpl -->

<!-- *** The second page *** -->

<!-- BEGIN success_tpl -->
<h2>{intl-this_message_is_send_to} {to_name}</h2>
<p>{intl-mail_send_to} {server_name} {intl-mail_from}: <br />
{from_name}</p>
<br />
<h2>{header_text}</h2>
<p>{intro_text}</p>
<br />
<!-- BEGIN user_comment_tpl -->
<p class="boxtext">{intl-the_users_comment}:</p>
<div class="p">{user_comment}</div>
<!-- END user_comment_tpl -->

<p class="boxtext">{intl-url}:</p>
<a href="http://{site_url}/article/view/{art_id}">http://{site_url}/article/view/{art_id}</a><br />
<br />
<a class="path" href="http://{site_url}/article/mailtofriend/{art_id}">&lt;&lt;&nbsp;back</a>
<!-- END success_tpl -->