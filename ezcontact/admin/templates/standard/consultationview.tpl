<h1>{intl-consultation_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN company_contact_item_tpl -->
<p>{intl-new_company_consultation_for} {company_name}</p>
<!-- END company_contact_item_tpl -->
<!-- BEGIN person_contact_item_tpl -->
<p>{intl-new_person_consultation_for} {person_lastname}, {person_firstname}</p>
<!-- END person_contact_item_tpl -->

<!-- BEGIN consultation_item_tpl -->

<!-- BEGIN consultation_date_item_tpl -->
<p class="boxtext">{intl-date}:</p>
{consultation_date}
<!-- END consultation_date_item_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
	    <td width="50%">
	    <p class="boxtext">{intl-shortdescription}:</p>
	    {short_description}<br /><br />
	    </td>

	    <td width="50%">
	    <p class="boxtext">{intl-status}:</p>
	    {status_name}<br /><br />
	    </td>
    </tr>
    <tr>
	    <td width="50%" valign="top">
	    <p class="boxtext">{intl-description}:</p>
	    {description}<br /><br />
	    </td>

	    <td>
	    <p class="boxtext">{intl-group_notice}:</p>
	    <!-- BEGIN group_notice_select_tpl -->
	    {group_notice_name}
	    <!-- END group_notice_select_tpl -->
	    <!-- BEGIN no_group_notice_tpl -->
	    {intl-no_group_notice}
	    <!-- END no_group_notice_tpl -->
		<br /><br />
	    </td>
    </tr>
    <tr>
	    <td width="50%" valign="top">
	    <p class="boxtext">{intl-email_notice}:</p>
	    {email_notification}<br /><br />
	    </td>

	    <td width="50%">
	    <p class="boxtext">{intl-owner}:</p>
	    {owner}<br /><br />
	    </td>
    </tr>
</table>

<!-- END consultation_item_tpl -->


<br />


<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
        <form method="post" action="{www_dir}{index}/contact/consultation/edit/{consultation_id}/" enctype="multipart/form-data">
	<input class="okbutton" type="submit" value="{intl-edit}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{www_dir}{index}/contact/consultation/list/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

