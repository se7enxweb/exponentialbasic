<form method="post" action="{www_dir}{index}/user/settings/{action_value}/">

<h1>{intl-headline} {first_name} {last_name}</h1>

<hr noshade="noshade" size="4" />

<br />
<!-- BEGIN module_tab_item_tpl -->
<input type="checkbox" name="ModuleTabBar" {module_tab} value="enabled"/>
<span class="boxtext">{intl-module_tab}</span><br />
<!-- END module_tab_item_tpl -->
<input type="checkbox" name="SingleModule" {single_module} />
<span class="boxtext">{intl-single_module}</span><br />

<input type="hidden" name="RefURL" value="{ref_url}" />

<br />
<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</td>	
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-abort}" />
	</td>
</tr>
</table>
</form>			
