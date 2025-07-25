<HTML>
<HEAD>
<TITLE>Form POPUP!</TITLE>
<SCRIPT language="JavaScript" type="text/javascript">    
function checkFields()
{
	thetype = document.shareform.type.value;
	val= "";
	for (var i = 0; i < document.shareform.elements.length; i++)
	{
		var e=document.shareform.elements[i];
		if (e.checked)
		{
			val+=e.value+", ";
		}
	}
	
	if (document.shareform.SelectedReceiver.value) val+=document.SelectedReceiver.Receiver.value;
	if(thetype=="to")
	{

		window.opener.document.form.Receiver.value=val;
	}

	window.close();
	return false;
}
</SCRIPT>
</HEAD>

<BODY>

<CENTER>

<FORM action="?" method="get" name="shareform" onsubmit="return checkFields()">
<SCRIPT language="javascript" type="text/javascript">
	var aec=1;
        function GrayBottom(selv) {
        var state=selv.checked;
	var elms=selv.form.elements;
        for(var i=0;elms[i];i++) {
            if(elms[i].type=="checkbox" && elms[i].name!="SelectedReceiver") {
                SwitchQ(elms[i]);
                elms[i].checked=state;
            }
        }
    }
    function SwitchQ(selv) {
        if(selv.className!="Switch") {
            selv.className="Switch";
            aec--;
        } else {
            selv.className="";
            aec++;
        }
        GrayBottomDisable(selv);
    }
    function GrayBottomDisable(selv) {
        var state=(aec==1)?true:false;
        selv.form.Pick2.disabled=state;
    }
</SCRIPT>
<!-- BEGIN message_list_tpl -->

<TABLE cellpadding="0" cellspacing="0" width="100%">
	<TR>
		<td>&nbsp;</td>
		<td>Navn</td>
		<td>V&aelig;lg</td>
		<td>login</td>
	</TR>
<!-- BEGIN message_item_tpl -->
	<!-- BEGIN message_user_tpl -->
	<TR>
		<TD><A name="A"></A>&nbsp;</TD>
		<TD>{first_name} {last_name}</TD>
		<TD><INPUT onclick="SwitchQ(this)" type="Checkbox" value="{login_name}"></TD>
		<TD>{login_name}</TD>
	</TR>
	<!-- END message_user_tpl -->
	<!-- END message_item_tpl -->
	<TR>
		<td colspan="4" align="right">
			<INPUT name="SelectedReceiver" onblur="if(this.value==''){document.forms[0].Pick2.disabled = true;}" onfocus="document.forms[0].Pick2.disabled = false" size="30" type="hidden"> 
			<INPUT name="type" size="30" type="Hidden" value="to"> 
			<INPUT disabled name="Pick2" type="submit" value=" Ok "> 
			<INPUT name="NoPick2" onclick="javascript:picker = window.close();" type="button" value="Fortryd"> 
		</td>
	</TR>
</TABLE>
<!-- END message_list_tpl -->
</FORM>

</CENTER>
</BODY>
</HTML>