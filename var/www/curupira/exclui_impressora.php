<?php
include('inc/conn.php');

//Dados do formulário
		$CodImpressora = "";
if ($_GET["id"] != "")
	$CodImpressora = $_GET["id"];

//Excluindo impressora do Banco de Dados
		if ($CodImpressora != "")
{
	$NomeImpressoraSQL = "SELECT printername FROM printers WHERE id='" . $CodImpressora . "';";
	$UserQuotaSQL = "DELETE FROM userpquota WHERE printerid = ".$CodImpressora;
	$GrupoQuotaSQL = "DELETE FROM grouppquota WHERE printerid = ".$CodImpressora;
	$CoefficientsSQL = "DELETE FROM coefficients WHERE printerid = ".$CodImpressora;
	$GroupsSQL = "DELETE FROM printergroupsmembers WHERE printerid = ".$CodImpressora;
	$JobsSQL = "DELETE FROM jobhistory WHERE printerid = ".$CodImpressora;
	$PrinterSQL = "DELETE FROM printers WHERE id = ".$CodImpressora;

	$resultado_nome = pg_query($NomeImpressoraSQL) or die ("Erro ao tentar pegar nome da Impressora");
	$linha = pg_fetch_row($resultado_nome) or die ("Erro, nenhum nome para esse id de impressora");
	if(isset($linha[0]) && $linha != NULL){
		$NomeImpressora = $linha[0];
	}else{
		die ("ERRO!!! Nome da impressora nulo!!!");
	}
	$ExecSQL4 = pg_query($UserQuotaSQL) or die ("Erro ao tentar excluir Dados de uma Impressora");
	$ExecSQL3 = pg_query($GrupoQuotaSQL) or die ("Erro ao tentar excluir Dados de uma Impressora");
	$ExecSQL2 = pg_query($CoefficientsSQL) or die ("Erro ao tentar excluir Dados de uma Impressora");
	$ExecSQL1 = pg_query($GroupsSQL) or die ("Erro ao tentar excluir um Grupo Impressora");
	$ExecSQL5 = pg_query($JobsSQL) or die ("Erro ao tentar ecluir os trabalhos da impressora.");
	$ExecSQL = pg_query($PrinterSQL) or die ("Erro ao tentar excluir uma Impressora");

}

//Excluindo impressora do controle do pykota
		exec("pkprinters --del $NomeImpressora");
?>

		<script language="JavaScript">
		alert('Impressora (e todos os dados relacionados a ela) foram excluídos com sucesso.');
	window.location.href = 'cad_impressora.php';
</script>
