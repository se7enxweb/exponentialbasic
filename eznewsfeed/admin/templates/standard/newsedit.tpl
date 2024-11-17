<form method="post" action="{www_dir}{index}/newsfeed/news/">

<h1>{intl-news_edit}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-news_title}:</p>
<input type="text" class="box" size="40" name="NewsTitle" value="{news_title_value}" />

<p class="boxtext">{intl-news_category}:</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-news_source}:</p>
<input type="text" class="box" size="40" name="NewsSource" value="{news_source_value}" />

<p class="boxtext">{intl-news_date}:</p>
<input type="text" size="4" name="Year" value="{news_year_value}" />
<input type="text" size="2" name="Month" value="{news_month_value}" />
<input type="text" size="2" name="Day" value="{news_day_value}" />
-
<input type="text" size="2" name="Hour" value="{news_hour_value}" />
<input type="text" size="2" name="Minute" value="{news_minute_value}" />
<input type="text" size="2" name="Second" value="{news_second_value}" />

<p class="boxtext">{intl-news_intro}:</p>
<textarea class="box" cols="40" rows="5" wrap="soft" name="NewsIntro">{news_intro_value}</textarea>

<p class="boxtext">{intl-news_url}: <a target="_blank" href="{www_dir}{index}{news_url_value}">{news_url_value} </a></p> 
<input class="box" type="text" size="40" name="NewsURL" value="{news_url_value}"/>

<!--
<p class="boxtext">{intl-news_keywords}:</p>
<input type="text" size="40" name="NewsKeywords" value="{news_keywords_value}"/>
-->

<br /><br />
<input type="checkbox" name="IsPublished" {news_is_published} />
<span class="boxtext">{intl-news_is_published}</span><br /><br />

<hr noshade="noshade" size="4" />

<input type="submit" class="okbutton" value="{intl-ok}" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
<input type="hidden" value="{action_value}" name="Action" />
<input type="hidden" value="{news_id}" name="NewsID" />
<input type="hidden" value="{old_category_id}" name="OldCategoryID" />

</form>