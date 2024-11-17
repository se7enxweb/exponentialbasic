<h1>{intl-ext_search}</h1>

<hr noshade size="4" />

<form action="{www_dir}{index}/user/userlist/" method="post">
  <table border="0" cellspacing="0" cellpadding="4">
    <tr> 
      <td><b>{intl-last_name}</b></td>
      <td> 
        <input type="text" name="LastName" size="20" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-first_name}</b></td>
      <td> 
        <input type="text" name="FirstName" size="20" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-login}</b></td>
      <td> 
        <input type="text" name="Login" size="20" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-email}</b></td>
      <td> 
        <input type="text" name="EMail" size="20" />
      </td>
    </tr>
    <tr> 
      <td><b>{intl-match}</b></td>
      <td> 
        <select name="match">
          <option value="AND" selected>{intl-and}</option>
          <option value="OR">{intl-or}</option>
        </select>
      </td>
    </tr>
  </table>
  <p align="left">
    <input class="stdbutton" type="submit" name="Search" value="{intl-search}">
  </p>
</form>
