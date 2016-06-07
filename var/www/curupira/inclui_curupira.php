<?php
include('inc/conn.php');

$tabela ="tb_link";
if (isset($_POST["codunidade"]) && $_POST["codunidade"] != "")
	$CodUnidade = $_POST["codunidade"];
else
	$CodUnidade = "null";

if ($CodUnidade == "null")
	$SQL = "INSERT INTO $tabela (Ds_Link, Link, Co_Filial, End_Predio, No_Servidor, Ip_Servidor) VALUES ('".$_POST["Ds_Link"]."', '".$_POST["Link"]."', '".$_POST["Co_Filial"]."', '".$_POST["End_Predio"]."', '".$_POST["No_Servidor"]."', '".$_POST["Ip_Servidor"]."')";
else
	$SQL = "UPDATE $tabela SET ds_link = '".$_POST["Ds_Link"]."', link = '".$_POST["Link"]."', co_filial = '".$_POST["Co_Filial"]."', End_Predio='".$_POST["End_Predio"]."' , No_Servidor='".$_POST["No_Servidor"]."', Ip_Servidor='".$_POST["Ip_Servidor"]."' WHERE Id = ".$CodUnidade;

$SQLUser = pg_query($SQL) or die ("Erro ao tentar incluir/alterar uma Unidade. $SQL");

?>

		<script language="JavaScript">
		alert('Unidade incluída/alterada com sucesso. <?php $_POST["Ds_Link"];?>');
	window.location.href = 'cad_curupira.php';
	</script>
