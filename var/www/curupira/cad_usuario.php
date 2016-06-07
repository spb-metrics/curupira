<?php
/*
* Curupira: Sistema PHP gerenciador de impressoes para ambiente corporativo.
* Copyright (C) 2006 - Caixa Economica Federal - GISUT/BH
* Authors:
*  Bernardo Cunha Vieira
*  Bruno Marcal Lacerda Fonseca
*  Daniel Andrade Costa Silva
*  Edgard Antonio de Aguiar
*  Evando Marcio de Almeida
*  Ricardo Carlini Sperandio
*  Zeniel Chaves
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*/

session_start();
include('inc/conn.php');

$grupo = (isset($_POST["codgrupo"]))?(int)$_POST["codgrupo"]:0;

if($grupo>0){
	$limitaUnidade = (isset($limitaUnidade))?$limitaUnidade." AND groupsmembers.groupid = ".$grupo." ":" AND groupsmembers.groupid = ".$grupo." ";
}

if($_SESSION["perfil"] !=2 && $_SESSION["perfil"] !=1&& $_SESSION["perfil"] !=4){
	header("Location: relimpressoras.php");
}else
	if($_SESSION["perfil"] ==4 ){
	$_POST["codunidade"] = $_SESSION['uni'];
	}

	if(isset($_POST["codunidade"]) && $_POST["codunidade"]>=0){
		if(isset($limitaUnidade))
			$limitaUnidade .= "AND users.codunidade = ".$_POST["codunidade"];
		else
			$limitaUnidade = "AND users.codunidade = ".$_POST["codunidade"];
	}
	include('inc/head.php');

	?>
