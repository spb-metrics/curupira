<?php
session_start();
include('inc/valida_session.php');
include('inc/conn.php');
//include('inc/pagina.php');

?>
		<link href="estilo/estiloCaixa.css" rel="stylesheet">
		<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td>
					<table border="0" width="100%">
						<tr>
							<td class="Titulo">&nbsp;
<?php
	if (!isset($_POST["relatorio"]) || $_POST["relatorio"] == "") $PostRelatorio = $_GET["rel"]; else $PostRelatorio = $_POST["relatorio"];
	if ($PostRelatorio == 1)
		echo "CURUPIRA - Relat&oacute;rio de Impress&otilde;es p/Usu&aacute;rio";
	elseif ($PostRelatorio == 2)
		echo "CURUPIRA - Relat&oacute;rio de P&aacute;ginas p/Usu&aacute;rio";
	elseif ($PostRelatorio == 3)
		echo "CURUPIRA - Relat&oacute;rio de Custo p/Usu&aacute;rio";
	elseif ($PostRelatorio == 4)
		echo "CURUPIRA - Relat&oacute;rio de Impress&otilde;es p/Ilha";
	elseif ($PostRelatorio == 5)
		echo "CURUPIRA - Relat&oacute;rio de P&aacute;gina/Custo p/Ilha";
	elseif ($PostRelatorio == 6)
		echo "CURUPIRA - Relat&oacute;rio de Impress&otilde;es/P&aacute;gina/Custo p/Unidade";
	elseif ($PostRelatorio == 7)
		echo "CURUPIRA - Relat&oacute;rio de P&aacute;ginas/Custo p/Unidade";
	elseif ($PostRelatorio == 9)
		echo "CURUPIRA - Relat&oacute;rio de Impress&otilde;es/P&aacute;gina/Custo p/Unidade";
	elseif ($PostRelatorio == 10)
		echo "CURUPIRA - Relat&oacute;rio de P&aacute;gina/Custo p/Impressora";
?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0">
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
<?php
	//Dados do formulario
	if (isset($_POST["limit"]) && $_POST["limit"] != "") $LIMIT = $_POST["limit"]; else $LIMIT = $_GET["limit"];

	if ($PostRelatorio == 1)
	{
		//Impressoes p/Usuario
		$RegistroSQL = pg_query("SELECT users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid GROUP BY users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, jobhistory.jobdate, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0 ;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid GROUP BY users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, jobhistory.jobdate, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="8">Impress&otilde;es p/Usu&aacute;rio&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Usu&aacute;rio</th>
							<th>&nbsp;Nome</th>
							<th>&nbsp;Impressora</th>
							<th>&nbsp;Documento</th>
							<th>&nbsp;Esta&ccedil;&atilde;o</th>
							<th>&nbsp;Data</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL))
		{
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["login"] != "") $Login = $rs["login"]; else $Login = "&nbsp;";
			if ($rs["nome"] != "") $Nome = $rs["nome"]; else $Nome = "&nbsp;";
			if ($rs["printername"] != "") $Impressora = $rs["printername"]; else $Impressora = "&nbsp;";
			if ($rs["title"] != "") $Title = $rs["title"]; else $Title = "&nbsp;";
			if ($rs["hostname"] != "") $Estacao = $rs["hostname"]; else $Estacao = "&nbsp;";
			if ($rs["data"] != "") $Data = $rs["data"]; else $Data = "&nbsp;";
			if ($rs["paginas"] != "") $Paginas = $rs["paginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";
			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$Login.'</td>
							<td bgcolor="'.$Cor.'">'.$Nome.'</td>
							<td bgcolor="'.$Cor.'">'.$Impressora.'</td>
							<td bgcolor="'.$Cor.'">'.$Title.'</td>
							<td bgcolor="'.$Cor.'">'.$Estacao.'</td>
							<td bgcolor="'.$Cor.'">'.$Data.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>
';
		}
