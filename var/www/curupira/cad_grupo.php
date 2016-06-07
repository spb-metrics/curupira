<?php
session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/head.php');
?>
		<html>
		<head><script language="JavaScript" src="script/general_functions.js"></script><script language="JavaScript">
		function Valida()
{
	if(document.frm_grupo.grupo.value == '')
{
	alert('O campo "Nome Grupo" é de preenchimento obrigatório');
				document.frm_grupo.grupo.focus();
				return false;
}

			document.frm_grupo.action = 'inclui_grupo.php';
			document.frm_grupo.submit();
}

		function ExcluirGrupo(url)
{
	if (confirm("ATENÇÃO:\nAo excluir um grupo, será excluído também tudo relacionado a ele. Tem certeza que deseja excluir esse grupo?"))
{
	document.frm_grupo.action = url;
				document.frm_grupo.submit();
}
}
		</script>
		<TITLE>Cadastro de Grupos</TITLE>
		</head>

		<?php
		if (isset($_GET["id"]) && $_GET["id"] != "")
{
	$CodGrupo = $_GET["id"];
	$SQL = pg_query("SELECT * FROM groups WHERE id = ".$CodGrupo) or die ("Usuário não encontrado.");
	$rsUpdate = pg_fetch_row($SQL);
	$NomeGrupo = $rsUpdate[1];
	$Descricao = $rsUpdate[2];
}
else
{
	$CodGrupo = 0;
	$NomeGrupo = "";
	$Descricao = "";
}
?>

		<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<br>
		<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td valign="top" height="100%" width="100%">
		<?php if($CodGrupo !=0){?>
	<form method="post" name="frm_grupo">
			<?php if ($NomeGrupo != "") echo '<input type="hidden" name="codgrupo" value="'.$CodGrupo.'" />
'; ?>
			<fieldset>
					<legend accesskey="C"><font face="verdana,arial" size="2"><b>&nbsp;Cadastro de Grupos&nbsp;</b></font></legend>
					<table border="0" width="100%">
					<tr>
					<td nowrap="true"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<font size="1" color="red">*</font>Nome Grupo:&nbsp;</font></td>
					<td>
					<?php
					echo '<input type="text" name="grupo" style="font-size:8pt" size="40" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Grupo\');" value="'.$NomeGrupo.'" />
';
			?>&nbsp;</td>
					</tr>
					<tr>
					<td valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Descri&ccedil;&atilde;o:</font></td>
					<td width="100%">
					<?php
					echo '<textarea name="descricao" style="font-size:8pt" wrap="soft" rows="6" cols="50" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Descri&ccedil;&atilde;o\');" onkeydown="javascript:CountMaxChar(this.form.name,this.name,\'Descri&ccedil;&atilde;o\',\'maxcaracteres\',1000);">'.$Descricao.'</textarea><br />
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input class="semborder" type="text" name="maxcaracteres" size="3" value="1000" readonly="true" disabled="true"/>&nbsp;caracteres dispon&iacute;veis</font>
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
					<?php } ?>
					<table border="0" width="100%">
							<tr>
							<td valign="top" height="100%" colspan="2">
							<table border="0" width="100%">
							<tr>
							<td class="titTable" colspan="4">Listagem de Grupos</td>
							</tr>
							<tr>
							<th align="left">&nbsp;Grupo</th>
							<th align="left">&nbsp;Descri&ccedil;&atilde;o</th>
							<th class="CENTER">A&ccedil;&atilde;o</th>
							</tr>
							<?php
							$SQL = pg_query("SELECT * FROM groups where id < 100 ORDER BY groupname");
					$Cor = "";
					while ($rs = pg_fetch_row($SQL))
					{
						if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

						echo '<tr>
<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[1].'</font></td>
<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[2].'</font></td>
<td nowrap="true" bgcolor="'.$Cor.'" valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="cad_grupo.php?id='.$rs[0].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<!--a href="javascript:ExcluirGrupo(\'exclui_grupo.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir" --></a></font></td>
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
							</td>
							</tr>
							<tr><?php include('inc/foot.php');;?></tr>
							</table> <!-- Fecha tabela do header.php -->
	</body>
</html>