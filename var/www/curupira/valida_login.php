<?php
	Session_start();
	include('inc/conn.php');

	$Login = $_SESSION["matricula"];
	$SQL = pg_query('SELECT id, username, codunidade, nome FROM users WHERE username = \''.$Login.'\';');
	$rs = pg_fetch_row($SQL);
	if (!empty($rs[0]))
	{
		if( ( strtolower($rs[3]) == 'desconhecido' )&& ( ! isset($_SESSION["revalidate"])) ){
			include_once 'pega_info_usuario.php';
			$_SESSION["revalidate"] = false;
				include_once "pega_info_usuario.php";
				Atualiza_Informacoes_Usuario($_SESSION["matricula"]);
				header("location: valida_login.php");
		} else {
			$_SESSION["userid"] = $rs[0];
			$_SESSION["login"] = $rs[1];
			$_SESSION["uni"] = $rs[2];
			if($_SESSION["perfil"] == 6) header("location: relimpressoras.php");
			else header("location: relunidades.php");
		}
	}else{
		echo "<script language=\"javascript\">
 alert('Usuário não encontrado: ".$Login.", Faça uma primeira impressão para ser cadastrado no sistema.');
</script>";
	}

?>
