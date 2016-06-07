<?php
session_start();
include ("inc/valida_session.php");
include ("inc/pagina.php");
include ("inc/conn.php");

/*Setando a opção de ordenação padrao*/
if(!isset($_POST["flg"])) $_POST["flg"] = "R";
if(!isset($_POST["tord"])) $_POST["tord"] = "DESC";
/*Criando uma nova pagina*/
$pagina = new Pagina();
/*Verificando os perfis*/
/*Utilizar os aspectos especificos da pagina na consulta*/

if(!isset($_GET['id']) && $_SESSION["perfil"] != 2 && $_SESSION["perfil"] != 3){
	$_POST["codunidade"]=$_SESSION["uni"];
}

if(isset($_GET['id']) && $_GET['id']!="" && (!isset($_POST["codunidade"]) || $_POST["codunidade"]=-1)){
	$CodImpressora=$_GET['id'];
	$codimpresq="AND printers.id=".$_GET['id']." ";
	/*Pegando o nome da impressora*/
  if(!isset($CODSQ)) $CODSQ = "";
	$SQL = pg_query("SELECT printers.description, printers.id, printers.printername, unidades.nomeunidade, printers.priceperpage, groups.groupname FROM printers, unidades, groups , printergroupsmembers WHERE printers.codunidade = unidades.codunidade AND printers.id = printergroupsmembers.printerid AND groups.id = printergroupsmembers.groupid AND printers.id = ".$CodImpressora." $CODSQ ");
	$DadosImpressora;
	$rs = pg_fetch_assoc($SQL);
	$NomeImpressora = $rs['printername'];
	$NomeImpressora = " de ".$NomeImpressora;
  if($_SESSION["perfil"] == 4){
	  $_POST["codunidade"]=$_SESSION["uni"];
  }

}

/*Fim aspectos*/
if(!isset($NomeImpressora)) $NomeImpressora = "";
$pagina->comeca("Relat&oacute;rio de Usu&aacute;rios $NomeImpressora", $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);
$pagina->camposBusca();
/*Consulta para essa pagina é: */
if(!isset($codimpresq)) $codimpresq = "";
$sql = "SELECT users.id as userid, users.nome as username, users.username as login, sum(jobhistory.jobsize) AS paginas, sum(jobhistory.jobprice) AS custo FROM users, jobhistory, printers WHERE users.id = jobhistory.userid AND jobhistory.printerid = printers.id $codimpresq GROUP BY users.username,users.id, users.nome";
//print "Valor".$_POST["codunidade"];
/*Setando as colunas*/
$params[strtolower("login")] = new InfoCol("Usu&aacute;rio", "U");
$params[strtolower("username")] = new StringCol("Nome", "N",50);
$params[strtolower("paginas")] = new InfoCol("P&aacute;ginas", "P");
$params[strtolower("custo")] = new InfoCol("Custo", "R");
/*Iniciando a tabela*/
$pagina->geraSQL($sql);
$pagina->tabela($params);
$pagina->adicionarVariaveisPaginacao($params);
/*O que estara na linha inicial*/
$linhaInicial = "Impress&otilde;es por usu&aacute;rio";
/*O que sera mandado para a proxima pagina. em forma de link.*/
$links["userid"] = 0;
//$links[""] = 0;
if(isset($_GET["id"]))
  $link = new InfoLink($links,strtolower("username"),"impressoes.php?id=".$_GET["id"]);
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

//Cabeçalho
echo $pagina->header; // Imprime header.
echo $pagina->body; // Imprime cabeçalho.
echo $pagina->cab; // Imprime cabeçalho.
//echo $DadosImpressora; // Imprime dados da impressora.
//Comente para paginas que serão para gerar um relatorio para impressão
if (isset($_GET["impressao"]) && $_GET["impressao"] == 1)
	echo $pagina->CabecalhoImpressao($_SESSION["matricula"],$_SESSION["nome"],$_SESSION["departamento"]);

//Conteúdo
echo $pagina->campos; //Imprime campos de consulta
echo $pagina->tabela; //Imprime tabela
//Rodapé
echo $pagina->barraPagina;//Imprime paginação
echo "<br />";
$pagina->foot();
echo $pagina->footer;//Imprime paginação

?>
