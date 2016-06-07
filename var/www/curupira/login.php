<?php
	session_start();
	include('inc/conn.php');
	include('script/function_infos.php');
	if ($_SERVER["REMOTE_USER"] == ""){
		echo "Você não tem permiss&atilde;o de utilizar esta p&aacute;gina.";
		exit(0);
	}

	$_SESSION["matricula"]=trata_login($_SERVER["REMOTE_USER"],"matricula");
	$_SESSION["nome"]=trata_login($_SERVER["REMOTE_USER"],"fullname");
	$_SESSION["email"]=trata_login($_SERVER["REMOTE_USER"],"email");
	$_SESSION["telefone"]=trata_login($_SERVER["REMOTE_USER"],"telefone");
	$_SESSION["unidade"]=trata_login($_SERVER["REMOTE_USER"],"unidade");
	$_SESSION["departamento"]=trata_login($_SERVER["REMOTE_USER"],"departamento");
	$_SESSION["grupo"]=trata_login($_SERVER["REMOTE_USER"],"grupo");

	$SQL = pg_query("SELECT groups.id FROM groups, users, groupsmembers WHERE users.id=groupsmembers.userid and groupsmembers.groupid=groups.id and username='".$_SESSION["matricula"]."'");
	$rs = pg_fetch_row($SQL);
	if (is_null($rs))
		$_SESSION["perfil"] = 6;
	else
		$_SESSION["perfil"] = $rs[0];
	header("location: valida_login.php");
?>
