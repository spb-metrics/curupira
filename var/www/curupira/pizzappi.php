<?php
// incluir as classes jpgraph usadas para criacao do grafico
include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_pie.php");
include ("jpgraph/jpgraph_pie3d.php");

include ("inc/conn.php");
session_start();

if(isset($_GET["salvar"]) && $_GET["salvar"]){
	header("Content-Disposition: attachment; filename=grafico.png");
}

// variaveis
$numero_paginas = array();
$impressoras = array();

$subtitle;
if(isset($_GET["codunidade"]) && $_GET["codunidade"]>=0){
	$tmp = "SELECT unidades.nomeunidade FROM unidades WHERE codunidade = ".$_GET["codunidade"];
	$nome = pg_fetch_row(pg_query($tmp));
	$subtitle.= 'Unidade '.$nome[0].' ';
  if($_SESSION['perfil'] != 4)
  	$incluir.=" AND printers.codunidade = ".$_GET["codunidade"]." ";
  else
  	$incluir.=" AND users.codunidade = ".$_GET["codunidade"]." ";
}

if(isset($_GET["datai"]) && $_GET["datai"]!=""){
	list($DiaI,$MesI,$AnoI) = explode('/',$_GET["datai"]);
	list($DiaF,$MesF,$AnoF) = explode('/',$_GET["dataf"]);
	$datainicial = $AnoI."-".$MesI."-".$DiaI;
	$datafinal = $AnoF."-".$MesF."-".$DiaF;
	$incluir .= " AND date(jobhistory.jobdate) between '".$datainicial."' AND '".$datafinal."' ";
	$subtitle .= 'Data '.$_GET["datai"]. " a ".$_GET["dataf"].' ';
}

// Busca no banco o número de paginas impressas por impressoras, dada a unidade
$SQL = "SELECT printers.printername AS printername, SUM(jobhistory.jobsize) AS numeropaginas FROM jobhistory, printers, users, unidades WHERE jobhistory.printerid = printers.id AND unidades.codunidade = printers.codunidade and users.id = jobhistory.userid ";
if(isset($incluir))
  $SQL .= $incluir . " ";
$SQL .= "GROUP BY printers.printername, unidades.nomeunidade";

// envia consulta
$result = pg_query($SQL) or die("deu pau");

$i=0;
while($linha = pg_fetch_assoc($result)){
	$numero_paginas[$i] = $linha['numeropaginas'];
	$impressoras[$i] = $linha['printername']." (".$linha['numeropaginas'].")";
	$i++;
}

// criar novo grafico de 350x200 pixels com tipo de
// imagem automatico
$grafico = new PieGraph("750", "400","auto");

// adicionar sombra
$grafico->SetShadow();

// tÃ­tulo do grafico
if(!isset($subtitle)) $subtitle = "";
$grafico->title->Set(iconv("UTF-8", "ISO-8859-1","Páginas impressas por impressora\n".$subtitle));
$grafico->title->SetFont(FF_FONT1,FS_BOLD);
$grafico->subtitle->Set($subtitle);
// definir valores ao grafico
$p1 = new PiePlot($numero_paginas);

// centralizar a 45% da largura
$p1->SetCenter(0.45);
$p1->SetLabelType(1);
// definir legendas

$p1->SetLegends($impressoras);
//$p1->HideLabels(false);
$p1->ShowBorder();
$p1->ExplodeAll(20);
// adicionar valores ao grafico
$grafico->Add($p1);

// gerar o grafico
$grafico->Stroke();
?>
