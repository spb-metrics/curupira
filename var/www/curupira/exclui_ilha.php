<?php
include('inc/conn.php');

//Dados do formul�rio
		$CodIlha = "";
if ($_GET["id"] != "")
	$CodIlha = $_GET["id"];

if ($CodIlha != "")
{
	$SQL = pg_query("SELECT * FROM unidades WHERE codilha = ".$CodIlha);
	$rs = pg_fetch_row($SQL);
	if (!$rs)
	{
		$SQL = "DELETE FROM ilhas WHERE codilha = ".$CodIlha;
		$ExecSQL = pg_query($SQL) or die ("Erro ao tentar excluir Dados de uma Ilha");
	}
	else
	{
		?>
				<script language="JavaScript">
				alert('N�o � poss�vel excluir essa ilha, pois existem registros que a utilizam.');
		window.location.href = 'cad_ilha.php';
		</script>
				<?
	}
}
?>
		<script language="JavaScript">
		alert('Ilha (e todos os dados relacionados a ela) foram exclu�dos com sucesso.');
	window.location.href = 'cad_ilha.php';
</script>
