<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="tdminipath" width="1%"><img src="{www_dir}/images/1x1.gif" width="1" height="38"></td>
	<td class="tdminipath" align="left" width="99%">

	<img src="{www_dir}/sitedesign/designsection1/images/path-arrow-top.gif" width="16" height="10" border="0" alt="" />
	<a class="toppath" href="{www_dir}{index}/forum/categorylist/">{intl-forum-main}</a> 

	</td>
</tr>
<tr>
	<td class="toppathbottom" colspan="2"><img src="{www_dir}/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
        <form action="{www_dir}{index}/forum/search/" method="post">
           <input type="text" name="QueryString" size="12" />
           <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th colspan="2" width="1%">{intl-name}:</th>
   	<th>{intl-desc}:</th>
</tr>


<!-- BEGIN category_item_tpl -->
<tr bgcolor="{color}">
    <td class={td_class} width="1%">
    <img src="{www_dir}/images/folder.gif" width="16" height="16" border="0" />
	</td>
    <td class={td_class} width="50%">
    <a href="{www_dir}{index}/forum/forumlist/{category_id}/">{category_name}</a>
    </td>
    <td class={td_class} width="49%">
    <span class="small">{category_description}</span>
    </td>
</tr>
<!-- END category_item_tpl -->
</table>

