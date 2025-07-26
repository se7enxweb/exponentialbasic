<h1>{intl-tip_statistics}</h1>

<hr noshade size="4" />

<h2>{tip_title}</h2>

<p>{tip_description}</p>

<p class="boxtext">{intl-banner}:</p>
<!-- BEGIN image_tpl -->
<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_tpl -->

<!-- BEGIN html_item_tpl -->
{html_banner}
<!-- END html_item_tpl -->

<p class="boxtext">{intl-total_view_count}:</p>
{tip_view_count}

<p class="boxtext">{intl-total_click_count}:</p>
{tip_click_count}

<p class="boxtext">{intl-total_click_percentage}:</p>
{tip_click_percent} <br />
<br />

<form action="{www_dir}{index}/tip/tip/edit/{tip_id}/" method="post" >

<hr noshade size="4" />

<input type="submit" class="okbutton" value="{intl-edit}" />
</form>
