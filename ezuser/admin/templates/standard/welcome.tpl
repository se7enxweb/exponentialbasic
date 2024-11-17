<h1>{intl-welcome} - {first_name} {last_name}</h1>

<hr noshade="noshade" size="4" />

{intl-welcome_message}

<br />

<!-- BEGIN error_tpl -->
<hr noshade="noshade" size="4" />
<h3 class="error">{intl-error_headline}</h3>
<ul>
     <!-- BEGIN convert_error_tpl -->
     <li />{intl-convert_error} <a href="{convert_location}">{convert_location}</a>
     <!-- END convert_error_tpl -->
</ul>
<!-- END error_tpl -->