?>
							<tr>
								<td align="center" colspan="8">&nbsp;</td>
							</tr>
							<tr>
								<td align="center" colspan="8"><?php
		$main_file = "gera_relatorio.php";
		if (isset($pagina) && $pagina != 0)
		{
			$num = $pagina - 1;
			echo '&nbsp;&nbsp;<a href="'.$main_file.'?pg='.$num.'&rel=1&limit='.$LIMIT.'"><< anterior</a>&nbsp;&nbsp;|';
		}

		if(!isset($pagina)) $pagina = 0;
		if ($pagina < $TotalPaginas)
		{
			$num = $pagina + 1;
			echo '&nbsp;&nbsp;<a href="'.$main_file.'?pg='.$num.'&rel=1&limit='.$LIMIT.'">pr&oacute;ximo >></a>&nbsp;&nbsp;';
		}
?></td>
							</tr>
<?php
	} elseif ($PostRelatorio == 2) {
		//Paginas p/Usuario
		$SQL = "SELECT users.id as userid, users.nome, users.username as login, sum(jobhistory.jobsize) AS paginas, sum(jobhistory.jobprice) AS custo FROM users, jobhistory, printers WHERE users.id = jobhistory.userid AND jobhistory.printerid = printers.id GROUP BY users.username, users.id, users.nome";
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="6">P&aacute;ginas p/Usu&aacute;rio&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Usu&aacute;rio</th>
							<th>&nbsp;Nome</th>
							<th>&nbsp;P&aacute;gina</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL))
		{
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["login"] != "") $Login = $rs["login"]; else $Login = "&nbsp;";
			if ($rs["nome"] != "") $Nome = $rs["nome"]; else $Nome = "&nbsp;";
			if ($rs["paginas"] != "") $Paginas = $rs["paginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$Login.'</td>
							<td bgcolor="'.$Cor.'">'.$Nome.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>
';
		}
	}	elseif ($PostRelatorio == 3){
		//Custo p/Usuario
		$SQL = "SELECT users.id as userid, users.nome, users.username as login, sum(jobhistory.jobprice) AS custo FROM users, jobhistory, printers WHERE users.id = jobhistory.userid AND jobhistory.printerid = printers.id GROUP BY users.username, users.id, users.nome";
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="6">Custo p/Usu&aacute;rio&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Usu&aacute;rio</th>
							<th>&nbsp;Nome</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL)){
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["login"] != "") $Login = $rs["login"]; else $Login = "&nbsp;";
			if ($rs["nome"] != "") $Nome = $rs["nome"]; else $Nome = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$Login.'</td>
							<td bgcolor="'.$Cor.'">'.$Nome.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>
';
		}
	}elseif ($PostRelatorio == 4){
		//Impressoes p/Ilhas
		$RegistroSQL = pg_query("SELECT ilhas.nomeilha, users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, ilhas, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND ilhas.codilha = unidades.codilha GROUP BY ilhas.nomeilha, users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT ilhas.nomeilha, users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, ilhas, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND ilhas.codilha = unidades.codilha GROUP BY ilhas.nomeilha, users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						</tr>
							<td class="titTable" colspan="5">Impress&otilde;es p/Ilha&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Nome Ilha</th>
							<th>&nbsp;Nome Unidade</th>
							<th>&nbsp;Impressora</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL))
		{
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["nomeilha"] != "") $NomeIlha = $rs["nomeilha"]; else $NomeIlha = "&nbsp;";
			if ($rs["nomeunidade"] != "") $NomeUnidade = $rs["nomeunidade"]; else $NomeUnidade = "&nbsp;";
			if ($rs["printername"] != "") $Impressora = $rs["printername"]; else $Impressora = "&nbsp;";
			if ($rs["paginas"] != "") $Paginas = $rs["paginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						</tr>
							<td bgcolor="'.$Cor.'">'.$NomeIlha.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Impressora.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>
';
		}
