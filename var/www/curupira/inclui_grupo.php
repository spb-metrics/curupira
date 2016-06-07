<?php
include('inc/conn.php');

if ($_POST["codgrupo"] != "")
	$CodGrupo = $_POST["codgrupo"];
else
	$CodGrupo = "null";

$NomeGrupo = $_POST["grupo"];
$Descricao = $_POST["descricao"];

if ($CodGrupo == "null")
	$SQL = "INSERT INTO groups VALUES (nextval('groups_id_seq'), '$NomeGrupo', '$Descricao', 'quota')";
else
	$SQL = "UPDATE groups SET groupname = '".$NomeGrupo."', description = '".$Descricao."' WHERE id = ".$CodGrupo;

$GrupoSQL = pg_query($SQL) or die ("Erro ao tentar incluir/alterar um Grupo.");
?>
		<script language="JavaScript">
		alert('Grupo incluído/alterado com sucesso.');
	window.location.href = 'cad_grupo.php';
</script>
