<?php
	Session_start();
	include('inc/conn.php');
	include('inc/valida_session.php');

	if (isset($_POST["codilha"]) && $_POST["codilha"] != "")
		$CodIlha = $_POST["codilha"];
	else
		$CodIlha = "null";

	$NomeIlha = $_POST["ilha"];
	if ($CodIlha == "null")
		$SQL = pg_query("INSERT INTO ilhas(codilha, nomeilha) VALUES (nextval('ilhas_codilha_seq'), '".$NomeIlha."')");
	else
		$SQL = pg_query("UPDATE ilhas SET nomeilha = '".$NomeIlha."' WHERE codilha = ".$CodIlha);
	?>

			<script language="JavaScript">
			alert('Ilha incluída/alterada com sucesso.');
	window.location.href = 'cad_ilha.php';
	</script>