?>
						<tr>
							<td align="center" colspan="5">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="5"><?php
		$main_file = "gera_relatorio.php";
		if(!isset($pagina))
			$pagina = 0;
		if ($pagina != 0){
			$num = $pagina - 1;
			echo '&nbsp;&nbsp;<a href="'.$main_file.'?pg='.$num.'&rel=4&limit='.$LIMIT.'"><< anterior</a>&nbsp;&nbsp;|';
		}
		if ($pagina < $TotalPaginas){
			$num = $pagina + 1;
			echo '&nbsp;&nbsp;<a href="'.$main_file.'?pg='.$num.'&rel=4&limit='.$LIMIT.'">pr&oacute;ximo >></a>&nbsp;&nbsp;';
}
?></td>
						</tr>
<?php
	} elseif ($PostRelatorio == 5) {
		//Pagina/Custo p/Ilhas
		$RegistroSQL = pg_query("SELECT ilhas.nomeilha, users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, ilhas, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND ilhas.codilha = unidades.codilha GROUP BY ilhas.nomeilha, users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, jobhistory.jobdate, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT ilhas.nomeilha, users.username as login, users.nome,jobhistory.title, sum(jobhistory.jobsize) as paginas, sum(jobhistory.jobprice) as custo, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, ilhas, unidades, groups, printergroupsmembers WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND ilhas.codilha = unidades.codilha GROUP BY ilhas.nomeilha, users.id, users.username, users.nome,jobhistory.title, jobhistory.hostname, jobhistory.jobdate, unidades.nomeunidade, groups.groupname, printers.printername ORDER BY users.id LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="4">P&aacute;gina/Custo p/Ilha&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Nome Ilha</th>
							<th>&nbsp;Nome Unidade</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
	while ($rs = pg_fetch_array($ExecSQL))
	{
		if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

		if ($rs["nomeilha"] != "") $NomeIlha = $rs["nomeilha"]; else $NomeIlha = "&nbsp;";
		if ($rs["nomeunidade"] != "") $NomeUnidade = $rs["nomeunidade"]; else $NomeUnidade = "&nbsp;";
		if ($rs["paginas"] != "") $Paginas = $rs["paginas"]; else $Paginas = "&nbsp;";
		if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

		echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$NomeIlha.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>
';
	}
?>
						<tr>
							<td align="center" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="4"><?php
		$main_file = "gera_relatorio.php";
		if ($pagina != 0){
			$num = $pagina - 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=5&limit='.$LIMIT.'><< anterior</a>&nbsp;&nbsp;|';
		}
		if(!isset($pagina))
			$pagina = 0;
		if ($pagina < $TotalPaginas){
			$num = $pagina + 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=5&limit='.$LIMIT.'">pr&oacute;ximo >></a>&nbsp;&nbsp;';
		}
?></td>
						</tr>
<?php
	} elseif ($PostRelatorio == 6) {
		//Impressoes p/Unidade
		if(!isset($limitaunidade))
			$limitaunidade = "";
		$RegistroSQL = pg_query("SELECT unidades.codunidade AS cgcunidade, unidades.nomeunidade AS nomeunidade,unidades.endereco AS endereco, COUNT( distinct printername) AS numerodeimpressoras, COUNT( distinct jobhistory.userid)AS numerousuarios, SUM(jobhistory.jobsize)AS numeropaginas, SUM(jobhistory.jobprice) AS custo FROM unidades, printers ,jobhistory WHERE unidades.codunidade = printers.codunidade AND printers.id = jobhistory.printerid $limitaunidade GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT unidades.codunidade AS cgcunidade, unidades.nomeunidade AS nomeunidade,unidades.endereco AS endereco, COUNT( distinct printername) AS numerodeimpressoras, COUNT( distinct jobhistory.userid)AS numerousuarios, SUM(jobhistory.jobsize)AS numeropaginas, SUM(jobhistory.jobprice) AS custo FROM unidades, printers, jobhistory WHERE unidades.codunidade = printers.codunidade AND printers.id = jobhistory.printerid $limitaunidade GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="8">Impress&otilde;es p/Unidade&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;CGC</th>
							<th>&nbsp;Unidade</th>
							<th>&nbsp;Endere&ccedil;o</th>
							<th>&nbsp;Nº Impressoras</th>
							<th>&nbsp;Nº Usu&aacute;rios</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL))
		{
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["cgcunidade"] != "") $CGCUnidade = $rs["cgcunidade"]; else $CGCUnidade = "&nbsp;";
			if ($rs["nomeunidade"] != "") $NomeUnidade = $rs["nomeunidade"]; else $NomeUnidade = "&nbsp;";
			if ($rs["endereco"] != "") $Endereco = $rs["endereco"]; else $Endereco = "&nbsp;";
			if ($rs["numerodeimpressoras"] != "") $nImpressoras = $rs["numerodeimpressoras"]; else $nImpressoras = "&nbsp;";
			if ($rs["numerousuarios"] != "") $nUsuarios = $rs["numerousuarios"]; else $nUsuarios = "&nbsp;";
			if ($rs["numeropaginas"] != "") $Paginas = $rs["numeropaginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$CGCUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Endereco.'</td>
							<td bgcolor="'.$Cor.'">'.$nImpressoras.'</td>
							<td bgcolor="'.$Cor.'">'.$nUsuarios.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>';
		}
?>
						<tr>
							<td align="center" colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="8"><?php
		$main_file = "gera_relatorio.php";
		if(!isset($pagina)) $pagina = 0;
		if ($pagina != 0){
			$num = $pagina - 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=6&limit='.$LIMIT.'><< anterior</a>&nbsp;&nbsp;|';
		}

		if ($pagina < $TotalPaginas){
			$num = $pagina + 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=6&limit='.$LIMIT.'>pr&oacute;ximo >></a>&nbsp;&nbsp;';
		}
?></td>
					</tr>
<?php
	} elseif ($PostRelatorio == 9) {
		//Impressoes p/Unidade
		$RegistroSQL = pg_query("SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo FROM jobhistory, printers, unidades WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade GROUP BY printers.printername, printers.id, unidades.nomeunidade");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo FROM jobhistory, printers, unidades WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade GROUP BY printers.printername, printers.id, unidades.nomeunidade LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="4">Impress&otilde;es p/Unidade&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;CGC</th>
							<th>&nbsp;Unidade</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL)){
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["printername"] != "") $NomeImpressora = $rs["printername"]; else $NomeImpressora = "&nbsp;";
			if ($rs["nomeunidade"] != "") $NomeUnidade = $rs["nomeunidade"]; else $NomeUnidade = "&nbsp;";
			if ($rs["numeropaginas"] != "") $Paginas = $rs["numeropaginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$NomeImpressora.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>';
		}
