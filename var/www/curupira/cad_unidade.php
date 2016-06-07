<?php
Session_start();
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
	if(document.frm_unidade.cgcunidade.value == '')
{
	alert('O campo "CGC Unidade" é de preenchimento obrigatório');
				document.frm_unidade.cgcunidade.focus();
				return false;
}

			if(document.frm_unidade.nomeunidade.value == '')
{
	alert('O campo "Nome Unidade" é de preenchimento obrigatório');
				document.frm_unidade.nomeunidade.focus();
				return false;
}

			document.frm_unidade.action = 'inclui_unidade.php';
			document.frm_unidade.submit();
}

		function ExcluirUnidade(url)
{
	if (confirm("ATENÇÃO:\nAo excluir uma unidade, será excluído também tudo relacionado a ela. Tem certeza que deseja excluir essa unidade?"))
{
	document.frm_unidade.action = url;
				document.frm_unidade.submit();
}
}
		</script>
		<TITLE>Cadastro de Unidades</TITLE>
		</head>

		<?php
		if (isset($_GET["id"]) &&$_GET["id"] != "")
{
	$CodUnidade = $_GET["id"];
	$SQL = pg_query("SELECT codunidade, nomeunidade, endereco, codilha FROM unidades WHERE codunidade = ".$CodUnidade) or die ("Unidade não encontrada.");
	$rsUpdate = pg_fetch_row($SQL);
	$CodUnidade = $rsUpdate[0];
	$NomeUnidade = $rsUpdate[1];
	$Endereco = $rsUpdate[2];
	$CodIlha = $rsUpdate[3];
	$sel = "readonly"." disabled";
}
else
{
	$CodUnidade = "";
	$NomeUnidade = "";
	$Endereco = "";
	$CodIlha = "null";
}
?>

		<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td height="100%" valign="top" width="100%">
		<form method="post" name="frm_unidade">
		<?php if ($CodUnidade != "") echo '<input type="hidden" name="codunidade" value="'.$CodUnidade.'" />
';?>
		<br>
				<fieldset>
				<legend accesskey="C">&nbsp;<font size="2"><b>Cadastro de Unidades</b></font>&nbsp;</legend>
				<table border="0" width="100%">
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>CGC Unidade:&nbsp;</font></td>
				<td>
				<?php
				if(!isset($sel))
				$sel = "";

		echo '<input '.$sel.' type="text" name="cgcunidade" style="font-size:8pt" size="5" maxlength="4" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'CGC Unidade\');" value="'.$CodUnidade.'" />
';
		?>&nbsp;</td>
				</tr>
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Nome Unidade:&nbsp;</font></td>
				<td>
				<?php
				echo '<input type="text" name="nomeunidade" style="font-size:8pt" size="40" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Unidade\');" value="'.$NomeUnidade.'">
';
		?>&nbsp;</td>
				</tr>
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Ilhas:&nbsp;</font></td>
				<td width="100%">
				<?php
				if ($CodIlha == "null")
				$strSQL = "SELECT * FROM ilhas ORDER BY nomeilha";
		else
			$strSQL = "SELECT codilha, nomeilha, CASE WHEN (codilha = '".$CodIlha."') THEN 1 ELSE 0 END as flg FROM ilhas ORDER BY nomeilha";
		$SQL = pg_query($strSQL);

		?>
				<select name="ilha" style="font-size:8pt">
				<option value="0">:: Ilhas de Impress&atilde;o ::</option>
				<?php
				while ($rs = pg_fetch_row($SQL))
{
	if ($rs[2] == 1)
		$Sel = 'selected="true"';
	else
		$Sel = "";
	echo '<option '.$Sel.' value="'.$rs[0].'|'.$rs[1].'">'.$rs[1].'</option>
';
}
													?>
															</select>
															</td>
															</tr>

															<tr>
															<td valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Endere&ccedil;o:</font></td>
															<td width="100%">
															<?php
															echo '<textarea name="endereco" style="font-size:8pt" wrap="soft" rows="6" cols="50" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Endere&ccedil;o\');" onkeyup="javascript:CountMaxChar(this.form.name,this.name,\'Endere&ccedil;o\',\'maxcaracteres\',1000);" onchange="javascript:CountMaxChar(this.form.name,this.name,\'Endere&ccedil;o\',\'maxcaracteres\',1000);">'.$Endereco.'</textarea><br />
													<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input class="semborder" style="font-size:8pt" type="text" name="maxcaracteres" size="3" value="1000" readonly="true" disabled="true"/>&nbsp;caracteres dispon&iacute;veis</font>
';
													?>&nbsp;</td>
															</tr>
															<tr><td colspan="2">&nbsp;</td></tr>
															<tr>
															<td>&nbsp;</td>
															<td><input type="button" class="button" name="gravar" value="Gravar" onclick="javascript:return Valida();">
															<font size="1" color="red">(* Campos de preenchimento obrigat&oacute;rio)</font></td>
															</tr>
															</table>
															</fieldset>
															<br>
															<table border="0" width="100%">
															<tr>
															<td valign="top" height="100%" colspan="2">
															<table border="0" width="100%">
															<tr>
															<td class="titTable" colspan="5">Listagem de Unidades</td>
															</tr>
															<tr>
															<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Nome Undiade</b>&nbsp;</font></td>
															<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Endere&ccedil;o</b>&nbsp;</font></td>
															<td nowrap="true" bgcolor="#e9e9e9" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>A&ccedil;&atilde;o</b>&nbsp;</font></td>
															</tr>
															<?php
															$SQL = pg_query("SELECT * FROM unidades ORDER BY nomeunidade");
													$Cor = "";
													while ($rs = pg_fetch_row($SQL))
{
	if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
	echo '<tr>
<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">('.$rs[0].') '.$rs[1].'</font></td>
<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[2].'</font></td>
<td nowrap="true" bgcolor="'.$Cor.'" valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="cad_unidade.php?id='.$rs[0].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:ExcluirUnidade(\'exclui_unidade.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir"></a></font></td>
</tr>
';
}
											$total=pg_num_rows($SQL);
											echo '<tr class="titTable">
											<td  colspan="5" align="center"> '.$total.' Unidades(s)</td></tr>
';
											?>
												</table>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><?php include('inc/foot.php');?></tr>
		</table>
	</body>
</html>