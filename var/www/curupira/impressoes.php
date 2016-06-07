<?php
session_start();
include ("inc/valida_session.php");
include ("inc/pagina.php");
include ("inc/conn.php");

/*Setando a opcao de ordenacao padrao*/
if(!isset($_POST["flg"])) $_POST["flg"] = "DA";
if(!isset($_POST["tord"])) $_POST["tord"] = "DESC";
/*Criando uma nova pagina*/
$pagina = new Pagina();
/*Aspectos especificos da pagina*/

if(!isset($_GET['id']) && $_SESSION["perfil"] != 2 && $_SESSION["perfil"] != 3){
	$_POST["codunidade"]=$_SESSION["uni"];
}

//Se usuario comum o filtro é outro
if($_SESSION["perfil"] ==6){
	unset($_POST["codunidade"]);
}

if(isset($_GET['id']) && $_GET['id']!=""){
	$CodImpressora=$_GET['id'];
	$codimpresq="AND printers.id=".$_GET['id']." ";//Sera colocado na pesquisa.
}

/*Se é um usuario comum limita-se a ele a pesquisa.*/
if($_SESSION["perfil"] == 6){
	$rs = pg_fetch_row(pg_query("SELECT id FROM users WHERE username='".$_SESSION["matricula"]."'"));
	$_GET['userid'] = $rs[0];
}

if(isset($_GET['userid']) && $_GET['userid']!=""){
	$coduser=$_GET['userid'];
  if(isset($codimpresq))
    $codimpresq.=" AND users.id=".$_GET['userid']." ";//Sera colocado na pesquisa
  else
    $codimpresq =" AND users.id=".$_GET['userid']." ";
}

/*Fim parte especifica.*/
$pagina->comeca("Relat&oacute;rio de Trabalhos Impressos", $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);
$pagina->camposBusca();
/*Consulta para essa pagina é: */
$sql = "SELECT users.username as login, users.nome as username,jobhistory.title, jobhistory.jobsize, jobhistory.jobprice, jobhistory.hostname, date(jobhistory.jobdate) ||'/'|| to_char(jobhistory.jobdate, 'HH24:MI:SS')AS data, unidades.nomeunidade, groups.groupname, printers.printername FROM printers, users, jobhistory, unidades, groups, printergroupsmembers  WHERE printers.id = jobhistory.printerid AND users.id = jobhistory.userid AND printers.codunidade = unidades.codunidade AND jobhistory.jobsize > 0 AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid";
if(isset($codimpresq))
  $sql .= " " . $codimpresq;

/*Setando as colunas*/
$params[strtolower("login")] = new EstruturaPF("Usu&aacute;rio", "U");
$params[strtolower("username")] = new EstruturaPF("Nome", "N");
$params[strtolower("printername")] = new StringPF("Impressora", "I",100);
$params[strtolower("title")] = new StringCol("Documento", "D",50);
$params[strtolower("hostname")] = new StringCol("Esta&ccedil;&atilde;o", "E",100);
$params[strtolower("data")] = new DataCol("Data", "DA");
$params[strtolower("jobsize")] = new InfoCol("P&aacute;ginas", "P");
$params[strtolower("jobprice")] = new InfoCol("Custo", "R");

/*Iniciando a tabela*/
$pagina->geraSQL($sql);
$pagina->tabela($params);
$pagina->adicionarVariaveisPaginacao($params);
/*O que estara na linha inicial*/
$linhaInicial = "Detalhes das Impress&otilde;es";
/*O que sera mandado para a proxima pagina. em forma de link.*/
if(isset($links))
  $link = new InfoLink($links,strtolower("title"),"");
else
  $link = new InfoLink(NULL, strtolower("title"),"");
/**/
$indice=0;

$linhaFinal[$indice++] = new InfoLinhaFinal('&nbsp;',6,0,"center");
$linhaFinal[$indice++] = new InfoLinhaFinal('jobsize',1,1);
$linhaFinal[$indice++] = new InfoLinhaFinal('jobprice',1,1);
//?codunidade=$rs[cgcunidade]'";
/*Faz a tabela*/
$pagina->fazTabela($params, $linhaInicial, $linhaFinal, $link);
$pagina->varPaginacao();
$pagina->head();

//Cabecalho
echo $pagina->header; // Imprime header.
echo $pagina->body; // Imprime cabecalho.
echo $pagina->cab; // Imprime cabecalho.

//Comente para paginas que serao para gerar um relatorio para impressao
if (isset($_GET["impressao"]) && $_GET["impressao"] == 1)
	echo $pagina->CabecalhoImpressao($_SESSION["matricula"],$_SESSION["nome"],$_SESSION["departamento"]);

//Conteudo
echo $pagina->campos; //Imprime campos de consulta
echo $pagina->tabela; //Imprime tabela

//Rodapé
echo $pagina->barraPagina;//Imprime paginacao
$pagina->foot();
echo $pagina->footer;//Imprime paginacao

?>
