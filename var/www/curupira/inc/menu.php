<?php

$menu=
'<script language="javascript" src="script/menu.js" type="text/javascript"></script>
<table border="0" height="26" cellpadding="1" cellspacing="1" width="100%" background="imagens/barra_prata.gif">
<tr>
	<td width="100%">';
function getNumero($nome1, $nome2){
	$a = substr($nome1,0,strlen($nome2));
	if ($a == $nome2)
		return 2;
	return "";
}
if($_SESSION["perfil"]!=6){
  if(!isset($enderecoclique))
    $enderecoclique = NULL;
	$menu.='<input type="button" class="btnMenu'.getNumero($enderecoclique,'/relunidades.php').'" value="Unidades" onClick="executaMenu(\'relunidades.php\')">';
}
$menu.='<input type="button" class="btnMenu'.getNumero($enderecoclique,'/relimpressoras.php').'" value="Impressoras" onClick="executaMenu(\'relimpressoras.php\')">';
if($_SESSION["perfil"]!=6){
$menu.='<input type="button" class="btnMenu'.getNumero($enderecoclique,'/detalheimpressora.php').'" value="Usu&aacute;rios" onClick="executaMenu(\'detalheimpressora.php\')">';
}
$menu.='<input type="button" class="btnMenu'.getNumero($enderecoclique,'/impressoes.php').'" value="Impress&otilde;es" onClick="executaMenu(\'impressoes.php\')">';
	if($_SESSION["perfil"]!=6 && $_SESSION["perfil"]!=3){

	$menu.=
	'<select class="btnMenu" name="cadastro" onChange="executaMenu(this.value)">
		<option value="-1">:: Cadastros ::</option>';
	if($_SESSION["perfil"] == 2){
		$menu.='
		<option value="cad_grupo.php">Grupos</option>
		<option value="cad_ilha.php">Ilhas de Impress&atilde;o</option>
		<option value="cad_impressora.php">Impressoras</option>
		<option value="cad_unidade.php">Unidades</option>
		<option value="cad_curupira.php">Links</option>';
		}
		$menu.='<option value="cad_usuario.php">Usu&aacute;rios</option>
	</select>';
	}
	if($_SESSION["perfil"] != 6)
	{
	$menu.='<select class="btnMenu" name="Relat&oacute;rios" onChange="executaMenu(this.value)">
		<option value="-1" selected>:: Relat&oacute;rios ::</option>
		<optgroup label="Relat&oacute;rios">
			<option value="rel_impressoras.php">Impressoras</option>
			<option value="relatorio_usuario.php">Usu&aacute;rios</option>
			<option value="impressoesu.php">Impress&otilde;es</option>';
    if($_SESSION['perfil'] !=4){
			$menu.='<option value="rel_gerador.php">Gerador</option>';
    }
		$menu.='</optgroup>';
    if($_SESSION['perfil'] !=4){
  	$menu.='<optgroup label="Gr&aacute;ficos">
			<option value="grahora.php">P&aacute;g/Hora</option>
			<option value="grappi.php">P&aacute;g/Unid</option>
			<option value="grames.php">P&aacute;g/Per&iacute;odo</option>
		</optgroup>';
    }
		if($_SESSION["perfil"]==2)
		{
			$menu.='<optgroup label="Links">';
			$menu.='<option value="rel_links.php">Acesso</option>
			</optgroup>';
		}
		$menu.='</select>';
	}
	$menu.='</td>
	<td nowrap="true">
		<a href="'.$_SERVER["PHP_SELF"].'"><img src="imagens/Atualizar.gif" border="0" alt="Atualizar"></a>&nbsp;&nbsp;
		<a href="#" onclick="window.open(\'help/Ajuda do Curupira.htm\', \'\', \'top=0,left=0,width=660px,height=400px,toolbar=no,scrollbars=yes,resizable=yes\')"><img src="imagens/Ajuda.gif" border="0"></a>&nbsp;&nbsp;';



		if(isset($opcaoParaPaginasQueNaoImprimem) && $opcaoParaPaginasQueNaoImprimem){
			$menu.="<input type=\"image\" src=\"imagens/Imprimir.gif\" border=\"0\" value=\"Impress&atilde;o\" onClick=\"javascript:show_window('".(ereg("\?",$this->end)?$this->end."&impressao=1":$this->end."?impressao=1")."','_blank',800,600);\">&nbsp;&nbsp;&nbsp";
		}else{
			$menu.='<a href="javascript:print();"><img src="imagens/Imprimir.gif" border="0" alt="Imprimir"></a>&nbsp;&nbsp;';
		}
		if(isset($opcaoParaPaginasQueNaoImprimem) && $opcaoParaPaginasQueNaoImprimem){
			$menu.="<input type=\"image\" src=\"imagens/Salvar.gif\" value=\"Salvar\" onClick=\"javascript: modificaSalvar(1);\">&nbsp;";
		}else {
			$menu.='<a href="javascript:print();"><img src="imagens/Salvar.gif" border="0" alt="Salvar"></a>&nbsp;</td>';
		}
$menu.='</tr>
</table>';

?>
