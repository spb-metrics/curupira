<?php
session_start();
include('inc/valida_session.php');
include('inc/conn.php');
include('inc/head.php');

if($_SESSION["perfil"]==6){
	header("Location: relimpressoras.php");
}else
	if($_SESSION["perfil"]!=2){
		$limitaUnidade = "AND printers.codunidade = ".$_SESSION["uni"];
	}
	?>
			<html>
			<head>
			<script language="JavaScript" src="script/general_functions.js"></script>
			<script language="JavaScript">
			function Valida()
	{
		if(document.frm_impressora.impressora.value == '')
	{
		alert('O campo "Nome Impressora" é de preenchimento obrigatório');
	document.frm_impressora.impressora.focus();
	return false;
}

	if(document.frm_impressora.nserie.value == '')
	{
		alert('O campo "No. Série" é de preenchimento obrigatório');
	document.frm_impressora.nserie.focus();
	return false;
}

	if(document.frm_impressora.localizacaoimp.value == '')
	{
		alert('O campo "Localização" é de preenchimento obrigatório');
	document.frm_impressora.localizacaoimp.focus();
	return false;
}

	if(document.frm_impressora.nppm.value == '')
	{
		alert('O campo "No. PPM" é de preenchimento obrigatório');
	document.frm_impressora.nppm.focus();
	return false;
}

	if(document.frm_impressora.nomeservidor.value == '')
	{
		alert('O campo "Nome Servidor" é de preenchimento obrigatório');
	document.frm_impressora.nomeservidor.focus();
	return false;
}

	if(document.frm_impressora.grupo.value == 0)
	{
		alert('O campo "Grupo" é de preenchimento obrigatório');
	document.frm_impressora.grupo.focus();
	return false;
}

	if(document.frm_impressora.unidade.value == 'U')
	{
		alert('O campo "Unidade" é de preenchimento obrigatório');
	document.frm_impressora.unidade.focus();
	return false;
}

	if(document.frm_impressora.custo.value == '')
	{
		alert('O campo "Custo/Pág." é de preenchimento obrigatório');
	document.frm_impressora.custo.focus();
	return false;
}

	if(document.frm_impressora.recurso.value == '')
	{
		alert('O campo "Recurso Impressora" é de preenchimento obrigatório');
	document.frm_impressora.recurso.focus();
	return false;
}

	if(document.frm_impressora.cor.value == '')
	{
		alert('O campo "Cor" é de preenchimento obrigatório');
	document.frm_impressora.cor.focus();
	return false;
}

	document.frm_impressora.action = 'inclui_impressora.php';
	document.frm_impressora.submit();
}

	function ExcluirImpressora(url)
	{
		if (confirm("ATENÇÃO:\nAo excluir uma impressora, será excluído também tudo relacionado a ela. Tem certeza que deseja excluir esse impressora?"))
	{
		document.frm_impressora.action = url;
	document.frm_impressora.submit();
}
}
	</script>
			<TITLE>Cadastro de Impressoras</TITLE>
			</head>

			<?php
			if (isset($_GET["id"]) && $_GET["id"] != "")
	{
		$CodImpressora = $_GET["id"];
		$CodUnidade = $_GET["un"];
		$CodGrupo = $_GET["idg"];
		$SQL = pg_query("SELECT id, printername, priceperpage, codunidade, description, recurso, cor, nserie, ppm, nomeservidor,localizacao FROM printers WHERE id = ".$CodImpressora." AND codunidade = ".$CodUnidade) or die ("Impressora não encontrada.");
		$rsUpdate = pg_fetch_row($SQL);
		$CodImpressora = $rsUpdate[0];
		$NomeImpressora = $rsUpdate[1];
		$Custo = $rsUpdate[2];
		$CodUnidade = $rsUpdate[3];
		$Descricao = $rsUpdate[4];
		$Recurso = $rsUpdate[5];
		$Cor = $rsUpdate[6];
		$nSerie = $rsUpdate[7];
		$nPPM = $rsUpdate[8];
		$NomeServidor = $rsUpdate[9];
		$LocalizacaoImp = $rsUpdate[10];
	}
	else
	{
		$CodImpressora = "null";
		$CodUnidade = "null";
		$CodGrupo = 0;
		$NomeImpressora = "";
		$Custo = "";
		$CodUnidade = 0;
		$Descricao = "";
		$Recurso = 0;
		$Cor = 0;
		$nSerie = "";
		$nPPM = "";
		$NomeServidor = "";
		$LocalizacaoImp = "";
	}
	?>

			<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
			<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
			<tr>
			<td height="100%" valign="top" width="100%">
			<form method="post" name="frm_impressora">
			<?php if ($CodImpressora != "") echo '<input type="hidden" name="codimpressora" value="'.$CodImpressora.'">'; ?>
			<br>
					<fieldset>
					<legend accesskey="C">&nbsp;<font size="2"><b>Cadastro de Impressoras</b></font>&nbsp;</legend>
					<table border="0" width="100%">
					<tr>
					<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Nome Impressora:&nbsp;</font></td>
					<td>
					<?php echo '<input type="text" name="impressora" style="font-size:8pt" size="13" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Impressora\');" value="'.$NomeImpressora.'">';?>&nbsp;
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>No. S&eacute;rie:&nbsp;</font>
					<?php echo '<input type="text" name="nserie" style="font-size:8pt" size="18" maxlength="20" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'No. S&eacute;rie\');" value="'.$nSerie.'">';?>&nbsp;
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>No. PPM:&nbsp;</font>
					<?php echo '<input type="text" name="nppm" style="font-size:8pt" size="4" maxlength="4" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'No. PPM\');FormataFloat(this.form.name,this.name,0,\'.\',4,event);" onchange="javascript:FormataFloat(this.form.name,this.name,0,\'.\',4,event);" value="'.$nPPM.'">&nbsp;<font face="Verdana,Arial,Helvetica,sans-serif" size="1">PPM (P&aacute;g. p/min.)</font>";'?>&nbsp;
			</td>
					</tr>
					<tr>
					<td>
					<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Nome Servidor:&nbsp;</font></td>
					<td>
					<?php
					echo '<input type="text" name="nomeservidor" style="font-size:8pt" size="15" maxlength="70" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Nome Servidor\');" value="'.$NomeServidor.'">';
			?>&nbsp;</td>
					</tr>
					<tr>
					<td>
					<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Localiza&ccedil;&aacute;o:&nbsp;</font></td>
					<td>
					<?php
					echo '<input type="text" name="localizacaoimp" style="font-size:8pt" size="40" maxlength="100" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Localizacao Impressora\');" value="'.$LocalizacaoImp.'">';
			?>&nbsp;</td>
					</tr>
					<tr>
					<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Grupo:&nbsp;</font></td>
					<td width="100%">
					<?php
					if ($CodGrupo == "null")
					$strSQL = "SELECT id, groupname FROM groups ORDER BY groupname";
			else
				$strSQL = "SELECT id, groupname, CASE WHEN (id = '".$CodGrupo."') THEN 1 ELSE 0 END as flg FROM groups ORDER BY groupname";
			$SQL = pg_query($strSQL);
			?>
					<select name="grupo" style="font-size:8pt">
					<option value="0">:: Grupo ::</option>
					<?php
					while ($rs = pg_fetch_row($SQL))
			{
				if ($rs[2] == 1)
					$Sel = ' selected="selected"';
				else
					$Sel = '';
				echo '<option'.$Sel.' value="'.$rs[0].'|'.$rs[1].'">'.$rs[1].'</option>';
			}
			?>
					</select>
					</td>
					</tr>
					<tr>
					<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Unidade:&nbsp;</font></td>
					<td width="100%">
					<?php
					if ($CodUnidade == "null")
					$strSQL = "SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade";
			else
				$strSQL = "SELECT codunidade, nomeunidade, CASE WHEN (codunidade = '".$CodUnidade."') THEN 1 ELSE 0 END as flg FROM unidades ORDER BY nomeunidade";
			$SQL = pg_query($strSQL);

			?>
					<select name="unidade" style="font-size:8pt">
					<option value="U">:: Unidade ::</option>
					<?php
					while ($rs = pg_fetch_row($SQL))
			{
				if ($rs[2] == 1)
					$Sel = ' selected="true"';
				else
					$Sel = '';
				echo '<option'.$Sel.' value="'.$rs[0].'">'.$rs[1].'</option>';
			}
			?>
					</select>
					</td>
					</tr>
					<tr>
					<td nowrap="true"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Custo/P&aacute;g. (R$):&nbsp;</font></td>
					<td>
					<?php echo '<input type="text" name="custo" style="font-size:8pt" size="4" maxlength="4" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Custo\');FormataFloat(this.form.name,this.name,2,\'.\',4,event);" onchange="javascript:FormataFloat(this.form.name,this.name,2,\'.\',4,event);" value="'.$Custo.'">';?>&nbsp;
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Impressora Recurso:&nbsp;</font>
					<?php
					$Laser = "";
			$Tinta = "";
			if ($Recurso == 1)
				$Tinta = "selected";
			if ($Recurso == 2)
				$Laser = "selected";

			echo '											<select name="recurso">
					<option value="0">:: Recurso ::</option>
					<option '.$Tinta.' value="1">Jato de Tinta</option>
					<option '.$Laser.' value="2">Laser</option>
					</select>';
			?>&nbsp;
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;<font color="red">*</font>Cor:&nbsp;</font>
					<?php
					$Colorida = "";
			$Mono = "";
			if ($Cor == 1)
				$Colorida = "selected";
			if ($Cor == 2)
				$Mono = "selected";

			echo '											<select name="cor">
					<option value="0">:: Cor ::</option>
					<option '.$Colorida.' value="1">Colorida</option>
					<option '.$Mono.' value="2">Monocrom&aacute;tica</option>
					</select>';?>&nbsp;
			</td>
					</tr>
					<tr>
					<td valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Descri&ccedil;&atilde;o:</font></td>
					<td width="100%">
					<?php
					echo '												<textarea name="descricao" style="font-size:8pt" wrap="soft" rows="6" cols="50" onBlur="javascript:ValidaCampo(this.form.name,this.name,\'Descri&ccedil;&atilde;o\');" onchange="javascript:CountMaxChar(this.form.name,this.name,\'Descri&ccedil;&atilde;o\',\'maxcaracteres\',1000);" onchange="javascript:CountMaxChar(this.form.name,this.name,\'Descri&ccedil;&atilde;o\',\'maxcaracteres\',1000);">'.$Descricao.'</textarea><br>
					<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><input class="semborder" type="text" name="maxcaracteres" size="3" value="1000" readonly="true" disabled="true"/>&nbsp;caracteres dispon&iacute;veis</font>';
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
					<br><br>
					<table border="0" width="100%">
					<tr><td class="titTable"><font size="1" face="Verdana,Arial,Helvetica,sans-serif">Crit&eacute;rios de Busca:</font></td></tr>
					</table>
					<script language="JavaScript">
					function Busca()
			{
				document.frm_busca.action = 'cad_impressora.asp';
			document.frm_busca.submit();
}
			</script>
					<form name="frm_busca" method="post" onsubmit="javascript:Busca();">
					<table border="0" width="100%">
					<tr>
									<td valign="top" height="100%" colspan="2">&nbsp;Nome Impressora:
					<input type="text" name="busca">
					<input type="submit" class="button" name="buscar" value="OK"></td>
					</tr>
					</table>
					<br>
					<table border="0" width="100%">
					<tr>
					<td valign="top" height="100%" colspan="2">
					<table border="0" width="100%">
					<tr>
					<td class="titTable" colspan="5">Lista de Impressoras</td>
					</tr>
					<tr>
					<th>&nbsp;Impressora</th>
					<th>&nbsp;Unidade</th>
					<th>&nbsp;Grupo</th>
					<th>&nbsp;Custo/P&aacute;g.</th>
					<th class="CENTER">A&ccedil;&atilde;o</th>
					</tr>
					<?php
					if (isset($_POST["busca"]) && $_POST["busca"] != "")
					$NomeImpressora = "AND printers.printername LIKE '%".$_POST["busca"]."%'";
			else
				$NomeImpressora = "";

			if(!isset($limitaUnidade))
        $limitaUnidade = "";
      $SQL = pg_query("SELECT printers.id as printerid, printers.printername, unidades.codunidade, unidades.nomeunidade, printers.priceperpage, groups.id as groupid, groups.groupname FROM printers LEFT JOIN unidades ON printers.codunidade = unidades.codunidade , groups, printergroupsmembers WHERE printers.id = printergroupsmembers.printerid AND printergroupsmembers.groupid = groups.id $NomeImpressora $limitaUnidade ORDER BY printers.printername");

			$Cor = "";
			$total=pg_num_rows($SQL);
			while ($rs = pg_fetch_row($SQL))
			{
				if ($Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";
				echo '												<tr>
						<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[1].'</font></td>
						<td width="50%" bgcolor="'.$Cor.'" valign="top" align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$rs[3].'</font></td>
						<td nowrap width="50%" bgcolor="'.$Cor.'" valign="top"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.$rs[6].'&nbsp;</font></td>
						<td bgcolor="'.$Cor.'" valign="top" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;R$&nbsp;'.$rs[4].'&nbsp;</font></td>
						<td nowrap="true" bgcolor='.$Cor.' valign="top" align="left" width="1%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="cad_impressora.php?id='.$rs[0].'&un='.$rs[2].'&idg='.$rs[5].'"><img src="imagens/editar.gif" border="0" alt="Alterar"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:ExcluirImpressora(\'exclui_impressora.php?id='.$rs[0].'\');"><img src="imagens/excluir.gif" border="0" alt="Excluir"></a></font></td>
						</tr>';
			}
			echo '												<tr class="titTable"><td  colspan="5" align="center">'.$total.' Impressora(s)</td></tr>';
			?>
					</table>
					</td>
					</tr>
					</table>
					</form>
					</td>
					</tr>
					</legend>
					</body>
					</td>
					</tr>
					<tr><?php include('inc/foot.php');?></tr>
	</table>
</html>
