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
	if(document.frm_curupira.Ds_Link.value == '')
{
	alert('O campo "Descrição" é de preenchimento obrigatório');
					document.frm_curupira.Ds_Link.focus();
					return false;
}

				if(document.frm_curupira.Co_Filial.value == '')
{
	alert('O campo "Filial reponsável" é de preenchimento obrigatório');
					document.frm_curupira.Co_Filial.focus();
					return false;
}

				if(document.frm_curupira.End_Predio.value == '')
{
	alert('O campo "Endereço" é de preenchimento obrigatório');
					document.frm_curupira.End_Predio.focus();
					return false;
}

				if(document.frm_curupira.No_Servidor.value == '')
{
	alert('O campo "Nome do servidor" é de preenchimento obrigatório');
					document.frm_curupira.No_Servidor.focus();
					return false;
}

				if(document.frm_curupira.Ip_Servidor.value == '')
{
	alert('O campo "Ip Servidor" é de preenchimento obrigatório');
					document.frm_curupira.Ip_Servidor.focus();
					return false;
}

				document.frm_curupira.action = 'inclui_curupira.php';
				document.frm_curupira.submit();
}

			function ExcluirUnidade(url)
{
	if (confirm('ATENÇÃO:\Tem certeza que deseja excluir essa unidade?'))
{
	document.frm_curupira.action = url;
					document.frm_curupira.submit();
}
}
		</script>
		<TITLE>Cadastro de Pr&eacute;dios com Curupira</TITLE>
		</head>

		<?php

		$tabela ="tb_link";

if (isset($_GET["id"]) && $_GET["id"] != "")
{
	$CodUnidade = $_GET["id"];
	$SQL = pg_query("SELECT * FROM $tabela WHERE id = ".$CodUnidade) or die ("Unidade não encontrada.");
	$rsUpdate = pg_fetch_array($SQL);
}

else
{
	$rsUpdate = "";
}
?>

		<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td height="100%" valign="top" width="100%">
		<form method="post" name="frm_curupira">
		<?php if (isset($CodUnidade) && $CodUnidade != "") echo '<input type="hidden" name="codunidade" value="'.$CodUnidade.'">';?>
		<br />
				<fieldset>
				<legend accesskey="C">&nbsp;<font size="2"><b>Cadastro de Unidades com Curupira</b></font>&nbsp;</legend>
				<table border="0" width="100%">
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>UF :&nbsp;</font></td>
				<td>
				<?php
				echo '<input  type="text" name="Co_Filial" style="font-size:8pt" size="21" maxlength="20" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'CGC Unidade\');" value="';
		if (isset($rsUpdate["co_filial"]))
			echo $rsUpdate["co_filial"];
		echo '" >';
		?>&nbsp;</td>
				</tr>
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Pr&eacute;dio:&nbsp;</font></td>
				<td>
				<?php
				echo '<input type="text" name="Ds_Link" style="font-size:8pt" size="30" maxsize="30" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Unidade\');" value="';
		if (isset($rsUpdate["ds_link"]))
			echo $rsUpdate["ds_link"];
		echo '" >';
		?>&nbsp;</td>
				</tr>
				<tr>
				<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red"></font>Link:&nbsp;</font></td>
				<td>
				http://<?php echo '<input type="text" name="Link" style="font-size:8pt" size="50" maxsize="50" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Unidade\');" value="';
		if (isset($rsUpdate["link"]))
			echo $rsUpdate["link"];
		echo '" >';?>
				</td>
						</tr>

						<tr>
						<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Nome Servidor:&nbsp;</font></td>
						<td>
						<?php echo '<input type="text" name="No_Servidor" style="font-size:8pt" size="12" maxsize="11" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Unidade\');" value="';
				if(isset($rsUpdate["no_servidor"]))
					echo $rsUpdate["no_servidor"];
				echo '" >';?>
						</td>
								</tr>

								<tr>
								<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Ip Servidor:&nbsp;</font></td>
								<td>
								<?php echo '<input type="text" name="Ip_Servidor" style="font-size:8pt" size="12" maxsize="11" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Unidade\');" value="';
						if(isset($rsUpdate["ip_servidor"]))
							echo $rsUpdate["ip_servidor"];
						echo '" >';?>
								</td>
										</tr>

										<tr>
										<td valign="top" align="left">
										<font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<font color="red">*</font>Endere&ccedil;o:</font></td>
										<td width="100%">
										<?php
										if(!isset($rsUpdate["end_predio"]))
										$rsUpdate["end_predio"] = "";
								echo '<textarea name="End_Predio" style="font-size:8pt" wrap="soft" rows="6" cols="50" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Endere&ccedil;o\');" onkeydown="javascript:CountMaxChar(this.form.name,this.name,\'Endere&ccedil;o\',\'maxcaracteres\',1000);">'.$rsUpdate["end_predio"].'</textarea><br>
';
								echo '<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input class="semborder" style="font-size:8pt" type="text" name="maxcaracteres" size="3" value="1000" readonly="true" disabled="true"/>&nbsp;caracteres dispon&iacute;veis</font>';
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
										<br>
										<table border="0" width="100%">
										<tr>
										<td valign="top" height="100%" colspan="2">
										<table border="0" width="100%">
										<tr>
										<td class="titTable" colspan="7">Listagem de Unidades</td>
										</tr>
										<tr>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Pr&eacute;dio</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Link</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Endere&ccedil;o</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>UF</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Nome Servidor</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>Ip Servidor</b>&nbsp;</font></td>
										<td nowrap="true" bgcolor="#e9e9e9" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<b>A&ccedil;&atilde;o</b>&nbsp;</font></td>
										</tr>
										<?php
										$SQL = pg_query("SELECT * FROM $tabela ORDER BY Ds_Link");
								$Cor = "";
								while ($rs = pg_fetch_array($SQL))
{
	if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
	echo '<tr>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["ds_link"].'</font></td>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["link"].'</font></td>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["end_predio"].'</font></td>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["co_filial"].'</font></td>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["no_servidor"].'</font></td>
<td width="5%" bgcolor="'.$Cor.'" valign="top" align="left" ><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs["ip_servidor"].'</font></td>
<td nowrap="true" bgcolor="'.$Cor.'" valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="cad_curupira.php?id='.$rs["id"].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:ExcluirUnidade(\'exclui_curupira.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir"></a></font></td>
</tr>
';
}
											$total=pg_num_rows($SQL);
											echo '<tr class="titTable">
<td  colspan="7" align="center">'.$total.' Unidades(s)</td></tr>
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
			<tr><?php include('inc/foot.php');;?></tr>
		</table>
	</body>
</html>

