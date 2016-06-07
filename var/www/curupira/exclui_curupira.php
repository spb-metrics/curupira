<?php
include('inc/conn.php');

//Dados do formulário
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
		alert('Link foi excluído com sucesso.');
	window.location.href = 'cad_curupira.php';
</script>
