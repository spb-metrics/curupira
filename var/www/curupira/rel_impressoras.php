<?php
include('inc/head.php');
include('inc/paginacao2.php');
if(!isset($_SESSION)) session_start();
$ADICIONARGET="";

if($_SESSION["perfil"] != 2 && $_SESSION["perfil"]!=3 && $_SESSION["perfil"]!=4){
	$_POST["codunidade"]=$_SESSION["uni"];
	//echo $_POST["codunidade"];
	$CodUnit="AND unidades.codunidade= ".$_SESSION["uni"];
}else
if ($_SESSION['perfil']== 4){
  $_POST["codunidade"]=$_SESSION["uni"];
	//echo $_POST["codunidade"];
	$CodUnit="AND users.codunidade= ".$_SESSION["uni"];

}
else
if(isset($_POST['codunidade']) && $_POST['codunidade'] >= 0){
	$SQL = pg_query("SELECT unidades.nomeunidade FROM unidades WHERE unidades.codunidade = ".$_POST['codunidade'])
 or die ("Unidade n&atilde;o encontrada");
	$rs = pg_fetch_array($SQL);
	$NomeUnidade = $rs['nomeunidade'];
	$ADICIONARGET='&codunidade='.$_POST['codunidade'];
//	$_POST['page']=1;
	$CodUnit="AND unidades.codunidade= ".$_POST['codunidade'];
	//$limitaunidade = "AND printers.codunidade = ".$_REQUEST['codunidade'];
}
else $NomeUnidade =" ";

$Data=" ";

/*Post tem prioridade*/
if (isset($_POST["dtinicial"]) && $_POST["dtinicial"] != "" && $_POST["dtfinal"] != "")
{
	list($DiaI,$MesI,$AnoI) = explode('/',$_POST["dtinicial"]);
	list($DiaF,$MesF,$AnoF) = explode('/',$_POST["dtfinal"]);
//	$DataInicial = $DiaI."/".$MesI."/".$AnoI;
//	$DataFinal = $DiaF."/".$MesF."/".$AnoF;
	$DataInicial = $AnoI.'-'.$MesI.'-'.$DiaI;
	$DataFinal = $AnoF.'-'.$MesF.'-'.$DiaF;
	$Data=" AND date(jobdate) >= '".$DataInicial."' AND date(jobdate) <= '".$DataFinal."'";
//	$_POST['page']=1;
	$ADICIONARGET=($ADICIONARGET=="")? "&dtinicial=".$_POST["dtinicial"]."&dtfinal=".$_POST["dtfinal"]:  $ADICIONARGET."&dtinicial=".$_POST["dtinicial"]."&dtfinal=".$_POST["dtfinal"];
}
else if (isset($_GET["dtinicial"]) && $_GET["dtinicial"] != "" && $_GET["dtfinal"] != "")
{
	list($DiaI,$MesI,$AnoI) = explode("/",$_GET["dtinicial"]);
	list($DiaF,$MesF,$AnoF) = explode("/",$_GET["dtfinal"]);
	$DataInicial = $AnoI."/".$MesI."/".$DiaI;
	$DataFinal = $AnoF."/".$MesF."/".$DiaF;
	$Data=" AND date(jobdate) >= '".$DataInicial."' AND date(jobdate) <= '".$DataFinal."'";

	$ADICIONARGET=($ADICIONARGET=="")? "&dtinicial=".$_GET["dtinicial"]."&dtfinal=".$_GET["dtfinal"]:  $ADICIONARGET."&dtinicial=".$_GET["dtinicial"]."&dtfinal=".$_GET["dtfinal"];
}


$ORDENA='  ';
$GROUPCA='  ';

$TIPOORDENA = "ASC";

if(!isset($_GET['ord']) || $_GET['ord'] !='DESC'){
	$_GET['ord']='ASC';
}

switch($_GET['ord']){
	case 'ASC': $TIPOORDENA= 'ASC';
				break;
	case 'DESC': $TIPOORDENA= 'DESC';
				break;
	default: $TIPOORDENA= 'ASC';
}


$ADICIONANAPAGINACAO=$ADICIONARGET;
$ADICIONANAPAGINACAO.="&ord=$TIPOORDENA";

if(!isset($_GET['flg']) || $_GET['flg'] ==''){
	$_GET['flg']='I';
}


