<?php
include("inc/pagina.php");
include("inc/conn.php");
$pagina = new Pagina();
$pagina->comeca("Gr&aacute;fico de Impress&otilde;es P&aacute;ginas por M&ecirc;s", $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"],$_POST, $_GET);

$salvar = (isset($_POST["salvar"]))?$_POST["salvar"]:NULL;
if(!$salvar){
	$pagina->head();
}

$impressao = (isset($_GET["impressao"]))?$_GET["impressao"]:NULL;

if($impressao){
	$_POST = unserialize($_SESSION["postit"]);
}
if(isset($retval))
  $retval.= "<fieldset>";
else
  $retval = '<fieldset>
<legend accesskey="G">&nbsp;<font size="2"><b>Gr&aacute;fico de Impress&otilde;es Por Per&iacute;odo</b></font>&nbsp;</legend>
<form name="frm_data" method="post" action="'.$_SERVER["PHP_SELF"].'">
<table border="0" width="100%" cellpadding="0" cellspacing="0">';
$dataI = (isset($_POST["dtinicial"]))?$_POST["dtinicial"]:"01/".date("m/Y",time());
$dataF = (isset($_POST["dtfinal"]))?$_POST["dtfinal"]:date("d/m/Y",time());
$codunidade = (isset($_POST["codunidade"]))?$_POST["codunidade"]:NULL;
$retval .= '<tr>
<td align="left">&nbsp;Data Inicial:&nbsp;
<input type="text" size="10" maxlength="10" name="dtinicial" onblur="javascript:ValidaCampo(this.form.name,this.name,\'Data Inicial\');"  onchange="javascript:FormataData(this.form.name,this.name,event);" value="'.$dataI.'">
&nbsp;Data Final:&nbsp;
<input type="text" size="10" maxlength="10" name="dtfinal" onblur="javascript:ValidaCampo(this.form.name,this.name,\'Data Final\');" onchange="javascript:FormataData(this.form.name,this.name,event);" value="'.$dataF.'">';

if($_SESSION["perfil"]==2 || $_SESSION["perfil"]==3){
	$retval .='&nbsp;Unidade:&nbsp;';
	$SQLU = pg_query("SELECT codunidade, nomeunidade FROM unidades ORDER BY nomeunidade");
	$retval .='<select name="codunidade" style="font-size:8pt">
<option value="-1">:: Todas Unidades ::</option>';
				while ($rs = pg_fetch_row($SQLU)){
					if($rs[0] == $codunidade)
					$retval .= '<option value="'.$rs[0].'" selected>';
					else
					$retval .= '<option value="'.$rs[0].'">';
					$retval .= $rs[1]."</option>";
				}
	$retval .='</select>&nbsp';
}
else if($_SESSION["perfil"]==4){
	$retval .='&nbsp;Unidade:&nbsp;';
	$SQLU = pg_query("SELECT distinct unidades.codunidade, nomeunidade FROM unidades, users,
                    jobhistory, printers
                    WHERE printers.codunidade = unidades.codunidade AND
                    users.id = jobhistory.userid and jobhistory.printerid = printers.id AND users.codunidade = $_SESSION[uni]
                    ORDER BY nomeunidade");
	$retval .='<select name="codunidade" style="font-size:8pt">';
	$retval .='<option value="-1">:: Todas Unidades ::</option>';
				while ($rs = pg_fetch_row($SQLU)){
					if($rs[0] == $codunidade)
					$retval .= '<option value="'.$rs[0].'" selected>';
					else
					$retval .= '<option value="'.$rs[0].'">';
					$retval .= $rs[1]."</option>";
				}
	$retval .='</select>&nbsp';
}

else {
	$retval .='&nbsp;Unidade:&nbsp;';
	$SQLU = pg_query("SELECT codunidade, nomeunidade FROM unidades WHERE codunidade=".$_SESSION["uni"]);
	$rs = pg_fetch_row($SQLU);
	$_POST["codunidade"]=$_SESSION["uni"];
	$retval .='&nbsp;'.$rs[1].'&nbsp;';
}

$retval.='<input type="submit" class="btnMenu" name="ok" value=" OK " onclick="javascript:return RelGrafico(this.form);">
		</td>';
$retval .=  '</tr>
</table>
<input type="hidden" name="impressao" value="0">
<input type="hidden" name="salvar" value="0">
<input type="hidden" name="page" value="0">
</form>
</fieldset>
<br >';
$pagina->geraSQL("WHERE ");

if($salvar==0){
	echo $pagina->header; // Imprime header.
	//Conteúdo
	echo $pagina->body; // Imprime header.
	//echo $pagina->cab; // Imprime header.
	if(!$impressao){
	echo $retval; //Imprime campos de consulta
	}
	//Rodapé
	if(isset($_POST["dtinicial"]))
	echo "<center><img src='"."colunaano.php"."?datai=".$_POST["dtinicial"]."&dataf=".$_POST["dtfinal"]."&codunidade=".((isset($_POST["codunidade"]))?$_POST["codunidade"]:-1)."' /></center>";
	echo $pagina->barraPagina;//Imprime paginação
	echo "<br />";
	$pagina->foot();
	echo $pagina->footer;//Imprime paginação
}else {
	if(isset($_POST["dtinicial"])){
		header("Content-Disposition: attachment; filename=grafico.png");
		header("location: colunaano.php"."?datai=".$_POST["dtinicial"]."&dataf=".$_POST["dtfinal"]."&codunidade=".((isset($_POST["codunidade"]))?$_POST["codunidade"]:-1)."&salvar=".$salvar."' >");
	}
}
?>
