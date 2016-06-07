<?php
session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/head.php');

if($_SESSION["perfil"]==6)
	header("Location: relimpressoras.php");
else
	if($_SESSION["perfil"] != 2)
			$limitaUnidade = "AND printers.codunidade = ".$_SESSION["uni"];
?>
		<html>
		<head>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<script language="JavaScript">
		function Valida(formname,name)
{
	var checked = false;
				var i,aux,relatorio;
				aux = false;
				for(i=0; i<document.forms[formname].length; i++)
{
	if (document.forms[formname].elements[i].type == "radio")
		if (document.forms[formname].elements[i].name.substr(0,3) != "all")
		if (document.forms[formname].elements[i].checked)
{
	relatorio = document.forms[formname].elements[i].value;
								if ((relatorio == 1) || (relatorio == 4) || (relatorio == 5) || (relatorio == 6) || (relatorio == 9) || (relatorio == 10))
		aux = true;
								checked = true;
}
}

				if (!checked)
{
	alert("Não existe nenhum(a) \"" + name + "\" selecionado(a).");
					return false;
}

				if ((aux) && (document.frm_relatorio.limit.value == ""))
{
	alert('Quando habilitado o campo de \"N° de Registro\" é de preenchimento obrigatório');
					document.frm_relatorio.limit.focus();
					return false;
}

				//document.forms[formname].action = 'gera_relatorio.php';
				//document.forms[formname].submit();
				show_window('gera_relatorio.php?rel=' + relatorio + '&limit=' + document.forms[formname].limit.value,'Relatorio',800,600);
}

			function ValidaRadio(formname)
{
	for(i=0; i<document.forms[formname].length; i++)
{
	if (document.forms[formname].elements[i].type == "radio")
		if (document.forms[formname].elements[i].checked)
		if ((document.forms[formname].elements[i].value == 1) || (document.forms[formname].elements[i].value == 4) || (document.forms[formname].elements[i].value == 5) || (document.forms[formname].elements[i].value == 6) || (document.forms[formname].elements[i].value == 9) || (document.forms[formname].elements[i].value == 10))
{
	document.frm_relatorio.limit.focus();
								document.frm_relatorio.limit.readOnly = false;
								document.frm_relatorio.limit.style.backgroundColor = 'white';
}
							else
{
	document.frm_relatorio.limit.value = '';
								document.frm_relatorio.limit.readOnly = true;
								document.frm_relatorio.limit.style.backgroundColor = 'silver';
}
}

}
		</script>
		<title>Cadastro de Impressoras</title>
		</head>

		<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td height="100%" valign="top" width="100%">
		<form method="post" name="frm_relatorio">
		<br>
		<table border="0" width="100%">
		<tr>
		<td><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;</font></td>
		<td>
		<fieldset>
		<legend accesskey="C">&nbsp;<font size="2"><b>Tipos de Relat&oacute;rios</b></font>&nbsp;</legend>
		<table border="0" width="100%">
		<tr>
		<td>
		<table  border="0" width="100%">
		<tr>
		<td class="titTable"><font face="Verdana,Arial,Helvetica,sans-serif" size="1">&nbsp;Usu&aacute;rios</td>
		<td class="titTable"><font face="Verdana,Arial,Helvetica,sans-serif" size="1">&nbsp;Ilhas</td>
		<td class="titTable"><font face="Verdana,Arial,Helvetica,sans-serif" size="1">&nbsp;Unidades</td>
		<td class="titTable"><font face="Verdana,Arial,Helvetica,sans-serif" size="1">&nbsp;Impressoras</td>
		<!--td><font face="Verdana,Arial,Helvetica,sans-serif" size="1">&nbsp;Grupo</td-->
		</tr>
		<tr>
		<td nowrap="true" bgcolor="#f9f9f9">
		<font face="Verdana,Arial,Helvetica,sans-serif" size="1">
		<input type="radio" name="relatorio" value="1" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;Impress&otilde;es p/Usu&aacute;rio<br>
		<input type="radio" name="relatorio" value="2" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;P&aacute;ginas p/Usuario&nbsp;<br>
		<input type="radio" name="relatorio" value="3" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;Custo p/Usu&aacute;rio&nbsp;<br>
		</font>
		</td>
		<td nowrap="true" bgcolor="#f9f9f9">
		<font face="Verdana,Arial,Helvetica,sans-serif" size="1">
		<input type="radio" name="relatorio" value="4" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;Impress&otilde;es p/Ilha&nbsp;<br>
		<input type="radio" name="relatorio" value="5" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;P&aacute;ginas/Custo p/Ilha&nbsp;<br>
		</font>
		</td>
		<td nowrap="true" bgcolor="#f9f9f9">
		<font face="Verdana,Arial,Helvetica,sans-serif" size="1">
		<input type="radio" name="relatorio" value="6" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;Impress&otilde;es p/Unidade&nbsp;<br>
		<input type="radio" name="relatorio" value="7" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;P&aacute;ginas/Custo p/Unidade&nbsp;<br>
		</font>
		</td>
		<td nowrap="true" bgcolor="#f9f9f9">
		<font face="Verdana,Arial,Helvetica,sans-serif" size="1">
		<input type="radio" name="relatorio" value="9" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;Impress&otilde;es p/Impressora&nbsp;<br>
		<input type="radio" name="relatorio" value="10" onclick="javascript:ValidaRadio(this.form.name);">&nbsp;P&aacute;ginas/Custo p/Impressora&nbsp;<br>
		</font>
		</td>
		</tr>
		<tr>
		<td colspan="4"><hr size="1">
		&nbsp;N&deg; Registros:&nbsp;<input type="text" size="3" name="limit" onblur="javascript:FormataFloat(this.form.name,this.name,0,'',3,event);" onchange="javascript:FormataFloat(this.form.name,this.name,0,'',3,event);">
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</fieldset>
		</td>
		<td width="50%">&nbsp;</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
		<td>&nbsp;</td>
		<td colspan="2"><input type="button" class="button" name="gravar" value="Gerar Relat&oacute;rio" onclick="javascript:return Valida(this.form.name,'Relat&oacute;rio');"></td>
		</tr>
		</table>
		<br><br>
		</form>
		</td>
		</tr>
		</legend>
		</td>
		</tr>
		<tr><?php include('inc/foot.php');?></tr>
		</table>
	</body>
</html>
