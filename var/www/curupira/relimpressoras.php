<?php
	session_start();
	include ("inc/valida_session.php");
	include ("inc/pagina.php");
	include ("inc/conn.php");

	if(!isset($_POST["flg"])) $_POST["flg"] = "I";
	if(!isset($_POST["tord"])) $_POST["tord"] = "ASC";

	if(isset($_GET["cgcunidade"]) && !isset($_POST["codunidade"])){
		$_POST["codunidade"]=$_GET["cgcunidade"];
	}

	if($_SESSION["perfil"] != 2 && $_SESSION["perfil"] != 3){
		$_POST["codunidade"]=$_SESSION["uni"];
	}

	if($_SESSION["perfil"] ==6){
		unset($_POST["codunidade"]);
	}
	$_SERVER['HTTP_REFERER'] = 'https://'.$_SERVER['HTTP_HOST'].'/relimpressoras.php';
	$pagina = new Pagina();

	if(isset($_POST['codunidade']) && $_POST['codunidade'] != 0){
		$SQL = pg_query("SELECT unidades.nomeunidade FROM unidades WHERE unidades.codunidade = ".$_POST['codunidade'])
	or die ("Unidade não encontrada");
		$rs = pg_fetch_array($SQL);
		$NomeUnidade = $rs['nomeunidade'];
	}
	if(!isset($NomeUnidade))
		$NomeUnidade = "";

	$pagina->comeca("- Relat&oacute;rio de Impressoras - ".$NomeUnidade, $_SERVER["PHP_SELF"],$_POST, $_GET);
	$pagina->camposBusca();
	if ($_SESSION["perfil"]==4){
		if(isset($_GET["cgcunidade"])){
			$codunidade_80=" AND unidades.codunidade = $_GET[cgcunidade]";
		}
		$SQL = "SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo, localizacao FROM jobhistory, printers, unidades, users WHERE users.id = jobhistory.userid and jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade $codunidade_80 GROUP BY printers.printername, printers.id, unidades.nomeunidade,localizacao";

	}else{
	if($_SESSION["perfil"]==6)
		$SQL = "SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo,localizacao FROM jobhistory, printers, unidades, users WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade AND users.id = jobhistory.userid AND users.username='".$_SESSION["matricula"]."' GROUP BY printers.printername, printers.id, unidades.nomeunidade,localizacao";
	else
		$SQL = "SELECT unidades.nomeunidade as nomeunidade, printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas, printers.id AS id, SUM(jobhistory.jobprice) AS custo, localizacao FROM jobhistory, printers, unidades, users WHERE users.id = jobhistory.userid and jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade GROUP BY printers.printername, printers.id, unidades.nomeunidade,localizacao";
	}
	/*Setando as colunas*/
	$params[strtolower("printername")] = new InfoCol("Impressora", "I");
	$params[strtolower("localizacao")] = new InfoCol("Local", "L");
	$params[strtolower("NomeUnidade")] = new EstruturaPF("Unidade", "U");
	$params[strtolower("NumeroPaginas")] = new InfoCol("P&aacute;ginas", "P");
	$params[strtolower("Custo")] = new InfoCol("Custo", "T");

	/*Iniciando a tabela*/
	$pagina->geraSQL($SQL);
	$pagina->tabela($params);
	$pagina->adicionarVariaveisPaginacao($params);

	/*O que estará na linha inicial*/
	$linhaInicial = "Relat&oacute;rio por Impressora";

	/*O que será mandado para a próxima página. em forma de link.*/
	if($_SESSION["perfil"]==6){
		$links["id"] = 0;
		$pegar=pg_fetch_row(pg_query("SELECT users.id FROM users WHERE users.username='".$_SESSION["matricula"]."'"));
		$link = new InfoLink($links,strtolower("printername"),"impressoes.php?userid=".$pegar[0]);
	} else{
		$links["id"] = 0;
		$link = new InfoLink($links,strtolower("printername"),"detalheimpressora.php");
	}

	$indice=0;
	$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;', 1,0, "center");
	$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;', 1,0, "center");
	$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;', 1, 0, "center");
	$linhaFinal[$indice++] = new InfoLinhaFinal('numeropaginas', 1, 1);
	$linhaFinal[$indice++] = new InfoLinhaFinal('custo', 1, 1);

	$pagina->fazTabela($params, $linhaInicial, $linhaFinal, $link);
	$pagina->varPaginacao();
	$pagina->head();

	echo $pagina->header;
	echo $pagina->body;
	echo $pagina->cab;

	if (isset($_GET["impressao"]) && $_GET["impressao"] == 1)
		echo $pagina->CabecalhoImpressao($_SESSION["matricula"],$_SESSION["nome"],$_SESSION["departamento"]);

	echo $pagina->campos;
	echo $pagina->tabela;

	echo $pagina->barraPagina;
	echo "<br />
";
	$pagina->foot();
	echo $pagina->footer;
?>