?>
						<tr>
							<td align="center" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="4">
<?php
		$main_file = "gera_relatorio.php";
		if(!isset($pagina))
			$pagina = 0;
		if ($pagina != 0){
			$num = $pagina - 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=9&limit='.$LIMIT.'><< anterior</a>&nbsp;&nbsp;|';
		}

		if ($pagina < $TotalPaginas){
			$num = $pagina + 1;
			echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=9&limit='.$LIMIT.'>pr&oacute;ximo >></a>&nbsp;&nbsp;';
		}
?>
							</td>
						</tr>
<?php
	} elseif ($PostRelatorio == 7) {
		//Paginas p/Unidade
		if(!isset($limitaunidade))
			$limitaunidade = "";
		$SQL = "SELECT unidades.codunidade AS cgcunidade, unidades.nomeunidade AS nomeunidade,unidades.endereco AS endereco, COUNT( distinct printername) AS numerodeimpressoras, COUNT( distinct jobhistory.userid)AS numerousuarios, SUM(jobhistory.jobsize)AS numeropaginas, SUM(jobhistory.jobprice) AS custo FROM unidades, printers ,jobhistory WHERE unidades.codunidade = printers.codunidade AND printers.id = jobhistory.printerid $limitaunidade GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco";
		$ExecSQL = pg_query($SQL);
	?>
						<tr>
							<td class="titTable" colspan="6">P&aacute;ginas p/Unidade&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;CGC - Unidade</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL)){
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if (($rs["cgcunidade"] != "") && ($rs["nomeunidade"] != "")) $CGC_Unidade = $rs["cgcunidade"]." - ".$rs["nomeunidade"]; else $CGC_Unidade = "&nbsp;";
			if ($rs["numeropaginas"] != "") $Paginas = $rs["numeropaginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$CGC_Unidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>';
		}
	} elseif ($PostRelatorio == 10) {
		//Paginas/Custo p/Impressora
		$RegistroSQL = pg_query("SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo FROM jobhistory, printers, unidades WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade GROUP BY printers.printername, printers.id, unidades.nomeunidade");
		$TotalRegistros = pg_num_rows($RegistroSQL);	//Total de registros retornados

		$OFFSET = $LIMIT * (isset($_GET["pg"]))?$_GET["pg"]:0;

		//Total de paginas
		$TotalPaginas = ceil($TotalRegistros / $LIMIT);
		if (isset($_GET["pg"]) && $_GET["pg"] != "") $pagina = $_GET["pg"];

		$SQL = "SELECT unidades.codunidade, unidades.nomeunidade as nomeunidade, printers.nomeservidor, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo FROM jobhistory, printers, unidades WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade GROUP BY unidades.codunidade, printers.printername, printers.id, unidades.nomeunidade, printers.nomeservidor LIMIT ".$LIMIT." OFFSET ".$OFFSET;
		$ExecSQL = pg_query($SQL);
?>
						<tr>
							<td class="titTable" colspan="5">P&aacute;ginas p/Impressora&nbsp;</td>
						</tr>
						<tr>
							<th>&nbsp;Impressora</th>
							<th>&nbsp;Servidor</th>
							<th>&nbsp;Unidade</th>
							<th>&nbsp;P&aacute;ginas</th>
							<th>&nbsp;Custo</th>
						</tr>
<?php
		while ($rs = pg_fetch_array($ExecSQL))
		{
			if (isset($Cor) && $Cor == "#ffffff") $Cor = "#ECF2F8"; else $Cor = "#ffffff";

			if ($rs["printername"] != "") $NomeImpressora = $rs["printername"]; else $NomeImpressora = "&nbsp;";
			if ($rs["nomeunidade"] != "") $NomeServidor = $rs["nomeservidor"]; else $NomeServidor = "&nbsp;";
			if ($rs["nomeunidade"] != "") $NomeUnidade = $rs["codunidade"]." - ".$rs["nomeunidade"]; else $NomeUnidade = "&nbsp;";
			if ($rs["numeropaginas"] != "") $Paginas = $rs["numeropaginas"]; else $Paginas = "&nbsp;";
			if ($rs["custo"] != "") $Custo = $rs["custo"]; else $Custo = "&nbsp;";

			echo '						<tr>
							<td bgcolor="'.$Cor.'">'.$NomeImpressora.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeServidor.'</td>
							<td bgcolor="'.$Cor.'">'.$NomeUnidade.'</td>
							<td bgcolor="'.$Cor.'">'.$Paginas.'</td>
							<td bgcolor="'.$Cor.'">R$&nbsp;'.$Custo.'</td>
						</tr>';
		}
?>
						<tr>
							<td align="center" colspan="5">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="5">
<?php
	$main_file = "gera_relatorio.php";
	if(!isset($pagina)) $pagina = 0;
	if ($pagina != 0){
		$num = $pagina - 1;
		echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=9&limit='.$LIMIT.'><< anterior</a>&nbsp;&nbsp;|';
	}

	if ($pagina < $TotalPaginas){
		$num = $pagina + 1;
		echo '&nbsp;&nbsp;<a href='.$main_file.'?pg='.$num.'&rel=9&limit='.$LIMIT.'>pr&oacute;ximo >></a>&nbsp;&nbsp;';
	}
?>
							</td>
						</tr>
<?php
	}
?>
					</table>
				</td>
			</tr>
		</table>