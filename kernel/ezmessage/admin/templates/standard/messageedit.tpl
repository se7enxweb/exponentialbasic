<form action="{www_dir}{index}/message/edit/" method="post">

<h1>{intl-message_edit} </h1>

<!-- BEGIN error_tpl -->
<h2 class="error">{intl-receiver_not_found} </h2>
<!-- END error_tpl -->

<hr size="4" noshade="noshade" />
<br />

<!-- BEGIN message_sent_tpl -->
{intl-message_sent}
<!-- END message_sent_tpl -->


<!-- BEGIN message_verify_tpl -->

<input type="hidden" name="Receiver" value="{receiver}" />
<input type="hidden" name="Subject" value="{subject}" />
<input type="hidden" name="Description" value="{description}" />

<input type="hidden" name="Action" value="Verify" />

<p class="boxtext">{intl-receiver_list}:</p>
<!-- BEGIN message_receiver_tpl -->
{first_name} {last_name} ( {login} )<br />
<!-- END message_receiver_tpl -->

<p class="boxtext">{intl-subject}:</p>

{subject}

<p class="boxtext">{intl-description}:</p>
{description}

<hr size="4" noshade="noshade" />
<br />

<input class="okbutton" type="submit" name="Edit" value="{intl-edit}" />

<input class="okbutton" type="submit" name="SendMessage" value="{intl-send}" />




<!-- END message_verify_tpl -->

<!-- BEGIN message_edit_tpl -->

<p class="boxtext">{intl-receiver}:</p>
<input class="box" type="text" name="Receiver" size="40" value="{receiver}" />
<br /><br />


<p class="boxtext">{intl-subject}:</p>
<input class="box" type="text" name="Subject" size="40" value="{subject}" />
<br /><br />

<p class="boxtext">{intl-description}:</p>
<textarea class="box" name="Description" cols="40" rows="5" wrap="soft">{description}</textarea>
<br /><br />

<hr size="4" noshade="noshade" />

<input type="hidden" name="Action" value="Verify" />

<input class="okbutton" type="submit" name="Preview" value="{intl-preview}" />

<!-- END message_edit_tpl -->


</form>
