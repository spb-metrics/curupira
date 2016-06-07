<?php
session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/head.php');
?>
		<html>
		<head>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<script language="JavaScript">
		function Valida()
{
	if(document.frm_ilha.ilha.value == '')
{
	alert('O campo "Nome Ilha" é de preenchimento obrigatório');
				document.frm_ilha.ilha.focus();
				return false;
}

			document.frm_ilha.action = 'inclui_ilha.php';
			document.frm_ilha.submit();
}

		function ExcluirIlha(url)
{
	if (confirm("ATENÇÃO:\nAo excluir uma ilha, será excluído também tudo relacionado a ela. Tem certeza que deseja excluir esse ilha?"))
{
	document.frm_ilha.action = url;
				document.frm_ilha.submit();
}
}
		</script>
		<TITLE>Cadastro de Ilhas de Impress&aacute;o</TITLE>
		</head>

		<?php
		if (isset($_GET["id"]) && $_GET["id"] != "")
{
	$CodIlha = $_GET["id"];
	$SQL = pg_query("SELECT nomeilha FROM ilhas WHERE codilha = ".$CodIlha) or die ("Ilha não encontrada.");
	$rsUpdate = pg_fetch_row($SQL);
	$NomeIlha = $rsUpdate[0];
}
else
	$NomeIlha = "";
?>

		<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td height="100%" valign="top" width="100%">
		<form method="post" name="frm_ilha">
		<?php if (isset($CodIlha) && $CodIlha != "") echo '<input type="hidden" name="codilha" value="'.$CodIlha.'">';?>
		<br>
				<fieldset>
				<legend accesskey="C"><font size="2"><b>&nbsp;Cadastro de Ilhas de Impress&atilde;o&nbsp;</b></font></legend>
				<table border="0" width="100%">
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Nome Ilha:&nbsp;</font></td>
				<td width="100%">
				<?php
				echo '												<input type="text" name="ilha" style="font-size:8pt" size="40" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Ilha\');" value="'.$NomeIlha.'">';
		?>&nbsp;</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
				<td>&nbsp;</td>
				<td>
				<input type="button" class="button" name="gravar" value="Gravar" onclick="javascript:return Valida();">
				<font size="1" color="red">(* Campos de preenchimento obrigat&oacute;rio)</font>
				</td>
				</tr>
				</table>
				</fieldset>
				<br>
				<table border="0" width="100%">
				<tr>
				<td valign="top" height="100%" colspan="2">
				<table border="0" width="100%">
				<tr>
				<td class="titTable" colspan="5">Lista de Ilhas</td>
				</tr>
				<tr>
				<th width="100%">&nbsp;Ilha</th>
				<th class="CENTER">A&ccedil;&atilde;o</th>
				</tr>
				<?php
				$SQL = pg_query("SELECT * FROM ilhas ORDER BY nomeilha");

		$Cor = "";
		while ($rs = pg_fetch_row($SQL))
{
	if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
	echo
			'												<tr>
			<td width="50%" bgcolor="".$Cor."" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[1].'</font></td>
			<td nowrap="true" bgcolor='.$Cor.' valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<a href="cad_ilha.php?id='.$rs[0].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:ExcluirIlha(\'exclui_ilha.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir"></a>&nbsp;</font></td>
			</tr>';
}
												?>
														</table>
														</td>
														</tr>
														</table>
														</form>
														</td>
														</tr>
														</table>
														</body>
														</td>
														</tr>
														<tr><?php include('inc/foot.php');?></tr>
														</table>
																</html>
