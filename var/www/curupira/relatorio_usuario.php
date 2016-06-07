<?php
session_start();
include ("inc/valida_session.php");
include ("inc/paginauser.php");
include ("inc/conn.php");

/*Setando a opcao de ordenacao padrao*/
if(!isset($_POST["flg"])) $_POST["flg"] = "R";
if(!isset($_POST["tord"])) $_POST["tord"] = "DESC";
/*Criando uma nova pagina*/
$pagina = new PaginaComUsuario();
/*Utilizar os aspectos especificos da pagina na consulta*/


if(!isset($_GET['id']) && $_SESSION["perfil"] != 2 && $_SESSION["perfil"]!=3 && $_SESSION["perfil"] != 4){
	//$_POST["codunidade"]=$_SESSION["uni"];
	$limitaunidade = " AND printers.codunidade=".$_SESSION["uni"]." ";
// print $limitaunidade;
}
if($_SESSION['perfil'] == 4){
	$limitaunidade = " AND users.codunidade=".$_SESSION["uni"]." ";
}
if(isset($_GET['id']) && $_GET['id']!="" && ($_POST["codunidade"]<=0)){
	$CodImpressora=$_GET['id'];
	$codimpresq="AND printers.id=".$_GET['id']." ";
	/*Pegando o nome da impressora*/
	$SQL = pg_query("SELECT printers.id, printers.printername, unidades.nomeunidade, printers.priceperpage, groups.groupname FROM printers, unidades, groups , printergroupsmembers WHERE printers.codunidade = unidades.codunidade AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND printers.id = ".$CodImpressora." $CODSQ ") or die ("Impressora n&atilde;o encontrada");
	$rs = pg_fetch_array($SQL);
	$NomeImpressora = $rs['printername'];
	$NomeImpressora = " de ".$NomeImpressora;
}

/*Fim aspectos*/
if(!isset($NomeImpressora))
  $NomeImpressora = "";
$pagina->comeca("Usu&aacute;rios $NomeImpressora", $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);
$pagina->camposBusca();
/*Consulta para essa pagina é: */
$sql = "SELECT users.id as userid, users.nome as username, users.username as login, sum(jobhistory.jobsize) AS paginas, sum(jobhistory.jobprice) AS custo FROM users, jobhistory, printers WHERE jobhistory.jobsize > 0 AND users.id = jobhistory.userid AND jobhistory.printerid = printers.id ";
if(isset($codimpresq))
  $sql .= $codimpresq . " ";
if(isset($limitaunidade))
  $sql .= $limitaunidade . " ";
$sql .= "GROUP BY users.username,users.id, users.nome" or die ("Impressora n&atilde;o encontrada");
/*Setando as colunas*/
$params[strtolower("login")] = new InfoCol("Usu&aacute;rio", "U");
$params[strtolower("username")] = new StringCol("Nome", "N",50);
$params[strtolower("paginas")] = new InfoCol("P&aacute;ginas", "P");
$params[strtolower("custo")] = new InfoCol("Pre&ccedil;o", "R");
/*Iniciando a tabela*/
$pagina->geraSQL($sql);
$pagina->tabela($params);
$pagina->adicionarVariaveisPaginacao($params);
/*O que estara na linha inicial*/
$linhaInicial = "Detalhes dos Jobs";
/*O que sera mandado para a próxima pagina. em forma de link.*/
$links["userid"] = 0;
//$links[""] = 0;
if(isset($_GET["id"]))
  $link = new InfoLink($links,strtolower("username"),"impressoes.php?imprid=".$_GET["id"]);
else
  $link = new InfoLink($links,strtolower("username"),"impressoes.php");
/**/
$indice=0;
$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;',1,0,"center");
$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;',1,0,"center");
$linhaFinal[$indice++] = new InfoLinhaFinal('paginas',1,1);
$linhaFinal[$indice++] = new InfoLinhaFinal('custo',1,1);
//?codunidade=$rs[cgcunidade]'";
/*Faz a tabela*/
$pagina->fazTabela($params, $linhaInicial, $linhaFinal, $link);
$pagina->varPaginacao();
$pagina->head();
echo $pagina->header; // Imprime header.
echo $pagina->body; // Imprime cabecalho.
echo $pagina->cab; // Imprime cabecalho.
echo $pagina->campos; //Imprime campos de consulta
echo $pagina->tabela; //Imprime tabela
echo $pagina->barraPagina;//Imprime paginacao
$pagina->foot();
echo $pagina->footer;//Imprime paginacao

?>
