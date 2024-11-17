<script language="JavaScript1.2">

function getValue()
{
    document.MapForm.Values.value = document.ImageMapEditor.getAllElements();
    return true;
}

</script>

<h1>{intl-imagemap}: {article_name}</h1>

<hr noshade="noshade" size="4" />

<applet codebase="/" code="eZMapEditor.class" archive="ezimagemap.jar" width=600 height=400 name="ImageMapEditor">
<param name="Image" value="{image}" >
<!-- BEGIN element_tpl -->
<param name="Element{element_id}" value="{value}" />
<!-- END element_tpl -->
</applet>

<hr noshade="noshade" size="4" />

<form action="{www_dir}{index}/article/articleedit/imagemap/store/{image_id}/{article_id}/" name="MapForm" method="post" onSubmit="return getValue()">

<input type="hidden" value="aaa" name="Values">

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form action="{www_dir}{index}/article/articleedit/imagelist/{article_id}/" method="post">
	<input class="okbutton" type="submit" value="{intl-abort}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	</td>
</tr>
</table>
