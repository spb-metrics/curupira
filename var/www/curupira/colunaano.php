<?php
	include ("jpgraph/jpgraph.php");
	include ("jpgraph/jpgraph_bar.php");
	include ("inc/conn.php");
	session_start();
	function color(){
		$r = rand(0,255);
		$g = rand(0,255);
		$b = rand(0,255);
		return "#".dechex($r) . dechex($g) . dechex($b);
	}

	if(isset($_GET["salvar"]) && $_GET["salvar"]){
		header("Content-Disposition: attachment; filename=grafico.png");
	}

	// margem das partes principais do gráfico (dados), o que está
	// fora da margem fica separado para as labels, títulos, etc
	$grafico = new Graph("750", "400", "auto");
	$grafico->img->SetMargin(40,100,40,40);
	$grafico->SetScale("textlin");
	$grafico->SetFrame(true,'#CCCCCC',1);
	$grafico->SetColor('white');
	$grafico->SetMarginColor('white');
	$grafico->SetBox();
	$grafico->SetShadow();
	$grafico->title->Set(iconv("UTF-8", "ISO-8859-1", 'Páginas por Período'));

	if(isset($_GET["codunidade"]) && $_GET["codunidade"]>=0){
		$tmp = "SELECT unidades.nomeunidade FROM unidades WHERE codunidade = ".$_GET["codunidade"];
		$nome = pg_fetch_row(pg_query($tmp));
		if(isset($subtitle))
			$subtitle.= 'Unidade '.$nome[0].' ';
		else
			$subtitle = 'Unidade '.$nome[0].' ';
	}

	$grafico->yscale->SetGrace(5);

	if(isset($_GET["datai"])){
		list($DiaI,$MesI,$AnoI) = explode("/",$_GET["datai"]);
		list($DiaF,$MesF,$AnoF) = explode("/",$_GET["dataf"]);
		$datainicial = $AnoI."-".$MesI."-".$DiaI;
		$datafinal = $AnoF."-".$MesF."-".$DiaF;
		if(isset($incluir))
			$incluir .= " AND date(jobhistory.jobdate) between '".$datainicial."' AND '".$datafinal."' ";
		else
			$incluir = " AND date(jobhistory.jobdate) between '".$datainicial."' AND '".$datafinal."' ";

		$subtitle .= 'Data '.$_GET["datai"]. " a ".$_GET["dataf"].' ';
	}

	if(isset($_GET["codunidade"]) && $_GET["codunidade"]>=0){
			$incluir.=" AND printers.codunidade = ".$_GET["codunidade"]." ";
		if ($_SESSION['perfil'] ==4)
			$incluir.=" AND users.codunidade = ".$_SESSION['uni']." ";
	}

	$grafico->subtitle->Set($subtitle);

	$SQL = "SELECT to_char(jobhistory.jobdate, 'YYYY-MM') AS ano, sum(jobhistory.jobsize) as paginas, printers.printername as nome FROM jobhistory, printers,users WHERE users.id = jobhistory.userid and printers.id = jobhistory.printerid $incluir GROUP BY ano, printers.printername ";
	//echo $SQL;
	$consulta = pg_query($SQL) or die ("Sql - $SQL");
	$pagina_mes;
	while($result = pg_fetch_assoc($consulta)){
			list($ano,$mes)=split("[-]",$result["ano"]);
			$index = (($ano-$AnoI)*12+$mes);
			$pagina_mes[$result["nome"]][$index] = $result["paginas"];
	}

	$barras;
	$min=(int)$MesI; $max=(int)(($AnoF-$AnoI)*12+$MesF);


	$barras = array();
	$k=0;
	if(isset($pagina_mes) && $pagina_mes != NULL){
		foreach($pagina_mes as $printer => $meses){
			$plot= array();

			for($i=$min;$i<=$max ;$i++){
				$plot[$i]=0;
			}

			foreach($meses as $mes =>$paginas){
				$plot[$mes] = $paginas;
			}
				$barras[$k] = new BarPlot(array_values($plot));
				$barras[$k]->ShowValue(true);
				$barras[$k]->SetWidth(1.0);
				$barras[$k]->SetColor("#000000");
				$fill_color = color();
				$barras[$k]->SetFillColor($fill_color);
				$barras[$k]->SetLegend($printer);
				$k++;
		}
	}
	if(!empty($barras)){
		$grafico->Add(new GroupBarPlot($barras));
	}else {
		for($i=$min;$i<=$max;$i++)
			$arranjo[$i]=0;
		$tmp = new BarPlot(array_values($arranjo));
		$grafico->Add($tmp);
	}
	$grafico->yaxis->title->Set("Paginas");
	$grafico->title->SetFont(FF_FONT1,FS_BOLD);
	$grafico->xaxis->title->Set("Mes");
	$grafico->legend->pos(0, 0.1, 'right');
	$novoarranjo;
	$k=0;
	$plot= array();
	$ano = $AnoI;

	for($i=$min;$i<=$max ;$i++){
		$ano = $AnoI+ (int)($i/13);
		$mes = $i - ($ano-$AnoI)*12;
		$plot[$k] = $mes."-".$ano;
		$k++;
	}
	$grafico->xaxis->SetTickLabels(array_values($plot));

	$grafico->Stroke();
?>
