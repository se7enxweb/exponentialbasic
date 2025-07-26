<form action="{www_dir}{index}/survey/stats/{survey_id}/" method="post">

<h1>{intl-stats}</h1>

<hr noshade="noshade" size="4" />

<input type="hidden" name="Page" value="{page_number}" />

<h2>{title}</h2>
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
<!-- BEGIN value_list_tpl -->
  <tr>
    <td>{question_name}</td>
  </tr>
  <!-- BEGIN question_tpl -->
  <tr>
    <td>
    <!-- BEGIN question_item_tpl -->
      <table width="50%" cellspacing="0">
        <tr>
          <td><nobr>{choice_name} {choice_perc}%</nobr></td>
          <td align="right">Total: {choice_total}</td>
        </tr>
      </table>
      <table width="50%" cellspacing="0">
        <tr height="15">
          <td bgcolor="#ffcc00" width="{choice_perc}%"></td>
          <td bgcolor="#eeeeee" width="{choice_left}%"></td>
        </tr>
      </table>
    <!-- END question_item_tpl -->
    </td>
  </tr>
  <!-- END question_tpl -->
  <tr>
    <td>&nbsp;</td>
  </tr>
<!-- END value_list_tpl -->
</table>

Page {page_number} of {page_total}

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<!-- BEGIN previous_page_button_tpl -->
  <input class="okbutton" type="submit" name="PreviousPage" value="{intl-previous_page}" />&nbsp;
<!-- END previous_page_button_tpl -->
<!-- BEGIN next_page_button_tpl -->
  <input class="okbutton" type="submit" name="NextPage" value="{intl-next_page}" />&nbsp;
<!-- END next_page_button_tpl -->

</form>