switch($_GET['flg']){
	case 'I':  $ORDENA=" ORDER BY printers.printername";
				$ADICIONANAPAGINACAO="?flg=I$ADICIONANAPAGINACAO";
				break;
	case 'P':  $ORDENA=" ORDER BY paginas";
				$ADICIONANAPAGINACAO="?flg=P$ADICIONANAPAGINACAO";
				break;
	case 'U': $ORDENA=" ORDER BY unidades.nomeunidade";
				$ADICIONANAPAGINACAO="?flg=U$ADICIONANAPAGINACAO";
				break;
	case 'C': $ORDENA=" ORDER BY preco";
				$ADICIONANAPAGINACAO="?flg=C$ADICIONANAPAGINACAO";
				break;
	default: $ORDENA=" ORDER BY printers.printername";
}

$ORDENA = $ORDENA."  ".$TIPOORDENA."  ";

?>
<form name="frm_data" method="post">
 <input type="hidden" name="page" value=<?php echo (isset($POST["page"]))?'"'.$POST["page"].'"':'"1"';?>>
<table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr><td>
	<?php
		echo '<table style="MARGIN-BOTTOM:5px" border="0" width="100%" cellpadding="0" cellspacing="1" align="center" bgColor="black">
<tr><td class="Cab" align="center" colspan="5">Detalhes das Impressoras
</td></tr>
</table>';
		?>
	</tr></td>
	<tr>
	<td align="left">
	Data Inicial:
	<input type="text" size="10" maxlength="10" name="dtinicial" onblur="javascript:ValidaCampo(this.form.name,this.name,'Data Inicial');" onchange="javascript:FormataData(this.form.name,this.name,event);" value="<?php
			if (isset($_REQUEST['dtinicial']) && $_REQUEST['dtinicial'] != ""){
				echo $_REQUEST['dtinicial'];
      }
		 ?>">
	Data Final:
	<input type="text" size="10" maxlength="10" name="dtfinal" onblur="javascript:ValidaCampo(this.form.name,this.name,'Data Final');" onchange="javascript:FormataData(this.form.name,this.name,event);" value="<?php
			if (isset($_REQUEST['dtfinal']) && $_REQUEST['dtfinal'] != ""){
				echo $_REQUEST["dtfinal"];
      }
		 ?>">
		 <?php
		if($_SESSION["perfil"] == 2 || $_SESSION["perfil"]==3){
		 ?>
				Unidade:
				<?php

				$SQL = pg_query("SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade");
		 if(isset($limitaunidade)){
			 $sql .= $limitaunidade;
		}
		 $sql .= " GROUP BY unidades.codunidade, unidades.nomeunidade,unidades.endereco";
				?>

				<select name="codunidade" style="font-size:8pt">
				<option value="-1">:: Unidade ::</option>
				<?php
					while ($rs = pg_fetch_row($SQL)){
					if($rs[0] == $_REQUEST["codunidade"])
					echo '<option value="'.$rs[0].'" selected>';
					else
					echo '<option value="'.$rs[0].'">';
					echo $rs[1]."</option>";
					}
				?>
				</select>
				<?php
				}?>
				&nbsp;<input type="submit" class="btnMenu" name="ok" value=" OK " onclick="javascript:return RelData(this.form);">
				</td>
	</tr>
	<tr>
	<td valign="top" height="100%" width="100%">
		<table border="0" width="100%">
			<tr><td class="titTable" colspan="4">Relat&oacute;rio por Impressora</td></tr>
			<tr>
				<th <?php
		if($_GET['flg']=='I'){
				if($TIPOORDENA=='DESC'){
					echo ' class ="ordenadesc"><a href="'.$_SERVER['PHP_SELF'].'?flg=I&ord=ASC'.$ADICIONARGET.'">';
				}
				else{
				echo ' class ="ordena"><a href="'.$_SERVER['PHP_SELF'].'?flg=I&ord=DESC'.$ADICIONARGET.'">';
			}
			}else{
				echo'>
<a href="'.$_SERVER['PHP_SELF'].'?flg=I&ord=ASC'.$ADICIONARGET.'">';
			}
			?>
				&nbsp;Impressora&nbsp;</a></th>

				<th <?php
		if($_GET['flg']=='P'){
				if($TIPOORDENA=='DESC'){
					echo ' class ="ordenadesc"><a href="'.$_SERVER['PHP_SELF'].'?flg=P&ord=ASC'.$ADICIONARGET.'">';
				}else{
				echo ' class ="ordena"><a href="'.$_SERVER['PHP_SELF'].'?flg=P&ord=DESC'.$ADICIONARGET.'">';
			}
			}else{
				echo '><a href="'.$_SERVER['PHP_SELF'].'?flg=P&ord=ASC'.$ADICIONARGET.'">';
			}
			?>
				&nbsp;P&aacute;ginas Impressas&nbsp;</a></th>
				<th <?php
		if($_GET['flg']=='U'){
				if($TIPOORDENA=='DESC'){
					echo ' class ="ordenadesc"><a href="'.$_SERVER['PHP_SELF'].'?flg=U&ord=ASC'.$ADICIONARGET.'">';
				}
				else{
					echo ' class ="ordena"><a href="'.$_SERVER['PHP_SELF'].'?flg=U&ord=DESC'.$ADICIONARGET.'">';
				}
			}else{
				echo '><a href="'.$_SERVER['PHP_SELF'].'?flg=U&ord=ASC'.$ADICIONARGET.'">';
			}
			?>
				&nbsp;Unidade&nbsp;</a></th>
			<th <?php
		if($_GET['flg']=='C'){
				if($TIPOORDENA=='DESC'){
					echo ' class ="ordenadesc"><a href="'.$_SERVER['PHP_SELF'].'?flg=C&ord=ASC'.$ADICIONARGET.'">';
				}
				else{
					echo ' class ="ordena"><a href="'.$_SERVER['PHP_SELF'].'?flg=C&ord=DESC'.$ADICIONARGET.'">';
				}
			}else{
				echo '><a href="'.$_SERVER['PHP_SELF'].'?flg=C&ord=ASC'.$ADICIONARGET.'">';
			}
			?>
				&nbsp;Custo&nbsp;</a></th>
			</tr>
			<?php
			if(!isset($CodUnit)) $CodUnit = "";
      if(!isset($Data)) $Data = "";
      $SQL = pg_query("SELECT unidades.nomeunidade, printers.printername, SUM(jobhistory.jobsize) AS paginas, printers.id, SUM(jobhistory.jobprice) AS preco FROM jobhistory, printers, unidades, users WHERE users.id = jobhistory.userid and jobhistory.printerid = printers.id AND unidades.codunidade= printers.codunidade ".$Data."   ".$CodUnit."  GROUP BY printers.printername, printers.id, unidades.nomeunidade $ORDENA");
      if(!isset($SomaPag))
        $SomaPag = 0;
      if(!isset($Soma))
        $Soma = 0;
			while ($rs = pg_fetch_array($SQL)){
				$SomaPag = $SomaPag + $rs['paginas'];
				$Soma = $Soma + $rs['preco'];
			}

			$paginacao = new Paginar(pg_num_rows($SQL),1);

			if(isset($_POST['page']) && $_POST['page'] >=0){
				$paginacao->setaPaginaAtual($_POST['page']);
			}

			$LIMIT = $paginacao->retornaLimites();
			$SQL = pg_query("SELECT unidades.nomeunidade, printers.printername, SUM(jobhistory.jobsize) AS paginas, printers.id, SUM(jobhistory.jobprice) AS preco FROM jobhistory, printers, unidades, users WHERE users.id = jobhistory.userid and jobhistory.printerid = printers.id AND unidades.codunidade= printers.codunidade ".$Data."  ".$CodUnit." GROUP BY printers.printername, printers.id, unidades.nomeunidade $ORDENA $LIMIT");

			$Cor = "";
			while ($rs = pg_fetch_array($SQL))
			{
				if ($Cor == "#FFFFFF") $Cor = "#ECF2F8"; else $Cor = "#FFFFFF";
				echo '<tr bgcolor="'.$Cor.'" title="Detalhar '.$rs['printername'].'" onMouseOver="javascript: trOver(this);" onMouseOut="javascript: trOut(this);" style="cursor: pointer" onClick="javascript:show_window(\'detalheimp.php?id='.$rs['id'].'\',\'Impressora\',370,300);">
<td>&nbsp;'.$rs['printername'].'&nbsp;</td>
<td align="left">&nbsp;'.number_format($rs['paginas'],0,',','.').'&nbsp;</td>
<td align="left">&nbsp;'.$rs[0].'&nbsp;</td>
<td align="right">R$&nbsp;'.number_format($rs['preco'],2,',','.').'&nbsp;</td>
</tr>';
			}
			?>
			<tr class="titTable">
				<td>&nbsp;</td>
				<td align="left">&nbsp;<?php echo number_format($SomaPag,0,',','.');?>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">&nbsp;R$&nbsp;<?php echo number_format($Soma,2,',','.');?>&nbsp;</td>
			</tr>
		</table>
			<tr><td colspan="4" align="center" class="LinkPaginacao"><?php echo $paginacao->retornaBarraPaginas($_SERVER['PHP_SELF'].$ADICIONANAPAGINACAO); ?>
	</td></tr>
	</td></tr>
</table>
</form>
<?php
include('inc/foot.php');
?>