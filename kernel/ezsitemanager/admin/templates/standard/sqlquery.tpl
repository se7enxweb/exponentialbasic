<h1>{intl-sql_query}</h1>

<b>{intl-notice}</b>: {intl-only_for_advanced_users}
<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/sitemanager/sqladmin/query" method="post">

{query_text2}&nbsp;<input type="submit" name="Run2" value="{intl-run2}" /> 
<br />

<textarea name="QueryText" cols="80" rows="4">{query_text}</textarea><br />

<input type="submit" name="Export" value="{intl-save_query}" />
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="Run" value="{intl-run}" />&nbsp;

<hr noshade="noshade" size="4" />

</form>

{intl-query_rows}:&nbsp;{query_rows}

{query_result}
{error}
