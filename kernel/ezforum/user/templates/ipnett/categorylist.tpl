<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td align="left" valign="bottom">
        <h1>{intl-headline}</h1>
     </td>
     <td align="right">
        <form action="/forum/search/" method="post">
           <input class="searchbox" type="text" name="QueryString" size="10" />
           <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
        </form>
     </td>
  </tr>
</table>

	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
	<a class="path" href="/forum/categorylist/">{intl-forum-main}</a>

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
   	<th colspan="2" width="1%">{intl-name}:</th>
   	<th>{intl-desc}:</th>
</tr>


<!-- BEGIN category_item_tpl -->
<tr bgcolor="{color}">
    <td class={td_class} width="1%">
    <img src="/images/folder.gif" width="16" height="16" border="0" />
	</td>
    <td class={td_class} width="50%">
    <a href="/forum/forumlist/{category_id}/">{category_name}</a>
    </td>
    <td class={td_class} width="49%">
    <span class="small">{category_description}</span>
    </td>
</tr>
<!-- END category_item_tpl -->
</table>

