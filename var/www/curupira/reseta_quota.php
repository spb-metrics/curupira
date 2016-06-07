<?php
Session_start();
include('inc/conn.php');
include('inc/valida_session.php');

//Dados do formulario
		if ($_POST["usuario"] != "")
		$Usuario = $_POST["usuario"];
else
	$Usuario = "null";

if ($Usuario == "null")
{
}
else
{
	exec("/usr/bin/edpykota --reset $Usuario");
}

?>
		<script language="JavaScript">
		alert('Quota do Usuário restaurada com sucesso.');
	window.location.href = 'cad_usuario.php';
</script>
