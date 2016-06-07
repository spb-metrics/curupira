<?php
// exemplo2.php
include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_bar.php");
include ("inc/conn.php");
if(!isset($_SESSION)) session_start();
if(isset($_GET["salvar"]) && $_GET["salvar"]){
	header("Content-Disposition: attachment; filename=grafico.png");
}

$grafico = new Graph("750", "400", "auto");
$grafico->img->SetMargin(40,40,40,40);
$grafico->SetScale("textlin");
$grafico->SetFrame(true,'#CCCCCC',1);
$grafico->SetColor('white');
$grafico->SetMarginColor('white');
$grafico->SetBox();
$grafico->SetShadow();
$grafico->title->Set(iconv("UTF-8", "ISO-8859-1", 'Páginas por Hora'));

if(isset($_GET["codunidade"]) && $_GET["codunidade"]>=0){
	$tmp = "SELECT unidades.nomeunidade FROM unidades WHERE codunidade = ".$_GET["codunidade"];
	$nome = pg_fetch_row(pg_query($tmp));
	$subtitle.= 'Unidade '.$nome[0].' ';
}

if(isset($_GET["codimpressora"]) && $_GET["codimpressora"] >= 0){
$tmp = "SELECT printers.printername FROM printers WHERE printers.id = ".(int)$_GET["codimpressora"];
$nome = pg_fetch_row(pg_query($tmp));
$subtitle .="Impressora ".$nome[0].' ';
}

$grafico->yscale->SetGrace(5);

if(isset($_GET["datai"]) && $_GET["datai"]!=""){
	list($DiaI,$MesI,$AnoI) = split("[/]",$_GET["datai"]);
	list($DiaF,$MesF,$AnoF) = split("[/]",$_GET["dataf"]);
	$datainicial = $AnoI."-".$MesI."-".$DiaI;
	$datafinal = $AnoF."-".$MesF."-".$DiaF;
	$incluir .= " AND date(jobhistory.jobdate) between '".$datainicial."' AND '".$datafinal."' ";
	$subtitle .= 'Data '.$_GET["datai"]. " a ".$_GET["dataf"].' ';
}

if(isset($_GET["codunidade"]) && $_GET["codunidade"]>=0){
  if($_SESSION['perfil'] !=4){
	$incluir.=" AND printers.codunidade = ".$_GET["codunidade"]." ";
  }
  else
	$incluir.=" AND users.codunidade = ".$_GET["codunidade"]." ";
}

if(isset($_GET["codimpressora"]) && $_GET["codimpressora"] >= 0){
	$incluir.=" AND printers.id = ".$_GET["codimpressora"]." ";
}



$SQL = "SELECT to_char(jobhistory.jobdate, 'HH24') AS hora, sum(jobhistory.jobsize) as paginas FROM jobhistory, printers, users WHERE users.id = jobhistory.userid and printers.id = jobhistory.printerid ";
if(isset($incluir))
  $SQL .= $incluir . " ";
$SQL .= "GROUP BY hora ORDER BY hora";
//$subtitle.=$SQL;
if(isset($subtitle))
  $grafico->subtitle->Set($subtitle);
//echo $SQL;
$consulta = pg_query($SQL);
$pagina_hora;

for($i=0; $i<24;$i++){
	$pagina_hora[$i] = 0;
}

while($result = pg_fetch_assoc($consulta)){
	$pagina_hora[(int)$result["hora"]] = $result["paginas"];
}

$barras = new BarPlot(array_values($pagina_hora));
$barras->SetFillColor("#0099FF");
$barras->ShowValue(true);
$barras->SetWidth(0.75);

$grafico->yaxis->title->Set("Paginas");
$grafico->title->SetFont(FF_FONT1,FS_BOLD);
$grafico->xaxis->title->Set("Hora");
$novoarranjo;
$k=0;

foreach (array_keys($pagina_hora) as $keys){
	$novoarranjo[$k++]=(($keys<10)?"0".(int)$keys."h":(int)$keys."h");
}

$grafico->xaxis->SetTickLabels($novoarranjo);

$grafico->Add($barras);
$grafico->Stroke();
?>
