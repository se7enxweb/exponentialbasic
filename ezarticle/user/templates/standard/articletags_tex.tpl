<!-- VAR br=\\\ -->

<!-- BEGIN header_1_tpl -->

\section{{contents}}

<!-- END header_1_tpl -->

<!-- BEGIN header_2_tpl -->

\subsection{{contents}}

<!-- END header_2_tpl -->

<!-- BEGIN header_3_tpl -->

\subsubsection{{contents}}

<!-- END header_3_tpl -->

<!-- BEGIN header_4_tpl -->

\subsubsubsection{{contents}}

<!-- END header_4_tpl -->

<!-- BEGIN header_5_tpl -->
<h5>{contents}</h5>
<!-- END header_5_tpl -->

<!-- BEGIN header_6_tpl -->
<h6>{contents}</h6>
<!-- END header_6_tpl -->

<!-- BEGIN image_tpl -->
   <!-- BEGIN image_link_tpl -->

   <!-- END image_link_tpl -->
   <!-- BEGIN ext_link_tpl -->

   <!-- END ext_link_tpl -->
   <!-- BEGIN no_link_tpl -->

   <!-- END no_link_tpl -->
\begin{figure}[h]
\begin{center}
\scalebox{0.60}{
\includegraphics{../../ezimagecatalogue/catalogue/{image_file_name}.eps}
}
<!-- BEGIN image_text_tpl -->

\caption{{caption}}

<!-- END image_text_tpl -->

\end{center}

\end{figure}

<!-- END image_tpl -->

<!-- BEGIN image_float_tpl -->
   <!-- BEGIN image_link_float_tpl -->
   <a target="{target}" href="{www_dir}{index}/imagecatalogue/imageview/{image_id}/?RefererURL={referer_url}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END image_link_float_tpl -->
   <!-- BEGIN ext_link_float_tpl -->
   <a href="{www_dir}{index}{image_href}">
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   </a>   
   <!-- END ext_link_float_tpl -->
   <!-- BEGIN no_link_float_tpl -->  
   <img src="{www_dir}{image_url}" border="0" width="{image_width}" height="{image_height}" alt="" />
   <!-- END no_link_float_tpl -->
<!-- END image_float_tpl -->

<!-- BEGIN link_tpl -->
<a href="{www_dir}{index}{href}" target="{target}" >{link_text}</a>
<!-- END link_tpl -->

<!-- BEGIN popuplink_tpl -->
<a href="{href}" target="_new" >{link_text}</a>
<!-- END popuplink_tpl -->


<!-- BEGIN bold_tpl -->

\begin{bf}

{contents}

\end{bf}

<!-- END bold_tpl -->

<!-- BEGIN italic_tpl -->

\begin{em}
{contents}
\end{em}

<!-- END italic_tpl -->

<!-- BEGIN underline_tpl -->
<u>{contents}</u>
<!-- END underline_tpl -->

<!-- BEGIN strong_tpl -->
<font color="885522" ><strong>{contents}</strong></font>
<!-- END strong_tpl -->

<!-- BEGIN factbox_tpl -->
<table bgcolor="#555555" width="250" align="right" cellspacing="2" cellpadding="2" >
<tr>
	<td bgcolor="#eeeeee" >
	{contents}
	</td>
</tr>
</table>
<!-- END factbox_tpl -->

<!-- BEGIN bullet_tpl -->

\begin{itemize}

	<!-- BEGIN bullet_item_tpl -->

\begin{item}
{contents}
\end{item}

	<!-- END bullet_item_tpl -->
\end{itemize}

<!-- END bullet_tpl -->

<!-- BEGIN list_tpl -->

\begin{enumerate}

	<!-- BEGIN list_item_tpl -->

 \begin{item}
 {contents}
 \end{item}

	<!-- END list_item_tpl -->
 \end{enumerate}

<!-- END list_tpl -->

<!-- BEGIN quote_tpl -->
<blockquote>
{contents}
</blockquote>
<!-- END quote_tpl -->

<!-- BEGIN pre_tpl -->
<table width="100%" bgcolor="#eeeeee" >
<tr>
	<td>
	<pre>{contents}</pre>
	</td>
</tr>
</table>
<!-- END pre_tpl -->

<!-- BEGIN media_tpl -->
<embed src="{www_dir}{media_uri}" {attribute_string} />
<!-- END media_tpl -->

<!-- BEGIN file_tpl -->
<a href="{www_dir}{file_uri}">{text}</a>
<!-- END file_tpl -->


<!-- BEGIN table_tpl -->
<br clear="all" />
<table width="{table_width}" >
<tr>
<td bgcolor="#aaaaaa">
<table width="100%" border="{table_border}" cellpadding="2" cellspacing="2">
<!-- BEGIN tr_tpl -->
<tr>
<!-- BEGIN td_tpl -->
    <td width="{td_width}" colspan="{td_colspan}" rowspan="{td_rowspan}" valign="top"  bgcolor="#ffffff">
    {contents}
    </td>
<!-- END td_tpl -->
</tr>
<!-- END tr_tpl -->
</table>
</td>
</tr>
</table>
<!-- END table_tpl -->

<!-- BEGIN logo_tpl -->
<a href="developer.ez.no">eZ publish</a>{contents}
<!-- END logo_tpl -->