<html>
	<head>
		<script language="JavaScript" src="script/general_functions.js"></script>
		<SCRIPT language=javascript src="script/funcoes.js"></SCRIPT>
		<SCRIPT language=javascript>

	function getObj(name)
	{
		if (document.getElementById)
	{
		this.obj = document.getElementById(name);
	}
	else if (document.all)
	{
		this.obj = document.all[name];
	//this.style = document.all[name].style;
}
	else if (document.layers)
	{
		this.obj = document.layers[name];
   	//this.style = document.layers[name];
}
	return this.obj;
}
	function Valida()
	{
		if(document.frm_usuario.login.value == '')
	{
		alert('O campo "Usuário" é de preenchimento obrigatório');
	document.frm_usuario.login.focus();
	return false;
}

	if(document.frm_usuario.grupo.value == '')
	{
		alert("O campo \"Grupo\" é de preenchimento obrigatório");
	document.frm_usuario.grupo.focus();
	return false;
}

	document.frm_usuario.action = 'inclui_usuario.php';
	document.frm_usuario.submit();
}
	function unhide(elemento){
		novoelemento = getObj("i"+elemento.name);
	novoelemento2 = getObj("e"+elemento.name);
	if(elemento.checked){
		novoelemento.style.display="inline";
	novoelemento2.style.display="inline";
}
	else{
		novoelemento.style.display="none";
	novoelemento2.style.display="none";
}
}
	function ExcluirUsuario(url)
	{
		if (confirm("ATENÇÃO:\nAo excluir um usuário, será excluído também tudo relacionado a ele. Tem certeza que deseja excluir esse usuário?"))
	{
		document.frm_usuario.action = url;
	document.frm_usuario.submit();
}
}
	</SCRIPT>

			<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
			</HEAD>

			<?php
			if (isset($_GET["id"]) && $_GET["id"] != "")
	{
		$CodUsuario = $_GET["id"];
		$CodGrupo = $_GET["idg"];
		$SQL = pg_query("SELECT users.id, users.username, users.email, users.description, users.codunidade, users.nome FROM users, unidades WHERE users.codunidade = unidades.codunidade AND users.id = ".$CodUsuario) or die ("Usu&aacute;rio n&atilde;o encontrado.");
		$rsUpdate = pg_fetch_row($SQL);
		$Login = $rsUpdate[1];
		$Email = $rsUpdate[2];
		$Descricao = $rsUpdate[3];
		$CodUnidade = $rsUpdate[4];
		$Nome = $rsUpdate[5];
	}
	else
	{
		$CodUsuario = "null";
		$CodGrupo = 0;
		$Login = "";
		$Email = "";
		$Descricao = "";
		$CodUnidade = "null";
		$Nome = "";
	}
	?>

			<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0" >
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">

			<tr>
			<td height="100%" valign="top" width="100%">
			<?php //echo $CodUnidade !="null"; ?>
			<?php if ($_SESSION["perfil"] ==2 || ($_SESSION["uni"] == $CodUnidade  || $CodUnidade == "null" )) {?>
		
		<form method="post" name="frm_usuario">
				<?php if ($Login != "") echo '<input type="hidden" name="codusuario" value="'.$CodUsuario.'"/>'; ?>
				<br />
						<fieldset>
						<legend accesskey="C">&nbsp;<font size="2"><b>Cadastro de Usu&aacute;rios</b></font>&nbsp;</legend>
						<table border="0" width="100%">
						<tr>
						<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Usuario:&nbsp;</font></td>
						<td>
						<?php
						echo '<input type="text" name="login" style="font-size:8pt" size="10" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Login\');" value="'.$Login.'">
';
				?>&nbsp;
				<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red"></font>Nome:&nbsp;</font>
						<?php echo '<input type="text" name="nome" style="font-size:8pt" size="40" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome\');" value="'.$Nome.'">
';?>&nbsp;</td>
						</tr>
								<tr>
								<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Grupo:&nbsp;</font></td>
								<td width="100%">
								<?php
								$SQL = pg_query("SELECT id, groupname, CASE WHEN (id = ".$CodGrupo.") THEN 1 ELSE 0 END as flg FROM groups WHERE id < 100 ORDER BY groupname");
						?>
								<select name="grupo" style="font-size:8pt">
								<option value="0">:: Grupo ::</option>
								<?php
								if($CodGrupo==2 && $_SESSION["perfil"]!=2){
							echo '<option selected="true" value="2|Nivel4">Nivel4</option>
';
								}else{
									while ($rs = pg_fetch_row($SQL))
									{
										if ($rs[2] == 1)
											$Sel = 'selected="true"';
										else
											$Sel = "";
										if($_SESSION["perfil"]!=2 && !(strcasecmp($rs[1],"Nivel4"))){
											;
										}else
											echo '<option '.$Sel.' value="'.$rs[0].'|'.$rs[1].'">'.$rs[1].'</option>
';
									}
								}
								?>
										</select>

										<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red"></font>Unidade:&nbsp;</font>
										<?php
										$SQL = pg_query("SELECT codunidade, nomeunidade, CASE WHEN (codunidade = ".$CodUnidade.") THEN 1 ELSE 0 END as flg FROM unidades ORDER BY nomeunidade");
								if($_SESSION["perfil"]==2){
									?>
											<select name="unidade" style="font-size:8pt">
											<option value="0">:: Unidade ::</option>
											<?php
											while ($rs = pg_fetch_row($SQL))
									{
										if ($rs[2] == 1)
											$Sel = 'selected="true"';
										else
											$Sel = "";
										echo '<option '.$Sel.' value="'.$rs[0].'">'.$rs[1].'</option>
';
									}
									?>
											</select>
											<?php
								}
								else
								{
									$rows = pg_fetch_row(pg_query("SELECT nomeunidade FROM unidades WHERE unidades.codunidade = ".$_SESSION["uni"]." ORDER BY nomeunidade"));
									echo '<input type="hidden" name="unidade" value="'.$_SESSION["uni"].'">
<input type="text" readonly="true" value="'.$rows[0].'">
';
								}
								?>
										</td>
										</tr>
										<tr>
										<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;E-mail:&nbsp;</font></td>
										<td>
										<?php
										echo '<input type="text" name="email" style="font-size:8pt" size="40" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'E-Mail\');" value="'.$Email.'">
';
								?>&nbsp;</td>
										</tr>
										<tr>
										<td valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Descri&ccedil;&atilde;o:</font></td>
										<td width="100%">
										<?php
										echo '<textarea name="descricao" style="font-size:8pt" wrap="soft" rows="4" cols="54" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Descri&ccedil;&atilde;o\');" onkeydown="javascript:CountMaxChar(this.form.name,this.name,\'Descri&ccedil;&atilde;o\',\'maxcaracteres\',1000);">'.$Descricao.'</textarea><br />
<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input class="semborder" type="text" name="maxcaracteres" size="3" value="1000" readonly="true" disabled="true"/>&nbsp;caracteres dispon&iacute;veis</font>
';
								?>&nbsp;</td>
										</tr>
										<tr><td colspan="2">&nbsp;</td></tr>
										<tr>
										<td>&nbsp;</td>
										<td><input type="button" class="button" name="gravar" value="Gravar" onclick="javascript:return Valida();">
										<font size="1" color="red">(* Campos de preenchimento obrigat&oacute;rio)</font>
										</td>
										</tr>
										</table>

										</fieldset>
										</form>
										<?php } ?>

										<?PHP
										if($_SESSION["perfil"]==2 || $_SESSION["perfil"]==1) {
										?>
													<fieldset>
													<legend accesskey="P">&nbsp;<font size="1"><b>Permiss&otilde;es <?php echo $Nome;?></b></font>&nbsp;</legend>
													<form name="frm_permissoes" method="post" action="inclui_imp.php">
															<input type="hidden" name="codusuario" value="<?php echo $CodUsuario; ?>"/>
															<table border="0" width="100%">

																	<?php

																	if($_SESSION['perfil'] == 2){
																$SQLtexto = "SELECT printers.id, printername, temporarydenied as permitido, softlimit as soft FROM printers, userpquota WHERE printers.id = userpquota.printerid AND userpquota.userid = $CodUsuario ORDER BY printername";
																	}else{
																		$SQLtexto = "SELECT printers.id, printername, temporarydenied as permitido, softlimit as soft FROM printers, userpquota WHERE printers.id = userpquota.printerid AND userpquota.userid = $CodUsuario AND printers.codunidade = $_SESSION[uni] ORDER BY printername";
																	}
																	$LIMITAR= pg_query("SELECT printers.id from printers where printers.id not in(SELECT printers.id FROM printers, userpquota WHERE printers.id = userpquota.printerid AND userpquota.userid = $CodUsuario)");

																	while($rs=pg_fetch_array($LIMITAR)){
																		$a1="INSERT INTO userpquota(printerid, userid) VALUES ($rs[id],$CodUsuario)";
																		pg_query($a1);
																	}

																	$SQL = pg_query ($SQLtexto);
																	?>
										&nbsp;Impressoras:
																			<?php
																			while ($rs = pg_fetch_array($SQL))
																	{ echo '<tr>
<td valign="top" height="100%" colspan="2">
<input type="checkbox" name="'.$rs['id'].'"'.((!strcmp($rs['permitido'],"f"))? " checked ":" ").' onclick="unhide(this);" />'.$rs['printername'];
																	if(!strcmp($rs['permitido'],"f")){
																		echo '
<span id= "e'.$rs["id"].'" style="display:inline;">Quota:</span><input type="text" style="display:inline" id= "i'.$rs["id"].'" name="i'.$rs["id"].'" value="'.$rs['soft'].'" size="2" maxlength="3" />
';
																	}else{
																		echo '
<span id="e'.$rs["id"].'" style="display:none;">Quota:</span><input type="text" style="display:none;"  id= "i'.$rs["id"].'" name="i'.$rs["id"].'" value="'.$rs['soft'].'" size="2" maxlength="3">
';
																	}
																	}
																	echo "</td>
</tr>";?>
																			<tr><td colspan="2">
																					<input type="submit" class="button" name="modificar" value="Modificar"></td>
																					</tr>
																					</table>
																					</form>
																					<form method="post" name="frm_reseta" action="reseta_quota.php">
																					<input type="hidden" name="usuario" value="<?echo $Login;?>">
																					<input class="button" type="submit" class="botao" name="submit" value="Recoloca Quota">
																							</form>
																							</fieldset>
																							<br><br>

																							<script language="JavaScript">
																							function Busca()
																					{
																						document.frm_busca.action = 'cad_usuario.php';
																					document.frm_busca.submit();
																					}
																					</script>
																							<fieldset>
																							<legend accesskey="B">&nbsp;<font size="1"><b>Busca</b></font>&nbsp;</legend>
																							<form name="frm_busca" method="post" onsubmit="javascript:Busca();">
																							<table border="0" width="100%">
																							<tr>
								<td valign="top" height="100%" colspan="2">&nbsp;Nome Usuario:
																							<input type="text" name="busca">
																							<?PHP //if($_SESSION["perfil"]==2)
																						$SQL = pg_query("SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade");
																					?>
										&nbsp;Unidade:
																							<select name="codunidade" style="font-size:8pt">
																							<option value="-1">:: Unidade ::</option>
																							<?php
																							while ($rs = pg_fetch_row($SQL))
																					{
																						if (isset($_POST["codunidade"]) && $rs[0] == $_POST["codunidade"])
																							$Sel = 'selected="true"';
																						else
																							$Sel = "";

																						echo '<option '.$Sel.' value="'.$rs[0].'">'.$rs[1].'</option>
';
																					}
																					echo "</select>
";
								echo '&nbsp;Grupo:
										<select name="codgrupo" style="font-size:8pt">
										<option value="-1">:: Grupo ::</option>';
								$SQL = pg_query("SELECT id, groupname, CASE WHEN (id = ".$grupo.") THEN 1 ELSE 0 END as flg FROM groups where id < 100 ORDER BY groupname");
								while ($rs = pg_fetch_row($SQL))
								{
									if ($rs[2] == 1)
										$Sel = 'selected="true"';
									else
										$Sel = "";
									echo "<option ".$Sel.' value="'.$rs[0].'">'.$rs[1].'</option>
';
								}
								echo "</select>";
?>
										<input type="submit" class="button" name="buscar" value="OK"></td>
										</tr>
										</table>
										</form>
										</fieldset>
										<br>
										<span class="LinkPaginacao"><center><a href="atualiza_usuarios.php">Atualiza usu&aacute;rios desconhecidos</a></center></span>
										<br />
										<table border="0" width="100%">
										<tr>
										<td valign="top" height="100%" colspan="2">
										<table border="0" width="100%">
										<tr>
										<td class="titTable" colspan="5">Listagem de Usu&aacute;rios</td>
										</tr>
										<tr>
										<th align="left">&nbsp;Matr&iacute;cula - Nome</th>
										<th align="left">&nbsp;Unidade</th>
										<th align="left">&nbsp;Grupo</th>
										<th class="CENTER">A&ccedil;&atilde;o</th>
										</tr>
										<?php
										if (isset($_POST["busca"]) && $_POST["busca"] != "")
										$NomeUsuario = "AND users.nome LIKE '%".$_POST["busca"]."%'";
								else
									$NomeUsuario = "";

								$SQL = "SELECT users.id, users.username, users.email, groups.id, groups.groupname, unidades.nomeunidade, users.nome, unidades.codunidade FROM users, groups, groupsmembers, unidades WHERE users.id = groupsmembers.userid AND groupsmembers.groupid = groups.id AND users.codunidade = unidades.codunidade $NomeUsuario ";
								if(isset($limitaUnidade))
									$SQL .= $limitaUnidade . " ";
								$SQL .= "ORDER BY users.username";

								$ExecSQL = pg_query($SQL);

								$Cor = "";
								$totalus = pg_num_rows($ExecSQL);
								while ($rs = pg_fetch_array($ExecSQL))
								{
									if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

									echo '<tr>
<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[1].' - '.$rs[6].'</font></td>
<td nowrap bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[5].'</font></td>
<td nowrap bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[4].'</font></td>
<td nowrap="true" bgcolor="'.$Cor.'" valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="cad_usuario.php?id='.$rs[0]."&idg=".$rs[3].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;
';
									if ($_SESSION["perfil"] ==2 || ($_SESSION["uni"] == $rs['codunidade'] ))
										echo '<a href="javascript:ExcluirUsuario(\'exclui_usuario.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir"></a></font>
</td>
</tr>';
								}
								echo '<tr class="titTable">
';
								echo '<td  colspan="4" align="center">'.$totalus.' Usu&aacute;rio(s)</td></tr>
';
?>
</table></th></tr></table></form></th></tr></table></body></td></tr><tr>
<?PHP
}
?>
<?PHP 
include('inc/foot.php');
?></tr></table></html>
