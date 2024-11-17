<h1>{intl-ad_statistics}</h1>

<hr noshade="noshade" size="4" />

<h2>{ad_title}</h2>

<p>{ad_description}</p>

<p class="boxtext">{intl-banner}:</p>
<!-- BEGIN image_tpl -->
<img src="{www_dir}{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_tpl -->

<!-- BEGIN html_item_tpl -->
{html_banner}
<!-- END html_item_tpl -->

<p class="boxtext">{intl-total_view_count}:</p>
{ad_view_count}

<p class="boxtext">{intl-total_click_count}:</p>
{ad_click_count}

<p class="boxtext">{intl-total_click_percentage}:</p>
{ad_click_percent}

<p class="boxtext">{intl-total_view_revenue}:</p>
{ad_view_revenue}

<p class="boxtext">{intl-total_click_revenue}:</p>
{ad_click_revenue}

<p class="boxtext">{intl-total_revenue}:</p>
{ad_total_revenue}

<br /><br />

<form action="{www_dir}{index}/ad/ad/edit/{ad_id}/" method="post" >

<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" value="{intl-edit}" />
</form>

