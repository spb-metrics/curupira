<?php
include ("inc/pagina.php");
include ("inc/conn.php");
include ("inc/valida_session.php");
if(!isset($_SESSION))
  session_start();
/*Setando a opcao de ordenacao padrao*/
if(!isset($_POST["flg"])) $_POST["flg"] = "U";
if(!isset($_POST["tord"])) $_POST["tord"] = "ASC";

/*Criando uma nova pagina*/
$pagina = new Pagina();

/*Comeca a pagina com o titulo e passando as variaveis*/
$pagina->comeca("Relat&oacute;rio de Unidades", $_SERVER["PHP_SELF"],$_POST, $_GET);
$pagina->camposBusca();

/*Consulta para essa pagina é: */
if($_SESSION["perfil"]==6){
	header("location: relimpressoras.php");
}else if ($_SESSION["perfil"]== 4){
  $limitaunidade = " AND users.codunidade = ".$_SESSION["uni"]." ";
}else if($_SESSION["perfil"]!=2 && $_SESSION["perfil"]!=3){
	$limitaunidade = " AND unidades.codunidade = ".$_SESSION["uni"]." ";
}

if($pagina->post["dtinicial"] != "" || $_SESSION['perfil'] == 4){
	$sql = "SELECT unidades.codunidade AS cgcunidade, unidades.nomeunidade AS nomeunidade,unidades.endereco AS endereco, COUNT( distinct printername) AS numerodeimpressoras, COUNT( distinct jobhistory.userid)AS numerousuarios, SUM(jobhistory.jobsize)AS numeropaginas, SUM(jobhistory.jobprice) AS custo FROM unidades, printers ,jobhistory,users WHERE unidades.codunidade = printers.codunidade AND printers.id = jobhistory.printerid AND users.id = jobhistory.userid ";
  if(isset($limitaunidade)){
    $sql .= $limitaunidade;
  }
  $sql .= " GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco";
}
else{
	$sql = "SELECT unidades.codunidade AS cgcunidade, unidades.nomeunidade AS nomeunidade,unidades.endereco AS endereco, COUNT(DISTINCT qryRelUnidades.printerid) AS numerodeimpressoras , COUNT (distinct qryRelUnidades.userid) as numerousuarios, SUM(qryRelUnidades.CUSTO) as custo, SUM (qryRelUnidades.numeropaginas ) as numeropaginas FROM qryRelUnidades, unidades, printers WHERE unidades.codunidade = printers.codunidade AND printers.id = qryRelUnidades.printerid ";
  if(isset($limitaunidad))
    $sql .= $limitaunidadee . " ";
  $sql .= "GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco";

}
$params[strtolower("CGCUNIDADE")] = new InfoCol("CGC", "C");
$params[strtolower("NomeUnidade")] = new InfoCol("Unidade", "U");
$params[strtolower("Endereco")] = new InfoCol("Endere&ccedil;o", "E");
$params[strtolower("NumerodeImpressoras")] = new InfoCol("Impressoras", "I");
$params[strtolower("NumeroUsuarios")] = new InfoCol("Usu&aacute;rios", "S");
$params[strtolower("NumeroPaginas")] = new InfoCol("P&aacute;ginas", "P");
$params[strtolower("Custo")] = new InfoCol("Custo", "T");

/*Iniciando a tabela*/
if(isset($_POST["dtinicial"]) && $_POST["dtinicial"] != ""){
  if(!isset($limitaunidade)) $limitaunidade = "";
   $SQLCOUNT =$pagina->geraSQL("SELECT COUNT( distinct printername) AS numerodeimpressoras, COUNT( distinct jobhistory.userid)AS numerousuarios, SUM(jobhistory.jobsize) AS numeropaginas, SUM(jobhistory.jobprice) AS custo FROM unidades, printers ,jobhistory WHERE unidades.codunidade = printers.codunidade AND printers.id = jobhistory.printerid $limitaunidade GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco");
}

//echo $SQLCOUNT;
$pagina->geraSQL($sql);
$pagina->tabela($params);
$pagina->adicionarVariaveisPaginacao($params);
/*O que estara na linha inicial*/
$linhaInicial = "Relat&oacute;rio por Centro de Custo";

/*O que sera mandado para a proxima pagina. em forma de link.*/
$links[strtolower("cgcunidade")] = 0;
$link = new InfoLink($links,strtolower("nomeunidade"),"relimpressoras.php");

/*Contando o numero de unidades*/
$indices=0;
$linhaFinal[$indices++] = new InfoLinhaFinal('registros Unidade(s)',3,1,"center");
$linhaFinal[$indices++] = new InfoLinhaFinal('numerodeimpressoras',1,1);
$linhaFinal[$indices++] = new InfoLinhaFinal('numerousuarios',1,1);
$linhaFinal[$indices++] = new InfoLinhaFinal('numeropaginas',1,1);
$linhaFinal[$indices++] = new InfoLinhaFinal('custo',1,1);
//?codunidade=$rs[cgcunidade]'";

/*Faz a tabela*/

$pagina->fazTabela($params, $linhaInicial, $linhaFinal, $link);
//echo (time() - $a )."s" ;
$pagina->varPaginacao();
$pagina->head();
echo $pagina->header; // Imprime header.
echo $pagina->body; // Imprime cabecalho.
echo $pagina->cab; // Imprime cabecalho.
echo $pagina->campos; //Imprime campos de consulta
echo $pagina->tabela; //Imprime tabela
echo $pagina->barraPagina;//Imprime paginacao
echo "<br />";
$pagina->foot();
echo $pagina->footer;//Imprime paginacao
?>
