<?php
include('inc/conn.php');

//Dados do formul�rio
		$CodUnidade = "";
if ($_GET["id"] != "")
	$CodUnidade = $_GET["id"];

$tabela = "tb_link";

if ($CodUnidade != "")
{
	$SQL = "DELETE FROM $tabela WHERE Id = ".$CodUnidade;
	$ExecSQL = pg_query($SQL) or die ("Erro ao tentar excluir um Link!");
}
?>

		<script language="JavaScript">
		alert('Link foi exclu�do com sucesso.');
	window.location.href = 'cad_curupira.php';
</script>
