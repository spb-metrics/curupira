<?php
include('inc/conn.php');

//Dados do formulário
		$CodUnidade = "";
if ($_GET["id"] != "")
	$CodUnidade = $_GET["id"];

if ($CodUnidade != "")
{
	$SQL = "DELETE FROM unidades WHERE codunidade = ".$CodUnidade;
	$ExecSQL = pg_query($SQL) or die ("Erro ao tentar excluir uma Unidade");
}
?>

		<script language="JavaScript">
		alert('Unidade (e todos os dados relacionados a ela) foram excluídos com sucesso.');
	window.location.href = 'cad_unidade.php';
</script>
